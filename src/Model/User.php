<?php

namespace Niyam\ACL\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Spatie\Permission\Traits\HasRoles;
use Niyam\ACL\Infrastructure\BaseModel;
use Illuminate\Notifications\Notifiable;


class User extends BaseModel implements AuthenticatableContract
{
    use Authenticatable, HasRoles, Notifiable;
    protected $guard_name = 'api';

    protected $fillable = [
        'name', 'mobile', 'username', 'personnel_code', 'gender', 'avatar', 'signature', 'email', 'password', 'password_change'
    ];

    protected $hidden = ['pivot', 'password', 'updated_at', 'deleted_at', 'roles', 'permissions'];

    public function getRoles()
    {
        return $this->roles()->where('type', 0);
    }

    public function getPositions()
    {
        return $this->roles()->where('type', 1);
    }

    public function positions()
    {
        return $this->roles()->where('type', 1);
    }
}
