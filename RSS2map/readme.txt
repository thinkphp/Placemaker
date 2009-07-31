RSS2map is a script that uses Placemaker and 
Yahoo Maps to display any RSS feed on a map. 
You don't need to know how to use maps or Placemaker, 
simply provide the feed URL and include rss2map.php where 
you want the map to appear.

<?php include('rss2map.php');?> 

If that is all you do, RSS2map will look for a URL parameter called feed 
that must contain the URL to a valid RSS feed. 
If one is provided it’ll load, analyse it and render 
a map at the place of the include.
If you don’t want to enable RSS reading via URL 
(which of course can be dangerous) you simply provide 
the RSS feed you want analysed and displayed as a value 
of a variable called $rss2mapfeed:

<?php

 $rss2mapfeed = 'http://www.hotnews.ro/rss/sport';
 include('rss2map.php');

?>



You can see an example of rss2map at:
http://localhost/placemaker/RSS2map/index.php?feed=http://www.hotnews.ro/rss/sport
