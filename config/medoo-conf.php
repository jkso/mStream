<?php

//  Medoo!
require_once('../medoo.min.php');

// configure medoo
$database = new medoo([
	// required
	'database_type' => 'mysql',
	'database_name' => 'mstream',
	'server' => 'localhost',
	'username' => 'root',
	'password' => 'root',
 
	// optional
	'port' => 3306,
	'charset' => 'utf8',
	// driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
	'option' => [
		PDO::ATTR_CASE => PDO::CASE_NATURAL
	]
]); 


?>