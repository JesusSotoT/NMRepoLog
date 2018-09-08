<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8"/>
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>   
  <link   rel="stylesheet" href="css/reportenomina.css" type="text/css">
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script>
  <script type="text/javascript" src='js/reportenomina.js'></script>
  <script src="../../libraries/dataTable/js/datatables.min.js"></script>
  <link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
  <link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css"> 
  <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
  <script src="../cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
  <script language='javascript' src='../cont/js/pdfmail.js'></script>
</head>
<body>
  <div class="container-fluid" class='encabezado' 
  style="text-align:center;font-family: Courier;background-color:#F5F5F5;font-size: 25px;">
    <b>Reporte de nóminas timbradas y envío masivo</b>
  </div>
  <br><br>
  <div class="container well" style="width: 96%;">
    <form method="post" action="index.php?c=Reportes&f=reporteNominas" id="formfecha">
      <fieldset class="scheduler-border">
        <legend align="center" class="scheduler-border">Búsqueda</legend>
        <div class="row">
          <div class="form-inline">
            <div class="col-md-4">
              <label>Fecha Inicio:</label>
              <input type="text" id="fechainicial" autocomplete="false" name="fechainicial" class="form-control btn-sm" 
              value="<?php echo @$_REQUEST['fechainicial'];?>" style="width: 70%;"> 
            </div>
            <div class="col-md-4">
              <label>Fecha Fin:</label>
              <input type="text" id="fechafinal"  autocomplete="false" name="fechafinal" class="form-control btn-sm"  
              value="<?php echo @$_REQUEST['fechafinal'];?>" style="width: 70%;">  
            </div>
            <div class="col-md-4">
              <label>Empleado:</label>
              <select class="selectpicker" id="nombreEmpleado" data-live-search="true" name="empleados">
                <option value="">Todos</option>
                <?php 
                while ($e = $empleados->fetch_object()){
                  $b = "";
                  if(isset($datos)){ if($e->idEmpleado == $datos->idEmpleado){ $b="selected"; } }
                  echo '<option value="'. $e->idEmpleado .'" '. $b .'>'.$e->apellidoPaterno .' '.$e->apellidoMaterno .' '.$e->nombreEmpleado.' </option>';
                }
                ?>
              </select>  
            </div>
          </div>
            <div class="col-md-12" style="text-align: center;padding-top: 15px;"> 
              <button type="button" class="btn btn-primary btn-sm" id="load" data-loading-text="Consultando<i class='fa fa-refresh fa-spin'></i>">Generar Reporte</button>
              <button type="button" class="btn btn-success btn-sm" onclick="envioCorreos();">Envio</button>
            </div>     
        </div>
      </fieldset>
    </form>
    <br>
    <br>
    <div class="alert alert-info">
      <table id="tablanominas" cellpadding="0" class="table table-striped dt-responsive nowrap table-bordered"  width='100%';>
        <thead>
          <tr style="background-color:#B4BFC1;color:#000000">
            <th>No.(ID)</th>
            <th>UUID</th>
            <th>Empleado</th>
            <th>Fecha inicial pago</th>
            <th>Fecha final pago</th>
            <th>Días de pago</th>
            <th>Subtotal</th>
            <th>Descuento</th>
            <th>Total</th>
            <th>Accion</th> 
            <th hidden="true"></th>
          </tr>
        </thead>
        <tbody>
          <?php
          if($reporteNomi){
            while($in = $reporteNomi->fetch_assoc()){
              $meses = array('1' => 'Enero','2' => 'Febrero','3' => 'Marzo','4' => 'Abril','5' => 'Mayo','6' => 'Junio','7' => 'Julio','8' => 'Agosto','9' => 'Septiembre','10' => 'Octubre','11' => 'Noviembre','12' => 'Diciembre');
              if($in['cancelado']==1){ $cancel = "style='text-decoration: line-through' "; }else{ $cancel="";}  
              ?>
              <tr <?php echo $cancel;?> class="out" onmouseout="this.className='out'" onmouseover="this.className='over'" xml="<?php echo $in['nombreXML'];?>" idemp="<?php echo $in['email'];?>" nomemp="<?php echo $in['nombreEmpleado'];?>" fechaini="<?php echo $in['fechainicial']; ?>" fechafin="<?php echo $in['fechafinal'];?>" >
                <td><?php echo $in['idNominatimbre'];?></td>
                <td><?php echo $in['UUID'];?></td>
                <td><?php echo $in['nombreEmpleado'].' '.$in['apellidoPaterno'].' '.$in['apellidoMaterno'];?></td>
                <td><?php echo $in['fechainicial'];?></td>
                <td><?php echo $in['fechafinal'];?></td>
                <td><?php echo number_format($in['diaspago'],2,'.',',');?></td>
                <td><?php echo number_format($in['subtotal'],2,'.',',');?></td>
                <td><?php echo number_format($in['descuento'],2,'.',',');?></td>
                <td><?php echo number_format($in['total'],2,'.',',');?></td>
                <td id="cancela<?php echo $in['idNominatimbre'];?>">
                  <a href="../cont/xmls/facturas/temporales/<?php echo $in['nombreXML'];?>" target='_blank'>
                    <img src="images/lupa2.png" style="width: 19px;" title="Visor de XML."></a>
                    <a href="javascript:mailNominas('<?php echo $in['nombreXML'];?>','<?php echo $in['email'];?>','<?php echo $in['fechainicial'];?>','<?php echo $in['fechafinal'];?>');">
                      <img src="../../../webapp/netwarelog/repolog/img/email.png"  
                      title ="Enviar Facturas por correo electrónico" style="width: 19px;"></a>
                      <a href="../cont/xmls/facturas/temporales/<?php echo $in['nombreXML'];?>" id="descargar" download>
                        <img src="images/xml.png" style="width: 19px;" id="descargarp" title="Descarga de XML.">
                      </a>
                      <a href="../cont/controllers/visorpdf.php?name=<?php echo $in['nombreXML'];?>&id=temporales" target="_blank"> <img src="images/pdf.png" style="width: 19px;" title="Descargar PDF."></a>
                      <a href="javascript:reutilizaFactura('<?php echo $in['idNominatimbre'];?>');">
                        <img src="images/reload.png" style="width: 19px;" title="Reutilizar Nomina.">
                      </a>
                      <?php if($in['cancelado']==0 ){ ?>
                      <a id="cancl" href="javascript:cancelarFactura('<?php echo $in['UUID'];?>',<?php echo $in['idNominatimbre'];?>);"><img style="width: 26px;height: 26px;"  title="Cancelar Factura." src="images/cancelar.png">
                      </a>
                      <?php 
                    }else{ echo "<b style='color:red'>CANCELADA</b>";}?>
                    </td>
                    <td id="cargando<?php echo $in['idNominatimbre'];?>" style="display: none;width:30px !important;">
                      <b>Cancelando</b><i class='fa fa-refresh fa-spin '></i>
                    </td>
                  </tr>
                  <?php }
                }else{ ?> 
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div id="loading" style="position: absolute; top:30%; left: 50%;display:none;z-index:2;">
          <div 
          id="divmsg"
          style="
          opacity:0.8;
          position:relative;
          background-color:#000;
          color:white;
          padding: 20px;
          -webkit-border-radius: 20px;
          border-radius: 10px;
          left:-50%;
          top:-30%
          ">
          <center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...
          </center>
        </div>
      </div>
      <script>
        function cerrarloading(){
          $("#loading").fadeOut(0);
          var divloading="<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...</center>";
          $("#divmsg").html(divloading);
        }
      </script>
    </body>
    </html>
