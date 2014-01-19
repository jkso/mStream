<?php

// file_put_contents('playlists/newplaylist.m3u', 'efwefwef';




// function generatem3u(){
// 	foreach($_POST['stuff'] as $post_it) {
// 		print_r($post_it);

// 		print_r( '\n');

// 	}
// }





$myFile = "playlists/newplaylist.m3u";
$fh = fopen($myFile, 'w') or die("can't open file");



foreach($_POST['stuff'] as $post_it) {
	fwrite($fh, $post_it . "\n");


}


fclose($fh);


?>