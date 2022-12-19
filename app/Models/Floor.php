<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'floor_name',
        'site_id',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
