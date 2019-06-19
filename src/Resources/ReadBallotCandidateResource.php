<?php

namespace LBHurtado\Ballot\Resources;

use LBHurtado\Ballot\Models\{Ballot, BallotCandidate};
use Illuminate\Http\Resources\Json\JsonResource;

class ReadBallotCandidateResource extends JsonResource
{
    public function toArray($request)
    {
    	return Ballot::with('positions')
    		->where('code', $this->resource->ballot_code)
    		->first()
    		;
    }
}
