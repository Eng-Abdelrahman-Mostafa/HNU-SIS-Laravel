<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseInstructorAssignmentResource\Pages;
use App\Models\CourseInstructorAssignment;
use App\Models\Course;
use App\Models\Instructor;
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

class CourseInstructorAssignmentResource extends Resource
{
    protected static ?string $model = CourseInstructorAssignment::class;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_groups.operations');
    }

    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-user-plus';
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.CourseInstructorAssignment');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.CourseInstructorAssignment');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make(__('filament.sections.assignment_information'))
                            ->description(__('filament.descriptions.assignment_info'))
                            ->schema([
                                Forms\Components\Select::make('semester_id')
                                    ->label(__('filament.field.semester'))
                                    ->required()
                                    ->relationship('semester', 'semester_name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('filament.helper_text.semester')),

                                Forms\Components\Select::make('course_id')
                                    ->label(__('filament.field.course'))
                                    ->required()
                                    ->relationship('course', 'course_name')
                                    ->getOptionLabelFromRecordUsing(fn (Course $record) => "{$record->course_code} - {$record->course_name}")
                                    ->searchable(['course_code', 'course_name'])
                                    ->preload()
                                    ->helperText(__('filament.helper_text.course')),

                                Forms\Components\Select::make('instructor_id')
                                    ->label(__('filament.field.instructor'))
                                    ->required()
                                    ->relationship('instructor', 'instructor_code')
                                    ->getOptionLabelFromRecordUsing(fn (Instructor $record) => "{$record->instructor_code} - {$record->first_name} {$record->last_name}")
                                    ->searchable(['instructor_code', 'first_name', 'last_name'])
                                    ->preload()
                                    ->helperText(__('filament.helper_text.instructor')),

                                Forms\Components\TextInput::make('section_number')
                                    ->label(__('filament.field.section_number'))
                                    ->required()
                                    ->maxLength(10)
                                    ->placeholder('01')
                                    ->helperText(__('filament.helper_text.section_number')),

                                Forms\Components\TextInput::make('student_count')
                                    ->label(__('filament.field.student_count'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText(__('filament.helper_text.student_count')),
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
                Tables\Columns\TextColumn::make('semester.semester_name')
                    ->label(__('filament.field.semester'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('course.course_code')
                    ->label(__('filament.field.course_code'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('course.course_name')
                    ->label(__('filament.field.course_name'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('section_number')
                    ->label(__('filament.field.section_number'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('instructor.instructor_code')
                    ->label(__('filament.field.instructor_code'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('instructor.first_name')
                    ->label(__('filament.field.instructor_name'))
                    ->formatStateUsing(fn (CourseInstructorAssignment $record) =>
                        "{$record->instructor->first_name} {$record->instructor->last_name}"
                    )
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('student_count')
                    ->label(__('filament.field.student_count'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('semester')
                    ->label(__('filament.field.semester'))
                    ->relationship('semester', 'semester_name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('course')
                    ->label(__('filament.field.course'))
                    ->relationship('course', 'course_name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('instructor')
                    ->label(__('filament.field.instructor'))
                    ->relationship('instructor', 'instructor_code')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->label(fn () => __('filament.table_actions.edit'))
                    ->url(fn (CourseInstructorAssignment $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label(fn () => __('filament.table_actions.view'))
                    ->url(fn (CourseInstructorAssignment $record) => static::getUrl('view', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->label(fn () => __('filament.table_actions.delete'))
                    ->requiresConfirmation()
                    ->action(fn (CourseInstructorAssignment $record) => $record->delete()),
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
            'index' => Pages\ListCourseInstructorAssignments::route('/'),
            'create' => Pages\CreateCourseInstructorAssignment::route('/create'),
            'view' => Pages\ViewCourseInstructorAssignment::route('/{record}'),
            'edit' => Pages\EditCourseInstructorAssignment::route('/{record}/edit'),
        ];
    }
}
