<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';

    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function prestasi()
    {
        return $this->hasMany(PrestasiMahasiswaModel::class, 'id_kategori');
    }

    public function pengguna()
    {
        return $this->belongsToMany(
            PenggunaModel::class,
            'minat_bakat_pengguna',
            'id_kategori',
            'id_pengguna'
        )->withTimestamps();
    }

    public function lombas()
    {
        return $this->belongsToMany(LombaModel::class, 'kategori_lomba_pivot', 'id_kategori', 'id_lomba');
    }
}
