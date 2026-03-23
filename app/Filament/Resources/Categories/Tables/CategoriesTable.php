<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Filament\Exports\CategoryExporter;
use App\Filament\Imports\CategoryImporter;
use App\Models\Category;
use BladeUI\Icons\Components\Icon;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\View;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
            ->headerActions([
                ExportAction::make()
                    ->exporter(CategoryExporter::class),
                ImportAction::make()
                    ->importer(CategoryImporter::class)
                    ->csvDelimiter(';'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    ViewAction::make()->color('info'),
                    ReplicateAction::make()
                        ->excludeAttributes(['slug'])
                        ->successRedirectUrl(fn(Model $replica) => route(
                            'filament.admin.resources.categories.edit',
                            $replica
                        )),
                    // admin/categories/{record}/edit filament.admin.resources.categories.edit
                ])


                // ->record()
                // Action::make('delete')
                //     ->requiresConfirmation()
                //     ->action(fn(Category $record) => $record->delete()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
