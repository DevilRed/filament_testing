<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Project Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'pending' => 'gray',
                        default => 'white'
                    })
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'pending' => 'Pending',
                    ]),
                TernaryFilter::make('has_employees')
                    ->label('Has Employees')
                    ->queries(
                        true: fn($query) => $query->whereHas('employees'),
                        false: fn($query) => $query->whereDoesntHave('employees'),
                    ),
            ])
            ->recordActions([
                ActionGroup::make([
                    // View in Store action
                    Action::make('viewInStore')
                        ->label('View in Store')
                        ->icon('heroicon-o-shopping-cart')
                        ->url(fn(Project $record): string => static::getStoreUrl($record))
                        ->openUrlInNewTab(),
                ]),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                // Duplicate action
                Action::make('duplicate')
                    ->label('Duplicate project')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(fn(Project $record) => static::duplicateProject($record))
                    ->requiresConfirmation()
                    ->modalHeading('Duplicate Project')
                    ->modalDescription('Are you sure you want to duplicate this project? This will create a new project with the same details.')
                    ->modalSubmitActionLabel('Yes, duplicate it'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('exportSelectedCsv')
                        ->label('Export to CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn (Collection $records) => static::exportToCsv($records))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->headerActions([
                Action::make('exportAllCsv')
                    ->label('Export all to CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn($livewire) => static::exportAllToCsv($livewire)),
            ]);
    }
    /**
     * Duplicate a project record
     */
    public static function duplicateProject(Project $original): void
    {
        try {
            $newProject = $original->replicate();
            $newProject->name = $original->name . ' (Copia)';
            $newProject->save();

            Notification::make()
                ->title('Project Duplicated')
                ->body('Project has been duplicated successfully.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to duplicate project: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public static function exportToCsv(Collection $records): void
    {
        $fileName = 'projects-' . now()->format('Y-m-d-H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        $handle = fopen('php://output', 'w');

        // Add BOM for UTF-8
        fwrite($handle, "\xEF\xBB\xBF");

        // CSV headers
        fputcsv($handle, ['Name', 'Description', 'Status']);

        // data rows
        foreach ($records as $record) {
            fputcsv($handle, [
                $record->name,
                $record->description,
                $record->status,
            ]);
        }

        fclose($handle);
        exit;
    }

    public static function exportAllToCsv($livewire): void
    {
        $query = $livewire->getFilteredTableQuery();
        $records = $query->withCount('employees')->get();

        $fileName = 'projects-all-' . now()->format('Y-m-d-H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        $handle = fopen('php://output', 'w');

        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, ['Name', 'Description', 'Status']);

        foreach ($records as $record) {
            fputcsv($handle, [
                $record->name,
                $record->description,
                $record->status,
            ]);
        }

        fclose($handle);
        exit;
    }

    public static function getStoreUrl(Project $record): string
    {
        return url('/store/projects/' . $record->id);
    }
}
