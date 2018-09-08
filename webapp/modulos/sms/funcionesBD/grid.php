<?php
//include("../../../netwarelog/catalog/conexionbd.php");
switch ($_POST["funcion"])
{
	case 'gridofertas': echo  gridOfertas($_POST["pagina"],$_POST["filtro"],true);break;
	
}


/////////////////////////////////////////////////////////////////////////////////////////// 
function gridOfertas($pagina=1,$filtro=1,$post=false,$paginacion=15,$elimina=false)
{
	if($post){include("../../../netwarelog/webconfig.php");}else{
	include("../../../../netwarelog/webconfig.php");}
	
	$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
	date_default_timezone_set('America/Mexico_City'); 
	$hoy_beta = date("Y-m-d");
	$hoy = strtotime($hoy_beta);

	//Actualiza los estatus
	$consultaEstatus = $conection->query("SELECT idOferta, fechafin, estatus FROM sms_oferta"); 		
	
	while($row = $consultaEstatus->fetch_array(MYSQLI_ASSOC))
	{
		$fin = strtotime($row["fechafin"]);
		if($hoy > $fin && $row['estatus'] == "Vigente") 
		{
			$actualizaEstatus = $conection->query("UPDATE sms_oferta SET estatus = 'Vencida' WHERE idOferta =".$row['idOferta']);
		}
	}
	
	if($pagina==1){$begin=0;}else{$begin=($paginacion*$pagina)-$paginacion;}

	
	$consulta0="	SELECT o.idOferta,p.codigo,p.nombre producto,u.compuesto unidad, o.precio,o.cantidad,o.fechainicio,o.fechafin,o.fechaCreacion, o.estatus, o.cantidadExistente
					FROM sms_oferta o, mrp_producto p, mrp_unidades u WHERE o.idProducto=p.idProducto and u.idUni=o.idUnidad and ".$filtro." order by o.idOferta desc "; 		
	$q0=$conection->query($consulta0);
	$paginas=($q0->num_rows/$paginacion);if($q0->num_rows%$paginacion!=0){$paginas++;}
	//$paginas=ceil($paginas);
	
	$consulta="		SELECT o.idOferta,p.codigo,p.nombre producto,u.compuesto unidad, o.precio,o.cantidad,o.fechainicio,o.fechafin,o.fechaCreacion, o.estatus, o.cantidadExistente 
					FROM sms_oferta o, mrp_producto p, mrp_unidades u WHERE o.idProducto=p.idProducto and u.idUni=o.idUnidad and ".$filtro." order by o.idOferta desc  LIMIT ".$begin.",".$paginacion; 		
	$q=$conection->query($consulta);
	$i=0;
	
	
	while($row=$q->fetch_array(MYSQLI_ASSOC))
	{
		list($anoInicio, $mesInicio, $diaInicio) = explode("-", $row["fechainicio"]);
		list($anoFin, $mesFin, $diaFin) = explode("-", $row["fechafin"]);
		
		list($fechaCreacion, $horaCreacion) = explode(" ", $row["fechaCreacion"]);	
		list($anoCreacion, $mesCreacion, $diaCreacion) = explode("-", $fechaCreacion);	
		
		$link="consult.php?id=".$row['idOferta']."&fexp=".$row['fechafin'];
		
		if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
				
		$filas.='<td><a class="a_registro" href="'.$link.'">'.($row["idOferta"]).'</a></td>';
		$filas.='<td><a class="a_registro" href="'.$link.'">'.utf8_decode($row["codigo"]).'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.($row["cantidad"]).'</a></td>';
		$filas.='<td><a class="a_registro" href="'.$link.'">'.utf8_decode($row["unidad"]).'</a></td>';
		$filas.='<td><a class="a_registro" href="'.$link.'">'.utf8_decode($row["producto"]).'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">$'.number_format($row["precio"],2,".",",").'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.($row["cantidadExistente"]).'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$diaInicio.'-'.$mesInicio.'-'.$anoInicio.'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$diaFin.'-'.$mesFin.'-'.$anoFin.'</a></td>';
		$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.$diaCreacion.'-'.$mesCreacion.'-'.$anoCreacion.' '.$horaCreacion.'</a></td>';	
		if($row['estatus'] == "Vencida")
			$filas.='<td align="center"><a class="a_registro" href="'.$link.'"><div style="color: #FF000C;">'.utf8_decode($row["estatus"]).'</div></a></td>';
		if($row['estatus'] == "Vigente")
			$filas.='<td align="center"><a class="a_registro" href="'.$link.'"><div style="color: #01a05f;">'.utf8_decode($row["estatus"]).'</div></a></td>';
		if($row['estatus'] == "Cerrada")
			$filas.='<td align="center"><a class="a_registro" href="'.$link.'"><div style="color: #000CFF;">'.utf8_decode($row["estatus"]).'</div></a></td>';
		$filas.='</tr>';
	$i++;
	}

	
	$encabezado='
	<td align="center">ID</td>
	<td align="center">Código</td>
	<td align="center">Cantidad</td>
	<td align="center">Unidad</td>
	<td align="center">Producto</td>
	<td align="center">Precio</td>
	<td align="center">Existente</td>
	<td align="center">Fecha inicio</td>
	<td align="center">Fecha fin</td>
	<td align="center">Fecha de creación</td>
	<td align="center">Estatus</td>';
	
	
	if($i<10)
	{
		for($j=$i;$j<10;$j++)
		{	
		if($j%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
		$filas.="<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
		$filas.="</tr>";
		}
	}
	
	if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
	if(($pagina+1)>$paginas){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}			
	
	
	$catalogo='
	<p><div class="tipo">
	<table><tbody><tr>
	<td><input type="button" value="<" onclick="paginacionGridOferta('.$pag_anterior.',1);"></td>
	<td><input type="button" value=">" onclick="paginacionGridOferta('.$pag_siguiente.',1);" ></td>
	<td><a href="javascript:window.print();">
	<img src="../../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
	<td><b>Ofertas</b></td></tr></tbody></table></div><br>';
						
	$catalogo.='<center><div style="width: 95%; text-align: right;"><input type="button" value="Crear nueva oferta" onclick="cargaInterfaceAgregar()"></div>
	<p>
	<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="95%">
	<tr class="tit_tabla_buscar">'.$encabezado.'</tr>			
	<tr class="titulo_filtros" title="Segmento de búsqueda"></tr>
	'.$filas.'</table></center>';		
	
	mysqli_close($conection);
	return  $catalogo;
}
/////////////////////////////////////////////////////////////////////////////////////////// 
?>	