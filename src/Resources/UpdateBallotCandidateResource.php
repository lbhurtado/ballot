<?php

namespace LBHurtado\Ballot\Resources;

use LBHurtado\Ballot\Models\{Ballot, BallotCandidate};
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateBallotCandidateResource extends JsonResource
{
    public function toArray($request)
    {
    	return $this->getBallot()->with('positions')->get();
    }

    protected function getBallot()
    {
        return Ballot::where('code', $this->resource->ballot_code)->first();
    }
}
