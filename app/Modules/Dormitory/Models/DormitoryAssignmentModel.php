<?php

namespace Modules\Dormitory\Models;

use CodeIgniter\Model;

class DormitoryAssignmentModel extends Model
{
    protected $table            = 'dormitory_assignments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'dormitory_id',
        'student_id',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'dormitory_id' => 'required',
        'student_id'   => 'required|integer',
        'start_date'   => 'permit_empty|valid_date',
        'end_date'     => 'permit_empty|valid_date',
        'status'       => 'required|in_list[active,completed,cancelled]',
    ];

    protected $validationMessages = [
        'dormitory_id' => [
            'required' => 'Dormitory is required.',
        ],
        'student_id' => [
            'required' => 'Student is required.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get assignments for a dormitory with student details
     */
    public function getAssignmentsByDormitory(string $dormitoryId): array
    {
        $db = \Config\Database::connect();
        
        return $db->table('dormitory_assignments da')
            ->select('da.*, s.student_number, p.full_name, p.phone')
            ->join('students s', 's.id = da.student_id', 'left')
            ->join('profiles p', 'p.id = s.profile_id', 'left')
            ->where('da.dormitory_id', $dormitoryId)
            ->orderBy('da.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get active assignments for a dormitory
     */
    public function getActiveAssignments(string $dormitoryId): array
    {
        $db = \Config\Database::connect();
        
        return $db->table('dormitory_assignments da')
            ->select('da.*, s.student_number, p.full_name, p.phone, p.email')
            ->join('students s', 's.id = da.student_id', 'left')
            ->join('profiles p', 'p.id = s.profile_id', 'left')
            ->where('da.dormitory_id', $dormitoryId)
            ->where('da.status', 'active')
            ->orderBy('da.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get assignment by student ID
     */
    public function getAssignmentByStudent(int $studentId): ?array
    {
        $db = \Config\Database::connect();
        
        $result = $db->table('dormitory_assignments da')
            ->select('da.*, d.room_name, d.location, d.map_url')
            ->join('dormitories d', 'd.id = da.dormitory_id', 'left')
            ->where('da.student_id', $studentId)
            ->where('da.status', 'active')
            ->get()
            ->getRowArray();

        return $result;
    }

    /**
     * Assign student to dormitory
     */
    public function assignStudent(string $dormitoryId, int $studentId, ?string $startDate = null, ?string $notes = null, ?string $endDate = null): bool
    {
        // Check if student already has an active assignment
        $existing = $this->where('student_id', $studentId)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            // End existing assignment
            $this->update($existing['id'], [
                'status'   => 'completed',
                'end_date' => date('Y-m-d'),
            ]);
        }

        // Create new assignment
        return $this->insert([
            'dormitory_id' => $dormitoryId,
            'student_id'   => $studentId,
            'start_date'   => $startDate ?? date('Y-m-d'),
            'end_date'     => $endDate,
            'status'       => 'active',
            'notes'        => $notes,
        ]);
    }

    /**
     * Remove student from dormitory
     */
    public function unassignStudent(int $assignmentId, ?string $endDate = null): bool
    {
        return $this->update($assignmentId, [
            'status'   => 'completed',
            'end_date' => $endDate ?? date('Y-m-d'),
        ]);
    }

    /**
     * Get occupied beds count for a dormitory
     */
    public function getOccupiedBedsCount(string $dormitoryId): int
    {
        return $this->where('dormitory_id', $dormitoryId)
            ->where('status', 'active')
            ->countAllResults();
    }

    /**
     * Check if dormitory has available beds
     */
    public function hasAvailableBeds(string $dormitoryId, int $capacity): bool
    {
        $occupied = $this->getOccupiedBedsCount($dormitoryId);
        return $occupied < $capacity;
    }

    /**
     * Search students by name and get their dormitory assignment
     */
    public function searchStudentWithAssignment(string $search): array
    {
        $db = \Config\Database::connect();
        
        return $db->table('students s')
            ->select('s.id as student_id, s.student_number, p.full_name, p.phone, p.email,
                     da.id as assignment_id, da.dormitory_id, da.start_date, da.end_date, da.status as assignment_status, da.notes,
                     d.room_name, d.location, d.map_url, d.room_capacity')
            ->join('profiles p', 'p.id = s.profile_id', 'left')
            ->join('dormitory_assignments da', 'da.student_id = s.id AND da.status = "active"', 'left')
            ->join('dormitories d', 'd.id = da.dormitory_id', 'left')
            ->groupStart()
                ->like('p.full_name', $search)
                ->orLike('s.student_number', $search)
            ->groupEnd()
            ->orderBy('p.full_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get student with dormitory assignment by student ID
     */
    public function getStudentWithAssignment(int $studentId): ?array
    {
        $db = \Config\Database::connect();
        
        return $db->table('students s')
            ->select('s.id as student_id, s.student_number, p.full_name, p.phone, p.email,
                     da.id as assignment_id, da.dormitory_id, da.start_date, da.end_date, da.status as assignment_status, da.notes,
                     d.room_name, d.location, d.map_url, d.room_capacity')
            ->join('profiles p', 'p.id = s.profile_id', 'left')
            ->join('dormitory_assignments da', 'da.student_id = s.id AND da.status = "active"', 'left')
            ->join('dormitories d', 'd.id = da.dormitory_id', 'left')
            ->where('s.id', $studentId)
            ->get()
            ->getRowArray();
    }
}
