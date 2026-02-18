<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\CategoryModel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Produk')
                    ->description('Detail utama produk')
                    ->icon('heroicon-o-cube')
                    ->schema([

                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship(
                                name: 'category',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn($query) => $query->where('is_active', true)
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn() => CategoryModel::where('is_active', true)->count() === 0)
                            ->helperText(function () {
                                return ! CategoryModel::where('is_active', true)->exists()
                                    ? 'Data kategori kosong. Silahkan buat kategori terlebih dahulu.'
                                    : null;
                            }),

                        TextInput::make('name')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->live() // ⚠ jangan pakai onBlur dulu
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state) {
                                    $slug = Str::slug($state);
                                    $slug = preg_replace('/[0-9]/', '', $slug);

                                    $set('slug', $slug);
                                }
                            }),

                        TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(ignoreRecord: true),


                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->placeholder('Masukkan deskripsi produk (opsional)')
                            ->columnSpanFull(),

                    ])
                    ->columns(1),

                Section::make('Media & Status')
                    ->description('Gambar dan status publikasi')
                    ->icon('heroicon-o-photo')
                    ->schema([

                        FileUpload::make('image')
                            ->label('Gambar Produk')
                            ->image()
                            ->multiple()
                            ->disk('public')
                            ->directory('products')
                            ->reorderable()
                            ->imagePreviewHeight('120')
                            ->maxFiles(3)
                            ->acceptedFileTypes(['image/jpeg'])
                            ->maxSize(1024)
                            ->helperText('Hanya file JPG dengan ukuran maksimal 1MB.')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Aktifkan Produk')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}
