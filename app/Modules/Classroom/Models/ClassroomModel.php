<?php

namespace Modules\Classroom\Models;

use CodeIgniter\Model;

class ClassroomModel extends Model
{
    protected $table            = 'classrooms';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'batch',
        'grade',
        'program',
        'status',
        'start_date',
        'end_date',
        'schedule',
        'members'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'title'      => 'required|min_length[3]|max_length[255]',
        'batch'      => 'permit_empty|max_length[50]',
        'grade'      => 'permit_empty|max_length[50]',
        'program'    => 'permit_empty|max_length[255]',
        'status'     => 'required|in_list[active,inactive,completed]',
        'start_date' => 'permit_empty|valid_date',
        'end_date'   => 'permit_empty|valid_date',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}