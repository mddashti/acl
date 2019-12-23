<?php

namespace Niyam\ACL\Model;

use Illuminate\Database\Eloquent\Model;

class DepartmentTag extends Model
{
    // protected $table = "acl_department_tag";
    protected $connection = "acl";
    public $timestamps = false;    
    protected $fillable = ['department_id', 'tag_id'];
}
