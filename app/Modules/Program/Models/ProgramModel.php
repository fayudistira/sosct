<?php

namespace Modules\Program\Models;

use CodeIgniter\Model;

class ProgramModel extends Model
{
    protected $table = 'programs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    
    protected $allowedFields = [
        'id',
        'title',
        'description',
        'thumbnail',
        'features',
        'facilities',
        'extra_facilities',
        'registration_fee',
        'tuition_fee',
        'discount',
        'category',
        'sub_category',
        'status'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'registration_fee' => 'permit_empty|decimal',
        'tuition_fee' => 'permit_empty|decimal',
        'discount' => 'permit_empty|decimal|less_than_equal_to[100]',
        'status' => 'required|in_list[active,inactive]'
    ];
    
    protected $validationMessages = [
        'title' => [
            'required' => 'Program title is required.',
            'min_length' => 'Program title must be at least 3 characters.'
        ],
        'discount' => [
            'less_than_equal_to' => 'Discount cannot exceed 100%.'
        ]
    ];
    
    protected $beforeInsert = ['generateUUID', 'encodeJsonFields'];
    protected $beforeUpdate = ['encodeJsonFields'];
    protected $afterFind = ['decodeJsonFields'];
    
    /**
     * Generate UUID for new records
     */
    protected function generateUUID(array $data): array
    {
        if (!isset($data['data']['id'])) {
            $data['data']['id'] = $this->generateUUIDv4();
        }
        return $data;
    }
    
    /**
     * Generate UUID v4
     */
    private function generateUUIDv4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    
    /**
     * Encode JSON fields before save
     */
    protected function encodeJsonFields(array $data): array
    {
        $jsonFields = ['features', 'facilities', 'extra_facilities'];
        
        foreach ($jsonFields as $field) {
            if (isset($data['data'][$field])) {
                if (is_string($data['data'][$field])) {
                    // Convert multiline string to array
                    $lines = array_filter(array_map('trim', explode("\n", $data['data'][$field])));
                    $data['data'][$field] = json_encode(array_values($lines));
                } elseif (is_array($data['data'][$field])) {
                    $data['data'][$field] = json_encode($data['data'][$field]);
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Decode JSON fields after fetch
     */
    protected function decodeJsonFields(array $data): array
    {
        $jsonFields = ['features', 'facilities', 'extra_facilities'];
        
        if (isset($data['data'])) {
            foreach ($jsonFields as $field) {
                if (isset($data['data'][$field]) && is_string($data['data'][$field])) {
                    $data['data'][$field] = json_decode($data['data'][$field], true) ?? [];
                }
            }
        } elseif (isset($data['id'])) {
            foreach ($jsonFields as $field) {
                if (isset($data[$field]) && is_string($data[$field])) {
                    $data[$field] = json_decode($data[$field], true) ?? [];
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Get programs with pagination
     */
    public function getWithPagination(int $perPage = 10)
    {
        return $this->orderBy('created_at', 'DESC')->paginate($perPage);
    }
    
    /**
     * Search programs
     */
    public function searchPrograms(string $keyword)
    {
        return $this->like('title', $keyword)
                    ->orLike('description', $keyword)
                    ->orLike('category', $keyword)
                    ->findAll();
    }
    
    /**
     * Filter by status
     */
    public function filterByStatus(string $status)
    {
        return $this->where('status', $status)->findAll();
    }
    
    /**
     * Filter by category
     */
    public function filterByCategory(string $category)
    {
        return $this->where('category', $category)->findAll();
    }
    
    /**
     * Get active programs only
     */
    public function getActivePrograms()
    {
        return $this->where('status', 'active')->findAll();
    }
    
    /**
     * Get programs grouped by category
     */
    public function getProgramsByCategory(): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        
        $results = $builder->select('category, COUNT(*) as total')
                          ->where('deleted_at', null)
                          ->groupBy('category')
                          ->orderBy('category', 'ASC')
                          ->get()
                          ->getResultArray();
        
        return $results;
    }
}
