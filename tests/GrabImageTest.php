<?php

namespace LBHurtado\Ballot\Tests;

// use Illuminate\Http\File;
use LBHurtado\Ballot\Models\Ballot;
use LBHurtado\Ballot\Jobs\GrabImage;
use Illuminate\Support\Facades\Storage;

class GrabImageTest extends TestCase
{
	/** @var \LBHurtado\Ballot\Models\Ballot */
	protected $ballot;

	/** @var string */
	protected $seed = 'tests/files/image.png';

	/** @var string */
	protected $sourceImageFile;

	/** @var string */
	protected $destinationImageFile;

    public function setUp(): void
    {
    	parent::setUp();

		$this->ballot = factory(Ballot::class)->create();

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
	public function job_accepts_ballot_and_filename()
	{
        /*** arrange ***/
		$filename = $this->faker->word;

        /*** act ***/
		$job = new GrabImage($this->ballot, $filename);

        /*** assert ***/
		$this->assertEquals($this->ballot, $job->ballot);
		$this->assertEquals($filename, $job->filename);
	}

	/** @test */
	public function job_can_move_source_image_to_destination_image_and_post_to_database()
	{
        /*** arrange ***/
		$job = new GrabImage($this->ballot, $this->sourceImageFile);	

        /*** assert ***/
        $this->assertTrue(file_exists($this->sourceImageFile));
        $this->assertFalse(file_exists($this->destinationImageFile));

        /*** act ***/
		$job->handle();

        /*** assert ***/
        $this->assertFalse(file_exists($this->sourceImageFile));
        $this->assertTrue(file_exists($this->destinationImageFile));

        $this->assertEquals($this->destinationImageFile, $this->ballot->image);
        $this->assertDatabaseHas('ballots', [
        	'image' => $this->destinationImageFile
        ]);
	}
}