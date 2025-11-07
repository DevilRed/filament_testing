<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardStats;
use BackedEnum;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            DashboardStats::class,
        ];
    }
}
