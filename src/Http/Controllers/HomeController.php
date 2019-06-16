<?php

namespace Niyam\ACL\Http\Controllers;

use Illuminate\Http\Request;
use Niyam\Infrastructure\BaseController;
use Niyam\ACL\Service\ACLService;

use Illuminate\Support\Facades\URL;

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
        // return 89797;
        return $service->giveUsersOfPosition(3);
        // return $service->givePositionOfUser(1);
    }
}
