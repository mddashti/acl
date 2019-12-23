<?php

namespace Niyam\ACL\Model;

use Niyam\ACL\Infrastructure\BaseModel;

class Department extends BaseModel
{
    public function positions()
    {
        return $this->hasMany(Role::class);
    }
}
