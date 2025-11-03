<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | You may specify which of the filesystem disks below you wish
    | to use as your default disk for general storage. You can set this
    | in your .env file with FILESYSTEM_DISK=public or FILESYSTEM_DISK=assets etc.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure multiple disks of different drivers.
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        // 通常のローカルストレージ（非公開）
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
            'report' => false,
        ],

        // public/storage ディレクトリ（Laravel標準）
        'public' => [
            'driver' => 'local',
            'root' => public_path('storage'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        // 🔹 追加：assets ディスク（ルート直下の assets フォルダ）
        'assets' => [
            'driver' => 'local',
            'root' => base_path('assets'), // ← Laravelルート直下の /assets/
            'url' => env('APP_URL').'/assets', // WebアクセスURL
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        // S3 などクラウドストレージ用（オプション）
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | The array keys are the locations of the links and the values
    | are their targets. You can safely leave this empty since we
    | don’t use symbolic links for assets or public storage anymore.
    |
    */

    'links' => [
        // シンボリックリンク不要
    ],

];
