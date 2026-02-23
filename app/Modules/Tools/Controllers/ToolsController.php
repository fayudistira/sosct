<?php

namespace Modules\Tools\Controllers;

use App\Controllers\BaseController;

class ToolsController extends BaseController
{
    /**
     * Tools index page - list of available tools
     * 
     * @return string
     */
    public function index(): string
    {
        $user = auth()->user();
        
        // Get menu items from all modules
        $menuItems = $this->loadModuleMenus();
        
        // Define available tools
        $tools = [
            [
                'name' => 'QR Code Generator',
                'description' => 'Create custom QR codes for URLs, text, or WhatsApp links',
                'url' => 'tools/qrgen',
                'icon' => 'qr-code',
                'permission' => 'tools.access'
            ],
        ];
        
        return view('Modules\Tools\Views\index', [
            'title' => 'Tools',
            'user' => $user,
            'menuItems' => $menuItems,
            'tools' => $tools
        ]);
    }
    
    /**
     * QR Code Generator page
     * 
     * @return string
     */
    public function qrgen(): string
    {
        $user = auth()->user();
        
        // Get menu items from all modules
        $menuItems = $this->loadModuleMenus();
        
        return view('Modules\Tools\Views\qrgen', [
            'title' => 'QR Code Generator',
            'user' => $user,
            'menuItems' => $menuItems
        ]);
    }
}