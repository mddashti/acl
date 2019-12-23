<?php

namespace Niyam\ACL\Http\Controllers;

use Niyam\ACL\Model\Sms;
use Niyam\ACL\Model\Email;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Spatie\Permission\Models\Role;
use Niyam\ACL\Infrastructure\BaseController;

class NotificationController extends BaseController
{
    public function emails()
    {
        return Email::all();
    }

    public function sms()
    {
        return Sms::all();
    }
}
