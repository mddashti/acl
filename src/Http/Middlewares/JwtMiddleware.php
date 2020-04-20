<?php

namespace Niyam\ACL\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    protected $exceptRoutes = [
        'captcha/*',
        'auth/*',
        'test',
        'test/*',
        'api/login',
        'users', // need 4 create user
    ];

    public function handleExcept($request)
    {
        foreach ($this->exceptRoutes as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }

    public function handle($request, Closure $next, $guard = null)
    {
        $uri = $request->path();

        if ($this->handleExcept($request)) return $next($request);

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

        if ($request->auth->password_change && $uri != 'password' && $uri != 'auth/logout')
            return redirect('password');

        if (env('LOGIN_URL') == $uri)
            return redirect(env('MAIN_URL'));
        return $next($request);
    }
}
