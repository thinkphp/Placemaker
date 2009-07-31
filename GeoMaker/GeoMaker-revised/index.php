<?php include('gm_controller.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <title><?php echo$labels[$stage]['title'];?></title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" type="text/css" href="geomaker.css">
   <script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/yuiloader/yuiloader-min.js"></script> 
</head>
<body class="yui-skin-sam">
<div id="doc" class="yui-t7">

 <div id="hd" role="banner"><h1>GeoMaker</h1></div>

   <div id="bd" role="main">

           <?php include('gm_tabs.php');?>

           <div class="intro"><?php echo $labels[$stage]['intro'];?></div>  

           <form action="index.php" method="post"> 

               <?php

                 if($stage == 'start'){

                      include('gm_input.php');

                 }//endif


                 if($stage == 'filter'){

                      include('gm_filter.php');

                 }//endif


                 if($stage == 'output'){

                      include('gm_output.php');

                 }//endif


               ?>
                   
           </form> 

   </div><!-- end main -->

   <?php include('gm_footer.php'); ?>
   <script type="text/javascript" charset="utf-8" src="geomaker.js"></script>
</div><!-- end doc -->

</body>
</html>
