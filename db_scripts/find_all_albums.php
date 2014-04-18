<?php

//  Medoo!
require_once('../medoo.min.php');
require_once('../config/medoo-conf.php');





$sql = "SELECT * from files GROUP BY album;";

$results = $database->query($sql)->fetchAll();

$albums = array();

foreach ($results as $result) {
	if(empty($result['album'])){
		continue;
	}

	$push_this = array();
	$push_this['album'] = $result['album'];
	$push_this['artist'] = $result['artist'];


	array_push($albums, $push_this);

}


echo json_encode($albums);
