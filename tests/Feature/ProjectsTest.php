<?php

use App\Filament\Resources\Projects\Pages\ListProjects;
use Livewire\Livewire;
use App\Models\Project;

it('has projects page', function () {
    $projects = Project::factory()
        ->count(3)
        ->create();
    Livewire::test(ListProjects::class)->assertCanSeeTableRecords(
        $projects
    );
});
