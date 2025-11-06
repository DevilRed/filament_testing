<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

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
}
