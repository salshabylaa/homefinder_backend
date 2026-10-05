<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lamarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Status & Umum
            $table->boolean('is_data_lengkap')->default(false);
            $table->timestamp('tanggal_submit')->nullable();
            $table->string('status_crm')->default('Menunggu Validasi');
            $table->dateTime('jadwal_interview')->nullable();
            $table->string('hasil_interview')->default('Belum Dijadwalkan');

            // Sec 1: Personal Info
            $table->string('nama_lengkap')->nullable();
            $table->string('domisili')->nullable();
            $table->string('nomor_wa')->nullable();
            $table->string('sosial_media')->nullable();
            $table->string('dokumen_cv_url')->nullable();

            // Sec 2: Property Experience
            $table->boolean('punya_pengalaman')->nullable();
            $table->string('lama_pengalaman')->nullable();
            $table->string('jenis_properti')->nullable();

            // Sec 3: Market Network
            $table->string('area_dikuasai')->nullable();
            $table->string('status_network')->nullable();
            $table->string('sumber_customer')->nullable();
            $table->string('jumlah_network')->nullable();

            // Sec 4: Sales Skills
            $table->text('pengalaman_sales')->nullable();
            $table->text('kenyamanan_komunikasi')->nullable();
            $table->text('pemahaman_fasilitas_wilayah')->nullable();

            // Sec 5: Commitment
            $table->string('alokasi_waktu')->nullable();
            $table->string('kesediaan_visit')->nullable();
            $table->boolean('punya_kendaraan')->nullable();
            $table->text('alasan_tertarik')->nullable();
            $table->text('harapan_bergabung')->nullable();
            $table->boolean('bersedia_training')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lamarans');
    }
};
