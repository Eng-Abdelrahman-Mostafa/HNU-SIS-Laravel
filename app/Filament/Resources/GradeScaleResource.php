<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeScaleResource\Pages;
use App\Models\GradeScale;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GradeScaleResource extends Resource
{
    protected static ?string $model = GradeScale::class;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_groups.academic_structure');
    }

    protected static ?int $navigationSort = 3;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-squares-2x2';
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.GradeScale');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.GradeScale');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make(__('filament.sections.grade_scale_information'))
                            ->description(__('filament.descriptions.grade_scale_info'))
                            ->schema([
                                Forms\Components\TextInput::make('grade_letter')
                                    ->label(__('filament.field.grade_letter'))
                                    ->required()
                                    ->maxLength(5)
                                    ->unique(
                                        table: 'grade_scales',
                                        column: 'grade_letter',
                                        ignoreRecord: true
                                    )
                                    ->placeholder('A')
                                    ->helperText(__('filament.helper_text.grade_letter')),

                                Forms\Components\TextInput::make('min_percentage')
                                    ->label(__('filament.field.min_percentage'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(0.01)
                                    ->placeholder('90')
                                    ->helperText(__('filament.helper_text.min_percentage')),

                                Forms\Components\TextInput::make('max_percentage')
                                    ->label(__('filament.field.max_percentage'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(0.01)
                                    ->placeholder('100')
                                    ->helperText(__('filament.helper_text.max_percentage')),

                                Forms\Components\TextInput::make('grade_points')
                                    ->label(__('filament.field.grade_points'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(4)
                                    ->step(0.01)
                                    ->placeholder('4.0')
                                    ->helperText(__('filament.helper_text.grade_points')),

                                Forms\Components\Toggle::make('status')
                                    ->label(__('filament.field.status'))
                                    ->default(true)
                                    ->helperText(__('filament.helper_text.status')),
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
                Tables\Columns\TextColumn::make('grade_letter')
                    ->label(__('filament.field.grade_letter'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('min_percentage')
                    ->label(__('filament.field.min_percentage'))
                    ->sortable()
                    ->suffix('%'),

                Tables\Columns\TextColumn::make('max_percentage')
                    ->label(__('filament.field.max_percentage'))
                    ->sortable()
                    ->suffix('%'),

                Tables\Columns\TextColumn::make('grade_points')
                    ->label(__('filament.field.grade_points'))
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label(__('filament.field.status'))
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('status')
                    ->label(__('filament.field.status'))
                    ->toggle(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->label(fn () => __('filament.table_actions.edit'))
                    ->url(fn (GradeScale $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label(fn () => __('filament.table_actions.view'))
                    ->url(fn (GradeScale $record) => static::getUrl('view', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->label(fn () => __('filament.table_actions.delete'))
                    ->requiresConfirmation()
                    ->action(fn (GradeScale $record) => $record->delete()),
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
            'index' => Pages\ListGradeScales::route('/'),
            'create' => Pages\CreateGradeScale::route('/create'),
            'view' => Pages\ViewGradeScale::route('/{record}'),
            'edit' => Pages\EditGradeScale::route('/{record}/edit'),
        ];
    }
}
