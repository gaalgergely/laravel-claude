<?php

use Illuminate\Support\Facades\Http;
use GergelyGaal\LaravelClaude\Clients\HttpClaudeClient;
use GergelyGaal\LaravelClaude\Payloads\{MessagesData, Message, MessageFragment, MessagesPayloadValidator};
use GergelyGaal\LaravelClaude\Enums\Role;
use GergelyGaal\LaravelClaude\Exceptions\MessagesPayloadValidationException;

it('executes HTTP for valid payload', function () {
    Http::fake(['https://api.anthropic.com/*' => Http::response(['id' => 'ok'], 200)]);

    $payload = new MessagesData(
        model: 'claude-3-opus-20240229',
        messages: [
            new Message(Role::USER, [
                new MessageFragment('text', 'hello'),
            ]),
        ],
        maxTokens: 1024,
    );

    $client = new HttpClaudeClient(new PayloadValidationException(), 'test-key');

    expect($client->sendMessage($payload))->toMatchArray(['id' => 'ok']);
});

it('throws exception with exact paths when fields missing', function () {
    $client = new HttpClaudeClient(new MessagesPayloadValidator(), 'key');

    $client->sendMessage([
        'model' => '',
        'messages' => [['role' => 'system', 'content' => []]],
    ]);
})->throws(MessagesPayloadValidationException::class, fn ($e) => tap($e->errors(), function ($errors) {
    expect($errors)->toHaveKey('model.0', 'The model field is required.');
    expect($errors)->toHaveKey('messages.0.content.0.text.0');
}));

it('rejects enums and nested arrays correctly', function () {
    (new MessagesPayloadValidator())->validate([
        'model' => 'claude-3-opus-20240229',
        'messages' => [
            [
                'role' => 'invalid-role',
                'content' => [
                    ['type' => 'text', 'text' => 'hi'],
                ],
            ],
        ],
    ]);
})->throws(PayloadValidationException::class);

it('enforces optional vs required fields and type coercion', function () {
    (new MessagesPayloadValidator())->validate([
        'model' => 'claude-3-opus-20240229',
        'messages' => [
            [
                'role' => 'user',
                'content' => [['type' => 'text', 'text' => 'hi']],
            ],
        ],
        'max_tokens' => 'invalid',
    ]);
})->throws(PayloadValidationException::class);

it('fails when schema evolves with new required field', function () {
    $validator = new MessagesPayloadValidator(
        array_merge(MessagesSchema::rules(), ['metadata.trace_id' => ['required', 'uuid']])
    );

    $validator->validate([
        'model' => 'claude-3-opus-20240229',
        'messages' => [
            [
                'role' => 'user',
                'content' => [['type' => 'text', 'text' => 'hi']],
            ],
        ],
    ]);
})->throws(PayloadValidationException::class);
