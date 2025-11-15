<?php

namespace GergelyGaal\LaravelClaude\Payloads\Messages;

use GergelyGaal\LaravelClaude\Enums\Role;

final class MessagesSchema
{
    public static function rules(): array
    {
        $roleEnum = implode(',', array_map(fn (Role $role) => $role->value, Role::cases()));

        // @todo fix for images

        return [
            'model' => ['required', 'string', 'starts_with:claude-'],
            'system' => ['nullable', 'string'],
            'messages' => ['required', 'array', 'min:1'],
            'messages.*.role' => ['required', 'string', "in:{$roleEnum}"],
            'messages.*.content' => ['required', 'array', 'min:1'],
            'messages.*.content.*.type' => ['required', 'string', 'in:text'],
            'messages.*.content.*.text' => ['required', 'string', 'filled'],
            'max_tokens' => ['nullable', 'integer', 'min:1'],
            'temperature' => ['nullable', 'numeric', 'between:0,1'],
        ];
    }
}
