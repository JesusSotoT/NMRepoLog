<script language='javascript'>

 	function eliminar(archivo)
 	{
 		var confirmacion = confirm("Esta seguro de eliminar este archivo: \n"+archivo);
 		if(confirmacion)
 		{
 			$.post("ajax.php?c=Reports&f=EliminarArchivo",
		 	{
				Archivo: archivo
			},
			function()
			{
			 	//location.reload();
				//alert('Eliminado')
				$.post("ajax.php?c=Reports&f=borraFacturaForm",
		 		{
					IdPoliza: $('#plz').val(),
					Archivo: archivo
				},
				function()
				{
					var recalantes = parseFloat( $("#"+$('#plz').val()).html() ) + parseFloat($("#totalfinal").html());
					$("#"+$('#plz').val()).html(recalantes.toFixed(2));

					$.post("ajax.php?c=CaptPolizas&f=facturas_dialog",
			 		{
						IdPoliza: $('#plz').val()
					},
					function(data)
					{
					
					 	$('#listaFacturas').html(data)
					 	//actualizaListaFac();
					 	calcula();
						var recaldespues = parseFloat( $("#"+$('#plz').val()).html() ) - parseFloat($("#totalfinal").html());
					 	$("#"+$('#plz').val()).html(recaldespues.toFixed(2));
					
					});	 				
				});
			});
 		}
 	}
</script>
<style>
#lista td
{
	width:146px;
	text-align: center;
	border:1px solid #BDBDBD;
}

#buscar
{
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-o-border-radius: 4px;
border-radius: 4px;
}
#loading
{
	background-color:#BDBDBD;
	color:white;
	text-align:center;
	font-weight:bold;
}
</style>
<div id='Facturas' title='Lista de Facturas.'>
	<div>
		<form name='fac' id='fac' action='' method='post' enctype='multipart/form-data'>
			<input type='file' name='factura[]' id='factura' multiple><input type='hidden' name='plz' id='plz' value=''>
			<input type='submit' id='buttonFactura' value='Subir Facturas' class="nminputbutton_color2">  
			<span id='verif' style='color:green;display:none;'>Verificando...</span>
		</form>
	</div>
	<table id='listaFacturas'>
		<tbody></tbody>
	</table>
	<br>
	<div align="center" class="nmwatitles">
	Monto Total:&nbsp; $<label style="color: red;" id="totalfinal" ></label>
	</div>
</div>