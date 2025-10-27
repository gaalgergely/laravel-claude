<?php

namespace Gaalgergely\LaravelClaude\Clients;

use Illuminate\Support\Facades\Http;
use Gaalgergely\LaravelClaude\Contracts\ClaudeClientContract;

class HttpClaudeClient implements ClaudeClientContract
{
    public function generate(string $prompt): string
    {
        $config = config('claude');

        $response = Http::baseUrl($config['base_url'])
            ->withHeaders([
                'x-api-key' => $config['api_key'],
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->timeout($config['timeout'])
            ->retry($config['retries'])
            ->post('', [
                'model' => $config['model'],
                'max_tokens' => 800,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ])
            ->throw();

        return (string) data_get($response->json(), 'content.0.text', '');
    }
}
