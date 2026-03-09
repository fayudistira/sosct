<?php

namespace Modules\Inventory\Controllers;

use App\Controllers\BaseController;
use Modules\Inventory\Models\AlertModel;
use Modules\Inventory\Models\ItemModel;

class AlertController extends BaseController
{
    protected $alertModel;
    protected $itemModel;

    public function __construct()
    {
        $this->alertModel = new AlertModel();
        $this->itemModel = new ItemModel();
    }

    /**
     * List all alerts
     */
    public function index()
    {
        $status = $this->request->getGet('status');
        $type = $this->request->getGet('type');
        
        $builder = $this->alertModel->builder();
        
        if ($status) {
            $builder->where('status', $status);
        } else {
            // Default to active
            $builder->where('status', 'active');
        }
        
        if ($type) {
            $builder->where('alert_type', $type);
        }

        $alerts = $this->alertModel->orderBy('created_at', 'DESC')->findAll();

        // Get item names
        $itemIds = array_unique(array_column($alerts, 'item_id'));
        $items = [];
        foreach ($itemIds as $id) {
            $item = $this->itemModel->find($id);
            if ($item) {
                $items[$id] = $item;
            }
        }

        $counts = $this->alertModel->getCounts();

        $data = [
            'alerts' => $alerts,
            'items' => $items,
            'types' => $this->alertModel->getTypes(),
            'statuses' => ['active' => 'Active', 'resolved' => 'Resolved'],
            'counts' => $counts,
            'selectedStatus' => $status,
            'selectedType' => $type
        ];

        return view('Modules\Inventory\Views\alerts\index', $data);
    }

    /**
     * Resolve alert
     */
    public function resolve($id)
    {
        $data = $this->request->getPost();
        $notes = $data['notes'] ?? null;

        if ($this->alertModel->resolve($id, $notes)) {
            return redirect()->to('/inventory/alerts')->with('success', 'Alert resolved successfully');
        }

        return redirect()->back()->with('error', 'Failed to resolve alert');
    }
}
