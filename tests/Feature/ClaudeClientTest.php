<?php

use Illuminate\Support\Facades\Http;
use GergelyGaal\LaravelClaude\Clients\HttpClaudeClient;

it('returns generated text from Claude', function () {
    Http::fake([
        '*' => Http::response(['content' => [["text" => 'Hello from Claude']]], 200),
    ]);

    $client = new HttpClaudeClient();
    expect($client->sendMessages('test'))->toContain('Claude');
})->skip();

it('handles retries and timeouts', function () {
    Http::fakeSequence()
        ->pushStatus(500)
        ->push(['content' => [["text" => 'Recovered']]], 200);

    $client = new HttpClaudeClient();
    expect($client->sendMessages('retry'))->toBe('Recovered');
})->skip();
