<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $roles = [
            'Super Admin',
            'Admin Dapur',
            'Akuntan',
            'Gudang',
            'Pengadaan'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Create Default Tenant Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Dapur Default',
                'password' => Hash::make('password'),
            ]
        );

        $admin->assignRole('Admin Dapur');

        // Create Default Chart of Accounts (COA)
        $accounts = [
            ['code' => '1101', 'name' => 'Kas di Tangan', 'type' => 'asset', 'normal_balance' => 'debit'],
            ['code' => '1102', 'name' => 'Bank', 'type' => 'asset', 'normal_balance' => 'debit'],
            ['code' => '1201', 'name' => 'Persediaan Stok Bahan', 'type' => 'asset', 'normal_balance' => 'debit'],
            ['code' => '2101', 'name' => 'Hutang Dagang', 'type' => 'liability', 'normal_balance' => 'credit'],
            ['code' => '3101', 'name' => 'Modal Dapur', 'type' => 'equity', 'normal_balance' => 'credit'],
            ['code' => '5101', 'name' => 'Beban Pokok Penjualan (HPP)', 'type' => 'expense', 'normal_balance' => 'debit'],
            ['code' => '5102', 'name' => 'Beban Operasional', 'type' => 'expense', 'normal_balance' => 'debit'],
            ['code' => '5103', 'name' => 'Beban Gaji & Upah', 'type' => 'expense', 'normal_balance' => 'debit'],
        ];

        foreach ($accounts as $acc) {
            \App\Models\Tenant\Account::updateOrCreate(['code' => $acc['code']], $acc);
        }
    }
}
