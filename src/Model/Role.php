<?php

namespace Niyam\ACL\Model;

use Spatie\Permission\Models\Role as RoleModel;

class Role extends RoleModel
{
    protected $connection = 'acl';
    protected $guarded = ['id'];
}
