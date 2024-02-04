<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
            'analytics',
            'pending-web-pages',
            'pending-blogs',
            'web-pages',
            'blogs',
            'blog-categories',
            'keywords-banks',
            'create-keyword-bank',
            'create-web-pages',
            'show-publish-date',
            'show-slug',
            'show-keywords',
            'show-category',
            'show-seo-title',
            'show-description',
            'show-title',
            'show-tags',
            'submit-web-pages',
            'submit-blogs',
            'delete-blogs',
            'create-keyword-bank',
            'show-tags',
            'delete-tags',
            'edit-tags',
            'keyword-actions',
            'delete-web-page',
            'show-comments',
            'reply-comments',
            'edit-comments',
            'delete-comments',
            'add-users',
            'edit-users',
            'delete-users',
            'approve-comments',
            'delete-keywords',
            'edit-keywords',
            'publish-web-pages',
            'create-reservation',
            'update-reservation',
            'delete-reservation',
            'change-reservation-status',
            'analytics-reservation',
            'show-reservation',
            'create-coupon',
            'create-coupon',
            'update-coupon',
            'create-corporate',
            'delete-corporate',
            'update-corporate',
            'update-driver',
            'delete-driver',
            'create-driver',
            'update-city',
            'update-city',
            'delete-city',
            'delete-country',
            'create-country',
            'create-country',
            'delete-states',
            'create-states',
            'create-states',
        ];

        /** delete permission */
        foreach ($permission as $key => $value) {
            Permission::updateOrCreate([
                'name' =>$value,
            ]);
        };
        
        $seo_admin_permissions = [
            'analytics',
            'web-pages',
            'blogs',
            'blog-categories',
            'keywords-banks',
            'create-web-pages',
            'show-publish-date',
            'show-slug',
            'show-keywords',
            'show-category',
            'show-seo-title',
            'show-description',
            'show-title',
            'submit-web-pages',
            'submit-blogs',
            'create-keyword-bank',
            'show-tags',
            'keyword-actions',
            'delete-web-page',
            'add-users',
            'edit-users',
            'delete-users',
            'show-comments',
            'reply-comments',
            'approve-comments',
            'delete-comments',
            'pending-web-pages',
            'pending-blogs',
            'delete-tags',
            'edit-tags',
            'delete-keywords',
            'edit-keywords',
            'publish-web-pages',
            'delete-blogs'
            
        ];
        $blogger_permissions = [
            'blogs',
            'keywords-banks',
            'analytics'
        ];

        $Seo_specialist_permissions = [
            'analytics',
            'web-pages',
            'blogs',
            'blog-categories',
            'keywords-banks',
            'create-keyword-bank',
            'create-web-pages',
            'show-publish-date',
            'show-slug',
            'show-keywords',
            'show-category',
            'show-seo-title',
            'show-description',
            'show-title',
            'show-tags',
            'submit-web-pages',
            'submit-blogs',
            'create-keyword-bank',
            'show-tags',
            'keyword-actions',
            'delete-web-page',
            'show-comments',
            'reply-comments',
            'edit-comments',
            'delete-comments',
            'delete-tags',
            'edit-tags',
            'delete-keywords',
            'edit-keywords',
            
        ];

        $driver_permissions = [
            'show-reservation'
        ];



        $roles = [
            'Super-admin',
            'Seo-admin',
            'Seo-specialist',
            'Blogger',
            'Driver',
            'Dispatcher',
            'Super Dispatcher',
            'Customer'
        ];
        foreach ($roles as $key => $value) {
            Role::updateOrCreate([
                 'name'=>$value
            ]);
        }
        $blogger = Role::where('name','Blogger')->first();
        $seoAdmin = Role::where('name','Seo-admin')->first();
        $seoSpecialist = Role::where('name','Seo-specialist')->first();
        $superAdmin = Role::where('name','Super-admin')->first();
        $driver = Role::where('name','Driver')->first();
        
        $blogger->syncPermissions($blogger_permissions);
        $seoSpecialist->syncPermissions($Seo_specialist_permissions);
        $seoAdmin->syncPermissions($seo_admin_permissions);
        $superAdmin->syncPermissions($permission);
        $driver->syncPermissions($driver_permissions);

        $user = User::where('email','admin@admin.com')->first();
        $adminEmail = User::where('email','A.almiani@lavishride.com')->first();
        $salesEmail = User::where('email','Sales@lavishride.com')->first();
        $superAdminUser = User::where('email','alsmadi2@lavishride.com')->first();
        $seoSpecialistUser = User::where('email','t.atef@lavishride.com')->first();
        $seoSpecialistUser2 = User::where('email','y.mansour@lavishride.com')->first();

        if($adminEmail)
        {
            $adminEmail->syncRoles($superAdmin);
        }

        if($salesEmail)
        {
            $salesEmail->syncRoles($superAdmin);
        }
        if($seoSpecialistUser)
        {
            $seoSpecialistUser->syncRoles($seoSpecialist);
        }
        if($seoSpecialistUser2)
        {
            $seoSpecialistUser2->syncRoles($seoSpecialist);
        }

        if($superAdminUser)
        {
            $superAdminUser->syncRoles($superAdmin);
        }

        if(!$user)
        {
            $user = User::create([
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email'=> 'admin@admin.com',
                'password'=>bcrypt(123456),
                'phone'=>config('general.support_phone')
            ]);
        }
        else
        {
            $user->update([
                'password'=>bcrypt(123456)
            ]);
        }


        $user->syncRoles($superAdmin);

    }

    
}
