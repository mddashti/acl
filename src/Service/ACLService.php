<?php
namespace Niyam\ACL\Service;

use Niyam\ACL\Model\User;
use Niyam\ACL\Model\Role;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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
        // $this->token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJ7XCJpZFwiOjEsXCJuYW1lXCI6XCJEYXZpZFwiLFwidXNlcm5hbWVcIjpcImRhdmlkXCIsXCJlbWFpbFwiOlwibWRkYXNodGlAZ21haWwuY29tXCIsXCJzeXN0ZW1cIjowLFwiYXZhdGFyXCI6XCJcIixcInNpZ25hdHVyZVwiOlwiXCIsXCJjcmVhdGVkX2F0XCI6bnVsbCxcInVwZGF0ZWRfYXRcIjpudWxsLFwicm9sZXNcIjpbe1wiaWRcIjoxLFwibmFtZVwiOlwiQWFcIixcInRpdGxlXCI6XCJzc1wiLFwiZ3VhcmRfbmFtZVwiOlwiYXBpXCIsXCJwYXJlbnRfaWRcIjoyLFwidHlwZVwiOjAsXCJkZXBhcnRtZW50X2lkXCI6bnVsbCxcInVzZXJfaWRcIjpudWxsLFwiY3JlYXRlZF9hdFwiOm51bGwsXCJ1cGRhdGVkX2F0XCI6bnVsbCxcInBpdm90XCI6e1wibW9kZWxfaWRcIjoxLFwicm9sZV9pZFwiOjEsXCJtb2RlbF90eXBlXCI6XCJOaXlhbVxcXFxBQ0xcXFxcTW9kZWxcXFxcVXNlclwifX0se1wiaWRcIjoyLFwibmFtZVwiOlwiQmJCYjFcIixcInRpdGxlXCI6XCJlZVwiLFwiZ3VhcmRfbmFtZVwiOlwiYXBpXCIsXCJwYXJlbnRfaWRcIjowLFwidHlwZVwiOjAsXCJkZXBhcnRtZW50X2lkXCI6bnVsbCxcInVzZXJfaWRcIjozLFwiY3JlYXRlZF9hdFwiOm51bGwsXCJ1cGRhdGVkX2F0XCI6XCIyMDE5LTA1LTI2IDA3OjAyOjM3XCIsXCJwaXZvdFwiOntcIm1vZGVsX2lkXCI6MSxcInJvbGVfaWRcIjoyLFwibW9kZWxfdHlwZVwiOlwiTml5YW1cXFxcQUNMXFxcXE1vZGVsXFxcXFVzZXJcIn19XSxcInBlcm1pc3Npb25zXCI6W3tcImlkXCI6MSxcIm5hbWVcIjpcIlAtQVwiLFwidGl0bGVcIjpcInBlckFcIixcInBhcmVudF9pZFwiOjAsXCJndWFyZF9uYW1lXCI6XCJOaXlhbVxcXFxBQ0xcXFxcTW9kZWxcXFxcVXNlclwiLFwiY3JlYXRlZF9hdFwiOlwiMjAxOS0wNS0xOCAxOTozMDowMFwiLFwidXBkYXRlZF9hdFwiOlwiMjAxOS0wNS0xOCAxOTozMDowMFwiLFwicGl2b3RcIjp7XCJtb2RlbF9pZFwiOjEsXCJwZXJtaXNzaW9uX2lkXCI6MSxcIm1vZGVsX3R5cGVcIjpcIk5peWFtXFxcXEFDTFxcXFxNb2RlbFxcXFxVc2VyXCJ9fSx7XCJpZFwiOjMsXCJuYW1lXCI6XCJQLUNcIixcInRpdGxlXCI6XCJwZXJDXCIsXCJwYXJlbnRfaWRcIjowLFwiZ3VhcmRfbmFtZVwiOlwiTml5YW1cXFxcQUNMXFxcXE1vZGVsXFxcXFVzZXJcIixcImNyZWF0ZWRfYXRcIjpcIjIwMTktMDUtMTggMTk6MzA6MDBcIixcInVwZGF0ZWRfYXRcIjpcIjIwMTktMDUtMTggMTk6MzA6MDBcIixcInBpdm90XCI6e1wibW9kZWxfaWRcIjoxLFwicGVybWlzc2lvbl9pZFwiOjMsXCJtb2RlbF90eXBlXCI6XCJOaXlhbVxcXFxBQ0xcXFxcTW9kZWxcXFxcVXNlclwifX1dfSIsImlhdCI6MTU1OTM3MzQzNSwiZXhwIjoxNTU5NTg5NDM1fQ.VPf8hjAyAHPt_F3qYjbnDAmcp9-jDASP3wj2n4omDfc';
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
