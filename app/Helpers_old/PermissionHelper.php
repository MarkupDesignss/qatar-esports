<?php

use Illuminate\Support\Facades\DB;
use App\Models\RolePermission;
use App\Models\Permission;

class PermissionHelper
{
    public static function has($slug)
    {
        $permission = Permission::where('slug', $slug)->first();

        if (!$permission) {
            return false;
        }

        return RolePermission::where('role', auth()->user()->role)
            ->where('permission_id', $permission->id)
            ->exists();
    }
}