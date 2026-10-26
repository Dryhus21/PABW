<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    protected $table = 'kontaks'; // Nama tabel yang dihubungkan
    // Kolom yang boleh diisi melalui Kontak::create() dan update()
    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'foto',
    ];
}
