<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->guest(route('login'))->with('status', 'กรุณาเข้าสู่ระบบก่อน');
        }

        if (! $request->user()->is_admin) {
            abort(403, 'เฉพาะผู้ดูแลระบบเท่านั้น');
        }

        return $next($request);
    }
}
