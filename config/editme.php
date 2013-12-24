<?php
	// This is here to protect this file
	// You don't want people snooping arounf for information
	if($_SESSION["login"]==1){

	}
	else{
		header("Location: index.php");
		exit;
	}
?>

<script>
	// 'startdir' is the location of the initial directory from your computer's root.  PHP needs this
	var startdir = '/Applications/MAMP/htdocs/audiofiles/';
	// 'startdirstripped' is the location of the initial directory from your server's webroot
	var startdirstripped = '/audiofiles/';
	// This is as far as you want the user going back.  A value of '/' means you want your user going back as far as your webroot
	var rootdir='/audiofiles/';
</script>