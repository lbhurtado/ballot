<?php

namespace LBHurtado\Ballot\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use LBHurtado\Ballot\Exceptions\PositionMismatchException;;

class BallotCandidate extends Pivot
{
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    //TODO: throw exception in create and update event - in observable probably
    public function setCandidate(Candidate $candidate)
    {
        optional($this->position, function ($position) use ($candidate) {
            if ($candidate)
                if ($this->position->id <> $candidate->position->id)
                    throw new PositionMismatchException;            
        });


        $this->candidate()->associate($candidate);
        $this->votes = 1;

        return $this;
    }

    public function setVotes($votes)
    {
        $this->votes = $votes;

        return $this;
    }

    public function scopeWithPosition($query, Position $position)
    {
        return $this->whereHas('position', function ($query) use ($position) {
            $query->where('positions.id', '=', $position->id);
        });
    }
}