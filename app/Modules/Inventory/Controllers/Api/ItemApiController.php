<?php

namespace Modules\Inventory\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Inventory\Models\ItemModel;
use Modules\Inventory\Models\CategoryModel;
use Modules\Inventory\Models\LocationModel;

class ItemApiController extends ResourceController
{
    protected $itemModel;
    protected $categoryModel;
    protected $locationModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->categoryModel = new CategoryModel();
        $this->locationModel = new LocationModel();
    }

    /**
     * Get all items
     * GET /api/inventory/items
     */
    public function index()
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('per_page') ?? 20);
        $status = $this->request->getGet('status') ?? 'active';

        $items = $this->itemModel->where('status', $status)->paginate($perPage, 'default', $page);

        return $this->respond([
            'success' => true,
            'data' => $items,
            'pagination' => [
                'page' => $this->itemModel->pager->getCurrentPage(),
                'per_page' => $perPage,
                'total' => $this->itemModel->pager->getTotal(),
                'page_count' => $this->itemModel->pager->getPageCount()
            ]
        ]);
    }

    /**
     * Get single item
     * GET /api/inventory/items/{id}
     */
    public function show($id = null)
    {
        $item = $this->itemModel->find($id);

        if (!$item) {
            return $this->respond([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        // Get related data
        $category = $item['category_id'] ? $this->categoryModel->find($item['category_id']) : null;
        $location = $item['location_id'] ? $this->locationModel->find($item['location_id']) : null;

        $item['category'] = $category;
        $item['location'] = $location;

        return $this->respond([
            'success' => true,
            'data' => $item
        ]);
    }

    /**
     * Create new item
     * POST /api/inventory/items
     */
    public function create()
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

        // Handle specifications JSON
        if (isset($data['specifications']) && is_array($data['specifications'])) {
            $data['specifications'] = json_encode($data['specifications']);
        }

        if ($this->itemModel->insert($data)) {
            return $this->respond([
                'success' => true,
                'message' => 'Item created successfully',
                'data' => $this->itemModel->find($data['id'])
            ], 201);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to create item',
            'errors' => $this->itemModel->errors()
        ], 400);
    }

    /**
     * Update item
     * PUT /api/inventory/items/{id}
     */
    public function update($id = null)
    {
        $item = $this->itemModel->find($id);

        if (!$item) {
            return $this->respond([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $data = $this->request->getRawInput();

        // Handle specifications JSON
        if (isset($data['specifications']) && is_array($data['specifications'])) {
            $data['specifications'] = json_encode($data['specifications']);
        }

        if ($this->itemModel->update($id, $data)) {
            return $this->respond([
                'success' => true,
                'message' => 'Item updated successfully',
                'data' => $this->itemModel->find($id)
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to update item',
            'errors' => $this->itemModel->errors()
        ], 400);
    }

    /**
     * Delete item
     * DELETE /api/inventory/items/{id}
     */
    public function delete($id = null)
    {
        $item = $this->itemModel->find($id);

        if (!$item) {
            return $this->respond([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        if ($this->itemModel->delete($id)) {
            return $this->respond([
                'success' => true,
                'message' => 'Item deleted successfully'
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to delete item'
        ], 400);
    }

    /**
     * Search items
     * GET /api/inventory/items/search
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');

        if (!$keyword) {
            return $this->respond([
                'success' => true,
                'data' => []
            ]);
        }

        $items = $this->itemModel->search($keyword);

        return $this->respond([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Get barcode
     * GET /api/inventory/items/barcode/{id}
     */
    public function barcode($id = null)
    {
        $item = $this->itemModel->find($id);

        if (!$item) {
            return $this->respond([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        return $this->respond([
            'success' => true,
            'data' => [
                'item_id' => $item['id'],
                'item_code' => $item['item_code'],
                'barcode' => $item['barcode'],
                'name' => $item['name']
            ]
        ]);
    }

    /**
     * Get low stock items
     * GET /api/inventory/items/low-stock
     */
    public function getLowStock()
    {
        $items = $this->itemModel->getLowStock();

        return $this->respond([
            'success' => true,
            'data' => $items,
            'total' => count($items)
        ]);
    }
}
