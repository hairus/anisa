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


}
