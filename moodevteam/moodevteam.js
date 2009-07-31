
var YMAPPID = "OrnaXSnV34F26uD7a0DyZY9XdBtz4_YfDNG7EcX69S1Adk1SqFK8FYKRB4Gbjr4-";

//show love to the Module Pattern
var moodevteam = function() {

              function placeonmap(json) {

                      if(json.length > 0) {

                            var geopoints = [];

                            var map = new YMap(document.getElementById('map'));
 
                                map.addZoomLong();

                                map.addPanControl();

                            for(var i=0;i<json.length;i++) {

                                  var point = new YGeoPoint(json[i].lat,json[i].lon);

                                      geopoints.push(point);

                                  var newMarker = new YMarker(point,'marker' + json[i].woeid);

                                  if(json[i].avatar.indexOf('http') !== -1) {

                                       var label = '<div class="label"><span>'+ json[i].name +'</span><br/><img src="' + json[i].avatar + '"></div>'; 
 
                                  } else {

                                       var label = '<div class="label"><span>'+ json[i].name +'</span><br/><img src="http://mootools.net' + json[i].avatar + '"></div>'; 
                                  }

                                       newMarker.addAutoExpand(label);

                                       map.addOverlay(newMarker);
                            }//endfor

                              var zac = map.getBestZoomAndCenter(geopoints);   

                                        map.drawZoomAndCenter(zac.YGeoPoint,zac.zoomLevel);

                             YAHOO.util.Event.on('mydata','click',function(e){

                                        var target = YAHOO.util.Event.getTarget(e);

                                            while(target.nodeName.toLowerCase() !== 'tr') {

                                                       target = target.parentNode; 

                                            }//endwhile

                                            var id = target.id;

                                            var marker = map.getMarkerObject('marker' + id);

                                                if(marker) {
       
                                                          map.panToLatLon(marker.YGeoPoint);

                                                          marker.openSmartWindow(marker._expContent);

                                                }//endif
 
                             });  

                            

                       }//endif

              };

              return {placeonmap: placeonmap} 

}();//do EXEC