<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('salary')
                    ->money('usd')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project.name')
                    ->searchable()
                    ->sortable()
                    ->label('Project'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->schema([
                        TextInput::make('first_name'),
                        TextInput::make('last_name'),
                        TextInput::make('email'),
                        TextInput::make('phone'),
                        TextInput::make('position'),
                        TextInput::make('salary'),
                        TextInput::make('project_id')
                            ->label('Project'),
                    ]),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
