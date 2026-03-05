<?php

/**
 * Frontend Blog Controller
 * 
 * Handles public-facing blog pages including:
 * - Blog listing
 * - Single post view
 * - Category archive
 * - Tag archive
 * - Search
 * - RSS feed
 * - Sitemap
 */

namespace Modules\Blog\Controllers\Frontend;

use Modules\Blog\Models\BlogPostModel;
use Modules\Blog\Models\BlogCategoryModel;
use Modules\Blog\Models\BlogTagModel;
use CodeIgniter\Controller;

class BlogController extends Controller
{
    protected $postModel;
    protected $categoryModel;
    protected $tagModel;

    public function __construct()
    {
        $this->postModel = new BlogPostModel();
        $this->categoryModel = new BlogCategoryModel();
        $this->tagModel = new BlogTagModel();
    }

    /**
     * Blog home - list all published posts
     */
    public function index()
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = config('Blog')->postsPerPage ?? 12;
        
        $data['posts'] = $this->postModel->getPublishedPosts($page, $perPage);
        $data['pager'] = $this->postModel->pager;
        $data['featuredPosts'] = $this->postModel->getFeaturedPosts(5);
        $data['categories'] = $this->categoryModel->getCategoriesWithPostCount();
        $data['popularTags'] = $this->tagModel->getPopularTags(10);
        $data['recentPosts'] = $this->postModel->getRecentPosts(5);
        
        // SEO Meta
        $data['metaTitle'] = 'Blog - Educational Articles & Tutorials';
        $data['metaDescription'] = 'Read the latest educational articles, tutorials, and learning resources.';
        
        return view('Modules\Blog\Views\frontend\index', $data);
    }

    /**
     * Single blog post view
     */
    public function post(string $slug)
    {
        // First check if post exists with simple query
        $post = $this->postModel->where('slug', $slug)->first();
        
        if (!$post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Blog post not found: ' . $slug);
        }
        
        // Get full post data with joins
        $post = $this->postModel->getPostBySlug($slug, false);

        // Increment view count
        $this->postModel->incrementViewCount($post['id']);
        
        // Get tags
        $post['tags'] = $this->postModel->getPostTags($post['id']);
        
        // Get related posts
        $data['relatedPosts'] = $this->postModel->getRelatedPosts(
            $post['id'], 
            $post['category_id'] ?? null, 
            3
        );
        
        // Get recent posts
        $data['recentPosts'] = $this->postModel->getRecentPosts(5);
        
        // Get categories
        $data['categories'] = $this->categoryModel->getCategoriesWithPostCount();
        
        $data['post'] = $post;
        
        // SEO Meta
        $data['metaTitle'] = $post['meta_title'] ?? $post['title'];
        $data['metaDescription'] = $post['meta_description'] ?? $post['excerpt'];
        $data['metaKeywords'] = $post['meta_keywords'] ?? '';
        
        // Open Graph
        $data['ogTitle'] = $post['meta_title'] ?? $post['title'];
        $data['ogDescription'] = $post['meta_description'] ?? $post['excerpt'];
        $data['ogImage'] = $post['featured_image'] ? base_url($post['featured_image']) : '';
        $data['ogUrl'] = current_url();
        $data['ogType'] = 'article';
        
        // Twitter Card
        $data['twitterCard'] = $post['featured_image'] ? 'summary_large_image' : 'summary';
        
        return view('Modules\Blog\Views\frontend\post', $data);
    }

    /**
     * Category archive
     */
    public function category(string $slug)
    {
        $category = $this->categoryModel->getCategoryBySlug($slug);
        
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Category not found');
        }
        
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = config('Blog')->postsPerPage ?? 12;
        
        $data['posts'] = $this->postModel->getPostsByCategory($slug, $page, $perPage);
        $data['pager'] = $this->postModel->pager;
        $data['category'] = $category;
        $data['categories'] = $this->categoryModel->getCategoriesWithPostCount();
        $data['popularTags'] = $this->tagModel->getPopularTags(10);
        $data['recentPosts'] = $this->postModel->getRecentPosts(5);
        
        // Breadcrumb
        $data['breadcrumb'] = $this->categoryModel->getBreadcrumb($category['id']);
        
        // SEO Meta
        $data['metaTitle'] = $category['name'] . ' - Blog';
        $data['metaDescription'] = $category['description'] ?? 'Browse articles in ' . $category['name'];
        
        return view('Modules\Blog\Views\frontend\category', $data);
    }

    /**
     * Tag archive
     */
    public function tag(string $slug)
    {
        $tag = $this->tagModel->getTagBySlug($slug);
        
        if (!$tag) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tag not found');
        }
        
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = config('Blog')->postsPerPage ?? 12;
        
        $data['posts'] = $this->postModel->getPostsByTag($slug, $page, $perPage);
        $data['pager'] = $this->postModel->pager;
        $data['tag'] = $tag;
        $data['categories'] = $this->categoryModel->getCategoriesWithPostCount();
        $data['popularTags'] = $this->tagModel->getPopularTags(10);
        $data['recentPosts'] = $this->postModel->getRecentPosts(5);
        
        // SEO Meta
        $data['metaTitle'] = 'Tag: ' . $tag['name'] . ' - Blog';
        $data['metaDescription'] = 'Browse articles tagged with ' . $tag['name'];
        
        return view('Modules\Blog\Views\frontend\tag', $data);
    }

    /**
     * Search results
     */
    public function search()
    {
        $keyword = $this->request->getGet('q') ?? '';
        
        if (empty($keyword)) {
            return redirect()->to('blog');
        }
        
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = config('Blog')->postsPerPage ?? 12;
        
        $data['posts'] = $this->postModel->searchPosts($keyword, $page, $perPage);
        $data['pager'] = $this->postModel->pager;
        $data['keyword'] = $keyword;
        $data['categories'] = $this->categoryModel->getCategoriesWithPostCount();
        $data['popularTags'] = $this->tagModel->getPopularTags(10);
        $data['recentPosts'] = $this->postModel->getRecentPosts(5);
        
        // SEO Meta
        $data['metaTitle'] = 'Search: ' . esc($keyword) . ' - Blog';
        $data['metaDescription'] = 'Search results for ' . esc($keyword);
        
        return view('Modules\Blog\Views\frontend\search', $data);
    }

    /**
     * RSS Feed
     */
    public function feed()
    {
        $posts = $this->postModel->getPublishedPosts(1, 20);
        
        $data['posts'] = $posts;
        $data['blogName'] = 'Education Blog';
        $data['blogDescription'] = 'Educational articles and tutorials';
        $data['feedUrl'] = base_url('blog/feed');
        
        return view('Modules\Blog\Views\frontend\feed', $data);
    }

    /**
     * XML Sitemap
     */
    public function sitemap()
    {
        // Get all published posts
        $posts = $this->postModel->published()
            ->orderBy('updated_at', 'DESC')
            ->find();
        
        // Get all categories
        $categories = $this->categoryModel->active()->find();
        
        $data['posts'] = $posts;
        $data['categories'] = $categories;
        $data['baseUrl'] = base_url();
        
        return view('Modules\Blog\Views\frontend\sitemap', $data);
    }
}
