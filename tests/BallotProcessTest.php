<?php

namespace LBHurtado\Ballot\Tests;

use LBHurtado\Ballot\Jobs\ProcessImage;

class BallotProcessTest extends TestCase
{
	/** @test */
	public function ballot_process_works()
	{
        /*** arrange ***/
        $command = 'ballot:process';

        /*** act ***/
		$artisan = $this->artisan($command);

        /*** assert ***/
		$artisan->expectsOutput('Processing image...')->assertExitCode(0);
	}

	/** @test */
	public function ballot_process_pushes_process_image_job()
	{
        /*** arrange ***/
        $command = 'ballot:process';

        /*** assert ***/
        $this->expectsJobs(ProcessImage::class);

        /*** act ***/
		$this->artisan($command);
	}
}