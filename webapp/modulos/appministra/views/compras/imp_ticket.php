<!-- Carga las librerias -->
<script src="../../libraries/JsBarcode.all.min.js"></script>
<!-- bootstrap min CSS -->
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="css/comandas/comandas.css">

<script src="js/comandas/comandas.js"></script>

<div style="text-align:left;font-size:12px">
<?php if (!empty($logo)) { ?>
    <div style="text-align: center">
        <input type="image" src="<?php echo $logo ?>" style="width:180px"/>
    </div>
<?php } ?>
<div class="row" style="margin: 0; margin-top: 10px; border-bottom:3px solid;">
    <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>Folio Orden de compra:</strong> <?php echo $idCoti?></div>
    <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>Fecha y hora:</strong> <?php echo $fechaactual?></div>
    <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>Lugar de Expedición:</strong> <?php echo $expedicion?></div>
    <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>Usuario:</strong> <?php echo $datos_coti['nombre']?></div>
</div>    
<div class="row" style="margin: 0; margin-top: 10px; border-bottom:3px solid;">
    <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>Emisor</strong></div>
    <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $emisor['nombreorganizacion']?></div>
    <?php if(!empty($emisor['rfc'])) { ?>
        <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>RFC:</strong> <?php echo $emisor['rfc']?></div>
    <?php } ?>
    <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $emisor['domicilio'].", ".$emisor['municipio'].", ".$emisor['estado']?></div>
</div>  
<div class="row" style="margin: 0; margin-top: 10px; border-bottom:3px solid;">
    <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>Proveedor</strong></div>
    <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $proveedor['nombre']?></div>
    <?php if(!empty($proveedor['rfc'])) { ?>
        <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>RFC:</strong> <?php echo $proveedor['rfc']?></div>
    <?php } ?>
    <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $proveedor['direccion'].", ".$proveedor['municipio'].", ".$proveedor['estado']?></div>
    <?php if($datos_coti['tipo'] == 2) { ?>
        <div id="company_name" style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>No. Factura: </strong><?php echo $datos_coti['num_factura']?></div>
    <?php } ?>
</div>  
<div class="row" style="margin: 0; font-weight: bold; font-size:15px; font-family: Tahoma,'Trebuchet MS',Arial;">
    <div class="col-xs-3">Cant.</div>
    <div class="col-xs-3">Prod.</div>
    <div class="col-xs-3"  style="text-align: center">P.U.</div>
    <div class="col-xs-3"  style="text-align: center">Total</div>
</div> 
<div class="row" style="margin: 0; font-size:13px; font-family: Tahoma,'Trebuchet MS',Arial; border-bottom:3px solid;">
    <?php foreach ($conceptosDatos as $key => $value) { ?>
        <div class="col-xs-3"><?php echo $value['Cantidad']?></div>
        <div class="col-xs-3"><?php echo $value['Descripcion']?></div>
        <div class="col-xs-3"  style="text-align: center">$<?php echo $value['Precio']?></div>
        <div class="col-xs-3"  style="text-align: center">$<?php echo $value['Cantidad']*$value['Precio']?></div>
    <?php } ?>
</div>
<div class="row" style=" margin: 0; font-size:15px; font-family: Tahoma,'Trebuchet MS',Arial;">
    <div id="company_name" style="text-align: right; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>Subtotal: </strong>$<?php echo $subTotal?></div>
    <div id="company_name" style="text-align: right; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>Total: </strong>$<?php echo number_format($total, 2)?></div>
</div>  
<div class="row" style="margin:15px; font-size:15px; font-family: Tahoma,'Trebuchet MS',Arial;">
    <div id="company_name" style="text-align: left; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><strong>Observaciones: </strong></div>
    <div id="company_name" style="text-align: left; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;     border: solid 3px;"><?php echo $observaciones?></div>
</div>
