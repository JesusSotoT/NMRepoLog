<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=diarios_polizas.xls");
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 	
	</head>
	
<body>
	<table align="center " style="text-align: center" width="100%">
		<tr><td>
			<b style="font-size:16px; color:#6E6E6E; text-align: center;">&nbsp;Diarios y Polizas.</b><br>
			</br>
		</td></tr>
		<tr><td>
		<h2 align="center" style="font-size:16px; color:#6E6E6E; text-align: center;"><?php echo $empresa;?></h2>
		</td></tr>
		<tr><td>
		<h3 align="center" style="font-size:16px; color:#6E6E6E; text-align: center;">Impreso de Poliza</h3>
		</td></tr>
		<tr><td>
		<h4 id="periodo" align="center" style="font-size:16px; color:#6E6E6E; text-align: center;">Modena: PESO MEXICANO</h4>
        </td></tr>
        <tr><td>
        <p align="right" style="font-size:16px; color:#6E6E6E; text-align: right;margin-right: 55px;">Fecha : <?php echo $fecha;?></p>
		<p align="left" style="font-size:16px; color:#6E6E6E; text-align: left;margin-right: 55px;"><?php echo $direccion; ?></p>
		</td></tr>
</table>
		<table align='center' class="busqueda" id="detallado" width='100%'cellpadding="1" cellspacing="2" >
  	<thead>
  		<tr>
  			<th>Fecha </th>
  			<th></th>
  			<th>Tipo</th>
  			<th></th>
  			<th>Folio</th>
  			<th></th>
			<th>C o n c e p t o</th>
			<th>Clase</th>
			<th>Diario</th>
  		</tr>
  		<tr >
  			<th>No.</th>
  			<th>Refer.</th>
  			<th></th>
  			<th>C u e n t a</th>
  			<th></th>
  			<th>Nombre</th>
  			<th></th>
  			<th>C a r g o s</th>
  			<th>A b o n o s</th>
  		</tr>
  	</thead>
  	<tbody >
  		<?php echo $body; ?>
  	</tbody>
  
  </table><br></br>
 
</body>

</html>
