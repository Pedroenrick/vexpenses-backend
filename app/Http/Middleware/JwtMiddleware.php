<?php 

namespace App\Http\Middleware;

use App\Models\JwtAuth;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;

class JwtMiddleware
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
        $header = explode(' ', $request->header('Authorization'));
        $bearer = isset($header[0]) ? $header[0] : false;
        $token = isset($header[1]) ? $header[1] : false;

        if(!$token || !$bearer){
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        try{
            JwtAuth::decodeToken($token);
            return $next($request);
        }catch(ExpiredException $e){
            return response()->json([
                'message' => 'Token expired'
            ], 401);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Token invalid'
            ], 401);
        }
    }
}