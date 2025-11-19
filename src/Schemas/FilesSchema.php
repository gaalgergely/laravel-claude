<?php

namespace GergelyGaal\LaravelClaude\Schemas;

final class FilesSchema extends SchemaAbstract implements SchemaInterface
{
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'content' => ['required', 'string'],
        ];
    }

    public static function defaults(): array
    {
        return [];
    }
}
