<?php

namespace Niyam\ACL\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    protected $exceptRoutes = [
        'env("LOGIN_URL")',
        'captcha/math',
        'auth/login',
        'auth/logout',
        'auth/recovery',
        'test',
        'test/*',
        'api/login',
        'users', // need 4 create user
        // 'users/with-role',
        // 'users/list',
    ];

    public function handleExcept($uri)
    {
        return (array_search($uri, $this->exceptRoutes)) ? true : false;
        /*
        $ret = false;
        foreach($this->exceptRoutes as $er){
            $er = trim($er, '/');
            $er2 = $er;
            if(substr($er, strlen($er)-1) == '*'){
                $er2 = substr($er, 0, strlen($er)-1);
                $er2 = trim($er2, '/');
                $sp = strpos($uri, $er2);
                if($sp == 0)
                    $ret = true;
            }else{
                if($er == $uri){
                    $ret = true;
                }
            }
        }
        return $ret;
        */
    }

    public function handle($request, Closure $next, $guard = null)
    {
        $uri = $request->path();

        if($this->handleExcept($uri)) return $next($request);

        $token = $request->ajax() ? $request->header('Authorization') : $request->cookie('access_token');

        if (!$token) {
            if ($request->ajax()) return response()->json(['error' => 'Token not provided.'], 401);
            if (env('LOGIN_URL') != $uri) return redirect(env('LOGIN_URL'));            
            return $next($request);
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            if ($request->ajax()) return response()->json(['error' => 'Provided token is expired.'], 400);
            if (env('LOGIN_URL') == $uri) abort(500);
            return redirect(env('LOGIN_URL'));
        } catch (Exception $e) {
            if ($request->ajax()) return response()->json(['error' => 'An error while decoding token.'], 400);
            if (env('LOGIN_URL') == $uri) abort(500);
            return redirect(env('LOGIN_URL'));
        }

        //$user = User::find($credentials->sub);
        $user = $credentials->sub;
        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = json_decode($user);

        if($request->auth->password_change && $uri != 'password' && $uri != 'auth/logout')
            return redirect('password');

        if (env('LOGIN_URL') == $uri)
            return redirect(env('MAIN_URL'));
        return $next($request);
    }
}
