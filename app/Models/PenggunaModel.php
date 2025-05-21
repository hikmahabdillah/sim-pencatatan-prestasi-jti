<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenggunaModel extends Model
{
    use HasFactory;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    protected $keyType = 'string'; // Tipe data primary key
    protected $fillable = [
        'id_pengguna',
        'username',
        'password',
        'role_id',
        'status_aktif',
        'foto'
    ];

    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id', 'role_id');
    }
}
