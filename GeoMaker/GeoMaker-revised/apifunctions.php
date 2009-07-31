<?php

    function postToPlacemaker($content) {

             global $key;

             $ch = curl_init();

             if(isset($_GET['lang'])) {

                      $lang = '&inputLanguage='.$_GET['lang'];
             }

             define(POSTURL,'http://wherein.yahooapis.com/v1/document');

             define(POSTVARS,'appid='.$key.'&documentContent='.urlencode($content).'&documentType=text/plain&outputType=xml'.$lang);

             $ch = curl_init(POSTURL);

             curl_setopt($ch, CURLOPT_POST, 1);

             curl_setopt($ch, CURLOPT_POSTFIELDS, POSTVARS);

             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

             $x = curl_exec($ch);

             curl_close($ch);

             if(empty($x)) {return 'Server timeout.';}

                       else 

                           {return $x;}               
    };//end function

    function grab($url) {

             $root = 'http://query.yahooapis.com/v1/public/yql?q=';

             $yql = 'select * from html where url = "'.$url.'"';

             $query = $root . urlencode($yql). '&diagnostics=false&format=xml';

             $ch = curl_init();

             curl_setopt($ch,CURLOPT_URL,$query); 

             curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

             $x = curl_exec($ch);

             curl_close($ch);

             if(empty($x)) {return 'Server timeout.';}

                       else 

                           {return $x;}               

    }//end function grab


    function makePlacesHash($places) {

             $foundplaces = array();

             foreach($places->document->placeDetails as $p) {

                     $wkey = 'woeid'.$p->place->woeId;

                     $foundplaces[$wkey] = array(
                                                 'name'=>str_replace('. ZZ','',$p->place->name).'',

                                                 'type'=>$p->place->type.'',

                                                 'woeId'=>$p->place->woeId,

                                                 'lat'=>$p->place->centroid->latitude.'',

                                                 'lon'=>$p->place->centroid->longitude.''
                                                ); 

             }//end foreach  

         return $foundplaces;   

    };//end function 

?>