<?php

    $appid = 'q0pcWFLIkY77xr0DLfxcK04QfkBMGvEe';

    $url = 'http://rss.news.yahoo.com/rss/topstories';

    $inputType = 'text/rss';

    $outputType = 'rss';

    define('POSTURL','http://wherein.yahooapis.com/v1/document');

    define('POSTVARS','appid='.$appid.'&documentURL='.$url.'&documentType='.$inputType.'&outputType='.$outputType);

    $rss = get(POSTURL,POSTVARS);

    $places = simplexml_load_string($rss, 'SimpleXMLElement',LIBXML_NOCDATA);

            //if there are elements found
              if($places->channel->item) {

                       //start a JSON array
                       $output = '[';
 
                          //start a HTML output
                            $html = '<ul id="news">';

                            //set up the counter - this will be needed to link news
                              $counter = 0;

                              //loop over the RSS items
                                foreach($places->channel->item as $p) { 

                                        //set innercount (as there are more locations per news)
                                         $innercount = 0;

                                          //start the HTML list item and give it an ID with counter value

                                            $html .= '<li id="news'.$counter.'"'; 

                                                     //all child elements with the defined namespace
  
                                                     $locs = $p->children('http://wherein.yahooapis.com/v1/cle');

                                                     //check if there is a location sub-element in this item

                                                       if($locs->contentlocation) {

                                                              //if there is one add a class to the LI

                                                                $html .=' class="hasLocation"';

                                                                  //start an array for displaying of the location under the news items  

                                                                    $dlocs = array();

                                                                         //loop over all the places found for this item
                                                                           foreach($locs->contentlocation->place as $pl) {

                                                                             //append a new JS object with location data and a unique ID to the location array
                                      
                                                                                     $locations[] = '{"name":"'.$pl->name.'","title":"'. preg_replace('/\n+/',"",addSlashes($p->title)) .'","lat":"'.$pl->latitude.'","lon":"'.$pl->longitude.'","id":"m'.$counter.'x'.$innercount.'"}';

                                                                                     $dlocs[] = $pl->name;

                                                                                     $innercount++;

                                                                           }//endforeach  
                                                       }//end if


               $html .= '><h2><a href="'.$p->link.'" id="newss'.$counter.'">'.$p->title.'</a></h2><p>'.$p->description.'</p>';

             //if locations were found add them
               if(sizeof($dlocs) > 0) {

                        $html .='<p class="locations">Locations: '.join(',',$dlocs).'</p>';
               } 

              $html .='</li>';

              //increase the counter

              $counter++;


                                }//endforeach 

               //join the json object data with a comma and close the JSON

                 $output .= join(',',$locations);

                 $output .= ']';

                 $html .= "</ul>";

              } else {

                  $output = "";
              }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Yahoo! News Map</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
   <style>

        html,body{ background: #ccc; color:#000;font-family:calibri,sans-serif;}

        #bd{background:#fff; border:5px solid #fff;color:#000;}

        #news{height:400px;overflow:auto;}

        #news h2{margin:0;padding:0;}

        #news img{padding-right:10px;}

        p.locations{color:#999;padding-top:5px;font-size:90%;}

        #map table, #map th, #map td{border:none;padding:0;}

        #map table div td{padding:2px;}

        #map {height: 410px; width: 100%;}

        #ft { margin-top: 2em;color:#999; font-size: 11px}

        #ft a{color:#666;}


   </style>
</head>
<body>
<div id="doc3" class="yui-t7">

   <div id="hd" role="banner"><h1>Yahoo! News Topstories Map</h1></div>

   <div id="bd" role="main">

	<div class="yui-g">

             <div class="yui-u first">

                         <div id="map"></div>
	    </div>

             <div class="yui-u">

                         <?php echo$html; ?>

	    </div>

       </div><!-- ned yui-g -->

 </div><!-- end bd -->

   <div id="ft" role="contentinfo"><p>written By <a href="http://thinkphp.ro">Adrian Statescu </a>.Using YUI, Yahoo! Maps and Placemaker</p></div>

</div><!-- end doc -->

<script src="http://yui.yahooapis.com/2.6.0/build/utilities/utilities.js"></script>
<script type="text/javascript" src="http://l.yimg.com/d/lib/map/js/api/ymapapi_3_8_2_3.js"></script>
<script type="text/javascript" src="rssmap.js"></script>
<script type="text/javascript">rssmap.placeonmap(<?php echo$output;?>)</script>
</body>
</html>

<?php

         //use cURL
         function get($endpoint,$postvars) {

                  $ch = curl_init($endpoint);

                  curl_setopt($ch,CURLOPT_POST,1);

                  curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);

                  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 

                  $data = curl_exec($ch);

                  curl_close($ch);

                  if(empty($data)) {return 'Server Timeout.';}

                           else
                                   {return $data;}

         }//end function

?>