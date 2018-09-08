

<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="../cont/js/jquery-ui.js"></script>
<script src="js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>

<script type="text/javascript" src="js/subecomprobante.js" ></script>
<style>
	td
{
	width:158px;
	height:30px;
	text-align: center;
	border:1px solid #BDBDBD;
}

a.movs
{
	text-decoration: none;
	font-weight: bold;
	color:black;
}
a.movs:hover
{
	text-decoration: underline;
	color:white;
}
#Facturas,#userdeducible{
	display:none
}
</style>

<?php 
require("views/captpolizas/subefacturauser.php");
require("views/captpolizas/nodeducible.php");

?>
<div class="nmwatitles">Subir Comprobantes.</div>

<div class="nmwatitles" align="center">Bienvenido <?php echo $nombre; ?></div>
<h4>Suba sus comprobantes acorde al anticipo</h4>

<table style="width:807px;">
	<thead>
		<tr style="background-color: rgb(189, 189, 189); color: white; font-weight: bold; display: table-row;">
			<th class="nmcatalogbusquedatit" align="center">Concepto</th>
			<th class="nmcatalogbusquedatit" align="center">Importe a Cubrir</th>
			<th class="nmcatalogbusquedatit" align="center"></th>
			<th class="nmcatalogbusquedatit"></th>
			<th class="nmcatalogbusquedatit" align="center">Monto Restante</th>
		</tr>
	</thead>
	<tbody>
		<?php echo $relleno; ?>
	</tbody>
</table>
<script>
$(document).ready(function(){
	
    $('input[name="tanticipo[]"]').each(function() { 
    		var id=$(this).val();
  		var totalfinal=parseFloat($("#"+id).html().replace(/,/gi,''));
	    $.post('ajax.php?c=CaptPolizas&f=muestraDeducible',{
			idanticipo:id
		},function(resp){
			$("#tabla tfoot").html(resp);
			calculaNoDeducible();
			totalfinal -= parseFloat($("#totalnodedu").html()) ; 
	    		$.post("ajax.php?c=CaptPolizas&f=facturas_dialog",
	 		{
				IdPoliza: id
		 	},
			function(data)
			 {
			 	$('#listaFacturas tbody').html(data);
			 	calcula();
			 	totalfinal-=parseFloat($("#totalfinal").html());
			 	$("#"+id).html(totalfinal.toFixed(2));
			 });
		});
		
    });  
});
</script>