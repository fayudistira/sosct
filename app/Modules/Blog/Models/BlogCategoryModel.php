<?php

/**
 * Blog Category Model
 * 
 * Handles all database operations for blog categories including:
 * - CRUD operations
 * - Hierarchical category support
 * - Slug generation
 */

namespace Modules\Blog\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class BlogCategoryModel extends Model
{
    protected $table            = 'blog_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'display_order',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected array $casts = [
        'id' => 'int',
        'display_order' => 'int',
        'is_active' => 'bool',
    ];

    protected $dates = ['created_at', 'updated_at'];
    
    // Validation rules
    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'slug' => 'required|max_length[100]|alpha_dash',
    ];

    /**
     * Initialize model
     */
    public function initialize()
    {
        parent::initialize();
        $this->updatedAt = Time::now()->toDateTimeString();
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get parent category
     */
    public function parent()
    {
        return $this->belongsTo('Modules\Blog\Models\BlogCategoryModel', 'parent_id', 'id');
    }

    /**
     * Get subcategories
     */
    public function children()
    {
        return $this->hasMany('Modules\Blog\Models\BlogCategoryModel', 'parent_id', 'id');
    }

    /**
     * Get posts in category
     */
    public function posts()
    {
        return $this->hasMany('Modules\Blog\Models\BlogPostModel', 'category_id', 'id');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope: Active categories only
     */
    public function active()
    {
        return $this->where('is_active', 1);
    }

    /**
     * Scope: Root categories (no parent)
     */
    public function root()
    {
        return $this->where('parent_id', null);
    }

    /**
     * Scope: Ordered by display order
     */
    public function ordered()
    {
        return $this->orderBy('display_order', 'ASC');
    }

    // ==========================================
    // CRUD OPERATIONS
    // ==========================================

    /**
     * Get all active categories
     */
    public function getAllCategories()
    {
        return $this->active()
            ->ordered()
            ->find();
    }

    /**
     * Get all categories for admin (including inactive)
     */
    public function getAllCategoriesAdmin()
    {
        return $this->ordered()
            ->find();
    }

    /**
     * Get category by slug
     */
    public function getCategoryBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Get category with post count
     */
    public function getCategoriesWithPostCount()
    {
        $categories = $this->active()->ordered()->find();
        
        $postModel = new BlogPostModel();
        
        foreach ($categories as &$category) {
            $category['post_count'] = $postModel->where('category_id', $category['id'])
                ->published()
                ->countAllResults();
        }
        
        return $categories;
    }

    /**
     * Get hierarchical categories
     */
    public function getHierarchicalCategories()
    {
        $categories = $this->active()->ordered()->find();
        
        return $this->buildTree($categories);
    }

    /**
     * Build category tree from flat array
     */
    protected function buildTree(array $categories, ?int $parentId = null): array
    {
        $tree = [];
        
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $children = $this->buildTree($categories, $category['id']);
                
                if ($children) {
                    $category['children'] = $children;
                }
                
                $tree[] = $category;
            }
        }
        
        return $tree;
    }

    /**
     * Get parent categories (breadcrumb)
     */
    public function getBreadcrumb(int $categoryId): array
    {
        $breadcrumb = [];
        $current = $this->find($categoryId);
        
        while ($current) {
            array_unshift($breadcrumb, $current);
            
            if ($current['parent_id']) {
                $current = $this->find($current['parent_id']);
            } else {
                $current = null;
            }
        }
        
        return $breadcrumb;
    }

    // ==========================================
    // SLUG GENERATION
    // ==========================================

    /**
     * Generate unique slug from name
     */
    public function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->where('slug', $slug)->first()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // ==========================================
    // CREATE/UPDATE HELPERS
    // ==========================================

    /**
     * Create a new category
     */
    public function createCategory(array $data): int
    {
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        $data['created_at'] = Time::now()->toDateTimeString();
        $data['updated_at'] = Time::now()->toDateTimeString();

        return $this->insert($data);
    }

    /**
     * Update an existing category
     */
    public function updateCategory(int $id, array $data): bool
    {
        if (isset($data['name'])) {
            $category = $this->find($id);
            if ($category && $category['name'] !== $data['name'] && empty($data['slug'])) {
                $data['slug'] = $this->generateSlug($data['name']);
            }
        }

        $data['updated_at'] = Time::now()->toDateTimeString();

        return $this->update($id, $data);
    }

    /**
     * Toggle category active status
     */
    public function toggleStatus(int $id): bool
    {
        $category = $this->find($id);
        
        if (!$category) {
            return false;
        }

        return $this->update($id, [
            'is_active' => $category['is_active'] ? 0 : 1,
            'updated_at' => Time::now()->toDateTimeString()
        ]);
    }

    /**
     * Reorder categories
     */
    public function reorder(array $order): bool
    {
        foreach ($order as $index => $categoryId) {
            $this->update($categoryId, [
                'display_order' => $index + 1,
                'updated_at' => Time::now()->toDateTimeString()
            ]);
        }

        return true;
    }
}
