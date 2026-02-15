<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->placeholder('Contoh: Sepatu Sneakers Pria')
                            ->validationMessages([
                                'required' => 'Nama produk wajib diisi.',
                                'min_length' => 'Nama produk minimal 3 karakter.',
                            ]),

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
                            ->acceptedFileTypes(['image/jpeg']) // Hanya JPG
                            ->maxSize(1024) // 1024 KB = 1MB
                            ->helperText('Hanya file JPG dengan ukuran maksimal 1MB.')
                            ->validationMessages([
                                'accepted_file_types' => 'File harus berformat JPG.',
                                'max_size' => 'Ukuran gambar maksimal 1MB.',
                            ])
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Aktifkan Produk')
                            ->default(true)
                            ->helperText('Nonaktifkan jika produk tidak ingin ditampilkan.')
                            ->inline(false),
                    ]),
            ]);
    }
}
