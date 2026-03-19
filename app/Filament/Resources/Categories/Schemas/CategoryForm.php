<?php

namespace App\Filament\Resources\Categories\Schemas;

use Dom\Text;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
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
                            ->minLength(5)
                            ->live(true)
                            ->afterStateUpdated(
                                function ($set, ?string $state, string $operation) {
                                    if ($operation === 'edit') {
                                        return;
                                    }
                                    $set('slug', Str::slug($state));
                                }
                            ),

                        TextInput::make('slug')
                            // ->hidden(function ($get) {
                            //     return !$get('title');
                            // })
                            // ->hidden(fn ($get): bool => !$get('title')) //second solution
                            ->required()
                            ->unique()
                            ->helperText('Генерируется автоматически на основе наименования')
                            ->disabledOn('edit'),
                        RichEditor::make('content')->columnSpan(2)->required(),
                    ])->columns(2),
                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make()->schema([
                        FileUpload::make('image')
                            ->image()
                            ->directory("preview/" . date('Y') . '/' . date('m') . '/' . date('d')),
                    ]),
                ]),





                //Wizard
                // Wizard::make([

                //     Step::make('Основное')->icon('heroicon-o-user')->schema([
                //         TextInput::make('first_name')->required(),
                //         TextInput::make('middle_name'),
                //         TextInput::make('last_name'),
                //         TextInput::make('email')->email(),
                //         TextInput::make('password')->password()->revealable()->columnSpanFull(),
                //     ])->columns(2),

                //     Step::make('Контакты')->icon('heroicon-o-map')->schema([
                //         Select::make('country')->options(['Country 1', 'Country 2', 'Country 3']),
                //         Select::make('city')->options(['City 1', 'City 2', 'City 3']),
                //         Select::make('street')->options(['Street 1', 'Street 2', 'Street 3']),
                //         TextInput::make('zip')->required(),
                //         TextInput::make('phone')->tel()->mask('+99 999 999-99-99')
                //     ])->columns(2),

                //     Step::make('Дополнительно')->icon('heroicon-o-user')->schema([
                //         Select::make('dob')->options(
                //             array_combine(
                //                 range(date('Y'), 1900),
                //                 range(date('Y'), 1900)
                //             )
                //         ),
                //         Radio::make('gender')->options(['male', 'female']),
                //     ])->columns(2),

                //     Step::make('Аватар и примечание')->icon('heroicon-o-user')->schema([
                //         FileUpload::make('avatar')->image(),
                //         Textarea::make('notes')->rows(3),
                //     ])->columns(2),
                // ]),



                //TABs
                // Tabs::make()->tabs([

                //     Tab::make('Основное')->icon('heroicon-o-user')->schema([
                //         TextInput::make('first_name')->required(),
                //         TextInput::make('middle_name'),
                //         TextInput::make('last_name'),
                //         TextInput::make('email')->email(),
                //         TextInput::make('password')->password()->revealable()->columnSpanFull(),
                //     ])->columns(2),

                //     Tab::make('Контакты')->icon('heroicon-o-map')->schema([
                //         Select::make('country')->options(['Country 1', 'Country 2', 'Country 3']),
                //         Select::make('city')->options(['City 1', 'City 2', 'City 3']),
                //         Select::make('street')->options(['Street 1', 'Street 2', 'Street 3']),
                //         TextInput::make('zip')->required(),
                //         TextInput::make('phone')->tel()->mask('+99 999 999-99-99')
                //     ])->columns(2),

                //     Tab::make('Дополнительно')->icon('heroicon-o-user')->schema([
                //         Select::make('dob')->options(
                //             array_combine(
                //                 range(date('Y'), 1900),
                //                 range(date('Y'), 1900)
                //             )
                //         ),
                //         Radio::make('gender')->options(['male', 'female']),
                //     ])->columns(2),

                //     Tab::make('Аватар и примечание')->icon('heroicon-o-user')->schema([
                //         FileUpload::make('avatar')->image(),
                //         Textarea::make('notes')->rows(3),
                //     ])->columns(2),
                // ])


                //Groups
                // Group::make()->schema([

                //     Section::make('Основное')
                //         ->description('Основная информация о пользователе')
                //         ->icon('heroicon-o-user')
                //         ->schema([
                //             TextInput::make('first_name'),
                //             TextInput::make('middle_name'),
                //             TextInput::make('last_name'),
                //             TextInput::make('email')->email(),
                //             TextInput::make('password')->password()->revealable()
                //                 ->columnSpan('full'),
                //         ])->columnSpanFull()->columns(2)->collapsible(),

                //     Section::make('Контакты')
                //         ->description('Контактная информация пользователя')
                //         ->icon('heroicon-o-map')
                //         ->schema([
                //             Select::make('country')->options(['Country 1', 'Country 2', 'Country 3']),
                //             Select::make('city')->options(['City 1', 'City 2', 'City 3']),
                //             Select::make('street')->options(['Street 1', 'Street 2', 'Street 3']),
                //             TextInput::make('zip'),
                //             TextInput::make('phone')->tel()->mask('+99 999 999-99-99')
                //         ])->columnSpanFull()->columns(2)->collapsible()

                // ])->columnSpan(2),

                // Group::make()->schema([

                //     Section::make('Дополнительно')
                //         ->description('Дополнительная информация о пользователе')
                //         ->icon('heroicon-o-user')
                //         ->schema([
                //             Select::make('dob')->options(
                //                 array_combine(
                //                     range(date('Y'), 1900),
                //                     range(date('Y'), 1900)
                //                 )
                //             ),
                //             Radio::make('gender')->options(['male', 'female'])->inline()->inlineLabel(false),
                //         ])->columnSpanFull()->collapsible(),

                //     Section::make('Аватар')
                //         ->description('И еще немного')
                //         ->icon('heroicon-o-user')
                //         ->schema([
                //             FileUpload::make('avatar')->image(),
                //         ])->columnSpanFull()->collapsible()->collapsed(),


                //     Section::make('Примечание')
                //         ->description('И еще чуть-чуть')
                //         ->icon('heroicon-o-user')
                //         ->schema([
                //             Textarea::make('notes')->rows(4),
                //         ])->columnSpanFull()->collapsible()->collapsed(),
                // ]),


                //Other good fetures
                // TextInput::make('title')
                //     ->default('Test article')
                //     ->helperText(new HtmlString('Helper text for <strong>title</strong>'))
                //     ->hint('Hint for title')
                //     ->hintIcon('heroicon-m-language', 'Tooltip for title')
                //     ->hintColor('primary')
                //     // ->disabled()
                //     ->disabledOn('edit')
                //     ->hiddenOn('edit')
                //     ->autofocus()
                //     ->label('Наименование')
                //     ->required()
                //     ->columnSpan(2),
                // TextInput::make('slug')
                //     ->required(),
                // // TextInput::make('email')->email(),
                // Select::make('status')->options([
                //     'Draft',
                //     'Published',
                //     'Reviewing'
                // ])
                //     // ->native(false)
                //     // ->searchable()
                //     ->multiple(),
                // FileUpload::make('image')
                //     // ->disk('public_uploads')
                //     ->directory("preview/" . date('Y') . '/' . date('m') . '/' . date('d'))
                //     ->imageEditor()
                //     ->imageEditorAspectRatioOptions([
                //         null,
                //         '16:9',
                //         '4:3',
                //         '1:1',
                //     ])
                //     ->multiple()
                //     ->reorderable()
                //     ->acceptedFileTypes(['image/png', 'image/jpeg'])
                //     ->columnSpan(2)
                // // ->image()
                // ,
                // TextInput::make('domain')
                //     ->prefix('https://')
                //     ->suffix('.com')
                //     ->suffixIcon('heroicon-m-globe-alt')
                //     ->suffixIconColor('success'),
                // DatePicker::make('published_at')
                //     ->native(false)
                //     ->displayFormat('d M Y')                   //то, как отображаем
                //     ->locale('ru')
                //     ->format('Y-m-d')                          //то, что летит на сервер
                //     ->minDate(now()->subDays(7))
                //     ->maxDate(now()->addDays(7))
                //     ->closeOnDateSelection(),                 //автозакрывание календаря                      
                // TextInput::make('email')->type('email'),  //аналог метода выше
                // TextInput::make('password')->password()->revealable(),
                // TextInput::make('phone')->tel()->placeholder('+xx xxx xxx-xx-xx')->mask('+99 999 999-99-99'),

                // RichEditor::make('description')
                //     ->columnSpan(2),

                // Repeater::make('users')
                //     ->schema([
                //         TextInput::make('name')->required()->live(),
                //         Select::make('role')
                //             ->options([
                //                 'users' => 'User',
                //                 'manager' => 'Manager',
                //                 'admin' => 'Admin',
                //             ])
                //             ->required(),
                //     ])
                //     ->addActionLabel('Добавить пользователя')  //смена надписи кнопки
                //     ->cloneable()           //кнопка клонирования блока
                //     ->itemLabel(fn(array $state): ?string => $state['name'] ?? null) //надпись блока
                //     ->collapsible()         //сворачивание блока
                //     ->columns(2)
                //     ->columnSpan(2),

                // KeyValue::make('meta')->columnSpan(2),

                // Builder::make('content2')
                //     ->blocks([
                //         Block::make('heading')
                //             ->schema([
                //                 TextInput::make('content')
                //                     ->label('Heading')
                //                     ->required(),
                //                 Select::make('level')
                //                     ->options([
                //                         'h1' => 'Heading 1',
                //                         'h2' => 'Heading 2',
                //                         'h3' => 'Heading 3',
                //                         'h4' => 'Heading 4',
                //                         'h5' => 'Heading 5',
                //                         'h6' => 'Heading 6',
                //                     ])
                //                     ->required(),
                //             ])
                //             ->columns(2),
                //         Block::make('paragraph')
                //             ->schema([
                //                 Textarea::make('content')
                //                     ->label('Paragraph')
                //                     ->required(),
                //             ]),
                //         Block::make('image')
                //             ->schema([
                //                 FileUpload::make('url')
                //                     ->label('Image')
                //                     ->image()
                //                     ->required(),
                //                 TextInput::make('alt')
                //                     ->label('Alt text')
                //                     ->required(),
                //             ]),
                //     ])->columnSpan(2),

                // Textarea::make('content')
                //     ->required()
                //     ->columnSpanFull(),

                // ])->columns(3);
            ])->columns(3);
    }
}
