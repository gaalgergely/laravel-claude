<?php

use GergelyGaal\LaravelClaude\Clients\HttpClaudeClient;
use GergelyGaal\LaravelClaude\Enums\Role;
use GergelyGaal\LaravelClaude\Exceptions\PayloadValidationException;
use GergelyGaal\LaravelClaude\Fixtures\Messages\MessagesPayloadFixture;
use GergelyGaal\LaravelClaude\Schemas\MessagesSchema;
use GergelyGaal\LaravelClaude\Validators\PayloadValidator;

it('throws exception with exact paths when fields missing', function () {
    $client = new HttpClaudeClient(new PayloadValidator(MessagesSchema::class), 'key');

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
        expect($errors)->toHaveKey('messages.0.content.0.text', ['The messages.0.content.0.text field is required when messages.0.content.0.type is text.']);
    }
});

it('rejects enums and nested arrays correctly', function () {
    (new PayloadValidator(MessagesSchema::class))->validate([
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
    (new PayloadValidator(MessagesSchema::class))->validate([
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
