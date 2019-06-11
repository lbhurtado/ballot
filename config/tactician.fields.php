<?php

return [
    'ballot_id' => 'required|exists:ballots,id',
    'candidate_id' => 'required|exists:candidates,id',
];