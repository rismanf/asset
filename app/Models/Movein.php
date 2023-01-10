<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Movein extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'code_movein',
        'ticket_number',
        'customer_id',
        'pic_name',
        'pic_phone',
        'installation_date',
        'site_id',
        'floor_id',
        'flagging',
        'status_id',
        'created_by_id',
        'updated_by_id',
        'process_date',
        'approve_date',
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

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
