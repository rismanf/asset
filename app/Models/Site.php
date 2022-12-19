<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'address',
        'phone',
        'latitude',
        'longitude',
    ];

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }
}
