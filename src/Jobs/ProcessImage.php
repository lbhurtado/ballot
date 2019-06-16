<?php

namespace LBHurtado\Ballot\Jobs;

use Zxing\QrReader;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use LBHurtado\Ballot\Models\Ballot;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Bus\Dispatchable;
use LBHurtado\Ballot\Exceptions\{InvalidQRCodeException, DuplicateEntryException};
use Illuminate\Database\QueryException;

class ProcessImage
{
    use Dispatchable, Queueable;

    /** @var string */
    public $inputImagePath;

    /** @var string */
    public $outputImagePath;

    public function __construct()
    {
        $this->inputImagePath = config('ballot.files.image.source');
        $this->outputImagePath = config('ballot.files.image.destination');
    }

    public function handle()
    {
        if (! file_exists($this->inputImagePath))
            return;

        DB::beginTransaction();
        try {
            $ballot = Ballot::create();

            tap((new QrReader($qrCodePath = $this->getQRCodeFromImage($ballot)))->text(), function($code) use ($ballot) {
                if ($this->validateCode($code)) {
                    if (Ballot::where(compact('code'))->first())
                        throw new DuplicateEntryException;
                    $path = $this->moveImage($ballot);
                    $ballot->update([
                        'code' => $code,
                        'image' => basename($path),
                    ]);
                }
                else
                    throw new InvalidQRCodeException;
            });
            DB::commit();
        }
        catch (InvalidQRCodeException $e) {
            DB::rollBack();
            unlink($qrCodePath);
        }
        catch(DuplicateEntryException $e){
            DB::rollBack();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error Processing Request [ProcessImage::handle]', 1);
        }
        finally {
            unlink($this->inputImagePath);
        }
    }

    protected function getQRCodeFromImage(Ballot $ballot)
    {
        extract(config('ballot.qrcode.dimensions'));
        Image::make($this->inputImagePath)
            ->crop($w, $h, $x, $y)
            ->save($path = tempnam(config('ballot.files.temp'), 'QRCODE-'.$ballot->id.'-'))
            ;

        return $path;       
    }

    protected function moveImage(Ballot $ballot)
    {
        return tap($this->getNewFileName($ballot), function ($path) {
            $image = Image::make($this->inputImagePath);
            $image->save($path);
        });
    }

    protected function getNewFileName(Ballot $ballot)
    {
    	return suffixate_filename($this->outputImagePath, $ballot->id, '-');
    }

    protected function validateCode(string $code)
    {
        return preg_match(config('ballot.qrcode.regex'), $code);
    }
}