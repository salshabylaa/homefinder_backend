<?php

namespace App\Filament\Resources\Applications\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-m-envelope'),
                TextColumn::make('domisili')
                    ->label('Domisili')
                    ->searchable(),
                TextColumn::make('tanggal_submit')
                    ->label('Waktu Daftar')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status_crm')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        default => 'warning',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Disetujui' => 'heroicon-m-check-circle',
                        'Ditolak' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-clock',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->label('Detail'),
                    EditAction::make()->label('Edit'),
                    \Filament\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-m-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->hidden(fn (\App\Models\Application $record) => $record->status_crm !== 'Menunggu Validasi')
                        ->action(function (\App\Models\Application $record) {
                            $record->update(['status_crm' => 'Disetujui']);
                            if (!$record->user_id) {
                                $password = 'Homefinder123!';
                                $user = \App\Models\User::where('email', $record->email)->first();
                                if (!$user) {
                                    $user = \App\Models\User::create([
                                        'name' => $record->nama_lengkap,
                                        'email' => $record->email,
                                        'password' => \Illuminate\Support\Facades\Hash::make($password),
                                        'role' => 'homeadvisor'
                                    ]);
                                }
                                $record->update(['user_id' => $user->id]);
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Berhasil Disetujui')
                                    ->success()
                                    ->body('Akun Home Advisor berhasil dibuat dengan password default: <b>' . $password . '</b>')
                                    ->send();
                            }
                        }),
                    \Filament\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-m-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->hidden(fn (\App\Models\Application $record) => $record->status_crm !== 'Menunggu Validasi')
                        ->action(function (\App\Models\Application $record) {
                            $record->update(['status_crm' => 'Ditolak']);
                            \Filament\Notifications\Notification::make()
                                ->title('Pendaftar Ditolak')
                                ->danger()
                                ->send();
                        }),
                    DeleteAction::make()->label('Hapus'),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
