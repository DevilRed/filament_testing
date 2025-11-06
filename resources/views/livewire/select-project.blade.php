<x-filament::input.wrapper>
    <x-filament::input.select wire:model.live="selectedProject">
        <option value="all">All projects</option>
        @foreach ($projects as $project)
            <option value={{ $project->id }}>{{  $project->name }}</option>
        @endforeach
    </x-filament::input.select>
</x-filament::input.wrapper>
