<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = JWTAuth::authenticate();
        
        if($user->roles->role_id == 0){
          //  return ['redirect' => route('root')];
                $response = 'There was a problem with admin authenticating.';
                return $this->json($response, 404);
        }


        return $next($request);
    }
}
