<?php

namespace App\Filament\Resources\Categories\Tables;

use BladeUI\Icons\Components\Icon;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')        //по какому полю сортировка
            ->defaultPaginationPageOption(5)  //дефолтное значение пагинации на странице
            ->extremePaginationLinks()   //кнопки пагинации на крайние страницы
            ->striped()   //таблица станет полосатой
            ->searchPlaceholder('Search by title & slug')  //плейсхолдер строки поиска
            ->searchDebounce('1000ms') //задержка строки поиска
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                ImageColumn::make('image')
                    ->toggleable(),

                ColumnGroup::make('Title & Slug', [
                    TextColumn::make('title')->sortable()->searchable(),
                    TextColumn::make('slug')
                        ->toggleable()
                        ->sortable()
                        ->searchable(isIndividual: true) //поиск только по слагу
                        ->copyable()   //возможность копирования содержимого кликом
                        ->tooltip('click for copy')   //подсказка возможности копирования
                        ->label('Slug (click for copy)')
                ]),

                IconColumn::make('is_featured')->boolean()->sortable(),
                // ToggleColumn::make('is_featured')
                //     ->afterStateUpdated(function () {
                //         Notification::make()->title('Saved')->success()->send();
                //     }),


                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
