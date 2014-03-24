<?php

require_once('../config/medoo-conf.php');


$album = $_POST['album'];

// $artist == $_POST['artist'];
//$artist = 'Cyberpunkers';


//$sql = "SELECT file_location,title FROM files WHERE album = '$album' AND artist  = '$artist';";
$sql = "SELECT file_location,title FROM files WHERE album = '$album';";

$results = $database->query($sql)->fetchAll();

// var_dump($results);



$songs = array();

foreach ($results as $result) {
	$push_this = array();


	if(empty($result['title'])){
		$push_this['title'] = '[NO TITLE]';
	}else{
		$push_this['title'] = $result['title'];
	}

	$push_this['file_location'] = substr($result['file_location'], 3); //$result['file_location'];


	array_push($songs, $push_this);

}


echo json_encode($songs);

