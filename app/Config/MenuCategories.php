<?php

/**
 * Menu Categories Configuration
 * 
 * Defines the categories for organizing sidebar menu items.
 * Each category can have:
 * - title: Display name
 * - icon: Bootstrap icon name
 * - order: Sort order
 * - permission: Required permission(s) to view the category
 * - standalone: If true, renders as a direct link without collapsible
 */

return [
    'dashboard' => [
        'title' => 'Dashboard',
        'icon' => 'speedometer2',
        'order' => 1,
        'standalone' => true
    ],
    'student_portal' => [
        'title' => 'Student Portal',
        'icon' => 'person-badge',
        'order' => 2,
        'permission' => 'student.access'
    ],
    'academic' => [
        'title' => 'Akademik',
        'icon' => 'book',
        'order' => 10,
        'permission' => ['student.manage', 'admission.manage', 'classroom.manage', 'program.view', 'dormitory.view']
    ],
    'finance' => [
        'title' => 'Keuangan',
        'icon' => 'cash-stack',
        'order' => 20,
        'permission' => ['payment.manage', 'invoice.manage']
    ],
    'administration' => [
        'title' => 'Administrasi',
        'icon' => 'gear',
        'order' => 30,
        'permission' => 'admin.settings'
    ],
    'tools' => [
        'title' => 'Tools',
        'icon' => 'tools',
        'order' => 40,
        'permission' => 'tools.access'
    ]
];
