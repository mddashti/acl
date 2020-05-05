<?php

namespace Niyam\ACL\Model;

// use Niyam\ACL\Infrastructure\BaseModel;
use Illuminate\Database\Eloquent\Model;


class UserVendor extends Model
{
    protected $connection = 'vendor';
    protected $table = 'vendor_users';
}
