<?php
namespace Niyam\ACL\Helper;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class ACLService
{
    protected $token;

    // public function __construct(Request $request)
    // {
    //     $this->token = $this->request->cookie('access_token');
    // }
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function token()
    {
        if(!$this->token)
            return response()->_json(Response::HTTP_NOT_FOUND, 'Token not found!');

        try {
            $credentials = JWT::decode($this->token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->_json(Response::HTTP_UNPROCESSABLE_ENTITY, 'Provided token is expired');
        } catch (Exception $e) {
            return response()->_json(Response::HTTP_UNPROCESSABLE_ENTITY, 'An error while decoding token');
        }

        return $credentials;
    }

    public function permissions()
    {
        $credentials = $this->token();
        $permissions = json_decode($credentials->sub)->permissions;

        return response()->_json(Response::HTTP_OK, 'OK', $permissions);
    }

    public function hasPermission($permission)
    {
        $credentials = $this->token();
        $permissions = json_decode($credentials->sub)->permissions;
        foreach($permissions as $permiss){
            if($permission == $permiss->id)
                return response()->_json(Response::HTTP_OK, 'OK', true);
        }

        return response()->_json(Response::HTTP_NOT_FOUND, '', false);
    }

    public function roles()
    {
        $credentials = $this->token();
        $roles = json_decode($credentials->sub)->roles;

        return response()->_json(Response::HTTP_OK, 'OK', $roles);
    }

    public function hasRole($role)
    {
        $credentials = $this->token();
        $roles = json_decode($credentials->sub)->roles;
        foreach($roles as $rol){
            if($role == $rol->id)
                return response()->_json(Response::HTTP_OK, 'OK', true);
        }

        return response()->_json(Response::HTTP_NOT_FOUND, '', false);
    }

}