<?php

namespace Modules\Inventory\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'inventory_categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'id',
        'name',
        'description',
        'parent_id',
        'sort_order',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'parent_id' => 'permit_empty|is_natural',
        'sort_order' => 'permit_empty|is_natural'
    ];

    /**
     * Get categories as a tree (hierarchical)
     */
    public function getTree(): array
    {
        $categories = $this->orderBy('sort_order', 'ASC')->findAll();
        return $this->buildTree($categories);
    }

    /**
     * Build tree structure from flat array
     */
    protected function buildTree(array $elements, string $parentId = null): array
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['parent_id'] === $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    /**
     * Get subcategories
     */
    public function getSubcategories(string $parentId): array
    {
        return $this->where('parent_id', $parentId)->findAll();
    }

    /**
     * Get category path (breadcrumb)
     */
    public function getPath(string $categoryId): array
    {
        $path = [];
        $current = $this->find($categoryId);

        while ($current) {
            array_unshift($path, $current);
            if ($current['parent_id']) {
                $current = $this->find($current['parent_id']);
            } else {
                break;
            }
        }

        return $path;
    }
}
