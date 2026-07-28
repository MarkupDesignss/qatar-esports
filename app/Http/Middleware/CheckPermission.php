<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\PermissionHelper;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!PermissionHelper::has($permission)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
