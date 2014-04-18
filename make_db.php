<?php
// Setup getID3
require_once('id3/getid3/getid3.php');
$getID3 = new getID3;

//  Medoo!
require_once('medoo.min.php');
require_once('config/medoo-conf.php');







//
// // Backup Files Table
// $table_name = "files";
// $backup_file  = "mp3db_backup.sql";
// $sql = "SELECT * INTO OUTFILE '$backup_file' FROM $table_name";
// $database->query($sql);
// var_dump($database->error());
//
// var_dump($database->error());
// echo 'yo';
// exit;
//




// Drop Table
$sql = "DROP TABLE files";
$database->query($sql);

// We can rebuild it
$sql = "CREATE TABLE files
(
title varchar(255),
artist varchar(255),
year INT(6),
album varchar(255),
file_location TEXT
);";
$database->query($sql);



// Make the database
try{
	$json = json_decode(file_get_contents('config/editme.json'), true);

	$dir = $json['startdir'];

	scan_dir($dir, $getID3, $database);
}catch(Exception $e){
	// TODO: restore backup table
	//TODO: Log Error
}




//TODO: Create cache tables









// The majority of the following function was written while under the influence
function scan_dir($dir, $getID3, $database){
	// Setup arrays
	$more_dirs = array();
	$mp3s = array();

	// Get contents of the directory
	$contents = scandir($dir);

	foreach ($contents as $file) {
		// Ignore the following
		if($file == '.' || $file =='..'){
			continue;
		}

		//
		if(is_dir($dir.$file)){
			array_push($more_dirs, $dir.$file);

		}


		if(substr($file, -3)=='mp3'){

			$mp3_info = array();

			$filename=$dir.$file;

			// Debug code
			// echo $filename;
			// echo '<br>';

			try{
				$ThisFileInfo = $getID3->analyze($filename);

				getid3_lib::CopyTagsToComments($ThisFileInfo);


				$mp3_info['title'] = $ThisFileInfo['comments_html']['title'][0];
				$mp3_info['artist'] = $ThisFileInfo['comments_html']['artist'][0];
				$mp3_info['album'] = $ThisFileInfo['comments_html']['album'][0];
				$mp3_info['year'] = $ThisFileInfo['comments_html']['year'][0];

			}catch(Exception $e){
				var_dump($e);

				// TODO: Log this
			}


			$mp3_info['file_location'] = $dir.$file;




			// ??? Flags
			// Flags are files that are marked b/c the id3 info might benefit from manipulation




			// Push the info to the
			array_push($mp3s, $mp3_info);

			// Save it
			$database->insert('files', $mp3_info);

			// Todo: check for errors
			$error = $database->error();
			if( empty($error) ){
				var_dump($database->error()) ;
			}

		}

	}






	// Recursivly go through all the directories
	foreach($more_dirs as $another_dir){
		scan_dir($another_dir . '/', $getID3, $database);

	}

	return true;
}
