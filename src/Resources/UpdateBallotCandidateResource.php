<?php

namespace LBHurtado\Ballot\Resources;

use LBHurtado\Ballot\Models\{Ballot, BallotCandidate};
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateBallotCandidateResource extends JsonResource
{
    public function toArray($request)
    {
    	return $this->getBallot()->with('positions')->get();

    	return BallotCandidate::where('ballot_id', $this->resource->ballot_id)->get();

    	return $this->getBallot()->with('candidates')->get();
    }

    protected function getBallot()
    {
    	return Ballot::find($this->resource->ballot_id);
    }
}
