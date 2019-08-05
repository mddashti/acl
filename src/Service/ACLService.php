<?php
namespace Niyam\ACL\Service;

use Niyam\ACL\Model\User;
use Niyam\ACL\Model\Role;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;

class ACLService
{
    protected $token;

    public function __construct(Request $request)
    {
        //$this->token = $request->cookie('access_token');
        $this->token = $_COOKIE['access_token'];
    }

    public function token()
    {
        if (!$this->token)
            return response()->json(['message' => 'Token not found!'], Response::HTTP_NOT_FOUND);

        try {
            $credentials = JWT::decode($this->token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json(['message' => 'Provided token is expired'], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error while decoding token'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $credentials;
    }

    public function findUserPermissions()
    {
        $credentials = $this->token();
        $permissions = json_decode($credentials->sub)->permissions;

        return ['isSuccess'=>true,'data'=>$permissions];
    }

    public function hasPermission($permission)
    {
        $credentials = $this->token();
        $permissions = json_decode($credentials->sub)->permissions;
        foreach ($permissions as $permiss) {
            if ($permission == $permiss->id || $permission == $permiss->name)
                return ['isSuccess'=>true,'data'=>1];
        }
        return ['isSuccess'=>true,'data'=>0];
    }

    public function findUserRoles()
    {
        $credentials = $this->token();
        $roles = json_decode($credentials->sub)->roles;
        return ['isSuccess'=>true,'data'=>$roles];
    }

    public function hasRole($role)
    {
        $credentials = $this->token();
        $roles = json_decode($credentials->sub)->roles;
        foreach ($roles as $rol) {
            if ($role == $rol->id || $role == $rol->name)
                return ['isSuccess'=>true,'data'=>1];
        }

        return ['isSuccess'=>true,'data'=>0];
    }

    public function checkPassword($userId, $password)
    {
        $user = User::where('id', $userId)->first();
        if ($user && \Hash::check($password, $user->password)) {
            return true;
        }
        return false;
    }

    public function userInfo($userId)
    {
        $user = User::where('id', $userId)->first();
        return $user;
    }

    public function givePositionOfUser($userId)
    {
        return User::with('positions')->find($userId);
    }

    public function giveUsersOfPosition($position)
    {
        return Role::with('users')->where('type', 1)->find($position);
    }

    public function findPositions($positionArray)
    {
        return Role::whereIn('id', $positionArray)->get();
    }



    //****************************************************************ADDED

    public function giveRoleOfUser($user)
    {
        return User::findOrFail($user)->getRoles()->get(['id', 'name','title']);
    }


}
