var placemaker = function() {

    var config = {

         appID: 'INSERT-ID-HERE'  
    };

    function analyseUrl(url,callback,locale) {

             if(config.appID === 'INSERT-ID-HERE') {

                      alert('Invalide ID application. Please override the configuration');

             } else {

                    var yql = 'use "http://www.datatables.org/geo/geo.placemaker.xml" as geo.placemaker; ' + 

                        'select * from geo.placemaker where documentURL="' + 

                                  encodeURIComponent(url) + '" and documentType="text/html" and ' + 

                                            'appid="'+config.appID+'"';    

                    if(locale !== '') {

                            yql += ' and inputLanguage="' + locale + '"';  
                    }           

                    if(typeof callback === 'function') {

                                       placemaker.callback = callback;
                    }//endif

                    getJSON(yql);
             }

    } 

    function analyseText(text,callback,locale) {

             if(config.appID === 'INSERT-ID-HERE') {

                      alert('Invalide ID application. Please override the configuration');

             } else {

                    var yql = 'use "http://www.datatables.org/geo/geo.placemaker.xml" as geo.placemaker; ' + 

                            'select * from geo.placemaker where documentContent="' + 

                                  encodeURIComponent(text) + '" and documentType="text/plain" and ' + 

                                            'appid="'+config.appID+'"';    

                    if(locale !== '') {

                            yql += ' and inputLanguage="' + locale + '"';  
                    }           

                    if(typeof callback === 'function') {

                                       placemaker.callback = callback;
                    }//endif         

                    getJSON(yql);
             }

    };

    function analyseFeed() {/*TODO*/}

    function getJSON(yql) { 

             var base = 'http://query.yahooapis.com/v1/public/yql?q=' + encodeURIComponent(yql) + '&format=json&callback=placemaker.seed';

             var script = document.createElement('script');
                
                 script.setAttribute('type','text/javascript');

                 script.setAttribute('src',base);

                 document.getElementsByTagName('head')[0].appendChild(script);
    }; 

    function seed(json) {

           if(json.query.results === null) { placemaker.callback({error: 'No results found.'}); }

                   else {

                        var data = json.query.results.matches || {error: 'No results found.'};

                            placemaker.callback(data);
                      }
    };

    return{config: config,getPlacesFromText: analyseText,getPlacesFromUrl:analyseUrl,seed:seed};

}();
