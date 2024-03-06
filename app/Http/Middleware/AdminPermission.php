<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        echo "start middleware<br>";
        if (!$this->isLogin()) {
            return redirect(route("home"));
        }
        return $next($request);
    }
    public function isLogin()
    {
        return false;
    }
}
