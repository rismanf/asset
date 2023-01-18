<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_file','asset_original_file', 'result', 'status'
    ];
}
