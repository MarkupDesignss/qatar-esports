<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');

        $selected = RolePermission::where('role', 'moderator')
            ->pluck('permission_id')
            ->toArray();

        return view('admin.permissions.index', compact('permissions', 'selected'));
    }

    public function update(Request $request)
    {
        RolePermission::where('role', 'moderator')->delete();

        if ($request->has('permissions')) {

            foreach ($request->permissions as $permission) {

                RolePermission::create([
                    'role' => 'moderator',
                    'permission_id' => $permission
                ]);
            }
        }

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Moderator permissions updated successfully.');
    }
}
