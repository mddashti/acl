<?php

namespace Niyam\ACL\Http\Controllers;

use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Niyam\ACL\Model\User;
use Niyam\ACL\Infrastructure\BaseController;

class UserController extends BaseController
{
    private $guardName = 'Niyam\ACL\Model\User';

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
        $isHas = $user->hasPermissionTo($permission, $this->guardName);
        return response()->_json(Response::HTTP_OK, 'OK', $isHas);
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
        $permissions = $user->getDirectPermissions();
        return response()->_json(Response::HTTP_OK, 'OK', $permissions);
    }

    public function roles($user)
    {
        return User::findOrFail($user)->getRoles()->get(['id', 'name']);
    }

    public function positions($user)
    {
        return User::findOrFail($user)->getPositions()->get(['id', 'name']);
    }

    public function getUsers()
    {
        return User::all();
    }

    public function createUser()
    {
        $this->validate($this->request, [
            'email'     => 'required|unique:users',
            'password'  => 'required'
        ]);

        $data = $this->request->all();
        $data['avatar'] = NULL;
        $data['signature'] = NULL;

        if ($this->request->hasFile('avatar')) {
            $avatarUpload = FileController::upload(
                $this->request->file('avatar'),
                storage_path('users' . DIRECTORY_SEPARATOR . 'avatar')
            );
            $data['avatar'] = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'avatar' . DIRECTORY_SEPARATOR . $avatarUpload;
        }
        if ($this->request->hasFile('signature')) {
            $signatureUpload = FileController::upload(
                $this->request->file('signature'),
                storage_path('users' . DIRECTORY_SEPARATOR . 'signature')
            );
            $data['signature'] = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'signature' . DIRECTORY_SEPARATOR . $signatureUpload;
        }

        return User::create([
            'name' => $data['name'],
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
                storage_path('users' . DIRECTORY_SEPARATOR . 'avatar')
            );
            $data['avatar'] = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'avatar' . DIRECTORY_SEPARATOR . $avatarUpload;
        }
        if ($this->request->hasFile('signature')) {
            $signatureUpload = FileController::upload(
                $this->request->file('signature'),
                storage_path('users' . DIRECTORY_SEPARATOR . 'signature')
            );
            $data['signature'] = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'signature' . DIRECTORY_SEPARATOR . $signatureUpload;
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

        if ($this->request->hasFile('avatar')) {
            $avatarUpload = FileController::upload(
                $this->request->file('avatar'),
                storage_path('users' . DIRECTORY_SEPARATOR . 'avatar')
            );
            $userData['avatar'] = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'avatar' . DIRECTORY_SEPARATOR . $avatarUpload;
        }
        if ($this->request->hasFile('signature')) {
            $signatureUpload = FileController::upload(
                $this->request->file('signature'),
                storage_path('users' . DIRECTORY_SEPARATOR . 'signature')
            );
            $userData['signature'] = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'signature' . DIRECTORY_SEPARATOR . $signatureUpload;
        }

        $user =  User::create([
            'username' => $userData['username'],
            'name' => $userData['name'],
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
                storage_path('users' . DIRECTORY_SEPARATOR . 'avatar')
            );
            $userData['avatar'] = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'avatar' . DIRECTORY_SEPARATOR . $avatarUpload;
        }
        if ($this->request->hasFile('signature')) {
            $signatureUpload = FileController::upload(
                $this->request->file('signature'),
                storage_path('users' . DIRECTORY_SEPARATOR . 'signature')
            );
            $userData['signature'] = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'signature' . DIRECTORY_SEPARATOR . $signatureUpload;
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
