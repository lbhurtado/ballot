<?php

namespace LBHurtado\Ballot\Tests;

use LBHurtado\Ballot\Models\Ballot;
use Illuminate\Support\Facades\Storage;
use LBHurtado\Ballot\Jobs\ProcessImage;

class ProcessImageTest extends TestCase
{
	/** @var \LBHurtado\Ballot\Models\Ballot */
	protected $ballot;

	/** @var string */
	protected $sourceImageFile;

	/** @var string */
	protected $destinationImageFile;

    /** $var integer */
	protected $ballot_id = 1;

    public function setUp(): void
    {
    	parent::setUp();

    	$seed = config('ballot.files.image.seed');
		copy($seed, $this->sourceImageFile = config('ballot.files.image.source'));
        $this->destinationImageFile = suffixate_filename(config('ballot.files.image.destination'), $this->ballot_id, '-');
    }

    public function tearDown(): void
    {
        if (file_exists($this->destinationImageFile))
            unlink($this->destinationImageFile);

        parent::tearDown();
    }

	/** @test */
    public function job_persists_a_new_ballot()
    {
        /*** assert ***/
        $this->assertEquals(0, Ballot::all()->count());

        /*** act ***/
        $job = (new ProcessImage)->handle();

        /*** assert ***/
        $this->assertEquals(1, Ballot::all()->count());
    }

	/** @test */
	public function job_can_read_qr_code_in_image()
	{
        /*** arrange ***/
        $code = config('ballot.qrcode.test');
        $path = basename($this->destinationImageFile);

        /*** act ***/
        $job = (new ProcessImage)->handle();

        /*** assert ***/
        $ballot = Ballot::first();
        $this->assertEquals($code, $ballot->code);
        $this->assertEquals($path, $ballot->image);
        $this->assertDatabaseHas('ballots', [
            'code' => $code,
            'image' => $path
        ]);
	}

    /** @test */
    public function job_copies_source_file_after_processing()
    {
        /*** arrange ***/
        $path = $this->destinationImageFile;

        /*** assert ***/
        $this->assertFalse(file_exists($path));

        /*** act ***/
        $job = (new ProcessImage)->handle();

        /*** assert ***/
        $this->assertTrue(file_exists($path));
    }

    /** @test */
    public function job_deletes_source_file_after_processing()
    {
        /*** assert ***/
        $this->assertTrue(file_exists($this->sourceImageFile));

        /*** act ***/
        $job = (new ProcessImage)->handle();

        /*** assert ***/
        $this->assertFalse(file_exists($this->sourceImageFile));
    }

    /** @test */
    public function job_will_revert_if_no_image()
    {
        /*** arrange ***/
        unlink($this->sourceImageFile);

        /*** assert ***/
        $this->assertEquals(0, Ballot::all()->count());

        /*** act ***/
        $job = (new ProcessImage)->handle();

        /*** assert ***/
        $this->assertEquals(0, Ballot::all()->count());
    }

    /** @test */
    public function job_will_revert_if_not_valid()
    {
        /*** arrange ***/
        copy('tests/files/invalid.jpg', config('ballot.files.image.source'));

        /*** assert ***/
        $this->assertEquals(0, Ballot::all()->count());
        
        /*** act ***/
        $job = (new ProcessImage)->handle();

        /*** assert ***/
        $this->assertEquals(0, Ballot::all()->count());
    }

    //TODO: test temp qrcode crop files
}