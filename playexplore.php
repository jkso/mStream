<?php
	session_start();
	if($_SESSION["login"]!=1){
		header("Location: index.php");
		exit;
	}

?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mstream Media Player - Brainchild of Paul Sori</title>

    <script src="js/modernizr.js"></script>

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<?php require_once('config/editme.php'); ?> 

	<link rel="stylesheet" href="css/foundation.css" />
	<link rel="stylesheet" href="css/master.css">

	<!-- The following are all for jplayer -->
	<link href="jPlayer/skin/midnight.black/jplayer.midnight.black.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="jPlayer/jquery.jplayer/jquery.jplayer.js"></script>
	<!-- <script type="text/javascript" src="jPlayer/add-on/jplayer.playlist.js"></script> -->

	<!-- <link rel="stylesheet" href="css/grid.css"> -->
	<!-- <link rel="stylesheet" href="css/screen.css"> -->

	<script type="text/javascript" src="js/sortable.js"></script>
	<script type="text/javascript" src="js/mstream.js"></script>
</head>



<body>
	<input type="hidden" id="currentdir"></input>
	
	<form id="downform" action="zipplaylist.php" target="frameframe" method="POST">  
	</form>  
	
	<iframe id="downframe" src="" width="0" height="0" tabindex="-1" title="empty" class="hidden" hidden name="frameframe"></iframe>

	<div class="off-canvas-wrap">
  <div class="inner-wrap">
    <nav class="tab-bar">

      <section class="left-small">
        <a class="left-off-canvas-toggle menu-icon" ><span></span></a>
      </section>

      <section class="right tab-bar-section">
        <h1 class="title"><img src="img/mstream-logo.png" class="logo" alt="MStream" width="181" height="auto"></h1>
      </section>

    </nav>

    <!-- Off Canvas Menu -->
    <aside class="left-off-canvas-menu">
      <ul class="off-canvas-list">
        <li><label>Subdirectories(?)</label></li>
        <li><a href="#">The Psychohistorians</a></li>
        <li><a href="#">The Psychohistorians</a></li>
        <li><a href="#">The Psychohistorians</a></li>
        <li><a href="#">The Psychohistorians</a></li>
        <li><a href="#">The Psychohistorians</a></li>
        <li><a href="#">The Psychohistorians</a></li>
        <li><a href="#">The Psychohistorians</a></li>
      </ul>

      <ul class="off-canvas-list" id="playlist_list">
      	<?php 
      	$playlists = scandir('playlists/');

      	foreach ($playlists as $key => $playlist) {
      		# 
      		if(substr($playlist, -3)=='m3u'){
      			echo '<li><a data-filename="' . $playlist . '">' . $playlist . '</a></li>' . "\n";
      		}
      	}

      	?>
      </ul>
    </aside>

    <section class="main-section">
      <!-- content goes in here -->
		<div class="row">
			<div class="large-6 columns libraryColumn">
				<h3>Library</h3>

				<div class='col' id='filelist'>
					<div class="filez">beanz</div>
				</div>
			</div><!-- /6 columns -->
			
			<div class="large-6 columns playlistColumn" id="playlist_container" >
				<h3>Playlist</h3>
				<ul id="playlist">
				</ul>
			</div>
		</div>
		<!-- /row -->
				

			<div class="jplay col">
				<div class ="row">

				<div id="jquery_jplayer_1" class="jp-jplayer"></div>

				<div id="jp_container_1" class="jp-audio">
					<div class="jp-type-single">
						<div class="jp-gui jp-interface">
							<ul class="jp-controls">
								<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
								<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
								<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
								<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
								<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
								<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
							</ul>
							<div class="jp-progress">
								<div class="jp-seek-bar">
									<div class="jp-play-bar"></div>

								</div>
							</div>
							<div class="jp-volume-bar">
								<div class="jp-volume-bar-value"></div>
							</div>
							<div class="jp-current-time"></div>
							<div class="jp-duration"></div>
								<!-- <ul class="jp-toggles">
									<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
									<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
								</ul> -->
						</div>
						<div class="jp-no-solution">
							<span>Update Required</span>
							To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
						</div>
					</div>
				</div>

				</div>

			</div>
		</div>

		<div class="large-6 columns">

		<div class='controls' id='controls'>
			<div class="add" id='addall'><img src="img/glyphicons/png/glyphicons_131_inbox_plus.png" alt="" width="27" height="27"></div><!-- Add Directory to Playlist -->
			<div class="clear" id='clear'><img src="img/glyphicons/png/glyphicons_192_circle_remove.png" alt="" width="27" height="27"></div><!-- Clear Playlist -->
			<div class="downloadDirectory" id='download'><img src="img/glyphicons/png/glyphicons_134_inbox_in.png" alt="" width="27" height="27"></div><!-- Download Directory -->
			<div class="scrape"><img src="img/glyphicons/png/glyphicons_371_global.png" alt=""><input type="checkbox" id="scraper"></div><!-- Use ID3 scraper (this will lag) -->
			<div class="downloadPlaylist" id="downloadPlaylist"><img src="img/glyphicons/png/glyphicons_446_floppy_save.png"></div><!-- Download Playlist -->
			<div class="save" id="save_playlist"><img src="img/glyphicons/png/glyphicons_151_new_window.png" alt="" width="27" height="27"></div><!-- Save Playlist -->
		</div>

		</div>

	</div><!-- /6 columns -->

    </section>

  <!-- close the off-canvas menu -->
  <a class="exit-off-canvas"></a>

  </div>
</div>

    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
</body>