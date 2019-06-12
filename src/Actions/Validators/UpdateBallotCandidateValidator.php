<?php

namespace LBHurtado\Ballot\Actions\Validators;

use Validator;
use League\Tactician\Middleware;
use LBHurtado\Ballot\Exceptions\UpdateBallotCandidateValidationException;

class UpdateBallotCandidateValidator implements Middleware
{
    public function execute($command, callable $next)
    {
        $validator = Validator::make((array) $command, config('tactician.fields.update'));

        if ($validator->fails()) {
            throw new UpdateBallotCandidateValidationException($command, $validator);
        }

        return $next($command);
    }
}