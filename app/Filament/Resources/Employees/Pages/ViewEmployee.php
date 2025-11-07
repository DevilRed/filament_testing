<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->url(EmployeeResource::getUrl('index'))
                ->color('success')
                ->icon('heroicon-o-arrow-left')
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            'dashboard' => 'Home',
            'employees.index' => 'Employees',
            '' => $this->record->name,
        ];
    }
}
