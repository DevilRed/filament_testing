<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->minLength(2)
                    ->maxLength(100),
                Textarea::make('description')
                    ->label('Description')
                    ->required(),
                Select::make('status')
                    ->options(([
                        'pending' => 'Pending',
                        'active' => 'Active',
                    ]))
                    ->label('Status - select an option')
                    ->default('pending')
            ]);
    }
}
