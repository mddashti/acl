<?php

namespace Niyam\ACL\Infrastructure;

use Illuminate\Http\Request;

class BaseController extends BaseEntity
{
    protected $user;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user = $request->auth;
    }
}
