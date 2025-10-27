<?php

use Illuminate\Support\Facades\Http;
use GergelyGaal\LaravelClaude\Clients\HttpClaudeClient;

it('returns generated text from Claude', function () {
    Http::fake([
        '*' => Http::response(['content' => [["text" => 'Hello from Claude']]], 200),
    ]);

    $client = new HttpClaudeClient();
    expect($client->generate('test'))->toContain('Claude');
});

it('handles retries and timeouts', function () {
    Http::fakeSequence()
        ->pushStatus(500)
        ->push(['content' => [["text" => 'Recovered']]], 200);

    $client = new HttpClaudeClient();
    expect($client->generate('retry'))->toBe('Recovered');
});
