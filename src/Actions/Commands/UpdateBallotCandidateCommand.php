<?php

namespace LBHurtado\Ballot\Actions\Commands;

use LBHurtado\Tactician\Contracts\CommandInterface;

class UpdateBallotCandidateCommand implements CommandInterface
{
    /** @var string */
    public $ballot_code;

    /** @var integer */
	public $candidate_id;

    public function __construct(string $ballot_code, int $candidate_id)
    {
        $this->ballot_code = $ballot_code;
    	$this->candidate_id = $candidate_id;
    }

    public function getProperties(): array
    {
    	return [
            'ballot_code' => $this->ballot_code,
    		'candidate_id' => $this->candidate_id,
    	];
    }
}