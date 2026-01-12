<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramRequirementResource\Pages;
use App\Models\ProgramRequirement;
use App\Models\Department;
use App\Models\Course;
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

class ProgramRequirementResource extends Resource
{
    protected static ?string $model = ProgramRequirement::class;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_groups.course_management');
    }

    protected static ?int $navigationSort = 3;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.ProgramRequirement');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.ProgramRequirement');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make(__('filament.sections.program_requirement_information'))
                            ->description(__('filament.descriptions.program_requirement_info'))
                            ->schema([
                                Forms\Components\Select::make('department_id')
                                    ->label(__('filament.field.department'))
                                    ->required()
                                    ->relationship('department', 'department_name')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->helperText(__('filament.helper_text.department')),

                                Forms\Components\Select::make('course_id')
                                    ->label(__('filament.field.course'))
                                    ->required()
                                    ->relationship('course', 'course_name', fn (Builder $query, callable $get) =>
                                        $get('department_id')
                                            ? $query->where('department_id', $get('department_id'))
                                            : $query
                                    )
                                    ->getOptionLabelFromRecordUsing(fn (Course $record) => "{$record->course_code} - {$record->course_name}")
                                    ->searchable(['course_code', 'course_name'])
                                    ->preload()
                                    ->helperText(__('filament.helper_text.course')),

                                Forms\Components\Select::make('level_id')
                                    ->label(__('filament.field.academic_level'))
                                    ->required()
                                    ->relationship('academicLevel', 'level_name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('filament.helper_text.academic_level')),

                                Forms\Components\Select::make('requirement_type')
                                    ->label(__('filament.field.requirement_type'))
                                    ->required()
                                    ->options([
                                        'mandatory' => __('filament.requirement_type.mandatory'),
                                        'elective' => __('filament.requirement_type.elective'),
                                    ])
                                    ->default('mandatory')
                                    ->helperText(__('filament.helper_text.requirement_type')),
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
                Tables\Columns\TextColumn::make('department.department_name')
                    ->label(__('filament.field.department'))
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
                    ->limit(40),

                Tables\Columns\TextColumn::make('academicLevel.level_name')
                    ->label(__('filament.field.academic_level'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('requirement_type')
                    ->label(__('filament.field.requirement_type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mandatory' => 'danger',
                        'elective' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('filament.requirement_type.' . $state))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('requirement_type')
                    ->label(__('filament.field.requirement_type'))
                    ->options([
                        'mandatory' => __('filament.requirement_type.mandatory'),
                        'elective' => __('filament.requirement_type.elective'),
                    ]),
                Tables\Filters\SelectFilter::make('department')
                    ->label(__('filament.field.department'))
                    ->relationship('department', 'department_name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('level')
                    ->label(__('filament.field.academic_level'))
                    ->relationship('academicLevel', 'level_name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->label(fn () => __('filament.table_actions.edit'))
                    ->url(fn (ProgramRequirement $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label(fn () => __('filament.table_actions.view'))
                    ->url(fn (ProgramRequirement $record) => static::getUrl('view', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->label(fn () => __('filament.table_actions.delete'))
                    ->requiresConfirmation()
                    ->action(fn (ProgramRequirement $record) => $record->delete()),
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
            'index' => Pages\ListProgramRequirements::route('/'),
            'create' => Pages\CreateProgramRequirement::route('/create'),
            'view' => Pages\ViewProgramRequirement::route('/{record}'),
            'edit' => Pages\EditProgramRequirement::route('/{record}/edit'),
        ];
    }
}
