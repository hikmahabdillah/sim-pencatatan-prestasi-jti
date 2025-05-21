<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPrestasiModel extends Model
{
    use HasFactory;

    protected $table = 'laporan_prestasi';
    protected $primaryKey = 'id_laporan';
    protected $fillable = [
        'id_mahasiswa',
        'nama_mahasiswa',
        'prodi',
        'nama_lomba',
        'tingkat',
        'kategori',
        'hasil',
        'status_verifikasi'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProdiModel::class, 'prodi', 'id_prodi');
    }

    public function kategoriPrestasi()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori', 'id_kategori');
    }

    public function tingkatPrestasi()
    {
        return $this->belongsTo(TingkatPrestasiModel::class, 'tingkat', 'id_tingkat_prestasi');
    }
}
