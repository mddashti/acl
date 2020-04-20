<?php

namespace Niyam\ACL\Service;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Niyam\ACL\Model\User;
use Niyam\ACL\Model\Role;
use Niyam\ACL\Model\PositionTag;
use Niyam\ACL\Helper\Graph;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class ACLService
{
    protected $token;

    private $permissions;

    private $roles;

    private $user;

    public function __construct()
    {
        $this->token = isset($_COOKIE['access_token']) ? $_COOKIE['access_token'] : '';
        if ($this->token) {
            $credentials = $this->token();
            $this->user = json_decode($credentials->sub, true);
            $this->permissions = $this->user["permissions1"];
            $this->roles = $this->user["roles1"];
        }
    }

    public function token()
    {
        try {
            $credentials = JWT::decode($this->token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json(['message' => 'Provided token is expired'], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error while decoding token'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $credentials;
    }

    public function createUser(array $userData)
    {
        if (!isset($userData['email']) || !isset($userData['username']) || !isset($userData['password']) || !isset($userData['mobile']))
            return ['isSuccess' => false, 'error' => 'data passed missing!'];

        $q = User::where('email', $userData["email"])
            ->orWhere('mobile', $userData["mobile"])
            ->orWhere('username', $userData["username"])
            ->get();
        if (count($q))
            return ['isSuccess' => false, 'error' => 'user exist!'];

        return User::create([
            'firstname' => isset($userData["firstname"]) ? $userData["firstname"] : "",
            'lastname' => isset($userData["lastname"]) ? $userData["lastname"] : "",
            'name' => isset($userData["name"]) ? $userData["name"] : "",
            'username' => $userData['username'],
            'mobile' => $userData['mobile'],
            'email' => $userData['email'],
            'avatar' => '',
            'signature' => '',
            'password_change' => 0,
            'password' => password_hash($userData['password'], PASSWORD_BCRYPT)
        ]);
    }

    public function findCurrentUser()
    {
        return $this->user;
    }

    public function permissions()
    {
        return $this->permissions();
    }

    public function roles()
    {
        return $this->roles();
    }

    public function findUserPermissions()
    {
        return ['isSuccess' => true, 'data' => $this->permissions];
    }

    public function hasPermission($permission)
    {
        return ['isSuccess' => $this->arrayHas($permission, $this->permissions), 'data' => 0];
    }

    private function arrayHas($key, $array)
    {
        return array_key_exists($key, $array) || in_array($key, $array);
    }

    public function findUserRoles()
    {
        return ['isSuccess' => true, 'data' => $this->roles];
    }

    public function hasRole($role)
    {
        return ['isSuccess' => $this->arrayHas($role, $this->roles), 'data' => 0];
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
        return User::where('id', $userId)->first();
    }

    public function givePositionOfUser($userId)
    {
        $user = User::with('positions')->find($userId);
        return count($user['positions']) ? $user['positions'][0]['id'] : 0;
    }

    public function givePositionsOfUser($userId)
    {
        $user = User::with('positions')->find($userId);
        return count($user['positions']) ? $user['positions']->pluck('id') : [];
    }

    public function giveUsersOfPosition($position)
    {
        return Role::with('users')->where('type', 1)->find($position);
    }

    public function findPositions($positionArray)
    {
        return Role::whereIn('id', $positionArray)->get();
    }

    // new
    public function giveUsersByTag($departments, $tag, $successor) // by tag & dep
    {
    }

    public function giveUserOfPosition($positionId, $successor = false)
    {
        if ($successor) { // پرنت جانشین رول هست
            $position = Role::with('users')->where('type', 0)->find($positionId);
            // $userId = count($position['users']) ? $position['users'][0]['id'] : [];
            // $userId = count($position['users']) ? $position['users'] : [];
            $getSuccessor = $this->getPositionByTag($positionId, 1);
            // $successorId = count($getSuccessor) ? $getSuccessor[0]['parent_role_id'] : [];
            // $successorId = count($getSuccessor) ? $getSuccessor : [];
            return [$position, $getSuccessor];
        } else {
            $position = Role::with('users')->where('type', 0)->find($positionId);
            return $position;
            // return count($position['users']) ? [$position['users'][0]['id']] : [];
            return count($position['users']) ? $position['users'] : [];
        }
    }

    public function giveUsersOfPositions($positions, $successor = false)
    {
        $successor = 1;
        $ret = [];
        if (is_array($positions)) {
            foreach ($positions as $position) {
                $_get = self::giveUserOfPosition($position, $successor);
                $_users = [];
                $_tags = [];
                foreach ($_get['users'] as $_g) {
                    $_users[] = $_g['id'];
                }
                $ret['users'][] = $_users;
            }
        }

        return $ret;



        $positions = Role::with('users')->where('type', 0)->find($positions);
        return $positions;
        return count($position['users']) ? [$position['users']/*[0]*/['id']] : [];

        // $data = Role::with('users')->find($positions);
        // return $data;
        /*
        return [
            [
                users:[1,2,3],
                tags:[4,0,6]
            ],
            [
                users:[1,4,5],
                tags:[1,0,8]
            ],
        ]
        */
    }

    //****************************************************************ADDED
    public function giveRoleOfUser($user)
    {
        return User::findOrFail($user)->getRoles()->get(['id', 'name', 'title']);
    }

    // RELATIONS
    public static function getRoleByLevel($roleX, $level, $direction = 'parent')
    {
        return self::relations(compact('roleX', 'level', 'direction'));
    }

    public static function getUsersOfRole($role)
    {
        $field = (gettype($role) == 'integer') ? 'id' : 'name';
        return User::whereHas('getRoles', function ($query) use ($role, $field) {
            $query->where($field, $role);
        })->get();
    }

    public static function getUsersOfRoles($roles, $columns = '*')
    {
        $field = (gettype($roles[0]) == 'integer') ? 'id' : 'name';
        return User::whereHas('getRoles', function ($query) use ($roles, $field) {
            $query->whereIn($field, $roles);
        })->get($columns);
    }

    public static function getUserOfPositions($position_id)
    {
        return User::whereHas('positions', function ($query) use ($position_id) {
            $query->where('id', $position_id);
        })->get();
    }

    public static function getPositionOfUsers($user_id)
    {
        return User::with('positions')->where('id', $user_id)->get();
    }

    public static function getPositionByTag($role_id, $tag_id)
    {
        return PositionTag::where('position_id', $role_id)->where('tag_id', $tag_id)->get();
    }
    // public function getSubOfRole(){}

    public static function getUserByXLD($roleX = null, $roleY = null, $level = null, $tag = null, $direction = null)
    {
        return self::relations(compact('roleX', 'roleY', 'level', 'tag', 'direction'));
    }

    public static function relations($data)
    {
        // $roleX, $roleY, $level, $tag, $direction
        $roleX = isset($data['roleX']) ? $data['roleX'] : null;
        $roleY = isset($data['roleY']) ? $data['roleY'] : null;
        $level = isset($data['level']) ? $data['level'] : null;
        $tag = isset($data['tag']) ? $data['tag'] : null;
        $direction = isset($data['direction']) ? $data['direction'] : null;

        if (!$roleX)
            return response()->json(['error' => 'origin not found!'], Response::HTTP_NOT_FOUND);

        $_allRoles = Role::all(['id', 'parent_id']);
        $allRoles = [];
        foreach ($_allRoles as $ar) {
            if ($direction == 'child') // check direction
                $allRoles[$ar['parent_id']] = $ar['id'];
            else // if type == parent
                $allRoles[$ar['id']] = $ar['parent_id'];
        }

        if ($level) {
            $relation = self::getRolesByLevel($roleX, $level, $allRoles);
        } else if ($roleY || $tag) {
            $createNodes = self::createNodes($allRoles);
            $graph = new Graph($createNodes);

            if ($tag) {
                $parent_id = PositionTag::where(['role_id' => $roleX, 'tag_id' => $tag])->get('parent_role_id');
                if (!count($parent_id))
                    return response()->json(['error' => 'tag destination not found!'], Response::HTTP_NOT_FOUND);

                $relation = $graph->breadthFirstSearch($roleX, $parent_id[0]['parent_role_id']);
            } else {
                $relation = $graph->breadthFirstSearch($roleX, $roleY);
            }
        } else {
            return response()->json(['error' => 'destination not found!'], Response::HTTP_NOT_FOUND);
        }


        $users = User::role($relation)->get();

        return response()->json(['users' => $users]);
    }

    public static function getRolesByLevel($start, $level, array $data)
    {
        $result = [(int) $start];
        for ($i = 0; $i < $level; $i++) {

            if (!isset($data[$start]) || $data[$start] == 0)
                break;

            $start = $data[$start];
            $result[] = $start;
        }

        return $result;
    }

    public static function createNodes(array $data)
    {
        $result = [];
        foreach ($data as $id => $parent_id) {
            if ($id != 0 && $parent_id != 0) {
                $result[$id][] = $parent_id;
                $result[$parent_id][] = $id;
            }
        }
        return $result;
    }
    // \RELATIONS


}
