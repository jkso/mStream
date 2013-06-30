<?php
session_start();

//UNCOMMENT THE NEXT LINE TO DISABLE LOGIN
$_SESSION["login"]=1;

if($_SESSION["login"]==1){
	header("Location: playexplore.php");
	exit;
}
?>

<head>

<style>
.center{
   width: 350px;
   height: 30px;
   border: 1px solid #999999;
   padding: 5px;
   position: absolute;
   left: 50%;
   top: 50%;
   margin-left: -150px;
   margin-top: -150px;
}

.submit{
 // width: 100px;
  position: absolute;
//  left: 50%;
//  top: 70%
  margin-left: -150px;
  margin-top: -150px;
}
</style>

</head>




<form action="login.php" method="post">
<input type="password" name="pword" class="center" autofocus>

</br>

<input type="submit" class="submit" >
</form>
