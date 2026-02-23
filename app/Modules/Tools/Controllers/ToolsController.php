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
            [
                'name' => 'Image Converter',
                'description' => 'Convert images to WebP format with batch processing',
                'url' => 'tools/imager',
                'icon' => 'image',
                'permission' => 'tools.access'
            ],
            [
                'name' => 'Hanzi Flashcard',
                'description' => 'Learn Chinese characters with interactive flashcards and HSK levels',
                'url' => 'tools/hanzi',
                'icon' => 'translate',
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
    
    /**
     * Image Converter page
     * 
     * @return string
     */
    public function imager(): string
    {
        $user = auth()->user();
        
        // Get menu items from all modules
        $menuItems = $this->loadModuleMenus();
        
        return view('Modules\Tools\Views\imager', [
            'title' => 'Image Converter',
            'user' => $user,
            'menuItems' => $menuItems
        ]);
    }
}