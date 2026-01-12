<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorResource\Pages;
use App\Models\Instructor;
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

class InstructorResource extends Resource
{
    protected static ?string $model = Instructor::class;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_groups.people');
    }

    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-user-group';
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Instructor');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.model_names.Instructor');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make(__('filament.sections.instructor_information'))
                            ->description(__('filament.descriptions.instructor_info'))
                            ->schema([
                                Forms\Components\Select::make('department_id')
                                    ->label(__('filament.field.department'))
                                    ->required()
                                    ->relationship('department', 'department_name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('filament.helper_text.department')),

                                Forms\Components\TextInput::make('instructor_code')
                                    ->label(__('filament.field.instructor_code'))
                                    ->required()
                                    ->maxLength(20)
                                    ->unique(
                                        table: 'instructors',
                                        column: 'instructor_code',
                                        ignoreRecord: true
                                    )
                                    ->placeholder('INS001')
                                    ->helperText(__('filament.helper_text.instructor_code')),

                                Forms\Components\TextInput::make('first_name')
                                    ->label(__('filament.field.first_name'))
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('John')
                                    ->helperText(__('filament.helper_text.first_name')),

                                Forms\Components\TextInput::make('last_name')
                                    ->label(__('filament.field.last_name'))
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Doe')
                                    ->helperText(__('filament.helper_text.last_name')),

                                Forms\Components\TextInput::make('full_name_arabic')
                                    ->label(__('filament.field.full_name_arabic'))
                                    ->maxLength(255)
                                    ->placeholder('محمد أحمد')
                                    ->helperText(__('filament.helper_text.full_name_arabic')),

                                Forms\Components\TextInput::make('email')
                                    ->label(__('filament.field.email'))
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(
                                        table: 'instructors',
                                        column: 'email',
                                        ignoreRecord: true
                                    )
                                    ->placeholder('instructor@example.com')
                                    ->helperText(__('filament.helper_text.email')),

                                Forms\Components\TextInput::make('phone')
                                    ->label(__('filament.field.phone'))
                                    ->tel()
                                    ->maxLength(20)
                                    ->placeholder('+123456789')
                                    ->helperText(__('filament.helper_text.phone')),

                                Forms\Components\TextInput::make('title')
                                    ->label(__('filament.field.title'))
                                    ->maxLength(100)
                                    ->placeholder('Professor')
                                    ->helperText(__('filament.helper_text.instructor_title')),

                                Forms\Components\Select::make('status')
                                    ->label(__('filament.field.status'))
                                    ->required()
                                    ->options([
                                        'active' => __('filament.status.active'),
                                        'inactive' => __('filament.status.inactive'),
                                        'on_leave' => __('filament.status.on_leave'),
                                    ])
                                    ->default('active')
                                    ->helperText(__('filament.helper_text.instructor_status')),
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
                Tables\Columns\TextColumn::make('instructor_code')
                    ->label(__('filament.field.instructor_code'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('first_name')
                    ->label(__('filament.field.first_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label(__('filament.field.last_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament.field.email'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('department.department_name')
                    ->label(__('filament.field.department'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament.field.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.field.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'on_leave' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('filament.status.' . $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('course_instructor_assignments_count')
                    ->label(__('filament.field.assignments_count'))
                    ->counts('courseInstructorAssignments')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament.field.status'))
                    ->options([
                        'active' => __('filament.status.active'),
                        'inactive' => __('filament.status.inactive'),
                        'on_leave' => __('filament.status.on_leave'),
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
                    ->url(fn (Instructor $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label(fn () => __('filament.table_actions.view'))
                    ->url(fn (Instructor $record) => static::getUrl('view', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->label(fn () => __('filament.table_actions.delete'))
                    ->requiresConfirmation()
                    ->action(fn (Instructor $record) => $record->delete()),
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
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'view' => Pages\ViewInstructor::route('/{record}'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('courseInstructorAssignments');
    }
}
