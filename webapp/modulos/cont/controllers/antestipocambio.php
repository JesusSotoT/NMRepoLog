<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<script>
$(document).ready(function(){
	$("#i1412").select2({ width : "150px" });
	$("#i1414").val("Contable");
	$("#i1414").attr("readonly",true);
	$("#send").hide();
	$("#send").after('<input id="send2" class=" nminputbutton " type="button" value="Guardar" >');
});

$(document).ready(function() {
		 $('#send2').click(function() {

	var ano = $("#i1413_3").val();
	var mes = $("#i1413_1").val();
	var dia = $("#i1413_2").val();
	var fecha = ano+"-"+mes+"-"+dia;
	var moneda = $("#i1412").val();

<?php
if($_REQUEST['a'] == 0){?>
	$.post("../../modulos/cont/models/ajusteantes.php",{
		fecha:fecha,
		moneda:moneda,
		idtipo:$('#i1411').val(),
		opc:2
	},function(resp){
		if(resp!=1){
			$.post("../../modulos/cont/models/ajusteantes.php",{
				fecha:fecha,
				moneda:moneda,

				opc:1
				},function(resp){
					if(resp==1){
						$("#i1414").attr("readonly",true);
						alert("Ya se encuentra registrado ese tipo de cambio en la fecha "+fecha);
						return false;
					}else{
						$("#send").click();
					}
				});
		}else{

			$("#send").click();
		}
	});
			//$("#send").click();
<?php }else{?>

				$.post("../../modulos/cont/models/ajusteantes.php",{
				fecha:fecha,
				moneda:moneda,

				opc:1
				},function(resp){
					if(resp==1){
						$("#i1414").attr("readonly",true);
						alert("Ya se encuentra registrado ese tipo de cambio en la fecha "+fecha);
						return false;
					}else{
						$("#send").click();
					}
				});

	<?php }?>


});
});
<?php
include("../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$sql2=$conection->query('select * from cont_coin');
if($_REQUEST['a'] != 0){ ?>
	$('#i1412').empty();

<?php while($row=$sql2->fetch_array()){?>
	$('#i1412').append("<?php echo "<option value='".$row['coin_id']."'>".utf8_encode($row['description'])."(".$row['codigo'].")</option>";?>");
<?php }
}
$conection->close();
?>
</script>
