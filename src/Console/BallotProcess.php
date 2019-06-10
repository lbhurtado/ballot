<?php

namespace LBHurtado\Ballot\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use LBHurtado\Ballot\Jobs\ProcessImage;
use LBHurtado\Ballot\Exceptions\{InvalidQRCodeException, DuplicateEntryException};

class BallotProcess extends Command
{
	protected $signature = 'ballot:process';

	protected $description = 'Grab and process image.';

	public function handle()
	{
		$this->info('Processing image...');

		Bus::dispatch(new ProcessImage);

		$this->info('Image processed.');
	}
}