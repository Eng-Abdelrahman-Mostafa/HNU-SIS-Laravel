<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Models\Department;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-building-office';
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Department');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Department');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make(__('filament.sections.department_information'))
                            ->description(__('filament.descriptions.department_info'))
                            ->schema([
                                Forms\Components\TextInput::make('department_code')
                                    ->label(__('filament.field.department_code'))
                                    ->required()
                                    ->maxLength(10)
                                    ->unique(
                                        table: 'departments',
                                        column: 'department_code',
                                        ignoreRecord: true
                                    )
                                    ->placeholder('CS')
                                    ->helperText(__('filament.helper_text.department_code')),

                                Forms\Components\TextInput::make('department_prefix')
                                    ->label(__('filament.field.department_prefix'))
                                    ->required()
                                    ->maxLength(10)
                                    ->unique(
                                        table: 'departments',
                                        column: 'department_prefix',
                                        ignoreRecord: true
                                    )
                                    ->placeholder('CSC')
                                    ->helperText(__('filament.helper_text.department_prefix')),

                                Forms\Components\TextInput::make('department_name')
                                    ->label(__('filament.field.department_name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Computer Science')
                                    ->helperText(__('filament.helper_text.department_name')),
                            ])
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department_code')
                    ->label(__('filament.field.department_code'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('department_prefix')
                    ->label(__('filament.field.department_prefix'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('department_name')
                    ->label(__('filament.field.department_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('students_count')
                    ->label(__('filament.field.students_count'))
                    ->counts('students')
                    ->sortable(),

                Tables\Columns\TextColumn::make('courses_count')
                    ->label(__('filament.field.courses_count'))
                    ->counts('courses')
                    ->sortable(),

                Tables\Columns\TextColumn::make('instructors_count')
                    ->label(__('filament.field.instructors_count'))
                    ->counts('instructors')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->label(fn () => __('filament.table_actions.edit'))
                    ->url(fn (Department $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label(fn () => __('filament.table_actions.view'))
                    ->url(fn (Department $record) => static::getUrl('view', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->label(fn () => __('filament.table_actions.delete'))
                    ->requiresConfirmation()
                    ->action(fn (Department $record) => $record->delete()),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'view' => Pages\ViewDepartment::route('/{record}'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('students', 'courses', 'instructors');
    }
}
