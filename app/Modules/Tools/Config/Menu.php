<?php

/**
 * Menu configuration for Tools module
 * This file registers menu items that will appear in the dashboard sidebar
 */

return [
    [
        'title' => 'QR Generator',
        'url' => 'tools/qrgen',
        'icon' => 'qr-code',
        'permission' => 'tools.access',
        'order' => 1,
        'category' => 'tools'
    ],
];