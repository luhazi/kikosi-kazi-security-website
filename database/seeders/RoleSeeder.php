<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()['cache']->forget('spatie.permission.cache');

        $permissions = [
            'manage users',
            'manage jobs',
            'manage applications',
            'manage cms',
            'view reports',
            'apply jobs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roles = [
            'super_admin',
            'hr_manager',
            'recruitment_officer',
            'content_editor',
            'candidate',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $superAdmin = Role::findByName('super_admin');
        $superAdmin->syncPermissions(Permission::all());

        $hrManager = Role::findByName('hr_manager');
        $hrManager->syncPermissions([
            'manage jobs',
            'manage applications',
            'view reports',
        ]);

        $recruitmentOfficer = Role::findByName('recruitment_officer');
        $recruitmentOfficer->syncPermissions(['manage applications']);

        $contentEditor = Role::findByName('content_editor');
        $contentEditor->syncPermissions(['manage cms']);

        $candidate = Role::findByName('candidate');
        $candidate->syncPermissions(['apply jobs']);
    }
}
