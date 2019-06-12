<?php

namespace LBHurtado\Ballot\Actions\Validators;

use Validator;
use League\Tactician\Middleware;
use LBHurtado\Ballot\Exceptions\ReadBallotCandidateValidationException;

class ReadBallotCandidateValidator implements Middleware
{
    public function execute($command, callable $next)
    {
        $validator = Validator::make((array) $command, config('tactician.fields.read'));

        if ($validator->fails()) {
            throw new ReadBallotCandidateValidationException($command, $validator);
        }

        return $next($command);
    }
}