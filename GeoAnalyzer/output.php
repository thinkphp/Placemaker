<!-- START OUTPUT -->
<div class="yui-g">

     <div class="yui-u first">

        <h2>Your Text Analyzed</h2>
        <div name="outputText" id="outputText"><?php echo$buffercontent;?></div>

     </div>

     <div class="yui-u">

        <h2>Get Microformatted Text</h2>
        <textarea name="outputMicroformat" id="outputMicroformat"><?php echo$content;?></textarea>

     </div>

   </div>

<div class="yui-g">

     <div class="yui-u first">

        <h2>Your Map code</h2>

        <textarea name="outputMap" id="outputMap"><?php include('map.php');?></textarea>

     </div> 

     <div class="yui-u"> 

        <?php echo'<h2>Preview</h2><p class="preview">'.$content.'</p>';?>

     </div>  

</div>
        <h2>Your Map</h2>

<?php include('map.php');?>

<div class="yui-g">

     <div class="yui-u first">

        <h2>Your Photos<a href="yql.txt">YQL statements</a></h2>

        <?php echo$photos;?>     

     </div>  

     <div class="yui-u"> 

        <h2>Your YQL</h2>

        <textarea name="statementyql" id="statementyql"><?php echo$yql;?></textarea>

     </div>  

</div>
<!-- END OUTPUT -->
