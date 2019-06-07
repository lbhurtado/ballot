<?php

namespace LBHurtado\Ballot\Models;

use Illuminate\Database\Eloquent\Model;
use LBHurtado\Ballot\Models\BallotCandidate as Pivot;

class Ballot extends Model
{
    protected $fillable = [
        'code',
        'image'
    ];

    public function candidates()
    {
        return $this->belongsToMany(Candidate::class)
            ->withPivot('votes')
            ->using(Pivot::class)
            ->withTimestamps();
    }

    public function addCandidate(Position $position, Candidate $candidate, Pivot $pivot = null)
    {
    	$pivot = $pivot ?? Pivot::conjure($position, $candidate);
    	
        return $this->candidates()->attach($candidate, $pivot->getAttributes());
    }
}