<?php

         //post data to Placemaker
         function postToPlacemaker($content) {

               //define API key and do the call to Placemaker
               $key = 'q0pcWFLIkY77xr0DLfxcK04QfkBMGvEe';

               //define POSTURL
               define('POSTURL','http://wherein.yahooapis.com/v1/document');

               //define POSTVARS
               define('POSTVARS','appid='.$key.'&documentContent='.urlencode($content).'&documentType=text/plain&outputType=xml');

               $ch = curl_init(POSTURL);

               curl_setopt($ch,CURLOPT_POST,1);

               curl_setopt($ch,CURLOPT_POSTFIELDS,POSTVARS);

               curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

               $x = curl_exec($ch);
 
               curl_close($ch);

               if(empty($x)) {return 'Server timeout. Please try again.';}
 
                         else 

                             {return $x;}  

        }//end function


       //get data from url
       function get($url) {

               $ch = curl_init();

               curl_setopt($ch,CURLOPT_URL,$url);

               curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

               curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);

               $x = curl_exec($ch);

               curl_close($ch);

               if(empty($x)) {return 'Server timeout. Please try again.';}
 
                         else 

                             {return $x;}  
       }//end function


?>