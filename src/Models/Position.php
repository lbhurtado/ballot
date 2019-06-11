<?php

namespace LBHurtado\Ballot\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'name',
        'seats',
        'level',
    ];

    protected $hidden = [
        'created_at', 
        'updated_at', 
    ];
    
    public function scopeWithName($query, $name)
    {
    	$query->where('name', $name);
    }
}