<?php

namespace Modules\Employee\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table = 'staff';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'staff_number',
        'profile_id',
        'position',
        'department',
        'hire_date',
        'status',
        'employment_type',
        'salary',
        'termination_date',
        'termination_reason'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'id' => 'permit_empty|is_natural_no_zero',
        'staff_number' => 'required|max_length[20]|is_unique[staff.staff_number,id,{id}]',
        'profile_id' => 'required|is_natural_no_zero|is_unique[staff.profile_id,id,{id}]',
        'position' => 'required|max_length[100]',
        'department' => 'permit_empty|max_length[100]',
        'hire_date' => 'required|valid_date',
        'status' => 'required|in_list[active,inactive,resigned,terminated]',
        'employment_type' => 'required|in_list[full-time,part-time,contract]',
        'salary' => 'permit_empty|decimal',
    ];

    protected $validationMessages = [
        'staff_number' => [
            'is_unique' => 'This staff number already exists.'
        ],
        'profile_id' => [
            'is_unique' => 'This profile is already linked to a staff record.'
        ]
    ];

    /**
     * Generate unique staff number
     * Format: EMP-YYYY-NNNN (e.g., EMP-2026-0001)
     * 
     * @return string
     */
    public function generateStaffNumber(): string
    {
        $year = date('Y');
        $prefix = "EMP-{$year}-";

        // Get the last staff number for current year
        $lastRecord = $this->like('staff_number', $prefix)
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastRecord) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastRecord['staff_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            // First employee of the year
            $newNumber = 1;
        }

        // Format with leading zeros (4 digits)
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get staff with profile and user details
     * 
     * @param int|null $id
     * @return array|null
     */
    public function getStaffWithDetails(?int $id = null)
    {
        $builder = $this->select('staff.*, profiles.full_name, profiles.email, profiles.phone, profiles.photo, profiles.gender, profiles.place_of_birth, profiles.date_of_birth, profiles.street_address, profiles.district, profiles.regency, profiles.province, profiles.postal_code, profiles.citizen_id, profiles.documents, profiles.emergency_contact_name, profiles.emergency_contact_phone, profiles.emergency_contact_relation, profiles.father_name, profiles.mother_name, users.username, users.active as account_status')
            ->join('profiles', 'profiles.id = staff.profile_id')
            ->join('users', 'users.id = profiles.user_id', 'left');

        if ($id) {
            return $builder->find($id);
        }

        return $builder->findAll();
    }

    /**
     * Get instructors (staff with 'instructor' position OR users in instructor group)
     * 
     * @return array
     */
    public function getInstructors(): array
    {
        $db = \Config\Database::connect();
        $instructors = [];

        // Get staff with 'instructor' position
        $staffInstructors = $db->table('staff')
            ->select('staff.id, profiles.full_name, profiles.email')
            ->join('profiles', 'profiles.id = staff.profile_id')
            ->where('staff.position', 'instructor')
            ->where('staff.status', 'active')
            ->where('staff.deleted_at', null)
            ->orderBy('profiles.full_name', 'ASC')
            ->get()
            ->getResultArray();

        // Get users in 'instructor' group via auth_groups_users
        $groupInstructors = $db->table('auth_groups_users')
            ->select('profiles.id, profiles.full_name, profiles.email')
            ->join('profiles', 'profiles.user_id = auth_groups_users.user_id', 'left')
            ->where('auth_groups_users.`group`', 'instructor')
            ->where('profiles.id IS NOT NULL')
            ->orderBy('profiles.full_name', 'ASC')
            ->get()
            ->getResultArray();

        // Merge both lists and remove duplicates based on full_name
        $allInstructors = array_merge($staffInstructors, $groupInstructors);
        $uniqueInstructors = [];
        $seenNames = [];

        foreach ($allInstructors as $instructor) {
            $name = strtolower(trim($instructor['full_name']));
            if (!empty($name) && !in_array($name, $seenNames)) {
                $seenNames[] = $name;
                $uniqueInstructors[] = $instructor;
            }
        }

        return $uniqueInstructors;
    }
}
