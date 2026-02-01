<?php

namespace Modules\Admission\Models;

use CodeIgniter\Model;

class AdmissionModel extends Model
{
    protected $table = 'admissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    
    protected $allowedFields = [
        'registration_number',
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
        'course',
        'status',
        'application_date',
        'photo',
        'documents',
        'notes'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $validationRules = [
        'registration_number' => 'required|is_unique[admissions.registration_number,id,{id}]',
        'full_name' => 'required|min_length[3]|max_length[100]',
        'gender' => 'required|in_list[Male,Female]',
        'place_of_birth' => 'required|min_length[3]|max_length[100]',
        'date_of_birth' => 'required|valid_date',
        'religion' => 'required|min_length[3]|max_length[50]',
        'phone' => 'required|regex_match[/^[0-9]{10,15}$/]',
        'email' => 'required|valid_email|is_unique[admissions.email,id,{id}]',
        'street_address' => 'required|min_length[5]',
        'district' => 'required|min_length[3]',
        'regency' => 'required|min_length[3]',
        'province' => 'required|min_length[3]',
        'emergency_contact_name' => 'required|min_length[3]|max_length[100]',
        'emergency_contact_phone' => 'required|regex_match[/^[0-9]{10,15}$/]',
        'emergency_contact_relation' => 'required|min_length[3]|max_length[50]',
        'father_name' => 'required|min_length[3]|max_length[100]',
        'mother_name' => 'required|min_length[3]|max_length[100]',
        'course' => 'required|min_length[3]',
        'status' => 'required|in_list[pending,approved,rejected]'
    ];
    
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email is already registered.'
        ],
        'registration_number' => [
            'is_unique' => 'This registration number already exists.'
        ]
    ];

    
    /**
     * Generate unique registration number
     * Format: REG-YYYY-NNNN (e.g., REG-2024-0001)
     * 
     * @return string
     */
    public function generateRegistrationNumber(): string
    {
        $year = date('Y');
        $prefix = "REG-{$year}-";
        
        // Get the last registration number for current year
        $lastRecord = $this->like('registration_number', $prefix)
                          ->orderBy('id', 'DESC')
                          ->first();
        
        if ($lastRecord) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastRecord['registration_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            // First registration of the year
            $newNumber = 1;
        }
        
        // Format with leading zeros (4 digits)
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get admissions with pagination
     * 
     * @param int $perPage Number of records per page
     * @return array
     */
    public function getWithPagination(int $perPage = 10)
    {
        return $this->orderBy('created_at', 'DESC')->paginate($perPage);
    }
    
    /**
     * Search admissions by keyword
     * 
     * @param string $keyword Search keyword
     * @return array
     */
    public function searchAdmissions(string $keyword)
    {
        return $this->like('registration_number', $keyword)
                    ->orLike('full_name', $keyword)
                    ->orLike('email', $keyword)
                    ->orLike('course', $keyword)
                    ->orLike('phone', $keyword)
                    ->findAll();
    }
    
    /**
     * Filter admissions by status
     * 
     * @param string $status Status to filter by
     * @return array
     */
    public function filterByStatus(string $status)
    {
        return $this->where('status', $status)->findAll();
    }
    
    /**
     * Get count of admissions by status
     * 
     * @return array
     */
    public function getStatusCounts(): array
    {
        return [
            'pending' => $this->where('status', 'pending')->countAllResults(),
            'approved' => $this->where('status', 'approved')->countAllResults(),
            'rejected' => $this->where('status', 'rejected')->countAllResults(),
            'total' => $this->countAllResults(false)
        ];
    }
    
    /**
     * Get count of admissions by course
     * 
     * @return array
     */
    public function getCourseStatistics(): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        
        $results = $builder->select('course, COUNT(*) as total')
                          ->where('deleted_at', null)
                          ->groupBy('course')
                          ->orderBy('total', 'DESC')
                          ->get()
                          ->getResultArray();
        
        return $results;
    }
    
    /**
     * Get count of admissions by course and status
     * 
     * @return array
     */
    public function getCourseStatusBreakdown(): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        
        $results = $builder->select('course, status, COUNT(*) as total')
                          ->where('deleted_at', null)
                          ->groupBy('course, status')
                          ->orderBy('course', 'ASC')
                          ->orderBy('status', 'ASC')
                          ->get()
                          ->getResultArray();
        
        // Organize by course
        $organized = [];
        foreach ($results as $row) {
            $course = $row['course'];
            if (!isset($organized[$course])) {
                $organized[$course] = [
                    'course' => $course,
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                    'total' => 0
                ];
            }
            $organized[$course][$row['status']] = (int)$row['total'];
            $organized[$course]['total'] += (int)$row['total'];
        }
        
        return array_values($organized);
    }
}
