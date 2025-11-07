<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Projects', '24')
                ->description('All projects in system')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Total Employees', '156')
                ->description('All team members')
                ->descriptionIcon('heroicon-o-users')
                ->color('success')
                ->chart([15, 4, 10, 2, 12, 4, 12]),

            Stat::make('Active Projects', '18')
                ->description('Currently running projects')
                ->descriptionIcon('heroicon-o-play-circle')
                ->color('success')
                ->chart([5, 3, 8, 2, 7, 4, 8]),

            Stat::make('Pending Projects', '6')
                ->description('Awaiting approval or start')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart([2, 1, 3, 1, 4, 2, 3]),

            Stat::make('New Employees (30d)', '12')
                ->description('Hired in last 30 days')
                ->descriptionIcon('heroicon-o-user-plus')
                ->color('info')
                ->chart([1, 2, 1, 3, 2, 1, 2]),

            Stat::make('Projects without Team', '3')
                ->description('Need team assignment')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->chart([0, 1, 0, 2, 0, 1, 1]),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}
