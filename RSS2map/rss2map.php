<?php
if(preg_match('/rss2map.php$/',$_SERVER['PHP_SELF'])) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>RSS2map - PHP script using Placemaker and Yahoo Maps to turn RSS feeds into map</title>
<link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
<link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
</head>
<body>
<?php

     }//endif
?>


<?php

    if(isset($_GET['feed']) || isset($rss2mapfeed)) {

             //$rss2mapfeed = 'http://www.hotnews.ro/rss/sport';

             $url = isset($rss2mapfeed) ? $rss2mapfeed : $_GET['feed'];

             $key = 'q0pcWFLIkY77xr0DLfxcK04QfkBMGvEe';

             $apiendpoint = 'http://wherein.yahooapis.com/v1/document'; 

             $inputType = 'text/rss';

             $outputType = 'rss';

             $post = 'appid='.$key.'&documentURL='.$url.'&documentType='.$inputType.'&outputType='.$outputType;  

             $ch = curl_init($apiendpoint);

             curl_setopt($ch, CURLOPT_POST, 1);

             curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

             $results = curl_exec($ch);

             curl_close($ch);  
 
             $results = preg_replace('/cl:/','cl',$results);

             $places = simplexml_load_string($results, 'SimpleXMLElement',LIBXML_NOCDATA);

             $output = '[';

             $locations = array();

             if($places->channel->item) {

                         foreach($places->channel->item as $p) {

                                 $locs = $p->clcontentlocation;

                                    if($locs) {
                                         
                                         foreach($locs->clplace as $p1) {

                                                $content = '<h2><a href=\"'.$p->link.'\">'.cleanup($p->title).'</a></h2><p>'.cleanup($p->description).'</p>';

                                                $locations[] = '{"name":"'.$p1->clname.'","title":"'.cleanup($p->title).'","lat":"'.$p1->cllatitude.'","lon":"'.$p1->cllongitude.'","content":"'.$content.'"}';

                                         }//endforeach

                                    }//endif

                         }//endforeach

             }//endif              

             if(isset($locations)) {

                  $output .= implode(',',$locations);

             }//endif

                  $output .= ']';

?>

            <div id="rss2map"></div>

            <script src="http://yui.yahooapis.com/2.6.0/build/utilities/utilities.js"></script>
            <script type="text/javascript" src="http://l.yimg.com/d/lib/map/js/api/ymapapi_3_8_2_3.js"></script>
            <script type="text/javascript" src="rss2map.js"></script>
            <script type="text/javascript"> 
                       rss2map(<?php echo$output; ?>);
            </script>

                  
<?php

    }//endif 

    //cleanup the content
    function cleanup($elem) {

             return preg_replace('/\n\r?+/','',addSlashes($elem));

    };//end function

if(preg_match('/rss2map.php$/',$_SERVER['PHP_SELF'])) {
?>
</body>
</html>
<?php
     }//endif
?>
