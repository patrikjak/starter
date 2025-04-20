<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class SyncPermissionsRequest extends FormRequest
{
    /**
     * @return array<string>
     */
    public function getPermissions(): array
    {
        $permissions = [];
        $allData = $this->all();
        
        foreach ($allData as $key => $value) {
            if (!str_contains($key, 'permission_')) {
                continue;
            }

            $permissions[] = str_replace('permission_', '', $key);
        }
        
        return $permissions;
    }
}
