<?php

return [
    'temporary_file_upload' => [
        'disk' => 'local', // Cambia a 'local' en Railway
        'rules' => ['required', 'file', 'max:12288'], // 12MB max
        'directory' => 'livewire-tmp',
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5,
    ],
    
    'asset_url' => env('ASSET_URL'),
];