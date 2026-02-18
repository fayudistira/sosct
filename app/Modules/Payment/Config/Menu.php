<?php

/**
 * Menu configuration for Payment module
 * This file registers menu items that will appear in the dashboard sidebar
 */

return [
    // Contracts - single item, no submenu
    [
        'title' => 'Contracts',
        'url' => 'contract',
        'icon' => 'file-earmark-text',
        'permission' => ['payment.manage', 'invoice.manage', 'admission.manage'],
        'order' => 1,
        'category' => 'finance'
    ],
    // Payments - single item, no submenu
    [
        'title' => 'Pembayaran',
        'url' => 'payment',
        'icon' => 'cash-coin',
        'permission' => 'payment.manage',
        'order' => 2,
        'category' => 'finance'
    ],
    // Invoices - multiple submenus
    [
        'title' => 'Invoices',
        'url' => 'invoice',
        'icon' => 'receipt',
        'permission' => 'invoice.manage',
        'order' => 3,
        'category' => 'finance',
        'submenu' => [
            [
                'title' => 'All Invoices',
                'url' => 'invoice',
                'icon' => 'list-ul'
            ],
            [
                'title' => 'Create Invoice',
                'url' => 'invoice/create',
                'icon' => 'plus-circle'
            ],
            [
                'title' => 'Extend Invoice',
                'url' => 'invoice/extend',
                'icon' => 'arrow-repeat'
            ]
        ]
    ],
    // Reports - multiple submenus
    [
        'title' => 'Reports',
        'url' => 'payment/reports/revenue',
        'icon' => 'graph-up',
        'permission' => ['payment.manage', 'invoice.manage'],
        'order' => 4,
        'category' => 'finance',
        'submenu' => [
            [
                'title' => 'Revenue Report',
                'url' => 'payment/reports/revenue',
                'icon' => 'bar-chart'
            ],
            [
                'title' => 'Overdue Report',
                'url' => 'payment/reports/overdue',
                'icon' => 'clock-history'
            ]
        ]
    ]
];
