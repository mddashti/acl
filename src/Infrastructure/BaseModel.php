<?php

namespace Niyam\ACL\Infrastructure;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;
    // protected $connection = 'acl';
    protected $guarded = ['id'];
    protected $casts = [
        'options' => 'array',
    ];
    protected $hidden = ['deleted_at', 'updated_at'];
}
