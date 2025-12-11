<?php

use GaalGergely\LaravelClaude\Clients\HttpClaudeClient;
use Illuminate\Support\Facades\Http;

it('sends the beta header for all file endpoints', function () {
    Http::fake([
        'https://api.anthropic.com/*' => Http::response(['ok' => true], 200),
    ]);

    $client = new HttpClaudeClient();

    $client->createFile([
        'content' => 'hello world',
        'name' => 'example.txt',
        'purpose' => 'fine-tune',
    ]);

    $client->listFiles();
    $client->getFileMetadata('file_123');
    $client->downloadFile('file_123');
    $client->deleteFile('file_123');

    Http::assertSentCount(5);

    foreach (Http::recorded() as [$request, $_response]) {
        expect($request->hasHeader('anthropic-beta', config('claude.files_beta_version')))->toBeTrue();
    }
});

it('returns the downloadFile response body as a string', function () {
    Http::fake([
        'https://api.anthropic.com/files/*/content' => Http::response('file-contents', 200),
    ]);

    $client = new HttpClaudeClient();

    $content = $client->downloadFile('file_abc');

    expect($content)->toBe('file-contents');
});
