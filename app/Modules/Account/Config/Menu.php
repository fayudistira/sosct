<?php

/**
 * Menu configuration for Account module
 * This file registers menu items that will appear in the dashboard sidebar
 */

return [
    [
        'title' => 'Profile Saya',
        'url' => 'account',
        'icon' => 'person-circle',
        'permission' => null, // Available to all authenticated users
        'order' => 999
        // No category - will appear at bottom as uncategorized
    ]
];
