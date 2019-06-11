<?php

namespace LBHurtado\Ballot\Actions\Handlers;

use LBHurtado\Ballot\Jobs\UpdateBallotCandidate;
use LBHurtado\Tactician\Contracts\CommandInterface;
use LBHurtado\Tactician\Contracts\HandlerInterface;

class UpdateBallotCandidateHandler implements HandlerInterface
{
    public function handle(CommandInterface $command)
    {
    	UpdateBallotCandidate::dispatch($command->ballot_id, $command->candidate_id);
    }
}