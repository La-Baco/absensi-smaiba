<?php

// app/Models/Absensi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'id_siswa',
        'id_guru',
        'tanggal',
        'waktu_masuk',
        'waktu_pulang',
        'status',
        'keterangan',
    ];


    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }
}

