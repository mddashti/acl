<?php

namespace Niyam\ACL\Model;

use Spatie\Permission\Models\Permission as PermissionModel;

class Permission extends PermissionModel
{
    protected $connection = 'acl';
    protected $guarded = ['id'];
}
