<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Movein_detail extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'movein_id',
        'item_name',
        'item_description',
        'item_va',
        'rack_id',
        'rack_va_before',
        'rack_va_affter',
        'flagging',
        'status_id',
        'created_by_id',
        'updated_by_id',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by_id = is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : 1;
            $model->updated_by_id = NULL;
        });

        static::updating(function ($model) {
            $model->updated_by_id = is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : 1;
        });
    }
}
