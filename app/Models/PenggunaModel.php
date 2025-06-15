<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PenggunaModel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    protected $keyType = 'string'; // Tipe data primary key
    protected $fillable = [
        'id_pengguna',
        'username',
        'password',
        'role_id',
        'status_aktif',
        'keterangan_nonaktif',
        'foto'
    ];
    protected $hidden = [
        'password', // Sembunyikan password dari hasil query
    ];
    protected $casts = [
        'password' => 'hashed', // Hash password otomatis saat menyimpan ke database
    ];

    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id', 'role_id');
    }
    public function mahasiswa()
    {
        return $this->hasOne(MahasiswaModel::class, 'id_pengguna', 'id_pengguna');
    }

    public function dosen()
    {
        return $this->hasOne(DosenPembimbingModel::class, 'id_pengguna', 'id_pengguna');
    }

    public function admin()
    {
        return $this->hasOne(AdminModel::class, 'id_pengguna', 'id_pengguna');
    }
    public function getRoleName(): string
    {
        return $this->role->role_name ?? 'Unknown';
    }

    public function hasRole($role): bool
    {
        return $this->role->nama_role == $role;
    }

    public function getRole()
    {
        return $this->role->nama_role;
    }

    public function minatBakat()
    {
        return $this->belongsToMany(
            KategoriModel::class,
            'minat_bakat_pengguna',
            'id_pengguna',
            'id_kategori'
        )->withTimestamps();
    }

    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }
}
