<?php
set_time_limit(300);
include_once("../../netwarelog/catalog/conexionbd.php");
date_default_timezone_set("Mexico/General");

switch (@$_POST["funcion"])
{
	case 'nuevaVenta': echo nuevaVenta(); break;
	case 'cargacliente': echo cargacliente($_POST["cliente"]); break;
	case 'checatimbres': echo checatimbres(); break;
	case 'calculapago': echo calculapago($_POST["total"]); break;
	case 'checalimitecredito': echo checalimitecredito($_POST["cliente"],$_POST["monto"]); break;
	case 'saldocaja': echo saldocaja($_POST["sucursal"]); break;
	case 'ingresamercancia': echo ingresamercancia($_POST["producto"],$_POST["sucursal"],$_POST["cantidad"],$_POST["proveedor"],$_POST["costo"]); break;
	case 'productosexistencias': echo productosexistencias($_POST["iddepa"],$_POST["idfamilia"],$_POST["idlinea"]); break;
	case 'existenciassucursal': echo existenciasSucursal($_POST["id"]); break;
	case 'cancelarventa': echo cancelarventa($_POST["id"]); break;
	case 'cancelarVentaActual': echo cancelarVentaActual(); break;//Cancelar venta actual
	case 'ventas': echo imprimeventas($_POST["inicio"],$_POST["fin"],$_POST["sucursal"],$_POST["cliente"],$_POST["vendedor"],$_POST["estatus"]); break;
	//case 'guardarFacturacion': echo guardarFacturacion($_POST["idFact"],$_POST["datos"],$_POST["monto"],$_POST["cliente"],$_POST["idventa"]); break;
	case 'guardarFacturacion': echo guardarFacturacion($_POST["UUID"],$_POST["noCertificadoSAT"],$_POST["selloCFD"],$_POST["selloSAT"],$_POST["FechaTimbrado"],$_POST["idComprobante"],$_POST["idFact"],$_POST["idVenta"],$_POST["noCertificado"],$_POST["tipoComp"],$_POST["monto"],$_POST["cliente"],$_POST["trackId"],$_POST["idRefact"],$_POST["azurian"]); break;
	case 'entradasalidas': echo entradasalidas($_POST["inicio"],$_POST["fin"],$_POST["producto"],$_POST["movimiento"],$_POST["registros"],$_POST['pagina']); break;
	case 'cargaRfcs': echo cargaRfcs($_POST["idCliente"]); break;
	case 'Iniciarcaja': echo Iniciarcaja($_POST["sucursal"],$_POST["monto"]); break;
	case 'checarExistencia': echo checarExistencia($_POST["idArticulo"],$_POST["cantidad"],$_POST["almacen"],$_POST["txtCom"]); break;
	case 'eliminaproductocaja': echo eliminaproductocaja($_POST["id"]); break;
	case 'checatarjetaregalo': echo checatarjetaregalo($_POST["numero"],$_POST["monto"]);break;
	case 'eliminarpago': echo eliminarpago($_POST["id"],$_POST["total"]); break;
	case 'pagoscaja':echo pagoscaja($_POST["total"],$_POST["referencia"],$_POST["formapago"],$_POST["monto"],$_POST["idFormapago"],true); break;
	case 'agregaraCaja':echo imprimecaja($_POST["idArticulo"],$_POST["almacen"],$_POST["susp"]); break;
	case 'cambiarcantidad':echo cambiarcantidad($_POST["id"],$_POST["cantidad"],$_POST["descuento"],$_POST["tipodescuento"]); break;
	case 'cargaFamilias':echo familias($_POST["idDepartamento"]); break;
	case 'cargaFamilias2':echo familias2($_POST["idDepartamento"]); break;
	case 'cargaLineas':echo lineas($_POST["idFamilia"]); break;
	case 'cargaLineas2':echo lineas2($_POST["iddepa"],$_POST["idFamilia"]); break;
	case 'cargaProductos':echo productos($_POST["idLinea"]); break;
	case 'cargaProducto':echo CargaProducto($_POST["idProducto"],$_POST["almacen"]); break;
	case 'guardarVenta': echo guardarVenta($_POST["pagoautomatico"],$_POST["impuestos"],@$_POST["idFact"],$_POST["sucursal"],$_POST["almacen"],$_POST["documento"],$_POST["monto"],$_POST["cambio"],$_POST["cliente"],$_POST["empleado"],$_POST["ss_id"],$_POST["laobs"]); break;
	case 'suspenderVenta': echo suspenderVenta($_POST["pagoautomatico"],$_POST["impuestos"],@$_POST["idFact"],$_POST["sucursal"],$_POST["almacen"],$_POST["documento"],$_POST["monto"],$_POST["cambio"],$_POST["cliente"],$_POST["empleado"],$_POST["totalimpuestos"],$_POST["s_id"],$_POST["nombre"]); break;
	case 'datosSuspendida': echo datosSuspendida($_POST["id_suspendida"]); break;
	case 'elimina_suspendida': echo elimina_suspendida($_POST["id_suspendida"]); break;
	case 'cancelaFacturacion': echo cancelaFacturacion($_POST["id"]); break;
	case 'pendienteFacturacion': echo pendienteFacturacion($_POST["idFact"],$_POST["monto"],$_POST["cliente"],$_POST["idVenta"],$_POST["trackId"],$_POST["azurian"]); break;
	case 'envioFactura': echo envioFactura($_POST["uid"],$_POST["correo"],$_POST["azurian"]);
	case 'proveedores': echo proveedores($_POST["idProd"]); break;
	case 'cargaCosto': echo cargaCosto($_POST["idprov"],$_POST["idProd"]); break;
	case 'agregarPropina': echo agregarPropina($_POST["idArticulo"],$_POST["cantidad"]); break;
	case 'datosretiro': echo datosretiro($_POST['idretiro']); break;
	default:
		//var_dump($_SESSION['caja']);

}
mysql_close($conexion);
/////////////////////////////////////////////////////////////////////////////////////////////
function cortadec($numero)
{
	if( preg_match('/\./',$numero) ){
		if( preg_match('/E/',$numero) ){
			return $numero;
		}else{
			$de=explode('.',$numero);
			$de[1]=substr($de[1],0,9);
			return $de[0].'.'.$de[1];
		}
	}else{
		return $numero;
	}

}

