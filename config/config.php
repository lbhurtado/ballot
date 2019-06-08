<?php

return [
	'files' => [
		'image' => [
			'seed' => env('IMAGE_SEED', 'tests/files/image.jpg'),
			'source' => env('BALLOT_SOURCE', storage_path('/app/public/ballot.jpg')),
			'destination' => env('BALLOT_DESTINATION', storage_path('/app/ballot.jpg')),
		],
	],
	'qrcode' => [
		'regex' => env('QRCODE_REGEX', '/([\d]{4})-([\d]{4})/'),
		'test' => env('QRCODE_TEST', '0001-1234'),
	],
];