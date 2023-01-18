<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class AssetCategoryGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_group_name', 'category_group_code', 'description', 'created_by_id','updated_by_id','deleted_by_id','active'
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by_id = is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : 1;
            $model->updated_by_id = NULL;
        });
    }

    public function category()
    {
        return $this->hasMany(AssetCategory::class);
    }
}
