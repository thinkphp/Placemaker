<?php include('controller.php');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>GeoAnalyzer</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
   <link rel="stylesheet" href="geoanalyze.css" type="text/css">
</head>
<body>
<div id="doc" class="yui-t7">
 <div id="hd" role="banner"><h1>Geo<span>Analyzer</span></h1></div>
   <div id="bd" role="main">

<!-- START INPUT -->
<div class="yui-g">

 <form action="index.php" method="post" accept-charset="utf-8"> 

        <?php include('input.php');?>

        <input type="hidden" name="stage" id="stage" value="output"/>
        <input type="submit" value="find geo data"/>
 </form>

</div><!-- end class yui-g -->
<!-- END INPUT -->

<?php

   if($stage == 'output') {

            include('output.php');

   }//end if

   if($stage == 'input' && isset($errors)) {

            echo'<!-- start error --><p class="error">'.$errors.'</p><!-- end error -->';

   }//endif
?>

</div><!--end main -->

   <div id="ft" role="contentinfo"><p>Wrtitten by <a href="http://thinkphp.ro">Adrian Statescu</a> Using YUI, Yahoo! Maps and Placemaker</p></div>

</div><!-- end doc -->

</body>
</html>
