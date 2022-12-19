<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Web_solution_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
    ];
}
