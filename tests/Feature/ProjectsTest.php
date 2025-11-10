<?php

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use Livewire\Livewire;
use App\Models\Project;
use Filament\Actions\Testing\TestAction;

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

it('can update a project', function() {
    $project = Project::factory()->create();
    $newData = [
        'name' => 'Updated name',
        'description' => 'Updated description',
        'status' => 'active'
    ];

    livewire(EditProject::class, ['record' => $project->id])
        ->fillForm($newData)
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('projects', [
        'name' => $newData['name'],
        'description' => $newData['description'],
        'status' => $newData['status'],
    ]);
});

it('can delete a project', function () {
    $project = Project::factory()->create();
    livewire(EditProject::class, ['record' => $project->getRouteKey()])
        ->callAction('delete');

    $this->assertModelMissing($project);
});

it('can search a given project', function() {
    $projects = Project::factory()->count(10)->create();
    $targetProject = $projects->first();

    livewire(ListProjects::class)
        ->searchTable($targetProject->name)
        ->assertCanSeeTableRecords([$targetProject])
        ->assertCanNotSeeTableRecords($projects->skip($targetProject->id));
});

it('can filter projects by status', function () {
    $activeProjects = Project::factory()->count(10)->create(['status' => 'active']);
    $pendingProjects = Project::factory()->count(10)->create(['status' => 'pending']);

    livewire(ListProjects::class)
        ->filterTable('status', 'active')
        ->assertCanSeeTableRecords($activeProjects)
        ->assertCanNotSeeTableRecords($pendingProjects);
});

it('can sort projects by name', function() {
    $projects = Project::factory()->count(5)->create();
    livewire(ListProjects::class)
        ->sortTable('name')
        ->assertCanSeeTableRecords($projects->sortBy('name'), inOrder: true);
});

it('can bulk delete projects', function () {
    $projects = Project::factory()->count(5)->create();

    // call bulkAction for filament v4.2.0
    livewire(ListProjects::class)
        ->selectTableRecords($projects)
        ->call('mountTableBulkAction', 'delete')
        ->call('callMountedTableBulkAction')
        ->assertSuccessful();

    foreach ($projects as $project) {
        $this->assertModelMissing($project);
    }
});
