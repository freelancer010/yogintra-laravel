<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RbacModuleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session('is_supper')) {
            return $next($request);
        }

        $module = $request->segment(1); // controller name

        if ($module === 'recruit') {
            $module = 'recruiter';
        }

        $moduleAccess = session('module_access');

        if (!isset($moduleAccess[$module]) || !array_key_exists('access', $moduleAccess[$module])) {
            return redirect()->route('access.denied', [
                'back' => urlencode(base64_encode($request->getRequestUri()))
            ]);
        }

        return $next($request);
    }
}
