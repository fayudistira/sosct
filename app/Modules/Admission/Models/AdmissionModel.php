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
        'profile_id',
        'program_id',
        'status',
        'application_date',
        'reviewed_date',
        'reviewed_by',
        'notes',
        'applicant_notes'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $validationRules = [
        'id' => 'permit_empty|is_natural_no_zero',
        'registration_number' => 'required|is_unique[admissions.registration_number,id,{id}]',
        'profile_id' => 'required|is_natural_no_zero|is_not_unique[profiles.id]',
        'program_id' => 'required|is_not_unique[programs.id]',
        'status' => 'required|in_list[pending,approved,rejected,withdrawn]',
        'application_date' => 'permit_empty|valid_date',
        'reviewed_date' => 'permit_empty|valid_date',
        'reviewed_by' => 'permit_empty|is_natural_no_zero|is_not_unique[users.id]'
    ];
    
    protected $validationMessages = [
        'registration_number' => [
            'is_unique' => 'This registration number already exists.'
        ],
        'profile_id' => [
            'is_not_unique' => 'Profile does not exist.'
        ],
        'program_id' => [
            'is_not_unique' => 'Program does not exist.'
        ],
        'reviewed_by' => [
            'is_not_unique' => 'Reviewer user does not exist.'
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
     * Search admissions by keyword (with profile join)
     * 
     * @param string $keyword Search keyword
     * @return array
     */
    public function searchAdmissions(string $keyword)
    {
        return $this->select('admissions.*, profiles.full_name, profiles.email, profiles.phone, programs.title as program_title')
                    ->join('profiles', 'profiles.id = admissions.profile_id')
                    ->join('programs', 'programs.id = admissions.program_id')
                    ->groupStart()
                        ->like('admissions.registration_number', $keyword)
                        ->orLike('profiles.full_name', $keyword)
                        ->orLike('profiles.email', $keyword)
                        ->orLike('profiles.phone', $keyword)
                        ->orLike('programs.title', $keyword)
                    ->groupEnd()
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
     * Get count of admissions by program
     * 
     * @return array
     */
    public function getProgramStatistics(): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        
        $results = $builder->select('programs.title as program_title, programs.category, COUNT(*) as total')
                          ->join('programs', 'programs.id = admissions.program_id')
                          ->where('admissions.deleted_at', null)
                          ->groupBy('admissions.program_id')
                          ->orderBy('total', 'DESC')
                          ->get()
                          ->getResultArray();
        
        return $results;
    }
    
    /**
     * Get count of admissions by program and status
     * 
     * @return array
     */
    public function getProgramStatusBreakdown(): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        
        $results = $builder->select('programs.title as program_title, programs.category, admissions.status, COUNT(*) as total')
                          ->join('programs', 'programs.id = admissions.program_id')
                          ->where('admissions.deleted_at', null)
                          ->groupBy('admissions.program_id, admissions.status')
                          ->orderBy('programs.title', 'ASC')
                          ->orderBy('admissions.status', 'ASC')
                          ->get()
                          ->getResultArray();
        
        // Organize by program
        $organized = [];
        foreach ($results as $row) {
            $program = $row['program_title'];
            if (!isset($organized[$program])) {
                $organized[$program] = [
                    'program' => $program,
                    'category' => $row['category'],
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                    'withdrawn' => 0,
                    'total' => 0
                ];
            }
            $organized[$program][$row['status']] = (int)$row['total'];
            $organized[$program]['total'] += (int)$row['total'];
        }
        
        return array_values($organized);
    }
    
    /**
     * Get admission with profile and program details
     * 
     * @param int $id Admission ID
     * @return array|null
     */
    public function getWithDetails(int $id): ?array
    {
        return $this->select('
                admissions.id as admission_id,
                admissions.registration_number,
                admissions.profile_id,
                admissions.program_id,
                admissions.status,
                admissions.application_date,
                admissions.reviewed_date,
                admissions.reviewed_by,
                admissions.notes,
                admissions.applicant_notes,
                admissions.created_at,
                admissions.updated_at,
                profiles.profile_number,
                profiles.full_name,
                profiles.nickname,
                profiles.gender,
                profiles.place_of_birth,
                profiles.date_of_birth,
                profiles.religion,
                profiles.citizen_id,
                profiles.phone,
                profiles.email,
                profiles.street_address,
                profiles.district,
                profiles.regency,
                profiles.province,
                profiles.postal_code,
                profiles.emergency_contact_name,
                profiles.emergency_contact_phone,
                profiles.emergency_contact_relation,
                profiles.father_name,
                profiles.mother_name,
                profiles.photo,
                profiles.documents,
                programs.title as program_title,
                programs.category,
                programs.tuition_fee,
                programs.discount
            ')
                    ->join('profiles', 'profiles.id = admissions.profile_id')
                    ->join('programs', 'programs.id = admissions.program_id')
                    ->where('admissions.id', $id)
                    ->first();
    }
    
    /**
     * Get all admissions with profile and program details
     * 
     * @return array
     */
    public function getAllWithDetails(): array
    {
        return $this->select('
                admissions.id,
                admissions.registration_number,
                admissions.status,
                admissions.application_date,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title,
                programs.category
            ')
                    ->join('profiles', 'profiles.id = admissions.profile_id')
                    ->join('programs', 'programs.id = admissions.program_id')
                    ->orderBy('admissions.created_at', 'DESC')
                    ->findAll();
    }
}
