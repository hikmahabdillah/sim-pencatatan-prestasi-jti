<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeModel extends Model
{
    use HasFactory;

    protected $table = 'periode';
    protected $primaryKey = 'id_periode';
    protected $fillable = [
        'semester',
        'tahun_ajaran'
    ];
}
