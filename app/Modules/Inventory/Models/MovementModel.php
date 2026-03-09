<?php

namespace Modules\Inventory\Models;

use CodeIgniter\Model;

class MovementModel extends Model
{
    protected $table = 'inventory_movements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'item_id',
        'movement_type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'reference_number',
        'description',
        'performed_by',
        'location_id',
        'movement_date',
        'created_at'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    protected $validationRules = [
        'item_id' => 'required',
        'movement_type' => 'required|in_list[purchase,return,sale,distributed,adjustment,transfer,damage,expired,initial]',
        'quantity' => 'required|integer',
        'quantity_before' => 'permit_empty|integer',
        'quantity_after' => 'permit_empty|integer'
    ];

    /**
     * Get movements by item
     */
    public function getByItem(string $itemId): array
    {
        return $this->where('item_id', $itemId)
                    ->orderBy('movement_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get movements by type
     */
    public function getByType(string $type): array
    {
        return $this->where('movement_type', $type)->findAll();
    }

    /**
     * Get movements by date range
     */
    public function getByDateRange(string $startDate, string $endDate): array
    {
        return $this->where('movement_date >=', $startDate)
                    ->where('movement_date <=', $endDate)
                    ->findAll();
    }

    /**
     * Get movement types
     */
    public function getTypes(): array
    {
        return [
            'purchase' => 'Purchase',
            'return' => 'Return',
            'sale' => 'Sale',
            'distributed' => 'Distributed',
            'adjustment' => 'Adjustment',
            'transfer' => 'Transfer',
            'damage' => 'Damage',
            'expired' => 'Expired',
            'initial' => 'Initial Stock'
        ];
    }

    /**
     * Get movement summary
     */
    public function getSummary(string $startDate = null, string $endDate = null): array
    {
        $this->select('movement_type, SUM(quantity) as total_quantity, COUNT(*) as total_movements');
        
        if ($startDate && $endDate) {
            $this->where('movement_date >=', $startDate);
            $this->where('movement_date <=', $endDate);
        }
        
        return $this->groupBy('movement_type')
                   ->findAll();
    }
}
