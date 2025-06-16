<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestasiMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'prestasi_mahasiswa';
    protected $primaryKey = 'id_prestasi';
    protected $fillable = [
        'id_tingkat_prestasi',
        'id_dospem',
        'id_kategori',
        'nama_prestasi',
        'juara',
        'tanggal_prestasi',
        'id_periode',
        'deskripsi',
        'keterangan',
        'foto_kegiatan',
        'bukti_sertifikat',
        'surat_tugas',
        'karya',
        'tipe_prestasi',
        'status_verifikasi_dospem',
        'status_verifikasi',
    ];

    public function tingkatPrestasi()
    {
        return $this->belongsTo(TingkatPrestasiModel::class, 'id_tingkat_prestasi', 'id_tingkat_prestasi');
    }

    public function dosenPembimbing()
    {
        return $this->belongsTo(DosenPembimbingModel::class, 'id_dospem', 'id_dospem');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'id_kategori', 'id_kategori');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeModel::class, 'id_periode', 'id_periode');
    }
    public function anggota()
    {
        return $this->belongsToMany(MahasiswaModel::class, 'anggota_prestasi', 'id_prestasi', 'id_mahasiswa')
            ->withPivot('peran')
            ->withTimestamps();
    }

    public function mahasiswa()
    {
        return $this->anggota(); // Alias untuk relasi yang sama
    }
}
