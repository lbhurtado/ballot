<?php

return [
	'read' => [
	    'ballot_code' => 'required|exists:ballots,code', 
	],
	'update' => [
	    'ballot_code' => 'required|exists:ballots,code', 
	    'candidate_code' => 'required|exists:candidates,code',
	    'seat_id' => 'required:integer',
	],
];