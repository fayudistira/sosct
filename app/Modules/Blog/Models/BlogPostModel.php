<?php

/**
 * Blog Post Model
 * 
 * Handles all database operations for blog posts including:
 * - CRUD operations
 * - SEO slug generation
 * - Search and filtering
 * - Relationships with categories and tags
 */

namespace Modules\Blog\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class BlogPostModel extends Model
{
    protected $table            = 'blog_posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'author_id',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'reading_time',
        'view_count',
        'is_published',
        'is_featured',
        'ai_summary',
        'ai_keywords',
        'published_at',
        'created_at',
        'updated_at'
    ];

    // Validation rules
    protected $validationRules = [
        'title' => 'required|max_length[255]',
        'slug' => 'required|max_length[255]|alpha_dash',
        'content' => 'required',
        'author_id' => 'required|integer',
        'meta_title' => 'max_length[70]',
        'meta_description' => 'max_length[160]',
        'meta_keywords' => 'max_length[255]',
    ];

    /**
     * Initialize model
     */
    public function initialize()
    {
        parent::initialize();
        
        // Set timestamps
        $this->updatedAt = Time::now()->toDateTimeString();
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get author relationship
     */
    public function author()
    {
        return $this->belongsTo('Modules\Users\Models\UserModel', 'author_id', 'id');
    }

    /**
     * Get category relationship
     */
    public function category()
    {
        return $this->belongsTo('Modules\Blog\Models\BlogCategoryModel', 'category_id', 'id');
    }

    /**
     * Get tags relationship (many-to-many)
     */
    public function tags()
    {
        return $this->belongsToMany(
            'Modules\Blog\Models\BlogTagModel', 
            'blog_post_tags', 
            'post_id', 
            'tag_id'
        );
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope: Published posts only
     */
    public function published()
    {
        return $this->where('is_published', 1)
                    ->where('published_at <=', date('Y-m-d H:i:s'));
    }

    /**
     * Scope: Featured posts
     */
    public function featured()
    {
        return $this->where('is_featured', 1);
    }

    /**
     * Scope: By category
     */
    public function byCategory($categoryId)
    {
        return $this->where('category_id', $categoryId);
    }

    /**
     * Scope: By author
     */
    public function byAuthor($authorId)
    {
        return $this->where('author_id', $authorId);
    }

    // ==========================================
    // CRUD OPERATIONS
    // ==========================================

    /**
     * Get all published posts with pagination
     */
    public function getPublishedPosts(int $page = 1, int $perPage = 12)
    {
        return $this->select('blog_posts.*, 
            users.username as author_name,
            blog_categories.name as category_name,
            blog_categories.slug as category_slug')
            ->join('users', 'users.id = blog_posts.author_id', 'left')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->published()
            ->orderBy('published_at', 'DESC')
            ->paginate($perPage, 'default', $page);
    }

    /**
     * Get single post by slug
     */
    public function getPostBySlug(string $slug, bool $publishedOnly = true)
    {
        // First, get the basic post without joins to check existence
        $post = $this->where('slug', $slug)->first();
        
        if (!$post) {
            return null;
        }
        
        // Check published status
        if ($publishedOnly) {
            if (!$post['is_published']) {
                return null;
            }
            if (strtotime($post['published_at']) > time()) {
                return null;
            }
        }
        
        // Now get full data with joins
        return $this->select('blog_posts.*, 
            users.username as author_name,
            auth_identities.secret as author_email,
            blog_categories.name as category_name,
            blog_categories.slug as category_slug')
            ->join('users', 'users.id = blog_posts.author_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = \'email\'', 'left')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->where('blog_posts.slug', $slug)
            ->first();
    }
    
    /**
     * Get single post by ID
     */
    public function getPostById(int $id)
    {
        return $this->select('blog_posts.*, 
            users.username as author_name,
            auth_identities.secret as author_email,
            blog_categories.name as category_name,
            blog_categories.slug as category_slug')
            ->join('users', 'users.id = blog_posts.author_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = \'email\'', 'left')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->where('blog_posts.id', $id)
            ->first();
    }

    /**
     * Get all posts (including drafts) for admin
     */
    public function getAllPosts(int $page = 1, int $perPage = 20)
    {
        return $this->select('blog_posts.*, 
            users.username as author_name,
            blog_categories.name as category_name')
            ->join('users', 'users.id = blog_posts.author_id', 'left')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, 'default', $page);
    }

    /**
     * Get featured posts
     */
    public function getFeaturedPosts(int $limit = 5)
    {
        return $this->select('blog_posts.*, 
            users.username as author_name,
            blog_categories.name as category_name,
            blog_categories.slug as category_slug')
            ->join('users', 'users.id = blog_posts.author_id', 'left')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->published()
            ->featured()
            ->orderBy('published_at', 'DESC')
            ->limit($limit)
            ->find();
    }

    /**
     * Get recent posts
     */
    public function getRecentPosts(int $limit = 5)
    {
        return $this->select('blog_posts.id, blog_posts.title, blog_posts.slug, 
            blog_posts.excerpt, blog_posts.featured_image, blog_posts.published_at,
            blog_posts.reading_time, blog_categories.name as category_name,
            blog_categories.slug as category_slug')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->published()
            ->orderBy('published_at', 'DESC')
            ->limit($limit)
            ->find();
    }

    /**
     * Get posts by category
     */
    public function getPostsByCategory(string $categorySlug, int $page = 1, int $perPage = 12)
    {
        return $this->select('blog_posts.*, 
            users.username as author_name,
            blog_categories.name as category_name,
            blog_categories.slug as category_slug')
            ->join('users', 'users.id = blog_posts.author_id', 'left')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->where('blog_categories.slug', $categorySlug)
            ->published()
            ->orderBy('published_at', 'DESC')
            ->paginate($perPage, 'default', $page);
    }

    /**
     * Get posts by tag
     */
    public function getPostsByTag(string $tagSlug, int $page = 1, int $perPage = 12)
    {
        return $this->select('blog_posts.*, 
            users.username as author_name,
            blog_categories.name as category_name,
            blog_categories.slug as category_slug')
            ->join('users', 'users.id = blog_posts.author_id', 'left')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->join('blog_post_tags', 'blog_post_tags.post_id = blog_posts.id', 'left')
            ->join('blog_tags', 'blog_tags.id = blog_post_tags.tag_id', 'left')
            ->where('blog_tags.slug', $tagSlug)
            ->published()
            ->groupBy('blog_posts.id')
            ->orderBy('published_at', 'DESC')
            ->paginate($perPage, 'default', $page);
    }

    /**
     * Search posts
     */
    public function searchPosts(string $keyword, int $page = 1, int $perPage = 12)
    {
        return $this->select('blog_posts.*, 
            users.username as author_name,
            blog_categories.name as category_name,
            blog_categories.slug as category_slug')
            ->join('users', 'users.id = blog_posts.author_id', 'left')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->groupStart()
                ->like('blog_posts.title', $keyword)
                ->orLike('blog_posts.content', $keyword)
                ->orLike('blog_posts.excerpt', $keyword)
                ->orLike('blog_posts.meta_keywords', $keyword)
            ->groupEnd()
            ->published()
            ->orderBy('published_at', 'DESC')
            ->paginate($perPage, 'default', $page);
    }

    /**
     * Get related posts
     */
    public function getRelatedPosts(int $postId, ?int $categoryId = null, int $limit = 3)
    {
        $builder = $this->select('blog_posts.id, blog_posts.title, blog_posts.slug, 
            blog_posts.excerpt, blog_posts.featured_image, blog_posts.published_at,
            blog_posts.reading_time')
            ->published()
            ->where('blog_posts.id !=', $postId);

        if ($categoryId) {
            $builder->where('blog_posts.category_id', $categoryId);
        }

        return $builder->orderBy('blog_posts.view_count', 'DESC')
            ->limit($limit)
            ->find();
    }

    /**
     * Increment view count
     */
    public function incrementViewCount(int $postId)
    {
        $post = $this->find($postId);
        if ($post) {
            $this->update($postId, ['view_count' => $post['view_count'] + 1]);
        }
    }

    // ==========================================
    // SLUG GENERATION
    // ==========================================

    /**
     * Generate unique slug from title
     */
    public function generateSlug(string $title): string
    {
        // Convert to lowercase and replace spaces with hyphens
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Check if slug exists
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->where('slug', $slug)->first()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // ==========================================
    // READING TIME CALCULATION
    // ==========================================

    /**
     * Calculate reading time based on word count
     */
    public function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        $wordsPerMinute = config('Blog')->wordsPerMinute ?? 200;
        
        return max(1, ceil($wordCount / $wordsPerMinute));
    }

    /**
     * Auto-generate excerpt from content
     */
    public function generateExcerpt(string $content, int $length = 55): string
    {
        $text = strip_tags($content);
        $text = str_replace(['&nbsp;', '&amp;', '&quot;', '&#39;'], [' ', '&', '"', "'"], $text);
        $text = trim($text);
        
        if (strlen($text) <= $length) {
            return $text;
        }
        
        $excerpt = substr($text, 0, $length);
        $excerpt = substr($excerpt, 0, strrpos($excerpt, ' '));
        
        return $excerpt . '...';
    }

    // ==========================================
    // STATISTICS
    // ==========================================

    /**
     * Get blog statistics
     */
    public function getStats()
    {
        $totalPosts = $this->countAll();
        $publishedPosts = $this->published()->countAllResults();
        $draftPosts = $totalPosts - $publishedPosts;
        $featuredPosts = $this->featured()->published()->countAllResults();
        
        // Get total views
        $result = $this->selectSum('view_count')->first();
        $totalViews = $result['view_count'] ?? 0;
        
        return [
            'total_posts' => $totalPosts,
            'published_posts' => $publishedPosts,
            'draft_posts' => $draftPosts,
            'featured_posts' => $featuredPosts,
            'total_views' => $totalViews,
        ];
    }

    /**
     * Get popular posts
     */
    public function getPopularPosts(int $limit = 5)
    {
        return $this->select('blog_posts.id, blog_posts.title, blog_posts.slug, 
            blog_posts.view_count, blog_posts.featured_image, blog_posts.published_at,
            blog_categories.name as category_name,
            blog_categories.slug as category_slug')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->published()
            ->orderBy('view_count', 'DESC')
            ->limit($limit)
            ->find();
    }

    // ==========================================
    // TAG OPERATIONS
    // ==========================================

    /**
     * Attach tags to a post
     */
    public function attachTags(int $postId, array $tagIds)
    {
        $db = \Config\Database::connect();
        
        // Remove existing tags
        $db->table('blog_post_tags')->where('post_id', $postId)->delete();
        
        // Add new tags
        if (!empty($tagIds)) {
            $data = array_map(function($tagId) use ($postId) {
                return ['post_id' => $postId, 'tag_id' => $tagId];
            }, $tagIds);
            
            $db->table('blog_post_tags')->insertBatch($data);
        }
    }

    /**
     * Get tags for a post
     */
    public function getPostTags(int $postId): array
    {
        $db = \Config\Database::connect();
        
        return $db->table('blog_tags')
            ->join('blog_post_tags', 'blog_post_tags.tag_id = blog_tags.id')
            ->where('blog_post_tags.post_id', $postId)
            ->get()
            ->getResultArray();
    }

    // ==========================================
    // CREATE/UPDATE HELPERS
    // ==========================================

    /**
     * Create a new post with all necessary calculations
     */
    public function createPost(array $data): int
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }

        // Calculate reading time
        if (!isset($data['reading_time'])) {
            $data['reading_time'] = $this->calculateReadingTime($data['content']);
        }

        // Generate excerpt if not provided
        if (empty($data['excerpt'])) {
            $config = config('Blog');
            $data['excerpt'] = $this->generateExcerpt(
                $data['content'], 
                $config->excerptLength ?? 55
            );
        }

        // Set timestamps
        $data['created_at'] = Time::now()->toDateTimeString();
        $data['updated_at'] = Time::now()->toDateTimeString();

        // Set published_at if publishing
        if (isset($data['is_published']) && $data['is_published'] == 1 && empty($data['published_at'])) {
            $data['published_at'] = Time::now()->toDateTimeString();
        }

        // Insert post
        $postId = $this->insert($data);

        // Attach tags if provided
        if (isset($data['tags']) && is_array($data['tags'])) {
            $this->attachTags($postId, $data['tags']);
        }

        return $postId;
    }

    /**
     * Update an existing post
     */
    public function updatePost(int $id, array $data): bool
    {
        // Regenerate slug if title changed
        if (isset($data['title'])) {
            $post = $this->find($id);
            if ($post && $post['title'] !== $data['title'] && empty($data['slug'])) {
                $data['slug'] = $this->generateSlug($data['title']);
            }
        }

        // Recalculate reading time if content changed
        if (isset($data['content'])) {
            $data['reading_time'] = $this->calculateReadingTime($data['content']);
        }

        // Generate excerpt if not provided
        if (isset($data['content']) && empty($data['excerpt'])) {
            $config = config('Blog');
            $data['excerpt'] = $this->generateExcerpt(
                $data['content'], 
                $config->excerptLength ?? 55
            );
        }

        // Update timestamp
        $data['updated_at'] = Time::now()->toDateTimeString();

        // Set published_at if publishing for first time
        if (isset($data['is_published']) && $data['is_published'] == 1) {
            $post = $this->find($id);
            if ($post && empty($post['published_at'])) {
                $data['published_at'] = Time::now()->toDateTimeString();
            }
        }

        // Update tags if provided
        if (isset($data['tags'])) {
            $this->attachTags($id, $data['tags']);
            unset($data['tags']);
        }

        return $this->update($id, $data);
    }
}
