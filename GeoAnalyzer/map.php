<div id="map"></div>
<script src="http://yui.yahooapis.com/2.6.0/build/utilities/utilities.js"></script>
<script type="text/javascript" src="http://l.yimg.com/d/lib/map/js/api/ymapapi_3_8_2_3.js"></script>
<script type="text/javascript">
var YMAPPID = "OrnaXSnV34F26uD7a0DyZY9XdBtz4_YfDNG7EcX69S1Adk1SqFK8FYKRB4Gbjr4-";
               //add your YMAPPID
function placeonmap(o){
  if(o.length > 0){
            var geopoints = [];
            var map = new YMap(document.getElementById('map')); 
                map.addZoomLong();
                map.addPanControl();
            for(var i=0;i<o.length;i++) {
                    var point = new YGeoPoint(o[i].lat,o[i].lon);
                                geopoints.push(point);
                    var newMarker = new YMarker(point);
                    newMarker.addAutoExpand(o[i].name);
                    map.addOverlay(newMarker);
           }//endfor 
           var zac = map.getBestZoomAndCenter(geopoints);
                      map.drawZoomAndCenter(zac.YGeoPoint,zac.zoomLevel);
  }//endif
}//end function
</script>
<script type="text/javascript"> 
      placeonmap(<?php echo$output; ?>);
</script>
