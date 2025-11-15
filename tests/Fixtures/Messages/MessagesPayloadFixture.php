<?php

namespace GergelyGaal\LaravelClaude\Fixtures\Messages;

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
                    'role' => 'user',
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
