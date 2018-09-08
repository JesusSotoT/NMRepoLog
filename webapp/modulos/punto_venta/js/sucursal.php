<script type="text/javascript">
	$(document).ready(function(){
		$("td > input[value='...']").hide();
		
	});
	function editar_reg(){
		valor=$("#i1062").val();
		texto=$("#i1062 option[value='"+valor+"']").text();
		//	$("#i1062").prop( "disabled", true );
		//	$("#i1062").attr( "id", 'xxxxx' );
			$("#i1062").html("<option value='"+valor+"' selected>"+texto+"</option>");

		}
</script>

<?php
if($a==0){
	echo '<script type="text/javascript"> editar_reg(); </script>';
}

?>