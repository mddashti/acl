<?php

namespace Niyam\ACL\Http\Controllers;

use Niyam\ACL\Model\User;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;
use Niyam\ACL\Infrastructure\BaseController;

define('DS', DIRECTORY_SEPARATOR);

class UserController extends BaseController
{
    // private $guardName = 'Niyam\ACL\Model\User';

    public function index()
    {
        $userName = $this->user->name;
        return view('panels')->with(['userName' => $userName]);
    }

    public function getAvatar($user)
    {
        $user = User::findOrFail($user);
        // $avatar = $user['avatar'] ? '/storage/users/avatar/' . $user['avatar'] : '';
        $avatar = $user['avatar'];

        return $this->request->is('*/view') ? '<img src="' . $avatar . '">' : $avatar;
    }

    public function getSignature($user)
    {
        $user = User::findOrFail($user);
        // $signature = $user['signature'] ? '/storage/users/signature/' . $user['signature'] : '';
        $signature = $user['signature'];

        return $this->request->is('*/view') ? '<img src="' . $signature . '">' : $signature;
    }

    public function hasPermission($user, $permission)
    {
        $user = User::findOrFail($user);
        return $user->hasPermissionTo($permission) == true ? 1 : 0;
    }

    public function hasRole($user, $role)
    {
        $user = User::findOrFail($user);
        $isHas = $user->hasRole($role);
        return response()->_json(Response::HTTP_OK, 'OK', $isHas);
    }

    public function permissions($user)
    {
        $user  = User::find($user);
        return $permissions =  $user->getAllPermissions();
        //return response()->_json(Response::HTTP_OK, 'OK', $permissions);
    }

    public function roles($user)
    {
        return User::findOrFail($user)->getRoles()->get(['id', 'name']);
    }

    public function usersRole($role)
    {
        return Role::with('users')->findOrFail($role);
    }

    public function positions($user)
    {
        return User::findOrFail($user)->getPositions()->get();
    }

    public function getUsers()
    {
        return User::all();
    }

    public function addRolesToUser($userId)
    {
        $roleData = explode(',', $this->request->roles);

        $user = User::findOrFail($userId);
        $user->syncRoles($roleData);
    }

    public function createUserList()
    {
        $users = $this->request->all();
        $results = [];
        foreach ($users as $user) {
            $randomPass = rand(111111, 999999);
            $createUser = User::create([
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => password_hash($randomPass, PASSWORD_BCRYPT)
            ]);
            $results[] = ['id' => $user['id'], 'acl_id' => $createUser->id, 'password' => $randomPass];
        }
        return $results;
    }

    public function createUser()
    {
        $a = $this->request->validate([
            'name'      => 'required',
            'username'  => 'required',
            'mobile'    => 'required',
            'email'     => 'required|unique:acl_users',
            'password'  => 'required'
        ]);

        $data = $this->request->all();
        $data['avatar'] = NULL;
        $data['signature'] = NULL;

        if ($this->request->hasFile('avatar')) {
            $avatarUpload = FileController::upload(
                $this->request->file('avatar'),
                storage_path('users'.DS.'avatar')
            );
            $data['avatar'] = DS.'storage'.DS.'users'.DS.'avatar'.DS.$avatarUpload;
        }
        if ($this->request->hasFile('signature')) {
            $signatureUpload = FileController::upload(
                $this->request->file('signature'),
                storage_path('users'.DS.'signature')
            );
            $data['signature'] = DS.'storage'.DS.'users'.DS.'signature'.DS.$signatureUpload;
        }

        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'avatar' => $data['avatar'],
            'signature' => $data['signature'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
    }

    public function editUser($userId)
    {
        $data = $this->request->all();
        if ($this->request->has('password') && !empty($this->request->password))
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        else
            unset($data['password']);

        if ($this->request->hasFile('avatar')) {
            $avatarUpload = FileController::upload(
                $this->request->file('avatar'),
                storage_path('users'.DS.'avatar')
            );
            $data['avatar'] = DS.'storage'.DS.'users'.DS.'avatar'.DS.$avatarUpload;
        }
        if ($this->request->hasFile('signature')) {
            $signatureUpload = FileController::upload(
                $this->request->file('signature'),
                storage_path('users'.DS.'signature')
            );
            $data['signature'] = DS.'storage'.DS.'users'.DS.'signature'.DS.$signatureUpload;
        }
        return User::where('id', $userId)->update($data);
    }

    public function getCurrentUser()
    {
        return response()->json(['currentUser' => $this->request->auth]);
    }

    public function getUser($userId)
    {
        return User::findOrFail($userId);
    }

    public function createUserWithRole()
    {
        $userData = $this->request->except(['roles']);
        $roleData = explode(',', $this->request->roles);
        $userData['avatar'] = '';
        $userData['signature'] = '';

        if ($this->request->hasFile('avatar')) {
            $avatarUpload = FileController::upload(
                $this->request->file('avatar'),
                storage_path('users'.DS.'avatar')
            );
            $userData['avatar'] = DS.'storage'.DS.'users'.DS.'avatar'.DS.$avatarUpload;
        }
        if ($this->request->hasFile('signature')) {
            $signatureUpload = FileController::upload(
                $this->request->file('signature'),
                storage_path('users'.DS.'signature')
            );
            $userData['signature'] = DS.'storage'.DS.'users'.DS.'signature'.DS.$signatureUpload;
        }

        $user =  User::create([
            'firstname' => isset($userData['firstname']) ? $userData['firstname'] : '',
            'lastname' => isset($userData['lastname']) ? $userData['lastname'] : '',
            'username' => $userData['username'],
            'name' => $userData['name'],
            'mobile' => isset($userData['mobile']) ? $userData['mobile'] : '',
            'email' => $userData['email'],
            'avatar' => $userData['avatar'],
            'signature' => $userData['signature'],
            'password' => password_hash($userData['password'], PASSWORD_BCRYPT)
        ]);

        $user->syncRoles($roleData);
    }

    public function editUserWithRole($userId)
    {
        $userData = $this->request->except(['roles', '_method']);
        $roleData = explode(',', $this->request->roles);

        if (isset($userData['password']) && !empty($userData['password']))
            $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);
        else
            unset($userData['password']);


        if ($this->request->hasFile('avatar')) {
            $avatarUpload = FileController::upload(
                $this->request->file('avatar'),
                storage_path('users'.DS.'avatar')
            );
            $userData['avatar'] = DS.'storage'.DS.'users'.DS.'avatar'.DS.$avatarUpload;
        }
        if ($this->request->hasFile('signature')) {
            $signatureUpload = FileController::upload(
                $this->request->file('signature'),
                storage_path('users'.DS.'signature')
            );
            $userData['signature'] = DS.'storage'.DS.'users'.DS.'signature'.DS.$signatureUpload;
        }

        $user = User::findOrFail($userId);

        $user->update($userData);
        $user->syncRoles($roleData);
    }

    public function getUsersByRole($role)
    {
        return User::role($role)->get();
    }

    public function getUserPermissions($user)
    {
        $user = User::findOrFail($user);
        return $user->getAllPermissions();
        $type = $this->request->has('type') ? $this->request->type : 0;
        if ($type == 'direct')
            return $user->getDirectPermissions();
        else if ($type == 'roles')
            return $user->getPermissionsViaRoles();
        return $user->getAllPermissions();
    }

    public function deleteUser($user)
    {
        return User::where('id', $user)->delete();
    }
}
