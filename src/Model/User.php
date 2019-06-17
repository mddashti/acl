<?php

namespace Niyam\ACL\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Spatie\Permission\Traits\HasRoles;
use Niyam\ACL\Infrastructure\BaseModel;

class User extends BaseModel implements AuthenticatableContract
{
    use Authenticatable, HasRoles;
    protected $guard_name = 'api';

    protected $fillable = [
        'name', 'username', 'avatar', 'signature', 'email','password'
    ];

    protected $hidden = ['pivot','password','updated_at','deleted_at'];

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
