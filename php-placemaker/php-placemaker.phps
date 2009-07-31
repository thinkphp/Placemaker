<?php

       if(isset($_POST['getlocation']) && isset($_POST['raw']) && strlen($_POST['raw']) >0 && (isset($_POST['text']) || isset($_POST['url'])) ) {

                      header('content-type: text/xml');

                         if(isset($_POST['text']) && strlen($_POST['text']) > 0) {   

                               $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_ENCODED);
             
                               $key = 'q0pcWFLIkY77xr0DLfxcK04QfkBMGvEe'; 

                               define('POSTURL','http://wherein.yahooapis.com/v1/document');

                               define('POSTVARS','appid='.$key.'&documentContent='.$text.'&documentType=text/html&outputType=xml');

                               $xml = get(POSTURL,POSTVARS); 

                         } else if(isset($_POST['url']) && strlen($_POST['url']) > 0) {

                               $url = filter_input(INPUT_POST,'url',FILTER_SANITIZE_ENCODED);
             
                               $key = 'q0pcWFLIkY77xr0DLfxcK04QfkBMGvEe'; 

                               define('POSTURL','http://wherein.yahooapis.com/v1/document');

                               define('POSTVARS','appid='.$key.'&documentURL='.$url.'&documentType=text/html&outputType=xml');

                               $xml = get(POSTURL,POSTVARS); 
                         }

                     echo$xml;  

       } else {                       


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html> 
<head> 
 <title>PHPPlacemaker - geolocate web sites and texts with Yahoo! Placemaker in PHP</title> 
  <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css"> 
  <style>
  html,body{background: #E4F7C7;font-family: tahoma;}
  #bd{background: #fff;padding: 30px;border: 1em solid #fff;margin:10px;border-radius: 10px;-moz-border-radius: 10px;-webkit-border-radius: 10px;}
  #hd{text-align: center;font-size: 30px;font-weight: bold;color: #393;margin-top: 20px;} 
  form {background: #F2FFDF;width: 100%;}  
  #getlocation{width: 100px;width: 100px}
  h3{font-size: 20px;text-align: left;margin-top: 10px;color: #5A9780}
  h2{font-size: 15px;text-align: left;margin-top: 10px;color: #5A9780;font-family: monospace;background: #A1F79A;padding: 5px;border-radius: 10px;-moz-border-radius: 10px;-webkit-border-radius: 10px}
  table tr td{padding: 10px;text-align: left;width: 300px}
  table th{padding: 5px;font-weight: bold}
  table {border: 1px solid #393} 
  table caption{padding: 3px}
  table td{color: #5A9780}
  #ft{margin-top:2em;color:#999;font-size: 11px}
  #ft a{color:#666;}
  em{font-style: oblique}
  input,textarea{border: 1px solid #5A9780}
  </style>
</head> 
 <body> 
  <div id="doc" class="yui-t7"> 

   <div id="hd" role="banner"><h1>Geolocate web sites and texts with Yahoo! Placemaker</h1></div> 

       <div id="bd" role="main"> 

                  <div class="yui-g"> 
                     <form action="<?php echo$_SERVER['PHP_SELF'];?>" method="POST"> 
                     <table>
                       <tr><td>Enter web site URL:</td><td><input type="text" name="url" id="url" size="40"/></td><td>&nbsp;</td></tr>
                       <tr><td>Enter a text:</td><td><textarea cols="37" rows="5" name="text" id="text" ></textarea></td><td>&nbsp;</td></tr>
                       <tr><td><input type="checkbox" name="raw" id="raw" value="raw" /></td><td>Display results as raw XML</td></tr>
                       <tr><td><input type="submit" name="getlocation" id="getlocation" value="getlocation" /></td></tr>
                     </table>
                    </form>
                  </div> 

<?php

                   if(( isset($_POST['text']) || isset($_POST['url'] ) && $_POST['getlocation'])) {
          
?>
                  <div class="yui-g"> 

<?php

                  echo'<h3>Results</h3>';

                         if(isset($_POST['text']) && strlen($_POST['text']) > 0) {   

                               $text = filter_input(INPUT_POST,'text',FILTER_SANITIZE_ENCODED);
             
                               $key = 'q0pcWFLIkY77xr0DLfxcK04QfkBMGvEe'; 

                               define('POSTURL','http://wherein.yahooapis.com/v1/document');

                               define('POSTVARS','appid='.$key.'&documentContent='.$text.'&documentType=text/html&outputType=xml');

                               $xml = get(POSTURL,POSTVARS); 

                         } else if(isset($_POST['url']) && strlen($_POST['url']) > 0) {

                               $url = filter_input(INPUT_POST,'url',FILTER_SANITIZE_ENCODED);
             
                               $key = 'q0pcWFLIkY77xr0DLfxcK04QfkBMGvEe'; 

                               define('POSTURL','http://wherein.yahooapis.com/v1/document');

                               define('POSTVARS','appid='.$key.'&documentURL='.$url.'&documentType=text/html&outputType=xml');

                               $xml = get(POSTURL,POSTVARS); 

                         }
                    
                               $places = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

                               $urlsearch = isset($text) ? urldecode($text) : urldecode($url);

                               if($places->document->placeDetails) {

                                      echo'<table border="1">';

                                      echo '<caption>Locations for <em>"'.$urlsearch.'"</em></caption>';

                                      echo '<thead>';

                                      echo '<th scope="row">Name</th>';

                                      echo '<th scope="row">Type</th>';

                                      echo '<th scope="row">woeid</th>'; 

                                      echo '<th scope="row">Latitude</th>';

                                      echo '<th scope="row">Longitude</th>';

                                      echo '</thead>';

                                      echo '<tbody>';

                                       foreach($places->document->placeDetails as $p) {

                                             echo '<tr>';

                                             echo '<td>'.$p->place->name.'</td>';

                                             echo '<td>'.$p->place->type.'</td>';

                                             echo '<td>'.$p->place->woeId.'</td>';

                                             echo '<td>'.$p->place->centroid->latitude.'</td>';

                                             echo '<td>'.$p->place->centroid->longitude.'</td>';

                                             echo '</tr>';
                                       }

                                      echo'</table>';

                          } else {

                                      echo'<h2>'.'Could`n find any location for "'.$urlsearch.'".</h2>'; 
                          }

?>

                  </div> 


      </div> <!-- end bd -->

              <div id="ft" role="contentinfo"><p>Written by <a href="http://thinkphp.ro">Adrian Statescu.</a> Using YUI and Placemaker</p></div> 

      </div><!-- end doc -->


</body> 

</html> 


<?php
                  } else {
               
?>

                  <div class="yui-g"> 

                        <h2>Simply enter a URL or a TEXT in the following form and submit it. Placemaker then gives you the locations. Results can vary, so re-load the page if needed.</h2>

                  </div> 



      </div> <!-- end bd -->

              <div id="ft" role="contentinfo"><p>Written by <a href="http://thinkphp.ro">Adrian Statescu.</a> Using YUI and Placemaker</p></div> 

      </div><!-- end doc -->


</body> 

</html> 

<?php

                  }


             }//end final
?>

<?php 

     //use cURL
     function get($posturl,$postvars) {

              $ch = curl_init($posturl);

              curl_setopt($ch,CURLOPT_POST, 1);

              curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);

              curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 

              $buffer = curl_exec($ch);

              return $buffer;
        
     }//end function

?>