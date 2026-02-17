<?php

/**
 * Menu configuration for Dormitory module
 * This file registers menu items that will appear in the dashboard sidebar
 */

return [
    [
        'title'      => 'Dormitory',
        'url'        => 'dormitory',
        'icon'       => 'building',
        'permission' => 'dormitory.view',
        'order'      => 5,
        'category'   => 'academic'
    ]
];
