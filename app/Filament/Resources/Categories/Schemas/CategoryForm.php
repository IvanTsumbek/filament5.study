<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Group::make()->schema([

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
                            ->helperText('Generated automatically based on title'),

                        RichEditor::make('description')
                            ->fileAttachmentsDirectory("images/" . date('Y') . '/' . date('m')
                        . '/' . date('d')),
                    ]),
                ])->columnSpan(2),


                Group::make()->schema([

                    Section::make()->schema([

                        Select::make('parent_id')
                        ->options(function () {
                            return CategoryResource::getCategoriesTree(Category::all());
                        })
                        ->disableOptionWhen(function ($get, string $value) {
                            return $value == $get('id');
                        })
                        ->placeholder('Root category'),

                        FileUpload::make('photo')
                        ->image()
                        ->directory("preview/" . date('Y') . '/' . date('m')
                        . '/' . date('d'))

                    ]),
                ]),
            ])->columns(3);
    }
}
