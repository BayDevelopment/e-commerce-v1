<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('role')
                    ->colors([
                        'danger' => 'admin',
                        'primary' => 'customer',
                    ])
                    ->sortable(),

                BadgeColumn::make('email_verified_at')
                    ->label('Status')
                    ->getStateUsing(
                        fn($record) =>
                        $record->email_verified_at !== null ? 'Verified' : 'Not Verified'
                    )
                    ->colors([
                        'success' => 'Verified',
                        'danger'  => 'Not Verified',
                    ]),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable(),

            ])
            ->filters([
                SelectFilter::make('verification')
                    ->label('Verification Status')
                    ->options([
                        'verified' => 'Verified',
                        'unverified' => 'Not Verified',
                    ])
                    ->query(function (Builder $query, $state) {

                        if ($state === 'verified') {
                            $query->whereNotNull('email_verified_at');
                        }

                        if ($state === 'unverified') {
                            $query->whereNull('email_verified_at');
                        }
                    }),

                SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'customer' => 'Customer',
                    ]),

            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('sendVerification')
                        ->label('Kirim Link Verifikasi')
                        ->icon('heroicon-o-envelope')
                        ->visible(fn(User $record) => is_null($record->email_verified_at))
                        ->requiresConfirmation()
                        ->action(function (User $record) {
                            if (! $record->hasVerifiedEmail()) {
                                $record->sendEmailVerificationNotification();
                            }
                        })
                        ->successNotification(
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Link verifikasi berhasil dikirim.')
                                ->success()
                        ),
                    EditAction::make()
                        ->label('Edit')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary'),
                    DeleteAction::make()
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->visible(fn($record) => $record->id !== Auth::id())
                        ->requiresConfirmation()
                        ->modalHeading('Hapus data?')
                        ->modalDescription('Data yang dihapus tidak bisa dikembalikan.')
                        ->successNotification(
                            Notification::make()
                                ->title('Terhapus')
                                ->body('Data berhasil dihapus.')
                                ->success()
                        ),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->button()
                    ->outlined()
                    ->tooltip('Aksi data')
                    ->dropdownPlacement('bottom-end')

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
