<?php

namespace LBHurtado\Ballot\Actions\Commands;

use LBHurtado\Tactician\Contracts\CommandInterface;

class ReadBallotCandidateCommand implements CommandInterface
{
    /** @var integer */
	public $ballot_id;

    public function __construct(int $ballot_id)
    {
    	$this->ballot_id = $ballot_id;
    }

    public function getProperties(): array
    {
    	return [
    		'ballot_id' => $this->ballot_id,
    	];
    }
}