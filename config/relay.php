<?php

declare(strict_types=1);

use Prism\Relay\Enums\Transport;

return [
    /*
    |--------------------------------------------------------------------------
    | MCP Server Configurations
    |--------------------------------------------------------------------------
    |
    | Define your MCP server configurations here. Each server should have a
    | name as the key, and a configuration array with the appropriate settings.
    |
    */
    'servers' => [
        'puppeteer' => [
            'transport' => Transport::Stdio,
            'command' => ['npx', '-y', '@modelcontextprotocol/server-puppeteer'],
            'timeout' => env('RELAY_PUPPETEER_SERVER_TIMEOUT', 60),
            'env' => [],
        ],
        'db' => [
            'transport' => Transport::Stdio,
            'command' => ['npx', '-y', '@bytebase/dbhub' ,'--transport','--dsn', 'postgres://u:pl,OKMijn@192.168.100.100:5432/udb'],
            'timeout' => env('RELAY_PUPPETEER_SERVER_TIMEOUT', 60),
            'env' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tool Definition Cache Duration
    |--------------------------------------------------------------------------
    |
    | This value determines how long (in minutes) the tool definitions fetched
    | from MCP servers will be cached. Set to 0 to disable caching entirely.
    |
    */
    'cache_duration' => env('RELAY_TOOLS_CACHE_DURATION', 0),
];
