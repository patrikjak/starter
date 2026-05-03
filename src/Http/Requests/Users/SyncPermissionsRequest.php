<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class SyncPermissionsRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [];

        foreach (array_keys($this->all()) as $key) {
            if (str_starts_with($key, 'permission_')) {
                $rules[$key] = ['in:on'];
            }
        }

        return $rules;
    }

    /**
     * @return array<string>
     */
    public function getPermissions(): array
    {
        $permissions = [];
        $allData = $this->all();

        foreach ($allData as $key => $value) {
            if (!str_starts_with($key, 'permission_')) {
                continue;
            }

            $permissions[] = substr($key, strlen('permission_'));
        }

        return $permissions;
    }
}
