<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrerequisiteResource\Pages;
use App\Models\Prerequisite;
use App\Models\Course;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PrerequisiteResource extends Resource
{
    protected static ?string $model = Prerequisite::class;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_groups.course_management');
    }

    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-arrow-path';
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Prerequisite');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Prerequisite');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make(__('filament.sections.prerequisite_information'))
                            ->description(__('filament.descriptions.prerequisite_info'))
                            ->schema([
                                Forms\Components\Select::make('course_id')
                                    ->label(__('filament.field.course'))
                                    ->required()
                                    ->relationship('course', 'course_name')
                                    ->getOptionLabelFromRecordUsing(fn (Course $record) => "{$record->course_code} - {$record->course_name}")
                                    ->searchable(['course_code', 'course_name'])
                                    ->preload()
                                    ->helperText(__('filament.helper_text.prerequisite_course')),

                                Forms\Components\Select::make('prerequisite_course_id')
                                    ->label(__('filament.field.prerequisite_course'))
                                    ->required()
                                    ->relationship('prerequisiteCourse', 'course_name')
                                    ->getOptionLabelFromRecordUsing(fn (Course $record) => "{$record->course_code} - {$record->course_name}")
                                    ->searchable(['course_code', 'course_name'])
                                    ->preload()
                                    ->helperText(__('filament.helper_text.prerequisite_course_required')),
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
                Tables\Columns\TextColumn::make('course.course_code')
                    ->label(__('filament.field.course_code'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('course.course_name')
                    ->label(__('filament.field.course_name'))
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('prerequisiteCourse.course_code')
                    ->label(__('filament.field.prerequisite_code'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('prerequisiteCourse.course_name')
                    ->label(__('filament.field.prerequisite_name'))
                    ->searchable()
                    ->sortable()
                    ->limit(40),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->label(__('filament.field.course'))
                    ->relationship('course', 'course_name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->label(fn () => __('filament.table_actions.edit'))
                    ->url(fn (Prerequisite $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label(fn () => __('filament.table_actions.view'))
                    ->url(fn (Prerequisite $record) => static::getUrl('view', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->label(fn () => __('filament.table_actions.delete'))
                    ->requiresConfirmation()
                    ->action(fn (Prerequisite $record) => $record->delete()),
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
            'index' => Pages\ListPrerequisites::route('/'),
            'create' => Pages\CreatePrerequisite::route('/create'),
            'view' => Pages\ViewPrerequisite::route('/{record}'),
            'edit' => Pages\EditPrerequisite::route('/{record}/edit'),
        ];
    }
}
