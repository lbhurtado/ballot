<?php

namespace LBHurtado\Ballot\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use LBHurtado\Ballot\Jobs\ProcessImage;

class BallotProcess extends Command
{
	protected $signature = 'ballot:process';

	protected $description = 'Grab and process image.';

	public function handle()
	{
		Bus::dispatch(new ProcessImage);

		$this->info('Processing image...');
	}
}