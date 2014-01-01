<?php
	// This is here to protect this file
	// You don't want people snooping around for information
	if($_SESSION["login"]!=1){
		header("Location: index.php");
		exit;
	}

?>

<script>
	// This is the location of your files.
	var startdir = 'audiofiles/';

</script>