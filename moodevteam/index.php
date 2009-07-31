<?php
 
    require_once('placemaker.class.php');

    $root = 'http://query.yahooapis.com/v1/public/yql?q=';

    $yql = 'select * from html where url="http://mootools.net/developers" and xpath="//div[@class=\'dev\']"';

    $query = $root. urlencode($yql) . '&format=json&diagnostics=false';   

    $json = get($query);  
 
    $content = json_decode($json);

    //create a JSON Object to call function placeonmap
    $json = '[';

    //initialize an array
    $locations = array();

    //create a table to put out correctly and pretty the records from external data
    $output = '<table id="mydata" border="1">';   

    $output .= '<thead><th>User</th><th>Location</th><th>Type</th><th>Woeid</th><th>Latitude</th><th>Longitude</th></thead>'; 

    $output .= '<tbody>'; 

             //if there are results then fetching through results
             if($content->query->results && $content->query->results->div) {

                              //loop over the results and hold the needed variables
                              foreach($content->query->results->div as $d) {

                                      $nickname = $d->h2->span;

                                      $user = $d->h2->content;

                                      $user = preg_replace("/\n|\t|\r/","",$user);

                                      $w = $d->p[0]->a; 

                                         //if we have an array then loop ever
                                         if(is_array($w)) {

                                             foreach($w as $as) {

                                                     $website = $as->href;

                                                     break;

                                             }//endfor

                                          //otherwise do it
                                          } else {

                                             $website = $w->href;

                                          }
 
                                      //make link
                                      $website = preg_replace("/(http:[^\s]+)/","<a href=\"$1\">$1</a>",$website);

                                      //get avatar 
                                      $avatar = $d->div->img->src;

                                      $loc = split(":",$d->p[1]->content);  

                                      //get location
                                      $location = trim($loc[1]);

                                      //get places from Placemaker
                                      $obj = new Placemaker($location);

                                      $obj->getResultAsText();

                                      $type = $obj->getType();

                                      $woeid = $obj->getWoeid();

                                      $latitude = $obj->getLat(); 

                                      $longitude = $obj->getLon();

                                      $output .='<tr id="'.$woeid.'"><td><strong>'.$user.'</strong><em>'.$nickname.'</em><br/>'.$website.'</td><td>'.$location.'</td><td>'.$type.'</td><td>'.$woeid.'</td><td>'.$latitude.'</td><td>'.$longitude.'</td></tr>';

                                      $locations[] = '{"name":"'.$user.'","avatar":"'.$avatar.'","Name":"'.$location.'","type":"'.$type.'","woeid":"'.$woeid.'","lat":"'.$latitude.'","lon":"'.$longitude.'"}';

                              }//endforeach

             }//endif   

    $output .= '</tbody>';

    $output .= '</table>';

    $json .= join(",",$locations);

    $json .= ']';    

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Moo Dev Team</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
   <style type="text/css">

       html,body{background:#333;color:#ccc;font-family:"Arial Rounded MT Bold",Calibri,Futura,Sans-serif;}

       #bd {background:#efe;border:10px solid #efe;color:#000;}

       span{color: #3f3;}

       #intro{font-size:130%}

       h1{ font-size:400%; margin:0; padding-bottom:10px; color:#393;}

       #bd{-moz-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;}

       thead th{background:#363;color:#fff;}

       #map {height:300px; width:100%;}

       #map th, #map table, #map td, #map img {padding:0; margin:0; border:none;}

       #mydata tr:hover td,#result tr:hover th{background:#cfc;}

       #mydata {margin-top: 20px}

       #mydata {width: 100%}

       #mydata tr td p{width: 160px;font-weight: bold}

       .label span{color: #999;font-family: tahoma} 

       em{font-style: oblique;}

       a{color: #222;font-family: tahoma;font-size: 11px}

       #ft{margin:2em 0;font-family:Arial,Sans-serif;text-align:right;}

       #ft a {color:#eee;}


   </style>
</head>
<body>

<div id="doc" class="yui-t7">

   <div id="hd" role="banner"><h1>Moo<span>Dev</span>Team</h1></div>

   <div id="bd" role="main">

	<div class="yui-g">

                  <div id="map"></div>

	</div><!-- end yui g-->

        <div class="yui-g">
 
                  <?php echo$output; ?>

	</div><!-- end yui g-->

    </div><!-- end bd -->

   <div id="ft" role="contentinfo"><p>&copy; Written by <a href="http://thinkphp.ro">Adrian Statescu</a>. Using YUI, Yahoo! Maps and Placemaker</p></div>

</div><!-- end doc -->

<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/utilities/utilities.js"></script>
<script type="text/javascript" src="http://l.yimg.com/d/lib/map/js/api/ymapapi_3_8_2_3.js"></script>
<script type="text/javascript" src="moodevteam.js"></script>
<script type="text/javascript">moodevteam.placeonmap(<?php echo$json; ?>)</script> 

</body>
</html>

<?php

    //use cURL
    function get($url) {

         $ch = curl_init();

         curl_setopt($ch,CURLOPT_URL,$url);

         curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

         curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);

         $data = curl_exec($ch);

         curl_close($ch);

         if(empty($data)) {

                 return "Server Timeout"; 

         } else {

                 return $data; 
         }
  
    }//end function get content

?>