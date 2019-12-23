<?php

namespace Niyam\ACL\Http\Controllers;

use Niyam\ACL\Model\PositionTag;
use Niyam\ACL\Infrastructure\BaseController;


class PositionTagController extends BaseController
{
    public function index()
    {
        return PositionTag::all();
    }
}