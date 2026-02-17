<?php

/**
 * Menu configuration for Payment module
 * This file registers menu items that will appear in the dashboard sidebar
 */

return [
    [
        'title' => 'Contracts',
        'url' => 'contract',
        'icon' => 'file-contract',
        'permission' => ['payment.manage', 'invoice.manage', 'admission.manage'],
        'order' => 1,
        'category' => 'finance',
        'submenu' => [
            [
                'title' => 'All Contracts',
                'url' => 'contract',
                'icon' => 'list-ul'
            ]
        ]
    ],
    [
        'title' => 'Pembayaran',
        'url' => 'payment',
        'icon' => 'cash-coin',
        'permission' => 'payment.manage',
        'order' => 2,
        'category' => 'finance',
        'submenu' => [
            [
                'title' => 'All Payments',
                'url' => 'payment',
                'icon' => 'list-ul'
            ],
            [
                'title' => 'Add Payment',
                'url' => 'payment/create',
                'icon' => 'plus-circle'
            ]
        ]
    ],
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
                'title' => 'Overdue Invoices',
                'url' => 'payment/reports/overdue',
                'icon' => 'exclamation-triangle'
            ]
        ]
    ],
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
