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

    protected $hidden = [
        'created_at', 
        'updated_at', 
    ];

    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'ballot_candidate')
            ->withPivot('votes', 'position_id')
            ->using(Pivot::class)
            ->withTimestamps()
            ;
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'ballot_candidate')
            ->withPivot('candidate_id', 'votes')
            ->using(Pivot::class)
            // ->withTimestamps()
            ;
    }

    public function setImage($file)
    {
        $this->update(['image' => $file]);

        return $this;
    }

    public function addCandidate(Candidate $candidate, Pivot $pivot)
    {
        return $this->candidates()->attach($candidate, $pivot->getAttributes());
    }

    public function updateCandidate(Candidate $candidate, Pivot $pivot)
    {
        return $this->candidates()->updateExistingPivot($candidate, $pivot->getAttributes());
    }    

    // public function addCandidate(Position $position, Candidate $candidate = null, Pivot $pivot = null)
    // {
    // 	$pivot = $pivot ?? Pivot::conjure($position, $candidate);
    	
    //     return $this->candidates()->attach($candidate, $pivot->getAttributes());
    // }
}