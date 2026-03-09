<?php

namespace Modules\Inventory\Models;

use CodeIgniter\Model;

class LocationModel extends Model
{
    protected $table = 'inventory_locations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'name',
        'description',
        'address',
        'type',
        'is_default',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'type' => 'required|in_list[warehouse,room,storage]',
        'is_default' => 'permit_empty|in_list[0,1]'
    ];

    /**
     * Get default location
     */
    public function getDefault(): ?array
    {
        return $this->where('is_default', true)->first();
    }

    /**
     * Get locations by type
     */
    public function getByType(string $type): array
    {
        return $this->where('type', $type)->findAll();
    }

    /**
     * Get all types as array for dropdown
     */
    public function getTypes(): array
    {
        return [
            'warehouse' => 'Warehouse',
            'room' => 'Room',
            'storage' => 'Storage'
        ];
    }
}
