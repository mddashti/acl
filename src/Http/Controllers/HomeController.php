<?php

namespace Niyam\ACL\Http\Controllers;

use Illuminate\Http\Request;
use Niyam\Infrastructure\BaseController;
use Niyam\ACL\Helper\ACLService;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
class HomeController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function test()
    {
        $token = $this->request->cookie('access_token');
     
        echo "<pre>";
        $p = (new ACLService($token))->permissions();
        echo($p);
        echo "<hr>";
        $p = (new ACLService($token))->hasPermission(11);
        echo($p);
    }

    public function index(){
        return view('home');
    }

    public function main()
    {





        $a = $this->request->cookie('access_token');
        // dd($a);
            $credentials = JWT::decode($a, env('JWT_SECRET'), ['HS256']);
        dd(json_decode($credentials->sub));

        return;
        $userName = $this->user->name;
        return view('main')->with(['userName' => $userName]);
    }

}
