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

    <meta content="yes" name="mobile-web-app-capable">
	<meta content="yes" name="apple-mobile-web-app-capable">
	<meta content="black" name="apple-mobile-web-app-status-bar-style">

	<link rel="apple-touch-icon" href="img/apple-icon/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="120x120" href="img/apple-icon/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="152x152" href="img/apple-icon/apple-touch-icon-152x152.png">

	<link rel="shortcut icon" sizes="120x120" href="img/apple-icon/apple-touch-icon-120x120.png">


	<link rel="shortcut icon" type="image/png" href="favicon.png"/>

    <title>Mstream Media Player - All your media. Everywhere you go.</title>
    <?php include('includes/assets.inc.php'); ?>
<style>
.current
{
background-color: #E6EBFA !important;
}
</style>
</head>

<body>

<!-- Size Classes: [small medium large xlarge expand] -->
<div id="savePlaylist" class="reveal-modal small" data-reveal>
  <h2>Save Playlist</h2>
  	<form id="save_playlist_form">
	  <input id="playlist_name" type="text" required placeholder="Enter your playlist name" pattern="[a-zA-Z0-9 _-]+">
	  <input id="save_playlist" type="submit" class="button small" value="Save Playlist">
	</form>
  <a id="close_save_playlist" class="close-reveal-modal">&#215;</a>
</div>

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


      <ul class="off-canvas-list" id="playlist_list">
<!--       	<li><a href="photo_stream.php">Photo Stream</a></li> -->
				<li id="file_explorer"><label>File Explorer</label></li>
				<li id="all_playlists"><label>Playlists</label></li>
				<li id="all_albums"><label>Albums</label></li>
      </ul>
    </aside>

    <section class="main-section">
      <!-- content goes in here -->
		<div class="row">

		<dl class="tabs hide-for-medium-up" data-tab>
		  <dd class="active"><a href="#panel1">Library</a></dd>
		  <dd><a href="#panel2">Playlist</a></dd>
		</dl>
		<div class="tabs-content">
		  <div class="content active" id="panel1">
			<div class="large-12 columns libraryColumn">
				<div class="columnHeader">

					<div class="libraryHeaderContainer">
						<div class="large-6 small-12 columns noPaddingLeft">
							<h3 class="hide-for-small">Library</h3>
						</div>

						<div class="large-6 small-12 columns">
							<div class="controls leftControls">
								<a title="Add Directory to Playlist" class="add" id='addall'><img src="img/glyphicons/png/glyphicons_131_inbox_plus.png" alt="" width="27" height="27"></a><!-- Add Directory to Playlist -->
								<a title="Download Directory" class="downloadDirectory" id='download'><img src="img/glyphicons/png/glyphicons_181_download_alt.png" alt="" width="27" height="27"></a><!-- Download Directory -->
								<div class="scraper">
									<label for="scraper" id="getInfo" class="scrape">Get track info</label>
									<input class="left" type="checkbox" id="scraper">
								</div>
							</div>
						</div>
					</div><!-- /libraryHeaderContainer -->

					<div class="clear directoryTitle">
						<button disabled class="backButton tiny"><img src="img/glyphicons/png/glyphicons_435_undo.png" alt="Back" width="15" height="15"></button>
						<h4 class="directoryName">Current Directory Name</h4>
					</div>
				</div>
				<div class='clear col' id='filelist'>
					<div class="filez">Nothing Here...</div>
				</div>
			</div><!-- /6 columns -->		  </div>
		  <div class="content" id="panel2">
			<div class="large-12 columns playlistColumn" id="playlist_container" >
				<div class="columnHeader">
					<div class="large-6 small-12 columns noPaddingLeft">
						<h3 class="hide-for-small">Playlist</h3>
					</div>

					<div class="large-6 small-12 columns">
						<div class="controls">
							<a title="Clear Playlist" class="clear" id='clear'><img src="img/glyphicons/png/glyphicons_192_circle_remove.png" alt="" width="27" height="27"></a><!-- Clear Playlist -->
							<a title="Download Playlist" class="downloadPlaylist" id="downloadPlaylist"><img src="img/glyphicons/png/glyphicons_181_download_alt.png" width="27" height="27"></a><!-- Download Playlist -->
							<a title="Save Playlist" class="save" data-reveal-id="savePlaylist" ><img src="img/glyphicons/png/glyphicons_443_floppy_disk.png" alt="" width="27" height="27"></a><!-- Save Playlist -->
						</div>
					</div>
				</div>
				<div class="clear col">
					<ul class="clear" id="playlist"></ul>
				</div>

			</div><!--/6 cols-->
		  </div>
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
    <script>
    $(document).ready(function() {
    	$('a#getInfo').click(function(){
    		$('input[type=checkbox]').trigger('click');
    	}
    });
    </script>
</body>
