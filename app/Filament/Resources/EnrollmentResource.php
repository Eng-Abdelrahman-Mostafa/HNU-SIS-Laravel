<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
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

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_groups.operations');
    }

    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clipboard-document-check';
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Enrollment');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Enrollment');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make(__('filament.sections.enrollment_information'))
                            ->description(__('filament.descriptions.enrollment_info'))
                            ->schema([
                                Forms\Components\Select::make('student_id')
                                    ->label(__('filament.field.student'))
                                    ->required()
                                    ->relationship('student', 'student_id')
                                    ->getOptionLabelFromRecordUsing(fn (Student $record) => "{$record->student_id} - {$record->full_name_arabic}")
                                    ->searchable(['student_id', 'full_name_arabic'])
                                    ->preload()
                                    ->helperText(__('filament.helper_text.student')),

                                Forms\Components\Select::make('course_id')
                                    ->label(__('filament.field.course'))
                                    ->required()
                                    ->relationship('course', 'course_name')
                                    ->getOptionLabelFromRecordUsing(fn (Course $record) => "{$record->course_code} - {$record->course_name}")
                                    ->searchable(['course_code', 'course_name'])
                                    ->preload()
                                    ->helperText(__('filament.helper_text.course')),

                                Forms\Components\Select::make('semester_id')
                                    ->label(__('filament.field.semester'))
                                    ->required()
                                    ->relationship('semester', 'semester_name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('filament.helper_text.semester')),

                                Forms\Components\DatePicker::make('enrollment_date')
                                    ->label(__('filament.field.enrollment_date'))
                                    ->required()
                                    ->default(now())
                                    ->helperText(__('filament.helper_text.enrollment_date')),

                                Forms\Components\Select::make('status')
                                    ->label(__('filament.field.status'))
                                    ->required()
                                    ->options([
                                        'registered' => __('filament.enrollment_status.registered'),
                                        'withdrawn' => __('filament.enrollment_status.withdrawn'),
                                        'completed' => __('filament.enrollment_status.completed'),
                                    ])
                                    ->default('registered')
                                    ->helperText(__('filament.helper_text.enrollment_status')),

                                Forms\Components\Toggle::make('is_retake')
                                    ->label(__('filament.field.is_retake'))
                                    ->default(false)
                                    ->helperText(__('filament.helper_text.is_retake')),

                                Forms\Components\TextInput::make('grade_points')
                                    ->label(__('filament.field.grade_points'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(4)
                                    ->step(0.01)
                                    ->helperText(__('filament.helper_text.grade_points')),
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
                Tables\Columns\TextColumn::make('student.student_id')
                    ->label(__('filament.field.student_id'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.full_name_arabic')
                    ->label(__('filament.field.student_name'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('course.course_code')
                    ->label(__('filament.field.course_code'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('course.course_name')
                    ->label(__('filament.field.course_name'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('semester.semester_name')
                    ->label(__('filament.field.semester'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('enrollment_date')
                    ->label(__('filament.field.enrollment_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.field.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'registered' => 'info',
                        'withdrawn' => 'warning',
                        'completed' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('filament.enrollment_status.' . $state))
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_retake')
                    ->label(__('filament.field.is_retake'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('grade_points')
                    ->label(__('filament.field.grade_points'))
                    ->numeric(2)
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament.field.status'))
                    ->options([
                        'registered' => __('filament.enrollment_status.registered'),
                        'withdrawn' => __('filament.enrollment_status.withdrawn'),
                        'completed' => __('filament.enrollment_status.completed'),
                    ]),
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
                Tables\Filters\TernaryFilter::make('is_retake')
                    ->label(__('filament.field.is_retake'))
                    ->boolean()
                    ->trueLabel(__('filament.filter.retake_only'))
                    ->falseLabel(__('filament.filter.first_attempt'))
                    ->native(false),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->label(fn () => __('filament.table_actions.edit'))
                    ->url(fn (Enrollment $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label(fn () => __('filament.table_actions.view'))
                    ->url(fn (Enrollment $record) => static::getUrl('view', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->label(fn () => __('filament.table_actions.delete'))
                    ->requiresConfirmation()
                    ->action(fn (Enrollment $record) => $record->delete()),
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
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'view' => Pages\ViewEnrollment::route('/{record}'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }
}
