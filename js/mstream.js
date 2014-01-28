$(document).ready(function(){   


// Setup jPlayer
	var jPlayer = $("#jquery_jplayer_1").jPlayer({
		ready: function () {
			// NOTHING!
		},
		swfPath: "jPlayer/jquery.jplayer/Jplayer.swf",
		supplied: "mp3",
		smoothPlayBar: true,
		keyEnabled: true,
		// audioFullScreen: true
	});


// this code sets up the file browser.  It runs once when the page loads and is never used again
	//set a hidden input to the curent directory values
	$('#currentdir').val(startdir);
	//send this directory to be parsed and displayed
	senddir(startdir);

	$('.directoryName').html(startdir);



// when you click an mp3, add it to the playlist
	$("#filelist").on('click', 'div.filez', function() {

		addFile(this);

	});

// Adds file to playlist
	function addFile(that){
		var filename = $(that).attr("id");
		var title = $(that).find('span.title').html();
		var directory=$('#currentdir').val();
		//put these together to send to jPlayer
			// Must use escape because some special characters (like: ?) cause jPlayer to spaz out
		var mp3location = directory+filename;


		$('ul#playlist').append(
		    $('<li/>', {
		        'data-songurl': mp3location,
		        'class': 'dragable',
		        html: '<span class="play1">'+title+'</span><a href="javascript:void(0)" class="closeit">X</a>'
		    })
		);

		$('#playlist').sortable();

	}





	$('#save_playlist_form').on('submit', function(e){
		e.preventDefault();
		var playlistElements = $('ul#playlist li');
   		var playlistArray = jQuery.makeArray(playlistElements);

   		var title = $('#playlist_name').val();

   		var stuff = [];

   		// Check for special characters
   		if(/^[a-zA-Z0-9-_ ]*$/.test(title) == false) {
			console.log('do not do that');
			return false;	
		}

		//loop through array and add each file to the playlist
		$.each( playlistArray, function() {

			stuff.push($(this).data('songurl'));

		});


		$.ajax({
		  type: "POST",
		  url: "savem3u.php",
		  data: {
		  	title:title,
		  	stuff:stuff
		  },
		})
	    .done(function( msg ) {

	    	if(msg==1){
	    	}
	    	if(msg==0){

	    		$('#playlist_list').append('<li><a data-filename="' + title + '.m3u">' + title + '</a></li>')
	    	}

	    });


	});





	$('#playlist_list').on('dblclick', 'li', function(){
		var filename = $(this).find('a').data('filename');
	    var name = $(this).find('a').html();

		// Make an AJAX call to get the contents of the playlist
		$.ajax({
		  type: "POST",
		  url: "playlists/playlist_parser.php",
		  data: {filename: filename},
		  dataType: 'json',
		})
	    .done(function( msg ) {
	    	// Add the playlist name to the modal
	    	$('#playlist_name').val(name);

	    	// Clear the playlist
			$('#playlist').empty();

	    	// Append the playlist items to the playlist
			$.each( msg, function(i ,item) {
      			$('ul#playlist').append(
			    	$('<li/>', {
			        'data-songurl': item,
			        	'class': 'dragable',
			    	    html: '<span class="play1">'+item+'</span><a href="javascript:void(0)" class="closeit">X</a>'
			    }));
			});


			$('#playlist').sortable();
	    });


	});



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
		var curdir=$('#currentdir').val();
		var location = curdir+adddir+'/';

		//update the hidden fileds with the new location
		$('#currentdir').val(location);
		$('.directoryName').html(location);


		//pass this value along
		senddir(location);
	});

// when you click the back directory
	$(".backButton").on('click', function() {
		if($('#currentdir').val() != startdir){
			//get the html of that class
			var curdirshort=$('#currentdir').val();
			var location = curdirshort+'../';

			//break apart the directory into an array of strings.  This will be used to chop off the last directory
			var arrayOfStrings = curdirshort.split('/');

			var builddir='';

			//loop through an construct new currentDirectory variables
			for (var i=0; i < arrayOfStrings.length-2; i++){
				builddir=builddir+arrayOfStrings[i]+'/';
			}

			$('#currentdir').val(builddir);
			$('.directoryName').html(builddir);


			senddir(location);
		}
	});

	$('body').on('click', 'a.closeit', function(e){
		$(this).parent().remove();
	});

// clear the playlist
	$("#clear").click(function() {
		$('#playlist').empty();
	    $('#playlist_name').val('');

	});

// Download Directory  
// Downloads uses hidden iframe
	$("#download").click(function() {
		var dirz = encodeURIComponent( $('#currentdir').val() );
		$('#downframe').attr('src', "zipdir.php?dir="+dirz);
	});

// Download Playlist
// Submits form to hidden iframe
	$('#downloadPlaylist').click(function(){
		// encode entire playlist data into into array
   		var playlistElements = $('ul#playlist li');
   		var playlistArray = jQuery.makeArray(playlistElements);

   		var n = 0;
		//loop through array and add each file to the playlist
		$.each( playlistArray, function() {
			$('<input>').attr({
			    type: 'hidden',
			    name: n,
			    value: $(this).data('songurl')
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
		$.post('dirparser.php', {dir: dir, scrape: scrape}, function(response) {
		    // hand this data off to be printed on the page
		    printdir(response);
		});
	}

// function that will recieve JSON from dirparser.php.  It will then make a list of the directory and tack on classes for functionality
	function printdir(dir){
		var dirty = $.parseJSON(dir);

		//clear the list
		$('#filelist').empty();

		//parse through the json array and make an array of corresponding divs
		var filelist = [];
		$.each(dirty, function() {
			if(this.type=='mp3'){
				if(this.artist!=null || this.title!=null){
					filelist.push('<div id="'+this.link+'" class="filez"><span class="pre-char">&#9836;</span> <span class="title">'+this.artist+' - '+this.title+'</span></div>');
				}
				else{
					filelist.push('<div id="'+this.link+'" class="filez"><span class="pre-char">&#9835;</span> <span class="title">'+this.link+'</span></div>');
				}
			}
			if(this.type=='dir'){
				filelist.push('<div id="'+this.link+'" class="dirz">'+this.link+'</div>');
			}
		});

		//add a listing to go back
		if($('#currentdir').val() != startdir){
			// filelist.push('<div id=".." class="back">..</div>');
			$('.backButton').prop('disabled', false);
		}else{
			$('.backButton').prop('disabled', true);
		}

		$('#filelist').html(filelist);
	}





/////////////////////   The Following Code is for the playlist 


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
	$('#playlist').on( 'click', 'li span', function() {
		var mp3 = $(this).parent().data('songurl');
		
		$('#playlist li').removeClass('current');
		$(this).parent().addClass('current');

		// Add that URL to jPlayer
		$('#jquery_jplayer_1').jPlayer("setMedia", {
			mp3: escape(mp3),
		});
		$('#jquery_jplayer_1').jPlayer("play");
	});


});

