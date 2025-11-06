<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                // Get the current Livewire component
                $livewire = \Livewire\Livewire::current();

                // Check if selectedProject property exists and has a value
                if ($livewire && property_exists($livewire, 'selectedProject') && $livewire->selectedProject) {
                    $query->where('project_id', $livewire->selectedProject);
                }

                return $query;
            })
            ->columns([
            TextColumn::make('first_name')
                ->icon('heroicon-o-user')
                ->iconColor('primary')
                ->searchable()
                ->sortable(),
            TextColumn::make('last_name')
                ->icon('heroicon-o-user')
                ->iconColor('primary')
                ->searchable()
                ->sortable(),
            TextColumn::make('email')
                ->icon('heroicon-o-envelope')
                ->iconColor('info')
                ->copyable()
                ->copyMessage('Email copied!')
                ->searchable(),
                TextColumn::make('phone')
                ->icon('heroicon-o-phone')
                ->iconColor('success')
                ->searchable(),
                TextColumn::make('position')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'Manager' => 'success',
                    'Developer' => 'info',
                    'Designer' => 'warning',
                    'Analyst' => 'primary',
                    default => 'gray',
                })
                ->icon(fn(string $state): string => match ($state) {
                    'Manager' => 'heroicon-o-briefcase',
                    'Developer' => 'heroicon-o-code-bracket',
                    'Designer' => 'heroicon-o-paint-brush',
                    'Analyst' => 'heroicon-o-chart-bar',
                    default => 'heroicon-o-user',
                })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('salary')
                ->money('USD')
                ->sortable()
                ->color(fn($state): string => match (true) {
                    $state >= 7000 => 'success',
                    $state >= 4000 => 'warning',
                    default => 'danger',
                })
                ->icon('heroicon-o-currency-dollar')
                ->weight('bold'),
            TextColumn::make('project.name')
                ->label('Project')
                ->badge()
                ->color('primary')
                ->icon('heroicon-o-folder')
                ->placeholder('No Project')
                ->searchable()
                ->sortable(),
            ])
            ->filters([
            SelectFilter::make('project_id')
                ->label('Project')
                ->relationship('project', 'name')
                ->preload()
                ->searchable(),
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
                EditAction::make()
                    ->schema([
                        TextInput::make('first_name'),
                        TextInput::make('last_name'),
                        TextInput::make('email'),
                        TextInput::make('phone'),
                        TextInput::make('position'),
                        TextInput::make('salary'),
                        Select::make('project_id')
                            ->relationship('project', 'name')
                    ->default(fn(RelationManager $livewire): int => $livewire->getOwnerRecord()->id)
                            ->dehydrated()
                            ->required(),
                    ]),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
