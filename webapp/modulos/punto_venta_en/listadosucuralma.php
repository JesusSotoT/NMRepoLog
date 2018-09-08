<!DOCTYPE html>
<html>
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="../../netwarelog/design/default/netwarlog.css" />  	

	<body>
		<?php 
include("../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
		?>
			<div class="tipo">
				<div class="nmwatitles">Listado de Sucursales</div>
	<table><tbody><tr>
	<td><img class="nmwaicons" src="../../netwarelog/design/default/pag_ant.png"  onclick="paginacionGridCxc(<?php //echo $pag_anterior;?>,1);"></td>
	<td><img class="nmwaicons" src="../../netwarelog/design/default/pag_sig.png"  onclick="paginacionGridCxc(<?php //echo $pag_siguiente;?>,1);" ></td>
	<td><a href="javascript:window.print();">
	<img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0"></a></td>
	<td></td></tr></tbody></table></div><br>
	
<div style="width: 95%;  text-align: right;"><!--<input type="button" value="Agregar" onclick="window.location='sucualma.php?v=new'" />-->
	
</div>
<br></br>
		<table class="busqueda" id="datos" cellpadding="3" cellspacing="1" width="95%" height="95%"  >

	<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
		    
			<th class="nmcatalogbusquedatit">ID</th>
			<th class="nmcatalogbusquedatit" width="50"></th>
			<th class="nmcatalogbusquedatit" >Sucursal</th>
			<th class="nmcatalogbusquedatit" width="50"></th>
			<th class="nmcatalogbusquedatit" >Direcci&oacute;n</th>
			<th class="nmcatalogbusquedatit" width="50"></th>
			<th class="nmcatalogbusquedatit" >Municipio</th>
			<th class="nmcatalogbusquedatit" width="50"></th>
			<th class="nmcatalogbusquedatit" >Estado</th>
			
	</tr>   
		
	<?php
	
$agrega=$conection->query("Select ms.idSuc, ms.nombre,ms.direccion,m.municipio,e.estado from mrp_sucursal ms, almacen a,estados e,municipios m where ms.idMunicipio=m.idmunicipio and ms.idEstado=e.idestado GROUP BY ms.nombre");
while($lista=$agrega->fetch_array(MYSQLI_BOTH)){ 
		
		if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
		{
    		$color='nmcatalogbusquedacont_1';
		}
		else//Si es impar pinta esto
		{
    		$color='nmcatalogbusquedacont_2';
		}
		
		echo "<tr class='".$color."' title='Segmento de búsqueda' style='font-size: 10pt;'>";
		$cont++
	?>
		
		<td><a  style="text-decoration:none;color: #6E6E6E;" href="sucualma.php?v=<?php echo $lista['0']; ?>"><?php echo $lista['0']; ?></a></td>
		<td width="50"></td>
		<td><a  style="text-decoration:none;color: #6E6E6E;" href="sucualma.php?v=<?php echo $lista['0']; ?>"><?php echo utf8_encode($lista['1']); ?></a></td>
		<td width="50"></td>
		<td><a  style="text-decoration:none;color: #6E6E6E;" href="sucualma.php?v=<?php echo $lista['0']; ?>"><?php echo utf8_encode($lista['2']); ?></a></td>
		<td width="50"></td>
		<td><a  style="text-decoration:none;color: #6E6E6E;" href="sucualma.php?v=<?php echo $lista['0']; ?>"><?php echo utf8_encode($lista['3']); ?></a></td>
        <td width="50"></td>
		<td><a  style="text-decoration:none;color: #6E6E6E;" href="sucualma.php?v=<?php echo $lista['0']; ?>"><?php echo utf8_encode($lista['4']); ?></a></td>
					
				
			</tr>
			<?php }
$conection->close();
 ?>
		</table>

<br></br>


	</body>
</html>