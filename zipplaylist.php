<?php
// var_dump($_POST);exit;

if(isset($_POST[0])){




$name='yoooo';

//get incoming POST data.  POST data will be a directory
//$dir=$_POST['files'];
//$dir = '/Applications/MAMP/htdocs';
// $files = scandir($dir);

// $explode = explode('/', $dir);
// $name = $explode[count($explode)-2];


// //create a zip
$zip = new ZipArchive;
$zipname='zips/'.$name.'.zip';

if ($zip->open($zipname, ZipArchive::CREATE) == TRUE) {
// 	foreach ($files as $file) {
// 		//add mp3s to the zip 
// 		if(substr($file, -3)=='mp3'){
// 			$zip->addFile($dir.$file, $file);
// 		}

// 	}
// 	$zip->close();
	foreach ($_POST as $key => $value) {

		$filename = explode('/', $value);
		$filename = end($filename);

		$zip->addFile($value, $filename);
	}
	$zip->close();
}
else {
    echo 'failed';
}



//send the zip file
header('Content-Type: application/zip');
header("Content-disposition: attachment; filename=yoooo.zip");
header('Content-Length: ' . filesize($zipname));
readfile($zipname);

unlink($zipname);

//print_r($data); 
}
?>