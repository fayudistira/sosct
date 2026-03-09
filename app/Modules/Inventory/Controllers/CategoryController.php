<?php

namespace Modules\Inventory\Controllers;

use App\Controllers\BaseController;
use Modules\Inventory\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    /**
     * List all categories
     */
    public function index()
    {
        $categories = $this->categoryModel->orderBy('sort_order', 'ASC')->findAll();
        
        // Build tree structure
        $tree = $this->categoryModel->getTree();

        $data = [
            'categories' => $categories,
            'tree' => $tree
        ];

        return view('Modules\Inventory\Views\categories\index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $parentId = $this->request->getGet('parent');
        
        $data = [
            'categories' => $this->categoryModel->findAll(),
            'parentId' => $parentId
        ];

        return view('Modules\Inventory\Views\categories\create', $data);
    }

    /**
     * Store new category
     */
    public function store()
    {
        $data = $this->request->getPost();
        
        // Generate ID if not provided
        if (empty($data['id'])) {
            $data['id'] = uuid_v4();
        }
        
        // Handle parent_id - set to null if empty
        if (empty($data['parent_id'])) {
            $data['parent_id'] = null;
        }
        
        if ($this->categoryModel->insert($data)) {
            return redirect()->to('/inventory/categories')->with('success', 'Category created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create category: ' . implode(', ', $this->categoryModel->errors()));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/inventory/categories')->with('error', 'Category not found');
        }

        $data = [
            'category' => $category,
            'categories' => $this->categoryModel->findAll()
        ];

        return view('Modules\Inventory\Views\categories\edit', $data);
    }

    /**
     * Update category
     */
    public function update($id)
    {
        $data = $this->request->getPost();

        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('/inventory/categories')->with('success', 'Category updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update category: ' . implode(', ', $this->categoryModel->errors()));
    }

    /**
     * Delete category
     */
    public function delete($id)
    {
        // Check if category has items
        $itemModel = new \Modules\Inventory\Models\ItemModel();
        $items = $itemModel->where('category_id', $id)->countAllResults();
        
        if ($items > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated items');
        }

        // Check for subcategories
        $subcategories = $this->categoryModel->where('parent_id', $id)->countAllResults();
        
        if ($subcategories > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with subcategories');
        }

        if ($this->categoryModel->delete($id)) {
            return redirect()->to('/inventory/categories')->with('success', 'Category deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete category');
    }
}
