<?php

// Varz
$title = $_POST['title'];
$is_there_a_file_there = 0;


// Check for special characters
$pattern = '/^[a-zA-Z0-9-_ ]*$/';
if(!preg_match($pattern, $title)) {
	echo 'bad user';exit;
}

// Check if file exists
if(file_exists('playlists/' . $title . '.m3u')){
	$is_there_a_file_there = 1;
}


$myFile = "playlists/".$title.".m3u";
$fh = fopen($myFile, 'w') or die("can't open file");



foreach($_POST['stuff'] as $post_it) {
	fwrite($fh, $post_it . "\n");


}


fclose($fh);

echo $is_there_a_file_there;


?>