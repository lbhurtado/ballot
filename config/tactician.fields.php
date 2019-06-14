<?php

return [
	'read' => [
	    'ballot_code' => 'required|exists:ballots,code', 
	],
	'update' => [
	    'ballot_code' => 'required|exists:ballots,code', 
	    'candidate_id' => 'required|exists:candidates,id',
	],
];