<?php

namespace Modules\Inventory\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Inventory\Models\CategoryModel;

class CategoryApiController extends ResourceController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Get all categories
     * GET /api/inventory/categories
     */
    public function index()
    {
        $categories = $this->categoryModel->orderBy('sort_order', 'ASC')->findAll();

        return $this->respond([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get single category
     * GET /api/inventory/categories/{id}
     */
    public function show($id = null)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return $this->respond([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // Get parent category
        if ($category['parent_id']) {
            $category['parent'] = $this->categoryModel->find($category['parent_id']);
        }

        // Get subcategories
        $subcategories = $this->categoryModel->where('parent_id', $id)->findAll();
        if ($subcategories) {
            $category['subcategories'] = $subcategories;
        }

        return $this->respond([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Create new category
     * POST /api/inventory/categories
     */
    public function create()
    {
        $data = $this->request->getPost();

        // Generate ID if not provided
        if (empty($data['id'])) {
            $data['id'] = uuid_v4();
        }

        if ($this->categoryModel->insert($data)) {
            return $this->respond([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $this->categoryModel->find($data['id'])
            ], 201);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to create category',
            'errors' => $this->categoryModel->errors()
        ], 400);
    }

    /**
     * Update category
     * PUT /api/inventory/categories/{id}
     */
    public function update($id = null)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return $this->respond([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $data = $this->request->getRawInput();

        if ($this->categoryModel->update($id, $data)) {
            return $this->respond([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $this->categoryModel->find($id)
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to update category',
            'errors' => $this->categoryModel->errors()
        ], 400);
    }

    /**
     * Delete category
     * DELETE /api/inventory/categories/{id}
     */
    public function delete($id = null)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return $this->respond([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // Check for subcategories
        $subcategories = $this->categoryModel->where('parent_id', $id)->countAllResults();
        if ($subcategories > 0) {
            return $this->respond([
                'success' => false,
                'message' => 'Cannot delete category with subcategories'
            ], 400);
        }

        // Check for items
        $itemModel = new \Modules\Inventory\Models\ItemModel();
        $items = $itemModel->where('category_id', $id)->countAllResults();
        if ($items > 0) {
            return $this->respond([
                'success' => false,
                'message' => 'Cannot delete category with associated items'
            ], 400);
        }

        if ($this->categoryModel->delete($id)) {
            return $this->respond([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to delete category'
        ], 400);
    }

    /**
     * Get category tree
     * GET /api/inventory/categories/tree
     */
    public function tree()
    {
        $tree = $this->categoryModel->getTree();

        return $this->respond([
            'success' => true,
            'data' => $tree
        ]);
    }
}
