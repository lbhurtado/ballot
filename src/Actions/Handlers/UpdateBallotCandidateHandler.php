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
        $ballot_id = optional(Ballot::where('code', $command->ballot_code)->first())->id;
        $candidate_id = optional(Candidate::where('code', $command->candidate_code)->first())->id;
        $seat_id = $command->seat_id;

        UpdateBallotCandidate::dispatch($ballot_id, $candidate_id, $seat_id);
    }
}