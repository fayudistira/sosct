<?php

if (!function_exists('render_menu')) {
    /**
     * Render menu items as HTML with category support
     * 
     * @param array $menuItems Array of menu items
     * @param string $currentUrl Current URL for active state
     * @return string HTML output
     */
    function render_menu(array $menuItems, string $currentUrl = ''): string
    {
        if (empty($menuItems)) {
            return '';
        }
        
        // Load categories
        $categories = load_menu_categories();
        
        // Group items by category
        $categorizedItems = group_items_by_category($menuItems);
        
        $html = '<ul class="nav flex-column">';
        
        // Render items in category order
        foreach ($categories as $categoryKey => $category) {
            // Check if category has items
            if (!isset($categorizedItems[$categoryKey]) || empty($categorizedItems[$categoryKey])) {
                continue;
            }
            
            $items = $categorizedItems[$categoryKey];
            
            // Sort items within category by order
            usort($items, function ($a, $b) {
                return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
            });
            
            // Check if standalone category (like Dashboard)
            if (!empty($category['standalone'])) {
                // Render as single link
                $item = $items[0];
                $active = is_active_menu($item['url']) ? 'active' : '';
                $icon = $item['icon'] ?? 'circle';
                
                $html .= '<li class="nav-item">';
                $html .= '<a class="nav-link ' . $active . '" href="' . base_url($item['url']) . '">';
                $html .= '<i class="bi bi-' . esc($icon) . '"></i>';
                $html .= '<span>' . esc($item['title']) . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            } else {
                // Render as collapsible category
                $html .= render_category_section($categoryKey, $category, $items);
            }
        }
        
        // Render items without category (backward compatibility)
        if (isset($categorizedItems['uncategorized']) && !empty($categorizedItems['uncategorized'])) {
            $uncategorizedItems = $categorizedItems['uncategorized'];
            usort($uncategorizedItems, function ($a, $b) {
                return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
            });
            
            foreach ($uncategorizedItems as $item) {
                $active = is_active_menu($item['url']) ? 'active' : '';
                $icon = $item['icon'] ?? 'circle';
                
                $html .= '<li class="nav-item">';
                $html .= '<a class="nav-link ' . $active . '" href="' . base_url($item['url']) . '">';
                $html .= '<i class="bi bi-' . esc($icon) . '"></i>';
                $html .= '<span>' . esc($item['title']) . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            }
        }
        
        $html .= '</ul>';
        
        return $html;
    }
}

if (!function_exists('load_menu_categories')) {
    /**
     * Load menu categories from configuration
     * 
     * @return array
     */
    function load_menu_categories(): array
    {
        $categoriesFile = APPPATH . 'Config/MenuCategories.php';
        
        if (file_exists($categoriesFile)) {
            $categories = include $categoriesFile;
            
            // Sort by order
            uasort($categories, function ($a, $b) {
                return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
            });
            
            return $categories;
        }
        
        // Default categories if file doesn't exist
        return [
            'dashboard' => [
                'title' => 'Dashboard',
                'icon' => 'speedometer2',
                'order' => 1,
                'standalone' => true
            ],
            'uncategorized' => [
                'title' => 'Other',
                'icon' => 'folder',
                'order' => 999
            ]
        ];
    }
}

if (!function_exists('group_items_by_category')) {
    /**
     * Group menu items by their category
     * 
     * @param array $menuItems
     * @return array
     */
    function group_items_by_category(array $menuItems): array
    {
        $grouped = [];
        
        foreach ($menuItems as $item) {
            $category = $item['category'] ?? 'uncategorized';
            
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            
            $grouped[$category][] = $item;
        }
        
        return $grouped;
    }
}

