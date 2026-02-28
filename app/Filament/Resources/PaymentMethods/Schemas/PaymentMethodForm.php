<?php

namespace App\Filament\Resources\PaymentMethods\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Metode Pembayaran')
                    ->description('Data rekening tujuan yang akan ditampilkan kepada customer saat checkout.')
                    ->icon('heroicon-o-credit-card')
                    ->columns(2)
                    ->schema([

                        TextInput::make('name')
                            ->label('Nama Metode')
                            ->placeholder('Contoh: Transfer Bank')
                            ->prefixIcon('heroicon-o-wallet')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        TextInput::make('bank_name')
                            ->label('Nama Bank')
                            ->placeholder('Contoh: BCA, BRI, Mandiri')
                            ->prefixIcon('heroicon-o-building-library')
                            ->maxLength(255),

                        TextInput::make('account_number')
                            ->label('Nomor Rekening')
                            ->placeholder('Contoh: 1234567890')
                            ->prefixIcon('heroicon-o-hashtag')
                            ->tel()
                            ->numeric() // hanya angka
                            ->maxLength(30)
                            ->required(),

                        TextInput::make('account_name')
                            ->label('Atas Nama')
                            ->placeholder('Contoh: PT Trendora')
                            ->prefixIcon('heroicon-o-user')
                            ->maxLength(255),

                        Toggle::make('is_active')
                            ->label('Aktifkan Metode Pembayaran')
                            ->helperText('Jika nonaktif, metode ini tidak akan muncul di halaman checkout.')
                            ->onIcon('heroicon-o-check-circle')
                            ->offIcon('heroicon-o-x-circle')
                            ->default(true)
                            ->columnSpan(2),

                    ]),
            ]);
    }
}
