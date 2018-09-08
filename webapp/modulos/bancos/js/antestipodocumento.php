<script>
<?php if($_REQUEST['a'] == 0){?>
	$("#i1789").attr('onchange','return validaRelacion()');
	$("#i1683").attr('onchange','return validaTipo()');
	var tipo = $("#i1683").val();
	function validaRelacion(){
		if($("#i1789").val()==2){
			$.post("../../modulos/bancos/models/antes.php",{
				idtipo:$("#i1680").val(),
				opc:12
			},function(status){
				if(status==1){
					alert("No puede inactivar el tipo\nYa esta relacionado a un documento.");
					$("#i1789").val(1);
				}
			});
		}
	}
	function validaTipo(){
		if($("#i1683").val()!=tipo){
			$.post("../../modulos/bancos/models/antes.php",{
				idtipo:$("#i1680").val(),
				opc:12
			},function(status){
				if(status==1){
					alert("No puede cambiar el clasificador\nEl tipo ya esta relacionado a un documento.");
					$("#i1683").val(tipo);
				}
			});
		}
	}
<?php } ?>
</script>