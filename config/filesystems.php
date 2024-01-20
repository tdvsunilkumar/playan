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

    'default' => env('FILESYSTEM_DRIVER', 'uploads'),

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
            'root' => storage_path(),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'uploads' => [
            'driver' => 'local',
            'root' => public_path('uploads'),
            'url' => env('APP_URL').'/storage',
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
        'remote' => [
            'driver' => 'sftp',
            'host' => '194.163.142.192',
            'port' => 22,  // SFTP port
            'username' => 'root',
            'password' => 'Jrfg4l6Du3Tadp88',
            'root' => '/var/www/html/palayan_backend/',
            'asset_url' => 'http://194.163.142.192/palayan_backend/public/uploads/',
            'visibility' => 'public',
        ],
        'digitalSignature' => [
            'driver' => 'sftp',
            'host' => '110.74.186.44',
            'port' => 8022,  // SFTP port
            'username' => 'dynedge',
            'password' => 'Dy43dG3123',
            'root' => 'DYNEDGESFTP/',
            'pCode'=>'1CRS_AX1TAADF',
            'userId'=>'DYNEDGEUSER1',
            'orgId'=>'DYNEDGEUSER1',
            'pin'=>'Abcd@1234',
            'fileServerId'=>'dynedge',
            'customSignatureText'=>'Digitally Signed by-on',
            'signPdfConfigB_url'=>'https://demo-kit.posdigicert.com.my/DssManagerApi/rest/roaming/signPdfConfigB',
            'verifyRoamingCert_url'=>'https://demo-kit.posdigicert.com.my/DssManagerApi/rest/roaming/verifyRoamingCert',
            'verifyRoamingPin_url'=>'https://demo-kit.posdigicert.com.my/DssManagerApi/rest/roaming/verifyRoamingPin',
            'visibility' => 'public',
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
        public_path('storage') => storage_path('app/public/assets'),
    ],

];
