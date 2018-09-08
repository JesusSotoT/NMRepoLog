<style>
@media print
{
	#imprime
	{
		display:none;
	}
}

</style>
<?php
$prod = explode("*|*",$info['Producto']);
?>
<div style='margin-left:10px;'>
	<center>
	<?php
		if(!$info['tipo_traspaso'])
			$tipo = "Salida";
		if($info['tipo_traspaso'] == 1)
			$tipo = "Entrada";
		if($info['tipo_traspaso'] == 2)
			$tipo = "Traspaso";
	?>
		<h3>Impresion de <?php echo $tipo; ?>.</h3>
		<table width='400' border=1>
		<tr><td width='150'><b>Id:</b></td><td><?php echo $_REQUEST['idMov'] ?></td></tr>
		<tr><td><b>Producto:</b></td><td><?php echo $prod[0] ?></td></tr>
		<tr><td><b>Cantidad:</b></td><td><?php echo $info['cantidad'] ?></td></tr>
		<tr><td><b>Importe:</b></td><td><?php echo $info['importe'] ?></td></tr>
		<tr><td><b>Almacen Origen:</b></td><td><?php echo $info['Almacen_Origen'] ?></td></tr>
		<tr><td><b>Almacen Destino:</b></td><td><?php echo $info['Almacen_Destino'] ?></td></tr>
		<tr><td><b>Costo Unitario:</b></td><td><?php echo $info['costo'] ?></td></tr>
		<tr><td><b>Referencia:</b></td><td><?php echo $info['referencia'] ?></td></tr>
		<tr><td><b>Fecha:</b></td><td><?php echo $info['fecha'] ?></td></tr>
		
		<?php

		if($info['id_producto_caracteristica'] != "" && $info['id_producto_caracteristica'] != "'0'")
		{
			$caracs = $this->InventariosModel->info_carac($info['id_producto_caracteristica']);
			$txt_caracs = "";
			while($ca = $caracs->fetch_object())
			{
				$txt_caracs .= $ca->NombrePadre.": ".$ca->NombreHija.", ";
			}
			echo "<tr><td colspan='2' style='border-top:3px solid black;'><b>Caracteristicas:</b></td></tr>";
			echo "<tr><td colspan='2'>".$txt_caracs."</td></tr>";
		}
	
		if(intval($info['id_lote']))
		{
			$lote = $this->InventariosModel->info_lote($info['id_lote']);
			echo "<tr><td colspan='2' style='border-top:3px solid black;'><b>Informacion Lote</b></td></tr>";
			echo "<tr><td><b># Lote:</b></td><td>".$lote['no_lote']."</td></tr>";
			echo "<tr><td><b>Fecha Fabricacion:</b></td><td>".$lote['fecha_fabricacion']."</td></tr>";
			echo "<tr><td><b>Fecha Caducidad:</b></td><td>".$lote['fecha_caducidad']."</td></tr>";

		}
		if(intval($info['id_pedimento']))
		{
			$pedi = $this->InventariosModel->info_pedimento($info['id_pedimento']);	
			echo "<tr><td colspan='2' style='border-top:3px solid black;'><b>Informacion Pedimento</b></td></tr>";
			echo "<tr><td><b># Pedimento:</b></td><td>".$pedi['no_pedimento']."</td></tr>";
			echo "<tr><td><b>Aduana:</b></td><td>".$pedi['aduana']."</td></tr>";
			echo "<tr><td><b># Aduana:</b></td><td>".$pedi['no_aduana']."</td></tr>";
			echo "<tr><td><b>Tipo Cambio:</b></td><td>".$pedi['tipo_cambio']."</td></tr>";
			echo "<tr><td><b>Fecha Pedimento:</b></td><td>".$pedi['fecha_pedimento']."</td></tr>";
		}

		if(intval($prod[1]))
		{
			$series = $this->InventariosModel->info_srs($_REQUEST['idMov']);
			$txt_series = '';
			while($ss = $series->fetch_object())
			{
				$txt_series .= $ss->serie.", ";
			}
			echo "<tr><td colspan='2' style='border-top:3px solid black;'><b>Series:</b></td></tr>";
			echo "<tr><td colspan='2'>".$txt_series."</td></tr>";

		}
		?>
		</table>
		<div id='imprime'>
			<a href='javascript:window.print()'>Imprimir</a>
		</div>
	</center>
</div>
<script language='javascript'>
window.print();
</script>