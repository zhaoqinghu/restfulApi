<?php
include_once 'inc/db.php';
include_once 'inc/medoo.db.php';

$dbm = new medoo([
		'database_type' => 'mysql',
		'database_name' => $DB['mysql']['dbname'],
		'server' => $DB['mysql']['host'],
		'username' => $DB['mysql']['username'],
		'password' => $DB['mysql']['password'],
		'charset' => 'utf8',
		'port' => $DB['mysql']['port']
	]);





?>