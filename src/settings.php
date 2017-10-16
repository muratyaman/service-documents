<?php
return [
    'settings' => [
        // set to false in production
        'displayErrorDetails'    => true,

        // Allow the web server to send the content-length header
        'addContentLengthHeader' => false,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // keep documents in this directory
        'upload_directory' => __DIR__ . '/../storage/',
    ],
];
