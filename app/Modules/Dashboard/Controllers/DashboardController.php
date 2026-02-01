<?php

namespace Modules\Dashboard\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function __construct()
    {
        // Load menu helper
        helper('Modules\Dashboard\Helpers\menu_helper');
    }
    
    /**
     * Dashboard home page
     * 
     * @return string
     */
    public function index(): string
    {
        $user = auth()->user();
        
        // Get menu items from all modules
        $menuItems = $this->loadModuleMenus();
        
        // Get admission statistics if user has permission
        $admissionStats = null;
        $courseStats = null;
        
        if ($user->can('admission.view') || $user->can('admission.manage')) {
            $admissionModel = new \Modules\Admission\Models\AdmissionModel();
            $admissionStats = $admissionModel->getStatusCounts();
            $courseStats = $admissionModel->getCourseStatusBreakdown();
        }
        
        return view('Modules\Dashboard\Views\index', [
            'title' => 'Dashboard',
            'user' => $user,
            'menuItems' => $menuItems,
            'admissionStats' => $admissionStats,
            'courseStats' => $courseStats
        ]);
    }
    
    /**
     * Load and filter menu items from all modules
     * 
     * @return array
     */
    private function loadModuleMenus(): array
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
    private function hasMenuPermission(array $item): bool
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
