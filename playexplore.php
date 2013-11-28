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


<link rel="stylesheet" type="text/css" href="css/playexplore.css" />

<!-- jQuery -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!-- The following are all for jplayer -->
<link href="jPlayer24/skin/prettify-jPlayer.css" rel="stylesheet" type="text/css" />
<link href="jPlayer24/skin/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jPlayer/jquery.jplayer/jquery.jplayer.js"></script>
<script type="text/javascript" src="jPlayer/add-on/jplayer.playlist.js"></script>


<script type="text/javascript">
$(document).ready(function(){
// this sets up the first dir you see when you long on.   
	//EDIT THESE VALUES:
		//'startdir' is the location of the initial directory from your computer's root.  PHP needs this
	var startdir = '/Applications/MAMP/htdocs/audiofiles/';
		//'startdirstripped' is the location of the initial directory from your server's webroot
	var startdirstripped = '/audiofiles/';
		//this is as far as you want the user going back.  A value of '/' means you want your user going back as far as your webroot
	var rootdir='/';
	//DONE WITH EDITTING



// initialize jPlayer. This needs to be done before doing naything else
	var myPlaylist = new jPlayerPlaylist({
			jPlayer: "#jquery_jplayer_N",
			cssSelectorAncestor: "#jp_container_N"
		}, 

		[], //feed the playlsit an empty json value

		{
			playlistOptions: {
				enableRemoveControls: true
			},
			swfPath: "/CSS3MusicList/jPlayer24/Jplayer.swf",
			supplied: "mp3",
			smoothPlayBar: true,
			keyEnabled: true,
			audioFullScreen: true
		});



// this code sets up the file browser.  It runs once when the page loads and is never used again
	//set a hidden input to the curent directory values
	$('#currentdir').val(startdirstripped);
	$('#currentdirlong').val(startdir);
	//send this directory to be parsed and displayed
	senddir(startdir);



// when you click an mp3, add it to the playlist
	$("#filelist").on('click', 'div.filez', function() {
		//get the mp3 name and the directory its in
		var addfile=$(this).attr("id");
		var thedir=$('#currentdir').val();
		//put these together to send to jPlayer
		var mp3location = thedir+addfile;

		//add it to the playlist
		myPlaylist.add({
			title: addfile,
			//artist:"The Stark Palace",
			mp3: mp3location
			//poster: "http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png"
		});
	});

// when you click 'add directory', add entire directory to the playlist
	$("#addall").on('click', function() {
		//make an array of all the mp3 files in the curent directory
		var elems = document.getElementsByClassName('filez');
   		var arr = jQuery.makeArray(elems);

   		//var with the current directory
		var thedir=$('#currentdir').val();

		//loop through array and add each file to the playlist
		$.each( arr, function() {
			var addfile=$(this).attr("id");
			var mp3location= thedir+addfile

			// add it to the playlist
			myPlaylist.add({
				title: addfile,
				//artist:"The Stark Palace",
				mp3: mp3location
				//poster: "http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png"
			});
		});
	});

// when you click on a directory, go to that directory
	$("#filelist").on('click', 'div.dirz', function() {
		//get the html of that class
		var adddir=$(this).attr("id");
		var curdirlong=$('#currentdirlong').val();
		var curdir=$('#currentdir').val();
		var location = curdirlong+adddir+'/';

		//update the hidden fileds with the new location
		$('#currentdirlong').val(location);
		$('#currentdir').val(curdir+adddir+'/');

		//pass this value along
		senddir(location);
	});

// when you click the back directory
	$("#filelist").on('click', 'div.back', function() {
		if($('#currentdir').val()!=rootdir){
			//get the html of that class
			var adddir=$(this).html();
			var curdirlong=$('#currentdirlong').val();
			var curdirshort=$('#currentdir').val();
			var location = curdirlong+adddir+'/';

			//break apart the directory into an array of strings.  This will be used to chop off the last directory
			var arrayOfStrings = curdirlong.split('/');

			var builddirlong='/';
			var builddir='/';

			// Find the difference in the number of directories of the currentDirecory variables
			var curDirShortLength = curdirshort.split('/').length;
			var curDirLongLength = arrayOfStrings.length;
			var diff = curDirLongLength-curDirShortLength;


			//loop through an construct new currentDirectory variables
			for (var i=0; i < arrayOfStrings.length-2; i++){
				if(i!=0){
					builddirlong=builddirlong+arrayOfStrings[i]+'/';
				}
				if(i>diff){
					builddir=builddir+arrayOfStrings[i]+'/';
				}
			}
			//console.log(builddirlong);
			//console.log(builddir);

		$('#currentdirlong').val(builddirlong);
		$('#currentdir').val(builddir);

		senddir(location);
		}
		});

// clear the playlist
	$("#clear").click(function() {
		myPlaylist.setPlaylist([]);
	});

// downlaod the dir contents.  Download uses hidden iframe
	$("#download").click(function() {
		var dirz = encodeURIComponent( $('#currentdirlong').val() );
		$('#downframe').attr('src', "zipdir.php?dir="+dirz);

		console.log("zipdir.php?dir="+dirz);
	});



// send a new directory to be parsed.
	function senddir(dir){
		// If the scraper option is checked, then tell dirparer to use getID3
		var scrape = $('#scraper').is(":checked");
		$.post('/mstream/dirparser.php', {dir: dir, scrape: scrape}, function(response) {
		     console.log("Response: "+response);
		    // hand this data off to be printed on the page
		    printdir(response);
		});
	}

// function that will recieve JSON from dirparser.php.  It will then make a list of the directory and tack on classes for functionality
	function printdir(dir){
		//console.log(jQuery.parseJSON(dir));
		var dirty = jQuery.parseJSON(dir);

		//clear the list
		$('#filelist').empty();

		//parse through the json array and make an array of corresponding divs
		var filelist = [];
		$.each(dirty, function() {
			if(this.type=='mp3'){
				if(this.artist!=null || this.title!=null){
					filelist.push('<div id="'+this.link+'" class="filez">'+this.artist+' - '+this.title+'</div>');
				}
				else{
					filelist.push('<div id="'+this.link+'" class="filez">'+this.link+'</div>');
				}
			}
			if(this.type=='dir'){
				filelist.push('<div id="'+this.link+'" class="dirz">'+this.link+'</div>');
			}
		});

		//add a listing to go back
		if($('#currentdir').val()!=rootdir){
			filelist.push('<div id=".." class="back">..</div>');
		}

		//console.log(filelist);
		$('#filelist').html(filelist);
	}
});


