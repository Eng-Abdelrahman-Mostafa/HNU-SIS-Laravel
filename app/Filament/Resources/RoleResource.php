<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource as BaseRoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Unique;
use Spatie\Permission\Models\Permission;

class RoleResource extends BaseRoleResource
{
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('filament-shield::filament-shield.field.name'))
                                    ->unique(
                                        ignoreRecord: true,
                                        modifyRuleUsing: fn (Unique $rule): Unique => Utils::isTenancyEnabled() ? $rule->where(Utils::getTenantModelForeignKey(), Filament::getTenant()?->id) : $rule
                                    )
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('guard_name')
                                    ->label(__('filament-shield::filament-shield.field.guard_name'))
                                    ->default(Utils::getFilamentAuthGuard())
                                    ->nullable()
                                    ->maxLength(255),

                                static::getSelectAllFormComponent(),
                            ])
                            ->columns([
                                'sm' => 2,
                                'lg' => 3,
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                // Custom permissions display - shows ALL permissions from database
                static::getAllPermissionsComponent(),
            ]);
    }

    protected static function getAllPermissionsComponent(): Tabs
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            // Group permissions by type
            $name = $permission->name;

            // Shield role permissions (ViewAny:Role, etc.)
            if (str_contains($name, ':Role')) {
                return 'role_management';
            }

            // Model permissions (view_User, create_Student, etc.)
            $parts = explode('_', $name);
            if (count($parts) >= 2) {
                $action = $parts[0];
                $model = implode('_', array_slice($parts, 1));

                // Check if this is a known model
                $knownModels = [
                    'User', 'Department', 'AcademicLevel', 'GradeScale',
                    'Semester', 'Student', 'Instructor', 'Course',
                    'Prerequisite', 'ProgramRequirement',
                    'CourseInstructorAssignment', 'Enrollment'
                ];

                if (in_array($model, $knownModels)) {
                    return $model;
                }
            }

            // Custom permissions (access_admin_panel, etc.)
            return 'custom';
        });

        $tabs = [];

        // Create tabs for each group
        foreach ($permissions as $group => $groupPermissions) {
            $label = static::getGroupLabel($group);

            $tabs[] = Tabs\Tab::make($label)
                ->badge(count($groupPermissions))
                ->schema([
                    Section::make()
                        ->schema([
                            CheckboxList::make('permissions')
                                ->label('')
                                ->relationship('permissions', 'name')
                                ->options(
                                    $groupPermissions->mapWithKeys(function ($permission) {
                                        return [$permission->id => static::getPermissionLabel($permission->name)];
                                    })->toArray()
                                )
                                ->descriptions(
                                    $groupPermissions->mapWithKeys(function ($permission) {
                                        return [$permission->id => new HtmlString('<small class="text-gray-500">' . $permission->name . '</small>')];
                                    })->toArray()
                                )
                                ->columns(2)
                                ->gridDirection('row')
                                ->bulkToggleable()
                                ->searchable(),
                        ])
                        ->compact(),
                ]);
        }

        return Tabs::make('all_permissions')
            ->tabs($tabs)
            ->contained()
            ->persistTabInQueryString()
            ->columnSpanFull();
    }

    protected static function getGroupLabel(string $group): string
    {
        // Special cases
        if ($group === 'custom') {
            return __('filament-shield::filament-shield.custom');
        }

        if ($group === 'role_management') {
            return __('filament-shield::filament-shield.resource.label.roles');
        }

        // Try to get translation from filament-shield model_names
        $modelTranslation = __('filament-shield::filament-shield.model_names.' . $group);

        if ($modelTranslation !== 'filament-shield::filament-shield.model_names.' . $group) {
            return $modelTranslation;
        }

        // Fallback to the original name with proper formatting
        return str_replace('_', ' ', ucwords($group, '_'));
    }

    protected static function getPermissionLabel(string $permission): string
    {
        // Handle Shield role permissions (ViewAny:Role, etc.)
        if (str_contains($permission, ':')) {
            [$action, $model] = explode(':', $permission);
            $actionLabel = __(('filament-shield::filament-shield.resource_permission_prefixes_labels.' . strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $action))));
            if ($actionLabel === 'filament-shield::filament-shield.resource_permission_prefixes_labels.' . strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $action))) {
                $actionLabel = str_replace('_', ' ', ucwords(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $action)), '_'));
            }
            return $actionLabel;
        }

        $parts = explode('_', $permission);

        // Check if it's a custom permission (no model part)
        if (count($parts) === 1 || !ctype_upper($parts[count($parts) - 1][0] ?? '')) {
            // Try to get translation for the full permission name
            $customTranslation = __('filament-shield::filament-shield.permissions.' . $permission);

            if ($customTranslation !== 'filament-shield::filament-shield.permissions.' . $permission) {
                return $customTranslation;
            }

            return str_replace('_', ' ', ucwords($permission, '_'));
        }

        // For model permissions like view_User, create_Department
        $action = $parts[0];
        $model = implode('_', array_slice($parts, 1));

        // Get action translation
        $actionTranslation = __('filament-shield::filament-shield.resource_permission_prefixes_labels.' . $action);
        if ($actionTranslation === 'filament-shield::filament-shield.resource_permission_prefixes_labels.' . $action) {
            $actionTranslation = str_replace('_', ' ', ucwords($action, '_'));
        }

        // Get model translation
        $modelTranslation = __('filament-shield::filament-shield.model_names.' . $model);
        if ($modelTranslation === 'filament-shield::filament-shield.model_names.' . $model) {
            $modelTranslation = str_replace('_', ' ', ucwords($model, '_'));
        }

        return $actionTranslation . ' ' . $modelTranslation;
    }
}
