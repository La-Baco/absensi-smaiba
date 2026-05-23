<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libur extends Model
{
    protected $table = 'libur';

    protected $fillable = [
        'nama_libur', 'tanggal_mulai', 'tanggal_selesai',
    ];
}

