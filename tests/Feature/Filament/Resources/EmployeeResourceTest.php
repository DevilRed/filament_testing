<?php

use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Filament\Resources\Employees\Pages\ListEmployees;
use App\Models\Employee;
use App\Models\Project;
use Livewire\Livewire;

use function Pest\Livewire\livewire;

describe('EmployeeResource', function() {
    it('can render employee list page', function () {
        $employees = Employee::factory()
            ->count(5)
            ->create();

        Livewire::test(ListEmployees::class)->assertCanSeeTableRecords($employees)
            ->assertSuccessful();
    });

    it('can render the create page', function() {
        Livewire::test(CreateEmployee::class)
            ->assertSuccessful();
    });

    it('can render the edit page', function() {
        $employee = Employee::factory()->create();
        Livewire::test(EditEmployee::class, ['record' => $employee->getRouteKey()])
            ->assertSuccessful();
    });

    it('can create an employee', function() {
        $project = Project::factory()->create();
        $newEmployee = Employee::factory()->for($project)->make()->toArray();
        Livewire::test((CreateEmployee::class))
            ->fillForm($newEmployee)
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertNotified()
            ->assertRedirect();

        $this->assertDatabaseHas('employees', [
            'first_name' => $newEmployee['first_name'],
            'last_name' => $newEmployee['last_name'],
            'email' => $newEmployee['email'],
            'phone' => $newEmployee['phone'],
            'position' => $newEmployee['position'],
            'salary' => $newEmployee['salary'],
            'project_id' => $project->id
        ]);
    });

    it('can update an employee', function() {
        $employee = Employee::factory()->create();
        $newData = [
            'first_name' => 'updated first_name',
            'last_name' => 'updated last_name',
            'email' => 'new.email@gmail.com',
            'phone' => '3214567890',
            'position' => 'positionNew',
            'salary' => '1000',
            'project_id' => null
        ];

        Livewire::test(EditEmployee::class, ['record' => $employee->id])
            ->fillForm($newData)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('employees', [
            'first_name' => $newData['first_name'],
            'last_name' => $newData['last_name'],
            'email' => $newData['email'],
            'phone' => $newData['phone'],
            'position' => $newData['position'],
            'salary' => $newData['salary'],
            'project_id' => $newData['project_id'],
        ]);
    });

    it('can delete an employee', function () {
        $employee = Employee::factory()->create();
        livewire(EditEmployee::class, ['record' => $employee->getRouteKey()])
            ->callAction('delete');

        $this->assertModelMissing($employee);
    });

    it('can search a given employee', function () {
        $employees = Employee::factory()->count(10)->create();
        $targetEmployee = $employees->first();

        livewire(ListEmployees::class)
            ->searchTable($targetEmployee->first_name)
            ->assertCanSeeTableRecords([$targetEmployee])
            ->assertCanNotSeeTableRecords($employees->skip($targetEmployee->id));
    });

    it('can filter employees by project', function () {
        $project1 = Project::factory()->create();
        $project2 = Project::factory()->create();
        $employeesCompany1 = Employee::factory()->for($project1)->count(2)->create();
        $employeesCompany2 = Employee::factory()->for($project2)->count(2)->create();

        livewire(ListEmployees::class)
            ->filterTable('project_id', $project1->getRouteKey())
            ->assertCanSeeTableRecords($employeesCompany1)
            ->assertCanNotSeeTableRecords($employeesCompany2);
    });

    it('can sort employee by first name', function () {
        $project = Project::factory()->create();
        $employees = Employee::factory()->for($project)->count(5)->create();
        livewire(ListEmployees::class)
            ->sortTable('first_name')
            ->assertCanSeeTableRecords($employees->sortBy('first_name'), inOrder: true);
    });
});
