<?php

namespace Modules\Inventory\Models;

use CodeIgniter\Model;

class StockOpnameDetailModel extends Model
{
    protected $table = 'inventory_stock_opname_details';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'opname_id',
        'item_id',
        'system_stock',
        'physical_stock',
        'difference',
        'status',
        'notes',
        'counted_at',
        'created_at'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    protected $validationRules = [
        'opname_id' => 'required',
        'item_id' => 'required',
        'system_stock' => 'permit_empty|integer',
        'physical_stock' => 'permit_empty|integer'
    ];

    /**
     * Get details by opname
     */
    public function getByOpname(string $opnameId): array
    {
        return $this->where('opname_id', $opnameId)->findAll();
    }

    /**
     * Get details by item
     */
    public function getByItem(string $itemId): array
    {
        return $this->where('item_id', $itemId)->findAll();
    }

    /**
     * Get discrepancies
     */
    public function getDiscrepancies(string $opnameId): array
    {
        return $this->where('opname_id', $opnameId)
                    ->where('status', 'discrepancy')
                    ->findAll();
    }

    /**
     * Update physical stock and calculate difference
     */
    public function updatePhysicalStock(string $detailId, int $physicalStock): bool
    {
        $detail = $this->find($detailId);
        if (!$detail) {
            return false;
        }

        $difference = $physicalStock - $detail['system_stock'];
        $status = $difference === 0 ? 'matched' : 'discrepancy';

        return $this->update($detailId, [
            'physical_stock' => $physicalStock,
            'difference' => $difference,
            'status' => $status,
            'counted_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get status options
     */
    public function getStatuses(): array
    {
        return [
            'matched' => 'Matched',
            'discrepancy' => 'Discrepancy',
            'pending' => 'Pending'
        ];
    }
}
