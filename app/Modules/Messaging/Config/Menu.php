<?php

// This file might be consumed by a Menu manager if one exists.
// Currently providing it for structural completeness.
return [
    'messaging' => [
        'title' => 'Messages',
        'icon'  => 'bi bi-chat-dots',
        'url'   => 'messages',
        'permission' => 'messaging.view' // Optional, implied
    ]
];
