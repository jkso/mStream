<?php
$playlists = scandir('./');

$echo_playlists = array();

foreach ($playlists as $key => $playlist) {
  if(substr($playlist, -3)=='m3u'){
    $temp_array = array();
    $temp_array['file'] = $playlist;
    array_push($echo_playlists, $temp_array);

  }
}

echo json_encode($echo_playlists);

?>
