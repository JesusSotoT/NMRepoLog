<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

<script>
$(document).ready(function(){
	$("#i1529").select2({ width : "150px" });
	});
<?php
if($_REQUEST['a'] != 0){ ?>
$('#i1529').empty();
	$.post('../../modulos/bancos/models/antes.php',{opc:1,idbancaria:0},
	function(resp){
		$("#i1529").html(resp);
	});

<?php } else{ ?>
	
	$.post('../../modulos/bancos/models/antes.php',{opc:1,idbancaria:$('#i1529').val()},
	function(resp){
		$("#i1529").html(resp);
	});
	
<?php
}
?>

</script>


