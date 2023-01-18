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
        'sector_id',
        'created_by_id',
        'updated_by_id',
        'updated_by_id',
        'deleted_by_id',
        'active',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
