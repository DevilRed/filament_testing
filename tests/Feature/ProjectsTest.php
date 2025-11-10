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

it('can create a project', function () {
    // Use factory to produce a valid payload that matches the app expectations
    $newProject = Project::factory()->make()->toArray();
    livewire(CreateProject::class)
        ->fillForm($newProject)
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas('projects', [
        'name' => $newProject['name'],
        'description' => $newProject['description'],
        'status' => $newProject['status'],
    ]);
});
