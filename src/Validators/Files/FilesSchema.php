<?php

namespace GergelyGaal\LaravelClaude\Validators\Files;

use GergelyGaal\LaravelClaude\Enums\Role;

final class FilesSchema
{
    public static function rules(): array
    {
        $roleEnum = implode(',', array_map(fn (Role $role) => $role->value, Role::cases()));

        return [
            'name' => ['required', 'string'],
            'content' => ['required', 'string'],
        ];
    }
}
