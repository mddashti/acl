<?php

namespace Niyam\ACL\Model;

use Niyam\ACL\Infrastructure\BaseModelDelete;

class RoleUser extends BaseModelDelete
{
    public $timestamps = false;
    protected $table = 'model_has_roles';
}
