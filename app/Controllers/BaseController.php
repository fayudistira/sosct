<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        helper(['auth', 'setting']);
        helper('Modules\Dashboard\Helpers\menu_helper');

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }
    
    /**
     * Load and filter menu items from all modules
     * 
     * @return array
     */
    protected function loadModuleMenus(): array
    {
        $menuItems = [];
        $modulesPath = APPPATH . 'Modules/';
        
        if (!is_dir($modulesPath)) {
            return $menuItems;
        }
        
        foreach (scandir($modulesPath) as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }
            
            $menuFile = $modulesPath . $module . '/Config/Menu.php';
            
            if (file_exists($menuFile)) {
                $menuConfig = include $menuFile;
                
                if (is_array($menuConfig)) {
                    foreach ($menuConfig as $item) {
                        // Check if user has required permission
                        if ($this->hasMenuPermission($item)) {
                            $menuItems[] = $item;
                        }
                    }
                }
            }
        }
        
        // Sort by order
        usort($menuItems, function($a, $b) {
            return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
        });
        
        return $menuItems;
    }
    
    /**
     * Check if user has permission for menu item
     * 
     * @param array $item Menu item configuration
     * @return bool
     */
    protected function hasMenuPermission(array $item): bool
    {
        $user = auth()->user();
        
        // If no permission required, show to all authenticated users
        if (empty($item['permission'])) {
            return true;
        }
        
        // Check single permission
        if (is_string($item['permission'])) {
            return $user->can($item['permission']);
        }
        
        // Check multiple permissions (any)
        if (is_array($item['permission'])) {
            foreach ($item['permission'] as $permission) {
                if ($user->can($permission)) {
                    return true;
                }
            }
        }
        
        return false;
    }
}
