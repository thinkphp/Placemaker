<?php
         require_once('placemaker.class.php');

         $root = 'http://query.yahooapis.com/v1/public/yql?q=';

         if(isset($_POST['user']) && strlen($_POST['user']) > 0) {

                  $username = $_POST['user'];

         } else {

                  $username = "thinkphp";
         }

         $yql = 'select user.name,user.screen_name,user.profile_image_url,user.location,user.status.text from xml where url="http://twitter.com/statuses/friends/'.$username.'.xml"';

         $query = $root . urlencode($yql). '&format=xml&diagnostics=false';

         $content = get_content($query);

         $xml = simplexml_load_string($content);
        
         if($xml->results->users) {

                    $locations = array();

                    $json = "[";

                    $output = '<table id="mydata" border="1"><thead><th>Tweet</th><th>User</td><th>Name</th><th>Type</th><th>Woeid</th><th>Latitude</th><th>Logitude</th></thead>'; 

                    $output .= '<tbody>';

                    $counter = 0;

                    foreach($xml->results->users as $u) {

                           $user = $u->user;

                              if($user->location) {

                                  $obj = new Placemaker($user->location);

                                  $obj->getResultAsText();

                                  $type = $obj->getType();

                                  $woeid = $obj->getWoeid();

                                  $lat = $obj->getLat();

                                  $lon = $obj->getLon();

                                  $tweet = preg_replace('/\'/','&#39;',$user->status->text);

                                  $tweet = preg_replace('/\./',' ',$user->status->text);

                                  $tweet = preg_replace('/\n|\t/',' ',$user->status->text);

                                  $tweet = addSlashes($tweet);     

                                  if(isset($woeid)) {

                                              $location = preg_replace("/\n|\t/","",$user->location);

                                              $screen_name = preg_replace("/\n|\t/","",$user->screen_name);

                                              $output .= '<tr id="tweet'.$counter.'"><td><p>'.trim($user->status->text).'</p></td><td>'.$screen_name.'</td><td>'.$location.'</td><td>'.$type.'</td><td>'.$woeid.'</td><td>'.$lat.'</td><td>'.$lon.'</td></tr>';

                                              $locations[] = '{"user":"'.$user->screen_name.'","avatar":"'.$user->profile_image_url.'","tweet":"'.$tweet.'","lat":"'.$lat.'","lon":"'.$lon.'","id":"'.$counter.'"}';

                                     $counter++;

                                  }//endif
 
                              }//endif
                        
                    }//endfor          

                    $output .= '</tbody>';

                    $output .= '</table>';

                    $json .= join(",",$locations);

                    $json .= "]";

         }//end if xml->results->users

?> 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Tweet Locations</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
   <style type="text/css">

       html,body{background:#333;color:#ccc;font-family:"Arial Rounded MT Bold",Calibri,Futura,Sans-serif;}

       #bd {background:#efe;border:10px solid #efe;color:#000;}

       span{color: #3f3;}

       #intro{font-size:130%}

       h1{ font-size:400%; margin:0; padding-bottom:10px; color:#393;}

       form{ background:#030; margin: 1em 0;color:#fff;padding: 1em; font-size:150%;}

       form,#bd{-moz-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;}

       thead th{background:#363;color:#fff;}

       #map {height:300px; width:100%;}

       #map th, #map table, #map td, #map img {padding:0; margin:0; border:none;}

       #mydata tr:hover td,#result tr:hover th{background:#cfc;}

       #mydata {margin-top: 20px}

       #mydata {width: 100%}

       #mydata tr td p{width: 160px;font-weight: bold}

       .tweetonmap {padding: 5px;}

       .tweetonmap a{color: #393}  

       .tweetonmap span a{color: #555;font-weight: bold;}

       #ft{margin:2em 0;font-family:Arial,Sans-serif;text-align:right;}

       #ft a {color:#eee;}

   </style>
</head>
<body>
<div id="doc" class="yui-t7">

   <div id="hd" role="banner"><h1>Tweet<span>Locations</span></h1></div>

   <div id="bd" role="main">

	<div class="yui-g">

                  <div id="intro"><p>TweetLocations analyses twitter friends`s profile and checks if contain any geographical locations.</p></div>

	</div>

        <div class="yui-g">

                  <form action="index.php" method="post" accept-charset="utf-8">
                      <div>
                        <label for="user">Twitter User ID:</label>
                        <input name="user" id="user" value="<?php if(isset($_POST['user']) && strlen($_POST['user']) > 0) {echo$_POST['user'];} else {echo'thinkphp';}?>">
                        <input type="submit" name="submit" value="Find tweet locations">
                      </div>
                  </form>


	</div>

        <div class="yui-g">

<?php if(isset($output)) { ?>

                  <div id="map"></div>

<?php } ?>

	</div>

         <div class="yui-g">

                     <?php if(isset($output)) {echo$output;} else {echo"Invalid username.";} ?>
	</div>

   </div><!-- end bd -->

   <div id="ft" role="contentinfo"><p>Written by <a href="http://thinkphp.ro">Adrian Statescu.</a> Using YUI, Twitter, Yahoo! Maps and Placemaker</p></div>

</div><!-- end doc -->

<?php if(isset($output)) { ?>

<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/utilities/utilities.js"></script>
<script type="text/javascript" src="http://l.yimg.com/d/lib/map/js/api/ymapapi_3_8_2_3.js"></script>
<script type="text/javascript" src="tweetlocations.js"></script>
<script type="text/javascript">tweetlocations.placeonmap(<?php echo$json;?>)</script>

<?php } ?>

</body>
</html>

<?php


         //use cURL for extract the informations from any url
         function get_content($url) {

                 $ch = curl_init();

                 curl_setopt($ch,CURLOPT_URL,$url);

                 curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

                 curl_setopt($ch,CURLOPT_connecttimeout,2);                  

                 $data = curl_exec($ch);

                 curl_close($ch);

                 if(empty($data)) {return 'Server Timeout';}

                            else

                                  {return $data;}   
         }//end function


?>