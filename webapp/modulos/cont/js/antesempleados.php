<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

<script>
	$(document).ready(function(){
		$("#i1640").html('<option value="1">Efectivo</option><option value="2">Cheque</option><option value="4">Tarjeta de debito</option><option value="7">Transferencia</option><option value="8">Spei</option>');
		$("#i1640,#i1655,#i1652,#i1647,#i1646,#i1644,#i1643,#i1640,#i2004").select2({
			width : "150px"
		});
		clasificacion()
	});

	$("input[onclick='btn_i1655_click();']").hide();
	$("input[onclick='btn_i2009_click();']").hide();
	var valor = $("#i2004").val();
	if(valor == '') valor = '0';
	$("#i2004").remove()
	$("#lbl2004").after("<br /><select name='i2004' id='i2004'><option value='0'>Ninguna</option><option value='1'>Venta</option><option value='2'>Cobranza</option></select>");
	$("#i2004").val(valor).trigger("change")

	function clasificacion() {
		if($("#i2006").val() == '')
			$("#i2006").val('0');

		$.post("../../modulos/appministra/ajax.php?c=configuracion&f=listaClasificacionesEmp"; //select yena tipo operacion
		function(data) {
			$("#i2006").after("<select id='clasif' class='form-control' onchange='nuevoclasif()'></select>").attr('type','hidden');
			$("#clasif").html(data);
			$("#clasif").val(parseInt($("#i2006").val()));
		});
	}

	function nuevoclasif() {
		$("#i2006").val($("#clasif").val());
	}
</script>
