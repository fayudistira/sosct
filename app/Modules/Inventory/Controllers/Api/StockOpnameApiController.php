<?php

namespace Modules\Inventory\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Inventory\Models\StockOpnameModel;
use Modules\Inventory\Models\StockOpnameDetailModel;
use Modules\Inventory\Models\ItemModel;

class StockOpnameApiController extends ResourceController
{
    protected $stockOpnameModel;
    protected $detailModel;
    protected $itemModel;

    public function __construct()
    {
        $this->stockOpnameModel = new StockOpnameModel();
        $this->detailModel = new StockOpnameDetailModel();
        $this->itemModel = new ItemModel();
    }

    /**
     * Get all stock opnames
     * GET /api/inventory/stock-opname
     */
    public function index()
    {
        $status = $this->request->getGet('status');
        
        $builder = $this->stockOpnameModel->builder();
        if ($status) {
            $builder->where('status', $status);
        }

        $opnames = $this->stockOpnameModel->orderBy('created_at', 'DESC')->findAll();

        return $this->respond([
            'success' => true,
            'data' => $opnames
        ]);
    }

    /**
     * Get single stock opname
     * GET /api/inventory/stock-opname/{id}
     */
    public function show($id = null)
    {
        $opname = $this->stockOpnameModel->find($id);

        if (!$opname) {
            return $this->respond([
                'success' => false,
                'message' => 'Stock opname not found'
            ], 404);
        }

        // Get details
        $details = $this->detailModel->where('opname_id', $id)->findAll();
        
        // Get item names
        foreach ($details as &$detail) {
            $item = $this->itemModel->find($detail['item_id']);
            $detail['item_name'] = $item ? $item['name'] : 'Unknown';
        }

        $opname['details'] = $details;

        return $this->respond([
            'success' => true,
            'data' => $opname
        ]);
    }

    /**
     * Create new stock opname
     * POST /api/inventory/stock-opname
     */
    public function create()
    {
        $data = $this->request->getPost();

        // Generate ID if not provided
        if (empty($data['id'])) {
            $data['id'] = uuid_v4();
        }

        // Generate opname number
        if (empty($data['opname_number'])) {
            $data['opname_number'] = $this->stockOpnameModel->generateOpnameNumber();
        }

        $data['status'] = 'draft';
        $data['start_date'] = date('Y-m-d H:i:s');

        if ($this->stockOpnameModel->insert($data)) {
            // Create details for items
            if (!empty($data['location_id'])) {
                $items = $this->itemModel->where('location_id', $data['location_id'])->findAll();
                foreach ($items as $item) {
                    $this->detailModel->insert([
                        'id' => uuid_v4(),
                        'opname_id' => $data['id'],
                        'item_id' => $item['id'],
                        'system_stock' => $item['current_stock'],
                        'physical_stock' => 0,
                        'difference' => 0,
                        'status' => 'pending'
                    ]);
                }
            }

            return $this->respond([
                'success' => true,
                'message' => 'Stock opname created successfully',
                'data' => $this->stockOpnameModel->find($data['id'])
            ], 201);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to create stock opname',
            'errors' => $this->stockOpnameModel->errors()
        ], 400);
    }

    /**
     * Update stock opname
     * PUT /api/inventory/stock-opname/{id}
     */
    public function update($id = null)
    {
        $opname = $this->stockOpnameModel->find($id);

        if (!$opname) {
            return $this->respond([
                'success' => false,
                'message' => 'Stock opname not found'
            ], 404);
        }

        $data = $this->request->getRawInput();

        if ($this->stockOpnameModel->update($id, $data)) {
            return $this->respond([
                'success' => true,
                'message' => 'Stock opname updated successfully',
                'data' => $this->stockOpnameModel->find($id)
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to update stock opname'
        ], 400);
    }

    /**
     * Complete stock opname
     * POST /api/inventory/stock-opname/{id}/complete
     */
    public function complete($id = null)
    {
        $opname = $this->stockOpnameModel->find($id);

        if (!$opname) {
            return $this->respond([
                'success' => false,
                'message' => 'Stock opname not found'
            ], 404);
        }

        if ($this->stockOpnameModel->complete($id)) {
            return $this->respond([
                'success' => true,
                'message' => 'Stock opname completed successfully'
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to complete stock opname'
        ], 400);
    }

    /**
     * Add opname detail
     * POST /api/inventory/stock-opname/{id}/details
     */
    public function addDetail($opnameId = null)
    {
        $data = $this->request->getPost();

        if (empty($data['id'])) {
            $data['id'] = uuid_v4();
        }

        $data['opname_id'] = $opnameId;

        // Calculate difference
        $item = $this->itemModel->find($data['item_id']);
        $data['system_stock'] = $item ? $item['current_stock'] : 0;
        $data['difference'] = ($data['physical_stock'] ?? 0) - $data['system_stock'];
        $data['status'] = $data['difference'] === 0 ? 'matched' : 'discrepancy';

        if ($this->detailModel->insert($data)) {
            return $this->respond([
                'success' => true,
                'message' => 'Detail added successfully',
                'data' => $this->detailModel->find($data['id'])
            ], 201);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to add detail'
        ], 400);
    }

    /**
     * Update opname detail
     * PUT /api/inventory/stock-opname/{opname_id}/details/{detail_id}
     */
    public function updateDetail($opnameId = null, $detailId = null)
    {
        $data = $this->request->getRawInput();

        if ($this->detailModel->updatePhysicalStock($detailId, $data['physical_stock'])) {
            return $this->respond([
                'success' => true,
                'message' => 'Detail updated successfully'
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to update detail'
        ], 400);
    }
}
