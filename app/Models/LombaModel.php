<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LombaModel extends Model
{
    use HasFactory;

    protected $table = 'lomba';
    protected $primaryKey = 'id_lomba';
    protected $fillable = [
        'nama_lomba',
        'penyelenggara',
        'id_kategori',
        'id_tingkat_prestasi',
        'deskripsi',
        'link_pendaftaran',
        'periode',
        'biaya_pendaftaran',
        'berhadiah',
        'tanggal_mulai',
        'tanggal_selesai',
        'deadline_pendaftaran',
        'foto',
        'status_verifikasi',
        'added_by',
        'role_pengusul'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'id_kategori', 'id_kategori');
    }

    public function tingkatPrestasi()
    {
        return $this->belongsTo(TingkatPrestasiModel::class, 'id_tingkat_prestasi', 'id_tingkat_prestasi');
    }

    public function pengusul()
    {
        return $this->belongsTo(PenggunaModel::class, 'added_by', 'id_pengguna');
    }

    public function rolePengusul()
    {
        return $this->belongsTo(RoleModel::class, 'role_pengusul', 'role_id');
    }

    // public function pendaftaran()
    // {
    //     return $this->hasMany(PendaftaranLombaModel::class, 'id_lomba', 'id_lomba');
    // }

    public function periode()
    {
        return $this->belongsTo(PeriodeModel::class, 'periode', 'id_periode');
    }

    public function rekomendasi()
    {
        return $this->hasMany(RekomendasiLombaModel::class, 'id_lomba', 'id_lomba');
    }

    public function kategoris()
    {
        return $this->belongsToMany(KategoriModel::class, 'kategori_lomba_pivot', 'id_lomba', 'id_kategori');
    }
}
