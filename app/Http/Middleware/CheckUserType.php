<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = $request->user();
        // $user = Auth::user();

        if(!$user){
            return redirect()->route('login');
        }

        if(! in_array($user->type, $types)){
            abort(403, 'you are not allowed to access this page!');
        }        

        return $next($request);

        // $response = $next($request);
        // return $response->headers('x-powered-by' , 'Laravel');
    }
}
