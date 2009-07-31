(function(){ 
  var loader = new YAHOO.util.YUILoader({ 
    base:'', 
    require:['button','datatable','utilities'], 
    loadOptional:false, 
    combine:true, 
    filter:'MIN', 
    allowRollup: true, 
    onSuccess:function(){ 
      YAHOO.util.Dom.batch(['analyze','loadcontent','final','restart'],
        function(o){
          if(o){
            var nicebutton = new YAHOO.widget.Button(o); 
          }
        }
      );

      var o = YAHOO.util.Dom.get('load');
      if(o){
        var l = o.getElementsByTagName('label')[0];
        var b = YAHOO.util.Dom.get('loadcontent');
        var i = YAHOO.util.Dom.get('url');
        var w = o.offsetWidth - b.offsetWidth - l.offsetWidth-20;
        YAHOO.util.Dom.setStyle(i,'width',w+'px');
        YAHOO.util.Dom.setStyle(i,'padding',0);
      }
      var myColumnDefs = [
        {key:"use",label:"Use",sortable:true},
        {key:"match",label:"Match",sortable:true},
        {key:"name",label:"Real Name",sortable:true},
        {key:"type",label:"Type",sortable:true},
        {key:"woeid",label:"WOE ID",sortable:true,parser:"number"},
        {key:"lat",label:"latitude",
         formatter:YAHOO.widget.DataTable.formatFloat,
         sortable:true,parser:"float"},
        {key:"lon",label:"longitude",
         formatter:YAHOO.widget.DataTable.formatFloat,
         sortable:true,parser:"float"}
      ];
      var myDataSource = new YAHOO.util.DataSource(
        YAHOO.util.Dom.get("foundresults")
      );
      myDataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE;
      myDataSource.responseSchema = { fields: myColumnDefs };
      var myDataTable = new YAHOO.widget.DataTable("markup", myColumnDefs,
       myDataSource,
       {
         caption:"Found locations",
         sortedBy:{
           key:"match",
           dir:"desc"
         }
       }
      )
    }
}); 
loader.insert(); 
})(); 


