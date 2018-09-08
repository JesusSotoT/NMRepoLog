<?php
//include("../../../netwarelog/catalog/conexionbd.php");
if(count($_POST)>0)
{
	switch ($_POST["funcion"])
	{
		case 'gridlidquidacion': echo  gridliquidacionrutas($_POST["pagina"],true,$_POST["filtro"]);break;
		case 'gridliquidacionruta': echo  gridliquidacionruta($_POST["pagina"],$_POST["oferta"]);break;	
		case 'liquidacliente': echo liquidacliente($_POST["id"],$_POST["cantidad"],$_POST["comentarios"],$_POST["factura"],$_POST["ruta"]); break;
	}
}

function datosliquidacion($id)
{
	include("../../../../netwarelog/webconfig.php");
	$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
	$consulta="Select comentarios, recibiofactura, cantidadentregada from sms_liquidacion_ruta_cliente where idRutaOfertaCliente=".$id;	
	$q=$conection->query($consulta);
	if($q->num_rows)
	{
		$row=$q->fetch_array(MYSQLI_ASSOC);
		return $row;
	}	else{ return 0; }	
}


function liquidacliente($id,$cantidad,$comentarios,$factura,$idRuta)
{
include("../../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$consulta="INSERT INTO sms_liquidacion_ruta_cliente(idRutaOfertaCliente,cantidadentregada,recibiofactura,comentarios) VALUES ('".$id."','".$cantidad."','".$factura."','".$comentarios."')";
$q=$conection->query($consulta);

$consulta1="UPDATE sms_ruta_oferta_cliente set status=1 where id=".$id;
$q1=$conection->query($consulta1);

$cambiaestatus=true;
$q2=$conection->query("Select status from sms_ruta_oferta_cliente where idRutaOferta=".$idRuta);
while($row2=$q2->fetch_array(MYSQLI_ASSOC))
{
		if($row2["status"]==0){ $cambiaestatus=false; }
}
if($cambiaestatus)
{
	$q3=$conection->query("UPDATE sms_ruta_oferta set status=0 where id=".$idRuta);	
}



/*
$q6=$conection->query("select distinct(oc.idOferta) oferta from sms_ruta_oferta_cliente roc, sms_oferta_cliente oc where oc.id=roc.idOfertacliente and roc.idRutaOferta=".$idRuta);
$row6=$q6->fetch_array(MYSQLI_ASSOC);

$cambiaestatuspromo=true;
$q4=$conection->query("select ro.status   from sms_ruta_oferta_cliente roc, sms_oferta_cliente oc,sms_ruta_oferta ro where oc.id=roc.idOfertacliente and ro.id=roc.idRutaOferta  and oc.idOferta=".$row6["oferta"]);
while($row4=$q4->fetch_array(MYSQLI_ASSOC))
{
		if($row4["status"]==1){ $cambiaestatuspromo=false; }
}
if($cambiaestatuspromo)
{
	$q5=$conection->query("UPDATE sms_oferta set estatus='Cerrada' where idOferta=".$row6["oferta"]);	
	
}
*/


}

function datosOferta($idOferta)
{
include("../../../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$consulta='select 
CONCAT(o.cantidad," ",u.compuesto," de ",p.nombre,"  $",o.precio) oferta 
from 
sms_oferta o,
mrp_producto p, 
mrp_unidades u
where  o.idOferta='.$idOferta.' and p.idProducto=o.idProducto and o.idunidad=u.idUni LIMIT 0,1 ';
$q=$conection->query($consulta);
$row=$q->fetch_array(MYSQLI_ASSOC);

return $row;
}


function datosRuta($idRuta,$oferta)
{
include("../../../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

$consulta='select cc.id, 
CONCAT(o.cantidad," ",u.compuesto," de ",p.nombre,"  $",o.precio) oferta ,
CONCAT(tu.nombre," ",cu.capacidad," placas:",tu.placas) AS transporte,
ro.nombre nombreruta ,
oc.cantidad pedido,
IF(roc.status = 0, "Sin liquidar", "Liquidada") status,
CONCAT(cc.nombretienda," , ",cc.nombre) as cliente
,CONCAT(cc.direccion," Colonia ",cc.colonia," C.P:",cc.cp," ",e.estado," ",m.municipio) direccion,
g.nombre as giro ,
r.nombre rubro
from 
sms_ruta_oferta_cliente roc,
sms_oferta_cliente oc , 
comun_cliente cc, 
estados e, 
municipios m, 
sms_giro g,
sms_rubro r, 
sms_oferta o,sms_ruta_oferta ro, 
trt_unidades tu,
trt_capacidad_unidad cu, 
mrp_producto p, 
mrp_unidades u
where ro.id='.$idRuta.' and
roc.idOfertacliente=oc.id and cc.id=oc.idCliente and cc.idEstado=e.idestado and 
cc.idMunicipio=m.idmunicipio and g.idGiro=cc.idGiro and cc.idRubro=r.idRubro and
o.idOferta=oc.idOferta and ro.id=roc.idRutaOferta and (ro.status=1 or ro.status=0)  and 
tu.id=ro.idTransporte and cu.id=tu.capacidad and p.idProducto=o.idProducto and o.idunidad=u.idUni LIMIT 0,1 ';	
$q=$conection->query($consulta);
$row=$q->fetch_array(MYSQLI_ASSOC);

return $row;

}

//////////////////////////////////////////////////////////////////////////////
function LiquidacionRuta($idRuta,$oferta)
{

include("../../../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

$consulta='select cc.id, roc.id idRoc,
CONCAT(o.cantidad," ",u.compuesto," de ",p.nombre,"  $",o.precio) oferta ,
CONCAT(tu.nombre," ",cu.capacidad," placas:",tu.placas) AS trasnporte,
ro.nombre nombreruta ,
oc.cantidad pedido,
o.precio,
IF(roc.status = 0, "Sin liquidar", "Liquidada") status,
CONCAT(cc.nombretienda," , ",cc.nombre) as cliente
,CONCAT(cc.direccion," Colonia ",cc.colonia," C.P:",cc.cp," ",e.estado," ",m.municipio) direccion,
g.nombre as giro ,
r.nombre rubro
from 
sms_ruta_oferta_cliente roc,
sms_oferta_cliente oc , 
comun_cliente cc, 
estados e, 
municipios m, 
sms_giro g,
sms_rubro r, 
sms_oferta o,sms_ruta_oferta ro, 
trt_unidades tu,
trt_capacidad_unidad cu, 
mrp_producto p, 
mrp_unidades u
where ro.id='.$idRuta.' and
roc.idOfertacliente=oc.id and cc.id=oc.idCliente and cc.idEstado=e.idestado and 
cc.idMunicipio=m.idmunicipio and g.idGiro=cc.idGiro and cc.idRubro=r.idRubro and
o.idOferta=oc.idOferta and ro.id=roc.idRutaOferta and  (ro.status=1 or ro.status=0)  and 
tu.id=ro.idTransporte and cu.id=tu.capacidad and p.idProducto=o.idProducto and o.idunidad=u.idUni';


$i=0;
$encabezado='
<td align="center">ID</td>
<td align="center">Cliente</td>
<td align="center">Rubro</td>
<td align="center">Giro</td>
<td align="center">Dirección</td>
<td align="center">Cantidad pedida</td>
<td align="center">Cantidad entregada</td>
<td align="center">Entregó factura</td>
<td align="center">Estatus</td>
<td align="center">Monto generado venta</td>
';
$montototal=0;
$q=$conection->query($consulta);
$filas='';
if($q->num_rows>0)
{
	while($row=$q->fetch_array(MYSQLI_ASSOC))
	{
		if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
	
	
	$q0=$conection->query("Select comentarios, recibiofactura, cantidadentregada from sms_liquidacion_ruta_cliente where idRutaOfertaCliente=".$row["idRoc"]);
	if($q0->num_rows>0)
	{
		$row0=$q0->fetch_array(MYSQLI_ASSOC);
		
		if($row0["recibiofactura"]==1){$factura="Si";}else{$factura="No";}
		$monto=$row0["cantidadentregada"]*$row["precio"];
		$cantidadentregada=$row0["cantidadentregada"];
	}
	else
	{
		$factura="";
		$monto="0.0";
		$cantidadentregada="";
	}
	$montototal+=$monto;
	
	$filas.='<td onclick="LiquidarCliente('.($row["idRoc"]).','.$oferta.','.$idRuta.','.$row["pedido"].',\''.$row["status"].'\');"><a class="a_registro" >'.($row["id"]).'</a></td>';
	$filas.='<td onclick="LiquidarCliente('.($row["idRoc"]).','.$oferta.','.$idRuta.','.$row["pedido"].',\''.$row["status"].'\');"><a class="a_registro" >'.utf8_encode($row["cliente"]).'</a></td>';
	$filas.='<td onclick="LiquidarCliente('.($row["idRoc"]).','.$oferta.','.$idRuta.','.$row["pedido"].',\''.$row["status"].'\');"><a class="a_registro" >'.utf8_encode($row["rubro"]).'</a></td>';
	$filas.='<td onclick="LiquidarCliente('.($row["idRoc"]).','.$oferta.','.$idRuta.','.$row["pedido"].',\''.$row["status"].'\');"><a class="a_registro" >'.utf8_encode($row["giro"]).'</a></td>';
	$filas.='<td onclick="LiquidarCliente('.($row["idRoc"]).','.$oferta.','.$idRuta.','.$row["pedido"].',\''.$row["status"].'\');"><a class="a_registro" >'.utf8_encode($row["direccion"]).'</a></td>';
	$filas.='<td onclick="LiquidarCliente('.($row["idRoc"]).','.$oferta.','.$idRuta.','.$row["pedido"].',\''.$row["status"].'\');" align="center"><a class="a_registro" >'.($row["pedido"]).'</a></td>';
	$filas.='<td onclick="LiquidarCliente('.($row["idRoc"]).','.$oferta.','.$idRuta.','.$row["pedido"].',\''.$row["status"].'\');" align="center"><a class="a_registro" >'.$cantidadentregada.'</a></td>';
	$filas.='<td onclick="LiquidarCliente('.($row["idRoc"]).','.$oferta.','.$idRuta.','.$row["pedido"].',\''.$row["status"].'\');" align="center"><a class="a_registro" >'.$factura.'</a></td>';
	$filas.='<td onclick="LiquidarCliente('.($row["idRoc"]).','.$oferta.','.$idRuta.','.$row["pedido"].',\''.$row["status"].'\');"><a class="a_registro">'.utf8_encode($row["status"]).'</a></td>';
	$filas.='<td onclick="LiquidarCliente('.($row["idRoc"]).','.$oferta.','.$idRuta.','.$row["pedido"].',\''.$row["status"].'\');" align="center"><a class="a_registro" >$'.number_format($monto,2,".",",").'</a></td>';

	$filas.='</tr>';
	$i++;	
	}//end while

}//end if



	if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
	$filas.='<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
	$filas.='<td  align="center"><a class="a_registro" ><h2>Total Ruta:$'.number_format($montototal,2,".",",").'</h2></a></td>';
	$filas.='</tr>';
	$i++;

if($i<15)
{
	for($j=$i;$j<15;$j++)
	{	
	if($j%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
	$filas.="<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
	$filas.="</tr>";
	}
}


$catalogo='<div class="tipo">
<table border="0" width="95%"><tbody><tr>';
/*
$catalogo.='<td><input type="button" value="<" onclick="paginacionGridOfertaruta2();"></td>
<td><input type="button" value=">" onclick="paginacionGridOfertaruta2();" ></td>';
*/
$catalogo.='<td><a href="javascript:window.print();">
<img src="../../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
<td><b>liquidación de cliente</b></td>

<td width="80%" align="right"><input type="button" value="Regresar al listado de ofertas" onclick="window.location=\'../../../../modulos/sms/views/configrutas/liquidacion_rutas.php\'"></td>

<td width="80%" align="right"><input type="button" value="Regresar al listado de rutas" onclick="window.location=\'../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$oferta.'\'"></td>
</tr></tbody></table></div><br>';

$catalogo.='<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="100%">
<tr class="tit_tabla_buscar">'.$encabezado.'</tr>			
<tr class="titulo_filtros" ></tr>
'.$filas.'</table>';

		

return  $catalogo;

}

/////////////////////////////////////////////////////////////////////////////////////////// 
function gridliquidacionruta($pagina=1,$oferta,$filtro=1,$paginacion=15,$elimina=false)
{	
include("../../../../netwarelog/webconfig.php");

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

if($pagina==1){$begin=0;}else{$begin=($paginacion*$pagina)-$paginacion;}

$consulta0="select 
ro.nombre nombreruta,
CONCAT(ut.tipo,' ',um.marca) transporte,
c.capacidad,
u.placas,
ro.status estatusruta 
from 
sms_oferta_cliente of,
sms_ruta_oferta_cliente roc,
sms_ruta_oferta ro,
trt_unidades u,
trt_unidad_tipo ut,
trt_unidad_marca um,
trt_capacidad_unidad c 
where 
 (ro.status=1 or ro.status=0 ) and 
roc.idOfertacliente=of.id and  
roc.idRutaOferta=ro.id and 
ro.idTransporte=u.id and 
ut.id=u.tipo and 
um.id=u.marca and 
c.id=u.capacidad and 
of.idOferta=".$oferta."  
group by ro.id";



$q0=$conection->query($consulta0);



if(!$q0){
//$paginas=ceil($paginas);
$paginas=0;
}else
{
	$paginas=($q0->num_rows/$paginacion);if($q0->num_rows%$paginacion!=0){$paginas++;}
	
}
$consulta="select
ro.id, 
ro.nombre nombreruta,
CONCAT(ut.tipo,' ',um.marca) transporte,
c.capacidad,
u.placas,
IF(ro.status = 1, 'Iniciada', 'Concluida') estatusruta
from 
sms_oferta_cliente of,
sms_ruta_oferta_cliente roc,
sms_ruta_oferta ro,
trt_unidades u,
trt_unidad_tipo ut,
trt_unidad_marca um,
trt_capacidad_unidad c
where 
 (ro.status=1 or ro.status=0 ) and
roc.idOfertacliente=of.id and  
roc.idRutaOferta=ro.id and
ro.idTransporte=u.id and
ut.id=u.tipo and
um.id=u.marca and
c.id=u.capacidad and
of.idOferta=".$oferta." 
group by ro.id LIMIT ".$begin.",".$paginacion; 		
$q=$conection->query($consulta);
$i=0;



$link="offer/form.php";
if($paginas!=0){
while($row=$q->fetch_array(MYSQLI_ASSOC))
{
	if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
			
	$filas.='<td><a class="a_registro" href="../../views/configrutas/form_liquidacion.php?oferta='.$oferta.'&idr='.$row["id"].'">'.($row["id"]).'</a></td>';
	$filas.='<td><a class="a_registro" href="../../views/configrutas/form_liquidacion.php?oferta='.$oferta.'&idr='.$row["id"].'">'.utf8_decode($row["nombreruta"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="../../views/configrutas/form_liquidacion.php?oferta='.$oferta.'&idr='.$row["id"].'">'.($row["transporte"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="../../views/configrutas/form_liquidacion.php?oferta='.$oferta.'&idr='.$row["id"].'">'.($row["capacidad"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="../../views/configrutas/form_liquidacion.php?oferta='.$oferta.'&idr='.$row["id"].'">'.($row["placas"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="../../views/configrutas/form_liquidacion.php?oferta='.$oferta.'&idr='.$row["id"].'">'.utf8_decode($row["estatusruta"]).'</a></td>';
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

if(!$q0){

$catalogo.='<td><input type="button" value="<" onclick="paginacionGridOfertaruta2('.$pag_anterior.','.$oferta.');"></td>
<td><input type="button" value=">" onclick="paginacionGridOfertaruta2('.$pag_siguiente.','.$oferta.');" ></td>';
}
$catalogo.='<td><a href="javascript:window.print();">
<img src="../../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
<td><b>Rutas de entrega</b></td>
<td width="80%" align="right"><input type="button" value="Regresar al listado de ofertas" onclick="window.location=\'../../../../modulos/sms/views/configrutas/liquidacion_rutas.php\'"></td>
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
function gridliquidacionrutas($pagina=1,$post=false,$filtro=1,$paginacion=15,$elimina=false)
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
$consulta.=" from sms_oferta_cliente oc,sms_oferta o , mrp_producto p, mrp_unidades u where o.idProducto=p.idProducto and u.idUni=o.idUnidad and o.idOferta=oc.`idOferta`  and oc.`contesto`=1   and ".$filtro." GROUP BY oc.idOferta order by o.idOferta desc  LIMIT ".$begin.",".$paginacion; 		
$q=$conection->query($consulta);

$i=0;





while($row=$q->fetch_array(MYSQLI_ASSOC))
{
	if($i%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
	
		
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
					
					if($hour < 12)
						$indicador = "am";
					else
					{
						$indicador = "pm";
						$hour = $hour-12;
					}
						
					$valor = $dia . "/" . $mes . "/" . $ano . " " . $hour . ":" . $minuto . " " . $indicador; 
					
					
	date_default_timezone_set('America/Mexico_City'); 
	$hoy_beta = date("Y-m-d");
	$fecha_actual= strtotime($hoy_beta);
					
				//	$fecha_actual = strtotime(date("Y-m-d H:i:00",time()));
					$fecha_entrada =strtotime($row["fechafin"]);
	
	

										
	  if(($fecha_actual > $fecha_entrada) && $row["estatus"]=="Vigente" ){
	   	  		
	   	$qr=$conection->query("UPDATE sms_oferta set estatus='Vencida' where idOferta=".$row["idOferta"]);	
       	$estatus="Vencida";		
	   	}else{
	   	
			$estatus=$row["estatus"];
		}
	
													
			
	$filas.='<td><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'">'.($row["idOferta"]).'</a></td>';
	$filas.='<td><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'">'.utf8_decode($row["codigo"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'">'.($row["cantidad"]).'</a></td>';
	$filas.='<td><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'">'.utf8_decode($row["unidad"]).'</a></td>';
	$filas.='<td><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'">'.utf8_decode($row["producto"]).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'">$'.number_format($row["precio"],2,".",",").'</a></td>';
	
	$filas.='<td align="center"><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'">'.$row["cantidadExistente"].'</a></td>';
	
	
	$filas.='<td align="center"><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'">'.($fechainicio).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'">'.($fechafin).'</a></td>';
	$filas.='<td align="center"><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'">'.($valor).'</a></td>';
	//$filas.='<td align="center"><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'">'.$estatus.'</a></td>';	
	if($estatus == "Vencida")
			$filas.='<td align="center"><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'"><div style="color: #FF000C;">'.utf8_decode($estatus).'</div></a></td>';
	if($estatus == "Vigente")
			$filas.='<td align="center"><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'"><div style="color: #01a05f;">'.utf8_decode($estatus).'</div></a></td>';
	if($estatus == "Cerrada")
			$filas.='<td align="center"><a class="a_registro" href="../../../../modulos/sms/views/configrutas/liquidacion.php?idOferta='.$row["idOferta"].'"><div style="color: #000CFF;">'.utf8_decode($estatus).'</div></a></td>';
		$filas.='</tr>';
	//$filas.='</tr>';
$i++;
}

$encabezado='
<td align="center">ID</td>
<td align="center" width="50">Código Producto</td>
<td align="center">Cantidad</td>
<td align="center">Unidad</td>
<td align="center">Producto</td>
<td align="center">Precio</td>
<td align="center">Existencia</td>
<td align="center">Fecha inicio oferta</td>
<td align="center">Fecha fin oferta</td>
<td align="center">Fecha de creación oferta</td>
<td align="center">Estatus</td>';


if($i<10)
{
	for($j=$i;$j<15;$j++)
	{	
	if($j%2==0){$filas.='<tr class="busqueda_fila">';}else{$filas.='<tr class="busqueda_fila2">';}
	$filas.="<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
	$filas.="</tr>";
	}
}

if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
if(($pagina+1)>$paginas){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}			


$catalogo='<div class="tipo">
<h2>Selecciona la promoción y posteriormente la ruta que deseas liquidar</h2>
<table><tbody><tr>
<td><input type="button" value="<" onclick="paginacionGridOfertaruta('.$pag_anterior.',\''.$filtro.'\');"></td>
<td><input type="button" value=">" onclick="paginacionGridOfertaruta('.$pag_siguiente.',\''.$filtro.'\');" ></td>
<td><a href="javascript:window.print();">
<img src="../../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
<td><b>Listado de Ofertas</b></td></tr></tbody></table></div><br>';
					
$catalogo.='<table class="busqueda" border="1" cellpadding="3" cellspacing="1" width="100%">
<tr class="tit_tabla_buscar">'.$encabezado.'</tr>			
<tr class="titulo_filtros" title="Segmento de búsqueda"></tr>
'.$filas.'</table>';		

return  $catalogo;
}
/////////////////////////////////////////////////////////////////////////////////////////// 

?>	