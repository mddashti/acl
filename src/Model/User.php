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

    protected $guarded = ['id'];

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

    #region scopes
    public function scopeOfName($query, $name)
    {
        if (empty($name))
            return $query;
        return $query->where('firstname', $name);
    }

    public function scopeOfFamily($query, $family)
    {
        if (empty($family))
            return $query;
        return $query->where('lastname', $family);
    }

    public function scopeOfPersonnelCode($query, $personnelCode)
    {
        if (empty($personnelCode))
            return $query;
        return $query->where('personnel_code', $personnelCode);
    }

    public function scopeOfPosition($query, $position)
    {
        if (empty($position))
            return $query;
        return $query->whereHas('positions', function ($q) use ($position) {
            $q->where('id', $position);
        });
    }
    #endregion
}
