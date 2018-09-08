<?php 
  	include("../../../netwarelog/webconfig.php");

    $idEmpleado=$_SESSION['accelog_idempleado'];
    $conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
    $id_orden=$_POST['ido'];
    $cad=$_POST['cad'];

    $ori=$conection->query("SELECT idOferta,idUnidad,idProducto,precio,cantidad FROM sms_oferta WHERE idOferta='$id_orden' LIMIT 1;");
    if($ori->num_rows>=1){
        $row=$ori->fetch_array(MYSQLI_ASSOC);
        $idProducto=$row['idProducto'];
        $precio=$row['precio'];
        $cantidad=$row['cantidad'];
    }

    $result=$conection->query("SELECT ms.idAlmacen FROM administracion_usuarios au, mrp_sucursal ms WHERE au.idempleado=".$idEmpleado." and au.idSuc=ms.idSuc LIMIT 1");
    if($result->num_rows>=1){
      $row1=$result->fetch_array(MYSQLI_ASSOC); 
      $idAlmacen=$row1['idAlmacen'];
    } 

    date_default_timezone_set("Mexico/General");
    $fecha=date("Y-m-d H:i:s");
    $e=0;

    $exp3=explode('-', $cad);
    $id_cliente=$exp3[0];
    $id_factura=$exp3[1];

    if($id_factura=="")
        $bloqueo=1;
    else
        $bloqueo=0;

    $conection->autocommit(FALSE); 
    $ori=$conection->query("INSERT INTO venta (idCliente, monto, estatus, idEmpleado, documento, fecha, cambio, montoimpuestos, idSucursal, observacion,rfc) VALUES ('".$id_cliente."','".$precio."',1,'".$idEmpleado."',1,'".$fecha."',0.00,0.00,1,'SMS rutas','');");
    $id_venta=$conection->insert_id;
    if(!$ori){$e++;}

    $ori=$conection->query("INSERT INTO venta_pagos (idVenta, idFormapago, monto, referencia) VALUES ('".$id_venta."',1,'".$precio."','');");
    if(!$ori){$e++;}

    $ori=$conection->query("INSERT INTO venta_producto (idProducto, cantidad, preciounitario, tipodescuento, descuento, subtotal, idVenta, impuestosproductoventa, montodescuento, total, arr_kit, comentario) VALUES ('".$idProducto."',1,'".$precio."','$',0.00,'".$precio."','".$id_venta."',0.00,0.00,'".$precio."','NULL','SMS rutas');");
    if(!$ori){$e++;}

    $ori=$conection->query("UPDATE mrp_stock SET cantidad=cantidad-".$cantidad.", ocupados=ocupados-".$cantidad." WHERE idProducto=".$idProducto." and idAlmacen=".$idAlmacen);
    if(!$ori){$e++;}

    if($e>0){
        $conection->rollback();
        $JSON = array('success' =>0,
            'error'=>771, 
            'mensaje'=>'No existen datos de emisor.',
            'bloqueo'=>$bloqueo, 
            'idVenta'=>$id_venta, 
            'idCliente'=>$id_cliente,
            'idProd'=>$idProducto,
            'precio'=>$precio,
            //'cantidad'=>$cantidad, 
            'cantidad'=>1,
            'idFact'=>'',
            'carpeta'=>$carpeta);
        echo json_encode($JSON);
        exit();
    }else{
        $conection->commit();

        //carpeta temporal
        $carpeta=$_POST['carpeta'];
        if($carpeta==""){
            $carpeta="comprimido".time();
            mkdir($carpeta);
        }
        //

        //ticket pdf
        include "../../SAT/PDF/html2pdf/html2pdf.class.php";
        $objPdf = new HTML2PDF('P', 'A4', 'fr');
        $objPdf->WriteHTML(ticket($id_venta));
        $objPdf->Output($carpeta."/prueba_fer".time().".pdf","F");
        //

        $JSON = array('success' =>1,
            'error'=>'', 
            'mensaje'=>'Venta realizada con exito',
            'bloqueo'=>$bloqueo, 
            'idVenta'=>$id_venta,
            'idCliente'=>$id_cliente,
            'idProd'=>$idProducto, 
            'precio'=>$precio,
            //'cantidad'=>$cantidad,
            'cantidad'=>1,
            'idFact'=>$id_factura,
            'carpeta'=>$carpeta);
        echo json_encode($JSON);
        exit();
    }

    function ticket($idventa){
        include_once("../../../netwarelog/catalog/conexionbd.php"); 
        include("../../../modulos/punto_venta/funcionesPv.php");

        $organizacion=datosorganizacion();
        $venta=datosventa($idventa);
        $productos=productosventa($idventa);
        $pagos=pagos($idventa);
        $impuestos_venta=array();
        
        $content='<div id="receipt_wrapper"><div id="receipt_header"><div id="company_name">'.$organizacion->nombreorganizacion.'</div><div id="company_address">'.utf8_decode($organizacion->domicilio." ".$organizacion->municipio.",".$organizacion->estado).'</div>';
        if(strcmp($venta->estatus,"Cancelada")==0)
            $content.='<div id="company_phone">Venta '.$venta->estatus.'</div>';
                
        $content.='<div id="sale_receipt">'.$organizacion->RFC.'</div><div id="sale_receipt">Ticket de compra</div><div id="sale_time">'.formatofecha($venta->fecha).'</div></div><div id="receipt_general_info"><div id="customer">Cliente:'.$venta->cliente.'</div><div id="sale_id">Id venta:'.$venta->folio.'</div><div id="employee">Empleado:'.$venta->empleado.'</div></div><table id="receipt_items" border=0><tr><th style="width:16%;text-align:center;">Cantidad</th><th style="width:25%;">Producto</th><th style="width:17%;text-align:right;">Total</th></tr>';
        $total=0; 
        while($producto=mysql_fetch_object($productos)){    
            $impuestos_venta='';
            $stotal=$producto->total;
            $total+=$stotal;
            
            $qi=mysql_query("select pi.idimpuesto,i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=".$producto->idProducto.' order by pi.idimpuesto DESC');
            if(mysql_num_rows($qi)>0){
                while($ri = mysql_fetch_object($qi)){
                    $suma_impuestos =  $ri->valor;
                    if($ri->impuesto == 'IEPS'){
                        $calculos = str_replace(",", "", number_format(((($producto->preciounitario * $producto->cantidad  - str_replace(",", "",$producto->montodescuento)  )* $ri->valor)) / 100, 2));
                        $impuestos_venta2[$ri->impuesto] += $calculos;
                        $ieps = $calculos;
                    }else{
                        if($ieps != 0) {
                            $impuestos_venta2[$ri->impuesto] +=  str_replace(",", "", number_format((((($producto->preciounitario  * str_replace(",", "",$producto->cantidad)) + $ieps  - $producto->montodescuento) * $ri->valor) ) / 100, 2));
                        } else {
                            $impuestos_venta2[$ri->impuesto] += str_replace(",", "", number_format(((($producto->preciounitario  * $producto->cantidad - str_replace(",", "",$producto->montodescuento)) * $ri->valor)) / 100, 2));
                        }
                    }
                }
            }
            
            if(strlen($producto->descorta)>0)
                $descripcion=$producto->descorta." ".$descripcion=$producto->comentario; 
            else
                $descripcion=$producto->nombre." ".$descripcion=$producto->comentario;

            $content.='<tr><td style="text-align:center">'.$producto->cantidad.'</td><td style="text-align:center"><span class="long_name">'.utf8_decode(substr($descripcion,0,20)).'</span></td><td style="text-align:right">$'.number_format(($producto->preciounitario*$producto->cantidad)-$producto->montodescuento,2,".",",").'</td></tr>';

            if($producto->montodescuento>0){
                $content.='<tr><td style="text-align:center">Desc:</td><td style="text-align:center">$'.number_format($producto->montodescuento,2,".",",").'</td></tr>';
            }
        }
        $content.='<tr><td colspan="2" style="text-align:right;border-top:2px solid #000000"><b>Subtotal:</b></td><td colspan="1" style="text-align:right;border-top:2px solid #000000">$'.number_format($total,2,".",",").'</td></tr>';

        $totalimpuestos=0;
        if($impuestos_venta2=='')
            $impuestos_venta2['IVA']=0.00;

        $impuestos_venta=$impuestos_venta2;
        foreach($impuestos_venta as $impuesto=>$valorimpuesto){
            $totalimpuestos+=$valorimpuesto;
            $content.='<tr><td colspan="2" style="text-align:right"><b>'.$impuesto.'</b></td><td colspan="1" style="text-align:right">$'.number_format($valorimpuesto,2,".",",").'</td></tr>';  
        }

        $content.='<tr><td colspan="2" style="text-align:right"><b>Total:</b></td><td colspan="1" style="text-align:right">$'.number_format($total+$totalimpuestos,2,".",",").'</td></tr><tr><td colspan="6">&nbsp;</td></tr>';

        while($pago=mysql_fetch_object($pagos)){
            $content.='<tr><td colspan="2" style="text-align:right;"><b>'.utf8_decode($pago->nombre).'</b></td><td colspan="1" style="text-align:right">$'.number_format($pago->monto,2,".",",").'</td></tr>';
        }

        $content.='<tr><td colspan="6">&nbsp;</td></tr><tr><td colspan="2" style="text-align:right"><b>Cambio</b></td><td colspan="1" style="text-align:right">$'.number_format($venta->cambio,2,".",",").'</td></tr></table></div>';
        return $content;
    }
 ?>