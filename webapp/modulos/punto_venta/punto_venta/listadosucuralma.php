<!DOCTYPE html>
<html>
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />

	<body>
		<?php 
include("../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
		?>
			<div class="tipo">
	<table><tbody><tr>
	<td><input type="button" value="<" onclick="paginacionGridCxc(<?php //echo $pag_anterior;?>,1);"></td>
	<td><input type="button" value=">" onclick="paginacionGridCxc(<?php //echo $pag_siguiente;?>,1);" ></td>
	<td><a href="javascript:window.print();">
	<img src="../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
	<td><b style="font-size: 13px;">Listado de Sucursales.</b></td></tr></tbody></table></div><br>
	
<div style="width: 95%;  text-align: right;"><!--<input type="button" value="Agregar" onclick="window.location='sucualma.php?v=new'" />-->
	
</div>
<br></br>
		<table class="busqueda" id="datos" cellpadding="3" cellspacing="1" width="95%" height="95%"  >
	<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
		    
			<th>ID</th>
			<th width="50"></th>
			<th >Sucursal</th>
			<th width="50"></th>
			<th >Direcci&oacute;n</th>
			<th width="50"></th>
			<th >Municipio</th>
			<th width="50"></th>
			<th >Estado</th>
			
	</tr>   
		
	<?php
	
$agrega=$conection->query("Select ms.idSuc, ms.nombre,ms.direccion,m.municipio,e.estado from mrp_sucursal ms, almacen a,estados e,municipios m where ms.idMunicipio=m.idmunicipio and ms.idEstado=e.idestado GROUP BY ms.nombre");
while($lista=$agrega->fetch_array(MYSQLI_BOTH)){ ?>
			<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;>
		<td width="50"></td>
		<td><a  style="text-decoration:none;color: #6E6E6E;" href="sucualma.php?v=<?php echo $lista['0']; ?>"><?php echo $lista['0']; ?></a></td>
		<td width="50"></td>
		<td><a  style="text-decoration:none;color: #6E6E6E;" href="sucualma.php?v=<?php echo $lista['0']; ?>"><?php echo $lista['1']; ?></a></td>
		<td width="50"></td>
		<td><a  style="text-decoration:none;color: #6E6E6E;" href="sucualma.php?v=<?php echo $lista['0']; ?>"><?php echo $lista['2']; ?></a></td>
		<td width="50"></td>
		<td><a  style="text-decoration:none;color: #6E6E6E;" href="sucualma.php?v=<?php echo $lista['0']; ?>"><?php echo $lista['3']; ?></a></td>
        <td width="50"></td>
		<td><a  style="text-decoration:none;color: #6E6E6E;" href="sucualma.php?v=<?php echo $lista['0']; ?>"><?php echo $lista['4']; ?></a></td>
					
				
			</tr>
			<?php }
$conection->close();
 ?>
		</table>

<br></br>


	</body>
</html>