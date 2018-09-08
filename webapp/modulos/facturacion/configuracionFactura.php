<?php

	include "../netwarelog/webconfig.php";
	include "../netwarelog/catalog/conexionbd.php";

class configFactura{
	function configFactura($conexion_enviada){
        $this->conexion=$conexion_enviada;
    }
	
	function loadData(){	
		$result = $this->conexion->consultar("select id from configuracionFactura");
		if($rs = $this->conexion->siguiente($result)){
			return 1;
		}else
			return 0;
	}			
}

?>

<script>
/*
	function validaRfc(rfcStr) {
		var strCorrecta;
		strCorrecta = rfcStr;	
		if (rfcStr.length == 12){
			var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		}else{
			var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		}
		var validRfc=new RegExp(valid);
		var matchArray=strCorrecta.match(validRfc);
		if (matchArray==null) {
			return 0;
		}
		else
		{
			return 1;
		}	
	}
	*/

</script>

<div>
	<?php
		$menus = new configFactura($conexion); 
		if($menus->loadData()==1){ ?>
			<script>
				$(function() {
					$('#frm').html('Ya existe un RFC dado de alta');
				});
			</script>
		<?php }

	?>
</div>