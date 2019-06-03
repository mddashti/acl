<?php

namespace Niyam\ACL\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class PositionTag extends Model
{
    public $incrementing = false;
    protected $fillable = ['role_id', 'parent_role_id', 'tag_id'];
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
