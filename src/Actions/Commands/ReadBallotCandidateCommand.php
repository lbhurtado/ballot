<?php

namespace LBHurtado\Ballot\Actions\Commands;

use LBHurtado\Tactician\Contracts\CommandInterface;

class ReadBallotCandidateCommand implements CommandInterface
{
    /** @var string */
    public $ballot_code;

    public function __construct(string $ballot_code)
    {
        $this->ballot_code = $ballot_code;
    }

    public function getProperties(): array
    {
    	return [
            'ballot_code' => $this->ballot_code,
    	];
    }
}