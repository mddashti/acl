<?php

namespace Niyam\ACL\Http\Controllers;

use Firebase\JWT\JWT;
use Niyam\ACL\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Niyam\ACL\Infrastructure\BaseController;


class AuthController extends BaseController
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function jwt($user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => json_encode($user), // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + env('TOKEN_LIFE_TIME', 60) * 60 // Expiration time
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    public function authenticate(User $user)
    {
        $this->request->validate([
            'username'  => 'required',
            'password'  => 'required',
            'captcha'   => env('CAPTCHA_ENABLED', true) ? 'required|captcha' : 'required'
        ]);

        // AG todo
        $httpRef = isset($_SERVER['HTTP_REFERER']) ? urldecode($_SERVER['HTTP_REFERER']) : '';
        $parseRef = parse_url($httpRef);
        parse_str((isset($parseRef['query']) ? $parseRef['query'] . (isset($parseRef['fragment']) ? '#' . $parseRef['fragment'] : '') : null), $queryString);
        $ref = isset($queryString['ref']) ? $queryString['ref'] : '';
        // \AG todo

        // Find the user by email
        $userName =  $this->request->input('username');
        $user = User::where('email', $userName)->orWhere('username', $userName)->first();
        /*
        $user = User::where(function($q) use ($userName){
            $q->where('email', $userName)->orWhere('username', $userName);
        })->where('active', 1)->first();
        */
        if (!$user)
            return response()->json(['error' => 'Email does not exist.'], 400);

        if ($user->active == 0)
            return response()->json(['error' => 'user not active.'], 403);

        $user->permissions1 = $user->getAllPermissions()->pluck('name', 'id');
        $user->roles1 = $user->getRoles()->pluck('name', 'id');

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()
                ->json([
                    'token' => $this->jwt($user),
                    'ref' => $ref,
                    'user' => json_encode($user)
                ], 200)
                ->cookie('access_token', $this->jwt($user), env('TOKEN_LIFE_TIME', 60));
        }

        // Bad Request response
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }
    public function logout(Request $request)
    {
        return redirect('/')->withCookie(Cookie::forget('access_token'));
    }

    public function recoveryPassword()
    {
        return view('recovery-password');
    }

    public function changePassword()
    {
        echo "change PW";
    }

    // API
    public function apiAuthenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  => 'required',
            'password'  => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['error' => 'Validation fails.'], 400);

        $username = $request->get('username');
        $password = $request->get('password');

        $user = User::where('email', $username)->orWhere('username', $username)->first();

        if (!$user)
            return response()->json(['error' => 'Email does not exist.'], 400);

        if (Hash::check($password, $user->password)) {
            $user->permissions = $user->getPermissionsViaRoles();
            $user->roles = $user->getRoles()->get();
            return response()->json(['token' => $this->jwt($user), 'user' => $user], 200);
        }

        return response()->json(['error' => 'Password is wrong.'], 400);
    }
}
