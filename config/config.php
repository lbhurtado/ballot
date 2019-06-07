<?php

return [
	'files' => [
		'image' => [
			'source' => env('BALLOT_SOURCE', storage_path('/app/public/ballot.jpg')),
			'destination' => env('BALLOT_DESTINATION', storage_path('/app/ballot.jpg')),
		],
	],
];