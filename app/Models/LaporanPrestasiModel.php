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

    // Relasi ke mahasiswa melalui pivot table
    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa')
            ->withDefault([
                'nama' => 'N/A',
                'nim' => 'N/A'
            ]);
    }

    public function prestasi()
    {
        return $this->belongsTo(PrestasiMahasiswaModel::class, 'id_prestasi')->with(['tingkatPrestasi', 'kategori', 'periode', 'dosenPembimbing']);
    }

    // Scope untuk filter by periode
    public function scopeByPeriode($query, $id_periode)
    {
        return $query->whereHas('prestasi', function($q) use ($id_periode) {
            $q->where('id_periode', $id_periode);
        });
    }

    // Scope untuk data mahasiswa yang login
    public function scopeForCurrentUser($query, $user_id)
    {
        return $query->whereHas('mahasiswa', function($q) use ($user_id) {
            $q->where('id_pengguna', $user_id);
        });
    }
}