<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Create User', 'slug' => 'user-create'],
            ['name' => 'Read User', 'slug' => 'user-read'],
            ['name' => 'Update User', 'slug' => 'user-update'],
            ['name' => 'Delete User', 'slug' => 'user-delete'],
            ['name' => 'Create Role', 'slug' => 'role-create'],
            ['name' => 'Read Role', 'slug' => 'role-read'],
            ['name' => 'Update Role', 'slug' => 'role-update'],
            ['name' => 'Delete Role', 'slug' => 'role-delete'],
            ['name' => 'Create Permission', 'slug' => 'permission-create'],
            ['name' => 'Read Permission', 'slug' => 'permission-read'],
            ['name' => 'Update Permission', 'slug' => 'permission-update'],
            ['name' => 'Delete Permission', 'slug' => 'permission-delete'],
            ['name' => 'Create Employee', 'slug' => 'employee-create'],
            ['name' => 'Read Employee', 'slug' => 'employee-read'],
            ['name' => 'Update Employee', 'slug' => 'employee-update'],
            ['name' => 'Delete Employee', 'slug' => 'employee-delete'],
            ['name' => 'Read Attendance', 'slug' => 'attendance-read'],
            ['name' => 'Create Attendance', 'slug' => 'attendance-create'],
            ['name' => 'Update Attendance', 'slug' => 'attendance-update'],
            ['name' => 'Delete Attendance', 'slug' => 'attendance-delete'],
            ['name' => 'Read Leave', 'slug' => 'leave-read'],
            ['name' => 'Create Leave', 'slug' => 'leave-create'],
            ['name' => 'Update Leave', 'slug' => 'leave-update'],
            ['name' => 'Approve Leave', 'slug' => 'leave-approve'],
            ['name' => 'Delete Leave', 'slug' => 'leave-delete'],
            ['name' => 'Read Payroll', 'slug' => 'payroll-read'],
            ['name' => 'Create Payroll', 'slug' => 'payroll-create'],
            ['name' => 'Update Payroll', 'slug' => 'payroll-update'],
            ['name' => 'Delete Payroll', 'slug' => 'payroll-delete'],
            ['name' => 'Read Report', 'slug' => 'report-read'],
            ['name' => 'Create Report', 'slug' => 'report-create'],
            ['name' => 'Create Job Posting', 'slug' => 'job-posting-create'],
            ['name' => 'Read Job Posting', 'slug' => 'job-posting-read'],
            ['name' => 'Update Job Posting', 'slug' => 'job-posting-update'],
            ['name' => 'Delete Job Posting', 'slug' => 'job-posting-delete'],
            ['name' => 'Create Candidate', 'slug' => 'candidate-create'],
            ['name' => 'Read Candidate', 'slug' => 'candidate-read'],
            ['name' => 'Update Candidate', 'slug' => 'candidate-update'],
            ['name' => 'Delete Candidate', 'slug' => 'candidate-delete'],
            ['name' => 'Create Interview', 'slug' => 'interview-create'],
            ['name' => 'Read Interview', 'slug' => 'interview-read'],
            ['name' => 'Update Interview', 'slug' => 'interview-update'],
            ['name' => 'Create Offer', 'slug' => 'offer-create'],
            ['name' => 'Read Offer', 'slug' => 'offer-read'],
            ['name' => 'Create Verification', 'slug' => 'verification-create'],
            ['name' => 'Read Verification', 'slug' => 'verification-read'],
            ['name' => 'Update Verification', 'slug' => 'verification-update'],
            ['name' => 'Manage Talent Pool', 'slug' => 'talent-pool-manage'],
        ];

        $savedPermissions = collect();
        foreach ($permissions as $perm) {
            $savedPermissions->push(Permission::firstOrCreate(
                ['slug' => $perm['slug']],
                $perm
            ));
        }

        $roles = [
            'super-admin' => [
                'name' => 'Super Admin',
                'description' => 'Full system access',
                'permissions' => $savedPermissions->pluck('slug')->toArray(),
            ],
            'admin' => [
                'name' => 'Admin',
                'description' => 'Administrative access',
                'permissions' => [
                    'user-create', 'user-read', 'user-update', 'user-delete',
                    'role-create', 'role-read', 'role-update', 'role-delete',
                    'permission-create', 'permission-read', 'permission-update', 'permission-delete',
                    'employee-create', 'employee-read', 'employee-update', 'employee-delete',
                    'attendance-read', 'attendance-create', 'attendance-update', 'attendance-delete',
                    'leave-read', 'leave-create', 'leave-update', 'leave-approve', 'leave-delete',
                    'payroll-read', 'payroll-create', 'payroll-update', 'payroll-delete',
                    'report-read', 'report-create',
                    'job-posting-create', 'job-posting-read', 'job-posting-update', 'job-posting-delete',
                    'candidate-create', 'candidate-read', 'candidate-update', 'candidate-delete',
                    'interview-create', 'interview-read', 'interview-update',
                    'offer-create', 'offer-read',
                    'verification-create', 'verification-read', 'verification-update',
                    'talent-pool-manage',
                ],
            ],
            'hr-manager' => [
                'name' => 'HR Manager',
                'description' => 'HR department management',
                'permissions' => [
                    'employee-create', 'employee-read', 'employee-update', 'employee-delete',
                    'attendance-read', 'attendance-create', 'attendance-update', 'attendance-delete',
                    'leave-read', 'leave-create', 'leave-update', 'leave-approve', 'leave-delete',
                    'payroll-read', 'report-read', 'report-create',
                    'job-posting-create', 'job-posting-read', 'job-posting-update', 'job-posting-delete',
                    'candidate-create', 'candidate-read', 'candidate-update', 'candidate-delete',
                    'interview-create', 'interview-read', 'interview-update',
                    'offer-create', 'offer-read',
                    'verification-create', 'verification-read', 'verification-update',
                    'talent-pool-manage',
                ],
            ],
            'employee' => [
                'name' => 'Employee',
                'description' => 'Standard employee access',
                'permissions' => [
                    'employee-read', 'employee-update',
                    'attendance-read',
                    'leave-read', 'leave-create',
                    'payroll-read',
                ],
            ],
        ];

        foreach ($roles as $slug => $data) {
            $role = Role::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                ]
            );

            $permIds = Permission::whereIn('slug', $data['permissions'])->pluck('id');
            $role->permissions()->sync($permIds);
        }

        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@hrm.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $superAdmin->roles()->sync(Role::where('slug', 'super-admin')->first()->id);
    }
}
