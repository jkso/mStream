<!-- jQuery -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>



        <!-- all the important responsive layout stuff -->
        <style>
                .container { max-width: 90em; }

                /* you only need width to set up columns; all columns are 100%-width by default, so we start
                   from a one-column mobile layout and gradually improve it according to available screen space */

                @media only screen and (min-width: 34em) {
                        .feature, .info { width: 50%; }
                }

                @media only screen and (min-width: 54em) {
                        .content { width: 66.66%; }
                        .sidebar { width: 33.33%; }
                        .info    { width: 100%;   }
                }

                @media only screen and (min-width: 76em) {
                        .content { width: 58.33%; } /* 7/12 */
                        .sidebar { width: 41.66%; } /* 5/12 */
                        .info    { width: 50%;    }
                }
        </style>





<script>
/*
 * HTML5 Sortable jQuery Plugin
 * http://farhadi.ir/projects/html5sortable
 * 
 * Copyright 2012, Ali Farhadi
 * Released under the MIT license.
 */
(function($) {
var dragging, placeholders = $();
$.fn.sortable = function(options) {
        var method = String(options);
        options = $.extend({
                connectWith: false
        }, options);
        return this.each(function() {
                if (/^enable|disable|destroy$/.test(method)) {
                        var items = $(this).children($(this).data('items')).attr('draggable', method == 'enable');
                        if (method == 'destroy') {
                                items.add(this).removeData('connectWith items')
                                        .off('dragstart.h5s dragend.h5s selectstart.h5s dragover.h5s dragenter.h5s drop.h5s');
                        }
                        return;
                }
                var isHandle, index, items = $(this).children(options.items);
                var placeholder = $('<' + (/^ul|ol$/i.test(this.tagName) ? 'li' : 'div') + ' class="sortable-placeholder">');
                items.find(options.handle).mousedown(function() {
                        isHandle = true;
                }).mouseup(function() {
                        isHandle = false;
                });
                $(this).data('items', options.items)
                placeholders = placeholders.add(placeholder);
                if (options.connectWith) {
                        $(options.connectWith).add(this).data('connectWith', options.connectWith);
                }
                items.attr('draggable', 'true').on('dragstart.h5s', function(e) {
                        if (options.handle && !isHandle) {
                                return false;
                        }
                        isHandle = false;
                        var dt = e.originalEvent.dataTransfer;
                        dt.effectAllowed = 'move';
                        dt.setData('Text', 'dummy');
                        index = (dragging = $(this)).addClass('sortable-dragging').index();
                }).on('dragend.h5s', function() {
                        if (!dragging) {
                                return;
                        }
                        dragging.removeClass('sortable-dragging').show();
                        placeholders.detach();
                        if (index != dragging.index()) {
                                dragging.parent().trigger('sortupdate', {item: dragging});
                        }
                        dragging = null;
                }).not('a[href], img').on('selectstart.h5s', function() {
                        this.dragDrop && this.dragDrop();
                        return false;
                }).end().add([this, placeholder]).on('dragover.h5s dragenter.h5s drop.h5s', function(e) {
                        if (!items.is(dragging) && options.connectWith !== $(dragging).parent().data('connectWith')) {
                                return true;
                        }
                        if (e.type == 'drop') {
                                e.stopPropagation();
                                placeholders.filter(':visible').after(dragging);
                                dragging.trigger('dragend.h5s');
                                return false;
                        }
                        e.preventDefault();
                        e.originalEvent.dataTransfer.dropEffect = 'move';
                        if (items.is(this)) {
                                if (options.forcePlaceholderSize) {
                                        placeholder.height(dragging.outerHeight());
                                }
                                dragging.hide();
                                $(this)[placeholder.index() < $(this).index() ? 'after' : 'before'](placeholder);
                                placeholders.not(placeholder).detach();
                        } else if (!placeholders.is(this) && !$(this).children(options.items).length) {
                                placeholders.detach();
                                $(this).append(placeholder);
                        }
                        return false;
                });
        });
};
})(jQuery);
</script>

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



