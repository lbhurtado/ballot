<?php

namespace LBHurtado\Ballot\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'code',
        'name'
    ];
}