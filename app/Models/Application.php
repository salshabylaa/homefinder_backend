<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'is_data_lengkap',
        'tanggal_submit',
        'status_crm',
        'jadwal_interview',
        'hasil_interview',
        'nama_lengkap',
        'domisili',
        'nomor_wa',
        'sosial_media_ig',
        'sosial_media_tiktok',
        'sosial_media_linkedin',
        'dokumen_cv_url',
        'punya_pengalaman',
        'lama_pengalaman',
        'jenis_properti',
        'area_dikuasai',
        'status_network',
        'sumber_customer',
        'jumlah_network',
        'pengalaman_sales',
        'kenyamanan_komunikasi',
        'pemahaman_fasilitas_wilayah',
        'alokasi_waktu',
        'kesediaan_visit',
        'punya_kendaraan',
        'alasan_tertarik',
        'harapan_bergabung',
        'bersedia_training',
    ];

    /**
     * Get the user that owns the lamaran.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