<!-- The following are all for jplayer -->
<link href="jPlayer24/skin/prettify-jPlayer.css" rel="stylesheet" type="text/css" />
<link href="jPlayer24/skin/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jPlayer/jquery.jplayer/jquery.jplayer.js"></script>
<!-- <script type="text/javascript" src="jPlayer/add-on/jplayer.playlist.js"></script> -->

<link rel="stylesheet" href="css/grid.css">
<link rel="stylesheet" href="css/screen.css">


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
		//get the mp3 name and the directory its in
		// var filename=$(this).attr("id");

		addFile(this);

		$('#playlist').sortable();
	});


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

	}


// when you click 'add directory', add entire directory to the playlist
	$("#addall").on('click', function() {
		//make an array of all the mp3 files in the curent directory
		var elems = document.getElementsByClassName('filez');
   		var arr = jQuery.makeArray(elems);


		//loop through array and add each file to the playlist
		$.each( arr, function() {
			// var filename=$(this).attr("id");

			addFile(this);

		});

		$('#playlist').sortable();
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
	$('#downloadPlaylist').click(function(){
		// encode entire playlist data into into array
		// var elems = document.getElementsByClassName('dragable');
   		var elems = $('ul#playlist li');
   		var arr = jQuery.makeArray(elems);


   		var sendthis = {};

   		var $sendthis = $(sendthis);


   		string_a= $('#currentdir').val();
		string_b= $('#currentdirlong').val();
		first_occurance=string_b.indexOf(string_a);
		if(first_occurance==-1)
		{
		     alert('Search string Not found')   
		}else
		{
		    string_a_length=string_a.length;
		    if(first_occurance==0)
		    {
		     new_string=string_b.substring(string_a_length);
		    }else
		    {
		        new_string=string_b.substring(0,first_occurance);
		        new_string+=string_b.substring(first_occurance+string_a_length);  
		    }
		    //    alert(new_string);
		}
 



   		var n = 0;
		//loop through array and add each file to the playlist
		$.each( arr, function() {
			// // var filename=$(this).attr("id");
			// console.log($(this).data('songurl'));
			// //addFile(this);
			// var lol = $(this).data('songurl');
			// // sendthis.push({n:lol});
			// //sendthis.n = lol;
			// $sendthis.prop(n, lol);
			$('<input>').attr({
			    type: 'hidden',
			    name: n,
			    value: new_string + $(this).data('songurl')
			}).appendTo('#downform');
			n++;
		});
		console.log('yo');
		console.log(sendthis);

		// $.post('zipplaylist.php', arr, function(retData) {
		//   //$("body").append("<iframe src='zipplaylist.php' style='display: none;' ></iframe>");
		// 	//$('#downframe').attr('src', "zipplaylist.php");
		// 	console.log(retData);
		// });

		//submit form
		$('#downform').submit();

		$('#downform').empty();

		// $.ajax({
		//   type: "POST",
		//   url: "zipplaylist.php",
		//   data: sendthis
		//   //data: {"0":"/audiofiles/Dovregubben copy.mp3", "1":"/audiofiles/thethec.mp3"}  //sendthis
		// })
		//   .done(function( msg ) {
		// 	$('#downframe').attr('src', "zipplaylist.php");
		// 	// console.log(msg);
		//   });


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



	// $("#jquery_jplayer_1").bind($.jPlayer.event.play, function(event) { // Add a listener to report the time play began

	// 	 var jpData = $("#jquery_jplayer_1").data('jPlayer');
	// 	 console.log(jpData);
	// });	

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
<!-- Form Elements Here -->  
</form>  
<div id="iframeholder">
	<iframe id="downframe" src="" width="0" height="0" tabindex="-1" title="empty" class="hidden" hidden name="frameframe"></iframe>
</div>


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
						<ul class="jp-toggles">
							<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
							<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
						</ul>
					</div>
					<div class="jp-title">
						<ul>
							<li></li>
						</ul>
					</div>
					<div class="jp-no-solution">
						<span>Update Required</span>
						To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
					</div>
				</div>
			</div>


			<div id="playlist_container">
				<ul id="playlist">
				</ul>
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