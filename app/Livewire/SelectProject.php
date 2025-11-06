<?php

namespace App\Livewire;

use App\Models\Project;
use Filament\Notifications\Notification;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SelectProject extends Component
{
    public $setSelectedProject;

    public function updated(string $property): void {}

    public function render()
    {
        $projects = Project::select('name')->get();
        return view('livewire.select-project', [
            'projects' => $projects
        ]);
    }
}
