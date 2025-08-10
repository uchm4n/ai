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
            'command'   => ['npx', '-y', '@modelcontextprotocol/server-puppeteer'],
            'timeout'   => 30,
            'env'       => [],
            'transport' => \Prism\Relay\Enums\Transport::Stdio,
        ],
        'mcp' => [
            'url'       => env('RELAY_GITHUB_SERVER_URL', 'http://127.0.0.1:8099/mcp'),
            'timeout'   => 30,
            'transport' => Transport::Http,
        ],
        'github' => [
            'url'       => env('RELAY_GITHUB_SERVER_URL', 'https://api.githubcopilot.com/mcp/'),
            'api_key'   => env('GITHUB_MCP_TOKEN', 'xxx'),
            'timeout'   => 30,
            'transport' => \Prism\Relay\Enums\Transport::Http,
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
    'cache_duration' => env('RELAY_TOOLS_CACHE_DURATION', 60),
];
