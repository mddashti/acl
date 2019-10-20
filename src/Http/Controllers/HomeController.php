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
        
        //return ['pId' => $service->hasPermission(1), 'pName' => $service->hasPermission("p")];
        return ['p' => $service->findUserPermissions(), 'u' => $service->findCurrentUser()];
        // return ACLService::getRoleByLevel(3, 1, 'parent');
        // return ACLService::getUserOfPositions(4);
        // return ACLService::getPositionOfUsers(3);
        // return ACLService::getPositionByTag(4, 2); // also work for getSubOfRole()
        // return ACLService::getUserByXLD(2, 1); // $roleX, $roleY, $level, $tag, $direction
        return;
        echo '<form action="/users" method="post" enctype="multipart/form-data">';
        echo '<input type="text" name="name" value="name">';
        echo '<input type="text" name="username" value="username">';
        echo '<input type="text" name="mobile" value="mobile">';
        echo '<input type="text" name="email" value="email">';
        echo '<input type="text" name="password" value="password">';
        echo '<input type="file" name="avatar">';
        echo '<input type="file" name="signature">';
        echo '<input type="submit">';
        echo '</form>';

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
