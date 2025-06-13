<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    protected $fillable = [
        'nim',
        'id_pengguna',
        'nama',
        'angkatan',
        'email',
        'no_hp',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'id_prodi',
    ];

    public function prodi()
    {
        return $this->belongsTo(ProdiModel::class, 'id_prodi', 'id_prodi');
    }

    public function pengguna()
    {
        return $this->belongsTo(PenggunaModel::class, 'id_pengguna', 'id_pengguna');
    }

    public function prestasi()
    {
        return $this->belongsToMany(PrestasiMahasiswaModel::class, 'anggota_prestasi', 'id_mahasiswa', 'id_prestasi')
            ->withPivot('peran')
            ->withTimestamps();
    }
    public function rekomendasi()
    {
        return $this->hasMany(RekomendasiLombaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function kategori()
    {
        return $this->belongsToMany(KategoriModel::class, 'minat_bakat_pengguna', 'id_pengguna', 'id_kategori');
    }
}
