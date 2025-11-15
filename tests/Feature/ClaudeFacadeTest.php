<?php

use Illuminate\Support\Facades\Http;
use GergelyGaal\LaravelClaude\Facades\Claude;
use GergelyGaal\LaravelClaude\Services\Claude as ClaudeService;
use GergelyGaal\LaravelClaude\Fixtures\Messages\MessagesPayloadFixture;
use GergelyGaal\LaravelClaude\Fixtures\Messages\MessagesResponseFixture;

it('resolves the Claude facade root from the container', function () {
    expect(app(ClaudeService::class))->toBeInstanceOf(ClaudeService::class);
    expect(Claude::getFacadeRoot())->toBeInstanceOf(ClaudeService::class);
});

it('can call sendMessages via the facade', function () {
    Http::fake([
        '*' => Http::response(MessagesResponseFixture::success(), 200),
    ]);

    $response = Claude::sendMessages(MessagesPayloadFixture::base());

    expect(data_get($response, 'content.0.text'))->toContain('Hello! How can I help you today?');
});
