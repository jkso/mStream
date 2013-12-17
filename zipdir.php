<?php
//get incoming POST data.  POST data will be a directory
$dir=$_GET['dir'];

$files = scandir($dir);

$explode = explode('/', $dir);
$name = $explode[count($explode)-2];


//create a zip
$zip = new ZipArchive;
$zipname='zips/'.$name.'.zip';

if ($zip->open($zipname, ZipArchive::CREATE) == TRUE) {
	foreach ($files as $file) {
		//add mp3s to the zip 
		if(substr($file, -3)=='mp3'){
			$zip->addFile($dir.$file, $file);
		}

	}
	$zip->close();
}
else {
    echo 'failed';
    return false;
}

$filename=$name . '.zip';

//send the zip file
header('Content-Type: application/zip');
header("Content-disposition: attachment; filename=$filename");
header('Content-Length: ' . filesize($zipname));
readfile($zipname);

unlink($zipname);

//print_r($data); 
?>