<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log_rack extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'user_id',
        'ip',
        'rack_id',
        'event',
        'description',
        'isdate',
        'istime',
    ];

    public static function record($rack_id, $event, $description) 
    {
        return static::create([
            'user_id' => is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : 1,
            'ip' => request()->ip(),
            'rack_id' => $rack_id,
            'event' => $event,
            'description' => $description,
            'isdate' => date('Y-m-d'),
            'istime' => date('H:i:s'),
        ]);
    }
}
