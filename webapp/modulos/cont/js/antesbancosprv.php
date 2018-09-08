<script src="js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<?php
include("../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
// $sql=$conection->query('select id,nombre from comun_cliente where beneficiario_pagador=-1');
// $lista="<option value='0'>NINGUNA</option>";
// while($c = $sql->fetch_object())
// {
// $lista .= "<option value='$c->id'>$c->nombre</option>";
// }
$bancos = false;
$bancosSql=$conection->query('select * from accelog_perfiles_me where idmenu=1932');
if($status = $bancosSql->num_rows>0){
$bancos=true;
}
$conection->close();
?>
<script>
	$(document).ready(function(){
		$("#i1484").select2({
         width : "150px"
        });
        $("#i1483").select2({
         width : "150px"
        });
     
    // var clienteantes = $("#i1780").val(); 
    // $("#i1780").val(clienteantes);
    // $("#i1779").attr('onchange','return validaBene()') ;   
     <?php
	if(!$bancos){?>
		// $("tr[title='Beneficiario/Pagador (Cliente)").hide();
		// $("tr[title='Beneficiario/Pagador']").hide();
		// $("#i1780").val(0);$("#i1779").val(0);
<?php } ?>  
	//validaBene(); 
});
function validaBene(){
	<?php if($bancos){?>
		// if($("#i1779").val()==-1){
			// $('#i1484 option:selected').remove();
			// $("#i1484").append('<option value=0 selected></option>');
			// $("tr[title='Beneficiario/Pagador (Cliente)']").show();
			// $("tr[title='Proveedor']").hide();
// 			
// 			
		// }else{
			// $("#i1780").val(0);
			// $("tr[title='Proveedor']").show();
			// $("tr[title='Beneficiario/Pagador (Cliente)']").hide();
// 			
		// }
	
<?php }?>
}	
	$("input[onclick='btn_i1483_click();']").hide();
	$("input[onclick='btn_i1484_click();']").hide();
	$("input[onclick='btn_i1780_click();']").hide();
	
	$("#i1483").on('change', function() {
		if(this.value==93){
			$('#i1485').val('NA');
		}else{
			$('#i1485').val('')
		}
	});
	
</script>