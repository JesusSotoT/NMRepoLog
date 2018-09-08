<?php 
include_once("../../netwarelog/catalog/conexionbd.php");
?>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/select2/select2.min.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<?php

$query_departamento = mysql_query("SELECT idDep, nombre from mrp_departamento");
$select_departamento = "<div><label>Departamento:</label><br /><select id='dep' onchange='RecargaFamilia(this.value);' onLoad='RecargaFamilia(this.value);'>";
$select_departamento .= "<option value=''>Selecciona departamento</option>";
while ($row = mysql_fetch_array($query_departamento, MYSQL_BOTH)) 
	{
		$id=$row["idDep"];
		$nombre=$row["nombre"];
		
		$select_departamento .= "<option value='".$id."'>".$nombre."</option>";
	}
$select_departamento .= "</select></div>";
$select_departamento .= "<br><img id='preloader' src='../../modulos/mrp/images/preloader.gif'>";

?>

<script>
$(function(){
	var selector = document.getElementById('i363');
	selector.style.visibility = 'hidden';
	$(".campo #lbl363").prepend("<?php echo $select_departamento; ?>");
	$("#preloader").hide();
});
		
function RecargaFamilia(idDep)
{
	$("#preloader").show();
	 $.ajax({
	 	
					url:'../../modulos/mrp/filtrado_familia.php',
					type: 'POST',
					data: {funcion:'buscaDepartamento',id:idDep},
					success: function(callback)
					{	
					     $("#i363").html(callback);			 
						var selector = document.getElementById('i363');
						selector.style.visibility = 'visible';
						$("#preloader").hide();
					}
				});
}	
</script>
<?php
include("../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$sql=$conection->query('SELECT account_id, manual_code, description FROM cont_accounts where main_account = 3 AND removed=0');
$lista="<option value='0'>NINGUNA</option>";
$num = $sql->num_rows;
echo "<input type='hidden' id='numerocuentas' value='$num'>";
while($c = $sql->fetch_object())
{
$lista .= "<option value='$c->account_id'>$c->description ($c->manual_code)</option>";
}
$conection->close();
?>
<script language='javascript'>
$(document).ready(function(){
	$("#i1588").after("<select id='selCuentas' class='nminputselect'><option value='0'>Ninguno</option></select>");
	if(parseInt($("#numerocuentas").val()) <= 0)
	{
		$("#lbl1588").remove()
		$("#selCuentas").remove()
		
	}
		
		$("#i1588").attr('type','hidden')
		$("#selCuentas").empty().append("<?php echo $lista; ?>")
		$("#selCuentas").val($("#i1588").val())
		$("#selCuentas").select2({
        	 width : "100%"
        });
        $("#selCuentas").change(function(event) {
        	$("#i1588").val($(this).val())
        });
	});
	
		</script>