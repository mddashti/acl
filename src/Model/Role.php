<?php

namespace Niyam\ACL\Model;

use Spatie\Permission\Models\Role as RoleModel;

class Role extends RoleModel
{
    protected $table = "acl_roles";

    protected $connection = 'acl';
    protected $guarded = ['id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
