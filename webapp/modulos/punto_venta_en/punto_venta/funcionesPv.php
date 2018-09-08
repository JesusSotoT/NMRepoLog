<?php
include_once("../../netwarelog/catalog/conexionbd.php");
date_default_timezone_set("Mexico/General");

switch ($_POST["funcion"])
{
	case 'checatimbres': echo checatimbres(); break;
	case 'calculapago': echo calculapago($_POST["total"]); break;
	case 'checalimitecredito': echo checalimitecredito($_POST["cliente"],$_POST["monto"]); break;
	case 'saldocaja': echo saldocaja($_POST["sucursal"]); break;
	case 'ingresamercancia': echo ingresamercancia($_POST["producto"],$_POST["sucursal"],$_POST["cantidad"],$_POST["proveedor"],$_POST["costo"]); break;
	case 'productosexistencias': echo productosexistencias($_POST["id"]); break;
	case 'existenciassucursal': echo existenciasSucursal($_POST["id"]); break;
	case 'cancelarventa': echo cancelarventa($_POST["id"]); break;
	case 'ventas': echo imprimeventas($_POST["inicio"],$_POST["fin"],$_POST["sucursal"],$_POST["cliente"],$_POST["vendedor"],$_POST["estatus"],$_POST["registros"]); break;
	case 'guardarFacturacion': echo guardarFacturacion($_POST["idFact"],$_POST["datos"],$_POST["monto"],$_POST["cliente"],$_POST["idventa"]); break;
	case 'entradasalidas': echo entradasalidas($_POST["inicio"],$_POST["fin"],$_POST["producto"],$_POST["movimiento"],$_POST["registros"]); break;
	case 'cargaRfcs': echo cargaRfcs($_POST["idCliente"]); break;
	case 'Iniciarcaja': echo Iniciarcaja($_POST["sucursal"],$_POST["monto"]); break;
	case 'checarExistencia': echo checarExistencia($_POST["idArticulo"],$_POST["cantidad"],$_POST["almacen"]); break;
	case 'eliminaproductocaja': echo eliminaproductocaja($_POST["id"]); break;
	case 'checatarjetaregalo': echo checatarjetaregalo($_POST["numero"],$_POST["monto"]);break;
	case 'eliminarpago': echo eliminarpago($_POST["id"],$_POST["total"]); break;
	case 'pagoscaja':echo pagoscaja($_POST["total"],$_POST["referencia"],$_POST["formapago"],$_POST["monto"],$_POST["idFormapago"],true); break;
	case 'agregaraCaja':echo imprimecaja($_POST["idArticulo"],$_POST["almacen"]); break;
	case 'cambiarcantidad':echo cambiarcantidad($_POST["id"],$_POST["cantidad"],$_POST["descuento"],$_POST["tipodescuento"]); break;
	case 'cargaFamilias':echo familias($_POST["idDepartamento"]); break;
	case 'cargaFamilias2':echo familias2($_POST["idDepartamento"]); break;
	case 'cargaLineas':echo lineas($_POST["idFamilia"]); break;
	case 'cargaLineas2':echo lineas2($_POST["idFamilia"]); break;
	case 'cargaProductos':echo productos($_POST["idLinea"]); break;
	case 'cargaProducto':echo CargaProducto($_POST["idProducto"],$_POST["almacen"]); break;
	case 'guardarVenta': echo guardarVenta($_POST["pagoautomatico"],$_POST["impuestos"],$_POST["idFact"],$_POST["sucursal"],$_POST["almacen"],$_POST["documento"],$_POST["monto"],$_POST["cambio"],$_POST["cliente"],$_POST["empleado"]); break;

}
/////////////////////////////////////////////////////////////////////////////////////////////
function checalimitecredito($cliente,$monto)
{
	$q=mysql_query("select sum(saldoactual) as debe from cxc where idCliente=".$cliente);
	while($r=mysql_fetch_object($q))
		{
			$debe=(float)$r->debe;
		}
	$q1=mysql_query("select limite_credito credito from comun_cliente where id=".$cliente);
	while($r1=mysql_fetch_object($q1))
		{
			$credito=(float)$r1->credito;
		}

	$cargo=(float)($debe+(float)($monto));

	if($cargo>$credito)
	{
		return "El limite de credito del cliente se ha excedido, su limite de credito es de $".number_format($credito,2,".",",")." y actualmente tiene un monto por liquidar de $".number_format($debe,2,".",",");
	}else
	{
		return 1;
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////
function saldocaja($sucursal)
{
	 	$q=mysql_query("select  cc.saldofinalcaja from inicio_caja i inner join corte_caja cc on i.idCortecaja=cc.idCortecaja where i.idSucursal=".$sucursal." order by i.fecha desc limit 1");
		if(mysql_num_rows($q)>0)
		{
			while($r=mysql_fetch_object($q))
			{
				return "$".number_format($r->saldofinalcaja,2,".",",");
			}
		} else { return "$0.00";}
	}
/////////////////////////////////////////////////////////////////////////////////////////////
function ingresamercancia($producto,$sucursal,$cantidad,$proveedor,$costo)
{

		mysql_query("BEGIN");
		$q=mysql_query("select idAlmacen from mrp_sucursal where idSuc=".$sucursal." limit 1");
		while($r=mysql_fetch_object($q))
		{
			$almacen=$r->idAlmacen;
		}

		$e=mysql_query("select cantidad from  mrp_stock where idProducto=".$producto." and idAlmacen=".$almacen." limit 1");
		if(mysql_num_rows($e)>0)
		{
				while($re=mysql_fetch_object($e))
				{
					$vcantidad=$re->cantidad;
				}
				 mysql_query("update mrp_stock set cantidad=".($vcantidad+$cantidad)." where idProducto=".$producto." and idAlmacen=".$almacen);
		}else
		{
				mysql_query("Insert into mrp_stock values('',".$producto.",".$cantidad.",".$almacen.",1)");
		}

		$fechaactual=date("Y-m-d H:i:s");

		if(is_numeric($proveedor))
		{
			mysql_query("insert into ingreso_mercancia values('','".$fechaactual."',".$producto.",".$proveedor.",".$cantidad.",".$sucursal.",'".$costo."');");
		}
		else
		{
			mysql_query("insert into ingreso_mercancia values('','".$fechaactual."',".$producto.",NULL,".$cantidad.",".$sucursal.",'".$costo."');");

		}



	if(!$q){$error=1;}
	if($error){mysql_query("ROLLBACK");return "Error en la transaccion";} else{mysql_query("COMMIT");
		return  existenciasSucursal($producto);}



	}
/////////////////////////////////////////////////////////////////////////////////////////////
function existenciasSucursal($idProducto)
{
$q=mysql_query("select
idSuc,
sucursal,
producto.idAlmacen,
CASE WHEN cantidad IS NOT NULL
       THEN cantidad
       ELSE 0
END AS cantidad
from (select s.idSuc,s.nombre sucursal, a.idAlmacen from mrp_sucursal s inner join almacen a on s.idAlmacen=a.idAlmacen ) as sucursales
left join
(
select p.idProducto,p.nombre producto, s.cantidad,s.idAlmacen from mrp_producto p left join mrp_stock s on p.idProducto=s.idProducto where p.idProducto=".$idProducto."
) producto on sucursales.idAlmacen=producto.idAlmacen GROUP BY sucursal ORDER BY idSuc");

$ex='<table  border="0" width="100%" class="busqueda" align="center">
    		<tr  class="busqueda_fila">
    			<th align="center">Sucursal</th>
    			<th align="center">Cantidad</th>
    		</tr>';
while($r=mysql_fetch_object($q))
{
		$ex.='
    		<tr>
    			<td align="center">'.$r->sucursal.'</td>
    			<td align="center">'.$r->cantidad.'</td>
    		</tr>';
}
		$ex.='</table>';
return $ex;
}


//**************************************************************************************************/
function imprimeventas($inicio,$fin,$sucursal,$cliente,$vendedor,$estatus,$registros)
{
		$filtro=1;

		if($fin!="")
		{
			list($a,$m,$d)=explode("-",$fin);
			$fin=$a."-".$m."-".((int)$d+1);
		}





		if($inicio!="" && $fin=="")
		{
		 	$filtro.=" and  fecha >= '".$inicio."' ";
		}
		if($fin!="" && $inicio=="")
		{
			$filtro.=" and  fecha <= '".$fin."' ";
		}
		if($inicio!="" && $fin!="")
		{
			$filtro.=" and  fecha <= '".$fin."' and   fecha >= '".$inicio."' ";
		}

		if(is_numeric($estatus))
		{
			$filtro.=" and estatus=".$estatus;
		}

		if(is_numeric($sucursal))
		{
			$filtro.=" and idSucursal=".$sucursal;
		}
		if(is_numeric($vendedor))
		{
			$filtro.=" and idEmpleado=".$vendedor;
		}
		if(is_numeric($cliente))
		{
			if($cliente==0)
			{$filtro.=" and c.nombre is null ";

			}else{	$filtro.=" and idCliente=".$cliente;}
		}


	 return ventas($filtro,$registros);
}
//**************************************************************************************************/
function cancelarventa($id)
{
	$q=mysql_query("select vp.cantidad,vp.idProducto,v.idSucursal,s.idAlmacen from venta_producto vp inner join venta v on v.idVenta=vp.idVenta inner join mrp_sucursal s on s.idSuc=v.idSucursal where v.idVenta=".$id);
		while($rowp=mysql_fetch_object($q))
		{
			$querycompuesto=mysql_query("select p.idProducto, p.esreceta, p.nombre as producto,pm.cantidad,pm.idUnidad,pu.compuesto from mrp_producto_material pm inner join mrp_unidades pu on  pm.idUnidad=pu.idUni
inner join mrp_producto p on p.idProducto=pm.idMaterial
where pm.idProducto=".$rowp->idProducto);
			if(mysql_num_rows($querycompuesto)>0)
			{
				while($rowcompuesto=mysql_fetch_array($querycompuesto))
				{
			$q0=mysql_query("Select cantidad from mrp_stock s where s.idProducto=".$rowcompuesto["idProducto"]." and s.idAlmacen=".$rowp->idAlmacen);
			$row0=mysql_fetch_object($q0);
			mysql_query("update mrp_stock s set cantidad=".($row0->cantidad+($rowp->cantidad*$rowcompuesto["cantidad"]))." where s.idProducto=".$rowcompuesto["idProducto"]." and s.idAlmacen=".$rowp->idAlmacen);

				}//end while compuesto
			}else//else si no es compuesto
			{
			$q0=mysql_query("Select cantidad from mrp_stock s where s.idProducto=".$rowp->idProducto." and s.idAlmacen=".$rowp->idAlmacen);
			$row0=mysql_fetch_object($q0);
			mysql_query("update mrp_stock s set cantidad=".($row0->cantidad+$rowp->cantidad)." where s.idProducto=".$rowp->idProducto." and s.idAlmacen=".$rowp->idAlmacen);
	    }//end while
	    	}//end else sicompuesto
	mysql_query("update venta set estatus=0 where idVenta=".$id);
	return ventas();
}
//**************************************************************************************************/
function detalleventa($id)
{
		$impuestos_venta=array();
			/*agregado*/
		$caja='<div id="caja"><table id="table-caja" border="0" width="90%">
    		<tr>

    			<th>Código</th>
    			<th width="30%">Descripción</th>
    			<th>Cantidad</th>
    			<th>Precio U.</th>
    			<th>Descuento</th>
    			<th>Impuestos</th>
    			<th>Subtotal</th>
    		</tr>';
$i=0;
$supertotal=0;
$total=0;

$caja_v=array();
$q=mysql_query("select * from venta_producto vp inner join mrp_producto p on vp.idProducto=p.idProducto where vp.idVenta=".$id);
		while($rowp=mysql_fetch_object($q))
		{
			$caja_v[$rowp->idProducto]=$rowp;

	    }//end while


	foreach($caja_v as $id=>$producto)
	{


		/*IMPUESTOS*/
	$qi=mysql_query("select i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$id);
		if(mysql_num_rows($qi)>0)
		{
			while($ri = mysql_fetch_object($qi))
			{
				if(array_key_exists($ri->impuesto,$impuestos_venta))
				{
					$impuestos_venta[$ri->impuesto]=(($producto->precioventa*$ri->valor)/100+$impuestos_venta[$ri->impuesto])*$producto->cantidad;
				}
				else
				{
					$impuestos_venta[$ri->impuesto]=(($producto->precioventa*$ri->valor)/100)*$producto->cantidad;
				}
			}
		}

			/* END IMPUESTOS*/


			$descuento="";
			$descuentogeneral=0;
			if($producto->tipodescuento=="%")
			{
				$descuentogeneral=(($producto->precioventa*$producto->descuento)/100)*$producto->cantidad;
				$descuento='$'.number_format($descuentogeneral,2,".",",");
			}
			if($producto->tipodescuento=="$")
			{
				$descuentogeneral=$producto->descuento;
				$descuento='$'.number_format($producto->descuento,2,".",",");
			}

			if(	$descuento=="$")
			{
				$descuento="";
			}

			$subtotal=($producto->precioventa*$producto->cantidad)-$descuentogeneral;
				$total+=$subtotal;

			$materiales="";
			$propiedad="";

			$querycompuesto=mysql_query("select p.esreceta, p.nombre as producto,pm.cantidad,pm.idUnidad,pu.compuesto from mrp_producto_material pm inner join mrp_unidades pu on  pm.idUnidad=pu.idUni
inner join mrp_producto p on p.idProducto=pm.idMaterial
where pm.idProducto=".$id);
			if(mysql_num_rows($querycompuesto)>0)
			{
				$propiedad='style="font-size:18px;font-weight:bold;"';
				while($rowcompuesto=mysql_fetch_array($querycompuesto))
				{
					if($rowcompuesto["esreceta"]==0)
					{
						$materiales.=$rowcompuesto["cantidad"]." ".$rowcompuesto["compuesto"]." ".$rowcompuesto["producto"]."<br>";
					}
				}
			}


			if($i%2==0){ $caja.='<tr class="par" >'; } else { $caja.='<tr  class="impar">';}
				$caja.='
							<td align="center">'.$producto->codigo.'</td>
			    			<td align="center"><span '.$propiedad.'>'.$producto->nombre.'</span><br>'.$materiales.'</td>
			    			<td align="center">'.$producto->cantidad.'</td>
			    			<td align="center">$'.number_format($producto->precioventa,2,".",",").'</td>
			    			<td align="center">'.($descuento).'</td>
			    			<td align="center">$'.number_format($producto->impuestosproductoventa,2,".",",").'</td>
    						<td align="center">$'.number_format(($producto->precioventa*$producto->cantidad)-$descuentogeneral,2,".",",").'</td>
    				</tr>';
    				$supertotal+=($producto->precioventa*$producto->cantidad)-$descuentogeneral+($producto->impuestosproductoventa);
		$i++;
	}//end foreach



	$caja.='<tr>
			<td></td><td></td><td></td><td></td><td  align="left"></td><td  align="right">
			<strong>Subtotal:</strong></td><td align="center">$'.number_format($total,2,".",",");$caja.='</td></tr>';
			$totalimpuestos=0;
			foreach($impuestos_venta as $impuesto=>$valorimpuesto)
			{
				$totalimpuestos+=$valorimpuesto;
				$caja.='<tr><td></td><td></td><td></td><td></td><td  align="left"></td><td  align="right"><strong>'.$impuesto.':</strong></td><td align="center">$'.number_format($valorimpuesto,2,".",",");$caja.='</td></tr>';
			}
	$caja.='<tr>';




			if($i%2==0){ $caja.='<tr class="par" >'; } else { $caja.='<tr  class="impar">';}
				$caja.='
							<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="right"><strong>Total</strong></td>
    						<td align="center"><strong style="color:green;">$'.number_format($supertotal,2,".",",").'</strong></td>
    				</tr>';
		$i++;


   			if($i<10)
				{
					for($ii=$i;$ii<=10;$ii++)
					{
							if($ii%2==0){ $caja.='<tr class="par">'; } else { $caja.='<tr class="impar">';}
				$caja.='<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
    						<td align="center"></td>
    				</tr>';
					}
				}
    $caja.='</table>';

				/*end agregado*/
	return $caja;
}
//**************************************************************************************************/
function ventas($filtro=1,$registros=20)
{
		$i=0;
$totalmonto=0;
$totaliva=0;
	$tabla='<table align="center" width="95%" border="0" class="busqueda">
		<tr class="busqueda_fila2">
			<th align="center">Folio</th>
			<th align="center">Fecha</th>
			<th align="center">Cliente</th>
			<th align="center">Vendedor</th>';

					 //si es simple//
		if(simple()){
	  //end si es simple//


			$tabla.='<th align="center">Sucursal</th>';


					 //si es simple//
				}
	  //end si es simple//

			$tabla.='<th align="center">Estatus</th>
			<th align="center">Impuestos</th>
			<th align="center">Monto</th>
			<th align="center"></th>
		</tr>';

$q=mysql_query("select
v.idVenta as folio,
v.fecha as fecha,
CASE WHEN c.nombre IS NOT NULL
       THEN c.nombre
       ELSE 'General Public'
END AS cliente,
e.nombre as empleado,
s.nombre as sucursal,
CASE WHEN v.estatus =1
       THEN 'Activa'
       ELSE 'Cancelada'
END AS estatus,
v.montoimpuestos as iva,
(v.monto) as monto
 from venta v left join comun_cliente c on c.id=v.idCliente inner join  empleados e on e.idempleado=v.idEmpleado inner join mrp_sucursal s on s.idSuc=v.idSucursal
 where ".$filtro." order by fecha desc LIMIT 0, ".$registros."");



if(mysql_num_rows($q)>0)
{
		while($r=mysql_fetch_object($q))
		{

			if($r->estatus=='Cancelada'){ $propiedad="style='color:red;'"; } else { $propiedad="";  }

			$tabla.='<tr class="busqueda_fila2">
			<td align="center">'.$r->folio.'</td>
			<td>'.formato_fecha($r->fecha).'</td>
			<td>'.$r->cliente.'</td>
			<td align="center">'.$r->empleado.'</td>';


					 //si es simple//
		if(simple()){
	  //end si es simple//
			$tabla.='<td>'.$r->sucursal.'</td>';


					 //si es simple//
	}
	  //end si es simple//
			$tabla.='<td align="center"><span '.$propiedad.'>'.$r->estatus.'</span></td>
			<td align="center">$'.number_format($r->iva,2,".",",").'</td>
			<td align="center">$'.number_format($r->monto,2,".",",").'</td>
			<td align="center">';
			//if($r->estatus!='Cancelada')
			//{
				$tabla.='<img src="img/editar.png" height="25" width="25" style="cursor:pointer;"  onclick="detalleVenta('.$r->folio.');" >';
			//}
			$tabla.='</td>
		</tr>';
		$i++;
		$totaliva+=$r->iva;
		$totalmonto+=$r->monto;


		}
		//if($filtro!="1")
		//{
		$tabla.='<tr class="busqueda_fila2">
			<td></td>
			<td></td>
			<td></td>
			<td></td>';


					 //si es simple//
		if(simple()){
	  //end si es simple//
			$tabla.='<td></td>';
						 //si es simple//
	}
	  //end si es simple//

			$tabla.='<td></td>
			<td align="center"><strong>Total:$'.number_format($totaliva,2,".",",").'</strong></td>
			<td align="center"><strong>Total:$'.number_format($totalmonto,2,".",",").'</strong></td>
			<td></td>
		</tr>';
		//}

}

if($i<=15)
{
	for($e=$i;$e<=15;$e++)
	{
		$tabla.='<tr class="busqueda_fila2"> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td> <td></td>	</tr>';
	}
}


		return $tabla;
}
//**************************************************************************************************/
function cargaRfcs($idCliente)
{
	$rfcs="<select id='rfc'>";
	$q=mysql_query("select id , rfc from comun_facturacion where nombre=".$idCliente);
	while($r=mysql_fetch_object($q))
	{
		$rfcs.="<option value='".$r->id."'>".$r->rfc."</option>";
	}
	$rfcs.="</select>";
	return  $rfcs;
}
//**************************************************************************************************/
function formato_fecha($date)
{
	list($anio,$mes,$dia)=explode("-",$date);
	list($dia,$hora)=explode(" ",$dia);
	list($h,$m,$s)=explode(":",$hora);

	return $dia."/".$mes."/".$anio." ".$h.":".$m;
}


/**************************************************************************/
function entradasalidas($inicio="",$fin="",$producto="",$movimiento="",$registros=20)
{
	$filtro="1";

	if($fin!="")
		{
			list($a,$m,$d)=explode("-",$fin);
			$fin=$a."-".$m."-".((int)$d+1);
		}

		if($inicio!="" && $fin=="")
		{
		 	$filtro.=" and  fecha >= '".$inicio."' ";
		}
		if($fin!="" && $inicio=="")
		{
			$filtro.=" and  fecha <= '".$fin."' ";
		}
		if($inicio!="" && $fin!="")
		{
			$filtro.=" and  fecha <= '".$fin."' and   fecha >= '".$inicio."' ";
		}

		if(is_numeric($producto))
		{
			$filtro.=" and idProducto=".$producto;
		}
		if($movimiento!="")
		{
			$filtro.=" and movimiento='".trim($movimiento)."'";
		}


$q=mysql_query("select * from
(
select
distinct(mm.id) as folio,
mm.fechamovimiento as fecha,
p.nombre as producto,
p.idProducto as idProducto,
mm.cantidadmovimiento as cantidad,
'' as monto,
'' as sucursal,
'' as proveedor_cliente,
'Traspaso' as movimiento,
(Select aa.nombre from almacen aa where aa.idAlmacen=mm.idAlmacenOrigen   ) as origen,
(Select aa.nombre from almacen aa where aa.idAlmacen=mm.idAlmacenDestino   ) as destino
from movimientos_mercancia mm, mrp_producto p ,almacen a
where p.idProducto=mm.idProducto

UNION


select
im.id as folio,
im.fecha as fecha,
p.nombre as producto,
p.idProducto as idProducto,
im.cantidad,
im.costo as monto,
s.nombre sucursal,
prv.razon_social as proveedor_cliente,
'Ingreso inventario' as movimiento,
'' as origen,
'' as destino
from  ingreso_mercancia im inner join mrp_producto p on p.idProducto=im.idProducto inner join mrp_sucursal s on s.idSuc=im.idSuc left join mrp_proveedor prv on prv.idPrv=im.idProveedor


UNION

select
dc.idCompra as folio,
c.fecha as fecha,
p.nombre as producto,
p.idProducto as idProducto,
dc.cantidad,
dc.costo as monto,
s.nombre sucursal,
pr.razon_social as proveedor_cliente,
'Compra' as movimiento,
'' as origen,
'' as destino
from mrp_detalle_compra dc,mrp_producto_orden_compra poc ,mrp_compra c,mrp_producto p, mrp_orden_compra oc,mrp_sucursal s, mrp_proveedor pr
where dc.idProductoOrdenCompra=poc.idPrOr and c.id=dc.idCompra and p.idProducto=poc.idProducto and oc.idOrd=poc.idOrden and s.idSuc=oc.idSuc and pr.idPrv=oc.idProveedor

UNION

select
v.idVenta folio,
v.fecha as fecha,
p.nombre as producto,
p.idProducto as idProducto,
vp.cantidad as cantidad,
(vp.subtotal+vp.impuestosproductoventa) as monto,
s.nombre sucursal,
CASE WHEN c.nombre IS NOT NULL
       THEN c.nombre
       ELSE 'General Public'
END AS proveedor_cliente,
'Venta' as movimiento,
'' as origen,
'' as destino
from venta_producto vp inner join venta v  on v.idVenta=vp.idVenta inner join  mrp_producto p on  p.idProducto=vp.idProducto inner join
mrp_sucursal s on s.idSuc=v.idSucursal  left join comun_cliente c on c.id=v.idCliente
) as super where ".$filtro." order by fecha desc LIMIT 0, ".$registros."
");

$i=0;
$total=0;
	$tabla='<table align="center" width="95%" border="0" class="busqueda">
		<tr class="busqueda_fila2">
			<th>Folio</th>
			<th>Fecha</th>
			<th>Producto</th>
			<th>Movimiento</th>
			<th>Proveedor/Cliente</th>
			<th>Cantidad</th>
			<th>Monto</th>
			<th>Sucursal</th>
			<th>Almac&eacute;n origen</th>
			<th>Almac&eacute;n destino</th>
		</tr>';
if(mysql_num_rows($q)>0)
{
		while($r=mysql_fetch_object($q))
		{
			if(is_numeric($r->monto)){ $monto="$".number_format($r->monto,2,".",","); }	 else{$monto="";}

			$tabla.='<tr class="busqueda_fila2">
			<td align="center">'.$r->folio.'</td>
			<td>'.formato_fecha($r->fecha).'</td>
			<td>'.$r->producto.'</td>
			<td align="center">'.$r->movimiento.'</td>
			<td>'.$r->proveedor_cliente.'</td>
			<td align="center">'.$r->cantidad.'</td>
			<td align="center">'.$monto.'</td>
			<td align="center">'.$r->sucursal.'</td>
			<td align="center">'.$r->origen.'</td>
			<td align="center">'.$r->destino.'</td>
		</tr>';
		$i++;
		if($movimiento!="")
		{
			 $total=$total+$r->cantidad;
		}
		else
		{
			switch($r->movimiento)
			{
				case "Venta":  $total=$total-$r->cantidad; break;
				case "Compra": $total=$total+$r->cantidad; break;
			}
		}

		}
/*
		if($filtro!="1")
		{
		$tabla.='<tr class="busqueda_fila2">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td align="center"><strong>'.$total.'</strong></td>
			<td></td>
			<td></td><td></td><td></td>
		</tr>';
		}
 */
}
if($i<=19)
{
	for($e=$i;$e<=19;$e++)
	{
		$tabla.='<tr class="busqueda_fila2"> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td> 	</tr>';
	}
}


		return $tabla;
}

//**************************************************************************************************/


function Iniciarcaja($sucursal,$monto)
{
	date_default_timezone_set("Mexico/General");
	$fechaactual=date("Y-m-d H:i:s");
	$_SESSION['sucursal']=$sucursal;
	mysql_query("Insert into inicio_caja(id,fecha,monto,idUsuario,idCortecaja,idSucursal) values('','".$fechaactual."','".$monto."',".$_SESSION['accelog_idempleado'].",NULL,".$sucursal.")");

	$qsuc=mysql_query("select  s.idSuc, s.nombre sucursal,a.idAlmacen ,a.nombre almacen from mrp_sucursal s, almacen a where s.idAlmacen=a.idAlmacen and s.idSuc=".$sucursal);
	$objsuc=mysql_fetch_object($qsuc);

	 return '<input type="hidden" id="caja-sucursal" value="'.$objsuc->idSuc.'"><input type="hidden" id="caja-almacen" value="'.$objsuc->idAlmacen.'">Sucursal:'.$objsuc->sucursal;
}

 //**************************************************************************************************/

function verificainicioCaja()
{

		$q=mysql_query("select au.idSuc,mp.nombre from administracion_usuarios au,mrp_sucursal mp where mp.idSuc=au.idSuc and au.idempleado=".$_SESSION['accelog_idempleado']);
			if(mysql_num_rows($q)>0)
			{
				while($r=mysql_fetch_object($q))
				{
					$sucursal_operando=$r->nombre;
					$sucursal_id=$r->idSuc;
				}
			}

		$q=mysql_query("Select idCortecaja from inicio_caja where idSucursal=".$sucursal_id." order by id desc limit 1");
		if(mysql_num_rows($q)>0)
		{
			$row=mysql_fetch_object($q);
			if(is_numeric($row->idCortecaja))
			{
				return 1;
			}else{ return 0;}
		}
		else
		{
			return 1;
		}
}
/**************************************************************************************************/

function checarExistencia($producto,$cantidad,$almacen)
{
		 //si es simple//
		if(simple()){
	  //end si es simple//

			 //*checo si el producto es compuesto por materiales*/
			$q0=mysql_query("select idMaterial,cantidad from mrp_producto_material where idProducto=".$producto);
			if(mysql_num_rows($q0)>0)
			{
				while($r=mysql_fetch_object($q0))
				{
						$q1=mysql_query("select s.cantidad from mrp_stock s where idAlmacen=".$almacen." and idProducto=".$r->idMaterial);
						$row1 = mysql_fetch_object($q1);
						if((float)($r->cantidad*$cantidad)>(float)$row1->cantidad)
						{

							return "No hay existencia suficiente de este producto";
						}
				}
				return 0;

			}
			else //si no es producto que contiene materiales
			{
					$q=mysql_query("select s.cantidad from mrp_stock s where idAlmacen=".$almacen." and idProducto=".$producto);
					$row = mysql_fetch_object($q);
					if((float)$cantidad>(float)$row->cantidad)
					{
						return "No hay existencia suficiente de este producto";
					}
					else{ return 0;}
			}


			//si es simple//
				 }else { return 0; }
	  //end si es simple//

}
/**************************************************************************************************/
function unidadMinima($idProducto)
	{
		$q=mysql_query('SELECT idUni FROM mrp_producto_proveedor WHERE idProducto='.$idProducto);
		if (mysql_num_rows($q) > 0)
		{
				$row = mysql_fetch_object($q);
				//if($row->idUni!=27){
					$compuesto=conversionMinima($row->idUni);
				//}
				//$compuesto=$this->conversionMinima(27);

				return $compuesto;
		}
		else { return "Unidad"; }
	}

/**************************************************************************************************/
	function conversionMinima($unidad)
	{

			$q=mysql_query('SELECT conversion,unidad,compuesto from mrp_unidades where idUni='.$unidad);
			$row = mysql_fetch_object($q);
			$compuesto=$row->compuesto;
			if($row->unidad!=$unidad)
			{
				$compuesto=conversionMinima($row->unidad);
				//var_dump($compuesto);
			}
			return $compuesto;
	}
/**************************************************************************************************/
function existenciaproducto($id,$almacen)
{


	$q=mysql_query("select s.cantidad from mrp_stock s where idAlmacen=".$almacen." and idProducto=".$id);
	$row = mysql_fetch_object($q);
	if (mysql_num_rows($q) > 0)
		{$cantidad= $row->cantidad;}else{$cantidad=0;}
	return $cantidad ." ".unidadMinima($id)."(es)";


}
/**************************************************************************************************/
function iva()
{
	$q=mysql_query("Select iva from parametros_pv where id=1");
	$row=mysql_fetch_array($q);
	return $row["iva"]/100;
}
/**************************************************************************************************/
function eliminaproductocaja($idArticulo)
{
	unset($_SESSION['caja'][$idArticulo]);
	return imprimecaja();
}

/**************************************************************************************************/
function guardarVenta($pagoautomatico,$impuestos,$idFact,$sucursal,$almacen,$doc,$monto,$cambio,$cliente,$empleado)
{

	date_default_timezone_set("Mexico/General");
	mysql_query("BEGIN");
	date_default_timezone_set("Mexico/General");
	$fechaactual=date("Y-m-d H:i:s");

	$monto=str_replace(",","",$monto);
	$cambio=str_replace(",","",$cambio);
	$impuestos=str_replace(",","",$impuestos);

	if(!is_numeric($cliente))
	{

		$result=mysql_query("INSERT INTO venta (idVenta,idCliente,monto,estatus,idEmpleado,rfc,documento,fecha,cambio,montoimpuestos,idSucursal) VALUES ('',NULL,'".$monto."',1,".$empleado.",'',".$doc.",'".$fechaactual."','".$cambio."','".$impuestos."',".$sucursal.");");

    }
	else
	{

		$result=mysql_query("INSERT INTO venta (idVenta,idCliente,monto,estatus,idEmpleado,rfc,documento,fecha,cambio,montoimpuestos,idSucursal) VALUES ('',".$cliente.",'".$monto."',1,".$empleado.",'',".$doc.",'".$fechaactual."','".$cambio."','".$impuestos."',".$sucursal.");");
	}


	$idVenta=mysql_insert_id();

	foreach($_SESSION['caja'] as $key=>$producto)
	{

		if($producto->tipodescuento=="%")
			{
				$descuentogeneral=(($producto->precioventa*$producto->descuento)/100)*$producto->cantidad;
			}
			if($producto->tipodescuento=="$")
			{
				$descuentogeneral=$producto->descuento;
			}




		$subtotal=($producto->precioventa*$producto->cantidad);
		//	$ivaproducto=(($producto->precioventa*$producto->cantidad)-$descuentogeneral)*0.16;
		$total=($producto->precioventa*$producto->cantidad)-$descuentogeneral+($producto->impuesto*$producto->cantidad);

		$queryp="INSERT INTO venta_producto (idventa_producto,idProducto,cantidad,preciounitario,tipodescuento,descuento,subtotal,idVenta,impuestosproductoventa,montodescuento,total) VALUES ";
		$queryp.="('',".$key.",".$producto->cantidad.",'".$producto->precioventa."','".$producto->tipodescuento."','".$producto->descuento."',".($subtotal).",".$idVenta.",'".($producto->impuesto*$producto->cantidad)."','".$descuentogeneral."','".($total)."');";
		$result=mysql_query($queryp);
		$idVentaProducto=mysql_insert_id();


		$qi=mysql_query("select i.id idImpuesto,i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$key);
		if(mysql_num_rows($qi)>0)
		{
			while($ri = mysql_fetch_object($qi))
			{
				$queryi="insert into venta_producto_impuesto values('',".$idVentaProducto.",".$ri->idImpuesto.",'".$ri->valor."');";
				mysql_query($queryi);
			}
		}


		/*compruebo si el producto tiene materiales*/
		$q0=mysql_query("select idMaterial,cantidad from mrp_producto_material where idProducto=".$key);
			if(mysql_num_rows($q0)>0)
			{
				while($r=mysql_fetch_object($q0))
				{
						$q2=mysql_query("select s.cantidad from mrp_stock s where  s.idProducto=".$r->idMaterial." and  s.idAlmacen=".$almacen);
						$row2 = mysql_fetch_object($q2);
						$updatestock="Update mrp_stock set cantidad=".($row2->cantidad-($r->cantidad*$producto->cantidad))." where  idAlmacen=".$almacen." and idProducto=".$r->idMaterial;
						mysql_query($updatestock);
				}
			}
			else{//si el producto no tiene materiales

		$q=mysql_query("select s.cantidad from mrp_stock s where  s.idProducto=".$key." and  s.idAlmacen=".$almacen);
		if(mysql_num_rows($q)>0)
		{
			$row = mysql_fetch_object($q);
			$updatestock="Update mrp_stock set cantidad=".($row->cantidad-$producto->cantidad)." where  idAlmacen=".$almacen." and idProducto=".$key;
			mysql_query($updatestock);
		}
		}
		//var_dump($queryp);
	}

	foreach($_SESSION['pagos-caja'] as $idFormapago=>$formapago)
	{
		list($fpago,$montopago,$referenciapago)=explode("_",$formapago);
		if($montopago>0)
		{
			$result=mysql_query("INSERT INTO venta_pagos(idventa_pagos,idVenta,idFormapago,monto,referencia) VALUES('',".$idVenta.",".$idFormapago.",'".$montopago."','".$referenciapago."')");
			if($idFormapago==6)//pago a credito
			{
//var_dump("INSERT INTO cxc (fechacargo, fechavencimiento, idVenta, monto, saldoabonado, saldoactual, estatus, idCliente, concepto) VALUES ('".$fechaactual."','".$fechaactual."',".$idVenta.",'".$montopago."','0','0','0',".$cliente.",'Venta a credito');");


$cc=mysql_query("INSERT INTO cxc (fechacargo, fechavencimiento, idVenta, monto, saldoabonado, saldoactual, estatus, idCliente, concepto) VALUES ('".$fechaactual."','".$fechaactual."',".$idVenta.",'".$montopago."','0','".$montopago."','0',".$cliente.",'Venta a credito');");
			}

			if($idFormapago==3)//tarjeta de regalo
			{
//var_dump("INSERT INTO cxc (fechacargo, fechavencimiento, idVenta, monto, saldoabonado, saldoactual, estatus, idCliente, concepto) VALUES ('".$fechaactual."','".$fechaactual."',".$idVenta.",'".$montopago."','0','0','0',".$cliente.",'Venta a credito');");


$cc=mysql_query("select numero,valor,usada,montousado from tarjeta_regalo where numero='".$referenciapago."'");
$cco=mysql_fetch_object($cc);
$extensionconsulta="";
if(((float)$montopago+(float)$cco->montousado)>=(float)$cco->valor)
{
	$extensionconsulta=",usada=1";
}

mysql_query("Update tarjeta_regalo set montousado=".((float)$montopago+(float)$cco->montousado).$extensionconsulta." where numero='".$referenciapago."'");

		//var_dump("Update tarjeta_regalo set montousado=".((float)$montopago+(float)$cco->montousado).$extensionconsulta." where numero='".$referenciapago."'")
			}
		}
	}

	//simple
	//pago automatico (efectivo)
	if($pagoautomatico==1)
	{

	$result=mysql_query("INSERT INTO venta_pagos(idventa_pagos,idVenta,idFormapago,monto,referencia) VALUES('',".$idVenta.",1,'".$monto."','')");

	}
	//end pago automatico
	//end simple
	if(is_numeric($idFact))
	{
		Facturar($idFact,($monto-$impuestos),$impuestos);
	}
	else
	{
		if(is_numeric($cliente))
		{
			mysql_query("insert into pvt_pendienteFactura values('',".$idVenta.",'".$fechaactual."',".$cliente.",'".$monto."',0);");
		}
		else
		{
			mysql_query("insert into pvt_pendienteFactura values('',".$idVenta.",'".$fechaactual."',NULL,'".$monto."',0);");
		}
	}
	echo $idVenta."xd-dx";

	if(!$result){$error=1;}
	if($error){mysql_query("ROLLBACK");return "Error en la transaccion";}else{mysql_query("COMMIT");


	unset($_SESSION['pagos-caja']);
	unset($_SESSION['caja']);

	//return "Has registrado la venta con exito.";
	}
}
/**************************************************************************************************/
function eliminarpago($id,$total)
{
	unset($_SESSION['pagos-caja'][$id]);
	return json_encode(pagoscaja($total,0,0,true));
}
/***************************************************************************************************/
function checatarjetaregalo($numero,$monto)
{
	$qr=mysql_query("select valor,usada,montousado from tarjeta_regalo where numero='".$numero."'");
	if(mysql_num_rows($qr)>0)
	{
		$rowr=mysql_fetch_object($qr);
		$disponible=(float)$rowr->valor-(float)$rowr->montousado;
		if((float)$disponible<(float)$monto)
		{
				return "Saldo disponible en tarjeta de regalo:$".number_format($disponible,2,".",",");
		}


		if($rowr->usada==1){return "Se ha agotado el saldo de la tarjeta de regalo.";}
		else
		{
			//$qr2=mysql_query("Update tarjeta_regalo set usada=1 where numero=".$numero);
			return $monto;
		}
	}
	else
	{
		return "No esta registrada este numero de tarjeta";
	}
}
/******/
function calculapago($total)
{
		$totalpagos=0;
		foreach($_SESSION['pagos-caja'] as $key=>$pagos)
		{
			list($fp,$m,$ref)=explode("_",$pagos);
			if( $m!=0 && $key!=0 )
			{
				$totalpagos+=$m;
			}
		}
		if($total-$totalpagos>0){ return $total-$totalpagos;} else{ return "";}
}
/***************************************************************************************************/
function pagoscaja($total,$referencia,$formapago="",$monto=0,$idformapago=0,$ajax=false)
{
	$pagosC='<table width="100%" border="0" ><tr><th>Forma de pago</th><th>Monto</th><th></th></tr>';
	$i=0;
	$_SESSION['pagos-caja'][$idformapago]=$formapago."_".$monto."_".$referencia;
	$totalpagos=0;
	foreach($_SESSION['pagos-caja'] as $key=>$pagos)
	{
		list($fp,$m,$ref)=explode("_",$pagos);
		if( $m!=0 && $key!=0 )
		{

			if(!$ajax)
			{
							if($key==4 || $key==5 )
							{
								unset($_SESSION['pagos-caja'][$key]);
							}
							else
							{
								$pagosC.='<tr><td align="center">'.$fp.'</td><td align="right">$'.number_format($m,2,".",",").'</td><td><img src="img/eliminar.gif" onclick="eliminarpago('.$key.');"; ></td></tr>';
								$i++;
								$totalpagos+=$m;
							}
			}
			else
			{
				$pagosC.='<tr><td align="center">'.$fp.'</td><td align="right">$'.number_format($m,2,".",",").'</td><td><img src="img/eliminar.gif" onclick="eliminarpago('.$key.');"; ></td></tr>';
				$i++;
				$totalpagos+=$m;
			}


		}

	}




	if($i<6)
	{
		for($ii=$i;$ii<6;$ii++)
		{
			$pagosC.='<tr><td>&nbsp;</td></tr>';
		}
	}
		$pagosC.='<tr><td align="center"><strong>Total</strong></td><td align="right"><strong>$'.number_format($totalpagos,2,".",",").'</strong></td><td></td></tr>';



	$pagosC.='<table>';

	$resultado=array();
		$resultado[0]=$pagosC;
		$resultado[1]="$".number_format($totalpagos,2,".",",");
		if($total-$totalpagos>0)
		{
			$resultado[2]="$".number_format($total-$totalpagos,2,".",",");
		}
		else { $resultado[2]="$0.00"; }

		if($totalpagos>$total)
		{
			$resultado[3]="$".number_format($totalpagos-$total,2,".",",");
		}
		else{$resultado[3]="$0.00";  }

	if(!$ajax)
	{
		return $resultado;
	}
	else{

		return json_encode($resultado);
	}
}
/***************************************************************************************************/
function CargaProducto($idProducto,$almacen)
{
$query = mysql_query("select * from mrp_producto where  idProducto=".$idProducto);
$row = mysql_fetch_object($query);


$detalle='<table width="100%" style="font-size:12px;" border="0">
<tr>
	<td><input type="hidden" id="hidden-idproducto"  value="'.$row->idProducto.'"><h1>'.$row->nombre.'</h1></td> <td rowspan="3" width="250"><img src="../mrp/'.$row->imagen.'" width="250" height="300" /></td>
</tr>
<tr>
	<td style="text-align: justify;">'.$row->deslarga.'
	</td>
</tr>
<tr>
	<td><h2>Precio de venta:$'.number_format($row->precioventa,2,".",",").'</h2></td>
</tr>';

 //si es simple//
		if(simple()){
	  //end si es simple//
$detalle.='<tr><td align="center"><b style="color:green;">Existencia:'.existenciaproducto($idProducto,$almacen).'</b></td></tr>';
 //si es simple//
		}
	  //end si es simple//

$detalle.='</table>';
return $detalle;
}
/***************************************************************************************************/
function cantidadProductocaja($id)
{
	$articulo=$_SESSION['caja'][$id];
	return $articulo->cantidad;
}
/***************************************************************************************************/
function cambiarcantidad($idArticulo,$cantidad,$descuento,$tipodescuento)
{
	$articulo=$_SESSION['caja'][$idArticulo];
	$articulo->cantidad=$cantidad;
	$articulo->descuento=$descuento;
	$articulo->tipodescuento=$tipodescuento;
	$_SESSION['caja'][$idArticulo]=$articulo;
	return imprimecaja();
}
/***************************************************************************************************/
function datosArticulo($idArticulo)
{
$query = mysql_query("select * from mrp_producto where idProducto=".$idArticulo);
$row = mysql_fetch_object($query);
return $row;
}
/***************************************************************************************************/
function imprimecaja($idArticulo=0,$almacen=0)
{
	$resultado=array();

	//si llega el nombre del producto
	if(!is_numeric($idArticulo))
	{
		$queryn = mysql_query("select idProducto id,nombre,codigo,precioventa  from mrp_producto where strcmp(nombre,'".$idArticulo."')=0  OR strcmp(codigo,'".$idArticulo."')=0  ");
		if(mysql_num_rows($queryn)==0)
			{
				$resultado[0]="No existe un articulo con esa descripción o codigo";
				return json_encode($resultado);
			}
			else
			{
				$rown = mysql_fetch_object($queryn);
				$idArticulo=$rown->id;
			}
	}
	else
	{
		if($idArticulo!=0)
		{
			$queryn = mysql_query("select idProducto id,nombre,codigo,precioventa  from mrp_producto where strcmp(codigo,'".$idArticulo."')=0  ");
		if(mysql_num_rows($queryn)>0)
			{
				$rown = mysql_fetch_object($queryn);
				$idArticulo=$rown->id;
			}
			else{
						$queryn = mysql_query("select idProducto id,nombre,codigo,precioventa  from mrp_producto where idProducto=".$idArticulo );
						if(mysql_num_rows($queryn)==0)
						{
							$resultado[0]="No existe un articulo con el ID o codigo ingresado";
							return json_encode($resultado);
						}
			}
		}
	}

	//en si llega el nombre del producto

	$impuestos_venta=array();
	$caja='<div id="caja"><table id="table-caja" border="0" width="100%">
    		<tr>
    			<th></th>
    			<th>Código</th>
    			<th width="40%">Descripción</th>
    			<th>Cantidad</th>
    			<th>Precio U.</th>
    			<th>Impuestos</th>
    			<th>Descuento</th>
    			<th>Subtotal</th>
    		</tr>';
$i=0;
$total=0;
/**/
if($idArticulo!=0)
{
	 //si es simple//
		if(simple()){
	  //end si es simple//


			//checar existencias
	if($almacen!=0)
	{
	$q0=mysql_query("select idMaterial,cantidad from mrp_producto_material where idProducto=".$idArticulo);
			if(mysql_num_rows($q0)>0)
			{
				while($r=mysql_fetch_object($q0))
				{
						$q1=mysql_query("select s.cantidad from mrp_stock s where idAlmacen=".$almacen." and idProducto=".$r->idMaterial);
						$row1 = mysql_fetch_object($q1);
						if((float)$r->cantidad>(float)$row1->cantidad)
						{
							$resultado[0]="No hay existencia suficiente de este producto";
							return json_encode($resultado);
						}
				}

			}
			else //si no es producto que contiene materiales
			{
					$q=mysql_query("select s.cantidad from mrp_stock s where idAlmacen=".$almacen." and idProducto=".$idArticulo);
					$row = mysql_fetch_object($q);
					if(1>(float)$row->cantidad)
					{
						$resultado[0]="No hay existencia suficiente de este producto";
						return json_encode($resultado);
					}

			}
	}//almacen !=0
	//end checar existencias

	 //si es simple//
	 }
	  //end si es simple//

if(!isset($_SESSION['caja']))//si no existen la caja
{
	$query = mysql_query("select idProducto id,nombre,codigo,precioventa  from mrp_producto where idProducto=".$idArticulo);
	$row = mysql_fetch_object($query);
	$row->cantidad=1;
	$row->descuento=0;
	$row->tipodescuento="$";
	$producto_impuesto=0;
	$qi=mysql_query("select i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$idArticulo);
	if(mysql_num_rows($qi)>0)
	{
		while($ri = mysql_fetch_object($qi))
		{
			$producto_impuesto+=($row->precioventa*$ri->valor)/100;
		}
	}
	$row->impuesto=$producto_impuesto;
	$_SESSION['caja'][$idArticulo]=$row;
}
else
{
	if(array_key_exists($idArticulo,$_SESSION['caja']))//si ya existe el articulo agregado en la caja
	{
		$articulo=$_SESSION['caja'][$idArticulo];
		//$articulo->cantidad=$articulo->cantidad+1;
		$articulo->cantidad=$articulo->cantidad;

		$_SESSION['caja'][$idArticulo]=$articulo;
	}
	else//si es un articulo nuevo agregado a la caja
	{
		$query = mysql_query("select idProducto id,nombre,codigo,precioventa  from mrp_producto where idProducto=".$idArticulo);
		$row = mysql_fetch_object($query);
		$row->cantidad=1;
		$row->descuento=0;
		$row->tipodescuento="$";
		$producto_impuesto=0;
		$qi=mysql_query("select i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$idArticulo);
		if(mysql_num_rows($qi)>0)
		{
			while($ri = mysql_fetch_object($qi))
			{
				$producto_impuesto+=($row->precioventa*$ri->valor)/100;
			}
		}
		$row->impuesto=$producto_impuesto;
		$_SESSION['caja'][$idArticulo]=$row;
	}
}

}//if $idArticulo=0
	if(isset($_SESSION['caja']))
	{



	foreach($_SESSION['caja'] as $id=>$producto)
	{
			$descuento="";
			$descuentogeneral=0;
			if($producto->tipodescuento=="%")
			{
				$descuentogeneral=(($producto->precioventa*$producto->descuento)/100)*$producto->cantidad;
				$descuento='$'.number_format($descuentogeneral,2,".",",");
			}
			if($producto->tipodescuento=="$")
			{
				$descuentogeneral=$producto->descuento;
				$descuento='$'.number_format($producto->descuento,2,".",",");
			}

			if(	$descuento=="$")
			{
				$descuento="";
			}

			$subtotal=($producto->precioventa*$producto->cantidad)-$descuentogeneral;
			$total+=$subtotal;

			$materiales="";
			$propiedad="";



			/*IMPUESTOS*/
	$qi=mysql_query("select i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$id);
		if(mysql_num_rows($qi)>0)
		{
			while($ri = mysql_fetch_object($qi))
			{
				if(array_key_exists($ri->impuesto,$impuestos_venta))
				{
					$impuestos_venta[$ri->impuesto]=(($subtotal*$ri->valor)/100+$impuestos_venta[$ri->impuesto]);
				}
				else
				{
					$impuestos_venta[$ri->impuesto]=(($subtotal*$ri->valor)/100);
				}
			}
		}


			/* END IMPUESTOS*/

			$querycompuesto=mysql_query("select p.esreceta, p.nombre as producto,pm.cantidad,pm.idUnidad,pu.compuesto from mrp_producto_material pm inner join mrp_unidades pu on  pm.idUnidad=pu.idUni
inner join mrp_producto p on p.idProducto=pm.idMaterial
where pm.idProducto=".$id);
			if(mysql_num_rows($querycompuesto)>0)
			{
				$propiedad='style="font-size:18px;font-weight:bold;"';
				while($rowcompuesto=mysql_fetch_array($querycompuesto))
				{
					if($rowcompuesto["esreceta"]==0)
					{
						$materiales.=$rowcompuesto["cantidad"]." ".$rowcompuesto["compuesto"]." ".$rowcompuesto["producto"]."<br>";
					}
				}
			}


			if($i%2==0){ $caja.='<tr class="par" >'; } else { $caja.='<tr  class="impar">';}
				$caja.='<td align="center"><img src="img/bor.png" onclick="eliminaproductocaja('.$id.');" style="cursor:pointer"></td>
							<td align="center" onclick="editArticulo('.$id.');">'.$producto->codigo.'</td>
			    			<td align="center" onclick="editArticulo('.$id.');"><span '.$propiedad.'>'.$producto->nombre.'</span><br>'.$materiales.'</td>
			    			<td align="center" onclick="editArticulo('.$id.');">'.$producto->cantidad.'</td>
			    			<td align="center" onclick="editArticulo('.$id.');">$'.number_format($producto->precioventa,2,".",",").'</td>
			    			<td align="center" onclick="editArticulo('.$id.');">$'.number_format($producto->impuesto*$producto->cantidad,2,".",",").'</td>
			    			<td align="center" onclick="editArticulo('.$id.');">'.($descuento).'</td>
    						<td align="center" onclick="editArticulo('.$id.');">$'.number_format((($producto->precioventa*$producto->cantidad))-$descuentogeneral,2,".",",").'</td>
    				</tr>';
		$i++;
	}//end foreach
/**/	}//if isset caja

   			if($i<8)
				{
					for($ii=$i;$ii<=8;$ii++)
					{
							if($ii%2==0){ $caja.='<tr class="par">'; } else { $caja.='<tr class="impar">';}
				$caja.='<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
			    			<td align="center"></td>
    						<td align="center"></td>
    				</tr>';
					}
				}
    $caja.='</table>';



	$q=mysql_query("select au.idSuc,mp.nombre from administracion_usuarios au,mrp_sucursal mp where mp.idSuc=au.idSuc and au.idempleado=".$_SESSION['accelog_idempleado']);
	if(mysql_num_rows($q)>0)
	{
		while($r=mysql_fetch_object($q))
		{
			$sucursal_operando=$r->nombre;
			$idSuc=$r->idSuc;
		}
	}
	/*
	if(in_array($_SESSION,"sucursal"))
	{
		$idSuc=$_SESSION["sucursal"];
	}*/
	else
	{
		$qs=mysql_query("Select idSucursal from inicio_caja order by id desc limit 1");
		$rows=mysql_fetch_object($qs);
		$idSuc=$rows->idSucursal;
	}


	$qsuc=mysql_query("select  s.idSuc, s.nombre sucursal,a.idAlmacen ,a.nombre almacen from mrp_sucursal s, almacen a where s.idAlmacen=a.idAlmacen and s.idSuc=".$idSuc);
	$objsuc=mysql_fetch_object($qsuc);


    $caja.='</div>
    	<div id="footer-caja">
	    	<table border="0" width="100%">';


			$caja.='<tr>
			<td></td><td  align="left"></td><td  align="right">
			Subtotal:$'.number_format($total,2,".",",");$caja.='</td></tr>';
			$totalimpuestos=0;
			foreach($impuestos_venta as $impuesto=>$valorimpuesto)
			{
				$totalimpuestos+=$valorimpuesto;
				$caja.='<tr><td></td><td  align="left"></td><td  align="right">'.$impuesto.':$'.number_format($valorimpuesto,2,".",",");$caja.='</td></tr>';
			}


			$caja.='<tr>
			<td>
			<span id="dalmacen">
				<input type="hidden" id="caja-sucursal" value="'.$objsuc->idSuc.'">
				<input type="hidden" id="caja-almacen" value="'.$objsuc->idAlmacen.'">';
				 //si es simple//
		if(simple()){
	  //end si es simple//

				$caja.='Sucursal:'.$objsuc->sucursal;
					 //si es simple//
		}
	  //end si es simple//

			$caja.='</span>
			</td>

				<td  align="left">
				<input type="hidden" id="idvendedor" value="'.$_SESSION['accelog_idempleado'].'" >
				<div id="vendedor">Vendedor:'.$_SESSION['accelog_login'].'</div></td>
				<td id="total" align="right">
					Total:$'.number_format($total+$totalimpuestos,2,".",",");
				 if($i>0)
				 {
				 		 //si es simple//
		if(simple()){
	  //end si es simple//
				 	$caja.='<input type="button" value="Pagar" id="boton-pagar" onclick="pagar('.($total+$totalimpuestos).','.$totalimpuestos.',0);">';
						 //si es simple//
		}	  else{
		$caja.='<input type="button" value="Pagar" id="boton-pagar" onclick="pagar('.($total+$totalimpuestos).','.$totalimpuestos.',1);">';

		}
	  //end si es simple//
				 }
				$caja.='</td>
			</tr>


			</table>
		</div>';

	$resultado[0]=1;
	$resultado[1]=$caja;

	return json_encode($resultado);

	//return $caja;
}

 /***************************************************************************************************/
function productosexistencias($id=0)
{
	if($id!=0){$filtro=" where idLinea=".$id;}else{$filtro="";}

	$cbo='<select id="producto" name="producto" onchange="cargaexistencias(this.value);">';
	  $cbo.='<option value="">-Seleccione-</option>';
	$query = mysql_query("select idProducto id,nombre  from mrp_producto ".$filtro." where nombre!=''  ORDER BY nombre asc");
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}


 //***************************************************************************************************/
function productos($id=0)
{
	if($id!=0){$filtro=" where idLinea=".$id;}else{$filtro="";}

	$cbo='<select id="producto" name="producto" onchange="cargaProducto(this.value);">';
	  $cbo.='<option value="">-Seleccione-</option>';
	$query = mysql_query("select idProducto id,nombre  from mrp_producto ".$filtro);
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}

/***************************************************************************************************/
function departamentos()
{
	$cbo='<select id="departamento" name="departamento" onchange="cargaFamilias(this.value);">';
	$query = mysql_query("select idDep id,nombre  from mrp_departamento");
    $cbo.='<option value="">-Seleccione-</option>';
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}

/***************************************************************************************************/
function departamentos2()
{
	$cbo='<select id="departamento" name="departamento" onchange="cargaFamilias2(this.value);">';
	$query = mysql_query("select idDep id,nombre  from mrp_departamento");
    $cbo.='<option value="">-Seleccione-</option>';
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}
/***************************************************************************************************/
function familias($id=0)
{
	if($id!=0){$filtro=" where idDep=".$id;}else{$filtro="";}

	$cbo='<select id="familia" name="familia" onchange="cargaLineas(this.value);">';

	  $cbo.='<option value="">-Seleccione-</option>';
	$query = mysql_query("select idFam id,nombre  from mrp_familia ".$filtro);
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.utf8_decode($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}

function familias2($id=0)
{
	if($id!=0){$filtro=" where idDep=".$id;}else{$filtro="";}

	$cbo='<select id="familia" name="familia" onchange="cargaLineas2(this.value);">';

	  $cbo.='<option value="">-Seleccione-</option>';
	$query = mysql_query("select idFam id,nombre  from mrp_familia ".$filtro);
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.utf8_decode($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}
/***************************************************************************************************/
function lineas($id=0)
{
	if($id!=0){$filtro=" where idFam=".$id;}else{$filtro="";}

	$cbo='<select id="linea" name="linea" onchange="cargaProductos(this.value);">';
	  $cbo.='<option value="">-Seleccione-</option>';
	$query = mysql_query("select idLin id,nombre  from mrp_linea ".$filtro);
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}


function lineas2($id=0)
{
	if($id!=0){$filtro=" where idFam=".$id;}else{$filtro="";}

	$cbo='<select id="linea" name="linea" onchange="loadproductos(this.value);">';
	  $cbo.='<option value="">-Seleccione-</option>';
	$query = mysql_query("select idLin id,nombre  from mrp_linea ".$filtro);
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}
/***************************************************************************************************/
function formasPago()
{
	$cbo='<select id="formapago" name="formapago" onchange="seleccionaformadepago(this.value);">';
	$query = mysql_query("select idFormapago id,nombre  from forma_pago");

    	 //si es simple//
		if(simple()){
	  //end si es simple//

    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
	}

	 //si es simple//
		}else {  $cbo.='<option value="1">Efectivo</option>';  }
	  //end si es simple//

	$cbo.='</select>';
	return $cbo;
}
/***************************************************************************************************/
function fechaActual()
{
	date_default_timezone_set('America/Mexico_City');

	$dia= date('l');
	$numero= date('d');
	$mes= date('F');
	$anio= date('Y');

	switch($dia)
	{
		case "Monday":  $dia="Lunes"; break;
		case "Tuesday": $dia="Martes"; break;
		case "Wednesday": $dia="Miercoles"; break;
		case "Thursday":$dia="Jueves"; break;
		case "Friday":  $dia="Viernes"; break;
		case "Saturday":$dia="Sabado"; break;
		case "Sunday":  $dia="Domingo"; break;
	}

	switch($mes)
	{
		case "January":  $mes="Enero"; break;
		case "Febrary":  $mes="Febrero"; break;
		case "March":    $mes="Marzo"; break;
		case "April":    $mes="Abril"; break;
		case "May":      $mes="Mayo"; break;
		case "June":	 $mes="Junio"; break;
		case "July":     $mes="Julio"; break;
		case "August":   $mes="Agosto"; break;
		case "September":$mes="Septiembre"; break;
		case "October":  $mes="Octubre"; break;
		case "November": $mes="Noviembre"; break;
		case "December": $mes="Diciembre"; break;
	}

	return $dia." ".$numero."  de ".$mes." ".$anio;
}
/***************************************************************************************************/

function sucursales()
{
	$cbo='<select id="sucursal" name="sucursal" class="form-control">';
	$query = mysql_query("select idSuc id,nombre  from mrp_sucursal");
    $cbo.='<option value="">-Seleccione-</option>';
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}

/***************************************************************************************************/

function proveedores()
{
	$cbo='<select id="proveedor" name="proveedor" >';
	$query = mysql_query("select idPrv id,razon_social nombre  from mrp_proveedor");
    $cbo.='<option value="">-Seleccione-</option>';
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}

/***********************************************FACTURACION ****************************************************/
function datosFacturacion($id)
{
$q=mysql_query("Select
domicilio,
cp,
colonia,
num_ext,
pais,
correo,
razon_social,
rfc,
cf.id as idFac,
e.estado estado,
ciudad,
municipio,
regimen_fiscal
from comun_facturacion cf left join estados e on  e.idestado=cf.estado where  id=".$id);
$r=mysql_fetch_object($q);
return $r;

}
/*********************************************** ****************************************************/

function Facturar($idFact,$monto,$impuestos)
{
	 	$df=datosFacturacion($idFact);
		$q=mysql_query("SELECT serie,folio FROM pvt_serie_folio LIMIT 1");
		$data=mysql_fetch_array($q);
		$supertotal=0;

	 // Arreglos Basicos
		//===============================================================
		$parametros = array();
		//$arregloFactura = array();
		//$arregloFactura['idFactura']=$data['idFact'];
		// Ruta
		//===============================================================
		$ruta_guardar='facturacion/';


	  	// Receptor
	  	//===============================================================
	  	$parametros['Receptor'] = array();
	  	$parametros['Receptor']['RFC']           = $df->rfc;
	  	$parametros['Receptor']['RazonSocial']   = utf8_decode($df->razon_social);
	  	$parametros['Receptor']['Pais']          = utf8_decode($df->pais);
	  	$parametros['Receptor']['Calle']         = utf8_decode($df->domicilio);
	  	$parametros['Receptor']['NumExt']        = $df->num_ext;
	  	$parametros['Receptor']['Colonia']       = utf8_decode($df->colonia);
	  	$parametros['Receptor']['Municipio']     = utf8_decode($df->municipio);
	  	$parametros['Receptor']['Ciudad']        = utf8_decode($df->ciudad);
	  	$parametros['Receptor']['CP']            = $df->cp;
	  	$parametros['Receptor']['Estado']        = utf8_decode($df->estado);
	  	$parametros['Receptor']['Email1']        = $df->correo;

		 // Conceptos
	  	//===============================================================
	  	$x=0;
		$textodescuento="";
	  	//foreach ($_SESSION['caja'] as $producto) {
		foreach($_SESSION['caja'] as $key=>$producto)
		{
			if($producto->tipodescuento=="%")
			{
				$descuentogeneral=(($producto->precioventa*$producto->descuento)/100)*$producto->cantidad;
				if($producto->descuento>0)
				{
					$textodescuento.=" - ".$producto->descuento." %";
				}
			}
			if($producto->tipodescuento=="$")
			{
				$descuentogeneral=$producto->descuento;
				if($producto->descuento>0)
				{
					$textodescuento.=" - $".$producto->descuento."";
				}
			}



		    $conceptosDatos[$x]['Cantidad'] = $producto->cantidad;
		    $conceptosDatos[$x]['Unidad'] = 'Unidad';
		    $conceptosDatos[$x]['Descripcion'] = utf8_decode($producto->nombre." ".$textodescuento);
		    $conceptosDatos[$x]['Precio'] = $producto->precioventa-(float)((float)$descuentogeneral/$producto->cantidad);

		    $importe=($producto->cantidad*$producto->precioventa);
		    $impfinal=$importe-$descuentogeneral;

			$supertotal+=$impfinal;
		    $conceptosDatos[$x]['Importe'] = $impfinal;
		  	$x++;


				/*IMPUESTOS*/
	$qi=mysql_query("select i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$key);
		if(mysql_num_rows($qi)>0)
		{
			while($ri = mysql_fetch_object($qi))
			{
				if(array_key_exists($ri->impuesto,$impuestos_venta))
				{
					$impuestos_venta[$ri->impuesto]=(($impfinal*$ri->valor)/100+$impuestos_venta[$ri->impuesto]);
				}
				else
				{
					$impuestos_venta[$ri->impuesto]=(($impfinal*$ri->valor)/100);


				}
			}
		}

			/* END IMPUESTOS*/

		 }


		$impuestosDatos=array();
		$calculototalimpuestos=0;
		foreach($impuestos_venta as $impuesto=>$valorimpuesto)
		{
			$calculototalimpuestos+=$valorimpuesto;
			$impuestosDatos[]=array('TipoImpuesto' =>strtoupper($impuesto),'Tasa' => 16,'Importe' => round($valorimpuesto,2));

		}



		// Basicos
	  	//===============================================================
	  	$parametros['DatosCFD'] = array();
	  	$parametros['DatosCFD']['FormadePago']       = "Pago en una sola exhibicion";
	  	$parametros['DatosCFD']['Moneda']            = "MXP";
	  	$parametros['DatosCFD']['Subtotal']          = $monto;
	  	$parametros['DatosCFD']['Total']             = ($supertotal+$calculototalimpuestos);
	  	$parametros['DatosCFD']['Serie']             = $data['serie'];
		$parametros['DatosCFD']['Folio']             = $data['folio'];
	  	$parametros['DatosCFD']['TipodeComprobante'] = "F"; //F o C
	  	$parametros['DatosCFD']['MensajePDF']        = "";
	  	$parametros['DatosCFD']['LugarDeExpedicion']        = "Mexico";

		/*
	  	//Impuestos
	  	//===============================================================
	  	$impuestosDatos = array(
	      	array(
	        	'TipoImpuesto' => "IVA",
	        	'Tasa' => 16,
	        	'Importe' => round(($monto*iva()),2)
	      	),
	      	array(
	        	'TipoImpuesto' => "ISR",
	        	'Tasa' => 10,
	        	'Importe' => round((($monto*1)*0.10),2)
	      	)
	  	);
		*/
		$Email=$df->correo;

		require_once('../../modulos/WS_facturacion.php');
}

/**/

/*****************************************************************************************************************************************/
function guardarFacturacion($idFacturacion,$datos,$monto,$cliente,$idventa)
{

	$d_fact=explode("xd-dx",$datos);
	$folio=str_replace("xxxPartirCadenaxxx","",$d_fact[0]);


	$fechaactual=date("Y-m-d H:i:s");

	if(strpos($datos,"Error")>0)
	{

		if(is_numeric($cliente))
		{
			mysql_query("insert into pvt_pendienteFactura values('',".$idventa.",'".$fechaactual."',".$cliente.",'".$monto."',0);");
		}
		else
		{
			mysql_query("insert into pvt_pendienteFactura values('',".$idventa.",'".$fechaactual."',NULL,'".$monto."',0);");
		}

		return "Ha surgido un error al generar la factura, verifiquelo con su proveedor de facturación, la factura puede ser generada posteriormente";
	}
	else{

	$q=mysql_query("INSERT INTO pvt_respuestaFacturacion
	(id,idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,borrado,tipoComp)
	VALUES ('',".$d_fact[6].",'".$idFacturacion."','".$folio."','".$d_fact[2]."','".$d_fact[5]."','".$d_fact[1]."','".$d_fact[4]."','".$d_fact[3]."','".$fechaactual."',0,'F');");
	$insertedId=mysql_insert_id();
	if(is_numeric($insertedId))
	{
			mysql_query("update pvt_contadorFacturas set total=total+1  where id=1");
	}

	return $insertedId;
	}
}
/*****************************************************************************************************************************************/


function vendedores()
{
	$cbo='<select id="vendedor" name="vendedor" class="form-control">';
	  $cbo.='<option value="">-Seleccione-</option>';
	$query = mysql_query("select idempleado id,nombre  from empleados  where administrador=0");
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}
/***************************************************************************************************/
function clientes()
{
	$cbo='<select id="cliente" name="cliente" class="form-control">';
	$cbo.='<option value="">-Seleccione-</option>';
	$cbo.='<option value="0">General Public</option>';
	$query = mysql_query("select  id,nombre  from comun_cliente");
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}

/**************************************************TICKET*************************************************/
function pagos($id)
{
	$q=mysql_query("select vp.monto, fp.nombre from venta_pagos vp inner join venta v on v.idVenta=vp.idVenta inner join forma_pago fp on vp.idFormapago=fp.idFormapago
where v.idVenta=".$id);
	return $q;
}

function productosventa($id)
{
	$q=mysql_query("select p.descorta ,p.idProducto,p.codigo,p.nombre,vp.preciounitario,vp.cantidad,vp.montodescuento,vp.total,vp.impuestosproductoventa from venta_producto vp inner join mrp_producto p on vp.idProducto=p.idProducto where vp.idVenta=".$id);
	return $q;
}

function datosorganizacion()
{
	$q=mysql_query("select * from organizaciones c left join estados e on e.idestado=c.idestado left join municipios m on m.idmunicipio=c.idmunicipio
 where idorganizacion=1");
	return mysql_fetch_object($q);
}

function datosventa($id)
{
	$q=mysql_query("select
v.idVenta as folio,
v.fecha as fecha,
v.cambio as cambio,
CASE WHEN c.nombre IS NOT NULL
       THEN c.nombre
       ELSE 'General Public'
END AS cliente,
e.nombre as empleado,
s.nombre as sucursal,
CASE WHEN v.estatus =1
       THEN 'Activa'
       ELSE 'Cancelada'
END AS estatus,
v.montoimpuestos as impuestos,
(v.monto) as monto
 from venta v left join comun_cliente c on c.id=v.idCliente inner join  empleados e on e.idempleado=v.idEmpleado inner join mrp_sucursal s on s.idSuc=v.idSucursal
 where v.idVenta=".$id);
	return mysql_fetch_object($q);
}


function iniciocaja()
{

	$q=mysql_query("select au.idSuc,mp.nombre from administracion_usuarios au,mrp_sucursal mp where mp.idSuc=au.idSuc and au.idempleado=".$_SESSION['accelog_idempleado']);
	if(mysql_num_rows($q)>0)
	{
		while($r=mysql_fetch_object($q))
		{
			$sucursal_operando=$r->nombre;
			$sucursal_id=$r->idSuc;
		}

		//var_dump("select  cc.saldofinalcaja from inicio_caja i inner join corte_caja cc on i.idCortecaja=cc.idCortecaja where i.idSucursal=".$sucursal_id." order by i.fecha desc limit 1");

		$q2=mysql_query("select  cc.saldofinalcaja from inicio_caja i inner join corte_caja cc on i.idCortecaja=cc.idCortecaja where i.idSucursal=".$sucursal_id." order by i.fecha desc limit 1");
		if(mysql_num_rows($q2)>0)
		{
			while($r2=mysql_fetch_object($q2))
			{
					$saldoencaja="$".number_format($r2->saldofinalcaja,2,".",",");
			}
		}else{ $saldoencaja="$0.00";}


		$ic='<table width="100%" >
		<tr>
			<td><label>Sucursal que esta operando</label></td>
			<td>'.$sucursal_operando.'<input type="hidden" id="sucursal" value="'.$sucursal_id.'"></td>
		</tr>
		<tr>
			<td><label>Saldo actualmente en caja</label></td>
			<td><span id="saldocaja">'.$saldoencaja.'</span></td>
		</tr>

		<tr>
			<td><label>Ingrese con cuanto inicia caja</label></td>
			<td><input type="text" id="iniciocaja" class="float" maxlength="8" /></td>
		</tr>
		</table>';

	}
	else{

		$cbo='<select id="sucursal" name="sucursal" onchange="cargasaldocaja(this.value);" >';
	$query = mysql_query("select idSuc id,nombre  from mrp_sucursal");
    $cbo.='<option value="">-Seleccione-</option>';
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';


	$ic='<table width="100%" >
		<tr>
			<td><label>Selecciona la ucursal que esta operando</label></td>
			<td>'.$cbo.'</td>
		</tr>
		<tr>
			<td><label>Saldo actualmente en caja</label></td>
			<td><span id="saldocaja">$0.00</span></td>
		</tr>

		<tr>
			<td><label>Ingrese con cuanto inicia caja</label></td>
			<td><input type="text" id="iniciocaja" class="float" maxlength="8" /></td>
		</tr>
		</table>';

	}	//else


	return $ic;
}

function sucursalesiniciocaja()
{
	/*
	$cbo='<select id="sucursal" name="sucursal" onchange="cargasaldocaja(this.value);" >';
	$query = mysql_query("select idSuc id,nombre  from mrp_sucursal");
    $cbo.='<option value="">-Seleccione-</option>';
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
	 */
}

/////////////////////////////////////////////
function formatofecha($fecha)
{
	list($anio,$mes,$rest)=explode("-",$fecha);
	list($dia,$hora)=explode(" ",$rest);

	return $dia."/".$mes."/".$anio." ".$hora;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////


function simple()
{
	$simple=false;
	$q=mysql_query("select a.idperfil from accelog_perfiles_me a where idmenu=1259");
	if(mysql_num_rows($q)>0)
	{
		$simple=true;
	}
	return $simple;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
function checatimbres()
{

		$ct=mysql_query("select total from pvt_contadorFacturas");
		while($rct=mysql_fetch_object($ct))
		{
			$numfacturas=$rct->total;
		}
		if($numfacturas>99)
		{
				return 1;
		}
		else{ return 0;}


}

?>
