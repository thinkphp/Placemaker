<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
   <title>GeoMaker - convert web sites and text into Maps and Geo Microformats</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="geomaker.css" type="text/css" />    
   <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/datatable/assets/skins/sam/datatable.css" />
   <script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/yuiloader/yuiloader-min.js"></script> 
</head>

<body class="yui-skin-sam">
<div id="doc" class="yui-t7">

  <div id="hd" role="banner"><h1>GeoMaker</h1></div>

  <div id="bd" role="main">

    <div id="tabs" class="yui-gb">

        <div class="yui-u first current">

             <h2><span>1</span>Input</h2><p>Enter or load content</p>

        </div>

        <div class="yui-u">

            <h2><span>2</span> Filter</h2><p>Pick geo locations</p>

        </div>

        <div class="yui-u">

           <h2><span>3</span>Output</h2><p>Get map and microformats code</p>

        </div>

    </div><!-- end yui-gb -->

    
   <div id="intro">GeoMaker creates microformats and maps from geographical information embedded in texts. You can either provide a URL to load and hit the "load content" button or start typing your own text and hit the "get locations" button to continue.</div>  

   <form action="index.php" method="post">

   <?php
         //if we have input and if submted then do it
         if((isset($_POST['url']) && strlen($_POST['url']) > 0 && $_POST['loadcontent'] === 'load content') || 

                 (isset($_POST['message']) && strlen($_POST['message']) > 0 && $_POST['analyze'] === 'get locations')) {

                           //endpoint placemaker
                           $root = 'http://query.yahooapis.com/v1/public/yql?q=';

                           $content_text = strip_tags($_POST['message']); 

                       //if we have an URL then go
                       if(isset($_POST['url']) && strlen($_POST['url']) > 0 && $_POST['loadcontent'] === 'load content') {

                               //statement YQL
                               $yql = 'SELECT * FROM geo.placemaker WHERE documentURL= "'.$_POST['url'].'" AND documentType="text/plain" AND appid = "q0pcWFLIkY77xr0DLfxcK04QfkBMGvEe"';

                       //if we have a free TEXT then do it
                       } else if(isset($_POST['message']) && strlen($_POST['message']) > 0 && $_POST['analyze'] === 'get locations') {

                               //statement YQL
                               $yql = 'SELECT * FROM geo.placemaker WHERE documentContent= "'.$_POST['message'].'" AND documentType="text/plain" AND appid = "q0pcWFLIkY77xr0DLfxcK04QfkBMGvEe"';

                       }
 
                         //assemble query
                         $query = $root . urlencode($yql). '&diagnostics=false&format=json&env=http%3A%2F%2Fdatatables.org%2Falltables.env';                          

                         //get json
                         $results = get($query);

                         //decode json
                         $content = json_decode($results);


                         //start zone markup
                         $output = '<div id="markup"><!-- start zone markup -->';    

                       //if we have mateches then puts into table
                       if($content->query->results->matches) {

                                             //start table
                                             $output .= '<table id="foundresults">';

                                             $output .= '<caption>Found Locations </caption>';

                                             $output .= '<thead><th scope="row">Use</th><th scope="row">Match</th><th scope="row">Real Name</th><th scope="row">Type</th><th scope="row">woeid</th><th scope="row">Latitude</th><th scope="row">Longitude</th></thead>';

                                             $output .= '<tbody>';

                               //if result is array then assemble them 
                              if($content->query->results->matches->match && is_array($content->query->results->matches->match)) {

                                       $matches = array();

                                       //loop over the matches and get the matches
                                       foreach($content->query->results->matches->match as $p) {

                                             $output .= '<tr>';

                                             $output .= '<td><input type="checkbox" checked="checked" name="collection[]" value="'.$p->place->woeId.'/'.$p->reference->text.'/'.$p->place->name.'/'.$p->place->type.'/'.$p->place->centroid->latitude.'/'.$p->place->centroid->longitude.'"></td>';

                                             $output .= '<td>'.$p->reference->text.'</td>';

                                             $output .= '<td>'.$p->place->name.'</td>';

                                             $output .= '<td>'.$p->place->type.'</td>';

                                             $output .= '<td>'.$p->place->woeId.'</td>';

                                             $output .= '<td>'.$p->place->centroid->latitude.'</td>';

                                             $output .= '<td>'.$p->place->centroid->longitude.'</td>';

                                             $output .= '</tr>';                    

                                             $text = preg_replace('/\n/','',$p->reference->text);

                                             $text = preg_replace('/\s+/',' ',$p->reference->text);

                                             $matches[] = $text;                                               

                                       }//endforach


                                  if(isset($_POST['message']) && $_POST['message'] != '') {

                                     //make a copy of the text
                                     $highlightedtext = $content_text;

                                     //for each match do just replace
                                     foreach($matches as $m) {

                                             $highlightedtext = preg_replace('/'.$m.'/','<strong>'.$m.'</strong>',$highlightedtext); 

                                     }//endforeach 

                                     //display the highlighted text
                                     echo'<h2>Your text analyzed:</h2><p id="highlighttext">'.$highlightedtext.'</p>';

                                   }//endif

                               //if matches is not array then get the match
                               } else if($content->query->results->matches->match && !is_array($content->query->results->matches->match)) {

                                             //get match
                                             $p = $content->query->results->matches->match;

                                             $output .= '<tr>';

                                             $output .= '<td><input type="checkbox" checked="checked" name="collection[]" value="'.$p->place->woeId.'/'.$p->reference->text.'/'.$p->place->name.'/'.$p->place->type.'/'.$p->place->centroid->latitude.'/'.$p->place->centroid->longitude.'"></td>';

                                             $output .= '<td>'.$p->reference->text.'</td>';

                                             $output .= '<td>'.$p->place->name.'</td>';

                                             $output .= '<td>'.$p->place->type.'</td>';

                                             $output .= '<td>'.$p->place->woeId.'</td>';

                                             $output .= '<td>'.$p->place->centroid->latitude.'</td>';

                                             $output .= '<td>'.$p->place->centroid->longitude.'</td>';

                                             $output .= '</tr>';

                                         if(isset($_POST['message']) && $_POST['message'] != '') {

                                             //replace new line and space with single space
                                             $text = preg_replace('/\n/','',$p->reference->text);
                                            
                                             $text = preg_replace('/\s+/',' ',$p->reference->text);

                                             //hold match
                                             $match = $text;

                                             //hold text message in variable hightlighedtext
                                             $highlightedtext = $content_text;

                                             //replace the match with tag <strong>match</strong>
                                             $highlightedtext = preg_replace('/'.$match.'/','<strong>'.$match.'</strong>',$highlightedtext);

                                             //display the text with that modification and the winner is...
                                             echo'<h2>Your text analyzed:</h2><p id="highlighttext">'.$highlightedtext.'</p>';

                                          }//endif

                                //otherwise we not have matches
                                } else {

                                       echo"No found matches."; 
                                }

                                             $output .= '</tbody>';

                                             //end table
                                             $output .= '</table>';

                                             $output .= '</div><!--end markup -->';

                                             $output .= '<div class="submit"><input type="submit" value="generate" id="final" name="final"></div>';

                                  //change tab menu
                                  echo'<script type="text/javascript">var tabs = document.getElementById("tabs");var divs = tabs.getElementsByTagName("div");divs[0].className = "yui-u first";divs[1].className += " current";document.getElementById("intro").innerHTML = "Cleanup time. As not all things machines find for us are really what we were looking for check the table below and uncheck results you don t want to have on your map. Possible duplicates have already been unchecked. Once you re done, hit the generate button to continue";</script>';

                                  echo"<h2>Results</h2>";

                                  //display table of data
                                  echo$output;

                       //display specific errors
                       } else {

                                   echo"<h2>Couldn`t find any location.</h2>";

                                   echo'<div class="submit"><input type="submit" value="Start over" id="restart" name="restart"></div>';
                       }


                  //if the button generate is submit then generate the map with afferent places
                  } else if(isset($_POST['final']) && $_POST['final'] === 'generate'){

                                  echo'<div id="map"></div><div class="submit"><input type="submit" value="Start over" id="restart" name="restart"></div>';

                                  echo'<script type="text/javascript">var tabs = document.getElementById("tabs");var divs = tabs.getElementsByTagName("div");divs[0].className = "yui-u first";divs[1].className = "yui-u";divs[2].className += " current";document.getElementById("intro").innerHTML = "And we\'re done. Below you\'ll see the map with your locations, the code to copy and paste to embed your own map and your locations as microformats.";</script>';

                                  $collection = $_POST['collection'];

                                  $out = array();

                                  $microformats = array();

                                  foreach($collection as $c) {

                                     list($woeid,$match,$name,$type,$lat,$lon) = split("/",$c);
   
                                     $out[] = '{"name":"'.$name.'","lat":"'.$lat.'","lon":"'.$lon.'"}';  

                                     $microformats[] = '<!-- match: '.$match.' -->'.
                                                       '<span class="vcard">'.
                                                       '<span class="adr">'.
                                                       '<span class="locality">'.$name.'</span>'.
                                                       '</span>'.
                                                        ' (<span class="geo">'.
                                                       '<span class="latitude">'.$lat.'</span>, '.
                                                       '<span class="longitude">'.$lon.'</span>'.
                                                       '</span>)'.
                                                       '</span>';
                                  }//endforeach

                                  $out = join(",",$out);

                                  $micro = join("\n\n",$microformats);

                                  echo'<script src="http://yui.yahooapis.com/2.7.0/build/utilities/utilities.js"></script>';
                                  echo'<script type="text/javascript" src="http://l.yimg.com/d/lib/map/js/api/ymapapi_3_8_2_3.js"></script>';
                                  echo'<script type="text/javascript">

                                       var YMAPPID = "PbXUT7HV34Fq2KhMd68qS.CRZY9RWjW_dEQLgINMwG.eNxu2hf84BTkvHNttEg4-";

                                  function placeonmap(o){

                                      if(o.length > 0){

                                              var geopoints = [];

                                              var map = new YMap(document.getElementById("map")); 

                                                  map.addZoomLong();

                                                  map.addPanControl();

                                                      for(var i=0;i<o.length;i++){

                                                          var point = new YGeoPoint(o[i].lat,o[i].lon); 

                                                              geopoints.push(point);

                                                          var newMarker = new YMarker(point,o[i].id);

                                                              newMarker.addAutoExpand(o[i].name);

                                                              map.addOverlay(newMarker);

                                                      }//endfor

                                                   var zac = map.getBestZoomAndCenter(geopoints);

                                                             map.drawZoomAndCenter(zac.YGeoPoint,zac.zoomLevel);
                                      }//endif

                                   }//end function
  
                                  </script>'; 

                                  echo'<script type="text/javascript">placeonmap(['.$out.'])</script>';

?>

<div class="yui-g">

<div class="yui-u first">

<h2>Your Map code</h2>

<p>Following is the code to generate the map above. For you to use it in your own products you need to apply for a free map developer key and replace the YMAPPID in the code with your own key.</p><br/>

<textarea>
<html>
<head>
<style>#map {width: 500px;height: 300px;}</style>
</head>
<body>
<div id="map"></div>
</body>
<script src="http://yui.yahooapis.com/2.7.0/build/utilities/utilities.js"></script>
<script type="text/javascript" src="http://l.yimg.com/d/lib/map/js/api/ymapapi_3_8_2_3.js"></script>
<script type="text/javascript">
var YMAPPID = "PbXUT7HV34Fq2KhMd68qS.CRZY9RWjW_dEQLgINMwG.eNxu2hf84BTkvHNttEg4-";
                                  function placeonmap(o){
                                      if(o.length > 0){
                                              var geopoints = [];
                                              var map = new YMap(document.getElementById("map")); 
                                                  map.addZoomLong();
                                                  map.addPanControl();
                                                      for(var i=0;i<o.length;i++){
                                                          var point = new YGeoPoint(o[i].lat,o[i].lon); 
                                                              geopoints.push(point);
                                                          var newMarker = new YMarker(point,o[i].id);
                                                              newMarker.addAutoExpand(o[i].name);
                                                              map.addOverlay(newMarker);
                                                      }//endfor
                                                  var zac = map.getBestZoomAndCenter(geopoints);
                                                            map.drawZoomAndCenter(zac.YGeoPoint,zac.zoomLevel);
                                      }//endif 
                                  }//end function placeonmap 
</script>
<?php  echo'<script type="text/javascript">placeonmap(['.$out.'])</script>'; ?>
</html>
</textarea>
</div><!-- end div yui-u first -->

<div class="yui-u">
<h2>Your Microformatted Locations</h2>
<p>If all you wanted is geolocate your text, here are the geo-microformats to copy and paste into the correct sections. Notice that we are not using the ABBR pattern as accessibility is something we care about.</p><br/>

<textarea>

<?php echo$micro ;?>

</textarea>
</div><!--end div yui-u -->


</div>

<?php


 
                         } else {

?>

         <h2>Get content from web:</h2>

         <div id="load">
              <label for="url">Load content from:</label><input type="text" name="url" id="url" value="">
              <input type="submit" id="loadcontent" value="load content" name="loadcontent">
         </div>


         <h2>or enter some text to analyze:</h2>
              <label for="message">Text content</label>
              <textarea name="message" id="message"></textarea>
              <div class="submit"><input type="submit" id="analyze" value="get locations" name="analyze"></div>


<?php } ?>

   </form><!--end form -->

</div><!-- end main -->

    <div id="ft" role="contentinfo"><p>Written by <a href="http://thinkphp.ro">Adrian Statescu</a> Using YUI, YQL, Yahoo! Geo Technologies</p></div>

</div><!-- end doc -->

<script type="text/javascript" charset="utf-8" src="geomaker.js"></script>

</body>
</html>


<?php

     //using cURL
     function get($url) {

              $ch = curl_init();

              curl_setopt($ch,CURLOPT_URL,$url);

              curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

              curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);

              $data = curl_exec($ch);

              curl_close($ch);

              if(empty($data)) {return "Server timeout.";}

                           else 

                               {return $data;}
     }//end function get

?>