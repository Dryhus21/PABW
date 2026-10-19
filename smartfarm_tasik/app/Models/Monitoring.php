<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $table = 'monitoring';

    protected $fillable = [
        'lokasi',
        'nama_tanaman',
        'kelembapan_tanah',
        'suhu_udara',
        'kelembapan_udara',
        'status_lahan',
    ];
}
