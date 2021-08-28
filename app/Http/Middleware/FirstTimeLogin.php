<?php

namespace App\Http\Middleware;

use Closure;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class FirstTimeLogin
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
        
        
        if(Auth::check()){
            $firstTimeLogin = Auth::user()->first_time_login;
            $currentPath = Route::currentRouteName();
 
            if($firstTimeLogin == 'true' && $currentPath != 'startChangePassIndex' && $currentPath != 'startChangePass' && $currentPath != 'logout'){
                return redirect('start-change-password');
                //return route('startChangePassIndex');
            }else{
                return $next($request);
            }

        }else{

            return $next($request);
        }
    }
}
