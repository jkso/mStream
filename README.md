mStream
=======

mStream is a personnal cloud mp3 player.  


Features
=======
- Stream your music from anywhere
- Download your music on the go.  I use this to move music from my computer to my phone wihtout a cable
- Can get file ID3 info
- Works on mobile devices

Demo
=======


Here's mStream on my personnal testing server:
https://www.youtube.com/watch?v=jpbYraAVjDY


SETUP
=======
You need a web server of some kind installed.  I've been using Apache but any standard server should work.
You must have your mp3 files in your webroot.  The easiest way to do this is to create a symlink from your mp3 directory to your webroot.

Once you have you mp3 direcotry setup, you have to edit a few variables
	- Open up editme.php (it's in the config folder)
	- Edit the three variables in it.
	
And that's it.  Put your server URL into your broswer and stream away.


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