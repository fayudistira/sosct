<?php

namespace App\Models;

use CodeIgniter\Model;

class TermsConditionModel extends Model
{
    protected $table            = 'terms_conditions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes    = false;
    protected $allowedFields    = [
        'language',
        'title',
        'content',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'language' => 'required|max_length[100]',
        'title'    => 'required|max_length[255]',
        'content'  => 'required',
    ];

    /**
     * Get active terms by language
     */
    public function getActiveByLanguage(string $language): ?array
    {
        return $this->where('language', $language)
                    ->where('is_active', 1)
                    ->first();
    }

    /**
     * Get all active terms
     */
    public function getAllActive(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('language', 'ASC')
                    ->findAll();
    }

    /**
     * Get all terms (including inactive)
     */
    public function getAllTerms(): array
    {
        return $this->orderBy('language', 'ASC')
                    ->findAll();
    }

    /**
     * Get available languages from terms
     */
    public function getAvailableLanguages(): array
    {
        return $this->select('language')
                    ->where('is_active', 1)
                    ->findAll();
    }

    /**
     * Check if terms exist for a language
     */
    public function hasTermsForLanguage(string $language): bool
    {
        return $this->where('language', $language)
                    ->where('is_active', 1)
                    ->countAllResults() > 0;
    }

    /**
     * Get available languages from programs table
     */
    public static function getAvailableProgramLanguages(): array
    {
        $db = \Config\Database::connect();
        return $db->table('programs')
                  ->select('language')
                  ->where('language IS NOT NULL')
                  ->where('language !=', '')
                  ->groupBy('language')
                  ->orderBy('language', 'ASC')
                  ->get()
                  ->getResultArray();
    }
}
