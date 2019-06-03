<?php

namespace Niyam\ACL\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;


class Controller extends BaseController
{
    protected $request;

    protected $user;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user = $request->auth;
    }
}
