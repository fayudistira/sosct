<?php

namespace Modules\Inventory\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Inventory\Models\MovementModel;
use Modules\Inventory\Models\ItemModel;

class MovementApiController extends ResourceController
{
    protected $movementModel;
    protected $itemModel;

    public function __construct()
    {
        $this->movementModel = new MovementModel();
        $this->itemModel = new ItemModel();
    }

    /**
     * Get all movements
     * GET /api/inventory/movements
     */
    public function index()
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('per_page') ?? 20);
        
        $movements = $this->movementModel->orderBy('movement_date', 'DESC')
                                        ->paginate($perPage, 'default', $page);

        return $this->respond([
            'success' => true,
            'data' => $movements,
            'pagination' => [
                'page' => $this->movementModel->pager->getCurrentPage(),
                'per_page' => $perPage,
                'total' => $this->movementModel->pager->getTotal()
            ]
        ]);
    }

    /**
     * Get single movement
     * GET /api/inventory/movements/{id}
     */
    public function show($id = null)
    {
        $movement = $this->movementModel->find($id);

        if (!$movement) {
            return $this->respond([
                'success' => false,
                'message' => 'Movement not found'
            ], 404);
        }

        // Get item info
        $item = $this->itemModel->find($movement['item_id']);
        $movement['item'] = $item;

        return $this->respond([
            'success' => true,
            'data' => $movement
        ]);
    }

    /**
     * Create new movement
     * POST /api/inventory/movements
     */
    public function create()
    {
        $data = $this->request->getPost();

        // Generate ID if not provided
        if (empty($data['id'])) {
            $data['id'] = uuid_v4();
        }

        // Get item current stock
        $item = $this->itemModel->find($data['item_id']);
        if (!$item) {
            return $this->respond([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $data['quantity_before'] = $item['current_stock'];
        
        // Determine if this is an "in" or "out" movement
        $inTypes = ['purchase', 'return', 'initial'];
        $outTypes = ['sale', 'distributed', 'damage', 'expired'];
        
        // Cast to integer to avoid type errors
        $currentStock = (int) $item['current_stock'];
        $quantity = (int) $data['quantity'];
        
        if (in_array($data['movement_type'], $inTypes)) {
            // Add stock for in-types
            $data['quantity_after'] = $currentStock + $quantity;
        } elseif (in_array($data['movement_type'], $outTypes)) {
            // Subtract stock for out-types
            $data['quantity_after'] = $currentStock - $quantity;
            // Ensure stock doesn't go negative
            if ($data['quantity_after'] < 0) {
                $data['quantity_after'] = 0;
            }
        } else {
            // For adjustment and transfer, use the value as-is
            $data['quantity_after'] = $currentStock + $quantity;
        }
        
        // Store the signed quantity for reference
        if (in_array($data['movement_type'], $outTypes)) {
            $data['quantity'] = -abs($quantity); // Store as negative for out types
        }
        
        $data['movement_date'] = date('Y-m-d H:i:s');

        if ($this->movementModel->insert($data)) {
            // Update item stock
            $this->itemModel->update($data['item_id'], [
                'current_stock' => $data['quantity_after']
            ]);

            return $this->respond([
                'success' => true,
                'message' => 'Movement recorded successfully',
                'data' => $this->movementModel->find($data['id'])
            ], 201);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to record movement',
            'errors' => $this->movementModel->errors()
        ], 400);
    }

    /**
     * Get movements by item
     * GET /api/inventory/movements/item/{item_id}
     */
    public function byItem($itemId = null)
    {
        $movements = $this->movementModel->getByItem($itemId);

        return $this->respond([
            'success' => true,
            'data' => $movements
        ]);
    }

    /**
     * Get movement summary
     * GET /api/inventory/movements/summary
     */
    public function summary()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $summary = $this->movementModel->getSummary($startDate, $endDate);

        return $this->respond([
            'success' => true,
            'data' => $summary
        ]);
    }
}
