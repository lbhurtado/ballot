<?php

namespace LBHurtado\Ballot\Models;

use Illuminate\Support\Facades\DB;
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
            ->withPivot('candidate_id', 'votes', 'seat_id')
            ->using(Pivot::class)
            ->withTimestamps()
            ;
    }

    public function setImage($file)
    {
        $this->update(['image' => $file]);

        return $this;
    }

    public function addPivot(Candidate $candidate, int $seatId = 1)
    {
        tap((new Pivot)->setCandidate($candidate, $seatId), function ($pivot) use ($candidate) {
            $this->positions()->attach($candidate->position_id, $pivot->getAttributes());
        });

        return $this;
    } 

    public function updatePivot(Candidate $candidate, int $seatId = 1)
    {
        DB::update('update `ballot_candidate` set `candidate_id` = ?, `votes` = 1 where `ballot_id` = ? and `position_id` = ? and  `seat_id` = ?', [$candidate->id, $this->id, $candidate->position_id, $seatId]);

        DB::commit();
        // tap((new Pivot)->setCandidate($candidate, $seatId), function ($pivot) use ($candidate, $seatId) {
        //     $this->positions()->updateExistingPivot($candidate->position_id, $pivot->getAttributes());
        // });

        return $this;
    }    
}