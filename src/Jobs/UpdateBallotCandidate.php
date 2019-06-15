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

    /** @var integer */
    protected $seatId;

    public function __construct(int $ballotId, int $candidateId, int $seatId = 1)
    {
        $this->ballot = Ballot::findOrFail($ballotId);
        $this->candidate = Candidate::findOrFail($candidateId);
        $this->seatId = $seatId;
    }

    public function handle()
    {
        $this->ballot->updatePivot($this->candidate, $this->seatId);
    }
}