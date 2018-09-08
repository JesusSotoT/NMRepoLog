<style>
@media print
{
	#imprime
	{
		display:none;
	}
}

img.desaturada 
	{ 
		filter: grayscale(100%);
		-webkit-filter: grayscale(100%);
		-moz-filter: grayscale(100%);
		-ms-filter: grayscale(100%);
		-o-filter: grayscale(100%);
	}

</style>
<body onload='window.print();'>
<table style='font-size:12px;width:200px;text-align:center;'>
	<tr><td>
		<?php 
			$imagen='../../netwarelog/archivos/1/organizaciones/'.$organizacion['logoempresa'];
			$imagesize=getimagesize($imagen);
			$porcentaje=0;
			if($imagesize[0]>200 && $imagesize[1]>90){
				if($imagesize[0]>$imagesize[1]){
					$porcentaje=intval(($imagesize[1]*100)/$imagesize[0]);
					$imagesize[0]=200;
					$imagesize[1]=(($porcentaje*200)/100);
				}else{
					$porcentaje=intval(($imagesize[0]*100)/$imagesize[1]);
					$imagesize[0]=200;
					$imagesize[1]=(($porcentaje*200)/100);	
				}
			}
			//"../../netwarelog/archivos/1/organizaciones/'.$cliente[0]->logoempresa.'"
			$src="";
			if($imagen!="" && file_exists($imagen))
				$src="<img src='".$imagen."' style='width:".$imagesize[0]."px;height:".$imagesize[1]."px;display:block;margin:0 auto 0 auto;' class='desaturada'/>";
			echo $src;
		?>
	</td></tr>
	<tr><td>
		<?php echo $organizacion['nombreorganizacion'] ?>
		<br />
		<?php echo $organizacion['domicilio'] . ", " . $organizacion['colonia'] . ", " . $organizacion['cp'] . ", " . $organizacion['municipio'] . ", " . $organizacion['estado'] ?>
		<br />
		<?php echo $organizacion['RFC'] ?>
	</td></tr>
	<tr><td>
		<b>Ticket comprobante de: <?php echo $tipo_comp ?></b>
	</td></tr>
	<tr><td>
		<?php echo $info_pago['fecha_pago'] ?><br />
		<?php echo $tit_cli_prov." ".$info_pago['cli_prov'] ?><br />
		<?php echo "(".$info_pago['id'].") ".$info_pago['concepto'] ?><br />
	</td></tr>
	<tr><td>
		<b>Cajero:</b><br /><?php echo $cajero ?>
	<tr><td>
		<table style='font-size:12px;'>
			<tr><th width='90' style='text-align:left;'>Concepto</th><th width='90' style='text-align:right;'>Cantidad</th></tr>
			<?php
			echo $pagos;
			?>
			<tr><td width='90' style='text-align:left;border-top:1px solid black;'><b>Total:</b></td><td width='90' style='text-align:right;border-top:1px solid black;'><b>$ <?php echo number_format($total,2); ?></b></td></tr>
		</table>
	</td></tr>
</table>
<div id='imprime'>
	<a href='javascript:window.print()'>Imprimir</a>
</div>
</body>