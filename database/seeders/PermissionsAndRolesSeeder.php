<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class PermissionsAndRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache before seeding
        $this->command->info('Clearing permission cache...');
        Cache::forget('spatie.permission.cache');
        Artisan::call('cache:clear');

        // Disable foreign key checks for PostgreSQL
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        // Start transaction for atomicity
        DB::beginTransaction();

        try {
            $this->command->info('Starting permissions and roles seeding...');

            // Step 1: Create all permissions
            $this->command->info('Creating permissions...');
            $permissions = $this->createPermissions();
            $this->command->info(count($permissions) . ' permissions created successfully.');

            // Step 2: Create all roles
            $this->command->info('Creating roles...');
            $roles = $this->createRoles();
            $this->command->info(count($roles) . ' roles created successfully.');

            // Step 3: Assign permissions to roles
            $this->command->info('Assigning permissions to roles...');
            $this->assignPermissionsToRoles($roles, $permissions);
            $this->command->info('Permissions assigned to roles successfully.');

            // Commit transaction
            DB::commit();

            // Clear cache after seeding
            $this->command->info('Clearing cache after seeding...');
            Cache::forget('spatie.permission.cache');
            Artisan::call('permission:cache-reset');

            $this->command->info('✓ Permissions and roles seeded successfully!');
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollBack();
            $this->command->error('Error seeding permissions and roles: ' . $e->getMessage());
            $this->command->error($e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Create all permissions from config.
     */
    protected function createPermissions(): array
    {
        $config = config('permissions');
        $guardName = $config['guard_name'];
        $permissions = [];
        $permissionData = [];

        // Generate model-based permissions
        foreach ($config['models'] as $modelKey => $modelConfig) {
            foreach ($modelConfig['permissions'] as $action) {
                $permissionName = str_replace(
                    ['{action}', '{model}'],
                    [$action, $modelConfig['name']],
                    $config['permission_format']
                );

                $permissionData[] = [
                    'name' => $permissionName,
                    'guard_name' => $guardName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Add additional permissions
        foreach ($config['additional_permissions'] as $permissionKey => $permissionLabel) {
            $permissionData[] = [
                'name' => $permissionKey,
                'guard_name' => $guardName,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Batch insert permissions using chunks for better performance
        $chunks = array_chunk($permissionData, 100);
        $progressBar = $this->command->getOutput()->createProgressBar(count($chunks));
        $progressBar->start();

        foreach ($chunks as $chunk) {
            // Use insertOrIgnore to avoid duplicate key errors
            DB::table('permissions')->insertOrIgnore($chunk);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine();

        // Fetch all permissions from database
        $allPermissions = Permission::all();
        foreach ($allPermissions as $permission) {
            $permissions[$permission->name] = $permission;
        }

        return $permissions;
    }

    /**
     * Create all roles from config.
     */
    protected function createRoles(): array
    {
        $config = config('permissions.roles');
        $roles = [];
        $roleData = [];

        foreach ($config as $roleKey => $roleConfig) {
            $roleData[] = [
                'name' => $roleConfig['name'],
                'guard_name' => $roleConfig['guard_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Batch insert roles
        DB::table('roles')->insertOrIgnore($roleData);

        // Fetch all roles from database
        $allRoles = Role::all();
        foreach ($allRoles as $role) {
            $roles[$role->name] = $role;
        }

        return $roles;
    }

    /**
     * Assign permissions to roles based on config.
     */
    protected function assignPermissionsToRoles(array $roles, array $permissions): void
    {
        $config = config('permissions.roles');
        $rolePermissionData = [];

        $progressBar = $this->command->getOutput()->createProgressBar(count($config));
        $progressBar->start();

        foreach ($config as $roleKey => $roleConfig) {
            if (!isset($roles[$roleConfig['name']])) {
                $this->command->warn("Role {$roleConfig['name']} not found, skipping...");
                continue;
            }

            $role = $roles[$roleConfig['name']];

            // If role has all permissions (*)
            if ($roleConfig['permissions'] === '*') {
                foreach ($permissions as $permission) {
                    $rolePermissionData[] = [
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ];
                }
            } else {
                // Assign specific permissions
                foreach ($roleConfig['permissions'] as $permissionName) {
                    if (isset($permissions[$permissionName])) {
                        $rolePermissionData[] = [
                            'permission_id' => $permissions[$permissionName]->id,
                            'role_id' => $role->id,
                        ];
                    } else {
                        $this->command->warn("Permission {$permissionName} not found for role {$role->name}");
                    }
                }
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine();

        // Batch insert role-permission relationships using chunks
        if (!empty($rolePermissionData)) {
            $chunks = array_chunk($rolePermissionData, 500);
            $progressBar = $this->command->getOutput()->createProgressBar(count($chunks));
            $progressBar->start();

            foreach ($chunks as $chunk) {
                DB::table('role_has_permissions')->insertOrIgnore($chunk);
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->command->newLine();
        }
    }

    /**
     * Get summary of permissions and roles.
     */
    protected function getSummary(): array
    {
        return [
            'total_permissions' => Permission::count(),
            'total_roles' => Role::count(),
            'roles' => Role::with('permissions')->get()->map(function ($role) {
                return [
                    'name' => $role->name,
                    'permissions_count' => $role->permissions->count(),
                ];
            }),
        ];
    }
}
