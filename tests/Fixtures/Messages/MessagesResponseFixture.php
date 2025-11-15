<?php

namespace GergelyGaal\LaravelClaude\Fixtures\Messages;

final class MessagesResponseFixture
{
    /**
     * Build a successful Claude API response.
     */
    public static function success(array $overrides = []): array
    {
        $response = [
            'id' => 'msg_01J123EXAMPLE',
            'type' => 'message',
            'role' => 'assistant',
            'content' => [
                [
                    'type' => 'text',
                    'text' => 'Hello! How can I help you today?',
                ],
            ],
            'model' => 'claude-3-sonnet-20240229',
            'stop_reason' => 'end_turn',
            'usage' => [
                'input_tokens' => 128,
                'output_tokens' => 256,
            ],
        ];

        return array_replace_recursive($response, $overrides);
    }
}
