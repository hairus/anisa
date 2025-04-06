<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sekolah_final extends Model
{
    use HasFactory;

    protected $guarded = [];

    public  function smas()
    {
        return $this->hasOne(smas::class, 'npsn', 'npsn');
    }
}
