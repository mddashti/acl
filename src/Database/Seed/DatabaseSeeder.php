<?php
namespace Niyam\ACL\Database\Seed;

use Illuminate\Database\Seeder;

class NiyamACLSeeder extends Seeder
{
    public function run()
    {
		$this->call([
		  UsersTableSeeder::class,
		  DepartmentsTableSeeder::class,
		  DefaultsTableSeeder::class,
		]);
	}
	// app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
}
