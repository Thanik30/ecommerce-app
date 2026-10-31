<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CookieToBearer
{
    public function handle(Request $request, Closure $next)
    {
        // ถ้าเบราว์เซอร์ส่ง Cookie มา ให้ดึงไปใส่ใน Authorization Header อัตโนมัติ
        if ($request->hasCookie('auth_token')) {
            $request->headers->set('Authorization', 'Bearer ' . $request->cookie('auth_token'));
        }
        return $next($request);
    }
}
