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

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'ballot_candidate')
            ->withPivot('candidate_id', 'votes')
            ->using(Pivot::class)
            ->withTimestamps()
            ;
    }

    public function setImage($file)
    {
        $this->update(['image' => $file]);

        return $this;
    }

    public function addPivot(Candidate $candidate)
    {
        tap((new Pivot)->setCandidate($candidate), function ($pivot) use ($candidate) {
            $this->positions()->attach($candidate->position_id, $pivot->getAttributes());
        });

        return $this;
    } 

    public function updatePivot(Candidate $candidate)
    {

        // dd($this->positions()->where('position_id', $candidate->position_id)->first());

        tap((new Pivot)->setCandidate($candidate), function ($pivot) use ($candidate) {
            $this->positions()->updateExistingPivot($candidate->position_id, []);
            $this->positions()->updateExistingPivot($candidate->position_id, $pivot->getAttributes());
        });

        return $this;
    }    
}