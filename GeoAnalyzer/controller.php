<?php

//include utils functions
include('functions.php');

    //if the button is submited then go on
    if($_POST['stage'] == 'output') {

             //if we have a text and a template then we go on
             if(isset($_POST['analyze']) && $_POST['analyze'] != '' && isset($_POST['template']) && $_POST['template'] != '') {

                      //filter input
                      $content = filter_input(INPUT_POST, 'analyze',FILTER_SANITIZE_STRING, array("flags" => array(FILTER_FLAG_STRIP_LOW,FILTER_FLAG_STRIP_HIGH)));

                      //make a copy of content
                      $buffercontent = $content;

                      //strip slashes
                      $template = stripslashes($_POST['template']);

                      //POST to Placemaker
                      $places = postToPlacemaker($content);

                      //get XML with data
                      $places = simplexml_load_string($places, 'SimpleXMLElement',LIBXML_NOCDATA); 

                      //if we have places in TEXT then do it
                      if($places->document->placeDetails) {

                           $locations = array();

                           $list_of_woeids = array();

                           $output = "["; 

                           $foundplaces = array();

                                 //loop over through placeDetails 
                                 foreach($places->document->placeDetails as $p) {

                                         $woeid = 'woeid'.$p->place->woeId;

                                         $vector_of_woeids[] = $p->place->woeId;

                                         $foundplaces[$woeid] = array(
                                                                      'name'=> str_replace(', ZZ','',$p->place->name.''),

                                                                      'type'=>$p->place->type,

                                                                      'woeid'=>$p->place->woeId,

                                                                      'lat'=>$p->place->centroid->latitude,

                                                                      'lon'=>$p->place->centroid->longitude
                                                                     );

                                        $locations[] = '{"name":"'.str_replace(', ZZ','',$p->place->name).'","type":"'.$p->place->type.'","woeid":"'.$p->place->woeId.'","lat":"'.$p->place->centroid->latitude.'","lon":"'.$p->place->centroid->longitude.'"}';

                                 }//endforeach      


                            $output .= join(",",$locations);   

                            $output .= "]";


                           /*
                            *   Find photos on the Flickr.com for these woeids
                            */

                            $v = '(';

                            $v .= join(",",$vector_of_woeids); 

                            $v .= ')';

                            $root = 'http://query.yahooapis.com/v1/public/yql?q=';
                                     
                            $yql = "select * from flickr.photos.info where photo_id in (select id from flickr.photos.search where woe_id in $v and license=4)";

                            $query = $root . urlencode($yql) . '&diagnostics=false&format=json';

                            $x = get($query);

                            $json = json_decode($x);

                            $results = $json->query->results->photo;

                              //if we have photos then fetch through and hold the photo in li element
                              if($results) {       

                                    $photos = '<ul id="photos">';

                                    foreach($results as $p) {

                                           $href = 'http://www.flickr.com/photos/'.$p->owner->nsid.'/'.$p->id;

                                           $src = 'http://farm'.$p->farm.'.static.flickr.com/'.$p->server.'/'.$p->id.'_'.$p->secret.'_s.jpg';

                                           $photos .='<li><a href="'.$href.'" title="'.$p->title.' ('.$p->location->locality->content.')"><img src="'.$src.'" alt="'.$title.'" /></a></li>';

                                    }//endforeach

                                 $photos .='</ul>';

                              }//endif
                
                      }//endif

                      //if we have reference then go on
                      if($places->document->referenceList->reference) {

                           $history = array(); 

                               foreach($places->document->referenceList->reference as $r) {

                                    foreach($r->woeIds as $w) {

                                      if(!in_array($w.'',$history)){

                                         $history[] = $w.'';

                                         $currentloc = $foundplaces['woeid'.$w];

                                         if($r->text != '' && $currentloc['name'] != '' && $currentloc['type'] != '' && $currentloc['lat'] != '' && $currentloc['lon'] != '') {
 
                                                $text = preg_replace('/\n/','',$r->text);   

                                                $text = preg_replace('/\s+/',' ',$r->text);
                                               
                                                $buffercontent = preg_replace('/'.$r->text.'/','<strong>'.$text.'</strong>',$buffercontent);

                                                $lat = $currentloc['lat'];

                                                $lon = $currentloc['lon'];

                                                $mf = preg_replace('/%place%/',$text,$template);

                                                $mf = preg_replace('/%lat%/',$lat,$mf); 

                                                $mf = preg_replace('/%lon%/',$lon,$mf); 
       
                                                $content = preg_replace('/'.$r->text.'/',$mf,$content);                                            

                                         }//endif 

                                       }//endif

                                   }//endforeach

                              }//endforeach

                         $stage = 'output'; 

                      } else {

                             $stage = 'input';

                             $errors = 'I couldn\'t find any geographical locations in that text.';
                      }

             } else {


                    $stage = 'input';

                    $errors = 'You didn\'t send anything to be analysed. I am confused.';

             }

    }//end if

?>