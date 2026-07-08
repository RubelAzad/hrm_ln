<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        if (Employee::count() > 0) {
            return;
        }

        $admin = Employee::create([
            'employee_id' => 'EMP-00001',
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Admin',
            'email' => 'john.admin@hrm.com',
            'phone' => '+1-555-0100',
            'date_of_birth' => '1985-03-15',
            'gender' => 'male',
            'marital_status' => 'married',
            'nationality' => 'American',
            'address' => '123 Main Street',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
            'department' => 'Management',
            'job_title' => 'Chief Executive Officer',
            'employment_type' => 'full-time',
            'joining_date' => '2020-01-01',
            'confirmation_date' => '2020-07-01',
            'supervisor_id' => null,
            'status' => 'active',
        ]);

        $hrManager = Employee::create([
            'employee_id' => 'EMP-00002',
            'user_id' => null,
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'email' => 'sarah.johnson@hrm.com',
            'phone' => '+1-555-0101',
            'date_of_birth' => '1990-06-20',
            'gender' => 'female',
            'marital_status' => 'single',
            'nationality' => 'American',
            'address' => '456 Oak Avenue',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10002',
            'country' => 'USA',
            'department' => 'Human Resources',
            'job_title' => 'HR Manager',
            'employment_type' => 'full-time',
            'joining_date' => '2021-03-15',
            'confirmation_date' => '2021-09-15',
            'supervisor_id' => $admin->id,
            'status' => 'active',
        ]);

        Employee::create([
            'employee_id' => 'EMP-00003',
            'user_id' => null,
            'first_name' => 'Michael',
            'last_name' => 'Brown',
            'email' => 'michael.brown@hrm.com',
            'phone' => '+1-555-0102',
            'date_of_birth' => '1995-11-08',
            'gender' => 'male',
            'marital_status' => 'single',
            'nationality' => 'American',
            'address' => '789 Pine Road',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10003',
            'country' => 'USA',
            'department' => 'Engineering',
            'job_title' => 'Software Engineer',
            'employment_type' => 'full-time',
            'joining_date' => '2022-06-01',
            'confirmation_date' => '2022-12-01',
            'supervisor_id' => $hrManager->id,
            'status' => 'active',
        ]);

        $admin->contacts()->createMany([
            ['name' => 'Jane Admin', 'relationship' => 'Spouse', 'phone' => '+1-555-0199', 'is_primary' => true],
            ['name' => 'Bob Admin', 'relationship' => 'Brother', 'phone' => '+1-555-0198', 'is_primary' => false],
        ]);

        $admin->histories()->createMany([
            [
                'company_name' => 'Tech Corp',
                'job_title' => 'VP of Operations',
                'start_date' => '2015-03-01',
                'end_date' => '2019-12-31',
                'is_current' => false,
                'description' => 'Led operations for a mid-sized tech company.',
            ],
            [
                'company_name' => 'Startup Inc',
                'job_title' => 'Operations Director',
                'start_date' => '2012-01-01',
                'end_date' => '2015-02-28',
                'is_current' => false,
                'description' => 'Managed day-to-day operations.',
            ],
        ]);

        $admin->skills()->createMany([
            ['skill_name' => 'Leadership', 'category' => 'skill', 'proficiency' => 'expert'],
            ['skill_name' => 'Strategic Planning', 'category' => 'skill', 'proficiency' => 'advanced'],
            ['skill_name' => 'PMP Certification', 'category' => 'certification', 'proficiency' => 'expert', 'issued_by' => 'PMI', 'issued_date' => '2018-05-01'],
        ]);

        $admin->assets()->createMany([
            [
                'asset_type' => 'laptop',
                'asset_name' => 'MacBook Pro 16"',
                'asset_serial' => 'SN-MBP-2024-001',
                'brand' => 'Apple',
                'model' => 'MacBook Pro M3',
                'color' => 'Space Gray',
                'specification' => ['RAM' => '32GB', 'Storage' => '1TB SSD', 'Processor' => 'M3 Pro'],
                'assigned_date' => '2024-01-15',
                'condition_at_assignment' => 'new',
                'status' => 'assigned',
            ],
            [
                'asset_type' => 'phone',
                'asset_name' => 'iPhone 15 Pro',
                'asset_serial' => 'SN-IP-2024-001',
                'brand' => 'Apple',
                'model' => 'iPhone 15 Pro',
                'color' => 'Natural Titanium',
                'assigned_date' => '2024-01-15',
                'condition_at_assignment' => 'new',
                'status' => 'assigned',
            ],
            [
                'asset_type' => 'id_card',
                'asset_name' => 'Employee ID Card',
                'asset_serial' => 'ID-001',
                'assigned_date' => '2020-01-01',
                'condition_at_assignment' => 'new',
                'status' => 'assigned',
            ],
        ]);
    }
}
