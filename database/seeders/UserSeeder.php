<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin Default Permissions
        $adminPermissions = User::AdminDefaultPermission();

        // Admin Role and User
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'abhishek@gmail.com',
            'password' => bcrypt('123456789')
        ]);
        $adminUser->assignRole($adminRole);
        $adminUser->givePermissionTo($adminPermissions);


        // Employee Default Permissions
        // $employeePermissions = User::UserDefaultPermission();
        // $employeeRole = Role::firstOrCreate(['name' => 'user']);
        // $employeeUser = User::create([
        //     'name' => 'user',
        //     'email' => 'user@gmail.com',
        //     'password' => bcrypt('12345678')
        // ]);
        // $employeeUser->assignRole($employeeRole);
        // $employeeUser->givePermissionTo($employeePermissions);

        // Operator Default Permissions
        // $operatorPermissions = User::OperatorDefaultPermission();
        // $operatorRole = Role::firstOrCreate(['name' => 'operator']);
        // $operatorUser = User::create([
        //     'name' => 'operator',
        //     'email' => 'operator@gmail.com',
        //     'password' => bcrypt('12345678')
        // ]);
        // $operatorUser->assignRole($operatorRole);
        // $operatorUser->givePermissionTo($operatorPermissions);
    }
}
