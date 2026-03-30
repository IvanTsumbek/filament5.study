<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->striped()
            ->defaultPaginationPageOption(3)
            ->paginated([3, 5, 10, 20, 'all'])
            ->extremePaginationLinks()
            ->columns([
                TextColumn::make('my_id')
                    ->label('#')
                    ->state(function (HasTable $livewire, \stdClass $rowLoop) {
                        if ($livewire->getTableRecordsPerPage() == 'all') {
                            return $rowLoop->iteration;
                        }

                        return $rowLoop->iteration + ($livewire->getTableRecordsPerPage()
                            * ($livewire->getTablePage() - 1));
                    }),
                TextColumn::make('id')->label('ID')->sortable(),
                ImageColumn::make('photo'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('parent.title') //relation
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()
                        ->disabled(function ($record) {
                            return $record->children()->exists() || $record->products()->exists();
                        })
                        ->before(function ($record, $action) {
                            if ($record->children()->exists() || $record->products()->exists()) {
                                Notification::make()
                                    ->body('Forbidden')
                                    ->danger()
                                    ->send();
                                $action->cancel();
                            }
                        }),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
