<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Group::make([

                    Section::make()->schema([

                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(true)
                            ->afterStateUpdated(function (
                                $set,
                                $get,
                                ?string $state,
                                string $operation
                            ) {
                                if ($operation === 'edit' && $get('slug')) {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->hint('Generated automatically based on title'),

                        Select::make('category_id')
                            ->live()
                            ->options(function () {
                                return Category::getCategoriesTree(Category::all());
                            })
                            ->placeholder('Select category')
                            ->exists(table: Category::class, column: 'id')
                            ->required(),

                        Select::make('brand_id')
                            ->placeholder('Select brand')
                            ->options(Brand::all()->pluck('title', 'id')),


                        Textarea::make('excerpt')
                            ->maxLength(255)
                            ->default(null)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->fileAttachmentsDirectory("images/"  . date('Y') . '/' . date('m')
                                . '/' . date('d'))
                            ->columnSpanFull(),

                    ])->columns(2),
                ])->columnSpan(2),


                Group::make([

                    Section::make()->schema([

                        Select::make('filter_id')
                            ->label('Select filters')
                            ->relationship('filters', 'title')
                            ->multiple()
                            ->searchable()
                            ->preload('Start typing filter title')
                            ->options(function ($get) {
                                $categoryId = $get('category_id');

                                if (!$categoryId) {
                                    return [];
                                }

                                return ProductResource::getFiltersByCategory($categoryId);
                            })
                            ->columnSpanFull(),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->default(1),

                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->default(0),

                        TextInput::make('old_price')
                            ->required()
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_visible')
                            ->default(true)
                            ->required(),

                        Toggle::make('is_featured')
                            ->required(),

                        Toggle::make('is_hit')
                            ->required(),

                        Toggle::make('is_sale')
                            ->required(),

                    ])->columns(2),


                    Section::make('Photos')->schema([

                        FileUpload::make('photo')
                            ->image()
                            ->directory("preview/" . date('Y') . '/' . date('m')
                                . '/' . date('d')),

                        FileUpload::make('photos')
                            ->image()
                            ->multiple()
                            ->directory("preview/" . date('Y') . '/' . date('m')
                                . '/' . date('d')),

                    ])->collapsible(),

                ]),
            ])->columns(3);
    }
}
