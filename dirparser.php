<?php
if ($_POST['scrape']=='false'){
	$scrapeIt = false;
}else{
	$scrapeIt = true;
}

if($scrapeIt){
	require_once('id3/getid3/getid3.php');
	$getID3 = new getID3;
}


//get incoming POST data.  POST data will be a directory
$dir=$_POST['dir'];

$contents = scandir($dir);

$files=array();
$directories=array();
$m=0;
$n=0;
foreach ($contents as $file) {
	if($file == '.' || $file =='..'){
		continue;
	}


	if(is_dir($dir.$file)){
		$files[$m]['type']='dir';
		$files[$m]['link']=$file;
		$m++;
	}
	
	//echo a link to add this to jplayers playlist
	if(substr($file, -3)=='mp3'){
		$directories[$n]['type']='mp3';
		$directories[$n]['link']=$file;
		
		if($scrapeIt){
			$filename=$dir.$file;
			$ThisFileInfo = $getID3->analyze($filename);
			getid3_lib::CopyTagsToComments($ThisFileInfo);
			$directories[$n]['title']=$ThisFileInfo['comments_html']['title'][0];
			$directories[$n]['artist']=$ThisFileInfo['comments_html']['artist'][0];	
		}

		$n++;
	}
	
}

$sendthis = array_merge($directories, $files);

$jsondir = json_encode($sendthis);

echo $jsondir;
?>