</script>
</head>



<body>


	<div class='masterlist' id='filelist'>
		<div class="filez">beanz</div>
	</div>


	<input type="hidden" id="currentdir"></input>
	<input type="hidden" id="currentdirlong"></input>


	<div class='controls' id='controls'>
		<div id='addall'>Add Directory to Playlist</div>
		<div id='clear'>Clear Playlist</div>
		<div id='download'>Download Directory</div>
		<div><input type="checkbox" id="scraper">Use ID3 scraper (this will lag)</div>
	</div>

	<div id="iframeholder">
		<iframe id="downframe" src="" width="0" height="0" tabindex="-1" title="empty" class="hidden" hidden></iframe>
	</div>


	<div id="jp_container_N" class="jp-video jp-video-270p">
		<div class="jp-type-playlist">
			<div id="jquery_jplayer_N" class="jp-jplayer" style="width: 480px; height: 270px;"><video id="jp_video_0" preload="metadata" style="width: 0px; height: 0px;"></video></div>
			<div class="jp-gui" style="">
				<div class="jp-video-play" style="display: none;">
					<a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
				</div>
				<div class="jp-interface">
					<div class="jp-progress">
						<div class="jp-seek-bar" style="width: 100%;">
							<div class="jp-play-bar" style="width: 0%;"></div>
						</div>
					</div>
					<div class="jp-current-time">00:00</div>
					<div class="jp-duration">06:66</div>
					<div class="jp-controls-holder">
						<ul class="jp-controls">
							<li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>
							<li><a href="javascript:;" class="jp-play" tabindex="1" style="">play</a></li>
							<li><a href="javascript:;" class="jp-pause" tabindex="1" style="display: none;">pause</a></li>
							<li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>
							<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
							<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute" style="">mute</a></li>
							<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute" style="display: none;">unmute</a></li>
							<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume" style="">max volume</a></li>
						</ul>
						<div class="jp-volume-bar" style="">
							<div class="jp-volume-bar-value" style="width: 80%;"></div>
						</div>
						<ul class="jp-toggles">
							<li><a href="javascript:;" class="jp-full-screen" tabindex="1" title="full screen" style="">full screen</a></li>
							<li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="restore screen" style="display: none;">restore screen</a></li>
							<li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle" style="">shuffle</a></li>
							<li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="shuffle off" style="display: none;">shuffle off</a></li>
							<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat" style="">repeat</a></li>
							<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off" style="display: none;">repeat off</a></li>
						</ul>
					</div>
					<div class="jp-title" style="display: none;">
						<ul>
							<li>If you see this<span class="jp-artist">Something went wrong... Sorry</span></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="jp-playlist">
				<ul style="display: block;"><li class="jp-playlist-current"><div><a href="javascript:;" class="jp-playlist-item-remove" style="">×</a><a href="javascript:;" class="jp-playlist-item jp-playlist-current" tabindex="1">Cro Magnon Man <span class="jp-artist">by The Stark Palace</span></a></div></li></ul>
			</div>
			<div class="jp-no-solution" style="display: none;">
				<span>Update Required</span>
				To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
			</div>
		</div>
		</div>

</body>