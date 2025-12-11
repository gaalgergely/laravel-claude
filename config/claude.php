<?php

return [
    'base_url' => env('CLAUDE_BASE_URL', 'https://api.anthropic.com/v1'),
    'api_key' => env('CLAUDE_API_KEY', null),
    'anthropic_version' => env('CLAUDE_ANTHROPIC_VERSION', '2023-06-01'),
    'model' => env('CLAUDE_MODEL', 'claude-sonnet-4-5-20250929'),
    'max_tokens' => env('CLAUDE_MAX_TOKENS', 1024),
    'temperature' => env('CLAUDE_TEMPERATURE', 1.0),
    'timeout' => env('CLAUDE_TIMEOUT', 60),
    'retries' => env('CLAUDE_RETRIES', 1),
    'anthropic-beta' => env('CLAUDE_ANTROPHIC_BETA', 'files-api-2025-04-14'),
];

