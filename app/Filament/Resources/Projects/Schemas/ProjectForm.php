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
                TextInput::make('name'),
                Textarea::make('description'),
                Select::make('status')
                    ->options(([
                        'pending' => 'Pending',
                        'active' => 'Active',
                    ]))
            ]);
    }
}
