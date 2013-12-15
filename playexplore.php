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
<link href="jPlayer24/skin/prettify-jPlayer.css" rel="stylesheet" type="text/css" />
<link href="jPlayer24/skin/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jPlayer/jquery.jplayer/jquery.jplayer.js"></script>
<!-- <script type="text/javascript" src="jPlayer/add-on/jplayer.playlist.js"></script> -->

<link rel="stylesheet" href="css/grid.css">
<link rel="stylesheet" href="css/screen.css">

<script type="text/javascript" src="js/sortable.js"></script>



<script type="text/javascript">
$(document).ready(function(){   
//EDIT THESE VALUES:
	// 'startdir' is the location of the initial directory from your computer's root.  PHP needs this
	var startdir = '/Applications/MAMP/htdocs/audiofiles/';
	// 'startdirstripped' is the location of the initial directory from your server's webroot
	var startdirstripped = '/audiofiles/';
	// This is as far as you want the user going back.  A value of '/' means you want your user going back as far as your webroot
	var rootdir='/audiofiles/';
//DONE WITH EDITTING


// Setup jPlayer
	var jPlayer = $("#jquery_jplayer_1").jPlayer({
		ready: function () {
			// $(this).jPlayer("setMedia", {
			// 	mp3: '/audiofiles/thethec.mp3',
			// });
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

		addFile(this);

	});

// Adds file to playlist
	function addFile(that){
		var filename = $(that).attr("id");

		var title = $(that).html();

		var directory=$('#currentdir').val();
		//put these together to send to jPlayer
			// Must use escape because some special characters (like: ?) cause jPlayer to spaz out
		var mp3location = directory+filename;


		$('ul#playlist').append(
		    $('<li/>', {
		        'data-songurl': mp3location,
		        'class': 'dragable',
		        html: title
		    })
		);

		$('#playlist').sortable();

	}


// when you click 'add directory', add entire directory to the playlist
	$("#addall").on('click', function() {
		//make an array of all the mp3 files in the curent directory
		var elems = document.getElementsByClassName('filez');
   		var arr = jQuery.makeArray(elems);

		//loop through array and add each file to the playlist
		$.each( arr, function() {
			addFile(this);
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

			$('#currentdirlong').val(builddirlong);
			$('#currentdir').val(builddir);

			senddir(location);
		}
	});

// clear the playlist
	$("#clear").click(function() {
		$('#playlist').empty();
	});

// Download Directory  
// Downloads uses hidden iframe
	$("#download").click(function() {
		var dirz = encodeURIComponent( $('#currentdirlong').val() );
		console.log(dirz);
		$('#downframe').attr('src', "zipdir.php?dir="+dirz);
	});

// Download Playlist
// Submits form to hidden iframe
	$('#downloadPlaylist').click(function(){
		// encode entire playlist data into into array
   		var playlistElements = $('ul#playlist li');
   		var playlistArray = jQuery.makeArray(playlistElements);

   		// The following code was shamelesslt stolen from a Stack Overflow question
   		// It finds the difference between the two directory strings
   		string_a= $('#currentdir').val();
		string_b= $('#currentdirlong').val();
		first_occurance=string_b.indexOf(string_a);

	    string_a_length=string_a.length;
	    if(first_occurance==0)
	    {
	     new_string=string_b.substring(string_a_length);
	    }else
	    {
	        new_string=string_b.substring(0,first_occurance);
	        new_string+=string_b.substring(first_occurance+string_a_length);  
	    }

 

   		var n = 0;
		//loop through array and add each file to the playlist
		$.each( playlistArray, function() {

			$('<input>').attr({
			    type: 'hidden',
			    name: n,
			    value: new_string + $(this).data('songurl')
			}).appendTo('#downform');
			n++;
		});

		//submit form
		$('#downform').submit();
		// clear the form
		$('#downform').empty();

	});



// send a new directory to be parsed.
	function senddir(dir){
		// If the scraper option is checked, then tell dirparer to use getID3
		var scrape = $('#scraper').is(":checked");
		$.post('/mstream/dirparser.php', {dir: dir, scrape: scrape}, function(response) {
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



	// Core playlist functionality.  When a song ends, go to the next song
	$("#jquery_jplayer_1").bind($.jPlayer.event.ended, function(event) { // Add a listener to report the time play began

  		// Should disable any features that can cause the playlist to change
  		// This will prevent some edge case logic errors

  		// Check for playlist item with label "current song"
  		if($('#playlist').find('li.current').length!=0){
  			var current = $('#playlist').find('li.current');

  			// if there is a next item on the list
  			if($('#playlist').find('li.current').next('li').length!=0){
  				var next = $('#playlist').find('li.current').next('li');
  				// get the url in that item
  				var song = next.data('songurl');
  				// Add label of "current song" to this item
				current.toggleClass('current');
  				next.toggleClass('current');


  				// Add that URL to jPlayer
  				$(this).jPlayer("setMedia", {
					mp3: escape(song),
				});
				$(this).jPlayer("play");
  			}	
  			
  		}
	});





	// When an item in the playlist is clicked, start playing that song
	$('#playlist').on( 'click', 'li', function() {
		var mp3 = $(this).data('songurl');

		$('#playlist li').removeClass('current');
		$(this).addClass('current');

		// Add that URL to jPlayer
		$('#jquery_jplayer_1').jPlayer("setMedia", {
			mp3: escape(mp3),
		});
		$('#jquery_jplayer_1').jPlayer("play");
	});

	// Make the play list sortable
	$('#playlist').sortable();
});


</script>


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