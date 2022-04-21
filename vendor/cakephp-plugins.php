<?php
$baseDir = dirname(dirname(__FILE__));

return [
    'plugins' => [
        'Authentication' => $baseDir . '/vendor/cakephp/authentication/',
        'BryanCrowe/ApiPagination' => $baseDir . '/vendor/bcrowe/cakephp-api-pagination/',
        'EmailQueue' => $baseDir . '/vendor/lorenzo/cakephp-email-queue/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'Passbolt' => $baseDir . '/plugins/Passbolt/',
    ],
];
