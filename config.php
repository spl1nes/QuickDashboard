<?php

define('ROOT_PATH', __DIR__);

$CONFIG = [
	'db' => [
		'SD' => [
			'db'       => 'mssql', /* db type */
			'host'     => '127.0.0.1', /* db host address */
			'port'     => '1433', /* db host port */
			'login'    => 'Excel', /* db login name */
			'password' => '', /* db login password */
			'database' => '', /* db name */
			'prefix'   => '', /* db table prefix */
			'weight'   => 1000, /* db table prefix */
		],
		'GDF' => [
			'db'       => 'mssql', /* db type */
			'host'     => '127.0.0.1', /* db host address */
			'port'     => '1433', /* db host port */
			'login'    => 'Excel', /* db login name */
			'password' => '', /* db login password */
			'database' => '', /* db name */
			'prefix'   => '', /* db table prefix */
			'weight'   => 1000, /* db table prefix */
		]
	],
	'page'     => [
        'root'  => '/',
        'https' => false,
    ],
    'fiscal_year' => 7
];