<?php

namespace Modules\Account\Models;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    protected $table = 'profiles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'profile_number',
        'user_id',
        'full_name',
        'nickname',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'citizen_id',
        'phone',
        'email',
        'street_address',
        'district',
        'regency',
        'province',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'father_name',
        'mother_name',

        'photo',
        'documents'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'id' => 'permit_empty|is_natural_no_zero',
        'profile_number' => 'permit_empty|is_unique[profiles.profile_number,id,{id}]',
        'user_id' => 'permit_empty|is_natural_no_zero|is_unique[profiles.user_id,id,{id}]',
        'full_name' => 'required|max_length[100]',
        'email' => 'required|valid_email|is_unique[profiles.email,id,{id}]',
        'gender' => 'required|in_list[Male,Female]',
        'place_of_birth' => 'required|max_length[100]',
        'date_of_birth' => 'required|valid_date',
        'religion' => 'required|max_length[50]',
        'phone' => 'required|max_length[15]',
        'street_address' => 'required',
        'district' => 'required|max_length[100]',
        'regency' => 'required|max_length[100]',
        'province' => 'required|max_length[100]',
        'emergency_contact_name' => 'permit_empty|max_length[100]',
        'emergency_contact_phone' => 'permit_empty|max_length[15]',
        'emergency_contact_relation' => 'permit_empty|max_length[50]',
        'father_name' => 'permit_empty|max_length[100]',
        'mother_name' => 'permit_empty|max_length[100]'
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email is already registered.'
        ],
        'profile_number' => [
            'is_unique' => 'This profile number already exists.'
        ]
    ];

    /**
     * Generate unique profile number
     * Format: PROF-YYYY-NNNN (e.g., PROF-2026-0001)
     * 
     * @return string
     */
    public function generateProfileNumber(): string
    {
        $year = date('Y');
        $prefix = "PROF-{$year}-";

        // Get the last profile number for current year
        $lastRecord = $this->like('profile_number', $prefix)
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastRecord) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastRecord['profile_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            // First profile of the year
            $newNumber = 1;
        }

        // Format with leading zeros (4 digits)
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get profile by user ID
     */
    public function getByUserId(int $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }

    /**
     * Check if user has profile
     */
    public function hasProfile(int $userId): bool
    {
        return $this->where('user_id', $userId)->countAllResults() > 0;
    }

    /**
     * Upload photo file and convert to WebP
     * 
     * @param $file The uploaded file
     * @param string|null $customName Custom name prefix (e.g., registration number)
     * @return string|false Relative path to uploaded file or false on failure
     */
    public function uploadPhoto($file, $customName = null)
    {
        if (!$file->isValid()) {
            return false;
        }

        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return false;
        }

        if ($file->getSize() > 2048 * 1024) {
            return false;
        }

        $uploadPath = FCPATH . 'uploads/profiles/photos/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate filename based on custom name or random
        if ($customName) {
            // Sanitize the custom name
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', $customName);
            $webpName = $safeName . '.webp';
        } else {
            $newName = $file->getRandomName();
            $webpName = pathinfo($newName, PATHINFO_FILENAME) . '.webp';
        }

        // Move file temporarily for conversion
        $tempFileName = 'temp_' . uniqid();
        $tempPath = $uploadPath . $tempFileName;
        if (!$file->move($uploadPath, $tempFileName, true)) {
            return false;
        }

        // Convert to WebP
        $webpPath = $uploadPath . $webpName;
        if ($this->convertToWebP($tempPath, $webpPath)) {
            // Delete original file after successful conversion
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            return 'profiles/photos/' . $webpName;
        }

        // If conversion fails, keep the original file with custom name
        $extension = $file->getExtension();
        $finalName = $customName ? $safeName . '.' . $extension : $file->getRandomName();
        if (file_exists($tempPath)) {
            rename($tempPath, $uploadPath . $finalName);
        }
        return 'profiles/photos/' . $finalName;
    }

    /**
     * Convert image to WebP format
     * 
     * @param string $sourcePath Path to source image
     * @param string $destPath Path to save WebP image
     * @param int $quality WebP quality (0-100)
     * @return bool
     */
    private function convertToWebP($sourcePath, $destPath, $quality = 80)
    {
        // Check if GD or Imagick is available
        if (!extension_loaded('gd') && !extension_loaded('imagick')) {
            log_message('warning', 'Neither GD nor Imagick extension is available for WebP conversion');
            return false;
        }

        try {
            // Get image info
            $imageInfo = @getimagesize($sourcePath);
            if (!$imageInfo) {
                log_message('error', 'Unable to get image info for: ' . $sourcePath);
                return false;
            }

            $mimeType = $imageInfo['mime'];
            $width = $imageInfo[0];
            $height = $imageInfo[1];

            // Create image resource based on mime type
            $image = null;
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = @imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $image = @imagecreatefrompng($sourcePath);
                    // Handle PNG transparency
                    if ($image) {
                        imagepalettetotruecolor($image);
                        imagealphablending($image, true);
                        imagesavealpha($image, true);
                    }
                    break;
                case 'image/webp':
                    // Already WebP, just copy
                    if ($sourcePath !== $destPath) {
                        copy($sourcePath, $destPath);
                    }
                    return true;
                default:
                    log_message('error', 'Unsupported image type: ' . $mimeType);
                    return false;
            }

            if (!$image) {
                log_message('error', 'Failed to create image resource from: ' . $sourcePath);
                return false;
            }

            // Resize if too large (max 1200px width for profile photos)
            $maxWidth = 1200;
            if ($width > $maxWidth) {
                $newHeight = (int) ($height * ($maxWidth / $width));
                $resized = imagecreatetruecolor($maxWidth, $newHeight);
                
                // Preserve transparency for PNG
                if ($mimeType === 'image/png') {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                    imagefilledrectangle($resized, 0, 0, $maxWidth, $newHeight, $transparent);
                }
                
                imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
                imagedestroy($image);
                $image = $resized;
            }

            // Save as WebP
            $result = imagewebp($image, $destPath, $quality);
            imagedestroy($image);

            if (!$result) {
                log_message('error', 'Failed to save WebP image to: ' . $destPath);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'WebP conversion error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Upload document file
     * 
     * @param $file The uploaded file
     * @param string|null $customName Custom name prefix (e.g., registration number)
     * @param string|null $suffix Optional suffix for multiple documents (e.g., '_id_card', '_transcript')
     * @return string|false Relative path to uploaded file or false on failure
     */
    public function uploadDocument($file, $customName = null, $suffix = null)
    {
        if (!$file->isValid()) {
            return false;
        }

        $allowedTypes = [
            'application/pdf',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return false;
        }

        if ($file->getSize() > 5120 * 1024) {
            return false;
        }

        $uploadPath = FCPATH . 'uploads/profiles/documents/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // For image documents, convert to WebP
        $mimeType = $file->getMimeType();
        if (in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])) {
            // Generate filename based on custom name or random
            if ($customName) {
                $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', $customName);
                $docName = $safeName . ($suffix ? $suffix : '');
                $webpName = $docName . '.webp';
            } else {
                $newName = $file->getRandomName();
                $webpName = pathinfo($newName, PATHINFO_FILENAME) . '.webp';
            }
            
            // Move file temporarily for conversion
            $tempFileName = 'temp_' . uniqid();
            $tempPath = $uploadPath . $tempFileName;
            if (!$file->move($uploadPath, $tempFileName, true)) {
                return false;
            }

            // Convert to WebP
            $webpPath = $uploadPath . $webpName;
            if ($this->convertToWebP($tempPath, $webpPath)) {
                // Delete original file after successful conversion
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                return 'profiles/documents/' . $webpName;
            }

            // If conversion fails, keep the original file with custom name
            $extension = $file->getExtension();
            $finalName = $customName ? $docName . '.' . $extension : $file->getRandomName();
            if (file_exists($tempPath)) {
                rename($tempPath, $uploadPath . $finalName);
            }
            return 'profiles/documents/' . $finalName;
        }

        // For non-image documents, use custom name or random
        if ($customName) {
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', $customName);
            $extension = $file->getExtension();
            $finalName = $safeName . ($suffix ? $suffix : '') . '.' . $extension;
        } else {
            $finalName = $file->getRandomName();
        }
        
        if ($file->move($uploadPath, $finalName)) {
            return 'profiles/documents/' . $finalName;
        }

        return false;
    }
}
