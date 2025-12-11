<?php

namespace GaalGergely\LaravelClaude\Fixtures\Messages;

use GaalGergely\LaravelClaude\Enums\Role;

final class MessagesPayloadFixture
{
    /**
     * Build the base payload the Claude client would send.
     */
    public static function base(array $overrides = []): array
    {
        $payload = [
            'model' => 'claude-3-sonnet-20240229',
            'messages' => [
                [
                    'role' => Role::USER->value,
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Hello!',
                        ],
                    ],
                ],
            ],
            //'temperature' => 0.2,
            'max_tokens' => 1024,
        ];

        return array_replace_recursive($payload, $overrides);
    }
}

