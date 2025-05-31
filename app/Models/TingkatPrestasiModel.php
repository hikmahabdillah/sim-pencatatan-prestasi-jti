<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatPrestasiModel extends Model
{
    use HasFactory;

    protected $table = 'tingkat_prestasi';
    protected $primaryKey = 'id_tingkat_prestasi';
    protected $fillable = ['nama_tingkat_prestasi'];

    public function prestasi()
    {
        return $this->hasMany(PrestasiMahasiswaModel::class, 'id_kategori');
    }
}
