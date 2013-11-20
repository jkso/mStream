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

$files = scandir($dir);

$data=array();
$n=0;
$m=0;
foreach ($files as $file) {
	if($n!=2){
		$n++;
	}
	else{
		if(is_dir($dir.$file)){
			$data[$m]['type']='dir';
			$data[$m]['link']=$file;
			$m++;
		}
		else{
			//echo a link to add this to jplayers playlist
			if(substr($file, -3)=='mp3'){
				$data[$m]['type']='mp3';
				$data[$m]['link']=$file;
				
				if($scrapeIt){
					$filename=$dir.$file;
					$ThisFileInfo = $getID3->analyze($filename);
					getid3_lib::CopyTagsToComments($ThisFileInfo);
					$data[$m]['title']=$ThisFileInfo['comments_html']['title'][0];
					$data[$m]['artist']=$ThisFileInfo['comments_html']['artist'][0];	
				}


				$m++;
			}
		}
	}
}


$jsondir = json_encode($data);
//echo $json;

echo $jsondir;
?>