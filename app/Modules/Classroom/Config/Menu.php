<?php

/**
 * Menu configuration for Classroom module
 * This file registers menu items that will appear in the dashboard sidebar
 */

return [
    [
        'title' => 'Classrooms',
        'url' => 'classroom',
        'icon' => 'door-open',
        'permission' => 'classroom.manage',
        'order' => 15
    ]
];