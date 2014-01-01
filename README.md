mStream
=======

mStream is a cloud mp3 player.  


Demo
=======

Here's a video of mStream in action:
https://www.youtube.com/watch?v=jpbYraAVjDY



SETUP
=======
This is kind of complex, so pay attention:
- Download mStream
- Place contents of download on your webserver.
- Add your mp3 files in the audiofiles directory.
- Open up playexplore.php in your web browser.
- Listen to your music.


LOGIN
=======
This comes with a very simple login system.  The password is hardcoded in a php file.

The login system comes disabled.  To enable it:
- Open login.php
- Line 9 says 'f($_POST["pword"]=="99bottlesofbeer"){'
	- Replace '99bottlesofbeer' with your new password
- Open index.php
- Line 5 says '$_SESSION["login"]=1;'
 	 - Comment it out or delete it