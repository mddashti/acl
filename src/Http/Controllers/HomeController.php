<?php

namespace Niyam\ACL\Http\Controllers;

use Illuminate\Http\Request;
use Niyam\ACL\Service\ACLService;
use Niyam\ACL\Model\User;
use Niyam\ACL\Model\Documentation;
use Niyam\ACL\Infrastructure\BaseController;
use Illuminate\Support\Facades\DB;
use Niyam\ACL\Model\SSOUser;
use Mail;


use Auth;
use Niyam\ACL\SMSProviders\Magfa\SMSService;

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

    public function documentation(ACLService $service)
    {
        $isAdmin = $service->hasRole('acl_admin')['isSuccess'];
        // $videos = Documentation::all();
        return view('documentation', [
            'user' => $this->user,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function documentationShow(ACLService $service, $id)
    {
        $video = Documentation::where('id', $id)->first();
        $isAdmin = $service->hasRole('acl_admin')['isSuccess'];
        return view('documentation-show', [
            'video' => $video,
            'user' => $this->user,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function home(ACLService $service)
    {
        $isAdmin = $service->hasRole('acl_admin')['isSuccess'];
        // $rolesId = $service->roles()->pluck('id');
        $roles = $service->findUserRoles();
        return view('home')->with([
            'user'      => $this->user,
            'isAdmin'   => $isAdmin,
            'roles'     => $roles
        ]);
    }

    public function password()
    {
        $user = $this->user;
        return view('password', ['user' => $user]);
    }

    public function passwordChange(Request $request)
    {
        // $user = User::where('id', $this->user->id)->first();
        $validateRules = [
            'password' => 'min:6|required|confirmed|regex:/[a-zA-Z]/|regex:/[0-9]/'
        ];

        $this->request->validate($validateRules);

        $update = User::where('id', $this->user->id)->update([
            'password_change' => 0,
            'password' => password_hash($request->password, PASSWORD_BCRYPT)
        ]);
        if ($update) {
            // setcookie('access_token', '', -1, '/');
            return redirect('/auth/logout');
        }

        return;


        echo "<pre>";
        print_r($user);

        if (!$user->email) $validateRules['email']   = 'email|required';
        if (!$user->mobile) $validateRules['mobile'] = 'required';


        // echo "<pre>";
        // print_r($validateRules);

        $a = $this->request->validate($validateRules);
        print_r($a);

        return;

        $user->update([
            'email' => ''
        ]);

        // if($user->mobile){

        // }

        return;





        $update = User::where('id', $this->user->id)->update([
            'password_change' => 0,
            'password' => password_hash($request->password, PASSWORD_BCRYPT)
        ]);
        if ($update) {
            // setcookie('access_token', '', -1, '/');
            return redirect('/auth/logout');
        }
    }

    public function vendorExpert(ACLService $servive)
    {
        // 8 vendor expert id
        return $servive->getUsersofRole(8);
    }

    public function test(ACLService $service, SMSService $smsService)
    {

        $a = $service->createUser([
            'firstname' => 'ttt@ttt.ttt12',
            'email' => 'ttt@ttt.ttt1234',
            'mobile' => '09906113281234',
            'username' => 'david1234',
            'password' => '123456',
        ]);

        //$this->basic_email();
        //$this->basic_email('project@shiraz.ir');
        //return $this->basic_email('abediyeh.hossein@gmail.com');
        //return $smsService->getCreditSample();
        return $smsService->enqueueSample(['09138562838','09176791488','09132585269','09177006775']);

        $a = $service->getUsersOfRole(2);

        return $a;


        // $a = $service->giveUsersOfPositions([1,5, 3]);
        // $a = $service->giveUsersOfPositions([3,1,2,3]);
        $a = $service->giveUsersOfPositions(1);
        return $a;



        $a = $service->getUsersOfRole(2);
        return $a;



        $a = $service->findUserRoles();



        // $a = $service->getUserOfPositions(8);
        // $a = $service->getUsersOfRole(3);

        if (in_array('acl_admin', $a['data'])) {
            echo '<li>M1</li>';
        }
        echo '<li>M2</li>';
        echo '<li>M3</li>';
        echo '<li>M4</li>';


        return;


        return;
        $a = new SSOUser($service);
        return $a->roles()->pluck('id');

        return;

        return $service->giveUserOfPosition(1);

        return;
        //return ['pId' => $service->hasPermission(1), 'pName' => $service->hasPermission("p")];
        return ['p' => $service->findUserPermissions(), 'u' => $service->findCurrentUser()];

        // return ACLService::getRoleByLevel(3, 1, 'parent');
        // return ACLService::getUserOfPositions(4);
        // return ACLService::getPositionOfUsers(3);
        // return ACLService::getPositionByTag(4, 2); // also work for getSubOfRole()
        // return ACLService::getUserByXLD(2, 1); // $roleX, $roleY, $level, $tag, $direction
    }

    public function basic_email($to = 'mddashti@gmail.com')
    {
        $data = array('name' => "PMIS Shiraz");

        Mail::send(['html' => 'mail'], $data, function ($message) use ($to) {
            $message->to($to, 'Test User')->subject('PMIS Test Mail');
            $message->from('pmis@shiraz.ir', 'PMIS Shiraz');
        });
        return "Test Email Sent. Check your inbox.";
    }
}
