<?php

namespace App\Models;

use CodeIgniter\Model;

class PageviewModel extends Model
{
    protected $table            = 'pageviews';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['page_url', 'page_name', 'view_count', 'last_viewed_at', 'created_at', 'updated_at'];
    protected $useTimestamps      = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Record a page view - increments the view count or creates new record
     *
     * @param string $pageUrl The URL of the page
     * @param string|null $pageName Optional human-readable name for the page
     * @return int The current view count
     */
    public function recordPageView(string $pageUrl, ?string $pageName = null): int
    {
        // Normalize the URL
        $normalizedUrl = strtolower(trim($pageUrl));

        // Check if record exists
        $existing = $this->where('page_url', $normalizedUrl)->first();

        if ($existing) {
            // Increment view count
            $newCount = $existing['view_count'] + 1;
            $this->update($existing['id'], [
                'view_count'      => $newCount,
                'last_viewed_at'  => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);
            return $newCount;
        } else {
            // Create new record
            $this->insert([
                'page_url'        => $normalizedUrl,
                'page_name'       => $pageName ?? basename($pageUrl),
                'view_count'      => 1,
                'last_viewed_at'  => date('Y-m-d H:i:s'),
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);
            return 1;
        }
    }

    /**
     * Get view count for a specific page
     *
     * @param string $pageUrl The URL of the page
     * @return int The view count (0 if not found)
     */
    public function getPageViewCount(string $pageUrl): int
    {
        $normalizedUrl = strtolower(trim($pageUrl));
        $page = $this->where('page_url', $normalizedUrl)->first();

        return $page ? (int) $page['view_count'] : 0;
    }

    /**
     * Get all pageviews ordered by view count
     *
     * @param int $limit Number of records to return
     * @return array
     */
    public function getTopPages(int $limit = 10): array
    {
        return $this->orderBy('view_count', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
