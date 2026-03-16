<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                    'Draft',
                    'Published',
                    'Reviewing'
                ])
                    // ->native(false)
                    // ->searchable()
                    ->multiple(),
                FileUpload::make('image')
                    // ->disk('public_uploads')
                    ->directory("preview/" . date('Y') . '/' . date('m') . '/' . date('d'))
                    ->imageEditor()
                    ->imageEditorAspectRatioOptions([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->multiple()
                    ->reorderable()
                    ->acceptedFileTypes(['image/png', 'image/jpeg'])
                    ->columnSpan(2)
                // ->image()
                ,
                TextInput::make('domain')
                    ->prefix('https://')
                    ->suffix('.com')
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->suffixIconColor('success'),
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

                RichEditor::make('description')
                    ->columnSpan(2),

                Repeater::make('users')
                    ->schema([
                        TextInput::make('name')->required()->live(),
                        Select::make('role')
                            ->options([
                                'users' => 'User',
                                'manager' => 'Manager',
                                'admin' => 'Admin',
                            ])
                            ->required(),
                    ])
                    ->addActionLabel('Добавить пользователя')  //смена надписи кнопки
                    ->cloneable()           //кнопка клонирования блока
                    ->itemLabel(fn(array $state): ?string => $state['name'] ?? null) //надпись блока
                    ->collapsible()         //сворачивание блока
                    ->columns(2)
                    ->columnSpan(2),

                KeyValue::make('meta')->columnSpan(2),

                Builder::make('content2')
                    ->blocks([
                        Block::make('heading')
                            ->schema([
                                TextInput::make('content')
                                    ->label('Heading')
                                    ->required(),
                                Select::make('level')
                                    ->options([
                                        'h1' => 'Heading 1',
                                        'h2' => 'Heading 2',
                                        'h3' => 'Heading 3',
                                        'h4' => 'Heading 4',
                                        'h5' => 'Heading 5',
                                        'h6' => 'Heading 6',
                                    ])
                                    ->required(),
                            ])
                            ->columns(2),
                        Block::make('paragraph')
                            ->schema([
                                Textarea::make('content')
                                    ->label('Paragraph')
                                    ->required(),
                            ]),
                        Block::make('image')
                            ->schema([
                                FileUpload::make('url')
                                    ->label('Image')
                                    ->image()
                                    ->required(),
                                TextInput::make('alt')
                                    ->label('Alt text')
                                    ->required(),
                            ]),
                    ])->columnSpan(2),

                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
