<?php

/**
 * Menu configuration for Payment module
 * This file registers menu items that will appear in the dashboard sidebar
 */

return [
    [
        'title' => 'Payments',
        'url' => 'payment',
        'icon' => 'cash-coin',
        'order' => 20,
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
        'order' => 21,
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
        'order' => 22,
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
