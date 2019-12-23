<?php
namespace Niyam\ACL\Database\Seed;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['id'=>1,'name'=>'acl_admin','title'=>'ادمین ACL','guard_name'=>'api','parent_id'=>0,'type'=>0,'created_at'=>now(), 'updated_at'=>now()],
            ['id'=>2,'name'=>'rahbar_gis','title'=>'کاربر GIS','guard_name'=>'api','parent_id'=>0,'type'=>0,'created_at'=>now(), 'updated_at'=>now()],
            ['id'=>3,'name'=>'rahbar_fehresti','title'=>'کاربر امور فهرستی','guard_name'=>'api','parent_id'=>0,'type'=>0,'created_at'=>now(), 'updated_at'=>now()],
            ['id'=>4,'name'=>'rahbar_budget','title'=>'کاربر بودجه','guard_name'=>'api','parent_id'=>0,'type'=>0,'created_at'=>now(), 'updated_at'=>now()],
            ['id'=>5,'name'=>'rahbar_omrani','title'=>'کاربر درخواست های عملیات عمرانی','guard_name'=>'api','parent_id'=>0,'type'=>0,'created_at'=>now(), 'updated_at'=>now()],
            ['id'=>6,'name'=>'rahbar_peymankaran','title'=>'کاربر پیمانکاران','guard_name'=>'api','parent_id'=>0,'type'=>0,'created_at'=>now(), 'updated_at'=>now()],
            ['id'=>7,'name'=>'rahbar_gharardad','title'=>'کاربر قراردادها','guard_name'=>'api','parent_id'=>0,'type'=>0,'created_at'=>now(), 'updated_at'=>now()],
            ['id'=>8,'name'=>'expert_vendor','title'=>'کارشناس وندور','guard_name'=>'api','parent_id'=>0,'type'=>0,'created_at'=>now(), 'updated_at'=>now()],
        ]);
        DB::select("SELECT setval('roles_id_seq', 8, true)");


        DB::table('tags')->insert([
            ['id'=>1,'name'=>'جانشین','type'=>1,'created_at'=>now(), 'updated_at'=>now()]
        ]);
        DB::select("SELECT setval('tags_id_seq', 1, true)");

        DB::table('model_has_roles')->insert([
            ['role_id'=>1,'model_type'=>'Niyam\ACL\Model\User','model_id'=>1],
            ['role_id'=>2,'model_type'=>'Niyam\ACL\Model\User','model_id'=>1],
            ['role_id'=>3,'model_type'=>'Niyam\ACL\Model\User','model_id'=>1],
            ['role_id'=>4,'model_type'=>'Niyam\ACL\Model\User','model_id'=>1],
            ['role_id'=>5,'model_type'=>'Niyam\ACL\Model\User','model_id'=>1],
            ['role_id'=>6,'model_type'=>'Niyam\ACL\Model\User','model_id'=>1],
            ['role_id'=>7,'model_type'=>'Niyam\ACL\Model\User','model_id'=>1],
            ['role_id'=>8,'model_type'=>'Niyam\ACL\Model\User','model_id'=>1],
            ['role_id'=>1,'model_type'=>'Niyam\ACL\Model\User','model_id'=>12],
            ['role_id'=>2,'model_type'=>'Niyam\ACL\Model\User','model_id'=>12],
            ['role_id'=>3,'model_type'=>'Niyam\ACL\Model\User','model_id'=>12],
            ['role_id'=>4,'model_type'=>'Niyam\ACL\Model\User','model_id'=>12],
            ['role_id'=>5,'model_type'=>'Niyam\ACL\Model\User','model_id'=>12],
            ['role_id'=>6,'model_type'=>'Niyam\ACL\Model\User','model_id'=>12],
            ['role_id'=>7,'model_type'=>'Niyam\ACL\Model\User','model_id'=>12],
            ['role_id'=>8,'model_type'=>'Niyam\ACL\Model\User','model_id'=>12],
            ['role_id'=>1,'model_type'=>'Niyam\ACL\Model\User','model_id'=>3],
        ]);
    }
}