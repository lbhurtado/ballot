<?php

namespace LBHurtado\Ballot\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use LBHurtado\Ballot\Models\{BallotCandidate, Ballot, Position};

class PopulateBallotCandidate
{
    use Dispatchable, Queueable;

    /** @var \LBHurtado\Ballot\Models\Ballot */
    protected $ballot;

    public function __construct(Ballot $ballot)
    {
    	$this->ballot = $ballot;
    }

    public function handle()
    {
    	BallotCandidate::withBallot($this->ballot)->delete();

		Position::all()->each(function($position) {
            for ($seats = 1; $seats <= $position->seats; $seats++)
			 $this->ballot->positions()->attach($position, []);
		});
    }
}