<?php

namespace App\Filament\Resources\Listings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ListingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Properti')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Nama/Judul Properti')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                        FileUpload::make('image_url')
                            ->label('Foto Properti')
                            ->image()
                            ->directory('listings')
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label('Status Ketersediaan')
                            ->options([
                                'available' => 'Tersedia',
                                'sold_out' => 'Sold Out (Terjual)',
                            ])
                            ->required()
                            ->default('available'),
                        Select::make('user_id')
                            ->label('Pemilik (Home Advisor)')
                            ->relationship('user', 'name', fn ($query) => $query->where('role', 'homeadvisor'))
                            ->required()
                            // Jika login sebagai home advisor, sembunyikan dropdown ini dan otomatis isi dengan ID miliknya
                            ->default(fn () => auth()->user()->role === 'homeadvisor' ? auth()->id() : null)
                            ->disabled(fn () => auth()->user()->role === 'homeadvisor')
                            ->dehydrated(),
                    ])
            ]);
    }
}
