<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'oss'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'permissions' => [
                'file' => [
                    'public' => 0664,
                    'private' => 0600,
                ],
                'dir' => [
                    'public' => 0775,
                    'private' => 0700,
                ],
            ],
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/uploads',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

        'oss' => [
            'driver' => 'oss',
            'access_key' => env('OSS_ACCESS_KEY'),
            'secret_key' => env('OSS_SECRET_KEY'),
            'bucket' => env('OSS_BUCKET'), 
            //'public_path' => env('OSS_ENDPOINT'),
            // OSS 外网节点或自定义外部域名       
            'endpoint' => env('OSS_ENDPOINT'), 
            // 如果isCName为true, getUrl会判断cdnDomain是否设定来决定返回的url，
            'cdnDomain' => '', 
            // true to use 'https://' and false to use 'http://'. default isfalse,
            'ssl' => true, 
            // 是否使用自定义域名,true: 则Storage.url()会使用自定义的cdn或域名 生成文件url， false: 则使用外部节点生成url
            'isCName' => env('OSS_IS_CNAME'),
            'debug' => true,
           ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
        public_path('uploads') => storage_path('app/public'),
    ],

];
