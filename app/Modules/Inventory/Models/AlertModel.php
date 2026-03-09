<?php

namespace Modules\Inventory\Models;

use CodeIgniter\Model;

class AlertModel extends Model
{
    protected $table = 'inventory_alerts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'item_id',
        'alert_type',
        'current_stock',
        'threshold',
        'status',
        'notes',
        'created_at',
        'resolved_at'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    protected $validationRules = [
        'item_id' => 'required',
        'alert_type' => 'required|in_list[low_stock,overstock,expiring,expired]',
        'current_stock' => 'permit_empty|integer',
        'threshold' => 'permit_empty|integer',
        'status' => 'required|in_list[active,resolved]'
    ];

    /**
     * Get active alerts
     */
    public function getActive(): array
    {
        return $this->where('status', 'active')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get alerts by item
     */
    public function getByItem(string $itemId): array
    {
        return $this->where('item_id', $itemId)->findAll();
    }

    /**
     * Get alerts by type
     */
    public function getByType(string $type): array
    {
        return $this->where('alert_type', $type)->findAll();
    }

    /**
     * Get active alerts by type
     */
    public function getActiveByType(string $type): array
    {
        return $this->where('alert_type', $type)
                    ->where('status', 'active')
                    ->findAll();
    }

    /**
     * Resolve alert
     */
    public function resolve(string $alertId, string $notes = null): bool
    {
        $data = [
            'status' => 'resolved',
            'resolved_at' => date('Y-m-d H:i:s')
        ];

        if ($notes) {
            $data['notes'] = $notes;
        }

        return $this->update($alertId, $data);
    }

    /**
     * Get alert counts
     */
    public function getCounts(): array
    {
        $active = $this->where('status', 'active')->countAllResults();
        $lowStock = $this->where('status', 'active')
                         ->where('alert_type', 'low_stock')
                         ->countAllResults();
        $overstock = $this->where('status', 'active')
                          ->where('alert_type', 'overstock')
                          ->countAllResults();

        return [
            'active' => $active,
            'low_stock' => $lowStock,
            'overstock' => $overstock
        ];
    }

    /**
     * Get alert types
     */
    public function getTypes(): array
    {
        return [
            'low_stock' => 'Low Stock',
            'overstock' => 'Overstock',
            'expiring' => 'Expiring Soon',
            'expired' => 'Expired'
        ];
    }
}
