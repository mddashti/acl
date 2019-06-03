<?php
namespace Niyam\ACL\Database\Seed;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NiyamACLSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert(['username' => 'david','name' => 'David', 'email' => 'mddashti@gmail.com','password' => password_hash('123456', PASSWORD_BCRYPT), 'avatar' => '', 'signature' => '']);
        DB::table('users')->insert(['username' => 'milad','name' => 'Milad', 'email' => 'miladmodaresi525@gmail.com','password' => password_hash('123456', PASSWORD_BCRYPT), 'avatar' => '', 'signature' => '']);
        DB::table('users')->insert(['username' => 'nadi','name' => 'Nadi', 'email' => 'hamide.nadi@gmail.com','password' => password_hash('123456', PASSWORD_BCRYPT), 'avatar' => '', 'signature' => '']);
        DB::table('users')->insert(['username' => 'a','name' => 'a', 'email' => 'ag1363@gmail.com','password' => password_hash('123456', PASSWORD_BCRYPT), 'avatar' => '', 'signature' => '']);
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
