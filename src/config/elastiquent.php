<?php
return [
	'elastic_host'     => env('ELASTIC_HOST', '127.0.0.1'),
	'elastic_port'     => env('ELASTIC_PORT', 9200),
	'elastic_username' => env('ELASTIC_USERNAME', ''),
	'elastic_password' => env('ELASTIC_PASSWORD', ''),

	'migration'  => 'eq_migrations',
	'connection' => env('DB_CONNECTION'),
];