<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Rack extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];

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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class)->select(['id', 'floor_name','site_id']);
    }

    // public function room()
    // {
    //     return $this->belongsTo(Floor::class);
    // }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function rackpowerdefault()
    {
        return $this->belongsTo(Rack_power_default::class, 'rack_power_defaults_id')->select(['id', 'power_default']);
    }

    public function pic_name()
    {
        return $this->belongsTo(User::class, 'pic_id')->select(['id', 'name']);
    }

    
}
