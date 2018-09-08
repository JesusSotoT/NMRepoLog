<script src="js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<script>
$(document).ready(function(){
	var satd = $("#i2452").val();

		$.post("../../modulos/nominas/ajax.php?c=Catalogos&f=clasefactor&fac="+$("#i2451").val(),//select yena tipo operacion
         function(data) 
         {
         	$("#i2452").empty();
            $("#i2452").html(data).select2({ width : "380px"});
            $("#i2452").val(satd).select2({ width : "380px"});
	     });
	     
	$("#i2452").removeClass("nminputselect");
	$('#i2452').select2({ width : "380px"});
	$("#i2451").on('change',function(){
		$.post("../../modulos/nominas/ajax.php?c=Catalogos&f=clasefactor&fac="+$("#i2451").val(),//select yena tipo operacion
         function(data) 
         {
         	$("#i2452").empty();
            $("#i2452").html(data).select2({ width : "380px"});;
	     });
	});
 });

						                
</script>