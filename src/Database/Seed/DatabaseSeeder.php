<?php
namespace Niyam\ACL\Database\Seed;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NiyamACLSeeder extends Seeder
{
    public function run()
    {
    	// USERS
        DB::table('users')->insert(['username'=>'david','name'=>'David','firstname'=>'','lastname'=>'','email'=>'mddashti@gmail.com','password'=>password_hash('123456', PASSWORD_BCRYPT),'avatar'=>'','signature'=>'']);
        DB::table('users')->insert(['username'=>'nadi','name'=>'Nadi','firstname'=>'','lastname'=>'','email'=>'hamide.nadi@gmail.com','password'=>password_hash('123456', PASSWORD_BCRYPT),'avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'403402','name'=>'','firstname'=>'سید وهاب','lastname'=>'طبائی زاده','email'=>'vahab@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'403039','name'=>'','firstname'=>'محمد علی','lastname'=>'سرکوبی','email'=>'sarkobi@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'403219','name'=>'','firstname'=>'عوض','lastname'=>'شمشیری','email'=>'avaz@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'402999','name'=>'','firstname'=>'محمد','lastname'=>'ساسانی','email'=>'sasani@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'405739','name'=>'','firstname'=>'محمد','lastname'=>'راه خدائی','email'=>'rahkhodaee@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4311956','name'=>'','firstname'=>'وحید','lastname'=>'زارعی','email'=>'zaree@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4300690','name'=>'','firstname'=>'رضا','lastname'=>'کاویانی','email'=>'kaviani@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'402792','name'=>'','firstname'=>'شَهرام','lastname'=>'زارع','email'=>'zare@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4310433','name'=>'','firstname'=>'علی اصغر','lastname'=>'عدالت','email'=>'edalat@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'405861','name'=>'','firstname'=>'رضا','lastname'=>'محمدی','email'=>'mohamadi@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4312050','name'=>'','firstname'=>'سید رامتین','lastname'=>'منادی','email'=>'monadi@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'405261','name'=>'','firstname'=>'علی محمد','lastname'=>'بهزادفر','email'=>'behzadfar@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4312073','name'=>'','firstname'=>'عطا اله','lastname'=>'رحیم پور','email'=>'rahimpor@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'405650','name'=>'','firstname'=> 'یاسر','lastname'=>'باغبانیان','email'=>'baghbanian@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'405203','name'=>'','firstname'=>'سجاد','lastname'=>'سليماني','email'=>'soleymani@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'405700','name'=>'','firstname'=>'محمد باقر','lastname'=>'تراوش','email'=>'taravash@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4300256','name'=>'','firstname'=>'زهرا','lastname'=>'شعائري','email'=>'shoari@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'admin@admin.com','name'=>'','firstname'=>'فاطمه','lastname'=>'ايزدي','email'=>'ezadi@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'403924','name'=>'','firstname'=>'مريم','lastname'=>'قلاتيان','email'=>'ghalatian@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'rahbar','name'=>'','firstname'=>'راهبر','lastname'=>'مشاور','email'=>'rahbar@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4311629','name'=>'','firstname'=>'غلامرضا','lastname'=>'علیزاده','email'=>'alizadeh@shira.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4310444','name'=>'','firstname'=>'محمد علی','lastname'=>'قزل بیگلو','email'=>'ghezelbigloo@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'402112','name'=>'','firstname'=>'سید مهدی','lastname'=>'حسینی خواه','email'=>'hosseinikhah@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'406880','name'=>'','firstname'=>'اسدالله','lastname'=>'اسکندری','email'=>'eskandari@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'405719','name'=>'','firstname'=>'کامیار','lastname'=>'خزاعی','email'=>'khozaei@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4311519','name'=>'','firstname'=>'ناصر','lastname'=>'شیدایی دیندارلو','email'=>'naser@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4310956','name'=>'','firstname'=>'امیر ','lastname'=>'گرجی کهواده', 'email'=>'gorji.amir@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4311323','name'=>'','firstname'=>'مصطفی','lastname'=>'ابوطالبی','email'=>'aboutalebi@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'1234','name'=>'','firstname'=>'test','lastname'=>'test','email'=>'test@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4312257','name'=>'','firstname'=>'مجید رضا','lastname'=>'سراجی','email'=>'seraji@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'405923','name'=>'','firstname'=>'حمید','lastname'=>'بیرجندی','email'=>'birjandi@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4300139','name'=>'','firstname'=>'فریده','lastname'=>'عبدی','email'=>'abdi@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4300745','name'=>'','firstname'=>'پروانه','lastname'=>'نصیری','email'=>'nasiri@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'402141','name'=>'','firstname'=>'شاهرخ','lastname'=>'حکمتی','email'=>'hekmati@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4302009','name'=>'','firstname'=>'فرزاد','lastname'=>'حیدر زاده','email'=>'heydarzadeh@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'404557','name'=>'','firstname'=>'کریم','lastname'=>'نصیری','email'=>'karimnasiri@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4311301','name'=>'','firstname'=>'محمد هادی','lastname'=>'باقری','email'=>'bagheri@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
		DB::table('users')->insert(['username'=>'4311083','name'=>'','firstname'=>'سحر','lastname'=>'نعمتی','email'=>'s.nemati@shiraz.ir','password'=>'','avatar'=>'','signature'=>'']);
    	// DEPARTMENT
		DB::table('departments')->insert(['id'=>2, 'parent_id'=>0, 'name'=>'شهردار']);
		DB::table('departments')->insert(['id'=>3, 'parent_id'=>2, 'name'=>'اداره کل بازرسی']);
		DB::table('departments')->insert(['id'=>5, 'parent_id'=>2, 'name'=>'اداره کل ارتباطات و امور بین الملل']);
		DB::table('departments')->insert(['id'=>6, 'parent_id'=>2, 'name'=>'اداره کل حسابرسی']);
		DB::table('departments')->insert(['id'=>8, 'parent_id'=>2, 'name'=>'اداره کل حوزه شهردار و امور شورای اسلامی شهر']);
		DB::table('departments')->insert(['id'=>9, 'parent_id'=>2, 'name'=>'مدیریت گزینش']);
		DB::table('departments')->insert(['id'=>10, 'parent_id'=>2, 'name'=>'اداره کل حراست']);
		DB::table('departments')->insert(['id'=>11, 'parent_id'=>2, 'name'=>'اداره کل امور حقوقی']);
		DB::table('departments')->insert(['id'=>12, 'parent_id'=>2, 'name'=>'معاونت مالی و اقتصادی']);
		DB::table('departments')->insert(['id'=>13, 'parent_id'=>12, 'name'=>'اداره کل امور مالی']);
		DB::table('departments')->insert(['id'=>14, 'parent_id'=>12, 'name'=>'اداره کل تشخیص و وصول درآمد']);
		DB::table('departments')->insert(['id'=>15, 'parent_id'=>12, 'name'=>'اداره کل املاک مستغلات']);
		DB::table('departments')->insert(['id'=>16, 'parent_id'=>2, 'name'=>'معاونت شهرسازی و معماری']);
		DB::table('departments')->insert(['id'=>17, 'parent_id'=>16, 'name'=>'اداه کل امور شهرسازی']);
		DB::table('departments')->insert(['id'=>18, 'parent_id'=>16, 'name'=>'اداره کل کنترل و نظارت ساختمان']);
		DB::table('departments')->insert(['id'=>19, 'parent_id'=>2, 'name'=>'معاونت فنی و عمرانی']);
		DB::table('departments')->insert(['id'=>20, 'parent_id'=>19, 'name'=>'اداره کل فنی']);
		DB::table('departments')->insert(['id'=>21, 'parent_id'=>19, 'name'=>'اداره کل عمرانی']);
		DB::table('departments')->insert(['id'=>22, 'parent_id'=>19, 'name'=>'مدیریت تعمیر و نگهداری ابنیه و تاسیسات']);
		DB::table('departments')->insert(['id'=>23, 'parent_id'=>2, 'name'=>'معاونت برنامه ریزی و توسعه سرمایه انسانی']);
		DB::table('departments')->insert(['id'=>24, 'parent_id'=>23, 'name'=>'دفتر برنامه و بودجه']);
		DB::table('departments')->insert(['id'=>25, 'parent_id'=>23, 'name'=>'دفتر نوسازی و تحول اداری']);
		DB::table('departments')->insert(['id'=>26, 'parent_id'=>23, 'name'=>'اداره کل سرمایه انسانی']);
		DB::table('departments')->insert(['id'=>27, 'parent_id'=>2, 'name'=>'معاونت خدمات شهری']);
		DB::table('departments')->insert(['id'=>28, 'parent_id'=>27, 'name'=>'اداره کل نظارت بر خدمات شهری']);
		DB::table('departments')->insert(['id'=>29, 'parent_id'=>27, 'name'=>'اداره کل پیشگیری و رفع تخلفات شهری']);
		DB::table('departments')->insert(['id'=>30, 'parent_id'=>2, 'name'=>'معاونت حمل و نقل و ترافیک']);
		DB::table('departments')->insert(['id'=>31, 'parent_id'=>30, 'name'=>'اداره کل مهندسی و ایمنی و ترافیک']);
		DB::table('departments')->insert(['id'=>32, 'parent_id'=>30, 'name'=>'دفتر مطالعات و بررسی های ترافیک شهری']);
		DB::table('departments')->insert(['id'=>33, 'parent_id'=>30, 'name'=>'مدیریت کنترل ترافیک']);
		DB::table('departments')->insert(['id'=>34, 'parent_id'=>2, 'name'=>'مناطق']);
		DB::table('departments')->insert(['id'=>35, 'parent_id'=>34, 'name'=>'منطقه 1']);
		DB::table('departments')->insert(['id'=>36, 'parent_id'=>34, 'name'=>'منطقه 2']);
		DB::table('departments')->insert(['id'=>37, 'parent_id'=>34, 'name'=>'منطقه 3']);
		DB::table('departments')->insert(['id'=>38, 'parent_id'=>34, 'name'=>'منطقه 4']);
		DB::table('departments')->insert(['id'=>39, 'parent_id'=>34, 'name'=>'منطقه 5']);
		DB::table('departments')->insert(['id'=>40, 'parent_id'=>34, 'name'=>'منطقه 6']);
		DB::table('departments')->insert(['id'=>41, 'parent_id'=>34, 'name'=>'منطقه 7']);
		DB::table('departments')->insert(['id'=>42, 'parent_id'=>34, 'name'=>'منطقه 8']);
		DB::table('departments')->insert(['id'=>43, 'parent_id'=>34, 'name'=>'منطقه 9']);
		DB::table('departments')->insert(['id'=>44, 'parent_id'=>34, 'name'=>'منطقه 10']);
		DB::table('departments')->insert(['id'=>45, 'parent_id'=>34, 'name'=>'منطقه 11']);
		DB::table('departments')->insert(['id'=>46, 'parent_id'=>30, 'name'=>'سازمان مدیریت حمل و نقل مسافر']);
		DB::table('departments')->insert(['id'=>47, 'parent_id'=>30, 'name'=>'سازمان مدیریت حمل و نقل بار']);
		DB::table('departments')->insert(['id'=>48, 'parent_id'=>30, 'name'=>'سازمان حمل و نقل ریلی']);
		DB::table('departments')->insert(['id'=>49, 'parent_id'=>27, 'name'=>'سازمان آتش نشانی و خدمات ایمنی']);
		DB::table('departments')->insert(['id'=>50, 'parent_id'=>27, 'name'=>'سازمان آرامستان ها']);
		DB::table('departments')->insert(['id'=>51, 'parent_id'=>27, 'name'=>'سازمان ساماندهی مشاغل شهری و فراورده های کشاورزی']);
		DB::table('departments')->insert(['id'=>52, 'parent_id'=>27, 'name'=>'سازمان مدیریت پسماند']);
		DB::table('departments')->insert(['id'=>53, 'parent_id'=>27, 'name'=>'سازمان سیما، منظر و فضای سبز شهری']);
		DB::table('departments')->insert(['id'=>54, 'parent_id'=>23, 'name'=>'سازمان فناوری اطلاعات و ارتباطات']);
		DB::table('departments')->insert(['id'=>55, 'parent_id'=>19, 'name'=>'سازمان عمران و بازآفرینی فضاهای شهری']);
		DB::table('departments')->insert(['id'=>56, 'parent_id'=>12, 'name'=>'سازمان سرمایه گذاری و مشارکت های مردمی']);
		DB::table('departments')->insert(['id'=>57, 'parent_id'=>2, 'name'=>'سازمان فرهنگی، اجتماعی و ورزشی']);
		DB::table('departments')->insert(['id'=>58, 'parent_id'=>30, 'name'=>'معاونت حمل و نقل و ترافیک']);
		DB::table('departments')->insert(['id'=>59, 'parent_id'=>23, 'name'=>'معاونت برنامه ریزی و توسعه سرمایه انسانی']);
		DB::table('departments')->insert(['id'=>60, 'parent_id'=>27, 'name'=>'معاونت خدمات شهری']);
		DB::table('departments')->insert(['id'=>61, 'parent_id'=>19, 'name'=>'معاونت فنی و عمرانی']);
		DB::table('departments')->insert(['id'=>62, 'parent_id'=>27, 'name'=>'سازمان میادین']);
		DB::table('departments')->insert(['id'=>63, 'parent_id'=>30, 'name'=>'معاونت حمل و نقل و ترافیک']);
		DB::table('departments')->insert(['id'=>64, 'parent_id'=>23, 'name'=>'معاونت برنامه ریزی و توسعه سرمایه انسانی']);
		DB::table('departments')->insert(['id'=>65, 'parent_id'=>27, 'name'=>'معاونت خدمات شهری']);
		DB::table('departments')->insert(['id'=>66, 'parent_id'=>19, 'name'=>'اداره کل فنی']);
		DB::table('departments')->insert(['id'=>67, 'parent_id'=>27, 'name'=>'سازمان میادین']);
		DB::table('departments')->insert(['id'=>68, 'parent_id'=>30, 'name'=>'معاونت حمل و نقل و ترافیک']);
		DB::table('departments')->insert(['id'=>69, 'parent_id'=>23, 'name'=>'معاونت برنامه ریزی و توسعه سرمایه انسانی']);
		DB::table('departments')->insert(['id'=>70, 'parent_id'=>27, 'name'=>'معاونت خدمات شهری']);
		DB::table('departments')->insert(['id'=>71, 'parent_id'=>19, 'name'=>'اداره کل فنی']);
		DB::table('departments')->insert(['id'=>72, 'parent_id'=>27, 'name'=>'سازمان میادین']);
        //
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
