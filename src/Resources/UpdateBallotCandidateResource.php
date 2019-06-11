<?php

namespace LBHurtado\Ballot\Resources;

use LBHurtado\Ballot\Models\Ballot;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateBallotCandidateResource extends JsonResource
{
    public function toArray($request)
    {
    	return $this->getBallot()->with('candidates')->get();
    }

    protected function getBallot()
    {
    	return Ballot::find($this->resource->ballot_id);
    }
}
