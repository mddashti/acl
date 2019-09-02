<?php

namespace Niyam\ACL\Model;

use Spatie\Permission\Models\Permission as PermissionModel;

class Permission extends PermissionModel
{
    protected $table = "acl_permissions";

    protected $connection = 'acl';
    protected $guarded = ['id'];

    // public function department()
    // {
    //     return $this->belongsTo(Department::class);
    // }
}
