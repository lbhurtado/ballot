<?php

namespace LBHurtado\Ballot\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'code',
        'name'
    ];

    protected $hidden = [
        'created_at', 
        'updated_at', 
    ];
    
	public static function create(Position $position, array $attributes = [])
	{
    	$model = tap(static::query()
    		->make($attributes)
    		->position()->associate($position))
    		->save();

    	return $model;
	}

    public function position()
    {
    	return $this->belongsTo(Position::class);
    }
}