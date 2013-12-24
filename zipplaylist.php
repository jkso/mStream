<?php

// Make sure POST data was sent
if(isset($_POST[0])){


	// //create a zip
	$zip = new ZipArchive;
	$zipname='zips/'.$name.'.zip';

	if ($zip->open($zipname, ZipArchive::CREATE) == TRUE) {

		foreach ($_POST as $key => $value) {

			$filename = explode('/', $value);
			$filename = end($filename);

			// make sure this is an mp3 file.  Don't want people downloading your php files and find your password
			if(substr($filename, -3)=='mp3'){
				$zip->addFile($value, $filename);
			}
		}
		$zip->close();
	}
	else {
	    echo 'failed';
	    return false;
	    // TODO: set the header to a 500 error
	}



	//send the zip file
	header('Content-Type: application/zip');
	header("Content-disposition: attachment; filename=yoooo.zip");
	header('Content-Length: ' . filesize($zipname));
	readfile($zipname);

	unlink($zipname);

}
?>