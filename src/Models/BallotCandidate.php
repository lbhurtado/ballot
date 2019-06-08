<?php

namespace LBHurtado\Ballot\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

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

    public function setPosition(Position $position)
    {
        $this->position()->associate($position);

        return $this;
    }

    public function setCandidate(Candidate $candidate)
    {
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