<?php

namespace App\Http\Middleware\teacher;

use Closure;
use Illuminate\Http\Request;

class AuthTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user=auth()->guard('teacher')->user();
        if(!$user){
            return response()->json(['error' => 'Unauthorized Auth/Token'], 400);
        }
        return $next($request);
    }
}
