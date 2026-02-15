<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\ActionGroup;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Variant')
                    ->icon('heroicon-o-squares-2x2')
                    ->schema([
                        Grid::make(2)->schema([

                            TextInput::make('sku')
                                ->label('SKU')
                                ->placeholder('Contoh: TS-MERAH-M')
                                ->unique(ignoreRecord: true),

                            TextInput::make('color')
                                ->label('Warna')
                                ->placeholder('Contoh: Merah'),

                            TextInput::make('size')
                                ->label('Ukuran')
                                ->placeholder('Contoh: M'),
                        ]),

                        Grid::make(2)->schema([

                            TextInput::make('price')
                                ->label('Harga')
                                ->numeric()
                                ->required()
                                ->prefix('Rp')
                                ->minValue(0)
                                ->placeholder('Contoh: 150000')
                                ->live(onBlur: true)
                                ->formatStateUsing(
                                    fn($state) =>
                                    $state ? number_format($state, 0, ',', '.') : null
                                )
                                ->dehydrateStateUsing(
                                    fn($state) =>
                                    str_replace('.', '', $state)
                                ),

                            TextInput::make('stock')
                                ->label('Stok')
                                ->numeric()
                                ->required()
                                ->default(0)
                                ->minValue(0)
                                ->placeholder('Contoh: 10'),
                        ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),

                TextColumn::make('color')
                    ->label('Warna'),

                TextColumn::make('size')
                    ->label('Ukuran'),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('stock')
                    ->label('Stok')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Variant')
                    ->icon('heroicon-o-plus'),
                // AssociateAction::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Edit')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary'),

                    DeleteAction::make()
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Variant?')
                        ->modalDescription('Data yang dihapus tidak bisa dikembalikan.')
                        ->successNotification(
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Data berhasil dihapus.')
                                ->success()
                        ),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->button()
                    ->outlined()
                    ->tooltip('Aksi data')
                    ->dropdownPlacement('bottom-end'),
            ]);
    }
}
