<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranLombaModel extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_lomba';
    protected $primaryKey = 'id_pendaftaran';
    protected $fillable = [
        'id_mahasiswa',
        'id_lomba',
        'tanggal_pendaftaran',
        'status_pendaftaran',
        'berkas_pendaftaran'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function lomba()
    {
        return $this->belongsTo(LombaModel::class, 'id_lomba', 'id_lomba');
    }
}
