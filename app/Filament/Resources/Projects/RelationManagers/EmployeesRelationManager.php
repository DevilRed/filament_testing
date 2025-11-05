<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('email')
            ->columns([
                TextColumn::make('first_name'),
                TextColumn::make('last_name'),
                TextColumn::make('email'),
                TextColumn::make('phone'),
                TextColumn::make('position'),
                TextColumn::make('salary'),
                TextColumn::make('project_id')
                    ->label('Project'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }

    public function form(Schema $schema): Schema
{
    return $schema
        ->components([
            TextInput::make('first_name')->required(),
            TextInput::make('last_name')->required(),
            TextInput::make('email')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
            TextInput::make('phone')->required()
                ->tel()
                ->maxLength(255),
            TextInput::make('position')->required(),
            TextInput::make('salary')
                ->required()
                ->numeric()
                ->prefix('$'),
            Select::make('project_id')
                ->relationship('project', 'name')
                ->default(fn (RelationManager $livewire): int => $livewire->getOwnerRecord()->id)
                ->disabled()
                ->dehydrated()
                ->required(),
        ]);
}
}
