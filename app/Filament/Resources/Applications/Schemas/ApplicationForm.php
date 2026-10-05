<?php

namespace App\Filament\Resources\Applications\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Status Sistem')
                    ->columns(3)
                    ->schema([
                        TextInput::make('status_crm')->label('Status CRM')->required()->default('Menunggu Validasi'),
                        DateTimePicker::make('tanggal_submit')->label('Tanggal Submit')->default(now()),
                        TextInput::make('user_id')->label('User ID')->numeric(),
                        DateTimePicker::make('jadwal_interview')->label('Jadwal Interview'),
                        TextInput::make('hasil_interview')->label('Hasil Interview')->default('Belum Dijadwalkan'),
                        Toggle::make('is_data_lengkap')->label('Data Lengkap?'),
                    ]),
                \Filament\Schemas\Components\Section::make('Informasi Pribadi')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_lengkap')->label('Nama Lengkap')->required(),
                        TextInput::make('email')->label('Email')->email()->required(),
                        TextInput::make('domisili')->label('Domisili')->required(),
                        TextInput::make('nomor_wa')->label('Nomor WhatsApp')->required(),
                        TextInput::make('sosial_media_ig')->label('Instagram'),
                        TextInput::make('sosial_media_tiktok')->label('TikTok'),
                        TextInput::make('sosial_media_linkedin')->label('LinkedIn'),
                        // FileUpload requires Spatie Media Library or specific path handling. We'll keep it as TextInput for path/url or FileUpload
                        \Filament\Forms\Components\FileUpload::make('dokumen_cv_url')
                            ->label('Dokumen CV (PDF)')
                            ->directory('cvs')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(2048),
                    ]),
                \Filament\Schemas\Components\Section::make('Pengalaman & Keahlian')
                    ->columns(2)
                    ->schema([
                        Toggle::make('punya_pengalaman')->label('Punya Pengalaman Properti?'),
                        TextInput::make('lama_pengalaman')->label('Lama Pengalaman'),
                        TextInput::make('jenis_properti')->label('Jenis Properti yang Dikuasai'),
                        TextInput::make('area_dikuasai')->label('Area Spesifik yang Dikuasai'),
                    ]),
                \Filament\Schemas\Components\Section::make('Area & Network')
                    ->columns(2)
                    ->schema([
                        TextInput::make('status_network')->label('Network di Area Tersebut'),
                        TextInput::make('sumber_customer')->label('Sumber Customer Utama'),
                        TextInput::make('jumlah_network')->label('Estimasi Jumlah Network'),
                        Textarea::make('pengalaman_sales')->label('Pengalaman Sales di Industri Lain')->columnSpanFull(),
                        Textarea::make('kenyamanan_komunikasi')->label('Kenyamanan Berkomunikasi')->columnSpanFull(),
                        Textarea::make('pemahaman_fasilitas_wilayah')->label('Pemahaman Fasilitas Umum Wilayah')->columnSpanFull(),
                    ]),
                \Filament\Schemas\Components\Section::make('Kesiapan Kerja')
                    ->columns(2)
                    ->schema([
                        TextInput::make('alokasi_waktu')->label('Alokasi Waktu per Minggu'),
                        TextInput::make('kesediaan_visit')->label('Kesediaan Survey Lokasi/Temani Klien'),
                        Toggle::make('punya_kendaraan')->label('Memiliki Kendaraan Pribadi?'),
                        Toggle::make('bersedia_training')->label('Bersedia Ikuti Training?'),
                    ]),
                \Filament\Schemas\Components\Section::make('Motivasi')
                    ->schema([
                        Textarea::make('alasan_tertarik')->label('Alasan Tertarik Bergabung')->columnSpanFull(),
                        Textarea::make('harapan_bergabung')->label('Harapan Setelah Bergabung')->columnSpanFull(),
                    ]),
            ]);
    }
}
