var YMAPPID = "OrnaXSnV34F26uD7a0DyZY9XdBtz4_YfDNG7EcX69S1Adk1SqFK8FYKRB4Gbjr4-";

rssmap = function() {

       //call this function with a JSON array
       function placeonmap(json) {

                //if there are locations
                if(json.length > 0) {
                       
                    //create a new geopoints array to hold all locations
                    //this is needed to determine the original zoom level of the map
 
                    var geopoints = [];

                    //add map with controls

                    var map = new YMap(document.getElementById('map'));

                        map.addZoomLong();

                        map.addPanControl();

                            //loop over locations

                              for(var i=0;i<json.length;i++) {

                                    var point = new YGeoPoint(json[i].lat,json[i].lon);

                                        geopoints.push(point);

                                         //create a new marker and give it the unique id defined in PHP.
                                        //Pop up the title of the news item and the name of the location 
                                       //when the user hovers over the marker

                                        var newMarker = new YMarker(point,json[i].id);
    
                                            newMarker.addAutoExpand(json[i].title + '('+ json[i].name +')') 

                                            map.addOverlay(newMarker);
                              }//end for     

                              //define best zoom

                               var zac =  map.getBestZoomAndCenter(geopoints);

                                          map.drawZoomAndCenter(zac.YGeoPoint,zac.zoomLevel);


                              YAHOO.util.Event.on('news','mouseover',function(e){

              
                                 if(YAHOO.util.Event.getTarget(e).nodeName.toLowerCase() === 'a') {

                                    //get target
                                    var id = YAHOO.util.Event.getTarget(e).id;
                                 
                                         //remove the "newss" text of the ID of the current target
                                        //as we named the list items newss0 to newss19
                                        id = id.replace("newss","");

                                        //if there is still something left we have one of the news items
                                        if(id !== ''){

                                             //get the first marker with the ID we defined in the loop

                                             var marker = map.getMarkerObject('m'+id+'x0');

                                             //if there is one pan the map there and show them message attached  to it

                                               if(marker) {
  
                                                     map.panToLatLon(marker.YGeoPoint);

                                                     marker.openAutoExpand();

                                               }//endif
                                        }//endif

                                    }//endif


                                    YAHOO.util.Event.preventDefault(e);

                              });//end handler                       

   
                }//end if

       };//end function

       return{placeonmap: placeonmap}
}();