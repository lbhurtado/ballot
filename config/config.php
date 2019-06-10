<?php

return [
	'files' => [
		'image' => [
			'seed' => env('IMAGE_SEED', 'tests/files/image.jpg'),
			'source' => env('BALLOT_SOURCE', storage_path('app/public/ballot.jpg')),
			'destination' => env('BALLOT_DESTINATION', storage_path('app/ballot.jpg')),
			'qrcode' => env('BALLOT_QRCODE', storage_path('app/qrcode.jpg')),
		],
		'temp' => storage_path('app')
	],
	'qrcode' => [
		'regex' => env('QRCODE_REGEX', '/([\d]{4})-([\d]{4})/'),
		'test' => env('QRCODE_TEST', '0001-1234'),
		'dimensions' => [
			'w' => 302,
			'h' => 305,
			'x' => 125,
			'y' => 127,
		],
	],
];