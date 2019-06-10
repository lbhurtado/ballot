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
        $this->ballot = Ballot::find($ballotId);
        $this->candidate = Candidate::find($candidateId);
    }

    public function handle()
    {
        $this->getPivot()
            ->setCandidate($this->candidate)
            ->save()
            ;
    }

    protected function getPivot()
    {
        return BallotCandidate::where('ballot_id', $this->ballot->id)
            ->where('position_id', $this->candidate->position->id)
            ->first()
            ;
    }
}