function cancelaFacturacion($id)
{
	mysql_query("UPDATE pvt_contadorFacturas set total=total+1  where id=1");
	mysql_query("UPDATE pvt_respuestaFacturacion set borrado=1  where id='$id'");

}
function cargacliente($idCliente)
{
	$rfcs="<select id='rfc'>";
	$q=mysql_query("select id , rfc from comun_facturacion where nombre=".$idCliente);
	while($r=mysql_fetch_object($q))
	{
		$rfcs.="<option value='".$r->id."'>".$r->rfc."</option>";
	}
	$rfcs.="</select>";

	$q2=mysql_query("Select nombre from comun_cliente where id=".$idCliente);
	while($r2=mysql_fetch_object($q2))
	{
			$nombreCliente=$r2->nombre;
	}

	$respuesta=array();
	$respuesta[0]=$idCliente;
	$respuesta[1]=$nombreCliente;
	$respuesta[2]=$rfcs;

	return  json_encode($respuesta);
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
				mysql_query("Insert into mrp_stock values('',".$producto.",".$cantidad.",".$almacen.",1,0)");
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
function imprimeventas($inicio,$fin,$sucursal,$cliente,$vendedor,$estatus)
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


	 return ventas($filtro);
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

/////////////////////////////////////////////////////////////////////////////////////////////
	    	$regre=mysql_query("select vp.referencia,vp.monto from venta v, venta_pagos vp where vp.idVenta=v.idVenta and v.idVenta=".$id." and vp.idFormapago=3");
	    	if(mysql_num_rows($regre)>0){
	    		if($rege=mysql_fetch_array($regre)){
	  $tarjeta=mysql_query("update tarjeta_regalo set montousado=montousado+".$rege['monto']." where numero=".$rege['referencia']);
	    	}
			}
////////////////////////////////////////////////////////////////////////////////////////////////
	mysql_query("update venta set estatus=0 where idVenta=".$id);
	mysql_query("update pvt_pendienteFactura set facturado=2 where id_sale=".$id);
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

	//descuento %
	if($producto->tipodescuento=='%'){
		$descuento_p = $producto->preciounitario*$producto->descuento/100;
	}
	//descuento $
	if($producto->tipodescuento=='$'){
		$descuento_p = $producto->descuento;
	}

		/*IMPUESTOS*/
	$qi=mysql_query("select i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$id);
		if(mysql_num_rows($qi)>0)
		{
			while($ri = mysql_fetch_object($qi))
			{
				if(array_key_exists($ri->impuesto,$impuestos_venta))
				{
					$impuestos_venta[$ri->impuesto]=((($producto->preciounitario - $descuento_p)*$ri->valor)/100+$impuestos_venta[$ri->impuesto])*$producto->cantidad;
				}
				else
				{
					$impuestos_venta[$ri->impuesto]=((($producto->preciounitario - $descuento_p)*$ri->valor)/100)*$producto->cantidad;
				}
			}
		}

			/* END IMPUESTOS*/


			$descuento="";
			$descuentogeneral=0;
			if($producto->tipodescuento=="%")
			{
				$descuentogeneral=(($producto->preciounitario*$producto->descuento)/100)*$producto->cantidad;
				$descuento='$'.number_format($descuentogeneral,2,".",",");
			}
			if($producto->tipodescuento=="$")
			{
				$producto->descuento = ($producto->descuento == '') ? "0" : $producto->descuento;
				$descuentogeneral=$producto->descuento;
				$descuento='$'.number_format($producto->descuento,2,".",",");
			}

			if(	$descuento=="$")
			{
				$descuento="";
			}

			$subtotal=($producto->subtotal)-$descuentogeneral;
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
			    			<td align="center">$'.number_format($producto->preciounitario,2,".",",").'</td>
			    			<td align="center">'.($descuento).'</td>
			    			<td align="center">$'.number_format($producto->impuestosproductoventa,2,".",",").'</td>
    						<td align="center">$'.number_format(($producto->subtotal)-$descuentogeneral,2,".",",").'</td>
    				</tr>';
    				$supertotal+=($subtotal)-$descuentogeneral+($producto->impuestosproductoventa);
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

	//krmn
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
function ventas($filtro='fecha >= CURDATE()')
{
$i=0;
$totalmonto=0;
$totaliva=0;


$q=mysql_query("
	select
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
 where ".$filtro." order by folio desc");
//echo $filtro;
?>
	<table id="orden" align="center"  cellspacing="0" width="95%" border="0" class="busqueda">
		<thead>
			<tr class="busqueda_fila2">
				<th align="center" sort="folio">Folio</th>
				<th align="center" sort="fecha">Fecha</th>
				<th align="center" sort="cliente">Cliente</th>
				<th align="center" sort="vendedor">Vendedor</th>
			<?php
			if(simple()){
	 		?>
				<th align="center" sort="sucu">Sucursal</th>
			<?php
			}
			?>
				<th align="center" sort="estatus">Estatus</th>
				<th align="center" sort="impuestos">Impuestos</th>
				<th align="center" sort="monto">Monto</th>
				<th align="center"></th>
			</tr>
		</thead>
		<tbody>
<?php
	if(mysql_num_rows($q)>0){
		while($r=mysql_fetch_object($q))
		{

			if($r->estatus=='Cancelada'){ $propiedad="style='color:red;'"; } else { $propiedad="";  }
	?>
			<tr class="busqueda_fila2">
			<td align="center"><?php echo $r->folio; ?></td>
			<td align="center"><?php echo formato_fecha($r->fecha); ?></td>
			<td align="center"><?php echo $r->cliente; ?></td>
			<td align="center"><?php echo $r->empleado; ?></td>

	<?php
		if(simple()){
	?>
			<td align="center"><?php echo $r->sucursal; ?></td>
	<?php
	}
	?>
			<td align="center"><span <?php echo $propiedad; ?> ><?php echo $r->estatus; ?></span></td>
			<td align="center">$<?php echo number_format($r->iva,2,".",","); ?></td>
			<td align="center">$<?php echo number_format($r->monto,2,".",","); ?></td>
			<td align="center">
	<?php
	$i++;
		if($r->estatus!='Cancelada'){
	?>
			<img src="img/editar.png" height="25" width="25" style="cursor:pointer;"  onclick="detalleVenta(<?php echo $r->folio; ?>);" >
	<?php
	$totaliva+=$r->iva;
	$totalmonto+=$r->monto;
	}
	?>
			</td>
		</tr>

	<?php
		}
	if($i<=4)
{
	for($e=$i;$e<=4;$e++){
	?>
		<tr class="busqueda_fila2">
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td> <td></td>
		</tr>
	<?php
	}
}
	?>
	</tbody>
   	<tfoot class="nav">
   		<tr style="font-size: 10pt;background:#333333;color: #FFFFFF"><td></td><td></td><td></td><td></td><td></td><td></td>
			<td align="center" ><strong>Total General:$<?php echo number_format($totaliva,2,".",","); ?></strong></td>
			<td align="center"><strong>Total General:$<?php echo number_format($totalmonto,2,".",","); ?></strong></td>
			<td></td>
		</tr>
   		<tr align="right">
            <td colspan=7 align="right">
                    <div class="pagination" align="right" style="font-size: 12px;"></div>
                    <div class="paginationTitle" align="right" style="font-size: 12px;">Pagina</div>
                    <div class="selectPerPage" align="right" style="font-size: 12px;"></div>

            </td>
        </tr>
       </tfoot>
	</table>
	<?php

}



//return $q;


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

	return $mes."/".$dia."/".$anio." ".$h.":".$m;
}


/**************************************************************************/
function entradasalidas($inicio="",$fin="",$producto="",$movimiento="",$registros=20, $pagina=0)
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
) as super where ".$filtro." order by fecha desc");

$total =  mysql_num_rows($q);
$tpaginas = ceil($total/$registros);

echo $tpaginas.' Paginas';

if($pagina!=0){
	$pagina=$pagina-1;
	$comienza=$pagina*$registros;
	$actual=$pagina+1;
}else{
	$actual=$pagina+1;
	$comienza=0;
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
) as super where ".$filtro." order by fecha desc LIMIT ".$comienza.", ".$registros."
");

$i=0;
$total=0;
	$tabla='<table  id="orden" align="center" width="95%" border="0" class="busqueda">
		<thead><tr class="busqueda_fila2">
			<th sort="folio">Folio</th>
			<th sort="fecha">Fecha</th>
			<th sort="product">Producto</th>
			<th sort="movi">Movimiento</th>
			<th sort="prove">Proveedor/Cliente</th>
			<th sort="cantidad">Cantidad</th>
			<th sort="monto">Monto</th>
			<th sort="sucu">Sucursal</th>
			<th sort="alma">Almac&eacute;n origen</th>
			<th sort="destino">Almac&eacute;n destino</th>
		</tr></thead><tbody>';
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

$div = '<div>Pagina ';
for ($i=1; $tpaginas>=$i; $i++) {
	if($actual==$i){
		$div.='<b>'.$i.'</b> ';
	}else{
		$div.='<a style="cursor:pointer; text-decoration:underline;" onclick="filtramovimientos('.$i.')">'.$i.'</a> ';
	}
}
$div.='</div><br>';

$tabla.=$div;
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
	$qry  = "SELECT ";
	$qry .= "	au.idSuc ";
	//$qry .= "	,mp.nombre ";
	$qry .= "FROM ";
	$qry .= "	administracion_usuarios au ";
	$qry .= "	INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc ";
	$qry .= "WHERE ";
	$qry .= "	au.idempleado = " . $_SESSION['accelog_idempleado'] . " ";
	$qry .= "LIMIT 1;";

	$q = mysql_query( $qry );

	if( mysql_num_rows( $q ) > 0 )
	{
		while($r = mysql_fetch_object( $q ) )
		{
			//$sucursal_operando=$r->nombre;
			$sucursal_id = $r->idSuc;
		}
	}

	$qry  = "SELECT ";
	$qry .= "	idCortecaja ";
	$qry .= "FROM ";
	$qry .= "	inicio_caja ";
	$qry .= "WHERE ";
	$qry .= "	idSucursal = " . $sucursal_id . " ";
	$qry .= "	AND idUsuario = " . $_SESSION['accelog_idempleado'] . " ";
	$qry .= "ORDER BY ";
	$qry .= "	id desc ";
	$qry .= "LIMIT 1;";

	$q = mysql_query( $qry );
	if( mysql_num_rows( $q ) > 0 )
	{
		$row = mysql_fetch_object( $q );
		if( is_numeric( $row->idCortecaja ) )//Selecciona el corte y si ese dato no es nulo ese inicio esta cerrado
			return 1;
		else
			return 0;// si el registro traido es nulo envia 0
	}
	else
	{// Si ni siquiera tiene registro de inicio de caja
		return 1;
	}
}
/**************************************************************************************************/

function checarExistencia($producto,$cantidad,$almacen,$txtCom)
{
	$_SESSION['caja'][$producto]->comentario=$txtCom;
		 //si es simple//
		if(simple()){
	  //end si es simple//
			 //krmn

			 //*checo si el producto es compuesto por materiales*/
			$q0=mysql_query("select mpm.idMaterial,mpm.cantidad
			from mrp_producto_material mpm,mrp_producto mp
            where mp.idProducto=mpm.idProducto and mp.eskit=1 and mpm.idProducto=".$producto);
			if(mysql_num_rows($q0)>0)
			{
				while($r=mysql_fetch_object($q0))
				{
		$q1=mysql_query("select s.cantidad-if(SUM(o.nDevoluciones) is null,0,SUM(o.nDevoluciones)) cantidad
		from mrp_stock s left join mrp_devoluciones_reporte o on
		o.idProducto=s.idProducto and o.idAlmacen=s.idAlmacen and o.estatus=0
		where s.idAlmacen=".$almacen." and s.idProducto=".$r->idMaterial);
						$row1 = mysql_fetch_object($q1);
						if(((float)($r->cantidad*$cantidad)>(float)$row1->cantidad) || (((float)$row1->cantidad)==0))
						{

							return "No hay materiales suficiente de este producto";
						}
				}
				return 0;

			}
			else //si no es producto que contiene materiales
			{
					$q=mysql_query("select s.cantidad-if(SUM(o.nDevoluciones) is null,0,SUM(o.nDevoluciones)) cantidad from mrp_stock s left join mrp_devoluciones_reporte o on o.idProducto=s.idProducto and o.idAlmacen=s.idAlmacen and o.estatus=0 where s.idAlmacen=".$almacen." and s.idProducto=".$producto);
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
		$q=mysql_query('SELECT idunidad FROM mrp_producto WHERE idProducto='.$idProducto);
		if (mysql_num_rows($q) >= 0)
		{
				$row = mysql_fetch_object($q);
				//if($row->idUni!=27){
					$compuesto=conversionMinima($row->idunidad);
				//}
				//$compuesto=$this->conversionMinima(27);

				return $compuesto;
		}
		else { return "sin asignar Unidad"; }
	}

/**************************************************************************************************/
	function conversionMinima($unidad)
	{
		//krmn
			$q=mysql_query('SELECT conversion,unidad,compuesto from mrp_unidades where idUni='.$unidad);
			$row = mysql_fetch_object($q);
			$compuesto=$row->compuesto;
			if($row->unidad!=$unidad)
			{
				//$compuesto=conversionMinima($row->unidad);//se quita para que no regrese la minima unidad
				//var_dump($compuesto);
			}
			return $compuesto;
	}
/**************************************************************************************************/
function existenciaproducto($id,$almacen)
{


	$q=mysql_query("select s.cantidad-if(SUM(o.nDevoluciones) is null,0,SUM(o.nDevoluciones)) cantidad from mrp_stock s left join mrp_devoluciones_reporte o on o.idProducto=s.idProducto and o.idAlmacen=s.idAlmacen and o.estatus=0 where s.idAlmacen=".$almacen." and s.idProducto=".$id);
	$row = mysql_fetch_object($q);
	if (mysql_num_rows($q) > 0)
		{$cantidad= $row->cantidad;}else{$cantidad=0;}
	return $cantidad ." ".unidadMinima($id);


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
function guardarVenta($pagoautomatico,$impuestos,$idFact,$sucursal,$almacen,$doc,$monto,$cambio,$cliente,$empleado,$ss_id,$laobs='')
{

	if($ss_id>0){
		elimina_suspendida($ss_id);
	}
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
	if(!is_numeric($idVenta) || $idVenta==0 ){
		$JSON = array('success' =>'-1');
		echo json_decode($JSON);
	}
	foreach(@$_SESSION['caja'] as $key=>$producto)
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
		$total=($producto->precioventa*$producto->cantidad)-$descuentogeneral+($producto->impuesto);
////////////////////////////////////////////
		$queryp="INSERT INTO venta_producto (idventa_producto,idProducto,cantidad,preciounitario,tipodescuento,descuento,subtotal,idVenta,impuestosproductoventa,montodescuento,total,arr_kit,comentario) VALUES ";
        $queryp.="('',".$key.",".$producto->cantidad.",'".$producto->precioventa."','".$producto->tipodescuento."','".$producto->descuento."',".($subtotal).",".$idVenta.",'".($producto->impuesto)."','".$descuentogeneral."','".($total)."','".$producto->arr_kit."','".$producto->comentario."');";
        $result=mysql_query($queryp);
        $idVentaProducto=mysql_insert_id();
//////////////////////////////////////////////////

		$qi=mysql_query("select i.id idImpuesto,i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$key);
		if(mysql_num_rows($qi)>0)
		{
			while($ri = mysql_fetch_object($qi))
			{
				$queryi="insert into venta_producto_impuesto values('',".$idVentaProducto.",".$ri->idImpuesto.",'".$ri->valor."');";
				mysql_query($queryi);
			}
		}


		//compruebo si el producto tiene materiales
		/*
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
					//descuento el producto terminado
					$q2=mysql_query("select s.cantidad from mrp_stock s where  s.idProducto=".$key." and  s.idAlmacen=".$almacen);
					$row2 = mysql_fetch_object($q2);
					$updatestock="Update mrp_stock set cantidad=".($row2->cantidad-$producto->cantidad)." where  idAlmacen=".$almacen." and idProducto=".$key;
					mysql_query($updatestock);
			}
			else{//si el producto no tiene materiales
		*/
		//krmn

//////////////////////////////////////////
$sieskit=mysql_query('select s.cantidad
from mrp_stock s,mrp_producto mp
where  s.idProducto='.$key.' and  s.idAlmacen='.$almacen.' and mp.eskit=1 and mp.idProducto=s.idProducto ');
if(mysql_num_rows($sieskit)>0)//sisi es kit :)
{
	$idcantidad=mysql_query('select s.cantidad,mm.idMaterial,mm.cantidad materia
from mrp_stock s,mrp_producto_material mm
where    mm.idProducto='.$key.' and s.idProducto=mm.idMaterial
and s.idAlmacen='.$almacen.' GROUP BY mm.idMaterial');
while($materia = mysql_fetch_object($idcantidad))
		{
		$updatestock="Update mrp_stock set cantidad=".($materia->cantidad-$materia->materia)." where  idAlmacen=".$almacen." and idProducto=".$materia->idMaterial;
			mysql_query($updatestock);

		}
}
///////////////////////////////////////


else{
		if($producto->tipo_producto!=6){
			$q=mysql_query("select s.cantidad from mrp_stock s where  s.idProducto=".$key." and  s.idAlmacen=".$almacen);
			if(mysql_num_rows($q)>0)
			{
				$row = mysql_fetch_object($q);
				$updatestock="Update mrp_stock set cantidad=".($row->cantidad-$producto->cantidad)." where  idAlmacen=".$almacen." and idProducto=".$key;
				mysql_query($updatestock);
			}
		}

}
		//}
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

$diasCredito = mysql_query("SELECT dias_credito from comun_cliente where id=".$cliente);
$diasC = mysql_fetch_object($diasCredito);
$nuevafecha = strtotime ( '+'.$diasC->dias_credito.' day' , strtotime($fechaactual));
$nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha );

$cc=mysql_query("INSERT INTO cxc (fechacargo, fechavencimiento, idVenta, monto, saldoabonado, saldoactual, estatus, idCliente, concepto) VALUES ('".$fechaactual."','".$nuevafecha."',".$idVenta.",'".$montopago."','0','".$montopago."','0',".$cliente.",'Venta a credito');");
			}

			if($idFormapago==3)//tarjeta de regalo
			{
//var_dump("INSERT INTO cxc (fechacargo, fechavencimiento, idVenta, monto, saldoabonado, saldoactual, estatus, idCliente, concepto) VALUES ('".$fechaactual."','".$fechaactual."',".$idVenta.",'".$montopago."','0','0','0',".$cliente.",'Venta a credito');");
unset($diasCredito);
unset($diasC);
unset($nuevafecha);

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
	if(is_numeric($idFact) && $doc==2 )
	{
		if(!$result){$error=1;}
		if($error){
			mysql_query("ROLLBACK");
			$mms = "Error en la transaccion";
			$JSON = array('success' =>500,
				'mensaje'=>$mms);
			echo json_encode($JSON);
		}else{
			mysql_query("COMMIT");
			Facturar($idFact,($monto-$impuestos),$impuestos,$idVenta,0,$laobs);
		}
	}else{
		mysql_query("COMMIT");
		Facturar($idFact,($monto-$impuestos),$impuestos,$idVenta,1,$laobs);
		unset($_SESSION['pagos-caja']);
		unset($_SESSION['caja']);
		$mms='Venta realizada con exito.';
		$JSON = array('success' =>3,
				'mensaje'=>$mms,
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
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
		return "No esta registrado este numero de tarjeta";
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
	$pagosC='<table width="100%" border="0"><tr><th>Forma de pago</th><th>Monto</th><th></th></tr>';
	$i=0;
	$_SESSION['pagos-caja'][$idformapago]=$formapago."_".$monto."_".@$referencia;
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


	///////////////////puse el tfoot
	$pagosC.='

	<table>';

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

	switch ($articulo->tipodescuento) {
		case '%':
			# code...
			break;

		default:
			# code...
			break;
	}
	//descuento %
	if($articulo->tipodescuento=='%')
	{
		$descuento_p = ($articulo->precioventa*$articulo->cantidad*$articulo->descuento)/100;
		$impuesto_nuevo=((($articulo->precioventa*$articulo->cantidad - $descuento_p)*$articulo->suma_impuestos)/100);
	}
	//descuento $
	if($articulo->tipodescuento=='$')
	{
		$descuento_p = $articulo->descuento;
		$impuesto_nuevo=((($articulo->precioventa*$articulo->cantidad - $descuento_p)*$articulo->suma_impuestos)/100);
	}



	$articulo->impuesto=$impuesto_nuevo;

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
function imprimecaja($idArticulo=0,$almacen=0,$susp=0)
{
	$resultado=array();
	$caja = "";
	$rown = new stdClass();
	$rown -> nombre = '';
	$rows = array();
	//si llega el nombre del producto
	if(!is_numeric($idArticulo))
	{
		$queryn = mysql_query("select idProducto id,nombre,codigo,precioventa  from mrp_producto where vendible=1 and strcmp(nombre,'".$idArticulo."')=0  OR strcmp(codigo,'".$idArticulo."')=0  ");
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
			$queryn = mysql_query("select idProducto id,nombre,codigo,precioventa  from mrp_producto where vendible=1 and  strcmp(codigo,'".$idArticulo."')=0  ");
		if(mysql_num_rows($queryn)>0)
			{
				$rown = mysql_fetch_object($queryn);
				$idArticulo=$rown->id;
			}
			else{
						$queryn = mysql_query("select idProducto id,nombre,codigo,precioventa  from mrp_producto where vendible=1 and  idProducto=".$idArticulo );
						if(mysql_num_rows($queryn)==0)
						{
							$resultado[0]="No existe un articulo con el ID o codigo ingresado";
							return json_encode($resultado);
						}
			}
		}
	}

	$comanda = false;
	$rows = array();
	$pos = strpos($rown -> nombre, "comanda");
	if($pos !== false)
	{

		$comanda = true;

		$codigo = $rown -> codigo;

		/*
			Validamos que la comanda sea pago completo o por persona, si el primer caracter es '#', la comanda se paga por persona.
		*/
		if($codigo[5] == 'P')
		{
			$comanda = 'COM'. substr($codigo,0,5);
			$persona = round(substr($codigo,-2));

			$idProductos = "Select idProducto,cantidad ";
			$idProductos .= " FROM com_pedidos p,com_comandas c ";
			$idProductos .= " WHERE c.codigo = '".$comanda."' ";
			$idProductos .= " AND idProducto != 0 ";
			$idProductos .= " AND npersona = ".$persona." ";
			$idProductos .= " AND c.id=p.idcomanda ";
		}else
		{
			$idProductos = "Select idProducto,cantidad ";
			$idProductos .= " FROM com_pedidos p,com_comandas c ";
			$idProductos .= " WHERE c.codigo = '".$codigo."' ";
			$idProductos .= " AND idProducto != 0 ";
			$idProductos .= " AND c.id=p.idcomanda ";
		}

		$result = mysql_query($idProductos);


		while ($row = mysql_fetch_array($result, MYSQLI_ASSOC))
			{
				if(array_key_exists($row["idProducto"], $rows))
				{
					$rows[$row["idProducto"]]["idProducto"] = $row["idProducto"];
					$rows[$row["idProducto"]]["cantidad"] = (int)$rows[$row["idProducto"]]["cantidad"] + (int)$row["cantidad"];
				}else
				{
					$rows[$row["idProducto"]]["idProducto"] = $row["idProducto"];
					$rows[$row["idProducto"]]["cantidad"] = (int)$row["cantidad"];
				}
			}
		$rows = array_values($rows);

		if($rows[0] == '')
		{
			$resultado[0]="No se encontraron productos en la comanda.";
			return json_encode($resultado);
		}

		$resultado[3] = $codigo;

		$queryProductoPropina = " Select idproducto from com_productos_propina";

		$resultProductoPropina = mysql_query($queryProductoPropina);

		while($row = mysql_fetch_array($resultProductoPropina, MYSQLI_ASSOC))
            {
                $rowsPropina[] = $row["idproducto"];
            }

		if($rowsPropina[0] != '')
		{
			$resultado[4] = $rowsPropina[0];
		}
	}else
	{
		$rows[0]["cantidad"] = 1;
	}

	//en si llega el nombre del producto
	/*
		///SUSPENDER
		 $caja='
    	<div id="opciones-caja">
	    	<table border="0" width="100%">';
	$caja.='<tr><td>';

	$caja.='<input type="button" value="Suspender" id="boton-suspender" onclick="suspender();">';


	$caja.='</td>
			</tr>
			</table>
		</div>';
	//END SUSPENDER

	*/

	$impuestos_venta=array();
	@$caja.='<div id="caja"><table id="table-caja" border="0" width="100%">
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


foreach ($rows as $key => $value) {

		if($comanda)
		{
			$idArticulo=$value["idProducto"];
		}

if($idArticulo!=0)
{
	 //si es simple//
		//if(simple()){
	  //end si es simple//


			//checar existencias
if($susp==0){
	if($almacen!=0)
	{

$q0=mysql_query("select mpm.idMaterial,mpm.cantidad
			from mrp_producto_material mpm,mrp_producto mp
            where mp.idProducto=mpm.idProducto and mp.eskit=1 and mpm.idProducto=".$idArticulo);
			if(mysql_num_rows($q0)>0)
			{$cant=1;
				while($r=mysql_fetch_object($q0))
				{
						$q1=mysql_query("select s.cantidad from mrp_stock s where idAlmacen=".$almacen." and idProducto=".$r->idMaterial);
						$row1 = mysql_fetch_object($q1);
						if((float)$r->cantidad>(float)$row1->cantidad)
						{
							if(simple() && $comanda == FALSE){
								$resultado[0]="No hay existencia suficiente de este producto -> ".$idArticulo;
								return json_encode($resultado);
							}
						}
				}

			}
			else //si no es producto que contiene materiales
			{

//krmn existe
$q=mysql_query("select s.cantidad-if(SUM(o.nDevoluciones) is null,0,SUM(o.nDevoluciones)) cantidad from mrp_stock s left join mrp_devoluciones_reporte o on o.idProducto=s.idProducto and o.idAlmacen=s.idAlmacen and o.estatus=0 where s.idAlmacen=".$almacen." and s.idProducto=".$idArticulo);
					$row = mysql_fetch_object($q);

					if((float)$row->cantidad>0 && (float)$row->cantidad<1)
					{
						$cant=(float)$row->cantidad;
					}
					elseif((float)$row->cantidad>=1)
					{
						$cant=1;
					}
					else
					{
						if(simple() && $comanda == FALSE){
							$resultado[0]="No hay existencia suficiente de este producto -> ".$idArticulo;
							return json_encode($resultado);
						}else{
							$cant=1;
						}
					}

			}
	}//almacen !=0


	//end checar existencias
}
	 //si es simple//
	// }
	  //end si es simple//
if(!isset($_SESSION['caja']))//si no existen la caja
{
	$query = mysql_query("select p.idProducto id,p.nombre,p.codigo,p.precioventa,p.esreceta,p.eskit,p.tipo_producto,
(select (case p.idunidad when 0 then 0 else u.compuesto  end) FROM
mrp_unidades u where  u.idUni=p.idunidad)unidad
from mrp_producto p
 where  p.idProducto=".$idArticulo);
	$row = mysql_fetch_object($query);
	$row->cantidad=number_format($cant,2);
	$row->descuento=0;
	$row->tipodescuento="$";
////////////////////////////
	$row->arr_kit = "";
	 if($row->eskit==1){
	  $cons_tay = mysql_query("select idMaterial,cantidad
	        from mrp_producto_material
	        where idProducto=".$idArticulo."
	        GROUP BY idMaterial;");
		$consu=mysql_query('select p.idProducto,p.precioventa, pi.`valor`, i.`nombre`
                from impuesto i, mrp_producto p
 				left join producto_impuesto pi on p.`idProducto`=pi.`idProducto`
				where p.idProducto='.$idArticulo.' and i.`id`=pi.`idImpuesto`;');
		$obj_resul = mysql_fetch_object($consu);
		$arr_resul1 = array('idProducto'=>$obj_resul->idProducto,'precioVenta'=>$obj_resul->precioventa);
		$arr_resul2 = array($obj_resul->nombre => $obj_resul->valor);
		while($obj_resul = mysql_fetch_object($consu)){$arr_resul2[$obj_resul->nombre] = $obj_resul->valor;}
		$row->arr_kit.=json_encode($arr_resul1);
		$row->arr_kit.=json_encode($arr_resul2);
		//while($obj_resul = mysql_fetch_object($consu)){$row->arr_kit.= json_encode($obj_resul->idImpuesto);}
	    while($obj_tay = mysql_fetch_object($cons_tay)){$row->arr_kit.= json_encode($obj_tay);}
	 }
	 ///////////////////////////
	$producto_impuesto=0;
	$suma_impuestos=0;
	$qi=mysql_query("select i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$idArticulo. " Order by pi.idImpuesto DESC");
	if(mysql_num_rows($qi)>0)
	{

		while($ri = mysql_fetch_object($qi))
		{
			if($producto_impuesto != 0)
			{
				$producto_impuesto+=(($row->precioventa+$producto_impuesto)*$ri->valor)/100;
				$suma_impuestos+=$ri->valor;
			}else
			{
				$producto_impuesto+=(($row->precioventa - $row->descuento)*$ri->valor)/100;
				$suma_impuestos+=$ri->valor;
			}
			//exit("(".$row->precioventa." - ".$row->descuento.")*".$ri->valor.")/100");
		}

	}
	$row->impuesto=$producto_impuesto;
	$row->cantidad=number_format(@(int)$value["cantidad"],2);
	$row->suma_impuestos=$suma_impuestos;

	//krmn
	$_SESSION['caja'][$idArticulo]=$row;

}
else
{
	if(array_key_exists($idArticulo,$_SESSION['caja']))//si ya existe el articulo agregado en la caja
	{
		$articulo=$_SESSION['caja'][$idArticulo];
		//$articulo->cantidad=$articulo->cantidad+1;

		if((float)@$row->cantidad>=$articulo->cantidad+1)
		{

			if($comanda)
			{
				$articulo->cantidad=number_format((int)$articulo->cantidad+(int)$value["cantidad"],2);
			}else
			{
				$articulo->cantidad=number_format($articulo->cantidad+1,2);
			}

		}
		else
		{
			if(simple() && $comanda == FALSE){
				$resultado[0]="No hay existencia suficiente de este producto -> ".$idArticulo;
				return json_encode($resultado);
			}
		}

		$_SESSION['caja'][$idArticulo]=$articulo;
	}
	else//si es un articulo nuevo agregado a la caja
	{
		$query = mysql_query("select p.idProducto id,p.nombre,p.codigo,p.precioventa,p.esreceta,p.eskit,
(select (case p.idunidad when 0 then 0 else u.compuesto  end) FROM
mrp_unidades u where  u.idUni=p.idunidad)unidad
from mrp_producto p
 where  p.idProducto=".$idArticulo);
		$row = mysql_fetch_object($query);
		$row->cantidad=number_format(@$cant,2);
		$row->descuento=0;
		$row->tipodescuento="$";
		$producto_impuesto=0;

	///////////////////////////
	$row->arr_kit = "";
	 if($row->eskit==1){
	  $cons_tay = mysql_query("select idMaterial,cantidad
	        from mrp_producto_material
	        where idProducto=".$idArticulo."
	        GROUP BY idMaterial;");
		$consu=mysql_query('select p.idProducto,p.precioventa, pi.`valor`, i.`nombre`
                from impuesto i, mrp_producto p
 				left join producto_impuesto pi on p.`idProducto`=pi.`idProducto`
				where p.idProducto='.$idArticulo.' and i.`id`=pi.`idImpuesto`;');
		$obj_resul = mysql_fetch_object($consu);
		@$arr_resul1 = array('idProducto'=>$obj_resul->idProducto,'precioVenta'=>$obj_resul->precioventa);
		@$arr_resul2 = array($obj_resul->nombre => $obj_resul->valor);
		while($obj_resul = mysql_fetch_object($consu)){$arr_resul2[$obj_resul->nombre] = $obj_resul->valor;}
		$row->arr_kit.=json_encode($arr_resul1);
		$row->arr_kit.=json_encode($arr_resul2);
		//while($obj_resul = mysql_fetch_object($consu)){$row->arr_kit.= json_encode($obj_resul->idImpuesto);}
	    while($obj_tay = mysql_fetch_object($cons_tay)){$row->arr_kit.= json_encode($obj_tay);}
	 }
	///////////////////////////
		$suma_impuestos=0;
		$qi=mysql_query("select i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$idArticulo." Order by idImpuesto DESC");
		if(mysql_num_rows($qi)>0)
		{
			while($ri = mysql_fetch_object($qi))
			{
				if($producto_impuesto != 0)
				{
					$producto_impuesto+=(($row->precioventa+$producto_impuesto)*$ri->valor)/100;
					$suma_impuestos+=$ri->valor;
				}else
				{
					$producto_impuesto+=(($row->precioventa - $row->descuento)*$ri->valor)/100;
					$suma_impuestos+=$ri->valor;
				}
			}

		}
		//krmn
		$row->impuesto=$producto_impuesto;
		$row->suma_impuestos=$suma_impuestos;
		$row->cantidad=number_format(@$value["cantidad"],2);

		$_SESSION['caja'][$idArticulo]=$row;

	}
}

}//if $idArticulo=0

}

	if(isset($_SESSION['caja']))
	{

	foreach($_SESSION['caja'] as $id=>$producto)
	{
		$impuestos_venta='';

			$descuento="";
			$descuentogeneral=0;
			if($producto->descuento==''){
				$producto->descuento=0;
			}
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
	$qi=mysql_query("select i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$id." Order by idImpuesto DESC");

		if(mysql_num_rows($qi)>0)
		{
			//$cuenta=0;
			while($ri = mysql_fetch_object($qi))
			{

				if(array_key_exists($ri->impuesto,$impuestos_venta))
				{
					if(array_key_exists("IEPS",$impuestos_venta))
					{
						//echo "((".$subtotal."+".$impuestos_venta["IVA"].")*".$ri->valor.")/100+".$impuestos_venta[$ri->impuesto];
						$impuestos_venta[$ri->impuesto]=((($subtotal)*$ri->valor)/100+$impuestos_venta[$ri->impuesto]);
						$impuestos_venta2[$ri->impuesto]=((($subtotal)*$ri->valor)/100+$impuestos_venta[$ri->impuesto]);

					}else
					{
						$impuestos_venta[$ri->impuesto]=((($subtotal+$impuestos_venta["IEPS"])*$ri->valor)/100+$impuestos_venta[$ri->impuesto]);
						$impuestos_venta2[$ri->impuesto]=((($subtotal+$impuestos_venta["IEPS"])*$ri->valor)/100+$impuestos_venta[$ri->impuesto]);
					}
				}
				else
				{
					if(array_key_exists("IEPS",$impuestos_venta))
					{
						//echo "((".$subtotal."+".$impuestos_venta["IVA"].")*)".$ri->valor."/100";
						$sumassiva+=((($subtotal+$impuestos_venta["IEPS"])*$ri->valor)/100);
						$impuestos_venta[$ri->impuesto]=((($subtotal+$impuestos_venta["IEPS"])*$ri->valor)/100);
						if($impuestos_venta2[$ri->impuesto]!=''){
							$impuestos_venta2[$ri->impuesto]+=((($subtotal+$impuestos_venta["IEPS"])*$ri->valor)/100);

						}else{
							$impuestos_venta2[$ri->impuesto]=((($subtotal+$impuestos_venta["IEPS"])*$ri->valor)/100);

						}

					}else
					{

						$impuestos_venta[$ri->impuesto]=(($subtotal*$ri->valor)/100);
						if($impuestos_venta2[$ri->impuesto]!=''){
							$impuestos_venta2[$ri->impuesto]+=(($subtotal*$ri->valor)/100);
						}else{
							$impuestos_venta2[$ri->impuesto]=(($subtotal*$ri->valor)/100);
						}
					}
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
					if($producto->esreceta==0)
					{
						$materiales.=$rowcompuesto["cantidad"]." ".$rowcompuesto["compuesto"]." ".$rowcompuesto["producto"]."<br>";
					}
				}
			}

			//krmn
			if($producto->unidad!=null){ $uni=$producto->unidad; }else{ $uni='sin unidad';}
			if($i%2==0){ $caja.='<tr class="par" >'; } else { $caja.='<tr  class="impar">';}
				$caja.='<td align="center"><img src="img/bor.png" onclick="eliminaproductocaja('.$id.');" style="cursor:pointer"></td>
							<td align="center" onclick="editArticulo('.$id.');">'.$producto->codigo.'</td>
			    			<td align="center" onclick="editArticulo('.$id.');"><span '.$propiedad.'>'.$producto->nombre.'</span><br>'.$materiales.'</td>
			    			<td align="center" onclick="editArticulo('.$id.');">'.$producto->cantidad.' '.$uni.'</td>
			    			<td align="center" onclick="editArticulo('.$id.');">$'.number_format($producto->precioventa,2,".",",").'</td>
			    			<td align="center" onclick="editArticulo('.$id.');">$'.number_format($impuestos_venta['IEPS']+$impuestos_venta['IVA'],2,".",",").'</td>
			    			<td align="center" onclick="editArticulo('.$id.');">'.($descuento).'</td>
    						<td align="center" onclick="editArticulo('.$id.');">$'.number_format((($producto->precioventa*$producto->cantidad))-$descuentogeneral,2,".",",").'</td>
    				</tr>';
		$i++;
//var_dump($impuestos_venta2);
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
    $caja.='
    </table>';



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


    $caja.='</div>';



    	$caja.='<div id="footer-caja">
	    	<table id="orden" border="0" width="100%">';


			$caja.='<tr>
			<td></td><td  align="left"></td><td  align="right">
			Subtotal:$'.number_format($total,2,".",",");$caja.='</td></tr>';
			$totalimpuestos=0;
			//$impuestos_venta['IEPS']=$sumass;
			//$impuestos_venta['IVA']=$sumassiva;
			//$_SESSION['caja']['IVA']=$impuestos_venta['IVA'];
			if($impuestos_venta2==''){
				$impuestos_venta2['IVA']=0.00;
			}
			$impuestos_venta=$impuestos_venta2;
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

				<td  align="left">';

				$caja.='<input type="hidden" id="idvendedor" value="'.$_SESSION['accelog_idempleado'].'" >
				<div id="vendedor">Vendedor:'.$_SESSION['accelog_login'].'</div></td>
				<td id="total" align="right">
					Total:$'.number_format($total+$totalimpuestos,2,".",",");
				 if($i>0)
				 {
				 		 //si es simple//
				 		 //krmn1
		if(simple()){
	  //end si es simple//
				 	$caja.='<img id="llve" style="display:none;" src="img/loader.gif"><input type="button" value="Pagar" id="boton-pagar" onclick="pagar('.($total+$totalimpuestos).','.$totalimpuestos.',0);">';
						 //si es simple//
		}	  else{
		$caja.='<img id="llve" style="display:none;" src="img/loader.gif"><input type="button" value="Pagar" id="boton-pagar" onclick="pagar('.($total+$totalimpuestos).','.$totalimpuestos.',0);">';
			}
	  //end si es simple//
				 }
				$caja.='</td>
			</tr>
			</table>
		</div>';

	$resultado[0]=1;
	$resultado[1]=$caja;
	if($resultado[4] != '')
	{
		$resultado[5]=number_format(($total+$totalimpuestos)*10/100,2,".",",");;
	}
	return json_encode($resultado);

	//return $caja;
}

 /***************************************************************************************************/
function productosexistencias($iddepa=0,$idfamilia=0,$idlinea=0)
{
	$filtro="";
	if($idlinea!=0){$filtro="where idLinea=".$idlinea;}
	else
		if($idfamilia!=0){$filtro="where idLinea IN (select idLin from mrp_linea where idFam=".$idfamilia.")";}
		else
			if($iddepa!=0){$filtro="where idLinea IN (select idLin from mrp_linea where idFam IN (select idFam from mrp_familia where idDep=".$iddepa."))";}


	$cbo='<select id="producto" name="producto" onchange="cargaexistencias(this.value); filtraProveedor(this.value);">';
	  $cbo.='<option value="">-Seleccione-</option>';
	$query = mysql_query("select idProducto id,nombre from mrp_producto ".$filtro." ORDER BY nombre asc");
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
	if($id!=0){$filtro=" where idLinea=".$id." and vendible=1 and estatus=1";}else{$filtro="where vendible=1 and estatus=1";}

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
	$cbo='<select id="departamento" name="departamento" onchange="cargaFamilias2(this.value);cargaLineas2(this.value,0);loadproductos(this.value,0,0);">';
	$query = mysql_query("select idDep id,nombre  from mrp_departamento");
    $cbo.='<option value="0">-Todos-</option>';
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

function familias2($iddep=0)
{
	$filtro="";
	if($iddep!=0){$filtro=" where idDep=".$iddep;}

	$cbo='<select id="familia" name="familia" onchange="cargaLineas2('.$iddep.',this.value); loadproductos('.$iddep.',this.value,0);">';
	$cbo.='<option value="0">-Todos-</option>';
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


function lineas2($iddep=0,$idfam=0)
{
	$filtro="";
	if($idfam!=0){$filtro=" where idFam=".$idfam;}
	else
		if($iddep!=0){$filtro=" where idFam IN (SELECT idFam from mrp_familia where idDep=$iddep)";}


	$cbo='<select id="linea" name="linea" onchange="loadproductos('.$iddep.','.$idfam.',this.value);">';
	$cbo.='<option value="0">-Todos-</option>';
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
	$query = mysql_query("select idFormapago id,name nombre  from forma_pago");

    	 //si es simple//
    	 //krmn1
		//if(simple()){
	  //end si es simple//

    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
	}

	 //si es simple//
		//}else {  $cbo.='<option value="1">Efectivo</option>';  }
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
		case "February":  $mes="Febrero"; break;
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
	$cbo='<select id="sucursal" name="sucursal" >';
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

function proveedores($idProd=0)
{
	$filtro="";
	if($idProd!=0){$filtro=" where idPrv IN (select idPrv from mrp_producto_proveedor where idProducto=".$idProd.")";}

	$cbo='<select id="proveedor" name="proveedor" onchange="cargaCosto(this.value,'.$idProd.');">';
	$query = mysql_query("select idPrv id,razon_social nombre  from mrp_proveedor ".$filtro);
    $cbo.='<option value="">-Seleccione-</option>';
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	return $cbo;
}

function cargaCosto($idProv=0,$idProd=0){
	$query = mysql_query("select costo from mrp_producto_proveedor where idPrv=".$idProv." and idProducto=".$idProd.";");
	$row = mysql_fetch_array($query);
	return $row["costo"];
}

/***********************************************FACTURACION ****************************************************/
function datosFacturacion($id)
{
$q=mysql_query("Select
nombre,
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

function Facturar($idFact,$monto,$impuestos,$idVenta,$bloqueo,$laobs)
{
	 	$df=datosFacturacion($idFact);
	 	$idCliente=$df->nombre;
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
	  	$nn = array();
	  	$x=0;
	  	$ivas=0;
		$textodescuento="";
	  	//foreach ($_SESSION['caja'] as $producto) {
		foreach($_SESSION['caja'] as $key=>$producto)
		{
			$nn='';
			$impuestos_venta='';
			if($producto->tipodescuento=="%")
			{
				$descuentogeneral=(($producto->precioventa*$producto->descuento)/100)*$producto->cantidad;
				if($producto->descuento>0)
				{
					$textodescuento.=" - ".cortadec($producto->descuento)." %";
				}
			}
			if($producto->tipodescuento=="$")
			{
				$descuentogeneral=$producto->descuento;
				if($producto->descuento>0)
				{
					$textodescuento.=" - $".cortadec($producto->descuento)."";
				}
			}
			if($producto->unidad=="")
			{
				$uuun='Unidad';
			}else{
				$uuun=$producto->unidad;
			}


		    $conceptosDatos[$x]['Cantidad'] = $producto->cantidad;
		    $conceptosDatos[$x]['Unidad'] = utf8_decode($uuun);
		    $conceptosDatos[$x]['Descripcion'] = utf8_decode($producto->nombre." ".$textodescuento);
		    $conceptosDatos[$x]['Descripcion'] = trim($conceptosDatos[$x]['Descripcion']);
		    $conceptosDatos[$x]['Precio'] = $producto->precioventa-(float)((float)$descuentogeneral/$producto->cantidad);
		    $textodescuento='';
		    $importe=($producto->cantidad*$producto->precioventa);
		    $impfinal=$importe-$descuentogeneral;
		    $impfinalaa=$importe-$descuentogeneral;

			$supertotal+=$impfinal;
		    $conceptosDatos[$x]['Importe'] = $impfinal;
		  	$x++;


				/*IMPUESTOS*/
	$qi=mysql_query("select i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$key." ORDER BY idImpuesto DESC");
		if(mysql_num_rows($qi)>0)
		{
			$ti=1;
			while($ri = mysql_fetch_object($qi))
			{
				//var_dump($ri);
				if(array_key_exists(strtoupper(trim($ri->impuesto)),$nn)){
					if(array_key_exists($ri->valor, $nn[strtoupper(trim($ri->impuesto))] )){
						$nn[strtoupper(trim($ri->impuesto))][$ri->valor]['Valor']=(($impfinalaa*$ri->valor)/100+$nn[strtoupper(trim($ri->impuesto))][$ri->valor]['Valor']);

						$nn2[strtoupper(trim($ri->impuesto))][$ri->valor]['Valor']+=(($impfinalaa*$ri->valor)/100+$nn[strtoupper(trim($ri->impuesto))][$ri->valor]['Valor']);
					}else{
						$nn[strtoupper(trim($ri->impuesto))][$ri->valor]['Valor']=(($impfinalaa*$ri->valor)/100);
						$nn2[strtoupper(trim($ri->impuesto))][$ri->valor]['Valor']+=(($impfinalaa*$ri->valor)/100);
					}
				}else{
					$nn[strtoupper(trim($ri->impuesto))][$ri->valor]['Valor']=(($impfinalaa*$ri->valor)/100);
					$nn2[strtoupper(trim($ri->impuesto))][$ri->valor]['Valor']+=(($impfinalaa*$ri->valor)/100);
					if( strtoupper(trim($ri->impuesto))=='IEPS'){
						$impfinalaa=$impfinalaa+$nn[strtoupper(trim($ri->impuesto))][$ri->valor]['Valor'];
					}
				}


				if(array_key_exists($ri->impuesto,$impuestos_venta))
				{
					if(array_key_exists("IEPS",$impuestos_venta))
					{
						//echo "((".$subtotal."+".$impuestos_venta["IVA"].")*".$ri->valor.")/100+".$impuestos_venta[$ri->impuesto];
						$impuestos_venta[$ri->impuesto]=((($impfinal)*$ri->valor)/100+$impuestos_venta[$ri->impuesto]);
						$impuestos_venta2[$ri->impuesto]=((($impfinal)*$ri->valor)/100+$impuestos_venta[$ri->impuesto]);
					}else
					{
						$impuestos_venta[$ri->impuesto]=((($impfinal+$impuestos_venta["IEPS"])*$ri->valor)/100+$impuestos_venta[$ri->impuesto]);
						$impuestos_venta2[$ri->impuesto]=((($impfinal+$impuestos_venta["IEPS"])*$ri->valor)/100+$impuestos_venta[$ri->impuesto]);
					}
				}
				else
				{
					if(array_key_exists("IEPS",$impuestos_venta))
					{
						//echo "((".$subtotal."+".$impuestos_venta["IVA"].")*)".$ri->valor."/100";
						$sumassiva+=((($impfinal+$impuestos_venta["IEPS"])*$ri->valor)/100);
						$impuestos_venta[$ri->impuesto]=((($impfinal+$impuestos_venta["IEPS"])*$ri->valor)/100);
						if($impuestos_venta2[$ri->impuesto]!=''){
							$impuestos_venta2[$ri->impuesto]+=((($impfinal+$impuestos_venta["IEPS"])*$ri->valor)/100);

						}else{
							$impuestos_venta2[$ri->impuesto]=((($impfinal+$impuestos_venta["IEPS"])*$ri->valor)/100);

						}

					}else
					{

						$impuestos_venta[$ri->impuesto]=(($impfinal*$ri->valor)/100);
						if($impuestos_venta2[$ri->impuesto]!=''){
							$impuestos_venta2[$ri->impuesto]+=(($impfinal*$ri->valor)/100);
						}else{
							$impuestos_venta2[$ri->impuesto]=(($impfinal*$ri->valor)/100);
						}
					}
				}
			}
		}else{
			$ti=0;
		}

			/* END IMPUESTOS*/

		}


		$impuestosDatos=array();
		$calculototalimpuestos=0;
		if($nn2==''){
			$nn2['IVA'][0.00]['Valor']=0.00;
		}

		if($impuestos_venta2==''){
			$impuestos_venta2['IVA']=0.00;
		}
		$impuestos_venta=$impuestos_venta2;
		foreach($impuestos_venta as $impuesto=>$valorimpuesto)
		{
			$calculototalimpuestos+=$valorimpuesto;
			$impuestosDatos[]=array('TipoImpuesto' =>strtoupper($impuesto),'Tasa' => 16,'Importe' => round($valorimpuesto,2));

		}

		if($ti==1){
			$impuestosDatos=array();
			$calculototalimpuestos=0;
			foreach($impuestos_venta as $impuesto=>$valorimpuesto)
			{
				$calculototalimpuestos+=$valorimpuesto;
				$impuestosDatos[]=array('TipoImpuesto' =>strtoupper($impuesto),'Tasa' => 16,'Importe' => round($valorimpuesto,2));

			}
		}else{
			$impuestosDatos[]=array('TipoImpuesto' =>'IVA','Tasa' => 0,'Importe' => 0);
		}

		// Basicos
	  	//===============================================================
	  	$parametros['DatosCFD'] = array();

	  	//Obteniendo la descripcion de la forma de pago
	  	$formapago = "";
	  	$sql = " select name nombre,referencia from venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=".$idVenta;
	  	$qry_vp = mysql_query($sql);
	  	if($ri=mysql_fetch_object($qry_vp))
	  	{
	  		if(strlen($ri->referencia)>0)
			{
	  			$formapago .= $ri->nombre." Ref:".$ri->referencia.",";
			}
			else
			{
				$formapago .= $ri->nombre.",";
			}
		}

		$formapago=substr($formapago,0,strlen($formapago)-1);

		if($formapago==""){
			$formapago=".";
		}



		$Email=$df->correo;

	  	$parametros['DatosCFD']['FormadePago']       = "Pago en una sola exhibicion";
	  	$parametros['DatosCFD']['MetododePago']      = utf8_decode($formapago);
	  	$parametros['DatosCFD']['Moneda']            = "MXP";
	  	$parametros['DatosCFD']['Subtotal']          = $monto;
	  	$parametros['DatosCFD']['Total']             = ($supertotal+$calculototalimpuestos);
	  	$parametros['DatosCFD']['Serie']             = $data['serie'];
		$parametros['DatosCFD']['Folio']             = $data['folio'];
	  	$parametros['DatosCFD']['TipodeComprobante'] = "F"; //F o C
	  	$parametros['DatosCFD']['MensajePDF']        = "";
	  	$parametros['DatosCFD']['LugarDeExpedicion']        = "Mexico";


		unset($_SESSION['pagos-caja']);
		unset($_SESSION['caja']);

		/* FACTURACION AZURIAN
		============================================================== */
		require_once('../../modulos/SAT/config.php');

		date_default_timezone_set("Mexico/General");
		$fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));


		$q3=mysql_query("SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;");
		$r3=mysql_fetch_object($q3);

		$azurian=array();
		if($bloqueo==0){
		$q=mysql_query("SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;");
		if(mysql_num_rows($q)>0){
			$r=mysql_fetch_object($q);

			/* DATOS OBLIGATORIOS DEL EMISOR
			================================================================== */
			$rfc_cliente=$r->rfc;

			$parametros['EmisorTimbre'] = array();
			$parametros['EmisorTimbre']['RFC'] = $r->rfc;
			$parametros['EmisorTimbre']['RegimenFiscal'] = $r->regimenf;
			$parametros['EmisorTimbre']['Pais'] = $r->pais;
			$parametros['EmisorTimbre']['RazonSocial'] = $r->razon_social;
			$parametros['EmisorTimbre']['Calle'] = $r->calle;
			$parametros['EmisorTimbre']['NumExt'] = $r->num_ext;
			$parametros['EmisorTimbre']['Colonia'] = $r->colonia;
			$parametros['EmisorTimbre']['Ciudad'] = $r->ciudad; //Ciudad o Localidad
			$parametros['EmisorTimbre']['Municipio'] = $r->municipio;
			$parametros['EmisorTimbre']['Estado'] = $r->estado;
			$parametros['EmisorTimbre']['CP'] = $r->cp;
			$cer_cliente=$pathdc.'/'.$r->cer;
			$key_cliente=$pathdc.'/'.$r->llave;
			$pwd_cliente=$r->clave;

		}else{

			$JSON = array('success' =>0,
				'error'=>1001,
				'mensaje'=>'No existen datos de emisor.');
			echo json_encode($JSON);
			exit();

		}
		}

		/* CORREO RECEPTOR
		============================================================== */
		$nn=$nn2;
		$azurian['nn']['nn']        = $nn;
		$azurian['org']['logo']        = $r3->logoempresa;

		/* CORREO RECEPTOR
		============================================================== */
		$azurian['Correo']['Correo']        = $Email;
		$azurian['Observacion']['Observacion']        = $laobs;

		/* Datos Basicos
		============================================================== */
		$azurian['Basicos']['Moneda']=$parametros['DatosCFD']['Moneda'];
		$azurian['Basicos']['metodoDePago']=$parametros['DatosCFD']['MetododePago'];
		$azurian['Basicos']['LugarExpedicion']=$parametros['DatosCFD']['LugarDeExpedicion'];
		$azurian['Basicos']['version']='3.2';
		$azurian['Basicos']['serie']=$parametros['DatosCFD']['Serie']; //No obligatorio
		$azurian['Basicos']['folio']=$parametros['DatosCFD']['Folio']; //No obligatorio
		$azurian['Basicos']['fecha']=$fecha;
		$azurian['Basicos']['sello']='';
		$azurian['Basicos']['formaDePago']=$parametros['DatosCFD']['FormadePago'];
		$azurian['Basicos']['tipoDeComprobante']='ingreso';
		$azurian['Basicos']['noCertificado']='';
		$azurian['Basicos']['certificado']='';
		$azurian['Basicos']['subTotal']=number_format($parametros['DatosCFD']['Subtotal'],2,'.','');
		$azurian['Basicos']['total']=number_format($parametros['DatosCFD']['Total'],2,'.','');

		/* Datos Emisor
		============================================================== */

		$azurian['Emisor']['rfc']=strtoupper($parametros['EmisorTimbre']['RFC']);
		$azurian['Emisor']['nombre']=strtoupper($parametros['EmisorTimbre']['RazonSocial']);

		/* Datos Fiscales Emisor
		============================================================== */

		$azurian['FiscalesEmisor']['calle']=$parametros['EmisorTimbre']['Calle'];
		$azurian['FiscalesEmisor']['noExterior']=$parametros['EmisorTimbre']['NumExt'];
		$azurian['FiscalesEmisor']['colonia']=$parametros['EmisorTimbre']['Colonia'];
		$azurian['FiscalesEmisor']['localidad']=$parametros['EmisorTimbre']['Ciudad'];
		$azurian['FiscalesEmisor']['municipio']=$parametros['EmisorTimbre']['Municipio'];
		$azurian['FiscalesEmisor']['estado']=$parametros['EmisorTimbre']['Estado'];
		$azurian['FiscalesEmisor']['pais']=$parametros['EmisorTimbre']['Pais'];
		$azurian['FiscalesEmisor']['codigoPostal']=$parametros['EmisorTimbre']['CP'];

		/* Datos Regimen
		============================================================== */

		$azurian['Regimen']['Regimen']=$parametros['EmisorTimbre']['RegimenFiscal'];

		/* Datos Receptor
		============================================================== */

		$azurian['Receptor']['rfc']=strtoupper($parametros['Receptor']['RFC']);
		$azurian['Receptor']['nombre']=strtoupper($parametros['Receptor']['RazonSocial']);

		/* Datos Domicilio Receptor
		============================================================== */

		$azurian['DomicilioReceptor']['calle']=$parametros['Receptor']['Calle'];
		$azurian['DomicilioReceptor']['noExterior']=$parametros['Receptor']['NumExt'];
		$azurian['DomicilioReceptor']['colonia']=$parametros['Receptor']['Colonia'];
		$azurian['DomicilioReceptor']['localidad']=$parametros['Receptor']['Ciudad'];
		$azurian['DomicilioReceptor']['municipio']=$parametros['Receptor']['Municipio'];
		$azurian['DomicilioReceptor']['estado']=$parametros['Receptor']['Estado'];
		$azurian['DomicilioReceptor']['pais']=$parametros['Receptor']['Pais'];
		$azurian['DomicilioReceptor']['codigoPostal']=$parametros['Receptor']['CP'];

		$conceptosOri='';
		$conceptos='';

		foreach ($conceptosDatos as $key => $value) {
			$conceptosOri.='|'.$value['Cantidad'].'|';
			$conceptosOri.=$value['Unidad'].'|';
			$conceptosOri.=$value['Descripcion'].'|';
			$conceptosOri.=$value['Precio'].'|';
			$conceptosOri.=$value['Importe'];
			$conceptos.="<cfdi:Concepto cantidad='".$value['Cantidad']."' unidad='".$value['Unidad']."' descripcion='".$value['Descripcion']."' valorUnitario='".$value['Precio']."' importe='".$value['Importe']."'/>";
		}



		$ivas='';
		$tisr=0.00;
		$tiva=0.00;
		$tieps=0.00;

		$oriisr='';
		$oriiva='';

		$isr='';
		$iva='';
		$azurian['Conceptos']['conceptos']=$conceptos;
		$azurian['Conceptos']['conceptosOri']=$conceptosOri;

		$traslads='';
		$retenids='';
		$haytras=0;
		$hayret=0;
		$trasladsimp=0.00;
		$retenciones=0.00;
		$trasxml='';
		$retexml='';

		foreach ($nn as $clave => $imm) {
			if($clave=='IEPS' || $clave=='IVA'){

				$haytras=1;
				foreach ($nn[$clave] as $clavetasa => $val) {
					if($clave=='IEPS'){
						$tieps+=number_format($val['Valor'],2,'.','');
					}
					if($clave=='IVA'){
						$tiva+=number_format($val['Valor'],2,'.','');
					}
					$traslads.='|'.$clave.'|';
					$traslads.=''.$clavetasa.'|';
					$traslads.=number_format($val['Valor'],2,'.','');
					$trasladsimp+=number_format($val['Valor'],2,'.','');
					$trasxml.="<cfdi:Traslado impuesto='".$clave."' tasa='".$clavetasa."' importe='".number_format($val['Valor'],2,'.','')."' />";
				}

			}elseif($clave=='ISR'){
				$hayret=1;
				foreach ($nn[$clave] as $clavetasa => $val) {
					$tisr+=number_format($val['Valor'],2,'.','');
					$retenids.='|'.$clave.'|';
					$retenids.=''.number_format($val['Valor'],2,'.','').'|';
					$retenids.=number_format($val['Valor'],2,'.','');
					$retenciones+=number_format($val['Valor'],2,'.','');
					$retexml.="<cfdi:Retencion impuesto='".$clave."' importe='".number_format($val['Valor'],2,'.','')."' />";
				}
			}
		}
		$azurian['Impuestos']['totalImpuestosIeps']=$tieps;
		if($haytras==1){
			$iva.='<cfdi:Traslados>'.$trasxml.'</cfdi:Traslados>';
		}else{
			$traslads.='|IVA|';
			$traslads.='0.00|';
			$traslads.='0.00';
			$trasladsimp='0.00';
			$iva.="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='0.00' importe='0.00' /></cfdi:Traslados>";
		}
		if($hayret==1){
			$isr.='<cfdi:Retenciones>'.$retexml.'</cfdi:Retenciones>';
		}
		//echo $iva.'  '.$isr; exit();
	/*	foreach ($impuestosDatos as $key => $value) {


			if($value['TipoImpuesto']=='ISR' || $value['TipoImpuesto']=='isr' || $value['TipoImpuesto']=='Isr'){
				$isr="<cfdi:Retenciones><cfdi:Retencion impuesto='ISR' importe='";
				$tisr=($value['Importe']*1)+($tisr*1);
				$oriisr='|ISR|';
				$oriisr.=number_format($tisr,2,'.','').'|';
				$oriisr.=number_format($tisr,2,'.','');
			}

			if($value['TipoImpuesto']=='IVA' || $value['TipoImpuesto']=='iva' || $value['TipoImpuesto']=='Iva'){
				$iva="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16' importe='";
				$tiva=($value['Importe']*1)+($tiva*1);
				$oriiva='|IVA|';
				$oriiva.='16|';
				$oriiva.=number_format($tiva,2,'.','').'|';
				$oriiva.=number_format($tiva,2,'.','');
			}

			if($value['TipoImpuesto']=='IVA' || $value['TipoImpuesto']=='iva' || $value['TipoImpuesto']=='Iva'){
				$iva="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16' importe='";
				$tiva=($value['Importe']*1)+($tiva*1);
				$oriiva='|IVA|';
				$oriiva.='16|';
				$oriiva.=number_format($tiva,2,'.','').'|';
				$oriiva.=number_format($tiva,2,'.','');
			}
		}
*/

		$azurian['Impuestos']['isr']=$retenids;
		$azurian['Impuestos']['iva']=$traslads.'|'.number_format($trasladsimp,2,'.','');

		$azurian['Impuestos']['totalImpuestosRetenidos']=number_format($retenciones,2,'.','');
		$azurian['Impuestos']['totalImpuestosTrasladados']=number_format($trasladsimp,2,'.','');


		/*if($iva!=''){
			$iva.=number_format($tiva,2,'.','')."'"." /></cfdi:Traslados>";
		}
		if($isr!=''){
			$isr.=number_format($tisr,2,'.','')."'"." /></cfdi:Retenciones>";
		}*/
		$ivas.=$isr.$iva;

		$azurian['Impuestos']['ivas']=$ivas;
		$azurian['Venta']['venta']=$idVenta;
		unset($_SESSION['pagos-caja']);
		unset($_SESSION['caja']);


		require_once('../../modulos/lib/nusoap.php');
		require_once('../../modulos/SAT/funcionesSAT.php');

		//require_once('../../modulos/WS_facturacion.php');
}

/**/

/*****************************************************************************************************************************************/
function pendienteFacturacion($idFacturacion,$monto,$cliente,$idventa,$trackId,$azurian){
	$azurian=base64_encode($azurian);
	$fechaactual=date('Y-m-d H:i:s');

	if(is_numeric($cliente)){
		mysql_query("insert into pvt_pendienteFactura values('',".$idventa.",'".$fechaactual."',".$cliente.",'".$monto."',0,'".$trackId."','".$azurian."');");
	}
	else{
		mysql_query("insert into pvt_pendienteFactura values('',".$idventa.",'".$fechaactual."',NULL,'".$monto."',0,'".$trackId."','".$azurian."');");
	}
}

function guardarFacturacion($UUID,$noCertificadoSAT,$selloCFD,$selloSAT,$FechaTimbrado,$idComprobante,$idFact,$idVenta,$noCertificado,$tipoComp,$monto,$cliente,$trackId,$idRefact,$azurian){
	$azurian=base64_encode($azurian);
	$fechaactual=preg_replace('/T/', ' ', $FechaTimbrado);
	if($idRefact=='c'){
		$tipoComp='C';
		$q=mysql_query("UPDATE pvt_respuestaFacturacion SET borrado=2 WHERE idSale='$idVenta'");
	}
	$q=mysql_query("INSERT INTO pvt_respuestaFacturacion
	(idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,borrado,tipoComp,idComprobante,cadenaOriginal) VALUES ('".$idVenta."','".$idFact."','".$UUID."','".$trackId."','".$noCertificadoSAT."','".$noCertificado."','".$selloSAT."','".$selloCFD."','".$fechaactual."',0,'".$tipoComp."','".$idComprobante."','".$azurian."');");
	$insertedId=mysql_insert_id();
	if(is_numeric($insertedId))
	{
			mysql_query("UPDATE pvt_contadorFacturas set total=total+1 where id=1");
			mysql_query("UPDATE pvt_serie_folio SET folio=folio+1 where id=1");
	}

	if(preg_match('/all/', $idRefact)){
		$idRefact=preg_replace('/all/', '', $idRefact);
		mysql_query("UPDATE pvt_pendienteFactura SET facturado=1 WHERE id_sale in (".$idRefact.")");
	}

	if($idRefact>0 && $idRefact!='c'){
		mysql_query("UPDATE pvt_pendienteFactura SET facturado=1 WHERE id_sale='$idRefact'");
	}

	return $insertedId;
}

/*****************************************************************************************************************************************/


function vendedores()
{
	$cbo='<select id="vendedor" name="vendedor" >';
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
	$cbo='<select id="cliente" name="cliente">';
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
	$q=mysql_query("select vp.monto, fp.name nombre from venta_pagos vp inner join venta v on v.idVenta=vp.idVenta inner join forma_pago fp on vp.idFormapago=fp.idFormapago
where v.idVenta=".$id);
	return $q;
}

function productosventa($id)
{
	$q=mysql_query("select p.descorta ,p.idProducto,p.codigo,p.nombre,vp.preciounitario,vp.cantidad,vp.montodescuento,vp.total,vp.impuestosproductoventa,vp.comentario from venta_producto vp inner join mrp_producto p on vp.idProducto=p.idProducto where vp.idVenta=".$id);
	return $q;
}

function datosorganizacion()
{
	$q=mysql_query("select * from organizaciones c left join estados e on e.idestado=c.idestado left join municipios m on m.idmunicipio=c.idmunicipio
 where idorganizacion=1");
	return mysql_fetch_object($q);
}


function datossucursal($id_empleado)
{
		$q=mysql_query("select * from administracion_usuarios adm, mrp_sucursal suc where adm.idSuc = suc.idSuc and adm.idempleado = " . $id_empleado);
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
			<td><label>Saldo actual en caja</label></td>
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
			<td><label>Selecciona la sucursal que esta operando</label></td>
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

//Validamos que este activo el menu propina
function propina()
{
	$propina=0;
	$q=mysql_query("select a.idperfil from accelog_perfiles_me a where idmenu=1601");
	if(mysql_num_rows($q)>0)
	{
		$propina=1;
	}
	return $propina;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
function checatimbres()
{

		/*$ct=mysql_query("select total from pvt_contadorFacturas");
		while($rct=mysql_fetch_object($ct))
		{
			$numfacturas=$rct->total;
		}
		if($numfacturas>99)
		{
				return 1;
		}
		else{ return 0;}*/

		return 0;
}

function nuevaVenta()
{
	unset($_SESSION['pagos-caja']);
	unset($_SESSION['caja']);
}

function suspenderVenta($pagoautomatico,$impuestos,$idFact,$sucursal,$almacen,$doc,$monto,$cambio,$cliente,$empleado,$totalimpuestos,$s_id,$nombre)
{
	$q=mysql_query("select id,s_almacen from venta_suspendida where id='$s_id'");
	if(mysql_num_rows($q)>0)
	{
		$r=mysql_fetch_assoc($q);
		$result=mysql_query("UPDATE venta_suspendida SET borrado=1 WHERE id='$s_id'");

		foreach ($_SESSION['caja'] as $key => $value) {
		$result=mysql_query("UPDATE mrp_stock SET cantidad=cantidad+".$value->cantidad." WHERE idProducto='$key' AND idAlmacen='".$r['s_almacen']."'");
		}
	}

	date_default_timezone_set("Mexico/General");
	$fechaactual=date("Y-m-d H:i:s");

	$monto=str_replace(",","",$monto);
	$cambio=str_replace(",","",$cambio);
	$impuestos=str_replace(",","",$impuestos);
	$arr=json_encode($_SESSION['caja']);
	$arr2=json_encode($_SESSION['pagos-caja']);

	if($idFact==''){
		$idFact=0;
	}
	$result=mysql_query("INSERT INTO venta_suspendida (s_almacen,s_cambio,s_cliente,s_documento,s_empleado,s_funcion,s_idFact,s_impuestos,s_monto,s_pagoautomatico,s_sucursal,s_impuestost,arreglo1,arreglo2,identi,fecha) VALUES ('".$almacen."','".$cambio."',".$cliente.",'".$doc."',".$empleado.",'suspenderVenta',".$idFact.",'".$impuestos."','".$monto."',0,'".$sucursal."','".$totalimpuestos."','".$arr."','".$arr2."','".$nombre." - ".$fechaactual." - $".$monto."','".$fechaactual."');");

	foreach ($_SESSION['caja'] as $key => $value) {
		$result=mysql_query("UPDATE mrp_stock SET cantidad=cantidad-".$value->cantidad." WHERE idProducto='$key' AND idAlmacen='$almacen'");
	}

	unset($_SESSION['pagos-caja']);
	unset($_SESSION['caja']);

}

function elimina_suspendida($id_suspendida){

	$q=mysql_query("SELECT arreglo1, s_almacen from venta_suspendida where id='$id_suspendida' ");
	if(mysql_num_rows($q)>0){
		$r=mysql_fetch_assoc($q);
		$json=json_decode($r['arreglo1'],true);
	}
	mysql_query("UPDATE venta_suspendida SET borrado=1 WHERE borrado=0 AND id='$id_suspendida';");
	foreach ($json as $key => $value) {
		$result=mysql_query("UPDATE mrp_stock SET cantidad=cantidad+".$value['cantidad']." WHERE idProducto='$key' AND idAlmacen='".$r['s_almacen']."' ");
	}

}

function datosSuspendida($id_suspendida)
{

	$q=mysql_query("select a.*, b.nombre from venta_suspendida a inner join comun_cliente b on b.id=a.s_cliente WHERE a.id='$id_suspendida';");
		if(mysql_num_rows($q)>0)
		{
			$exist_vS=1;
			$arr_vS= array();
			while($r=mysql_fetch_assoc($q))
			{
				$arr_vS[]=$r;
			}
		}

$arr1 = json_decode($arr_vS[0]['arreglo1']);
$arr2 = json_decode($arr_vS[0]['arreglo2']);

$s_cants=array();
$s_descs=array();
$s_prods='';


foreach ($arr1 as $key => $value) {
	$s_prods.=$value->id.',';
	$s_cants[]=$value->cantidad;
	$s_descs[]=$value->descuento;
	$s_tdescs[]=$value->tipodescuento;
}
$s_prods=trim($s_prods,',');

$s_prods2='';
foreach ($arr2 as $key => $value) {
	if($key>0){
		//echo utf8_decode($value);
		$s_prods2.=utf8_decode($value).',';
	}
}
$s_prods2=trim($s_prods2,',');

	$JSON=array("success"=>1, "datos"=>$arr_vS, "arr1"=>$s_prods, "arr2"=>$s_prods2, "s_cants"=>$s_cants, "s_descs"=>$s_descs, "s_tdescs"=>$s_tdescs);
	return  json_encode($JSON);
}

function object_to_array($data){
    if (is_array($data) || is_object($data))
      {
          $result = array();
          foreach ($data as $key => $value){
              $result[$key] = object_to_array($value);
          }
          return $result;
      }
      return $data;
 }

function envioFactura($uid,$Email,$azurian)
{




		$azurian=json_decode($azurian);
		$azurian=object_to_array($azurian);
		//var_dump($azurian);
		$datosTimbrado=$azurian['datosTimbrado'];

		if($azurian['FiscalesEmisor']['noExterior']==''){
				$nemi='';
			}else{
				$nemi=' #'.$azurian['FiscalesEmisor']['noExterior'];
			}

			if($azurian['DomicilioReceptor']['noExterior']==''){
				$nrec='';
			}else{
				$nrec=' #'.$azurian['DomicilioReceptor']['noExterior'];
			}

		mysql_query("UPDATE venta SET observacion='".$azurian['Observacion']['Observacion']."' WHERE idVenta='".$azurian['Venta']['venta']."'; ");

		include "../../modulos/SAT/PDF/CFDIPDF.php";
		//var_dump($datosTimbrado);
		//var_dump($azurian);
		//exit();
		$obj=new CFDIPDF();
		//$obj->ponerColor('#333333');
		$obj->datosCFD($datosTimbrado['UUID'],$azurian['Basicos']['folio'],$datosTimbrado['noCertificado'],$datosTimbrado['FechaTimbrado'],$datosTimbrado['FechaTimbrado'],$datosTimbrado['noCertificadoSAT'],$azurian['Basicos']['formaDePago'],$azurian['Basicos']['tipoDeComprobante']);
		$obj->lugarE($azurian['Basicos']['LugarExpedicion']);
		$obj->datosEmisor($azurian['Emisor']['nombre'],$azurian['Emisor']['rfc'],$azurian['FiscalesEmisor']['calle'].$nemi,$azurian['FiscalesEmisor']['localidad'],$azurian['FiscalesEmisor']['colonia'],$azurian['FiscalesEmisor']['municipio'],$azurian['FiscalesEmisor']['estado'],$azurian['FiscalesEmisor']['codigoPostal'],$azurian['FiscalesEmisor']['pais'],$azurian['Regimen']['Regimen']);
		$obj->datosReceptor($azurian['Receptor']['nombre'],$azurian['Receptor']['rfc'],$azurian['DomicilioReceptor']['calle'].$nrec,$azurian['DomicilioReceptor']['localidad'],$azurian['DomicilioReceptor']['colonia'],$azurian['DomicilioReceptor']['municipio'],$azurian['DomicilioReceptor']['estado'],$azurian['DomicilioReceptor']['codigoPostal'],$azurian['DomicilioReceptor']['pais']);
		$obj->agregarConceptos($azurian['Conceptos']['conceptosOri']);
		$obj->agregarTotal($azurian['Basicos']['subTotal'],$azurian['Basicos']['total'],$azurian['nn']['nn']);
		$obj->agregarMetodo($azurian['Basicos']['metodoDePago'],'',$azurian['Basicos']['total']);
		$obj->agregarSellos($datosTimbrado['csdComplemento'],$datosTimbrado['selloCFD'],$datosTimbrado['selloSAT']);
		$obj->agregarObservaciones($azurian['Observacion']['Observacion']);
		$obj->generar("../../netwarelog/archivos/1/organizaciones/".$azurian['org']['logo']."",0);
		$obj->borrarConcepto();

	if($Email!=''){

		require_once('../../modulos/phpmailer/sendMail.php');

	    $mail->From = "mailer@netwarmonitor.com";
	    $mail->FromName = "NetwareMonitor";
	    $mail->Subject = "Factura Generada";
	    $mail->AltBody = "NetwarMonitor";
	    $mail->MsgHTML('Factura Generada');
	    $mail->AddAttachment('../../modulos/facturas/'.$uid.".xml");
	    $mail->AddAttachment('../../modulos/facturas/'.$uid.".pdf");
	    $mail->AddAddress($Email,$Email);

	    @$mail->Send();
	}
	exit();
}

function cancelarVentaActual()
{
	unset($_SESSION['pagos-caja'],$_SESSION['caja']);
}

function agregarPropina($idArticulo,$propina,$almacen)
{
	session_start();
	$arrayPropina = new stdClass();

	$datosPropina = 'Select codigo,nombre from mrp_producto where idProducto = '.$idArticulo.'';

	$result = mysql_query($datosPropina);


	while ($row = mysql_fetch_array($result, MYSQLI_ASSOC))
		{
			$rows[] = $row;
		}

	$arrayPropina->id = $idArticulo;
    $arrayPropina->nombre = $rows[0]["nombre"];
    $arrayPropina->codigo = $rows[0]["codigo"];
    $arrayPropina->precioventa = $propina;
    $arrayPropina->esreceta = 0;
    $arrayPropina->eskit = 0;
    $arrayPropina->unidad = '';
    $arrayPropina->cantidad = '1.00';
    $arrayPropina->descuento = 0;
    $arrayPropina->tipodescuento = 0;
    $arrayPropina->arr_kit = '';
    $arrayPropina->impuesto = 0;
    $arrayPropina->suma_impuestos = 0;


    $_SESSION['caja'][$idArticulo]=$arrayPropina;

    //print_r($_SESSION['caja']);
    return imprimecaja(0,$almacen,0);

    //return true;
}
function datosretiro($idretiro){

	$datosRetiro = "SELECT r.id,r.cantidad,r.concepto, u.usuario, r.fecha from venta_retiro_caja r, accelog_usuarios u where r.idempleado=u.idempleado and id=".$idretiro;
	$q=mysql_query($datosRetiro);
	return mysql_fetch_object($q);

}

?>
