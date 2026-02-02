<?php

/**
 * Menu configuration for Account module
 * This file registers menu items that will appear in the dashboard sidebar
 */

return [
    [
        'title' => 'My Profile',
        'url' => 'account',
        'icon' => 'person-circle',
        'permission' => null, // Available to all authenticated users
        'order' => 100
    ]
];
