<?php

namespace Modules\Inventory\Models;

use CodeIgniter\Model;

class StockOpnameModel extends Model
{
    protected $table = 'inventory_stock_opnames';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'opname_number',
        'location_id',
        'status',
        'performed_by',
        'start_date',
        'end_date',
        'notes',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'opname_number' => 'required|is_unique[inventory_stock_opnames.opname_number,id,{id}]',
        'location_id' => 'permit_empty|is_natural',
        'status' => 'required|in_list[draft,in_progress,completed,cancelled]'
    ];

    /**
     * Get opnames by status
     */
    public function getByStatus(string $status): array
    {
        return $this->where('status', $status)->findAll();
    }

    /**
     * Get opnames by location
     */
    public function getByLocation(string $locationId): array
    {
        return $this->where('location_id', $locationId)->findAll();
    }

    /**
     * Generate opname number
     */
    public function generateOpnameNumber(): string
    {
        $prefix = 'SO';
        $year = date('Y');
        $month = date('m');
        
        $lastOpname = $this->like('opname_number', "{$prefix}{$year}{$month}%")
                           ->orderBy('opname_number', 'DESC')
                           ->first();

        if ($lastOpname) {
            $lastNumber = (int) substr($lastOpname['opname_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get status options
     */
    public function getStatuses(): array
    {
        return [
            'draft' => 'Draft',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
    }

    /**
     * Complete opname and adjust stock
     */
    public function complete(string $opnameId): bool
    {
        $opname = $this->find($opnameId);
        if (!$opname || $opname['status'] !== 'in_progress') {
            return false;
        }

        $detailModel = new StockOpnameDetailModel();
        $itemModel = new ItemModel();
        
        // Get all details
        $details = $detailModel->where('opname_id', $opnameId)->findAll();
        
        foreach ($details as $detail) {
            if ($detail['difference'] !== 0) {
                // Update item stock
                $itemModel->update($detail['item_id'], [
                    'current_stock' => $detail['physical_stock']
                ]);
                
                // Create adjustment movement
                $movementModel = new MovementModel();
                $movementModel->insert([
                    'id' => uuid_v4(),
                    'item_id' => $detail['item_id'],
                    'movement_type' => 'adjustment',
                    'quantity' => $detail['difference'],
                    'quantity_before' => $detail['system_stock'],
                    'quantity_after' => $detail['physical_stock'],
                    'reference_number' => $opname['opname_number'],
                    'description' => 'Stock opname adjustment',
                    'performed_by' => $opname['performed_by'],
                    'location_id' => $opname['location_id'],
                    'movement_date' => date('Y-m-d H:i:s')
                ]);
            }
        }

        // Update opname status
        $this->update($opnameId, [
            'status' => 'completed',
            'end_date' => date('Y-m-d H:i:s')
        ]);

        return true;
    }
}
