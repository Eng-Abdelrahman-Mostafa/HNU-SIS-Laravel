<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use App\Models\Department;
use App\Models\AcademicLevel;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_groups.people');
    }

    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-users';
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Student');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Student');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make(__('filament.sections.student_information'))
                            ->description(__('filament.descriptions.student_info'))
                            ->schema([
                                Forms\Components\TextInput::make('student_id')
                                    ->label(__('filament.field.student_id'))
                                    ->required()
                                    ->maxLength(20)
                                    ->unique(
                                        table: 'students',
                                        column: 'student_id',
                                        ignoreRecord: true
                                    )
                                    ->placeholder('2024001')
                                    ->helperText(__('filament.helper_text.student_id'))
                                    ->disabled(fn ($record) => $record !== null),

                                Forms\Components\TextInput::make('full_name_arabic')
                                    ->label(__('filament.field.full_name_arabic'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('محمد أحمد علي')
                                    ->helperText(__('filament.helper_text.full_name_arabic')),

                                Forms\Components\TextInput::make('email')
                                    ->label(__('filament.field.email'))
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(
                                        table: 'students',
                                        column: 'email',
                                        ignoreRecord: true
                                    )
                                    ->placeholder('student@example.com')
                                    ->helperText(__('filament.helper_text.email')),

                                Forms\Components\Select::make('department_id')
                                    ->label(__('filament.field.department'))
                                    ->required()
                                    ->relationship('department', 'department_name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('filament.helper_text.department')),

                                Forms\Components\Select::make('current_level_id')
                                    ->label(__('filament.field.current_level'))
                                    ->required()
                                    ->relationship('academicLevel', 'level_name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('filament.helper_text.current_level')),

                                Forms\Components\DatePicker::make('enrollment_date')
                                    ->label(__('filament.field.enrollment_date'))
                                    ->required()
                                    ->default(now())
                                    ->helperText(__('filament.helper_text.enrollment_date')),

                                Forms\Components\TextInput::make('cgpa')
                                    ->label(__('filament.field.cgpa'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(4)
                                    ->step(0.01)
                                    ->default(0)
                                    ->helperText(__('filament.helper_text.cgpa')),

                                Forms\Components\TextInput::make('credit_hours_completed')
                                    ->label(__('filament.field.credit_hours_completed'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText(__('filament.helper_text.credit_hours_completed')),

                                Forms\Components\TextInput::make('credit_hours_in_progress')
                                    ->label(__('filament.field.credit_hours_in_progress'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText(__('filament.helper_text.credit_hours_in_progress')),

                                Forms\Components\TextInput::make('credit_hours_failed')
                                    ->label(__('filament.field.credit_hours_failed'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText(__('filament.helper_text.credit_hours_failed')),

                                Forms\Components\Select::make('status')
                                    ->label(__('filament.field.status'))
                                    ->required()
                                    ->options([
                                        'active' => __('filament.status.active'),
                                        'inactive' => __('filament.status.inactive'),
                                        'graduated' => __('filament.status.graduated'),
                                        'suspended' => __('filament.status.suspended'),
                                    ])
                                    ->default('active')
                                    ->helperText(__('filament.helper_text.student_status')),
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
                Tables\Columns\TextColumn::make('student_id')
                    ->label(__('filament.field.student_id'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_name_arabic')
                    ->label(__('filament.field.full_name_arabic'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament.field.email'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('department.department_name')
                    ->label(__('filament.field.department'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('academicLevel.level_name')
                    ->label(__('filament.field.current_level'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cgpa')
                    ->label(__('filament.field.cgpa'))
                    ->numeric(2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('credit_hours_completed')
                    ->label(__('filament.field.credit_hours_completed'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.field.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'graduated' => 'info',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('filament.status.' . $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label(__('filament.field.enrollments_count'))
                    ->counts('enrollments')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament.field.status'))
                    ->options([
                        'active' => __('filament.status.active'),
                        'inactive' => __('filament.status.inactive'),
                        'graduated' => __('filament.status.graduated'),
                        'suspended' => __('filament.status.suspended'),
                    ]),
                Tables\Filters\SelectFilter::make('department')
                    ->label(__('filament.field.department'))
                    ->relationship('department', 'department_name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('current_level')
                    ->label(__('filament.field.current_level'))
                    ->relationship('academicLevel', 'level_name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->label(fn () => __('filament.table_actions.edit'))
                    ->url(fn (Student $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label(fn () => __('filament.table_actions.view'))
                    ->url(fn (Student $record) => static::getUrl('view', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->label(fn () => __('filament.table_actions.delete'))
                    ->requiresConfirmation()
                    ->action(fn (Student $record) => $record->delete()),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('enrollments');
    }
}
