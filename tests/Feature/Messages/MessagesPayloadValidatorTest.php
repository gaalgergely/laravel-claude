<?php

use Illuminate\Support\Facades\Http;
use GergelyGaal\LaravelClaude\Clients\HttpClaudeClient;
use GergelyGaal\LaravelClaude\Payloads\Messages\{MessagesData, Message, MessageFragment, MessagesPayloadValidator, MessagesSchema};
use GergelyGaal\LaravelClaude\Enums\Role;
use GergelyGaal\LaravelClaude\Exceptions\PayloadValidationException;
use GergelyGaal\LaravelClaude\Fixtures\Messages\MessagesPayloadFixture;
use GergelyGaal\LaravelClaude\Fixtures\Messages\MessagesResponseFixture;

it('executes HTTP for valid payload', function () {

    Http::fake(['https://api.anthropic.com/*' => Http::response(MessagesResponseFixture::success(), 200)]);

    $client = new HttpClaudeClient();

    expect($client->sendMessages(MessagesPayloadFixture::base()))->toMatchArray(MessagesResponseFixture::success());

});

it('throws exception with exact paths when fields missing', function () {
    $client = new HttpClaudeClient(new MessagesPayloadValidator(), 'key');

    try {
        $client->sendMessages(MessagesPayloadFixture::base([
            'model' => '',
            'messages' => [
                [
                    'role' => Role::USER->name,
                    'content' => [
                        ['type' => 'text', 'text' => ''],
                    ],
                ],
            ],
        ]));

        test()->fail('PayloadValidationException was not thrown.');
    } catch (PayloadValidationException $e) {
        $errors = $e->errors();
        expect($errors)->toHaveKey('model', ['The model field is required.']);
        expect($errors)->toHaveKey('messages.0.content.0.text', ['The messages.0.content.0.text field is required.']);
    }
});

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
