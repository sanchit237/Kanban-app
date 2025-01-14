<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Todo extends Model
{
    use SoftDeletes,HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'shortcode', 'title', 'description', 'status', 'created_by'];


    public function scopeStatus(Builder $query, $status){
        return $query->where('status', $status);
    }
}
