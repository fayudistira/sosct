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
        'user_id',
        'full_name',
        'nickname',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'citizen_id',
        'phone',
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
        'position',
        'photo',
        'documents'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $validationRules = [
        'user_id' => 'required|is_natural_no_zero|is_unique[profiles.user_id,id,{id}]',
        'full_name' => 'required|max_length[100]',
        'gender' => 'required|in_list[Male,Female]',
        'place_of_birth' => 'required|max_length[100]',
        'date_of_birth' => 'required|valid_date',
        'religion' => 'required|max_length[50]',
        'phone' => 'required|max_length[15]',
        'street_address' => 'required',
        'district' => 'required|max_length[100]',
        'regency' => 'required|max_length[100]',
        'province' => 'required|max_length[100]',
        'emergency_contact_name' => 'required|max_length[100]',
        'emergency_contact_phone' => 'required|max_length[15]',
        'emergency_contact_relation' => 'required|max_length[50]',
        'father_name' => 'required|max_length[100]',
        'mother_name' => 'required|max_length[100]'
    ];

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
     * Upload photo file
     */
    public function uploadPhoto($file)
    {
        if (!$file->isValid()) {
            return false;
        }
        
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return false;
        }
        
        if ($file->getSize() > 2048 * 1024) {
            return false;
        }
        
        $uploadPath = WRITEPATH . 'uploads/profiles/photos/';
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        $newName = $file->getRandomName();
        
        if ($file->move($uploadPath, $newName)) {
            return 'profiles/photos/' . $newName;
        }
        
        return false;
    }

    /**
     * Upload document file
     */
    public function uploadDocument($file)
    {
        if (!$file->isValid()) {
            return false;
        }
        
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return false;
        }
        
        if ($file->getSize() > 5120 * 1024) {
            return false;
        }
        
        $uploadPath = WRITEPATH . 'uploads/profiles/documents/';
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        $newName = $file->getRandomName();
        
        if ($file->move($uploadPath, $newName)) {
            return 'profiles/documents/' . $newName;
        }
        
        return false;
    }
}
