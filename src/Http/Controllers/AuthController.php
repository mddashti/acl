<?php

namespace Niyam\ACL\Http\Controllers;

use Firebase\JWT\JWT;
use Niyam\ACL\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Niyam\ACL\Infrastructure\BaseController;
use Niyam\ACL\SMSProviders\Magfa\SMSService;

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
        return view('recovery');
    }

    public function changePassword2(Request $request, SMSService $smsService)
    {
        $mobile = $request->mobile;
        $code = $request->code;
        $password = $request->password;
        $password_confirmed = $request->password_confirmation;

        if(strlen($password) < 6)
            return 'کلمه عبور کوتاه است. (حداقل ۶ حرف)';
        else if($password != $password_confirmed)
            return 'کلمات عبور یکسان نمی باشند.';

        $user = User::where('mobile', $mobile)->where('sms_code', $code)->first();
        if(!$user) return 'کد اشتباه است.';

        $user->sms_code = null;
        $user->password = password_hash($password, PASSWORD_BCRYPT);
        $user->save();
        return redirect('/');
    }

    public function changePassword(Request $request, SMSService $smsService)
    {
        $mobile = $request->mobile;
        $username = $request->username;

        $user = User::where('mobile', $mobile)->where(function($q) use ($username){
            return $q->where('username', $username)->orWhere('email', $username);
        })->first();
        if(!$user) return 'کاربری با مشخصات فوق یافت نشد.';

        $code = rand(11111, 99999);
        $txt = "کد ورود: $code";
        $user->sms_code = $code;
        $user->save();
        $ss = $smsService->enqueueSample([$mobile], $txt);
        if($ss['successfull']){
            return redirect('/auth/recovery?mobile='.$mobile);
        }
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
