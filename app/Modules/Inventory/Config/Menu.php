<?php

/**
 * Menu configuration for Inventory module
 * This file registers menu items that will appear in the dashboard sidebar
 */

return [
    // Inventory Main Menu
    [
        'title' => 'Inventory',
        'url' => 'inventory/items',
        'icon' => 'box-seam',
        'permission' => ['inventory.manage', 'inventory.view'],
        'order' => 8,
        'category' => 'operations',
        'submenu' => [
            [
                'title' => 'Items',
                'url' => 'inventory/items',
                'icon' => 'list-ul'
            ],
            [
                'title' => 'Categories',
                'url' => 'inventory/categories',
                'icon' => 'folder'
            ],
            [
                'title' => 'Locations',
                'url' => 'inventory/locations',
                'icon' => 'geo-alt'
            ],
            [
                'title' => 'Movements',
                'url' => 'inventory/movements',
                'icon' => 'arrow-left-right'
            ],
            [
                'title' => 'Stock Opname',
                'url' => 'inventory/stock-opname',
                'icon' => 'clipboard-data'
            ],
            [
                'title' => 'Alerts',
                'url' => 'inventory/alerts',
                'icon' => 'exclamation-triangle'
            ],
            [
                'title' => 'Reports',
                'url' => 'inventory/reports/summary',
                'icon' => 'graph-up',
                'submenu' => [
                    [
                        'title' => 'Summary',
                        'url' => 'inventory/reports/summary',
                        'icon' => 'bar-chart'
                    ],
                    [
                        'title' => 'Valuation',
                        'url' => 'inventory/reports/valuation',
                        'icon' => 'cash-stack'
                    ],
                    [
                        'title' => 'Movements',
                        'url' => 'inventory/reports/movement',
                        'icon' => 'arrow-repeat'
                    ]
                ]
            ]
        ]
    ]
];
