<?php

namespace Niyam\ACL\Infrastructure;

use Illuminate\Database\Eloquent\Model;

class BaseModelDelete extends Model
{
    protected $connection = 'acl';
    protected $guarded = ['id'];
    protected $casts = [
        'options' => 'array',
    ];
}
