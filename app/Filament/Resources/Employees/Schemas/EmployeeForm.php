<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
//use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
// use Filament\Forms\Components\Section;
use Filament\Schemas\Components\Tabs;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Tabs::make('Employee Information')
                ->tabs([
                    // Tab 1: General Information
                    Tabs\Tab::make('General Information')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Section::make('Personal Details')
                                ->description('Basic employee information')
                                ->schema([
                        TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->minLength(2)
                            ->maxLength(50)
                            ->columnSpan(1),

                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->minLength(2)
                            ->maxLength(50)
                            ->columnSpan(1),

                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->email()
                            ->maxLength(150)
                            ->unique(Employee::class, 'email', ignoreRecord: true)
                            ->prefixIcon('heroicon-o-envelope')
                            ->columnSpan(1),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->required()
                            ->tel()
                            ->unique(Employee::class, 'phone', ignoreRecord: true)
                            ->rules(['required', 'digits:10', 'numeric'])
                            ->helperText('Enter a 10 digit phone number (numbers only, no spaces or symbols)')
                                    ->validationAttribute('phone_number')
                                    ->prefixIcon('heroicon-o-phone')
                                    ->columnSpan(1),
                            ])
                            ->columns(2),
                    ]),

                // Tab 2: Employment Details
                Tabs\Tab::make('Employment Details')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        Section::make('Job Information')
                            ->description('Position and compensation details')
                            ->schema([
                                TextInput::make('position')
                                    ->label('Job Title')
                                    ->required()
                                    ->minLength(4)
                            ->maxLength(150)
                            ->prefixIcon('heroicon-o-identification')
                            ->columnSpan(2),

                        TextInput::make('salary')
                            ->label('Salary')
                            ->required()
                            ->numeric()
                            ->minValue(1000)
                            ->maxValue(9999.99)
                            ->step(0.01)
                            ->placeholder('5000.00')
                            ->helperText('Enter a salary between 1000.00 and 9999.99')
                                        ->prefix('$')
                                        ->prefixIcon('heroicon-o-currency-dollar')
                                        ->columnSpan(2),

                                    Select::make('project_id')
                                        ->label('Assigned Project')
                                        ->relationship('project', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Select a project')
                                        ->prefixIcon('heroicon-o-folder')
                                        ->columnSpan(2),
                                ])
                                ->columns(2),
                        ]),
                ])
                ->columnSpanFull()
                ->persistTabInQueryString()
                ->activeTab(1),
            ]);
    }
}
