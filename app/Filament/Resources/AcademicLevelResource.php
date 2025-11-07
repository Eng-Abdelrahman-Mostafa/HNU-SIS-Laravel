<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicLevelResource\Pages;
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

class AcademicLevelResource extends Resource
{
    protected static ?string $model = AcademicLevel::class;

    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-academic-cap';
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.AcademicLevel');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.AcademicLevel');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make(__('filament.sections.academic_level_information'))
                            ->description(__('filament.descriptions.academic_level_info'))
                            ->schema([
                                Forms\Components\TextInput::make('level_name')
                                    ->label(__('filament.field.level_name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Freshman')
                                    ->helperText(__('filament.helper_text.level_name')),

                                Forms\Components\TextInput::make('level_number')
                                    ->label(__('filament.field.level_number'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(10)
                                    ->placeholder('1')
                                    ->helperText(__('filament.helper_text.level_number')),

                                Forms\Components\TextInput::make('min_credit_hours')
                                    ->label(__('filament.field.min_credit_hours'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->placeholder('0')
                                    ->helperText(__('filament.helper_text.min_credit_hours')),

                                Forms\Components\TextInput::make('max_credit_hours')
                                    ->label(__('filament.field.max_credit_hours'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->placeholder('180')
                                    ->helperText(__('filament.helper_text.max_credit_hours')),
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
                Tables\Columns\TextColumn::make('level_name')
                    ->label(__('filament.field.level_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('level_number')
                    ->label(__('filament.field.level_number'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('min_credit_hours')
                    ->label(__('filament.field.min_credit_hours'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('max_credit_hours')
                    ->label(__('filament.field.max_credit_hours'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('students_count')
                    ->label(__('filament.field.students_count'))
                    ->counts('students')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->label(fn () => __('filament.table_actions.edit'))
                    ->url(fn (AcademicLevel $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label(fn () => __('filament.table_actions.view'))
                    ->url(fn (AcademicLevel $record) => static::getUrl('view', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->label(fn () => __('filament.table_actions.delete'))
                    ->requiresConfirmation()
                    ->action(fn (AcademicLevel $record) => $record->delete()),
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
            'index' => Pages\ListAcademicLevels::route('/'),
            'create' => Pages\CreateAcademicLevel::route('/create'),
            'view' => Pages\ViewAcademicLevel::route('/{record}'),
            'edit' => Pages\EditAcademicLevel::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('students');
    }
}
