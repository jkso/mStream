<?php

require_once('../config/medoo-conf.php');


// $album == $_POST['album'];
$album = 'Epic';

// $artist == $_POST['artist'];
$artist = 'Cyberpunkers';


$sql = "SELECT file_location,title FROM files WHERE album = '$album' AND artist  = '$artist';";

$results = $database->query($sql)->fetchAll();

var_dump($results);

echo $database->last_query();