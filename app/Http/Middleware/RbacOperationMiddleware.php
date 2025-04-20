<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RbacOperationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session('is_supper')) {
            return $next($request);
        }

        $module = $request->segment(1);
        $operation = $request->segment(2);

        if ($module === 'recruit') {
            $module = 'recruiter';
        }

        $moduleAccess = session('module_access');

        if (!isset($moduleAccess[$module][$operation])) {
            return redirect()->route('access.denied', [
                'back' => urlencode(base64_encode($request->getRequestUri()))
            ]);
        }

        return $next($request);
    }
}
