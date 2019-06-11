<?php

namespace LBHurtado\Ballot\Actions\Commands;

use LBHurtado\Tactician\Contracts\CommandInterface;

class UpdateBallotCandidateCommand implements CommandInterface
{
    /** @var integer */
	public $ballot_id;

    /** @var integer */
	public $candidate_id;

    public function __construct(int $ballot_id, int $candidate_id)
    {
    	$this->ballot_id = $ballot_id;
    	$this->candidate_id = $candidate_id;
    }

    public function getProperties(): array
    {
    	return [
    		'ballot_id' => $this->ballot_id,
    		'candidate_id' => $this->candidate_id,
    	];
    }
}