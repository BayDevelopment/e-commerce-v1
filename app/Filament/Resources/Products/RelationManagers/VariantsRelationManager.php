<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Products\ProductResource;
use App\Models\ProductVariantModel;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
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
                                ->label('Ukuran / Porsi')
                                ->placeholder('Contoh: M, XL, 32, 44, Jumbo, 500gr')
                                ->maxLength(50)
                                ->nullable(),
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
                            Select::make('branch_id')
                                ->label('Cabang')
                                ->relationship('branch', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                        Grid::make(1)->schema([

                            TextInput::make('stock')
                                ->label('Stok')
                                ->numeric()
                                ->required()
                                ->default(0)
                                ->minValue(0)
                                ->placeholder('Contoh: 100'),

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
                TextColumn::make('branch.name')
                    ->label('Cabang')
                    ->badge()
                    ->color('info'),

                TextColumn::make('stock')
                    ->label('Stok')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state <= 0 ? 'danger' : ($state <= 5 ? 'warning' : 'success')
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Variant')
                    ->icon('heroicon-o-plus')

                    // Tombol Simpan jadi hijau
                    ->modalSubmitAction(
                        fn($action) => $action
                            ->label('Simpan Variant')
                            ->icon('heroicon-o-check-circle')
                            ->color('success')
                    )

                    // Notifikasi sukses custom (AMAN SEMUA VERSI)
                    ->successNotification(
                        Notification::make()
                            ->title('Berhasil')
                            ->body('Data berhasil ditambahkan.')
                            ->success()
                    )

                    // Aktifkan create another
                    ->createAnother()

                    // Tombol Cancel
                    ->modalCancelAction(
                        fn($action) => $action
                            ->label('Batal')
                            ->icon('heroicon-o-x-mark')
                            ->color('gray')
                    ),
            ])

            ->recordActions([
                ActionGroup::make([
                    Action::make('manageStock')
                        ->label('Kelola Stok')
                        ->icon('heroicon-o-cube')
                        ->color('success')
                        ->url(fn($record) => ProductResource::getUrl('edit', [
                            'record' => $record->product_id,
                        ])),
                    EditAction::make()
                        ->label('Edit')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary')
                        ->visible(fn($record) => ! $record->trashed()),

                    DeleteAction::make()
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Variant?')
                        ->modalDescription('Data akan dipindahkan ke trash.')
                        ->successNotificationTitle('Data berhasil dipindahkan ke trash.')
                        ->visible(fn($record) => ! $record->trashed()),

                    RestoreAction::make()
                        ->label('Restore')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Restore Variant?')
                        ->modalDescription('Data akan dikembalikan.')
                        ->successNotificationTitle('Data berhasil direstore.')
                        ->visible(fn($record) => $record->trashed()),

                    ForceDeleteAction::make()
                        ->label('Hapus Permanen')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Permanen?')
                        ->modalDescription('Data akan dihapus permanen dan tidak bisa dikembalikan.')
                        ->successNotificationTitle('Data berhasil dihapus permanen.')
                        ->visible(fn($record) => $record->trashed()),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->button()
                    ->outlined()
                    ->tooltip('Aksi data')
                    ->dropdownPlacement('bottom-end')
            ]);
    }
}
