<?php

namespace LBHurtado\Ballot\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BallotCandidate extends Pivot
{
    public static function conjure(Candidate $candidate, $votes = 1)
    {
        return static::make(['votes' => $votes])->candidate()->associate($candidate);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}