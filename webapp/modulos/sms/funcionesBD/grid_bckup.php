<?php
//include("../../../netwarelog/catalog/conexionbd.php");
switch ($_POST["funcion"])
{
	case 'gridofertas': echo  gridOfertas($_POST["pagina"]);break;
	
}


/////////////////////////////////////////////////////////////////////////////////////////// 
function gridOfertas($pagina=1,$filtro=1,$paginacion=15,$elimina=false)
{
include("../../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

if($pagina==1){$begin=0;}else{$begin=($paginacion*$pagina)-$paginacion;}

$consulta0="SELECT o.idOferta,p.codigo,p.nombre producto,u.compuesto unidad, o.precio,o.cantidad,o.fechainicio,o.fechafin,o.fechaCreacion";
$consulta0.=" FROM sms_oferta o, mrp_producto p, mrp_unidades u WHERE o.idProducto=p.idProducto and u.idUni=o.idUnidad order by o.idOferta desc "; 		
$q0=$conection->query($consulta0);
$paginas=($q0->num_rows/$paginacion);if($q0->num_rows%$paginacion!=0){$paginas++;}
//$paginas=ceil($paginas);

$consulta="SELECT o.idOferta,p.codigo,p.nombre producto,u.compuesto unidad, o.precio,o.cantidad,o.fechainicio,o.fechafin,o.fechaCreacion";
$consulta.=" FROM sms_oferta o, mrp_producto p, mrp_unidades u WHERE o.idProducto=p.idProducto and u.idUni=o.idUnidad order by o.idOferta desc  LIMIT ".$begin.",".$paginacion; 		
$q=$conection->query($consulta);
$i=0;

$link="offer/form.php";

while($row=$q->fetch_array(MYSQLI_ASSOC))
{
	if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
			
	$filas.='<td><a class="a_registro" href="'.$link.'">'.($row["idOferta"]).'</a></td>';
	$filas.='<td><a class="a_registro" href="'.$link.'">'.utf8_decode($row["codigo"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.($row["cantidad"]).'</a></td>';
	$filas.='<td><a class="a_registro" href="'.$link.'">'.utf8_decode($row["unidad"]).'</a></td>';
	$filas.='<td><a class="a_registro" href="'.$link.'">'.utf8_decode($row["producto"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="'.$link.'">$'.number_format($row["precio"],2,".",",").'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.($row["fechainicio"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.($row["fechafin"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="'.$link.'">'.($row["fechaCreacion"]).'</a></td>';
		
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
<td align="center">Fecha inicio</td>
<td align="center">Fecha fin</td>
<td align="center">Fecha de creación</td>
<td align="center">Estatus</td>';


if($i<10)
{
	for($j=$i;$j<15;$j++)
	{	
	if($j%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
	$filas.="<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
	$filas.="</tr>";
	}
}

if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
if(($pagina+1)>$paginas){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}			


$catalogo='<div class="tipo">
<table><tbody><tr>
<td><input type="button" value="<" onclick="paginacionGridOferta('.$pag_anterior.');"></td>
<td><input type="button" value=">" onclick="paginacionGridOferta('.$pag_siguiente.');" ></td>
<td><a href="javascript:window.print();">
<img src="../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
<td><b>Ofertas</b></td></tr></tbody></table></div><br>';
					
$catalogo.='<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="100%">
<tr class="tit_tabla_buscar">'.$encabezado.'</tr>			
<tr class="titulo_filtros" title="Segmento de búsqueda"></tr>
'.$filas.'</table>';		

return  $catalogo;
}
/////////////////////////////////////////////////////////////////////////////////////////// 
?>	