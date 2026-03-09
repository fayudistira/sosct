<?php

namespace Modules\Inventory\Controllers;

use App\Controllers\BaseController;
use Modules\Inventory\Models\StockOpnameModel;
use Modules\Inventory\Models\StockOpnameDetailModel;
use Modules\Inventory\Models\ItemModel;
use Modules\Inventory\Models\LocationModel;

class StockOpnameController extends BaseController
{
    protected $stockOpnameModel;
    protected $detailModel;
    protected $itemModel;
    protected $locationModel;

    public function __construct()
    {
        $this->stockOpnameModel = new StockOpnameModel();
        $this->detailModel = new StockOpnameDetailModel();
        $this->itemModel = new ItemModel();
        $this->locationModel = new LocationModel();
    }

    /**
     * List all stock opnames
     */
    public function index()
    {
        $status = $this->request->getGet('status');
        
        $builder = $this->stockOpnameModel->builder();
        
        if ($status) {
            $builder->where('status', $status);
        }

        $opnames = $this->stockOpnameModel->orderBy('created_at', 'DESC')->findAll();

        // Get location names
        $locationIds = array_unique(array_filter(array_column($opnames, 'location_id')));
        $locations = [];
        foreach ($locationIds as $id) {
            $location = $this->locationModel->find($id);
            if ($location) {
                $locations[$id] = $location['name'];
            }
        }

        $data = [
            'opnames' => $opnames,
            'locations' => $locations,
            'statuses' => $this->stockOpnameModel->getStatuses(),
            'selectedStatus' => $status
        ];

        return view('Modules\Inventory\Views\stock-opname\index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'locations' => $this->locationModel->findAll(),
            'opnameNumber' => $this->stockOpnameModel->generateOpnameNumber()
        ];

        return view('Modules\Inventory\Views\stock-opname\create', $data);
    }

    /**
     * Store new stock opname
     */
    public function store()
    {
        $data = $this->request->getPost();
        
        // Generate ID if not provided
        if (empty($data['id'])) {
            $data['id'] = uuid_v4();
        }

        // Generate opname number if not provided
        if (empty($data['opname_number'])) {
            $data['opname_number'] = $this->stockOpnameModel->generateOpnameNumber();
        }

        $data['status'] = 'draft';
        $data['start_date'] = date('Y-m-d H:i:s');

        // Get current user
        if (auth()->loggedIn()) {
            $user = auth()->user();
            $data['performed_by'] = $user->username ?? $user->email ?? 'Unknown';
        }

        if ($this->stockOpnameModel->insert($data)) {
            // Create details for all items in the location
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

            return redirect()->to('/inventory/stock-opname/detail/' . $data['id'])->with('success', 'Stock opname created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create stock opname: ' . implode(', ', $this->stockOpnameModel->errors()));
    }

    /**
     * Show opname detail
     */
    public function detail($id)
    {
        $opname = $this->stockOpnameModel->find($id);
        
        if (!$opname) {
            return redirect()->to('/inventory/stock-opname')->with('error', 'Stock opname not found');
        }

        // Get details with item info
        $details = $this->detailModel->where('opname_id', $id)->findAll();
        
        // Get item names
        $itemIds = array_unique(array_column($details, 'item_id'));
        $items = [];
        foreach ($itemIds as $itemId) {
            $item = $this->itemModel->find($itemId);
            if ($item) {
                $items[$itemId] = $item;
            }
        }

        $location = $opname['location_id'] ? $this->locationModel->find($opname['location_id']) : null;

        // Calculate summary
        $matched = count(array_filter($details, fn($d) => $d['status'] === 'matched'));
        $discrepancy = count(array_filter($details, fn($d) => $d['status'] === 'discrepancy'));
        $pending = count(array_filter($details, fn($d) => $d['status'] === 'pending'));

        $data = [
            'opname' => $opname,
            'details' => $details,
            'items' => $items,
            'location' => $location,
            'statuses' => $this->detailModel->getStatuses(),
            'summary' => [
                'matched' => $matched,
                'discrepancy' => $discrepancy,
                'pending' => $pending,
                'total' => count($details)
            ]
        ];

        return view('Modules\Inventory\Views\stock-opname\detail', $data);
    }

    /**
     * Update opname detail (physical count)
     */
    public function updateDetail($id)
    {
        $data = $this->request->getPost();
        
        $detailId = $data['detail_id'];
        $physicalStock = (int) $data['physical_stock'];

        if ($this->detailModel->updatePhysicalStock($detailId, $physicalStock)) {
            return redirect()->back()->with('success', 'Physical stock updated successfully');
        }

        return redirect()->back()->with('error', 'Failed to update physical stock');
    }

    /**
     * Complete stock opname
     */
    public function complete($id)
    {
        $opname = $this->stockOpnameModel->find($id);
        
        if (!$opname) {
            return redirect()->to('/inventory/stock-opname')->with('error', 'Stock opname not found');
        }

        if ($opname['status'] !== 'draft' && $opname['status'] !== 'in_progress') {
            return redirect()->to('/inventory/stock-opname')->with('error', 'Cannot complete this stock opname');
        }

        if ($this->stockOpnameModel->complete($id)) {
            return redirect()->to('/inventory/stock-opname')->with('success', 'Stock opname completed successfully');
        }

        return redirect()->back()->with('error', 'Failed to complete stock opname');
    }

    /**
     * Cancel stock opname
     */
    public function cancel($id)
    {
        $opname = $this->stockOpnameModel->find($id);
        
        if (!$opname) {
            return redirect()->to('/inventory/stock-opname')->with('error', 'Stock opname not found');
        }

        if ($opname['status'] === 'completed') {
            return redirect()->to('/inventory/stock-opname')->with('error', 'Cannot cancel completed stock opname');
        }

        if ($this->stockOpnameModel->update($id, ['status' => 'cancelled'])) {
            return redirect()->to('/inventory/stock-opname')->with('success', 'Stock opname cancelled successfully');
        }

        return redirect()->back()->with('error', 'Failed to cancel stock opname');
    }
}
