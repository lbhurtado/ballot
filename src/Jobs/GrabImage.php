<?php

namespace LBHurtado\Ballot\Jobs;

use Illuminate\Bus\Queueable;
use LBHurtado\Ballot\Models\Ballot;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Bus\Dispatchable;

class GrabImage
{
    use Dispatchable, Queueable;

    /** @var \LBHurtado\Ballot\Models\Ballot */
    public $ballot;

    /** @var string */
    public $filename;

    public function __construct(Ballot $ballot, $filename)
    {
    	$this->ballot = $ballot;
        $this->filename = $filename;
    }

    //TODO: create a driver for Image
    public function handle()
    {
    	$image = Image::make($this->filename);
    	$image->save($newFileName = $this->getNewFileName());
    	$this->ballot->update(['image' => $newFileName]);
    	unlink($this->filename);
    }

    protected function getNewFileName()
    {
    	return suffixate_filename(config('ballot.files.image.destination'), $this->ballot->id, '-');
    }
}