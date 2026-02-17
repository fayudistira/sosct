<?php

/**
 * Menu configuration for Dashboard module
 * This file registers menu items that will appear in the dashboard sidebar
 */

return [
    [
        'title' => 'Dashboard',
        'url' => 'dashboard',
        'icon' => 'speedometer2',
        'permission' => 'dashboard.access',
        'order' => 1,
        'category' => 'dashboard'
    ],
    // Student Menu Items
    [
        'title' => 'My Class',
        'url' => 'my/class',
        'icon' => 'calendar3-week',
        'permission' => 'student.access',
        'order' => 1,
        'category' => 'student_portal'
    ],
    // Student Financial Menu Items
    [
        'title' => 'My Invoices',
        'url' => 'my/invoices',
        'icon' => 'receipt',
        'permission' => 'student.access',
        'order' => 2,
        'category' => 'student_portal'
    ],
    [
        'title' => 'My Payments',
        'url' => 'my/payments',
        'icon' => 'credit-card',
        'permission' => 'student.access',
        'order' => 3,
        'category' => 'student_portal'
    ]
];
