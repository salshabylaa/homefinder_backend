<?php

namespace App\Filament\Resources\Applications\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Pribadi')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('nama_lengkap')->label('Nama Lengkap')->default('-'),
                        TextEntry::make('email')->label('Email')->icon('heroicon-m-envelope')->default('-'),
                        TextEntry::make('domisili')->label('Domisili')->default('-'),
                        TextEntry::make('nomor_wa')->label('Nomor WhatsApp')->icon('heroicon-m-phone')->default('-'),
                        TextEntry::make('sosial_media_ig')->label('Instagram')->default('-'),
                        TextEntry::make('sosial_media_tiktok')->label('TikTok')->default('-'),
                        TextEntry::make('sosial_media_linkedin')->label('LinkedIn')->default('-'),
                        TextEntry::make('dokumen_cv_url')
                            ->label('Tautan CV/Portofolio')
                            ->formatStateUsing(fn ($state) => $state ? 'Lihat/Unduh CV' : 'Tidak Ada CV')
                            ->url(fn ($state) => $state ? asset('storage/' . $state) : null)
                            ->openUrlInNewTab()
                            ->color(fn ($state) => $state ? 'primary' : 'gray'),
                    ]),
                \Filament\Schemas\Components\Section::make('Pengalaman & Keahlian')
                    ->columns(2)
                    ->schema([
                        IconEntry::make('punya_pengalaman')->label('Punya Pengalaman Properti?')->boolean(),
                        TextEntry::make('lama_pengalaman')->label('Lama Pengalaman')->default('-'),
                        TextEntry::make('jenis_properti')->label('Jenis Properti yang Dikuasai')->default('-'),
                        TextEntry::make('area_dikuasai')->label('Area Spesifik yang Dikuasai')->default('-'),
                    ]),
                \Filament\Schemas\Components\Section::make('Area & Network')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('status_network')->label('Network di Area Tersebut')->default('-'),
                        TextEntry::make('sumber_customer')->label('Sumber Customer Utama')->default('-'),
                        TextEntry::make('jumlah_network')->label('Estimasi Jumlah Network')->default('-'),
                        TextEntry::make('pengalaman_sales')->label('Pengalaman Sales di Industri Lain')->columnSpanFull()->default('-'),
                        TextEntry::make('kenyamanan_komunikasi')->label('Kenyamanan Berkomunikasi')->columnSpanFull()->default('-'),
                        TextEntry::make('pemahaman_fasilitas_wilayah')->label('Pemahaman Fasilitas Umum Wilayah')->columnSpanFull()->default('-'),
                    ]),
                \Filament\Schemas\Components\Section::make('Kesiapan Kerja')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('alokasi_waktu')->label('Alokasi Waktu per Minggu')->default('-'),
                        TextEntry::make('kesediaan_visit')->label('Kesediaan Survey Lokasi/Temani Klien')->default('-'),
                        IconEntry::make('punya_kendaraan')->label('Memiliki Kendaraan Pribadi?')->boolean(),
                        IconEntry::make('bersedia_training')->label('Bersedia Ikuti Training?')->boolean(),
                    ]),
                \Filament\Schemas\Components\Section::make('Motivasi & Harapan')
                    ->schema([
                        TextEntry::make('alasan_tertarik')->label('Alasan Tertarik Bergabung')->columnSpanFull()->default('-'),
                        TextEntry::make('harapan_bergabung')->label('Harapan Setelah Bergabung')->columnSpanFull()->default('-'),
                    ]),
                \Filament\Schemas\Components\Section::make('Status Sistem')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('status_crm')
                            ->label('Status Pendaftaran')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Disetujui' => 'success',
                                'Ditolak' => 'danger',
                                default => 'warning',
                            }),
                        TextEntry::make('tanggal_submit')->label('Tanggal Pendaftaran')->dateTime('d M Y H:i'),
                        TextEntry::make('user_id')->label('User ID (Jika sudah dibuat)'),
                    ])
            ]);
    }
}
