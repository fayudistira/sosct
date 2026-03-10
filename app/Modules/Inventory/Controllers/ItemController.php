<?php

namespace Modules\Inventory\Controllers;

use App\Controllers\BaseController;
use Modules\Inventory\Models\ItemModel;
use Modules\Inventory\Models\CategoryModel;
use Modules\Inventory\Models\LocationModel;
use Modules\Program\Models\ProgramModel;

class ItemController extends BaseController
{
    protected $itemModel;
    protected $categoryModel;
    protected $locationModel;
    protected $programModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->categoryModel = new CategoryModel();
        $this->locationModel = new LocationModel();
        $this->programModel = new ProgramModel();
    }

    /**
     * List all items
     */
    public function index()
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $location = $this->request->getGet('location');
        $status = $this->request->getGet('status');

        $builder = $this->itemModel->builder();
        
        if ($search) {
            $builder->like('name', $search)
                    ->orLike('item_code', $search)
                    ->orLike('barcode', $search);
        }
        
        if ($category) {
            $builder->where('category_id', $category);
        }
        
        if ($location) {
            $builder->where('location_id', $location);
        }
        
        if ($status) {
            $builder->where('status', $status);
        } else {
            $builder->where('status', 'active');
        }

        $data = [
            'items' => $this->itemModel->paginate($perPage, 'default', $page),
            'pager' => $this->itemModel->pager,
            'categories' => $this->categoryModel->findAll(),
            'locations' => $this->locationModel->findAll(),
            'programs' => $this->programModel->where('status', 'active')->findAll(),
            'search' => $search,
            'selectedCategory' => $category,
            'selectedLocation' => $location,
            'selectedStatus' => $status
        ];

        // Index categories and locations by ID for easy lookup in view
        $categoryList = [];
        foreach ($this->categoryModel->findAll() as $cat) {
            $categoryList[$cat['id']] = $cat;
        }
        $locationList = [];
        foreach ($this->locationModel->findAll() as $loc) {
            $locationList[$loc['id']] = $loc;
        }
        $data['categories'] = $categoryList;
        $data['locations'] = $locationList;
        
        // Also keep original arrays for filter dropdowns
        $data['categoryList'] = $this->categoryModel->findAll();
        $data['locationList'] = $this->locationModel->findAll();

        return view('Modules\Inventory\Views\items\index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'categories' => $this->categoryModel->findAll(),
            'locations' => $this->locationModel->findAll(),
            'programs' => $this->programModel->where('status', 'active')->findAll(),
            'units' => $this->itemModel->getUnits(),
            'itemCode' => $this->itemModel->generateItemCode()
        ];

        return view('Modules\Inventory\Views\items\create', $data);
    }

    /**
     * Store new item
     */
    public function store()
    {
        $data = $this->request->getPost();
        
        // Generate ID if not provided
        if (empty($data['id'])) {
            $data['id'] = uuid_v4();
        }
        
        // Generate item code if not provided
        if (empty($data['item_code'])) {
            $data['item_code'] = $this->itemModel->generateItemCode();
        }

        // Handle barcode - remove if empty to avoid unique constraint errors
        if (empty($data['barcode'])) {
            unset($data['barcode']);
        }

        // Handle specifications JSON
        if (isset($data['specifications']) && is_array($data['specifications'])) {
            $data['specifications'] = json_encode($data['specifications']);
        }

        if ($this->itemModel->insert($data)) {
            return redirect()->to('/inventory/items')->with('success', 'Item created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create item: ' . implode(', ', $this->itemModel->errors()));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $item = $this->itemModel->find($id);
        
        if (!$item) {
            return redirect()->to('/inventory/items')->with('error', 'Item not found');
        }

        // Decode specifications
        if ($item['specifications']) {
            $item['specifications'] = json_decode($item['specifications'], true);
        }

        $data = [
            'item' => $item,
            'categories' => $this->categoryModel->findAll(),
            'locations' => $this->locationModel->findAll(),
            'programs' => $this->programModel->where('status', 'active')->findAll(),
            'units' => $this->itemModel->getUnits()
        ];

        return view('Modules\Inventory\Views\items\edit', $data);
    }

    /**
     * Update item
     */
    public function update($id)
    {
        $data = $this->request->getPost();
        
        // Include ID for validation context
        $data['id'] = $id;

        // Handle barcode - remove if empty to avoid unique constraint errors
        if (isset($data['barcode']) && empty($data['barcode'])) {
            unset($data['barcode']);
        }

        // Handle specifications JSON
        if (isset($data['specifications']) && is_array($data['specifications'])) {
            $data['specifications'] = json_encode($data['specifications']);
        }

        if ($this->itemModel->update($id, $data)) {
            return redirect()->to('/inventory/items')->with('success', 'Item updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update item: ' . implode(', ', $this->itemModel->errors()));
    }

    /**
     * View item details
     */
    public function view($id)
    {
        $item = $this->itemModel->find($id);
        
        if (!$item) {
            return redirect()->to('/inventory/items')->with('error', 'Item not found');
        }

        // Decode specifications
        if ($item['specifications']) {
            $item['specifications'] = json_decode($item['specifications'], true);
        }

        // Get related data
        $category = $item['category_id'] ? $this->categoryModel->find($item['category_id']) : null;
        $location = $item['location_id'] ? $this->locationModel->find($item['location_id']) : null;
        
        // Get movement history
        $movementModel = new \Modules\Inventory\Models\MovementModel();
        $movements = $movementModel->where('item_id', $id)
                                   ->orderBy('movement_date', 'DESC')
                                   ->limit(10)
                                   ->findAll();

        $data = [
            'item' => $item,
            'category' => $category,
            'location' => $location,
            'movements' => $movements
        ];

        return view('Modules\Inventory\Views\items\view', $data);
    }

    /**
     * Generate barcode view
     */
    public function barcode($id)
    {
        $item = $this->itemModel->find($id);
        
        if (!$item) {
            return redirect()->to('/inventory/items')->with('error', 'Item not found');
        }

        $data = [
            'item' => $item
        ];

        return view('Modules\Inventory\Views\items\barcode', $data);
    }

    /**
     * Delete item (soft delete)
     */
    public function delete($id)
    {
        if ($this->itemModel->delete($id)) {
            return redirect()->to('/inventory/items')->with('success', 'Item deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete item');
    }

    /**
     * Search items (AJAX)
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        if (!$keyword) {
            return json_encode([]);
        }

        $items = $this->itemModel->search($keyword);
        
        return json_encode($items);
    }
}
