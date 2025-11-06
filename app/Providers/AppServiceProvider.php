<?php

namespace App\Providers;

use App\Filament\Resources\Employees\Pages\ListEmployees;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::registerRenderHook(
            'tables::toolbar.start',
            fn(): string => $this->shouldRenderSelectProject()
                ? Blade::render('@livewire(\'select-project\')')
                : '',
        );
    }

    protected function shouldRenderSelectProject(): bool
    {
        $routeName = request()->route()?->getName();

        return $routeName === 'filament.admin.resources.employees.index';
    }
}
