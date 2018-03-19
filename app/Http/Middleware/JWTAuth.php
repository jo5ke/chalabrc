<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\Utilities;


class JWTAuth
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
        $auth = $request->header('Authorization');
        $token = NULL;

        if($auth != NULL){
            //$token = Utilities::ValidateJWTToken($auth);
            $token = JWTAuth::parseToken();

            if($token != NULL){
                $request->merge([
                    // 'auth-email' => $token->email,
                    // 'auth-user_type' => $token->user_type,
                    'auth-uuid'   =>  $token->uuid
                ]);                
            }else{
                return response("Unauthorized.", 401);
            }
        }else{
            return response("Unauthorized.", 401);
        }

        return $next($request);
    }
}
