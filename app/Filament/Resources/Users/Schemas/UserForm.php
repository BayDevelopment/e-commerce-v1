<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi User')
                ->columns(2)
                ->schema([

                    TextInput::make('name')
                        ->required()
                        ->maxLength(100)
                        ->placeholder('Masukan nama/username'),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->placeholder('Masukan email')
                        ->unique(ignoreRecord: true),

                    Select::make('role')
                        ->required()
                        ->options([
                            'admin' => 'Admin',
                            'customer' => 'Customer',
                        ])
                        ->default('customer'),

                ]),

            Section::make('Keamanan')
                ->schema([

                    Grid::make(2)->schema([

                        TextInput::make('password')
                            ->password()
                            ->minLength(8)
                            ->placeholder('Masukan password')
                            ->autocomplete('new-password')
                            ->required(fn(string $operation) => $operation === 'create')
                            ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->helperText('Kosongkan saat edit jika tidak ingin mengganti password.'),

                    ]),

                ]),
        ]);
    }
}
