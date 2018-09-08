<?php 
error_reporting(0);
//include("../../netwarelog/webconfig.php");
//include("../../punto_venta_nuevo/funcionesConsulta.php"); 
include_once("../../../netwarelog/catalog/conexionbd.php");
$q=mysql_query("select * from organizaciones c left join estados e on e.idestado=c.idestado left join municipios m on m.idmunicipio=c.idmunicipio where idorganizacion=1");

while ($org = mysql_fetch_object($q)) {
    $nombreorganizacion = $org->nombreorganizacion;
    $rfc = $org->nombreorganizacion;
    $direccion = utf8_decode($org->domicilio.' '.$org->municipio.' ,'.$org->estado);
    $logoempresa = $org->logoempresa;
}
$idmov=$_REQUEST['idmov'];
$tipo=$_REQUEST['tipo'];

$q2=mysql_query("select mm.id,
                            (select nombre from almacen where idAlmacen=mm.idAlmacenOrigen) almacenorigen,
                            mm.cantidadtotalOrigen,
                            concat(mm.cantidadmovimiento,' ',u.compuesto,' de ',p.nombre) movimiento,
                            (select nombre from almacen where idAlmacen=mm.idAlmacenDestino) almacendestino,
                            mm.cantidadtotalDestino,mm.fechamovimiento, usu.usuario, mm.status,
                            (select usu.usuario from accelog_usuarios usu where mm.idEmpleado=usu.idempleado) usuariosalida,
                            (select IF(mm.idEmpleadoRec = 0, 'Nada', usu.usuario) as usuarioentrada from accelog_usuarios usu where mm.idEmpleadoRec=usu.idempleado) usuarioentrada
                            from movimientos_mercancia mm,mrp_producto p,mrp_unidades u,almacen a, accelog_usuarios usu
                            where   mm.idAlmacenDestino=a.idAlmacen and mm.idProducto=p.idProducto
                            and mm.idUnidad=u.idUni  and mm.idEmpleado=usu.idempleado and mm.id=".$idmov);
while ($mov = mysql_fetch_object($q2)) {
        $idmovieminto = $mov->id;
        $almacenorigen = $mov->almacenorigen;
        $movimiento = $mov->movimiento;
        $almacendestino = $mov->almacendestino;
        $usuariosalida = $mov->usuariosalida;
        $usuarioentrada = $mov->usuarioentrada;
        $fecha = $mov->fechamovimiento;
}
//$q2 = mysql_query("SELECT * from ")
//$idmov=$_REQUEST['idmov'];
//exit();

?>
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
            $imagen='../../netwarelog/archivos/1/organizaciones/'.$logoempresa;
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
        <div id="company_name"><?php echo $nombreorganizacion;?></div>
        <div id="company_address"><?php echo $direccion; ?></div>
        
          <!--  <?php if(strcmp($venta->estatus,"Cancelada")==0){?>
            <div id="company_phone">        
                <?php echo "Venta ".$venta->estatus;?>
            </div>
            <?php
        }  ?> -->
        <?php if($tipo==2){
            $titulo = 'Comprobante de Salida de Almacen';
        }else{
            $titulo = 'Comprobante de Entrada de Alamcen';
        }

            ?>

        <div id="sale_receipt"><?php echo  $rfc;?></div>  
        <div id="sale_receipt"><h2><?php echo $titulo; ?></h2></div>
        <div id="sale_time"><!--Fecha y hora--><?php //exit();//echo //formatofecha($retiro->fecha);?></div>
    </div>

    <div id="receipt_general_info" style="text-align:center;">
        <div id="sucursal">Sucursal:<?php echo $_SESSION["sucursalNombre"]; ?></div>
        <div id="sale_id">Id Moviemiento:<?php  echo $idmovieminto; ?></div>
        <div id="sale_id">Usuario:<?php echo $usuariosalida;  ?></div>
      <!--  <div id="employee"><h3>Empleado:</h3><h4><?php  echo $retiro->usuario; ?></h4></div> -->
    </div>

    <table id="receipt_items" border='0'>   
        <tr>
            <th style="width:16%;text-align:center;">Movimiento</th>
            <th style="width:16%;text-align:right;">Al. Origen</th>
            <th style="width:16%;text-align:right;">Al. Destino</th>
            <th style="width:16%;text-align:right;">Recibe</th>
        </tr>
        <tr>
            <td style='text-align:center;'><?php echo $movimiento; ?></td>
            <td style='text-align:right;'><?php echo $almacenorigen; ?></td>
            <td style='text-align:right;'><?php echo $almacendestino; ?></td>
            <td style='text-align:right;'><?php echo $usuarioentrada; ?></td>
        </tr>
        <tr>
             <td colspan="1" style='text-align:right;border-top:2px solid #000000;'></td>
             <td colspan="1" style='text-align:right;border-top:2px solid #000000;'></td>
             <td colspan="1" style='text-align:right;border-top:2px solid #000000;'></td>
             <td colspan="1" style='text-align:right;border-top:2px solid #000000;'></td>
        </tr>
    </table>   
</div>    