var YMAPPID = "OrnaXSnV34F26uD7a0DyZY9XdBtz4_YfDNG7EcX69S1Adk1SqFK8FYKRB4Gbjr4-";

var tweetlocations = function() {

     function placeonmap(json) {
 
              if(json.length > 0) {

                      var geopoints = [];

                      var map = new YMap(document.getElementById('map'));

                          map.addZoomLong();

                          map.addPanControl();

                          for(var i=0;i<json.length;i++) {

                                  var point = new YGeoPoint(json[i].lat,json[i].lon);

                                      geopoints.push(point);

                                  var newMarker = new YMarker(point,'marker'+json[i].id);

                                  var label = '<div class="tweetonmap"><span><a href="http://twitter.com/'+ json[i].user +'">' + json[i].user + '</a></span><br/><img src="'+json[i].avatar+'" alt="avatar" /><br/>' + tc.clean(json[i].tweet) + '</div>';

                                      newMarker.addAutoExpand(label);

                                      map.addOverlay(newMarker);
                          }//endfor

                              var zac = map.getBestZoomAndCenter(geopoints);   

                                        map.drawZoomAndCenter(zac.YGeoPoint,zac.zoomLevel);

                              //handler for click
                              YAHOO.util.Event.on('mydata','click',function(e){

                                       var target = YAHOO.util.Event.getTarget(e);

                                           while(target.nodeName.toLowerCase() !== 'tr') {

                                                 target = target.parentNode;

                                           }//endwhile

                                              var id = target.id.replace('tweet','marker');

                                              var marker = map.getMarkerObject(id);

                                                  if(marker) {
       
                                                          map.panToLatLon(marker.YGeoPoint);

                                                          marker.openSmartWindow(marker._expContent);

                                                  }//endif
                              });
     

 
              }//endif 

     };


     return {placeonmap: placeonmap} 
}();//do EXE


     var tc = function() {

              function Link(t) {

                       return t.replace(/(^|\s+)(https*\:\/\/\S+[^\.\s+])/g, function(x,y,z){

                              return ((y != '') ? ' ': '') + '<a href="'+ z +'">'+ ((z.length > 25) ? z.substr(0,24) + '...' : z) +'</a>';
                       }); 
              };

              function At(t) {

                       return t.replace(/(^|\s+)\@([a-zA-Z0-9_-]{1,15})/g, function(x,y,z){

                              return ((y != '') ? ' ': '') + '@<a href="http://twitter.com/'+ z +'">'+ z +'</a>';
                       }); 
              };

              function Hash(t) {

                       return t.replace(/(^|\s+)\#([a-zA-Z0-9_-]+)/g, function(x,y,z){

                              return ((y != '') ? ' ': '') + '#<a href="http://search.twitter.com/search?q=%23'+ z +'">'+ z +'</a>';
                       }); 

              };

              function clean(tweet) {

                     return this.Link(this.At(this.Hash(tweet))); 
              };

              return {clean: clean,Link: Link,At: At,Hash: Hash} 
     }();