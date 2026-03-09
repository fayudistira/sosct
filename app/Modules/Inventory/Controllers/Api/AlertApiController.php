<?php

namespace Modules\Inventory\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Inventory\Models\AlertModel;
use Modules\Inventory\Models\ItemModel;

class AlertApiController extends ResourceController
{
    protected $alertModel;
    protected $itemModel;

    public function __construct()
    {
        $this->alertModel = new AlertModel();
        $this->itemModel = new ItemModel();
    }

    /**
     * Get all alerts
     * GET /api/inventory/alerts
     */
    public function index()
    {
        $status = $this->request->getGet('status');
        $type = $this->request->getGet('type');
        
        $builder = $this->alertModel->builder();
        
        if ($status) {
            $builder->where('status', $status);
        }
        
        if ($type) {
            $builder->where('alert_type', $type);
        }

        $alerts = $this->alertModel->orderBy('created_at', 'DESC')->findAll();

        // Get item names
        foreach ($alerts as &$alert) {
            $item = $this->itemModel->find($alert['item_id']);
            $alert['item_name'] = $item ? $item['name'] : 'Unknown';
            $alert['item_code'] = $item ? $item['item_code'] : '';
        }

        return $this->respond([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * Get active alerts
     * GET /api/inventory/alerts/active
     */
    public function active()
    {
        $alerts = $this->alertModel->getActive();

        // Get item names
        foreach ($alerts as &$alert) {
            $item = $this->itemModel->find($alert['item_id']);
            $alert['item_name'] = $item ? $item['name'] : 'Unknown';
            $alert['item_code'] = $item ? $item['item_code'] : '';
        }

        $counts = $this->alertModel->getCounts();

        return $this->respond([
            'success' => true,
            'data' => $alerts,
            'counts' => $counts
        ]);
    }

    /**
     * Resolve alert
     * PUT /api/inventory/alerts/{id}/resolve
     */
    public function resolve($id = null)
    {
        $alert = $this->alertModel->find($id);

        if (!$alert) {
            return $this->respond([
                'success' => false,
                'message' => 'Alert not found'
            ], 404);
        }

        $data = $this->request->getRawInput();
        $notes = $data['notes'] ?? null;

        if ($this->alertModel->resolve($id, $notes)) {
            return $this->respond([
                'success' => true,
                'message' => 'Alert resolved successfully'
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to resolve alert'
        ], 400);
    }
}
