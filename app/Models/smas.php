<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class smas extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function kabs()
    {
        return $this->belongsTo(kab_kota::class, 'kab_id');
    }

    public function siswas()
    {
        return $this->hasMany(siswa::class, 'npsn_sma', 'npsn');
    }

    public function dapodik()
    {
        return $this->hasMany(siswaDapodik::class, 'npsn_sekolah_sekarang', 'npsn');
    }

    public  function siswasNo()
    {
        return $this->hasMany(siswa::class, 'npsn_sma', 'npsn');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'username', 'npsn');
    }

    public function finals()
    {
        return $this->hasOne(sekolah_final::class, 'npsn', 'npsn');
    }
}
