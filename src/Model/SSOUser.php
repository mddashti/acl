<?php

namespace Niyam\ACL\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Niyam\ACL\Service\ACLService;
use Spatie\Permission\Traits\HasRoles;
use Niyam\ACL\Infrastructure\BaseModel;

class SSOUser extends BaseModel implements AuthenticatableContract
{
    use Authenticatable, HasRoles;
    protected $guard_name = 'api';


    protected $guarded = [];

    protected $hidden = ['pivot'];
    protected $aclService;

    public function __construct(ACLService $aclService)
    {
        $this->aclService = $aclService;
    }

    //*******************************************************

    public function hasPermission($permission)
    {
        $res = $this->aclService->hasPermission($permission); //$this->permissions()->hasPermissionTo($permission);

        return $res['isSuccess'];
    }

    public function hasRole($role)
    {
        $res = $this->aclService->hasRole($role); //$this->hasRole($role);
        return $res['isSuccess'];
    }

    public function roles()
    {
        $res = $this->aclService->findUserRoles();
        // return $res['isSuccess']?collect($res['data']):[];
        $fres = [];
        foreach ($res['data'] as $id => $name) {
            $fres[] = ['id' => $id, 'name' => $name];
        }
        return collect($fres);
    }

    public function permissions()
    {
        $res = $this->aclService->findUserPermissions();
        $fres = [];
        foreach ($res['data'] as $id => $name) {
            $fres[] = ['id' => $id, 'name' => $name];
        }
        return collect($fres);
    }

    public function rolePermissions()
    {
        $res = $this->aclService->findUserPermissions();
        return $res['isSuccess'] ? collect($res['data']) : [];
    }
}
