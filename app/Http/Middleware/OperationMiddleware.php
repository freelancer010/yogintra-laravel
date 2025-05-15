<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_role_id')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        if (session('is_supper')) {
            return $next($request);
        }

        $module = $request->segment(1) ?? 'dashboard';
        $operation = $request->segment(2) ?? 'access';
        $admin_role_id = session('admin_role_id');

        // print_r($module);
        // print_r($operation);
        // die();

        $moduleAccess = DB::table('module_access')
            ->where([
                'admin_role_id' => $admin_role_id,
                'module' => $module,
                'operation' => $operation
            ])->first();

        if (!$moduleAccess) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Access Denied.'], 403);
            }
            return redirect()->route('access.denied', [
                'back' => urlencode(base64_encode($request->getRequestUri()))
            ]);
        }

        return $next($request);
    }
}
