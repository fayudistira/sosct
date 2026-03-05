<?php

/**
 * Category Controller
 * 
 * Handles category management for the blog.
 */

namespace Modules\Blog\Controllers;

use Modules\Blog\Models\BlogCategoryModel;
use Modules\Blog\Models\BlogPostModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;

class CategoryController extends BaseController
{
    protected $categoryModel;
    protected $postModel;

    public function __construct()
    {
        $this->categoryModel = new BlogCategoryModel();
        $this->postModel = new BlogPostModel();
    }

    /**
     * List all categories
     */
    public function index()
    {
        $data['categories'] = $this->categoryModel->getAllCategoriesAdmin();
        
        return view('Modules\Blog\Views\admin\categories\index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data['categories'] = $this->categoryModel->getAllCategories();
        $data['action'] = 'create';
        
        return view('Modules\Blog\Views\admin\categories\form', $data);
    }

    /**
     * Store new category
     */
    public function store(): RedirectResponse
    {
        $validation = $this->validate([
            'name' => 'required|max_length[100]',
            'slug' => 'permit_empty|max_length[100]|alpha_dash',
            'description' => 'permit_empty',
            'parent_id' => 'permit_empty|integer',
        ]);

        if (!$validation) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: '',
            'description' => $this->request->getPost('description') ?: '',
            'image' => $this->request->getPost('image') ?: '',
            'parent_id' => !empty($this->request->getPost('parent_id')) ? (int) $this->request->getPost('parent_id') : null,
            'display_order' => (int) $this->request->getPost('display_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 1,
        ];

        try {
            $this->categoryModel->createCategory($data);
            
            return redirect()->to('admin/blog/categories')
                ->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create category: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form
     */
    public function edit(int $id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('admin/blog/categories')
                ->with('error', 'Category not found!');
        }

        $data['category'] = $category;
        $data['categories'] = $this->categoryModel->getAllCategories();
        $data['action'] = 'edit';
        
        return view('Modules\Blog\Views\admin\categories\form', $data);
    }

    /**
     * Update existing category
     */
    public function update(int $id): RedirectResponse
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('admin/blog/categories')
                ->with('error', 'Category not found!');
        }

        $validation = $this->validate([
            'name' => 'required|max_length[100]',
            'slug' => 'permit_empty|max_length[100]|alpha_dash',
            'description' => 'permit_empty',
            'parent_id' => 'permit_empty|integer',
        ]);

        if (!$validation) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Prevent setting itself as parent
        $parentId = $this->request->getPost('parent_id');
        if ($parentId == $id) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Category cannot be its own parent!');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug') ?: '',
            'description' => $this->request->getPost('description') ?: '',
            'image' => $this->request->getPost('image') ?: '',
            'parent_id' => !empty($parentId) ? (int) $parentId : null,
            'display_order' => (int) $this->request->getPost('display_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        try {
            $this->categoryModel->updateCategory($id, $data);
            
            return redirect()->to('admin/blog/categories')
                ->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    /**
     * Delete category
     */
    public function delete(int $id): RedirectResponse
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('admin/blog/categories')
                ->with('error', 'Category not found!');
        }

        // Check if category has posts
        $postCount = $this->postModel->where('category_id', $id)->countAllResults();
        
        if ($postCount > 0) {
            return redirect()->to('admin/blog/categories')
                ->with('error', 'Cannot delete category with ' . $postCount . ' posts. Please reassign or delete the posts first.');
        }

        // Check if category has children
        $childCount = $this->categoryModel->where('parent_id', $id)->countAllResults();
        
        if ($childCount > 0) {
            return redirect()->to('admin/blog/categories')
                ->with('error', 'Cannot delete category with subcategories. Please delete or reassign subcategories first.');
        }

        try {
            $this->categoryModel->delete($id);
            
            return redirect()->to('admin/blog/categories')
                ->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->to('admin/blog/categories')
                ->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }

    /**
     * Toggle category active status
     */
    public function toggle(int $id): RedirectResponse
    {
        $result = $this->categoryModel->toggleStatus($id);
        
        if (!$result) {
            return redirect()->to('admin/blog/categories')
                ->with('error', 'Category not found!');
        }

        return redirect()->to('admin/blog/categories')
            ->with('success', 'Category status updated successfully!');
    }
}
