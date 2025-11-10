<?php

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use Livewire\Livewire;
use App\Models\Project;

use function Pest\Livewire\livewire;

it('can render projects list page', function () {
    $projects = Project::factory()
        ->count(3)
        ->create();
    Livewire::test(ListProjects::class)->assertCanSeeTableRecords(
        $projects
    )
    ->assertSuccessful();
});

it('can render the create page', function() {
    livewire(CreateProject::class)
    ->assertSuccessful();
});

it('can render the edit page', function() {
    $project = Project::factory()->create();
    livewire(EditProject::class, ['record' => $project->id])
        ->assertSuccessful();
});
