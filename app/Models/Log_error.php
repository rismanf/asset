<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log_error extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'ip',
        'menu',
        'event',
        'extra',
    ];

    public static function record($user_id = null, $menu, $event, $extra)
    {
        return static::create([
            'user_id' => is_null($user_id) ? null : $user_id->id,
            'ip' => request()->ip(),
            'menu' => $menu,
            'event' => $event,
            'extra' => $extra
        ]);
    }
}

