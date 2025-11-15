<?php

namespace GergelyGaal\LaravelClaude\Validators\Files;

use GergelyGaal\LaravelClaude\Enums\Role;

final class FilesSchema
{
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'content' => ['required', 'string'],
        ];
    }
}
