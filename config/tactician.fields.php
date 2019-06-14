<?php

return [
	'read' => [
	    'ballot_id' => 'required|exists:ballots,id',
	],
	'update' => [
	    'ballot_id' => 'required|exists:ballots,id',
	    'candidate_id' => 'required|exists:candidates,id',
	],
];