<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            $password = Hash::make('123456');
            $now = now()->toDateTimeString();

            $users = [
                [
                    'id' => 1,
                    'surname' => 'Mr',
                    'first_name' => 'Admin',
                    'last_name' => null,
                    'username' => 'admin',
                    'email' => 'admin@example.com',
                    'business_id' => 1,
                ],
                [
                    'id' => 2,
                    'surname' => 'Mr',
                    'first_name' => 'Demo',
                    'last_name' => 'Cashier',
                    'username' => 'cashier',
                    'email' => 'cashier@example.com',
                    'business_id' => 1,
                ],
                [
                    'id' => 3,
                    'surname' => 'Mr.',
                    'first_name' => 'Demo',
                    'last_name' => 'Admin',
                    'username' => 'demo-admin',
                    'email' => 'demoadmin@example.com',
                    'business_id' => 1,
                ],
                [
                    'id' => 4,
                    'surname' => 'Mr',
                    'first_name' => 'Demo',
                    'last_name' => 'Admin',
                    'username' => 'admin-pharmacy',
                    'email' => 'admin-pharma@example.com',
                    'business_id' => 2,
                ],
                [
                    'id' => 5,
                    'surname' => 'Mr',
                    'first_name' => 'Demo',
                    'last_name' => 'Admin',
                    'username' => 'admin-electronics',
                    'email' => 'admin-electronics@example.com',
                    'business_id' => 3,
                ],
                [
                    'id' => 6,
                    'surname' => 'Mr',
                    'first_name' => 'Demo',
                    'last_name' => 'Admin',
                    'username' => 'admin-services',
                    'email' => 'admin-services@example.com',
                    'business_id' => 4,
                ],
                [
                    'id' => 7,
                    'surname' => 'Mr',
                    'first_name' => 'Demo',
                    'last_name' => 'Admin',
                    'username' => 'admin-restaurant',
                    'email' => 'admin-restaurant@example.com',
                    'business_id' => 5,
                ],
                [
                    'id' => 8,
                    'surname' => 'Mr',
                    'first_name' => 'Kevin',
                    'last_name' => 'Nicols',
                    'username' => 'kevin-nicols',
                    'email' => 'kevin@example.com',
                    'business_id' => 5,
                ],
                [
                    'id' => 9,
                    'surname' => 'Mr.',
                    'first_name' => 'Super',
                    'last_name' => 'Admin',
                    'username' => 'superadmin',
                    'email' => 'superadmin@example.com',
                    'business_id' => 1,
                ],
                [
                    'id' => 10,
                    'surname' => 'Mr.',
                    'first_name' => 'WooCommerce',
                    'last_name' => 'User',
                    'username' => 'woocommerce_user',
                    'email' => 'woo@example.com',
                    'business_id' => 1,
                ],
                [
                    'id' => 11,
                    'surname' => 'Mr',
                    'first_name' => 'Admin Essential',
                    'last_name' => null,
                    'username' => 'admin-essentials',
                    'email' => 'admin_essentials@example.com',
                    'business_id' => 1,
                ],
                [
                    'id' => 12,
                    'surname' => 'Mr.',
                    'first_name' => 'Mike',
                    'last_name' => 'Lee',
                    'username' => 'manufacturer-demo',
                    'email' => 'manufacturer-demo@demo.com',
                    'business_id' => 6,
                ],
            ];

            foreach ($users as $user) {
                User::updateOrCreate(
                    ['id' => $user['id']],
                    [
                        'surname' => $user['surname'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'password' => $password,
                        'language' => 'en',
                        'business_id' => $user['business_id'],
                        'is_cmmsn_agnt' => 0,
                        'cmmsn_percent' => 0,
                        'allow_login' => 1,
                        'status' => 'active',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }

            $this->createBusinessRolesAndPermissions();

            $this->assignBusinessOneRoles();
            $this->assignOtherBusinessRoles();

            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Create required roles and permissions.
     *
     * @return void
     */
    private function createBusinessRolesAndPermissions()
    {
        $businessIds = [1, 2, 3, 4, 5, 6];

        foreach ($businessIds as $businessId) {
            Role::updateOrCreate(
                ['name' => 'Admin#'.$businessId, 'guard_name' => 'web'],
                [
                    'business_id' => $businessId,
                    'is_default' => 1,
                ]
            );

            Role::updateOrCreate(
                ['name' => 'Cashier#'.$businessId, 'guard_name' => 'web'],
                [
                    'business_id' => $businessId,
                    'is_default' => 0,
                ]
            );

            Permission::updateOrCreate(
                ['name' => 'location.'.$businessId, 'guard_name' => 'web'],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $cashierPermissions = [
            'sell.view',
            'sell.create',
            'sell.update',
            'sell.delete',
            'view_cash_register',
            'close_cash_register',
            'print_invoice',
            'dashboard.data',
        ];

        foreach ($cashierPermissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        for ($businessId = 1; $businessId <= 6; $businessId++) {
            $cashierRole = Role::where('name', 'Cashier#'.$businessId)->first();

            if (! empty($cashierRole)) {
                $cashierRole->syncPermissions($cashierPermissions);
            }
        }

        Permission::updateOrCreate(
            ['name' => 'superadmin', 'guard_name' => 'web'],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Assign roles for main business users.
     *
     * @return void
     */
    private function assignBusinessOneRoles()
    {
        $admin1 = User::findOrFail(1);
        $adminEssentials = User::findOrFail(11);
        $superadmin1 = User::findOrFail(9);
        $woocommerce1 = User::findOrFail(10);
        $cashier1 = User::findOrFail(2);
        $demoUser1 = User::findOrFail(3);

        $admin1->syncRoles(['Admin#1']);
        $adminEssentials->syncRoles(['Admin#1']);
        $superadmin1->syncRoles(['Admin#1']);
        $woocommerce1->syncRoles(['Admin#1']);
        $demoUser1->syncRoles(['Admin#1']);
        $cashier1->syncRoles(['Cashier#1']);

        $cashier1->givePermissionTo('location.1');

        $superadmin1->givePermissionTo('superadmin');
    }

    /**
     * Assign roles for other demo business users.
     *
     * @return void
     */
    private function assignOtherBusinessRoles()
    {
        User::findOrFail(4)->syncRoles(['Admin#2']);
        User::findOrFail(5)->syncRoles(['Admin#3']);
        User::findOrFail(6)->syncRoles(['Admin#4']);
        User::findOrFail(7)->syncRoles(['Admin#5']);
        User::findOrFail(12)->syncRoles(['Admin#6']);

        $waiter = User::findOrFail(8);

        $waiterRole = Role::updateOrCreate(
            ['name' => 'Waiter#5', 'guard_name' => 'web'],
            [
                'business_id' => 5,
                'is_default' => 0,
                'is_service_staff' => 1,
            ]
        );

        Permission::updateOrCreate(
            ['name' => 'dashboard.data', 'guard_name' => 'web'],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $waiterRole->syncPermissions(['dashboard.data']);

        $waiter->syncRoles(['Waiter#5']);
        $waiter->givePermissionTo('location.5');
    }
}