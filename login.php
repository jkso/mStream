<?php

if($_SESSION["login"]==1){
	session_start();
	header("Location: playexplore.php");
	exit;
}

if($_POST["pword"]=="99bottlesofbeer"){
	session_start();
	$_SESSION["login"]=1;
	header("Location: playexplore.php");
	exit;
}

else{
	echo "go away";
}

?>
