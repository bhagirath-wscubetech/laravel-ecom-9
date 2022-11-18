<?php

$storePath = 'public/images/'; // storing in storage folder
$readPath = 'storage/images/'; // read from public folder
return [
    'storePath' => $storePath,
    'readPath' => $readPath,
    'category' => [
        'banner_image' => 'category/banners',
        'icon_image' => 'category/icons',
    ],
    'product' => [
        'main_image' => 'product/main',
        'thumb_image' => 'product/thumb',
        'other_image' => 'product/other',
    ]
];
