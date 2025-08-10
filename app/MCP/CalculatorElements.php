<?php

namespace App\Mcp;

use PhpMcp\Server\Attributes\McpResource;
use PhpMcp\Server\Attributes\McpTool;

class CalculatorElements
{
    /**
     * Adds two numbers together.
     *
     * @param  int  $a  The first number
     * @param  int  $b  The second number
     * @return int The sum of the two numbers
     */
    #[McpTool(name: 'add_numbers')]
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }

    /**
     * Calculates power with validation.
     */
    #[McpTool(name: 'calculate_power')]
    public function power(
        float $base,

        int $exponent
    ): float {
        return $base ** $exponent;
    }

    /**
     * Get application configuration.
     */
    #[McpResource(
        uri: 'config://app/settings',
        mimeType: 'application/json'
    )]
    public function getAppSettings(): array
    {
        return [
            'theme'    => config('app.theme', 'light'),
            'timezone' => config('app.timezone'),
            'features' => config('app.features', []),
        ];
    }
}
