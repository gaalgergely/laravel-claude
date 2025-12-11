<?php

use Illuminate\Support\Facades\Http;
use GaalGergely\LaravelClaude\Clients\HttpClaudeClient;
use GaalGergely\LaravelClaude\Fixtures\Messages\MessagesPayloadFixture;
use GaalGergely\LaravelClaude\Fixtures\Messages\MessagesResponseFixture;

it('executes HTTP for valid payload', function () {

    Http::fake(['https://api.anthropic.com/*' => Http::response(MessagesResponseFixture::success(), 200)]);

    $client = new HttpClaudeClient();

    expect($client->sendMessages(MessagesPayloadFixture::base()))->toMatchArray(MessagesResponseFixture::success());

});

it('returns generated text from Claude', function () {

    Http::fake([
        '*' => Http::response(MessagesResponseFixture::success(), 200),
    ]);

    $client = new HttpClaudeClient();
    $response = $client->sendMessages(MessagesPayloadFixture::base());

    expect(data_get($response, 'content.0.text'))->toContain('Hello! How can I help you today?');
});

/**
 * @todo fix this ...
 */
it('handles retries and timeouts', function () {

    Http::fakeSequence()
        ->pushStatus(500)
        ->push(['content' => [["text" => 'Recovered']]], 200);

    $client = new HttpClaudeClient();

    expect(data_get($client->sendMessages(MessagesPayloadFixture::base()), 'content.0.text'))->toBe('Recovered');
})->skip();

