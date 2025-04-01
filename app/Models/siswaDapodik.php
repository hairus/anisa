<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class siswaDapodik extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function smas()
    {
        return $this->belongsTo(smas::class, 'npsn_sekolah_sekarang', 'npsn');
    }
}
