<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Geo Microformats analyzer</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
   <link rel="stylesheet" href="index.css" type="text/css">
</head>
<body>
<div id="doc" class="yui-t7">

   <div id="hd" role="banner"><h1>Geo<span>Microformats</span> analyzer</h1></div>

<div id="bd" role="main">

 <div class="yui-g">

    <div class="yui-u first">

      <form action="index.php" method="post" accept-charset="utf-8"> 

        <label for="analyze">Text to analyze</label>
        <textarea name="analyze" id="analyze">My name is Adrian. I am a German living in London, England and one of my favorite places to go is Caracas. I also enjoyed Brazil a lot.</textarea>

        <label for="template">Microformat template</label>
        <textarea name="template" id="template"><span class="vcard"><span class="adr"><span class="locality">%place%</span></span><span class="geo">(<span class="latitude">%lat%</span>,<span class="longitude">%lon%</span>)</span></span></textarea>

        <input type="submit" value="find geo data"/>

     </form>

    </div><!-- end yui-u first -->

    <div class="yui-u">

<?php
      if(isset($_POST['analyze'])) {

               //get free text
               $content = $_POST['analyze'];

               //get template
               $template = stripslashes($_POST['template']);

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

            //create an object from XML
            $places = simplexml_load_string($x,'SimpleXMLElement',LIBXML_NOCDATA);  

            //array of foundplaces
            $foundplaces = array();

               //if we have placeDetails then loop
               if($places->document->placeDetails) {

                       //loop over places and create an array with the woeid as the KEY
                      foreach($places->document->placeDetails as $p) {

                        $woeid = 'woeid'.$p->place->woeId;

                        $foundplaces[$woeid] = array('name'=> str_replace(', ZZ','',$p->place->name.''),

                                                     'type'=>$p->place->type.'',

                                                     'woeid'=>$p->place->woeId.'',

                                                     'lat'=>$p->place->centroid->latitude.'',

                                                     'lon'=>$p->place->centroid->longitude.'');  

                       }//end foreach

               }//endif

            //loop over the refereces and over the woeIds
            $refs = $places->document->referenceList->reference;

            if($refs) { 

              $history = array();

               foreach($refs as $r) {

                   foreach($r->woeIds as $wi) {

                     if(!in_array($wi.'',$history)){

                        $history[] = $wi.'';

                       //get dataset connected with the current woeid
                       $currentloc = $foundplaces["woeid".$wi]; 

                       //check if all interesting data exists
                       //get the template and replace the placehonders
                       if($r->text != '' && $currentloc['name'] != '' 

                                               && $currentloc['lat'] != ''

                                                   && $currentloc['lon'] != '') {

                                $lat = $currentloc['lat'];   

                                $lon = $currentloc['lon'];

                                $mf = preg_replace('/%place%/',$r->text,$template); 

                                $mf = preg_replace('/%lat%/',$lat,$mf); 

                                $mf = preg_replace('/%lon%/',$lon,$mf); 

                                $content = preg_replace('/'.$r->text.'/',$mf,$content);

                       }//end if

                     }//endif !in_array

                   }//endforeach

               }//end foreach    

             }//endif

    }//end if POST analyze
?>

          <label for="markup">Marked up text to copy</label>
          <textarea id="markup"><?php echo$content; ?></textarea>

   </div><!--end yui-u -->
 
 </div><!-- end yui-g -->


<?php if(isset($content)) { ?>
<!-- START OUTPUT -->
 <div class="yui-g"><!-- start yui-g -->
         <?php echo'<h2>Preview Microformatted Text</h2><p class="microformatted">'.$content.'</p>'; ?>
 </div><!--end yui-g -->

<?php
      }//endif
?>
<!-- END OUTPUT -->

</div><!-- end main -->

   <div id="ft" role="contentinfo"><p>written by <a href="http://thinkphp.ro">Adrian Statescu</a> using Placemaker</p></div>

</div><!-- end doc -->

</body>
</html>
