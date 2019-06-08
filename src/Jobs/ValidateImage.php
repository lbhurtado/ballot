<?php

namespace LBHurtado\Ballot\Jobs;

use Zxing\QrReader;
use Illuminate\Bus\Queueable;
use LBHurtado\Ballot\Models\Ballot;
use Illuminate\Foundation\Bus\Dispatchable;

class ValidateImage
{
    use Dispatchable, Queueable;

    /** @var \LBHurtado\Ballot\Models\Ballot */
    public $ballot;

    public function __construct(Ballot $ballot)
    {
    	$this->ballot = $ballot;
    }

    //TODO: create a driver for QR
    public function handle()
    {
        $code = (new QrReader($this->ballot->image))->text();

        if ($this->validate($code))
            $this->ballot->update(compact('code'));
    }

    protected function validate($code)
    {
        return preg_match(config('ballot.qrcode.regex'), $code);
    }
}