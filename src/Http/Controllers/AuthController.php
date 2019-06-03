<?php

namespace Niyam\ACL\Http\Controllers;

use Validator;
use Niyam\ACL\Model\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    // protected function jwt(User $user)
    protected function jwt($user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => json_encode($user), // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + env('TOKEN_LIFE_TIME', 60) * 60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * 
     * @param  \App\User   $user 
     * @return mixed
     */
    public function authenticate(User $user)
    {
        $this->validate($this->request, [
            'username'     => 'required',
            'password'  => 'required'
        ]);

        // AG todo
        $httpRef = isset($_SERVER['HTTP_REFERER']) ? urldecode($_SERVER['HTTP_REFERER']) : '';
        $parseRef = parse_url($httpRef);
        parse_str((isset($parseRef['query']) ? $parseRef['query'] : null), $queryString);
        $ref = isset($queryString['ref']) ? $queryString['ref'] : '';
        // \AG todo

        // Find the user by email
        $userName =  $this->request->input('username');
        $user = User::where('email', $userName)->orWhere('username', $userName)->first();

        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }

        $user->permissions = $user->getPermissionsViaRoles();
        $user->roles = $user->getRoles()->get();

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user),
                'ref'   => $ref, // AG
            ], 200);
        }

        // Bad Request response        
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }
    public function logout(Request $request)
    {

        // return 'ok';
        // AG
        $ref = isset($_SERVER['HTTP_REFERER']) ? urlencode($_SERVER['HTTP_REFERER']) : '';
        return response()->json(['ref'=>$ref]);
    }
}
