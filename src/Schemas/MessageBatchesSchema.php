<?php

namespace GaalGergely\LaravelClaude\Schemas;

use GaalGergely\LaravelClaude\Enums\Role;

final class MessageBatchesSchema extends SchemaAbstract implements SchemaInterface
{
    public static function rules(): array
    {
        $roleEnum = implode(',', array_map(fn (Role $role) => $role->value, Role::cases()));

        return [
            'requests' => ['required', 'array', 'min:1'],
            'requests.*.custom_id' => ['required', 'string', 'distinct'],
            'requests.*.params' => ['required', 'array'],
            'requests.*.params.model' => ['required', 'string', 'starts_with:claude-'],
            'requests.*.params.system' => ['nullable', 'string'],
            'requests.*.params.messages' => ['required', 'array', 'min:1'],
            'requests.*.params.messages.*.role' => ['required', 'string', "in:{$roleEnum}"],
            'requests.*.params.messages.*.content' => [
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
            'requests.*.params.messages.*.content.*.type' => ['required', 'string', 'in:text,image'],
            'requests.*.params.messages.*.content.*.text' => ['required_if:requests.*.params.messages.*.content.*.type,text', 'string', 'filled'],
            'requests.*.params.messages.*.content.*.source' => ['required_if:requests.*.params.messages.*.content.*.type,image', 'array'],
            'requests.*.params.messages.*.content.*.source.type' => ['required_if:requests.*.params.messages.*.content.*.type,image', 'string', 'in:url,base64'],
            'requests.*.params.messages.*.content.*.source.url' => ['required_if:requests.*.params.messages.*.content.*.source.type,url', 'string', 'active_url'],
            'requests.*.params.messages.*.content.*.source.media_type' => ['required_if:requests.*.params.messages.*.content.*.source.type,base64', 'string', 'in:image/jpeg,image/png,image/gif,image/webp'],
            'requests.*.params.messages.*.content.*.source.data' => ['required_if:requests.*.params.messages.*.content.*.source.type,base64', 'string'],
            'requests.*.params.max_tokens' => ['required', 'integer', 'min:1'],
            'requests.*.params.temperature' => ['required', 'numeric', 'between:0,1'],
            'requests.*.params.stream' => ['required', 'boolean:strict']
        ];
    }

    public static function defaults(): array
    {
        parent::defaults();
        return [
            'model' => static::$config['model'],
            'max_tokens' => static::$config['max_tokens'],
            'temperature' => static::$config['temperature'],
            'stream' => false
        ];
    }
}

