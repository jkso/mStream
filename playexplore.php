<?php
	session_start();
	if($_SESSION["login"]==1){

	}
	else{
		header("Location: index.php");
		exit;
	}
?>

<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/playexplore.css" />

<!-- The following are all for jplayer -->
<link href="jPlayer/skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jPlayer/jquery.jplayer/jquery.jplayer.js"></script>
<!-- <script type="text/javascript" src="jPlayer/add-on/jplayer.playlist.js"></script> -->

<link rel="stylesheet" href="css/grid.css">
<link rel="stylesheet" href="css/screen.css">

<script type="text/javascript" src="js/sortable.js"></script>
<script type="text/javascript" src="js/mstream.js"></script>


</head>



<body>
	<input type="hidden" id="currentdir"></input>
	<input type="hidden" id="currentdirlong"></input>
	
	<form id="downform" action="zipplaylist.php" target="frameframe" method="POST">  
	</form>  
	
	<iframe id="downframe" src="" width="0" height="0" tabindex="-1" title="empty" class="hidden" hidden name="frameframe"></iframe>


	<div class="container">

		<div class="row">

			<div class='col' id='filelist'>
				<div class="filez">beanz</div>
			</div>


			<div class="jplay col">

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
	<!-- 						<ul class="jp-toggles">
								<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
								<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
							</ul> -->
						</div>
						<div id="playlist_container" class="jp-title">
							<ul id="playlist">
							</ul>
						</div>
						<div class="jp-no-solution">
							<span>Update Required</span>
							To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
						</div>
					</div>
				</div>


			</div>
		</div>


		<div class='controls row' id='controls'>
			<div id='addall'>Add Directory to Playlist</div>
			<div id='clear'>Clear Playlist</div>
			<div id='download'>Download Directory</div>
			<div><input type="checkbox" id="scraper">Use ID3 scraper (this will lag)</div>
			<div></div>
			<div id="downloadPlaylist">Download Playlist</div>
		</div>


	</div>


</body>