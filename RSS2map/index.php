<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <title>RSS2map - a Placemaker / Yahoo! Maps mashup script generator</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
   <link rel="stylesheet" href="rss2map.css" type="text/css">
</head>
<body>
<div id="doc2" class="yui-t7">

   <div id="hd" role="banner"><h1>RSS2map - turn an RSS feed into a map</h1></div>

        <div id="bd" role="main">

                   <div class="yui-gd">

                              <div class="yui-u first">
          <?php

          $menu = array("Telegraph Travel"=>"index.php?feed=http://www.telegraph.co.uk/travel/rss",

                        "Telegraph News"=>"index.php?feed=http://www.telegraph.co.uk/news/rss",

                        "Telegraph Finance"=>"index.php?feed=http://www.telegraph.co.uk/finance/rss",

                        "Yahoo World News"=>"index.php?feed=http://rss.news.yahoo.com/rss/world",

                        "BBC World News"=>"index.php?feed=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/front_page/rss.xml",

                        "Guardian World"=>"index.php?feed=http://feeds.guardian.co.uk/theguardian/world/rss",

                        "Hotnews"=>"index.php?feed=http://www.hotnews.ro/rss/sport");
 
          echo"<ul>";

          foreach($menu as $k=>$v) {

                  if('index.php?feed='.$_GET['feed'] == $v) {
 
                      echo'<li>'.$k.'</li>';                 

                  }  else {

                      echo'<li><a href="'.$v.'">'.$k.'</a></li>';                 
                  }
          }    

          echo"</ul>";

          ?>



                              </div

                             <div class="yui-u">

                                   <?php include('rss2map.php');?>

                             </div>

                  </div><!-- end yui-u gd -->

       </div><!-- end main -->

   <div id="ft" role="contentinfo"><p>written by <a href="http://thinkphp.ro">Adrian Statescu</a> Using YUI, Yahoo! Maps and Placemaker</p></div>

</div><!-- end doc -->

</body>
</html>
