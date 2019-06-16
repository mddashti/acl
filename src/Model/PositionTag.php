<?php

namespace Niyam\ACL\Model;

use Spatie\Permission\Models\Role;
use Niyam\ACL\Infrastructure\BaseModel;

class PositionTag extends BaseModel
{
    public $incrementing = false;
    protected $table = 'role_tag';

    public function position()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }

    public function owner()
    {
        return $this->belongsTo(Role::class, 'parent_role_id');
    }
}
