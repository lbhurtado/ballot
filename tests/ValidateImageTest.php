<?php

namespace LBHurtado\Ballot\Tests;

use LBHurtado\Ballot\Models\Ballot;
use Illuminate\Support\Facades\Storage;
use LBHurtado\Ballot\Jobs\{GrabImage, ValidateImage};

class ValidateImageTest extends TestCase
{
	/** @var \LBHurtado\Ballot\Models\Ballot */
	protected $ballot;

	/** @var string */
	protected $seed;

	/** @var string */
	protected $sourceImageFile;

	/** @var string */
	protected $destinationImageFile;

	//TODO: refactor this, exactly the same as setup and teardown of GrabImageTest
    public function setUp(): void
    {
    	parent::setUp();

		$this->ballot = factory(Ballot::class)->create(['code' => null]);

    	$this->seed = config('ballot.files.image.seed');

		copy($this->seed, $this->sourceImageFile = config('ballot.files.image.source'));

		$filename = config('ballot.files.image.destination');
   		$filename_ext = pathinfo($filename, PATHINFO_EXTENSION);
		$newFileName = preg_replace('/^(.*)\.' . $filename_ext . '$/', "$1-{$this->ballot->id}." . $filename_ext, $filename);

		$this->destinationImageFile = suffixate_filename(config('ballot.files.image.destination'), $this->ballot->id, '-');
    }

    public function tearDown(): void
    {
    	if (file_exists($this->destinationImageFile))
    		unlink($this->destinationImageFile);

        parent::tearDown();
    }

	/** @test */
	public function job_accepts_ballot()
	{
        /*** arrange ***/
        $ballot = factory(Ballot::class)->create();

        /*** act ***/
		$job = new ValidateImage($ballot);

        /*** assert ***/
		$this->assertTrue($ballot->is($job->ballot));
	}

	/** @test */
	public function job_can_read_qr_code_in_image()
	{
        /*** arrange ***/
        (new GrabImage($this->ballot, $this->sourceImageFile))->handle();

        /*** act ***/
		$job = new ValidateImage($this->ballot);
        $job->handle();

        /*** assert ***/
        $this->assertEquals(config('ballot.qrcode.test'), $this->ballot->code);
	}
}