<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\Employee;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;
// use Filament\Resources\Components\Tab;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;
    public $selectedProject = null;

    protected static ?string $title = 'All Employees';
    protected ?string $heading = 'Employee List';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->createAnother(false),
        ];
    }

    #[On('project-selected')]
    public function updateProjectFilter($projectId)
    {
        $this->selectedProject = $projectId;

        // Reset the table to apply the new filter
        $this->resetTable();
    }

    // Add tabs for quick filtering
    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All Employees')
                ->badge(Employee::count())
                ->icon('heroicon-o-users'),
        ];

        $tabs['separator'] = Tab::make('── Filter by Position ──')
            ->disabled();
        // Get unique positions
        $positions = Employee::select('position')
            ->distinct()
            ->whereNotNull('position')
            ->pluck('position');

        foreach ($positions as $position) {
            $tabs[Str::slug($position)] = Tab::make($position)
                ->modifyQueryUsing(fn(Builder $query) => $query->where('position', $position))
                ->badge(Employee::where('position', $position)->count());
        }

        return $tabs;
    }
}
