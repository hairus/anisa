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

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function($query) use ($term){
            $query->where('name', 'like', $term)
            ->orWhere('nisn', 'like', $term)
            ->orWhere('npsn_sma', 'like', $term)
            ->orWhere('npsn_smp', 'like', $term)
            ->orWherehas('nilai', function($query) use ($term){
                $query->where('nilai', 'like', $term);
            });
        });
    }

}
