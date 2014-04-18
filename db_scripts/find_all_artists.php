<?php

//  Medoo!
require_once('../medoo.min.php');
require_once('../config/medoo-conf.php');





$sql = "SELECT DISTINCT artist FROM files;";

$results = $database->query($sql)->fetchAll();

var_dump($results);
