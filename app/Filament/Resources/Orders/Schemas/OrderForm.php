<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make()
                    ->schema([


                        Step::make('Customer')
                            ->schema([

                                Select::make('user_id')
                                    ->label('Exists user')
                                    ->searchable()
                                    ->live()
                                    ->getSearchResultsUsing(function (string $search) {
                                        return User::query()
                                            ->whereLike('name', "%{$search}%")
                                            ->limit(10)
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    })
                                    ->getOptionLabelUsing(fn($value): ?string => User::find($value)?->name)
                                    ->afterStateUpdated(function ($state, $set) {
                                        $user = User::query()->find($state);

                                        $set('name', $user?->name);
                                        $set('email', $user?->email);
                                    }),

                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('address')
                                    ->required()
                                    ->maxLength(255),

                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                Textarea::make('note')
                                    ->columnSpanFull(),
                            ])->columns(),


                        Step::make('Products')
                            ->schema([

                                Repeater::make('products')
                                    ->relationship('orderProducts')
                                    ->collapsed(false)
                                    ->schema([

                                        Select::make('product_id')
                                            ->label('Search product')
                                            ->searchable()
                                            ->live()
                                            ->getSearchResultsUsing(function (string $search) {
                                                return Product::query()
                                                    ->whereLike('title', "%{$search}%")
                                                    ->limit(10)
                                                    ->pluck('title', 'id')
                                                    ->toArray();
                                            })
                                            ->getOptionLabelUsing(fn($value): ?string => Product::find($value)?->title)
                                            ->afterStateUpdated(function ($state, $set) {
                                                $product = Product::query()->find($state);

                                                $set('title', $product?->title);
                                                $set('slug', $product?->slug);
                                                $set('price', $product?->price);
                                                $set('photo', $product?->photo);
                                            }),

                                        TextInput::make('quantity')
                                            ->required()
                                            ->numeric()
                                            ->default(1),

                                        TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->disabled()
                                            ->dehydrated(), //after disabled

                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->disabled()
                                            ->dehydrated(), //after disabled

                                        TextInput::make('price')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(), //after disabled

                                        TextInput::make('photo')
                                            ->maxLength(255)
                                            ->disabled()
                                            ->dehydrated(), //after disabled

                                    ])->columnSpanFull()->columns(),


                                TextInput::make('shipping')
                                    ->required()
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('discount')
                                    ->required()
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('total')
                                    ->required()
                                    ->numeric(),

                            ])->columns(),

                    ])->columnSpanFull(),
            ]);
    }
}
