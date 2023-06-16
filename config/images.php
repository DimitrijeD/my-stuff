<?php

return [
    'user' => [
        'image' => [
            'directory' => 'images', 
            'maxWidth'  => 1024, 
            'default'   => public_path('basic-images/basic-avatar.jpg'),
            'disk'      => 'public',
        ],

        'thumbnail' => [
            'directory' => 'thumbnails', 
            'maxWidth'  => 250, 
            'default'   => public_path('basic-images/basic-avatar.jpg'),
            'disk'      => 'public',
        ],
    ]
];