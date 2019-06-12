<?php

namespace LBHurtado\Ballot\Actions\Handlers;

use LBHurtado\Ballot\Jobs\UpdateBallotCandidate;
use LBHurtado\Tactician\Contracts\CommandInterface;
use LBHurtado\Tactician\Contracts\HandlerInterface;
use LBHurtado\Ballot\Models\{BallotCandidate, Ballot, Candidate};

class UpdateBallotCandidateHandler implements HandlerInterface
{
    public function handle(CommandInterface $command)
    {
    	// $ballot = Ballot::findOrFail($command->ballot_id);
    	// $candidate = Candidate::findOrFail($command->candidate_id);
     //    tap((new BallotCandidate)->setCandidate($candidate), function ($pivot) use ($ballot, $candidate) {
     //        $ballot
     //            ->positions()
     //            ->updateExistingPivot(
     //                $candidate->position_id, 
     //                $pivot->getAttributes()
     //            );
     //    });

    	UpdateBallotCandidate::dispatch($command->ballot_id, $command->candidate_id);
    }
}