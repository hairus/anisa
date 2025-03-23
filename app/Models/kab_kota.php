<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kab_kota extends Model
{
    use HasFactory;

    public  function smas()
    {
        return $this->hasMany(smas::class, 'kab_id');
    }

    public function smaNo()
    {
        return $this->hasMany(smas::class, 'kab_id');
    }

    public  function  finals()
    {
        return $this->hasMany(sekolah_final::class, 'kab_id')->where('final', 1);
    }

    public  function  NoFinals()
    {
        return $this->hasMany(sekolah_final::class, 'kab_id')->where('final', 0);
    }
}
