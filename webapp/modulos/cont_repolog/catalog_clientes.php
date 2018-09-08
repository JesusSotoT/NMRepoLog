<script language='javascript'>
$(document).ready(function(){
	if($("#i666").val() == "(Autonúmerico)")
	{
		$("#i2019").val(1).trigger("change")
		$("#i2032").val(1).trigger("change")
	}
});
</script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<style>
#s2id_i1464
{
	width:300px !important;
}
</style>
<?php
include("../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$sql=$conection->query('SELECT account_id, manual_code, description FROM cont_accounts where main_account = 3 AND removed=0 AND  currency_id = 1 AND main_father = (SELECT CuentaClientes FROM cont_config) ');
$lista="<option value='0'>NINGUNA</option>";
$num = $sql->num_rows;
echo "<input type='hidden' id='numerocuentas' value='$num'>";
while($c = $sql->fetch_object())
{
$lista .= "<option value='$c->account_id'>".utf8_encode($c->description)." (".$c->manual_code.")</option>";
} 
$sql2=$conection->query('SELECT account_id, manual_code, description FROM cont_accounts where main_account = 3 AND removed=0 AND  currency_id = 1 AND main_father = (SELECT CuentaProveedores FROM cont_config) ');
$listaprv="<option value='0'>NINGUNA</option>";
while($c = $sql2->fetch_object())
{
$listaprv .= "<option value='$c->account_id'>$c->description ($c->manual_code)</option>";
}
$bancos = false;
$bancosSql=$conection->query('select * from accelog_perfiles_me where idmenu=1932');
if($status = $bancosSql->num_rows>0){
$bancos=true;
}
$conection->close();
?>
<script src="js/select2/select2.min.js"></script>

<script language='javascript'>
$(document).ready(function(){
	
	<?php
	if(!$bancos){?>
		// $("tr[title='Cuenta Proveedor']").hide();
		// $("tr[title='Beneficiario/Pagador']").hide();
		// $("#i1687").val(0);
	<?php
		}
	?>
	 
	if(parseInt($("#numerocuentas").val()) <= 0)
	{
		$("#lbl1464").remove()
		$("#i1464").remove()
	}
		var valor = $("#i1464").val(); 
		var valorprv = $("#i1688").val();
		$("#i1464").empty().append("<?php echo $lista; ?>")
		$("#i1688").empty().append("<?php echo $listaprv; ?>")//cuenta prv
		$("#i1464").val(valor);
		$("#i1688").val(valorprv);
		$("#i1464,#i1688").select2({
        	 width : "550px"
        });
		$("#btn_cerrar_secundariolog").click()
		$("input[value='...']").remove()
		
	//$("#i1687").attr('onchange','return cuentaPrv()')
		
	<?php if($_REQUEST['a'] != 0){ ?>
		$('#i1533').val('XAXX010101000');
		//cuentaPrv();
	<?php } else{?> 
		// if($("#i1687").val()==0){
			// $("tr[title='Cuenta Proveedor']").hide();
		// }
	<?php }?>
	$('#send').hide();
	$('#send').after('<input id="send2" class=" nminputbutton " type="button" value="Guardar">')
	

$('#send2').click(function() {

		 var cadena = $("#i1533").val();
	     var alerta = "";
		 if (cadena.length == 12)
			var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		 else if(cadena.length == 13)
			var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		 else if(cadena.length <12){
		 	alerta += "El RFC no es valido.";
		 }
		var validRfc=new RegExp(valid);
		var matchArray=cadena.match(validRfc);
		if ($("#i1533").val() != "" && matchArray==null) {
			alerta += " El RFC no es valido.";
		}	
		 if(alerta != "")
		{ 
			alert (alerta);
			return false;
		}else{
		<?php if($_REQUEST['a'] != 0){?>
				$.post('../../modulos/mrp/guardavariable.php',{opc:19,rfc:$("#i1533").val(),id:0},
				function (resp){
					if($('#i1533').val()!="XAXX010101000" && resp==1){
						alert('El rfc ya se encuentra registrado');
					}else{
						$('#send').click();
					}

					});
		<?php }else{ ?>
			$.post('../../modulos/mrp/guardavariable.php',{opc:19,rfc:$("#i1533").val(),id:$('#i666').val()},
				function (resp){
					if($('#i1533').val()!="XAXX010101000" && resp==1){
						alert('El rfc ya se encuentra registrado');
					}else{
						
						$('#send').click();
					}

					});
		<?php } ?>
			//$('#send').click();
		}
	});
});
function cuentaPrv(){
<?php if($bancos){?>
		// if($("#i1687").val()==-1){
			// $("tr[title='Cuenta Proveedor']").show();
		// }else{
			// $("tr[title='Cuenta Proveedor']").hide();
			// $("#i1688").val(0);
		// }
// 	
<?php }?>
}
</script>
