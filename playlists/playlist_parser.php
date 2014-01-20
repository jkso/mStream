<?php


$lines = file($_POST['filename'], FILE_IGNORE_NEW_LINES);

$json = json_encode($lines);

echo $json;

?>