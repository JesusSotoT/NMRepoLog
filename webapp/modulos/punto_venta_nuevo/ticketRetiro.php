<?php 
error_reporting(0);
$idretiro=$_REQUEST['idretiro'];
include("funcionesConsulta.php"); 
$organizacion=datosorganizacion();
$retiro=datosretiro($idretiro);
header('Content-Type: text/html; charset=utf-8');
?>
<meta charset="UTF-8">
<link rel="stylesheet" rev="stylesheet" href="css/netpos.css" />
<link rel="stylesheet" rev="stylesheet" href="css/netpos_print.css"  media="print"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script id="scriptAccion" type="text/javascript"> 
$(document).ready(function() {
    window.print();
    
});
</script> 
<style>
#letraschicas{
    font-size: 10px;

}
.small_button a{
    color:white;
    text-decoration:none;
    font-family:Arial, Helvetica, sans-serif;
}

@media print
{
    .item_number{display:none;}
}
</style>
<div id="receipt_wrapper">
    <div id="logo">
        <?php 
            $imagen='../../netwarelog/archivos/1/organizaciones/'.$organizacion->logoempresa;
            $imagesize=getimagesize($imagen);
            $porcentaje=0;
            if($imagesize[0]>200 && $imagesize[1]>90){
                if($imagesize[0]>$imagesize[1]){
                    $porcentaje=intval(($imagesize[1]*100)/$imagesize[0]);
                    $imagesize[0]=200;
                    $imagesize[1]=(($porcentaje*200)/100);
                }else{
                    $porcentaje=intval(($imagesize[0]*100)/$imagesize[1]);
                    $imagesize[0]=200;
                    $imagesize[1]=(($porcentaje*200)/100);  
                }
            }
            //"../../netwarelog/archivos/1/organizaciones/'.$cliente[0]->logoempresa.'"
            $src="";
            if($imagen!="" && file_exists($imagen))
                $src='<img src="'.$imagen.'" style="width:'.$imagesize[0].'px;height:'.$imagesize[1].'px;display:block;margin:0 auto 0 auto;"/>';
            echo $src;
        ?>
    
    </div>
    <div id="receipt_header" style="text-align:center;">
        <div id="company_name"><?php echo $organizacion->nombreorganizacion;?></div>
        <div id="company_address"><?php echo utf8_decode($organizacion->domicilio." ".$organizacion->municipio.",".$organizacion->estado);?></div>
        
            <?php if(strcmp($venta->estatus,"Cancelada")==0){?>
            <div id="company_phone">        
                <?php echo "Venta ".$venta->estatus;?>
            </div>
            <?php
        }  ?>

        <div id="sale_receipt"><?php echo  $organizacion->RFC;?></div>  
        <div id="sale_receipt"><h2>Ticket Retiro de Caja</h2></div>
        <div id="sale_time"><!--Fecha y hora--><?php echo formatofecha($retiro->fecha);?></div>
    </div>
    <div id="receipt_general_info" style="text-align:center;">
        <div id="sucursal">Sucursal:<?php echo $_SESSION["sucursalNombre"]; ?></div>
        <div id="sale_id">Id Retiro:<?php  echo $retiro->id; ?></div>
        <div id="employee"><h3>Empleado:</h3><h4><?php  echo $retiro->usuario; ?></h4></div>
    </div>

    <table id="receipt_items" border='0'>   
        <tr>
            <th style="width:16%;text-align:center;">Concepto</th>
            <th style="width:16%;text-align:right;">Cantidad</th>
        </tr>
        <tr>
            <td style='text-align:center;'><?php echo $retiro->concepto; ?></td>
            <td style='text-align:right;'>$<?php echo $retiro->cantidad; ?></td>
        </tr>
        <tr>
            <td colspan="1" style='text-align:right;border-top:2px solid #000000;'></td>
            <td colspan="1" style='text-align:right;border-top:2px solid #000000;'><b>Total:</b>$<?php echo number_format($retiro->cantidad,2,".",","); ?></td>
        </tr>
        <tr>
             <td colspan="1" style='text-align:right;border-top:2px solid #000000;'></td>
             <td colspan="1" style='text-align:right;border-top:2px solid #000000;'></td>
        </tr>
    </table>   
</div>    