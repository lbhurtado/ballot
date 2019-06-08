<?php

namespace LBHurtado\Ballot\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BallotCandidate extends Pivot
{
    // public static function conjure(Position $position, Candidate $candidate = null, $votes = 1)
    // {
    //     $pivot = static::make()->position()->associate($position);

    //     if ($candidate) {
    //         $pivot->candidate()->associate($candidate);
    //         $pivot->votes = $votes;
    //     }
                    
    //     return $pivot;
    // }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function setPosition(Position $position)
    {
        $this->position()->associate($position);

        return $this;
    }

    public function setCandidate(Candidate $candidate)
    {
        $this->candidate()->associate($candidate);

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