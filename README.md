mStream
=======

mStream is a personnal cloud mp3 player.  


SETUP
=======
You must have your mp3 files in your webroot.  The easiest way to do this is to create a symlink from your mp3 directory to your webroot.

Once you have you mp3 direcotry setup, you have to edit a few variables
	-Open up mstream.js
	-Edit lines 4, 6, 8 (look at the comments by these lines for more information).

And that's it.  Put your server URL into your broswer and stream away.


LOGIN
=======
This comes with a very simple login system.  The password is hardcoded in a php file.

The login system comes disabled.  To enable it:
- Open login.php
- Line 9 says 'f($_POST["pword"]=="99bottlesofbeer"){'
	- replace '99bottlesofbeer' with your new password
- Open index.php
- Line 5 says '$_SESSION["login"]=1;'
 	 - Comment it out or delete it