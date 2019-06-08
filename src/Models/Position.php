<?php

namespace LBHurtado\Ballot\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'name',
        'level',
    ];

    public function scopeWithName($query, $name)
    {
    	$query->where('name', $name);
    }
}