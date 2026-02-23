<?php

namespace Modules\Tools\Hanzi\Models;

use CodeIgniter\Model;

class HanziModel extends Model
{
    protected $table = 'hanzi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'hanzi',
        'pinyin',
        'category',
        'translation',
        'example',
        'stroke_count',
        'frequency',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'hanzi' => 'required|max_length[50]|is_unique[hanzi.hanzi,id,{id}]',
        'pinyin' => 'required|max_length[100]',
        'category' => 'required|in_list[HSK1,HSK2,HSK3,HSK4,HSK5,HSK6,OTHER]',
    ];

    protected $validationMessages = [
        'hanzi' => [
            'required' => 'The hanzi character is required',
            'is_unique' => 'This hanzi character already exists',
        ],
        'pinyin' => [
            'required' => 'The pinyin is required',
        ],
        'category' => [
            'required' => 'The category is required',
            'in_list' => 'Invalid category selected',
        ],
    ];

    /**
     * Get hanzi by category
     */
    public function getByCategory(string $category): array
    {
        return $this->where('category', $category)->findAll();
    }

    /**
     * Get random hanzi for flashcards
     */
    public function getRandomForFlashcards(int $limit = 10, ?string $category = null): array
    {
        $builder = $this->builder();
        
        if ($category) {
            $builder->where('category', $category);
        }
        
        return $builder->orderBy('RAND()')->limit($limit)->get()->getResult();
    }

    /**
     * Bulk insert hanzi data
     */
    public function bulkInsert(array $data): bool
    {
        $this->db->transStart();
        
        try {
            foreach ($data as $item) {
                // Check if hanzi already exists
                $existing = $this->where('hanzi', $item['hanzi'])->first();
                
                if ($existing) {
                    // Update existing record
                    $this->update($item, $existing->id);
                } else {
                    // Insert new record
                    $this->insert($item);
                }
            }
            
            $this->db->transComplete();
            return true;
        } catch (\Exception $e) {
                $this->db->transRollback();
                log_message('error', 'Bulk insert failed: ' . $e->getMessage());
                return false;
            }
    }

    /**
     * Get categories with count
     */
    public function getCategoriesWithCount(): array
    {
        return $this->builder()
            ->select('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('category', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Search hanzi
     */
    public function searchHanzi(string $keyword, ?string $category = null, int $limit = 20): array
    {
        $builder = $this->builder();
        
        $builder->groupStart();
        $builder->like('hanzi', $keyword);
        $builder->orLike('pinyin', $keyword);
        $builder->groupEnd();
        
        if ($category) {
            $builder->where('category', $category);
        }
        
        return $builder->limit($limit)->get()->getResult();
    }
}