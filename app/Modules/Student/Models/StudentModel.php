<?php

namespace Modules\Student\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'student_number',
        'profile_id',
        'admission_id',
        'enrollment_date',
        'status',
        'program_id',
        'batch',
        'gpa',
        'total_credits',
        'graduation_date',
        'graduation_gpa'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'student_number' => 'required|max_length[20]|is_unique[students.student_number,id,{id}]',
        'profile_id' => 'required|is_natural_no_zero',
        'status' => 'required|in_list[active,inactive,graduated,dropped,suspended]',
        'program_id' => 'permit_empty|max_length[36]',
        'enrollment_date' => 'required|valid_date'
    ];

    /**
     * Generate unique student number
     * Format: STU-YYYY-NNNN (e.g., STU-2026-0001)
     */
    public function generateStudentNumber(): string
    {
        $year = date('Y');
        $prefix = "STU-{$year}-";

        $lastRecord = $this->like('student_number', $prefix)
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord['student_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get all students with details (Profile, Program)
     */
    public function getAllWithDetails()
    {
        return $this->select('
                students.*,
                profiles.full_name,
                profiles.email as profile_email,
                profiles.phone,
                profiles.photo,
                profiles.gender,
                programs.title as program_title,
                users.username,
                users.id as user_id
            ')
            ->join('profiles', 'profiles.id = students.profile_id')
            ->join('programs', 'programs.id = students.program_id', 'left')
            ->join('users', 'users.id = profiles.user_id', 'left')
            ->where('students.deleted_at IS NULL')
            ->where('profiles.deleted_at IS NULL')
            ->orderBy('students.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get specific student with details
     */
    public function getStudentWithDetails($id)
    {
        return $this->select('
                students.*,
                profiles.full_name,
                profiles.nickname,
                profiles.email as profile_email,
                profiles.phone,
                profiles.photo,
                profiles.gender,
                profiles.place_of_birth,
                profiles.date_of_birth,
                profiles.religion,
                profiles.citizen_id,
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
                programs.title as program_title,
                users.username
            ')
            ->join('profiles', 'profiles.id = students.profile_id')
            ->join('programs', 'programs.id = students.program_id', 'left')
            ->join('users', 'users.id = profiles.user_id', 'left')
            ->where('students.id', $id)
            ->first();
    }
}
