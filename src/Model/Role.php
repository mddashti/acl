<?php

namespace Niyam\ACL\Model;

use Spatie\Permission\Models\Role as RoleModel;

class Role extends RoleModel
{
    protected $hidden = ['pivot'];
    protected $connection = 'acl';
    protected $guarded = ['id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
