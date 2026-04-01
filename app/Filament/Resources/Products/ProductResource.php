<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-table-cells';

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getFiltersByCategory($categoryId)
    {
        $groupFilters = DB::table('category_filter_group')
            ->where('category_filter_group.category_id', $categoryId)
            ->selectRaw('
                distinct category_filter_group.filter_group_id,
                filter_groups.title as group_title,
                filters.id as filter_id,
                filters.title as filter_title
            ')
            ->join('filter_groups', 'category_filter_group.filter_group_id', '=', 'filter_groups.id')
            ->join('filters', 'filters.filter_group_id', '=', 'filter_groups.id')
            ->get();

        $filter_groups = [];

        foreach ($groupFilters as $filter) {
            $filter_groups["{$filter->filter_group_id} - {$filter->group_title}"][$filter->filter_id] = $filter->filter_title;
        }

        return $filter_groups;
    }
}
