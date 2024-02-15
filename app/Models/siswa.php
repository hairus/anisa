<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class siswa extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function nilai()
    {
        return $this->hasOne(nilai::class);
    }

    public  function smas()
    {
        return $this->belongsTo(smas::class, 'npsn_sma', 'npsn');
    }

    public  function smps()
    {
        return $this->belongsTo(smps::class, 'npsn_smp', 'npsn_smp');
    }


}
