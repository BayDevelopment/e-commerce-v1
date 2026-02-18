<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;

class CategoriesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $slug = Str::slug($state);

                                // Hilangkan angka kalau mau benar-benar tanpa angka
                                $slug = preg_replace('/[0-9]/', '', $slug);

                                $set('slug', $slug);
                            }),

                        TextInput::make('slug')
                            ->disabled()        // tidak bisa diedit
                            ->dehydrated()      // tetap dikirim ke DB
                            ->required()
                            ->unique(ignoreRecord: true),

                        Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
