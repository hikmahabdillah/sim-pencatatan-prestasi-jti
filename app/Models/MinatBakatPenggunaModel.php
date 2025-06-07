<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinatBakatPenggunaModel extends Model
{
    use HasFactory;

    protected $table = 'minat_bakat_pengguna';
    protected $primaryKey = ['id_pengguna', 'id_kategori'];
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id_pengguna',
        'id_kategori',
    ];

    public function pengguna()
    {
        return $this->belongsTo(PenggunaModel::class, 'id_pengguna');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'id_kategori');
    }
}
