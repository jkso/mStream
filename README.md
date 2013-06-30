mStream
=======

mStream is a small script that you can use to browse a directory for mp3 files.  jPlayer is used to stream files to wherever you are.  


SETUP
=======

There's three variables you need to edit before this will work.  Here's what you need to do:

-Open up playexplore.php
-Edit lines 19, 21, 23
-Save and enjoy

NOTE: You need to have your music in your webroot.  I used a symlink to make this easy.


LOGIN
=======
This comes with a very simple login system.  It's very basic because this is mean for personnal use and setting up a user database would be overkill.

The login system comes disabled.  To enable it:
- Open login.php
- Line 9 says 'f($_POST["pword"]=="99bottlesofbeer"){'
	- replace '99bottlesofbeer' with your new password
- Open index.php
- Line 5 says '$_SESSION["login"]=1;'
 	 - Comment it out or delete it