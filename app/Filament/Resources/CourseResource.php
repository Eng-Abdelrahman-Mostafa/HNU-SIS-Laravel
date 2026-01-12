<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
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

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_groups.course_management');
    }

    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-book-open';
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Course');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Course');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make(__('filament.sections.course_information'))
                            ->description(__('filament.descriptions.course_info'))
                            ->schema([
                                Forms\Components\Select::make('department_id')
                                    ->label(__('filament.field.department'))
                                    ->required()
                                    ->relationship('department', 'department_name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('filament.helper_text.department')),

                                Forms\Components\TextInput::make('course_code')
                                    ->label(__('filament.field.course_code'))
                                    ->required()
                                    ->maxLength(20)
                                    ->unique(
                                        table: 'courses',
                                        column: 'course_code',
                                        ignoreRecord: true
                                    )
                                    ->placeholder('CSC101')
                                    ->helperText(__('filament.helper_text.course_code')),

                                Forms\Components\TextInput::make('course_name')
                                    ->label(__('filament.field.course_name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Introduction to Programming')
                                    ->helperText(__('filament.helper_text.course_name')),

                                Forms\Components\TextInput::make('credit_hours')
                                    ->label(__('filament.field.credit_hours'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10)
                                    ->default(3)
                                    ->helperText(__('filament.helper_text.credit_hours')),

                                Forms\Components\Select::make('course_type')
                                    ->label(__('filament.field.course_type'))
                                    ->required()
                                    ->options([
                                        'theory' => __('filament.course_type.theory'),
                                        'practical' => __('filament.course_type.practical'),
                                        'mixed' => __('filament.course_type.mixed'),
                                    ])
                                    ->default('theory')
                                    ->helperText(__('filament.helper_text.course_type')),

                                Forms\Components\Select::make('category')
                                    ->label(__('filament.field.category'))
                                    ->required()
                                    ->options([
                                        'university_requirement' => __('filament.category.university_requirement'),
                                        'college_requirement' => __('filament.category.college_requirement'),
                                        'department_requirement' => __('filament.category.department_requirement'),
                                        'elective' => __('filament.category.elective'),
                                    ])
                                    ->default('department_requirement')
                                    ->helperText(__('filament.helper_text.course_category')),
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
                Tables\Columns\TextColumn::make('course_code')
                    ->label(__('filament.field.course_code'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('course_name')
                    ->label(__('filament.field.course_name'))
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('department.department_name')
                    ->label(__('filament.field.department'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('credit_hours')
                    ->label(__('filament.field.credit_hours'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('course_type')
                    ->label(__('filament.field.course_type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'theory' => 'info',
                        'practical' => 'success',
                        'mixed', 'M' => 'warning',
                        'E' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('filament.course_type.' . $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label(__('filament.field.category'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'university_requirement' => 'primary',
                        'college_requirement' => 'info',
                        'department_requirement' => 'success',
                        'elective' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('filament.category.' . $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label(__('filament.field.enrollments_count'))
                    ->counts('enrollments')
                    ->sortable(),

                Tables\Columns\TextColumn::make('prerequisites_count')
                    ->label(__('filament.field.prerequisites_count'))
                    ->counts('prerequisites')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_type')
                    ->label(__('filament.field.course_type'))
                    ->options([
                        'theory' => __('filament.course_type.theory'),
                        'practical' => __('filament.course_type.practical'),
                        'mixed' => __('filament.course_type.mixed'),
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->label(__('filament.field.category'))
                    ->options([
                        'university_requirement' => __('filament.category.university_requirement'),
                        'college_requirement' => __('filament.category.college_requirement'),
                        'department_requirement' => __('filament.category.department_requirement'),
                        'elective' => __('filament.category.elective'),
                    ]),
                Tables\Filters\SelectFilter::make('department')
                    ->label(__('filament.field.department'))
                    ->relationship('department', 'department_name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->label(fn () => __('filament.table_actions.edit'))
                    ->url(fn (Course $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label(fn () => __('filament.table_actions.view'))
                    ->url(fn (Course $record) => static::getUrl('view', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->label(fn () => __('filament.table_actions.delete'))
                    ->requiresConfirmation()
                    ->action(fn (Course $record) => $record->delete()),
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'view' => Pages\ViewCourse::route('/{record}'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('enrollments', 'prerequisites');
    }
}
