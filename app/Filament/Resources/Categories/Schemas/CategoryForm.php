<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->default('Test article')
                    ->helperText(new HtmlString('Helper text for <strong>title</strong>'))
                    ->hint('Hint for title')
                    ->hintIcon('heroicon-m-language', 'Tooltip for title')
                    ->hintColor('primary')
                    // ->disabled()
                    ->disabledOn('edit')
                    ->hiddenOn('edit')
                    ->autofocus()
                    ->label('Наименование')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('slug')
                    ->required(),
                // TextInput::make('email')->email(),
                Select::make('status')->options([
                    'Draft', 'Published', 'Reviewing'
                ])
                // ->native(false)
                // ->searchable()
                ->multiple(),
                DatePicker::make('published_at')
                ->native(false)
                ->displayFormat('d M Y')                   //то, как отображаем
                ->locale('ru')
                ->format('Y-m-d')                          //то, что летит на сервер
                ->minDate(now()->subDays(7))
                ->maxDate(now()->addDays(7))
                ->closeOnDateSelection(),                 //автозакрывание календаря                      
                TextInput::make('email')->type('email'),  //аналог метода выше
                TextInput::make('password')->password()->revealable(),
                TextInput::make('phone')->tel()->placeholder('+xx xxx xxx-xx-xx')->mask('+99 999 999-99-99'),
                TextInput::make('domain')
                    ->prefix('https://')
                    ->suffix('.com')
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->suffixIconColor('success'),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
            ])
            ->columns(2);
    }
}
