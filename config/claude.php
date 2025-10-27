<?php

return [
    'api_key' => env('CLAUDE_API_KEY'),
    'model' => env('CLAUDE_MODEL', 'claude-3-opus-20240229'),
    'base_url' => 'https://api.anthropic.com/v1/messages',
    'timeout' => 15,
    'retries' => 2,
];
