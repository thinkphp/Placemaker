<?php


include('apifunctions.php');
include('converters.php');



        if(!isset($_GET['url'])) {

?>

        <pre>
             GeoMaker API
             ------------       
              
             Simply send parameters and GeoMaker does stuff for you.

                    url (required)              - the URL to load and analyze
                    format (required)           - what to give back to you

                    format=microformats         - return the microformats HTML
                    format=kml                  - return data as KML
                    format=csv                  - return data as CSV
                    format=trek                 - return data as TREK
                    format=lol                  - return data as LOL
                    format=map                  - return js code for preview the geo data into a map(using Yahoo! Maps)
                    format=json&callback=seed   - does the same but wraps it into seed()
                    format=json                 - return a JSON object of matched locations as a JSON array of objects
                                                  each object has a latitude, longitude, title and match property


             Example:  
                    <a href="api.php?url=http://yahoo.com&format=json">http://thinkphp.ro/apps/geomaker/api.php?url=http://yahoo.com&format=json</a>
                    <a href="api.php?url=http://yahoo.com&format=json&callback=seed">http://thinkphp.ro/apps/geomaker/api.php?url=http://yahoo.com&format=json&callback=seed</a>
                    <a href="api.php?url=http://yahoo.com&format=map">http://thinkphp.ro/apps/geomaker/api.php?url=http://yahoo.com&format=map</a>

             Debugging:
                         If you set raw=true you can see the content retrieved from the URL
                         and the XML returned by Placemaker.
       
        </pre>         

<?php
        } else {

          $o = $_GET['format'];

          $key = 'z_MvdPDV34GB61qhSzVW8ZZ7UZsa00hBP5IadD7JJ8Rm7V74xkKAn0l4R.OUNBc-';

          $c = grab($_GET['url']);

               if(strstr($c,'<')) {

                       $c = preg_replace("/.*<results>|<\/results>.*/",'',$c);

                       $c = preg_replace("/<\?xml version=\"1\.0\" encoding=\"UTF-8\"\?>/",'',$c);

                       $c = strip_tags($c);

                       $c = preg_replace("/[\r?\n]+/"," ",$c);

                       $x = postToPlacemaker($c);

                       if(isset($_GET['raw'])){echo "<h1>Placemaker</h1>\n\n<pre>".htmlentities($x)."</pre>";};

                       $places = simplexml_load_string($x, 'SimpleXMLElement', LIBXML_NOCDATA);

                       $out = '';

                              if($places->document->placeDetails){ 

                                       $foundplaces = makePlacesHash($places);

                                       $refs = $places->document->referenceList->reference; 

                                                foreach($refs as $r){
      
                                                     foreach($r->woeIds as $wi){

                                                             $currentloc = $foundplaces["woeid".$wi];

                                                                    if($r->text!='' && $currentloc['name']!='' && $currentloc['lat']!='' && $currentloc['lon']!=''){

                                                                               $text = preg_replace('/\s+/',' ',$r->text);

                                                                               $current = $wi.'|'.str_replace(', ZZ','',$currentloc['name']).'|'.$text.'|'.$currentloc['lat'].'|'.$currentloc['lon'];

                                                                               $chunks = explode('|',$current);  
 
                                                                                          switch($o){

                                                                                            case 'microformats':
                                                                                            $mf[] = geomf($chunks);
                                                                                            break;

                                                                                            case 'json':
                                                                                            case 'map':
                                                                                            $points[] = json($chunks);
                                                                                            break;

                                                                                            case 'kml':
                                                                                            $kml[] = kml($chunks);
                                                                                            break; 

                                                                                            case 'csv':
                                                                                            $csv[] = csv($chunks);
                                                                                            break;

  
                                                                                            case 'lol':
                                                                                            $lol[] = lol($chunks);
                                                                                            break;

 
                                                                                             case 'trek':
                                                                                             $trek[] = trek($chunks);
                                                                                             break;

                                                                                           }//endswitch
                                                                    }//endif $r->text
 
                                                          }//endforeach $wi

                                                }//endforeach $r

     if($o=='kml'){

       $kml = implode("\n",$kml);

       header('content-type:application/vnd.google-earth.kml+xml');

       header('Content-disposition: attachment; filename=locations.kml');

       echo '<'.'?xml version="1.0" encoding="UTF-8"?'.'>';

       echo '<kml xmlns="http://www.opengis.net/kml/2.2"><Document>'.$kml;

       echo '</Document></kml>';
     }


     if($o=='map'){

       $points = '['.implode(',',$points).']';

       header('content-type:text/plain');

       include('mapcode.php');
     }


     if($o=='json'){

       $points = '['.implode(',',$points).']';

       header('content-type:text/javascript');

       if(isset($_GET['callback'])){

         echo $_GET['callback'].'('.$points.')';

       } else {

         echo $points;
       }

     }


     if($o=='microformats'){

       $mf = implode("\n",$mf);

       header('content-type:text/plain');

       echo $mf;

     }


     if($o=='lol'){

       $lol = implode("\n",$lol);

       header('content-type:text/plain');

       echo "O HAI\n$lol\nKTHNXBAI";

     }

     if($o=='trek'){

       $trek = implode("\n",$trek);

       header('content-type:text/plain');

       echo "Build:NCC1701\nEnergize\n$trek\nKhaaaaaaaaaaaaaaaaaaaaaaaan!";

     }


     if($o=='csv'){

       $csv = implode("\n",$csv);

       header('Content-type: application/vnd.ms-excel');

       header('Content-disposition: attachment; filename=locations.csv');

       echo 'woeid,name,description,latitude,longitude'."\n";

       echo $csv;
     }



                              }//endif $place->document->placeDetails                         

               }//endif strstr
                

        }//end if-else



?>