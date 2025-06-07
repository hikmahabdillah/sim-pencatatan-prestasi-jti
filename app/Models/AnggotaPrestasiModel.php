<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaPrestasiModel extends Model
{
    use HasFactory;

    protected $table = 'anggota_prestasi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_prestasi',
        'id_mahasiswa',
        'peran'
    ];

    public function prestasi()
    {
        return $this->belongsTo(PrestasiMahasiswaModel::class, 'id_prestasi', 'id_prestasi');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
