<?php
 $url=str_replace('verrepdetallado','verrepdetalladoexcel',$_SERVER["REQUEST_URI"]);
?>

<!DOCTYPE html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
		<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<style type="text/css">
      #div_img img{
        display: none;
      }
    </style>
	</head>
	
<body>
	<a href="javascript:window.print();">
	<img border="0" src="../../netwarelog/repolog/img/impresora.png">
	</a>
	<img src="images/images.jpg" title="Exportar a Excel" onclick="window.open('<?php echo $url; ?>')" width="35px" height="30px"> 


	<div style="width: 80%; height: 100%;background: #D8D8D8">
	<b style="font-size:16px; color:#6E6E6E; text-align: center;">&nbsp;Diarios y Polizas.</b><br>
		</br>
		<h2 align="center" style="font-size:16px; color:#6E6E6E; text-align: center;" id="div_img"><?php echo $empresa;?></h2>
		<h3 align="center" style="font-size:16px; color:#6E6E6E; text-align: center;">Impreso de Poliza</h3>
		<h4 id="periodo" align="center" style="font-size:16px; color:#6E6E6E; text-align: center;">Modena: PESO MEXICANO</h4>
<p align="right" style="font-size:16px; color:#6E6E6E; text-align: right;margin-right: 55px;">Fecha : <?php echo $fecha;?></p>
<p align="left" style="font-size:16px; color:#6E6E6E; text-align: left;margin-right: 55px;"><?php echo $direccion; ?></p>

		<table align='center' class="busqueda" id="detallado" width='100%'cellpadding="1" cellspacing="2" >
  	<thead>
  		<tr class="tit_tabla_buscar">
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
  		
  		<tr class="tit_tabla_buscar">
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
  	<tbody>
  		<?php echo $body; ?>
  	</tbody>
  </table><br></br>
  </div>
</body>

</html>
