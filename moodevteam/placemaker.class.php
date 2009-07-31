<?php

class Placemaker {

           //data members public
           public $documentType;

           public $outputType;

           public $endpoint;      
 
           public $appid;   

           public $text;

           //locations for places data members private
           private $woeid;

           private $type;

           private $name;

           private $lat;

           private $lon;

               //constructor of class placemaker
               public function __construct($text) {
         
                   $this->documentType = 'text/plain';

                   $this->outputType = 'xml';

                   $this->endpoint = 'http://wherein.yahooapis.com/v1/document';

                   $this->appid = 'q0pcWFLIkY77xr0DLfxcK04QfkBMGvEe';

                   $this->text = urlencode($text);

                   $postvars = 'appid='.$this->appid.'&documentType='.$this->documentType.'&documentContent='.$this->text.'&outputType='.$this->outputType;

                   $this->doit($this->endpoint,$postvars); 

               }//end method

               public function doit($endpoint,$postvars) {

                   $this->results = $this->get($endpoint,$postvars);

               }//end method

 
               public function getResultAsArray() {

                     $places = simplexml_load_string($this->results, 'SimpleXMLElement', LIBXML_NOCDATA);

                     $locations = array();
                 
                     if($places->document->placeDetails) {

                               foreach($places->document->placeDetails as $pl) {

                                       $locations[] = array("woeid"=>$pl->place->woeId,"type"=>$pl->place->type,"name"=>$pl->place->name,"lat"=>$pl->place->centroid->latitude,"lon"=>$pl->place->centroid->longitude);

                               }//end foreach
                     }//end if

                    return $locations;
               }               

               public function getResultAsText() {

                     $places = simplexml_load_string($this->results, 'SimpleXMLElement', LIBXML_NOCDATA);

                     if($places->document->placeDetails) {

                               foreach($places->document->placeDetails as $pl) {

                                       $this->woeid = $pl->place->woeId;

                                       $this->type = $pl->place->type;

                                       $this->name = $pl->place->name;

                                       $this->lat = $pl->place->centroid->latitude;

                                       $this->lon = $pl->place->centroid->longitude;

                               }//endforeach
                     }//end if

               }//end method

               public function getLat() {return $this->lat;}

               public function getLon() {return $this->lon;}

               public function getWoeid() {return $this->woeid;}

               public function getType() {return $this->type;}

               public function getName() {return $this->name;}

               public function get($endpoint,$postvars) {

                  $ch = curl_init($endpoint);

                  curl_setopt($ch,CURLOPT_POST,1);

                  curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);

                  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 

                  $data = curl_exec($ch);

                  curl_close($ch);

                  if(empty($data)) {return 'Server Timeout.';}

                           else
                                   {return $data;}

               }//end method

}//end class

?>