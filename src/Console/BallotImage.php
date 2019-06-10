<?php

namespace LBHurtado\Ballot\Console;

use Illuminate\Console\Command;
use LBHurtado\Ballot\Jobs\{GrabImage, ValidateImage};

class BallotImage extends Command
{
	protected $signature = 'ballot';

	protected $description = 'Grab and process image.';

	public function handle()
	{
		GrabImage::dispatch($ballot, $filename);
		$this->info('The quick brown fox.');
	}
}