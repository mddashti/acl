<?php

namespace Niyam\ACL\Http\Controllers;

use Illuminate\Http\Request;
use Niyam\ACL\Service\ACLService;
use Niyam\ACL\Infrastructure\BaseController;

use Auth;


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

        return;
        $a = Auth::user()->hasRole();

        echo '<pre>';
        print_r($a);



        return;
        // echo url()->current();
        return;
        echo '<form action="api/login" method="post"> ';
        echo '<input name="username">';
        echo '<input name="password">';
        echo '<input type="submit">';
        echo '</form>';

        return;
        // $cookie = setcookie('ITSNAME3','ITSVAL',time()+60*60*24*365, '/'); 
        // $cookie = \Cookie::queue(\Cookie::forever('ITSNAME3', 'safsdgsd'));
        // TOKEN_LIFE_TIME
        $cookie = setcookie('ITSNAME3', 'sam t filter', 1750000000, '/');
        echo "<pre>";
        print_r($cookie);

        echo "<hr>";
        // $cookie = \Cookie::get('ITSNAME3');
        $cookie = \Cookie::get('ITSNAME3');
        print_r($cookie);

        return;
        // return $service->giveUsersOfPosition(3);
    }
}
