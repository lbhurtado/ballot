<?php

namespace LBHurtado\Ballot\Observers;

use LBHurtado\Ballot\Models\Ballot;
use Illuminate\Foundation\Bus\DispatchesJobs;
use LBHurtado\Ballot\Jobs\PopulateBallotCandidate;

class BallotObserver
{
    use DispatchesJobs;

    public function created(Ballot $ballot)
    {
        $this->dispatch(new PopulateBallotCandidate($ballot));
    }
}