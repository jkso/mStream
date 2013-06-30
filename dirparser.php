<?php
//get incoming POST data.  POST data will be a directory
$dir=$_POST['dir'];

$dir2= str_replace('/Applications/MAMP/htdocs', '', $dir);

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
			//$data[$m]['link']=$dir2.$file;
			$data[$m]['link']=$file;
			$m++;
		}
		else{
			//echo a link to add this to jplayers playlist

			if(substr($file, -3)=='mp3'){
				$data[$m]['type']='mp3';
				//$data[$m]['link']=$dir2.$file;
				$data[$m]['link']=$file;
				$m++;
			}
		}

		//echo '<a href="' . $dir2 . $file . '">BOB</a>';
		// if(substr($file, -3)=='mp3'){
		// 	$data[$m]['title']=$file;
		// 	$data[$m]['mp3']=$dir2.$file;
		// 	//print_r($data[$m]);
		// 	//echo "</br></br>";
		// 	$m++;
		// }
	}
}


$jsondir = json_encode($data);
//echo $json;

echo $jsondir;
?>