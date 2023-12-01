<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 信息资产的设备中，锁定顶部的标签样式。
 */
class FilamentLockTab
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uri = $request->getPathInfo();
        $activeTab = $request->get('activeTab');
        $uri = explode('/', $uri);
        if (count($uri) == 3 && $activeTab != $uri[2]) {
            return redirect($request->getPathInfo().'?activeTab='.$uri[2]);
        }

        return $next($request);
    }
}
