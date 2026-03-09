<?php

namespace Modules\Inventory\Models;

use CodeIgniter\Model;
use Modules\Inventory\Models\MovementModel;
use Modules\Inventory\Models\AlertModel;

class ItemModel extends Model
{
    protected $table = 'inventory_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'id',
        'item_code',
        'barcode',
        'name',
        'description',
        'category_id',
        'location_id',
        'program_id',
        'unit',
        'purchase_price',
        'selling_price',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'status',
        'specifications',
        'thumbnail',
        'supplier_id',
        'supplier_name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'id' => 'permit_empty|max_length[36]',
        'item_code' => 'required|max_length[50]|is_unique[inventory_items.item_code,id,{id}]',
        'name' => 'required|min_length[2]|max_length[255]',
        'category_id' => 'permit_empty|max_length[36]',
        'location_id' => 'permit_empty|max_length[36]',
        'program_id' => 'permit_empty|max_length[36]',
        'unit' => 'permit_empty|max_length[20]',
        'purchase_price' => 'permit_empty|decimal',
        'selling_price' => 'permit_empty|decimal',
        'current_stock' => 'permit_empty|is_natural',
        'minimum_stock' => 'permit_empty|is_natural',
        'maximum_stock' => 'permit_empty|is_natural',
        'status' => 'required|in_list[active,inactive,discontinued]'
    ];

    // Relationships
    protected $belongsTo = [
        'category' => [
            'model' => 'CategoryModel',
            'foreign_key' => 'category_id'
        ],
        'location' => [
            'model' => 'LocationModel',
            'foreign_key' => 'location_id'
        ]
    ];

    /**
     * Get items with low stock
     */
    public function getLowStock(): array
    {
        return $this->where('current_stock <=', 'minimum_stock', false)
                    ->where('status', 'active')
                    ->where('deleted_at', null)
                    ->findAll();
    }

    /**
     * Get items by category
     */
    public function getByCategory(string $categoryId): array
    {
        return $this->where('category_id', $categoryId)->findAll();
    }

    /**
     * Get items by location
     */
    public function getByLocation(string $locationId): array
    {
        return $this->where('location_id', $locationId)->findAll();
    }

    /**
     * Get items by program
     */
    public function getByProgram(string $programId): array
    {
        return $this->where('program_id', $programId)->findAll();
    }

    /**
     * Search items
     */
    public function search(string $keyword): array
    {
        return $this->like('name', $keyword)
                    ->orLike('item_code', $keyword)
                    ->orLike('barcode', $keyword)
                    ->orLike('supplier_name', $keyword)
                    ->findAll();
    }

    /**
     * Get active items
     */
    public function getActive(): array
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Generate unique item code
     */
    public function generateItemCode(): string
    {
        $prefix = 'INV';
        $year = date('Y');
        $lastItem = $this->like('item_code', "{$prefix}{$year}%")
                        ->orderBy('item_code', 'DESC')
                        ->first();

        if ($lastItem) {
            $lastNumber = (int) substr($lastItem['item_code'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Update stock and create movement
     */
    public function updateStock(string $itemId, int $quantity, string $movementType, array $movementData = []): bool
    {
        $item = $this->find($itemId);
        if (!$item) {
            return false;
        }

        $quantityBefore = (int) $item['current_stock'];
        $quantityAfter = $quantityBefore + $quantity;

        // Update stock
        $this->update($itemId, ['current_stock' => $quantityAfter]);

        // Create movement record
        $movementModel = new MovementModel();
        $movementModel->insert([
            'id' => uuid_v4(),
            'item_id' => $itemId,
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'reference_number' => $movementData['reference_number'] ?? null,
            'description' => $movementData['description'] ?? null,
            'performed_by' => $movementData['performed_by'] ?? null,
            'location_id' => $movementData['location_id'] ?? $item['location_id'],
            'movement_date' => date('Y-m-d H:i:s')
        ]);

        // Check for low stock alert
        $this->checkLowStock($itemId);

        return true;
    }

    /**
     * Check and create low stock alert
     */
    protected function checkLowStock(string $itemId): void
    {
        $item = $this->find($itemId);
        if (!$item || $item['status'] !== 'active') {
            return;
        }

        $alertModel = new AlertModel();
        
        // Check if alert already exists
        $existingAlert = $alertModel->where('item_id', $itemId)
                                    ->where('alert_type', 'low_stock')
                                    ->where('status', 'active')
                                    ->first();

        if ($item['current_stock'] <= $item['minimum_stock'] && !$existingAlert) {
            $alertModel->insert([
                'id' => uuid_v4(),
                'item_id' => $itemId,
                'alert_type' => 'low_stock',
                'current_stock' => $item['current_stock'],
                'threshold' => $item['minimum_stock'],
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } elseif ($item['current_stock'] > $item['minimum_stock'] && $existingAlert) {
            $alertModel->update($existingAlert['id'], [
                'status' => 'resolved',
                'resolved_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Get stock value
     */
    public function getStockValue(): float
    {
        $items = $this->where('status', 'active')->findAll();
        $total = 0;
        foreach ($items as $item) {
            $total += (float) $item['current_stock'] * (float) $item['purchase_price'];
        }
        return $total;
    }

    /**
     * Get units as array
     */
    public function getUnits(): array
    {
        return [
            'piece' => 'Piece',
            'box' => 'Box',
            'pack' => 'Pack',
            'set' => 'Set',
            'kg' => 'Kg',
            'liter' => 'Liter',
            'meter' => 'Meter'
        ];
    }
}
