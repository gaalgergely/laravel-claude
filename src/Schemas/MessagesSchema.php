<?php

namespace GaalGergely\LaravelClaude\Schemas;

use GaalGergely\LaravelClaude\Enums\Role;

final class MessagesSchema extends SchemaAbstract implements SchemaInterface
{
    public static function rules(): array
    {
        $roleEnum = implode(',', array_map(fn (Role $role) => $role->value, Role::cases()));

        return [
            'model' => ['required', 'string', 'starts_with:claude-'],
            'system' => ['nullable', 'string'],
            'messages' => ['required', 'array', 'min:1'],
            'messages.*.role' => ['required', 'string', "in:{$roleEnum}"],
            'messages.*.content' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (is_array($value)) {
                        if (count($value) === 0) {
                            $fail('The '.$attribute.' field must not be empty.');
                        }
                        return;
                    }

                    if (!is_string($value) || trim($value) === '') {
                        $fail('The '.$attribute.' field must be a non-empty string or array.');
                    }
                },
            ],
            'messages.*.content.*.type' => ['required', 'string', 'in:text,image'],
            'messages.*.content.*.text' => ['required_if:messages.*.content.*.type,text', 'string', 'filled'],
            'messages.*.content.*.source' => ['required_if:messages.*.content.*.type,image', 'array'],
            'messages.*.content.*.source.type' => ['required_if:messages.*.content.*.type,image', 'string', 'in:url,base64'],
            'messages.*.content.*.source.url' => ['required_if:messages.*.content.*.source.type,url', 'string', 'active_url'],
            'messages.*.content.*.source.media_type' => ['required_if:messages.*.content.*.source.type,base64', 'string', 'in:image/jpeg,image/png,image/gif,image/webp'],
            'messages.*.content.*.source.data' => ['required_if:messages.*.content.*.source.type,base64', 'string'],
            'max_tokens' => ['required', 'integer', 'min:1'],
            'temperature' => ['required', 'numeric', 'between:0,1'],
            'stream' => ['required', 'boolean:strict']
        ];
    }

    public static function defaults(): array
    {
        parent::initDefaults();
        return [
            'model' => static::$config['model'],
            'max_tokens' => static::$config['max_tokens'],
            'temperature' => static::$config['temperature'],
            'stream' => false
        ];
    }
}

