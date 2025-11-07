<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SemesterResource\Pages;
use App\Models\Semester;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SemesterResource extends Resource
{
    protected static ?string $model = Semester::class;

    protected static ?int $navigationSort = 4;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-calendar-days';
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Semester');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Semester');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make(__('filament.sections.semester_information'))
                            ->description(__('filament.descriptions.semester_info'))
                            ->schema([
                                Forms\Components\TextInput::make('semester_code')
                                    ->label(__('filament.field.semester_code'))
                                    ->required()
                                    ->maxLength(10)
                                    ->unique(
                                        table: 'semesters',
                                        column: 'semester_code',
                                        ignoreRecord: true
                                    )
                                    ->placeholder('F2024')
                                    ->helperText(__('filament.helper_text.semester_code')),

                                Forms\Components\TextInput::make('semester_name')
                                    ->label(__('filament.field.semester_name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Fall 2024')
                                    ->helperText(__('filament.helper_text.semester_name')),

                                Forms\Components\TextInput::make('year')
                                    ->label(__('filament.field.year'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(2100)
                                    ->placeholder('2024')
                                    ->helperText(__('filament.helper_text.year')),

                                Forms\Components\DatePicker::make('start_date')
                                    ->label(__('filament.field.start_date'))
                                    ->required()
                                    ->helperText(__('filament.helper_text.start_date')),

                                Forms\Components\DatePicker::make('end_date')
                                    ->label(__('filament.field.end_date'))
                                    ->required()
                                    ->afterOrEqual('start_date')
                                    ->helperText(__('filament.helper_text.end_date')),

                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('filament.field.is_active'))
                                    ->default(false)
                                    ->helperText(__('filament.helper_text.is_active')),
                            ])
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                            ])
                            ->columnSpanFull(),

                        Section::make(__('filament.sections.student_registration_information'))
                            ->description(__('filament.descriptions.student_registration_info'))
                            ->schema([
                                Forms\Components\DateTimePicker::make('student_registeration_start_at')
                                    ->label(__('filament.field.student_registeration_start_at'))
                                    ->nullable()
                                    ->helperText(__('filament.helper_text.student_registeration_start_at')),

                                Forms\Components\DateTimePicker::make('student_registeration_end_at')
                                    ->label(__('filament.field.student_registeration_end_at'))
                                    ->nullable()
                                    ->afterOrEqual('student_registeration_start_at')
                                    ->helperText(__('filament.helper_text.student_registeration_end_at')),
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
                Tables\Columns\TextColumn::make('semester_code')
                    ->label(__('filament.field.semester_code'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('semester_name')
                    ->label(__('filament.field.semester_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('year')
                    ->label(__('filament.field.year'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('filament.field.start_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label(__('filament.field.end_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label(__('filament.field.enrollments_count'))
                    ->counts('enrollments')
                    ->sortable(),

                Tables\Columns\TextColumn::make('courseInstructorAssignments_count')
                    ->label(__('filament.field.assignments_count'))
                    ->counts('courseInstructorAssignments')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament.field.is_active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student_registeration_start_at')
                    ->label(__('filament.field.student_registeration_start_at'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student_registeration_end_at')
                    ->label(__('filament.field.student_registeration_end_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->label(fn () => __('filament.table_actions.edit'))
                    ->url(fn (Semester $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label(fn () => __('filament.table_actions.view'))
                    ->url(fn (Semester $record) => static::getUrl('view', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->label(fn () => __('filament.table_actions.delete'))
                    ->requiresConfirmation()
                    ->action(fn (Semester $record) => $record->delete()),
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
            'index' => Pages\ListSemesters::route('/'),
            'create' => Pages\CreateSemester::route('/create'),
            'view' => Pages\ViewSemester::route('/{record}'),
            'edit' => Pages\EditSemester::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('enrollments', 'courseInstructorAssignments');
    }
}
