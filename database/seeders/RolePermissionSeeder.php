<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Technician;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->createPermissions();
        $this->createRoles();
        $this->createUsers();
    }

    private function createPermissions(): void
    {
        $groups = [
            'dashboard' => ['dashboard.view'],
            'customers' => ['customers.view', 'customers.create', 'customers.edit', 'customers.delete'],
            'customer-units' => ['customer-units.view', 'customer-units.create', 'customer-units.edit', 'customer-units.delete'],
            'technicians' => ['technicians.view', 'technicians.create', 'technicians.edit', 'technicians.delete'],
            'work-orders' => ['work-orders.view', 'work-orders.create', 'work-orders.edit', 'work-orders.delete'],
            'service-reports' => ['service-reports.view', 'service-reports.create', 'service-reports.edit', 'service-reports.delete'],
            'complaints' => ['complaints.view', 'complaints.create', 'complaints.edit', 'complaints.delete'],
            'spareparts' => ['spareparts.view', 'spareparts.create', 'spareparts.edit', 'spareparts.delete'],
            'freon' => ['freon.view', 'freon.create', 'freon.edit', 'freon.delete'],
            'quotations' => ['quotations.view', 'quotations.create', 'quotations.edit', 'quotations.delete'],
            'invoices' => ['invoices.view', 'invoices.create', 'invoices.edit', 'invoices.delete'],
            'payments' => ['payments.view', 'payments.create', 'payments.edit', 'payments.delete'],
            'contracts' => ['contracts.view', 'contracts.create', 'contracts.edit', 'contracts.delete'],
            'roles' => ['roles.view', 'roles.create', 'roles.edit', 'roles.delete'],
            'permissions' => ['permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete'],
        ];

        foreach ($groups as $group => $perms) {
            foreach ($perms as $name) {
                $displayName = ucwords(str_replace(['.', '-'], [' ', ' '], $name));
                Permission::firstOrCreate(
                    ['name' => $name],
                    ['display_name' => $displayName, 'group' => $group]
                );
            }
        }

        $this->command->info('Permissions seeded: ' . Permission::count());
    }

    private function createRoles(): void
    {
        $allPermissionIds = Permission::pluck('id')->toArray();

        $roles = [
            'super_admin' => [
                'display_name' => 'Super Admin',
                'description' => 'Full access to all features',
                'permissions' => $allPermissionIds,
            ],
            'admin_operasional' => [
                'display_name' => 'Admin Operasional',
                'description' => 'Manage customers, work orders, service reports, complaints, quotations, invoices, payments',
                'permissions' => Permission::whereIn('group', [
                    'dashboard', 'customers', 'customer-units', 'technicians',
                    'work-orders', 'service-reports', 'complaints',
                    'quotations', 'invoices', 'payments',
                ])->pluck('id')->toArray(),
            ],
            'teknisi' => [
                'display_name' => 'Teknisi',
                'description' => 'View assigned work orders and update progress',
                'permissions' => Permission::whereIn('name', [
                    'dashboard.view', 'work-orders.view',
                ])->pluck('id')->toArray(),
            ],
            'supervisor' => [
                'display_name' => 'Supervisor',
                'description' => 'View all operational data (read-only)',
                'permissions' => Permission::whereIn('group', [
                    'dashboard', 'customers', 'work-orders', 'service-reports',
                    'complaints', 'quotations', 'invoices', 'payments',
                ])->where('name', 'like', '%.view')->pluck('id')->toArray(),
            ],
            'customer' => [
                'display_name' => 'Customer',
                'description' => 'View own data and work orders',
                'permissions' => Permission::whereIn('name', [
                    'dashboard.view',
                ])->pluck('id')->toArray(),
            ],
        ];

        foreach ($roles as $name => $data) {
            $role = Role::firstOrCreate(
                ['name' => $name],
                [
                    'display_name' => $data['display_name'],
                    'description' => $data['description'],
                ]
            );
            $role->permissions()->sync($data['permissions']);
        }

        $this->command->info('Roles seeded: ' . Role::count());
    }

    private function createUsers(): void
    {
        $users = [
            [
                'name' => 'Admin Utama',
                'email' => 'admin@dqin-ac.com',
                'role' => 'super_admin',
                'technician' => [
                    'nik' => 'TEC-ADMIN',
                    'full_name' => 'Admin Utama',
                    'phone' => '0812-3456-7890',
                    'address' => 'Jakarta',
                    'specialization' => 'Senior Technician',
                ],
            ],
            [
                'name' => 'Admin Operasional',
                'email' => 'operasional@dqin-ac.com',
                'role' => 'admin_operasional',
                'technician' => null,
            ],
            [
                'name' => 'Budi Teknisi',
                'email' => 'teknisi1@dqin-ac.com',
                'role' => 'teknisi',
                'technician' => [
                    'nik' => 'TEC-001',
                    'full_name' => 'Budi Teknisi',
                    'phone' => '0812-3456-7891',
                    'address' => 'Jakarta',
                    'specialization' => 'AC Split, Freon',
                ],
            ],
            [
                'name' => 'Agung Teknisi',
                'email' => 'teknisi2@dqin-ac.com',
                'role' => 'teknisi',
                'technician' => [
                    'nik' => 'TEC-002',
                    'full_name' => 'Agung Teknisi',
                    'phone' => '0812-3456-7892',
                    'address' => 'Jakarta',
                    'specialization' => 'AC Central, Chiller',
                ],
            ],
            [
                'name' => 'Supervisor',
                'email' => 'supervisor@dqin-ac.com',
                'role' => 'supervisor',
                'technician' => null,
            ],
            [
                'name' => 'Customer Demo',
                'email' => 'customer@dqin-ac.com',
                'role' => 'customer',
                'technician' => null,
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'phone' => $data['technician']['phone'] ?? null,
                ]
            );

            $role = Role::where('name', $data['role'])->first();
            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }

            if ($data['technician']) {
                Technician::firstOrCreate(
                    ['user_id' => $user->id],
                    $data['technician']
                );
            }
        }

        $this->command->info('Users seeded: ' . count($users));
    }
}
