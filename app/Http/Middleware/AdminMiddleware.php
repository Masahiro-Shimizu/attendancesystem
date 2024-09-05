<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // ユーザーが認証されているかどうか、かつ管理者かどうかを確認
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // 管理者でない場合、リダイレクト
        return redirect('/home')->with('error', 'You are not authorized to access this page.');
    }
}
