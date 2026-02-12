<?php

/**
 * Menu configuration for Admission module
 * This file registers menu items that will appear in the dashboard sidebar
 */

return [
    [
        'title' => 'Data Pendaftaran',
        'url' => 'admission',
        'icon' => 'file-earmark-text',
        'permission' => 'admission.manage',
        'order' => 10
    ]
];
