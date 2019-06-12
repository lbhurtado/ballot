<?php

namespace LBHurtado\Ballot\Actions\Responders;

use League\Tactician\Middleware;
use LBHurtado\Ballot\Resources\UpdateBallotCandidateResource;

class ReadBallotCandidateResponder implements Middleware
{
    public function execute($command, callable $next)
    {
        $next($command);

        return (new UpdateBallotCandidateResource($command))
            ->response()
            ->setStatusCode(200)
            ;
    }
}
