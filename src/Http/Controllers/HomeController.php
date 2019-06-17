<?php

namespace Niyam\ACL\Http\Controllers;

use Illuminate\Http\Request;
use Niyam\ACL\Service\ACLService;
use Niyam\ACL\Infrastructure\BaseController;

class HomeController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        return view('login');
    }

    public function home()
    {
        $userName = $this->user->name;
        return view('home')->with(['userName' => $userName]);
    }

    public function test(ACLService $service)
    {
        return $service->giveUsersOfPosition(3);
    }
}
