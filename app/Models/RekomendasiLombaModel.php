<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RekomendasiLombaModel extends Model
{
    protected $table = 'rekomendasi_lomba';
    protected $primaryKey = 'id_rekomendasi';
    public $timestamps = false;

    protected $fillable = [
        'id_mahasiswa',
        'id_lomba',
        'c1_kesesuaian_minat',
        'c2_jumlah_prestasi_sesuai',
        'c3_tingkat_lomba',
        'c4_durasi_pendaftaran',
        'c5_biaya_pendaftaran',
        'c6_benefit_lomba',
        'n_c1',
        'n_c2',
        'n_c3',
        'n_c4',
        'n_c5',
        'n_c6',
        'skor_moora',
        'id_dospem',
        'id_pengusul',
        'role_pengusul',
        'tanggal_rekomendasi',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function lomba()
    {
        return $this->belongsTo(LombaModel::class, 'id_lomba', 'id_lomba');
    }
}