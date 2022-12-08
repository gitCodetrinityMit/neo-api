<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class checkApiToken
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
        if(!empty(trim($request->input('api_token')))){
            if(Auth::user())
            {
                $is_exists = User::where('id' , Auth::user()->id)->exists();
                if($is_exists){
                    return $next($request);
                }
            }
            else
            {
                 return response()->json('You are not logged! Please Login or Register', 401);
            }
         }
         return response()->json('Invalid Token', 401);
    }
}
