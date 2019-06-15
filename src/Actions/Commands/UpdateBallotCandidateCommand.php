<?php

namespace LBHurtado\Ballot\Actions\Commands;

use LBHurtado\Tactician\Contracts\CommandInterface;

class UpdateBallotCandidateCommand implements CommandInterface
{
    /** @var string */
    public $ballot_code;

    /** @var string */
	public $candidate_code;

    public function __construct(string $ballot_code, string $candidate_code)
    {
        $this->ballot_code = $ballot_code;
    	$this->candidate_code = $candidate_code;
    }

    public function getProperties(): array
    {
    	return [
            'ballot_code' => $this->ballot_code,
    		'candidate_code' => $this->candidate_code,
    	];
    }
}