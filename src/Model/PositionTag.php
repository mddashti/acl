<?php

namespace Niyam\ACL\Model;

use Spatie\Permission\Models\Role;
use Niyam\ACL\Infrastructure\BaseModel;

class PositionTag extends BaseModel
{
    public $table = 'position_tag';
    public $incrementing = false;

    public function position()
    {
        return $this->belongsTo(Role::class, 'position_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }

    public function owner()
    {
        return $this->belongsTo(Role::class, 'parent_position_id');
    }
}
