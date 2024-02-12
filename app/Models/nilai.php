<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nilai extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function siswas()
    {
        return $this->belongsTo(siswa::class, 'siswa_id');
    }
}
