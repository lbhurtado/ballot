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
            ->withPivot('votes', 'position_id')
            ->using(Pivot::class)
            ->withTimestamps();
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'ballot_candidate')
            ->withPivot('candidate_id', 'votes')
            ->using(Pivot::class)
            ->withTimestamps();
    }

    public function setImage($file)
    {
        $this->update(['image' => $file]);

        return $this;
    }
    // public function addCandidate(Position $position, Candidate $candidate = null, Pivot $pivot = null)
    // {
    // 	$pivot = $pivot ?? Pivot::conjure($position, $candidate);
    	
    //     return $this->candidates()->attach($candidate, $pivot->getAttributes());
    // }
}