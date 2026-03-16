<?php

namespace Modules\Frontend\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Admission\Models\AdmissionModel;
use Modules\Program\Models\ProgramModel;
use Modules\Test\Models\TestRegistrationModel;

class FrontendApiController extends ResourceController
{
    protected $format = 'json';

    /**
     * Get recent admissions for popup notification
     * Returns last 5 admissions with program and applicant info
     * 
     * GET /frontend/api/recent-admissions
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function recentAdmissions()
    {
        $admissionModel = new AdmissionModel();
        $db = \Config\Database::connect();

        // Get last 5 admissions with program and profile info
        $admissions = $db->table('admissions a')
            ->select('a.registration_number, a.application_date, a.status, p.full_name, pr.title as program_title, pr.category')
            ->join('profiles p', 'p.id = a.profile_id')
            ->join('programs pr', 'pr.id = a.program_id')
            ->orderBy('a.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // Format the data for display
        $formattedAdmissions = array_map(function ($admission) {
            // Mask the name for privacy (show first name only)
            $nameParts = explode(' ', $admission['full_name']);
            $maskedName = $nameParts[0];
            if (count($nameParts) > 1) {
                $maskedName .= ' ' . substr($nameParts[1], 0, 1) . '.';
            }

            // Calculate time ago
            $timeAgo = $this->timeAgo($admission['application_date']);

            return [
                'name' => $maskedName,
                'program' => $admission['program_title'],
                'category' => $admission['category'],
                'time_ago' => $timeAgo,
                'registration_number' => $admission['registration_number'],
            ];
        }, $admissions);

        return $this->respond([
            'success' => true,
            'admissions' => $formattedAdmissions
        ]);
    }

    /**
     * Convert date to time ago format
     * 
     * @param string $date
     * @return string
     */
    protected function timeAgo($date): string
    {
        $timestamp = strtotime($date);
        $now = time();
        $diff = $now - $timestamp;

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' menit yang lalu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' jam yang lalu';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' hari yang lalu';
        } else {
            return date('d M Y', $timestamp);
        }
    }

    /**
     * Get random programs for popup
     * 
     * GET /frontend/api/random-programs
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function randomPrograms()
    {
        $programModel = new ProgramModel();
        $limit = (int) ($this->request->getGet('limit') ?? 3);
        
        // Get random programs
        $programs = $programModel->getRandomPrograms($limit);
        
        // Format the data for display
        $formattedPrograms = array_map(function ($program) {
            // Calculate discounted price if discount exists
            $originalPrice = $program['tuition_fee'] ?? 0;
            $discount = $program['discount'] ?? 0;
            $finalPrice = $originalPrice > 0 && $discount > 0 
                ? $originalPrice - ($originalPrice * $discount / 100) 
                : $originalPrice;
            
            // Build thumbnail URL
            $thumbnailUrl = null;
            if (!empty($program['thumbnail'])) {
                $thumbnailUrl = base_url('uploads/programs/thumbs/' . $program['thumbnail']);
            }
            
            return [
                'id' => $program['id'],
                'title' => $program['title'],
                'description' => $program['description'] ? substr(strip_tags($program['description']), 0, 100) . '...' : '',
                'thumbnail' => $thumbnailUrl,
                'language' => $program['language'],
                'category' => $program['category'],
                'original_price' => $originalPrice,
                'discount' => $discount,
                'final_price' => $finalPrice,
                'url' => base_url('programs/' . $program['id']),
            ];
        }, $programs);
        
        return $this->respond([
            'success' => true,
            'programs' => $formattedPrograms
        ]);
    }

    /**
     * Register for HSK Simulation Test
     * 
     * POST /frontend/api/test-registration
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function registerTest()
    {
        $model = new TestRegistrationModel();

        // Get JSON input
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->respond([
                'success' => false,
                'message' => 'Data tidak valid'
            ], 400);
        }

        // Prepare data
        $data = [
            'hsk_level'      => $json->hsk_level ?? '',
            'full_name'     => $json->full_name ?? '',
            'email'         => $json->email ?? '',
            'phone'         => $json->phone ?? '',
            'birth_date'   => $json->birth_date ?? null,
            'address'       => $json->address ?? null,
            'education'     => $json->education ?? null,
            'occupation'   => $json->occupation ?? null,
            'mandarin_level'=> $json->mandarin_level ?? null,
            'notes'         => $json->notes ?? null,
            'status'        => 'pending',
        ];

        // Validate
        if (empty($data['hsk_level']) || empty($data['full_name']) || empty($data['email']) || empty($data['phone'])) {
            return $this->respond([
                'success' => false,
                'message' => 'Mohon lengkapi semua field wajib'
            ], 400);
        }

        // Check if already registered
        if ($model->isEmailRegistered($data['email'], $data['hsk_level'])) {
            return $this->respond([
                'success' => false,
                'message' => 'Email ini sudah terdaftar untuk tingkat HSK yang sama'
            ], 400);
        }

        // Save
        try {
            $insertId = $model->insert($data);

            if ($insertId) {
                return $this->respond([
                    'success' => true,
                    'message' => 'Pendaftaran berhasil! Tim kami akan menghubungi Anda segera.',
                    'data' => [
                        'id' => $insertId,
                        'hsk_level' => $data['hsk_level'],
                        'full_name' => $data['full_name']
                    ]
                ], 201);
            } else {
                return $this->respond([
                    'success' => false,
                    'message' => 'Gagal menyimpan pendaftaran'
                ], 500);
            }
        } catch (\Exception $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
