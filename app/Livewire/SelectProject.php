<?php

namespace App\Livewire;

use App\Models\Project;
use Filament\Notifications\Notification;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SelectProject extends Component
{
    public $selectedProject;
    public $projects;

    public function mount()
    {
        // Load projects
        $this->projects = Project::all();

        // Initialize from query string if present
        $this->selectedProject = request('project');
    }

    public function updatedSelectedProject($value)
    {
        // Dispatch event to the table
        $this->dispatch('project-selected', projectId: $value);
    }

    public function render()
    {
        return view('livewire.select-project');
    }
}
