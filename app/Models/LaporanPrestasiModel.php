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
        'id_prestasi',
        'id_prodi',
        'id_tingkat_prestasi',
        'id_kategori',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function prestasi()
    {
        return $this->belongsTo(PrestasiMahasiswaModel::class, 'id_prestasi', 'id_prestasi');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProdiModel::class, 'id_prodi', 'id_prodi');
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
