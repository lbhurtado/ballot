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

    public function getRouteKeyName()
    {
        return 'code';
    }

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
        $candidateId = $candidate->id;
        $votes = 1;
        $ballotId = $this->id;
        $positionId = $candidate->position_id;

        DB::update('update `ballot_candidate` set `candidate_id` = ?, `votes` = ? where `ballot_id` = ? and `position_id` = ? and  `seat_id` = ?', [
            $candidateId, $votes, $ballotId, $positionId, $seatId
        ]);

        DB::commit();
        // tap((new Pivot)->setCandidate($candidate, $seatId), function ($pivot) use ($candidate, $seatId) {
        //     $this->positions()->updateExistingPivot($candidate->position_id, $pivot->getAttributes());
        // });

        return $this;
    }

    public function togglePivot(Candidate $candidate, int $seatId = 1)
    {
        if ($candidate->votes()->whereHas('ballot', function ($q) {$q->where('id', $this->id);})->count() > 0) {
            $candidate_id = null;
            $votes = null;
        }
        else {
            $candidate_id = $candidate->id;
            $votes = 1;
        }
        DB::update('update `ballot_candidate` set `candidate_id` = ?, `votes` = ? where `ballot_id` = ? and `position_id` = ? and  `seat_id` = ?', [$candidate_id, $votes, $this->id, $candidate->position_id, $seatId]);

        DB::commit();
        // tap((new Pivot)->setCandidate($candidate, $seatId), function ($pivot) use ($candidate, $seatId) {
        //     $this->positions()->updateExistingPivot($candidate->position_id, $pivot->getAttributes());
        // });

        return $this;
    }
}
