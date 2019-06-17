<?php

namespace Niyam\ACL\Http\Controllers;

use Niyam\ACL\Model\Tag;
use Niyam\ACL\Infrastructure\BaseController;


class TagController extends BaseController
{
    public function getTags()
    {
        return Tag::all();
    }

    public function postTag()
    {
        $tag = $this->request->all();
        return Tag::create($tag);
    }

    public function deleteTag($tag)
    {
        return (string)Tag::findOrFail($tag)->delete();
    }

    public function editTag($tag)
    {
        $data = $this->request->all();
        return (string)Tag::findOrFail($tag)->update($data);
    }
}
