<?php

namespace LBHurtado\Ballot\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use LBHurtado\Ballot\Models\{BallotCandidate, Ballot, Candidate};

class UpdateBallotCandidate
{
    use Dispatchable, Queueable;

    /** @var \LBHurtado\Ballot\Models\Ballot */
    protected $ballot;

    /** @var \LBHurtado\Ballot\Models\Candidate */
    protected $candidate;

    public function __construct(int $ballotId, int $candidateId)
    {
        $this->ballot = Ballot::findOrFail($ballotId);
        $this->candidate = Candidate::findOrFail($candidateId);
    }

    public function handle()
    {
        tap((new BallotCandidate)->setCandidate($this->candidate), function ($pivot) {
            $this->ballot
                ->positions()
                ->updateExistingPivot(
                    $this->candidate->position_id, 
                    $pivot->getAttributes()
                );
            // if ($this->candidate->id == 3)
            //     dd($this->ballot->positions()->where('candidate_id', $this->candidate->id)->first());
        });
    }
}