if (!function_exists('render_category_section')) {
    /**
     * Render a collapsible category section
     * 
     * @param string $categoryKey
     * @param array $category
     * @param array $items
     * @return string
     */
    function render_category_section(string $categoryKey, array $category, array $items): string
    {
        $html = '<li class="nav-item menu-category" data-category="' . esc($categoryKey) . '">';
        
        // Category header (clickable to expand/collapse) - no Bootstrap data attributes
        $html .= '<a class="nav-link menu-category-header collapsed" href="#" aria-expanded="false">';
        $html .= '<i class="bi bi-' . esc($category['icon'] ?? 'folder') . '"></i>';
        $html .= '<span>' . esc($category['title']) . '</span>';
        $html .= '<i class="bi bi-chevron-down ms-auto category-toggle-icon"></i>';
        $html .= '</a>';
        
        // Category items (collapsible) - initially hidden
        $html .= '<div class="menu-category-items" id="category-' . esc($categoryKey) . '">';
        $html .= '<ul class="nav flex-column submenu">';
        
        foreach ($items as $item) {
            $active = is_active_menu($item['url']) ? 'active' : '';
            $icon = $item['icon'] ?? 'circle';
            
            // Check if item has submenu
            if (isset($item['submenu']) && !empty($item['submenu'])) {
                $html .= render_submenu_item($item, $active);
            } else {
                $html .= '<li class="nav-item">';
                $html .= '<a class="nav-link ' . $active . '" href="' . base_url($item['url']) . '">';
                $html .= '<i class="bi bi-' . esc($icon) . '"></i>';
                $html .= '<span>' . esc($item['title']) . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            }
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</li>';
        
        return $html;
    }
}

if (!function_exists('render_submenu_item')) {
    /**
     * Render a menu item with submenu
     * 
     * @param array $item
     * @param string $activeClass
     * @return string
     */
    function render_submenu_item(array $item, string $activeClass): string
    {
        $icon = $item['icon'] ?? 'circle';
        $submenuId = 'submenu-' . preg_replace('/[^a-zA-Z0-9]/', '-', $item['url']);
        
        // Check if any submenu item is active
        $hasActiveSubmenu = false;
        foreach ($item['submenu'] as $subItem) {
            if (is_active_menu($subItem['url'])) {
                $hasActiveSubmenu = true;
                break;
            }
        }
        
        $expandClass = $hasActiveSubmenu ? 'show' : '';
        $collapsedClass = $hasActiveSubmenu ? '' : 'collapsed';
        $parentActive = $hasActiveSubmenu ? 'active' : '';
        
        $html = '<li class="nav-item">';
        $html .= '<a class="nav-link ' . $parentActive . ' has-submenu ' . $collapsedClass . '" href="#" data-target="#' . $submenuId . '" aria-expanded="' . ($hasActiveSubmenu ? 'true' : 'false') . '">';
        $html .= '<i class="bi bi-' . esc($icon) . '"></i>';
        $html .= '<span>' . esc($item['title']) . '</span>';
        $html .= '<i class="bi bi-chevron-down ms-auto submenu-toggle-icon"></i>';
        $html .= '</a>';
        
        $html .= '<div class="submenu-items ' . $expandClass . '" id="' . $submenuId . '">';
        $html .= '<ul class="nav flex-column submenu">';
        
        foreach ($item['submenu'] as $subItem) {
            $subActive = is_active_menu($subItem['url']) ? 'active' : '';
            $subIcon = $subItem['icon'] ?? 'circle';
            
            $html .= '<li class="nav-item">';
            $html .= '<a class="nav-link ' . $subActive . '" href="' . base_url($subItem['url']) . '">';
            $html .= '<i class="bi bi-' . esc($subIcon) . '"></i>';
            $html .= '<span>' . esc($subItem['title']) . '</span>';
            $html .= '</a>';
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</li>';
        
        return $html;
    }
}

if (!function_exists('is_active_menu')) {
    /**
     * Check if menu item is active based on current URL
     * 
     * @param string $menuUrl Menu item URL
     * @return bool
     */
    function is_active_menu(string $menuUrl): bool
    {
        $currentUrl = uri_string();
        
        // Exact match
        if ($currentUrl === trim($menuUrl, '/')) {
            return true;
        }
        
        // Check if current URL starts with menu URL (for sub-pages)
        if (!empty($menuUrl) && str_starts_with($currentUrl, trim($menuUrl, '/'))) {
            return true;
        }
        
        return false;
    }
}
