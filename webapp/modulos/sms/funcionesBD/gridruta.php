<?php
//include("../../../netwarelog/catalog/conexionbd.php");
if(count($_POST)>0)
{
switch ($_POST["funcion"])
{
	case 'gridofertasrutas': echo  gridOfertasrutas($_POST["pagina"],true,$_POST["filtro"]);break;
	case 'gridrutas': echo  gridrutas($_POST["pagina"],$_POST["oferta"]);break;
	
}
}

/////////////////////////////////////////////////////////////////////////////////////////// 
function gridrutas($pagina=1,$oferta,$filtro=1,$paginacion=15,$elimina=false)
{
include("../../../netwarelog/webconfig.php");

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

if($pagina==1){$begin=0;}else{$begin=($paginacion*$pagina)-$paginacion;}

$consulta0="select ro.id,
ro.nombre nombreruta,
CONCAT(b.tipo,' / ',transporte) transporte,
a.placas, c.capacidad,
ro.status estatusruta 
from 
sms_oferta_cliente of,
sms_ruta_oferta_cliente roc,
sms_ruta_oferta ro,
sms_transporte a,
sms_tipo_unidad b,
sms_capacidades c
where 
roc.idOfertacliente=of.id and  
roc.idRutaOferta=ro.id and
b.idtipo=a.idtipo and
c.idcapacidad=a.idcapacidad and
of.idOferta='$oferta'
group by ro.id";
$q0=$conection->query($consulta0);
//var_dump($q0);

if(!$q0){
//$paginas=ceil($paginas);
$paginas=0;
}else{
	$paginas=($q0->num_rows/$paginacion);if($q0->num_rows%$paginacion!=0){$paginas++;}
	
}
$consulta="select ro.id,
ro.nombre nombreruta,
CONCAT(b.tipo,' / ',transporte) transporte,
a.placas, c.capacidad,
ro.status estatusruta 
from 
sms_oferta_cliente of,
sms_ruta_oferta_cliente roc,
sms_ruta_oferta ro,
sms_transporte a,
sms_tipo_unidad b,
sms_capacidades c
where 
roc.idOfertacliente=of.id and  
roc.idRutaOferta=ro.id and
b.idtipo=a.idtipo and
c.idcapacidad=a.idcapacidad and
of.idOferta='$oferta' 
group by ro.id LIMIT ".$begin.",".$paginacion; 		
$q=$conection->query($consulta);
$i=0;

$link="offer/form.php";
if($paginas!=0){
while($row=$q->fetch_array(MYSQLI_ASSOC))
{
	if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
			
	$filas.='<td><a class="a_registro" href="../../funcionesBD/editaruta.php?idr='.$row["id"].'">'.($row["id"]).'</a></td>';
	$filas.='<td><a class="a_registro" href="../../funcionesBD/editaruta.php?idr='.$row["id"].'">'.utf8_decode($row["nombreruta"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="../../funcionesBD/editaruta.php?idr='.$row["id"].'">'.($row["transporte"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="../../funcionesBD/editaruta.php?idr='.$row["id"].'">'.($row["capacidad"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="../../funcionesBD/editaruta.php?idr='.$row["id"].'">'.($row["placas"]).'</a></td>';
	
	switch($row["estatusruta"])
	{
		case 0:$estatusruta="Concluida"; break;
		case 1:$estatusruta="Iniciada"; break;
		case 2:$estatusruta="Sin iniciar"; break;
	}
	
	$filas.='<td><a class="a_registro" href="../../funcionesBD/editaruta.php?idr='.$row["id"].'">'.utf8_decode($estatusruta).'</a></td>';
	$filas.='</tr>';
$i++;
}
}
$encabezado='
<td align="center">ID</td>
<td align="center">Ruta</td>
<td align="center">Transporte</td>
<td align="center">Capacidad</td>
<td align="center">Placas</td>
<td align="center">Estatus</td>';


if($i<10)
{
	for($j=$i;$j<15;$j++)
	{	
	if($j%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
	$filas.="<td></td><td></td><td></td><td></td><td></td><td></td>";
	$filas.="</tr>";
	}
}

if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
if(($pagina+1)>$paginas){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}			


$catalogo='<div class="tipo">
<table border="0" width="95%"><tbody><tr>';
/*
$catalogo.='<td><input type="button" value="<" onclick="paginacionGridOfertaruta('.$pag_anterior.');"></td>
<td><input type="button" value=">" onclick="paginacionGridOfertaruta('.$pag_siguiente.');" ></td>';
*/
$catalogo.='<td><a href="javascript:window.print();">
<img src="../../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
<td><b>Rutas de entrega</b></td>
<td width="40%" align="right"><input type="button" value="Regresar a el listado de ofertas" onclick="window.location=\'../configrutas/listado_ofertas.php\'"></td>
<td width="40%" align="right"><input type="button" value="Agregar ruta" onclick="confiruta('.$oferta.');"></td>
</tr></tbody></table></div><br>';
					
$catalogo.='<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="100%">
<tr class="tit_tabla_buscar">'.$encabezado.'</tr>			
<tr class="titulo_filtros" title="Segmento de búsqueda"></tr>
'.$filas.'</table>';		

return  $catalogo;
}
/////////////////////////////////////////////////////////////////////////////////////////// 
?>








<?php

/////////////////////////////////////////////////////////////////////////////////////////// 
function gridOfertasrutas($pagina=1,$post=false,$filtro=1,$paginacion=15,$elimina=false)
{
if($post){include("../../../netwarelog/webconfig.php");}
else{include("../../../../netwarelog/webconfig.php");}

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

if($pagina==1){$begin=0;}else{$begin=($paginacion*$pagina)-$paginacion;}

$consulta0="SELECT o.cantidadExistente ,o.estatus,o.idOferta,p.codigo,p.nombre producto,u.compuesto unidad, o.precio,o.cantidad,o.fechainicio,o.fechafin,o.fechaCreacion";
$consulta0.=" from sms_oferta_cliente oc,sms_oferta o , mrp_producto p, mrp_unidades u where o.idProducto=p.idProducto and u.idUni=o.idUnidad and o.idOferta=oc.`idOferta`  and oc.`contesto`=1  and ".$filtro." GROUP BY oc.idOferta order by o.idOferta desc "; 		
$q0=$conection->query($consulta0);
$paginas=($q0->num_rows/$paginacion);if($q0->num_rows%$paginacion!=0){$paginas++;}
//$paginas=ceil($paginas);

$consulta="SELECT o.cantidadExistente ,o.estatus,o.idOferta,p.codigo,p.nombre producto,u.compuesto unidad, o.precio,o.cantidad,o.fechainicio,o.fechafin,o.fechaCreacion";
$consulta.=" from sms_oferta_cliente oc,sms_oferta o , mrp_producto p, mrp_unidades u where o.idProducto=p.idProducto and u.idUni=o.idUnidad and o.idOferta=oc.`idOferta`  and oc.`contesto`=1  and ".$filtro." GROUP BY oc.idOferta order by o.idOferta desc  LIMIT ".$begin.",".$paginacion; 		
$q=$conection->query($consulta);
$i=0;

$link="offer/form.php";

while($row=$q->fetch_array(MYSQLI_ASSOC))
{
	
		list($fecha, $hora) = explode(' ', $row["fechainicio"]);
					list($ano, $mes, $dia) = explode('-', $fecha);
					list($hour, $minuto, $segundo) = explode(':', $hora);	
					$fechainicio = $dia . "/" . $mes . "/" . $ano;
		
		list($fecha, $hora) = explode(' ', $row["fechafin"]);
					list($ano, $mes, $dia) = explode('-', $fecha);
					list($hour, $minuto, $segundo) = explode(':', $hora);	
					$fechafin = $dia . "/" . $mes . "/" . $ano;
					
		list($fecha, $hora) = explode(' ', $row["fechaCreacion"]);
					list($ano, $mes, $dia) = explode('-', $fecha);
					list($hour, $minuto, $segundo) = explode(':', $hora);
						$fechacreacion = $dia . "/" . $mes . "/" . $ano;
						
		$fecha_actual = strtotime(date("Y-m-d H:i:00",time()));
		$fecha_entrada =strtotime($row["fechafin"]);
		
		
	date_default_timezone_set('America/Mexico_City'); 
	$hoy_beta = date("Y-m-d");
	$fecha_actual= strtotime($hoy_beta);			
										
	   if(($fecha_actual > $fecha_entrada) && $row["estatus"]=="Vigente" ){
	   	  		
	   	$qr=$conection->query("UPDATE sms_oferta set estatus='Vencida' where idOferta=".$row["idOferta"]);	
       	$estatus="Vencida";		
	   	}else{
	   	
			$estatus=$row["estatus"];
		}
							
	
	if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
			
	$filas.='<td><input type="button" onclick="ListaRutas('.$row["idOferta"].',1);" value="Configurar rutas de entrega"></td>';
	$filas.='<td><a class="a_registro" href="">'.($row["idOferta"]).'</a></td>';
	$filas.='<td><a class="a_registro" href="">'.utf8_decode($row["codigo"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="">'.($row["cantidad"]).'</a></td>';
	$filas.='<td><a class="a_registro" href="">'.utf8_decode($row["unidad"]).'</a></td>';
	$filas.='<td><a class="a_registro" href="">'.utf8_decode($row["producto"]).'</a></td>';
	
	$filas.='<td align="center"><a class="a_registro" href="">$'.number_format($row["precio"],2,".",",").'</a></td>';
$filas.='<td align="center"><a class="a_registro" href="">'.$row["cantidadExistente"].'</a></td>';
	
		
	$filas.='<td align="center"><a class="a_registro" href="">'.($fechainicio).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="">'.($fechafin).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="">'.($fechacreacion).'</a></td>';
	
	
	if($estatus == "Vencida")
			$filas.='<td align="center"><a class="a_registro" href=""><div style="color: #FF000C;">'.utf8_decode($estatus).'</div></a></td>';
	if($estatus == "Vigente")
			$filas.='<td align="center"><a class="a_registro" href=""><div style="color: #01a05f;">'.utf8_decode($estatus).'</div></a></td>';
	if($estatus == "Cerrada")
			$filas.='<td align="center"><a class="a_registro" href=""><div style="color: #000CFF;">'.utf8_decode($estatus).'</div></a></td>';
		$filas.='</tr>';
	//$filas.='<td align="center"><a class="a_registro" href="">'.($estatus).'</a></td>';	
	
$i++;
}

$encabezado='
<td align="center" style="width: 5%"></td>
<td align="center">ID</td>
<td align="center">Código</td>
<td align="center">Cantidad</td>
<td align="center">Unidad</td>
<td align="center">Producto</td>
<td align="center">Precio</td>
<td align="center">Existencia</td>
<td align="center">Fecha inicio</td>
<td align="center">Fecha fin</td>
<td align="center">Fecha de creación</td>
<td align="center">Estatus</td>';


if($i<10)
{
	for($j=$i;$j<15;$j++)
	{	
	if($j%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
	$filas.="<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
	$filas.="</tr>";
	}
}

if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
if(($pagina+1)>$paginas){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}			


$catalogo='<div class="tipo">
<table><tbody><tr>
<td><input type="button" value="<" onclick="paginacionGridOfertaruta('.$pag_anterior.','.$filtro.');"></td>
<td><input type="button" value=">" onclick="paginacionGridOfertaruta('.$pag_siguiente.','.$filtro.');" ></td>
<td><a href="javascript:window.print();">
<img src="../../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
<td><b>Ofertas</b></td></tr></tbody></table></div><br>';
					
$catalogo.='<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="100%">
<tr class="tit_tabla_buscar">'.$encabezado.'</tr>			
<tr class="titulo_filtros" title="Segmento de búsqueda"></tr>
'.$filas.'</table>';		

return  $catalogo;
}
/////////////////////////////////////////////////////////////////////////////////////////// 

?>	