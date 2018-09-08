
<style>
form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px }
#progress { position:relative; width:350px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
#bar { background-color: #91C313; width:0%; height:20px; border-radius: 3px; }
#percent { position:absolute; display:inline-block; top:3px; left:48%; }
</style>
<?php
include_once("../../netwarelog/catalog/conexionbd.php");
$q=mysql_query("Select * from agenda where activo=1 and id=".$_POST["id"]);
$row=mysql_fetch_array($q);
?>
<textarea class="ckeditor" cols="60" rows="10" id="descripcion" name="descripcion" style="width:350;">
<?=$row["descripcion"]?>
</textarea>

<form id="myForm" action="uploadfile.php" method="post" enctype="multipart/form-data">
     <input type="hidden" id="id" name="id" value="<?=$_POST["id"]?>">
     <input type="file" size="60" name="myfile">
     <input type="submit" value="Adjuntar archivo al expediente">
 </form>
 
 <div id="progress">
        <div id="bar"></div>
        <div id="percent">0%</div >
</div>
<br/>
    
<div id="message"></div>


<script>
$(document).ready(function()
{

	var options = { 
    beforeSend: function() 
    {
    	$("#progress").show();
    	//clear everything
    	$("#bar").width('0%');
    	$("#message").html("");
		$("#percent").html("0%");
    },
    uploadProgress: function(event, position, total, percentComplete) 
    {
    	$("#bar").width(percentComplete+'%');
    	$("#percent").html(percentComplete+'%');
    },
    success: function() 
    {
		
        $("#bar").width('100%');
    	$("#percent").html('100%');

    },
	complete: function(response) 
	{
		$("#message").html("<font style='color:#91C313'>"+response.responseText+"</font>");
	},
	error: function()
	{
		$("#message").html("<font color='red'> ERROR: No se pudo adjuntar el archivo</font>");

	}
     
}; 

     $("#myForm").ajaxForm(options);

});

</script>