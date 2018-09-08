<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>   
  <link   rel="stylesheet" type="text/css" href="css/reporteacumulado.css"> 
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script>
  <script type="text/javascript" src="../../libraries/numeral.min.js"></script>
  <script type="text/javascript" src='js/reporteacumulado.js'></script>
  <link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
  <link   rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css"><link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
  <link   rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
  <script src="../../libraries/dataTable/js/datatables.min.js"></script>
  <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
  <script src="../cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
  <!-- <script type="text/javascript">
    $(document).ready(function(){
        tpago=$("#period").val();
        $("#nombre").val(tpago);
    });
  </script> -->
  <body>
    <div class="ocultos" style="height: 46px;font-family: Courier;background-color:#F5F5F5;" align="center"><b style="font-size:25px;">Reporte Acumulado</b></div> 
    <div class="panel-body">
      <div class="row">

        <div class="col-md-12" align="center">
         <form class="ocultos" method="post" action="index.php?c=Reportes&f=reporteAcumulado" id="formAcumulado">     
          <fieldset class="scheduler-border  col-md-12">
            <legend class="scheduler-border" align="center">Búsqueda</legend>
            <div class="form-inline">
              <button type="button" class="btn btn-primary btn-sm" id="load" style="center" data-loading-text="Consultando<i class='fa fa-refresh fa-spin '></i>">Generar Reporte</button>
              &ensp;
              <a type="button" class="btn btn-sm" style="background-color:#d67166"  href="javascript:pdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif"  
                title ="Generar reporte en PDF" border="0"> 
              </a>
              &ensp;   
              <a type="button" class="btn btn-sm" style="background-color:#68a225"  href="javascript:mail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png"  
                title ="Enviar reporte por correo electrónico" border="0"> 
              </a>
            </div>
            <br>
            <div class="form-group row">
              <label for="first_name" class="col-xs-1 col-form-label mr-2">Periodo</label>
              <div class="col-xs-3">
               <select id="nombre" class="form-control selectpicker btn-sm" data-live-search="true" name="tipoperiodo">
                <option value="*" selected="selected">Todos</option>
                <?php 
                while ($e = $tipoperiodo->fetch_object()){
                  $b = "";
                  if(isset($datos)){ if($e->idtipop == $datos->idtipop){ $b="selected"; } }
                  echo '<option value="'. $e->idtipop .'" '. $b .'>'. $e->nombre .'  </option>';
                }
                ?>
              </select>
              <input type="text" id="period" name="period" class="selectpicker btn-sm form-control" value="<?php if (isset($_POST['tipoperiodo'])) echo $_POST['tipoperiodo']; ?>" style="display:none" />
            </div>
            <label for="nominas" class="col-xs-1 col-form-label mr-2">Nomina de</label>
            <div class="col-xs-3">
             <select id="nominas" class="form-control selectpicker btn-sm" data-live-search="true" name="nominas">
              <option value="*">Todos</option>
              <?php 
              while ($e = $nominas->fetch_object()){
                $b = ""; 
                if(isset($datos)){ if($e->idtipop == $datos->idtipop){ $b="selected"; } }
                echo '<option value="'. $e->idtipop .'" '.$b .'>'.'('.$e->numnomina.')'.'  '.$e->fechainicio.' '.$e->fechafin.'</option>';
              }
              ?> </select>
              <input type="text" id="nomi" name="nomi" class="selectpicker btn-sm form-control" value="<?php if (isset($_POST['nominas'])) echo $_POST['nominas']; ?>"  style="display:none"/>
              <input type="text" id="extraord" name="extraord" class="selectpicker btn-sm form-control" value="<?php if (isset($_POST['nominas'])) echo $_POST['nominas']; ?>"  style="display:none"/>
            </div>
            <label for="nominas" class="col-xs-1 col-form-label mr-2">Nomina al</label>
            <div class="col-xs-3">
             <select id="nominasdos" class="sel form-control selectpicker btn-sm" data-live-search="true" name="nominasdos"  >
              <option value="*">Todos</option>
              <?php 
              while ($e = $nominas->fetch_object()){
                $b = ""; 
                if(isset($datos)){ if($e->fechainicio == $datos->idtipop){ $b="selected"; } }
                echo '<option value="'. $e->idtipop .'" '.$b .'>'.'('.$e->numnomina.')'.'  '.$e->fechainicio.' '.$e->fechafin.'</option>';
              }
              ?> </select>
              <input type="text" id="nomidos" name="nomidos" class="selectpicker btn-sm form-control" value="<?php if (isset($_POST['nominasdos'])) echo $_POST['nominasdos']; ?>"  style="display:none"/>
              

            </div>
          </div>
          <div class="form-group row">
            <label for="last_name" class="col-xs-1 col-form-label mr-2">Empleado</label>
            <div class="col-xs-3">
              <select id="empleadosdos" class="sel form-control selectpicker btn-sm" data-live-search="true" name="empleadosdos">
                <option value="*">Todos</option>
                <?php 
                while ($e = $empleadosdos->fetch_object()){
                  $b = "";
                  if(isset($datos)){ if($e->idEmpleado == $datos->idEmpleado){ $b="selected"; } }
                  echo '<option value="'. $e->idEmpleado .'" '. $b .'>'. $e->apellidoPaterno .' '.$e->apellidoMaterno .' '.$e->nombreEmpleado.' </option>';
                }
                ?>
              </select>
            </div>
            <label for="last_name" class="col-xs-1 col-form-label mr-2">Origen</label>
            <div class="col-xs-3">
             <select id="origen" class="form-control selectpicker btn-sm" data-live-search="true" name="origen">
              <option  value="*">Todos</option>
              <option  value="0">Prenomina</option>
              <option  value="1">Aguinaldo</option>
              <option  value="2">Finiquito</option>
              <option  value="3">Ptu</option>
            </select> 
          </div>
        </div>
      </fieldset>
    </form>
  </div>
</div>
<br>
<div id="imprimible"> 
  <?php
  $concepto =0;
  $empleado =0;

  if($reporteAcumulado){
       //echo "----->".mysqli_num_rows($reporteAcumulado)."<-------";

    $url = explode('/modulos',$_SERVER['REQUEST_URI']);
    if($logo1 == 'logo.png') $logo1= 'x.png';
    $logo1 = str_replace(' ', '%20', $logo1);

    echo"<img src=http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo1 style='width: 200px;height: 45px;padding-right:30px;padding-left:15px;' hidden>";
    echo "<b style='font-size:12px;'  hidden>".$infoEmpresa['nombreorganizacion'].' '."</b>";
    echo "<b style='font-size:12px;'  hidden>".$infoEmpresa['RFC']."</b>";
    echo "<br>";
    echo "<p style='font-size:15px;font-weight:bold;padding-left:15px;text-align:center' class='siz' hidden>Reporte Acumulado</p>";


    if ($_REQUEST['period']!="3" && $_REQUEST['nominas'] =="*" && $_REQUEST['nominasdos'] =="*"  ) {
      echo "<p style='padding-top: 15px; font-weight: bold;text-align:center;font-size:17.5px;' class='siz'>"."Todos los periodos existentes"."</p>";
      echo "<p style='font-weight: bold;text-align:center;font-size:17.5px;' class='siz'>"."Todas las nominas existentes"."</p>";

    }
    else  if ($_REQUEST['period']!="3" &&  $_REQUEST['nomi']!="*" && $_REQUEST['nomidos']!="*"){

     echo "<p style='padding-top: 15px; font-weight:bold;text-align:center;font-size:17.5px;' class='siz'>"."Periodo"." "."del".$_REQUEST['nomi']." al ".$_REQUEST['nomidos']."</p>";

     echo "<p style='padding-top: 15px;font-weight: bold;text-align:center;font-size:17.5px;' class='siz'>"."Nomina"." "."del"." ".$_REQUEST['nominas']." al ".$_REQUEST['nominasdos']."</p>";
   }

   else if ($_REQUEST['period']=="3" &&  $_REQUEST['nomi']!="*") {
     echo "<p style='padding-top: 15px; font-weight:bold;text-align:center;font-size:17.5px;' class='siz'>"."Periodo"." ".$_REQUEST['extraord']."</p>";

     echo "<p style='padding-top: 15px;font-weight: bold;text-align:center;font-size:17.5px;' class='siz'>"."Nomina"." ".$_REQUEST['nominas']."</p>";
  }
  else {
     echo "<p style='padding-top: 15px; font-weight: bold;text-align:center;font-size:17.5px;' class='siz'>"."Todos los periodos existentes"."</p>";
     echo "<p style='font-weight: bold;text-align:center;font-size:17.5px;' class='siz'>"."Todas las nominas existentes"."</p>";

 }

 if(mysqli_num_rows($reporteAcumulado)!=0) {
   while($in = $reporteAcumulado->fetch_assoc()){

     if ($concepto != $in['idconcepto']) { 
      if ($concepto!=0 ){
        echo"
      </tbody>
      <tfoot align='right'>
        <tr style='background-color:#d1d4d5'><th colspan='2' style='text-align:right;font-weight:bold'>Totales</th><th colspan='1' style='text-align:right;font-weight:bold'></th>
         <th colspan='1' style='text-align:right;font-weight:bold'></th> 
         <th colspan='3' style='text-align:right;font-weight:bold'>Neto Total</th>
         <th colspan='2' style='text-align:right;font-weight:bold'></th></tr>
       </tfoot>
     </table>
     <br>
     <br>";
   } 
   if ($empleado != $in['idEmpleado']) {
    if ($empleado!=0 ){
      echo "</div>
      ";
    }
    echo"<div class=\"alert alert-info\">"; 
    echo "<div style='font-weight:bold'>";
    echo $in['nombreEmpleado'].' '.$in['apellidoPaterno'].' '.$in['apellidoMaterno'];
    echo"</div>";
    echo "<br>";
  }

  echo"
  <table cellpadding=\"0\" class=\"tablacumulado table table-striped table-bordered\" style=\"border:solid 1px;\" border=\"1\" bordercolor=\"#0000FF\" >
    <thead> 
      <tr style='background-color:rgb(180,191,193);color:black'>
        <th class='clav tama'>Clave</th>
        <th class='conc'>Concepto</th>
        <th class='perce'>Percepcion</th>
        <th class='dedu'>Deduccion</th>
        <th class='grav'>Gravado</th>
        <th class='exen'>Exento</th>
        <th class='peri'>Periodo</th>
        <th class='nomi'>Nomina</th>
        <th class='orig'>Origen</th>
      </tr>
    </thead>
    <tbody>"; 
    }
    ?>

    <tr >
      <td class='clav'><?php echo $in['idconcepto'];?></td>
      <td class='conc'><?php echo $in['descripcion'];?></td>
      <td class='perce importePercepciones' style='text-align:right'><?php echo number_format($in['percepciones'],2);?></td>
      <td class='dedu importeDeducciones' style='text-align:right'><?php echo number_format($in['deducciones'],2);?></td>
      <td class='grav' style='text-align:right'><?php echo number_format($in['gravado'],2);?></td> 
      <td class='exen' style='text-align:right'><?php echo number_format($in['exento'],2);?></td>
      <td class='peri'><?php echo $in['nombre'];?></td>
      <td class='nomi'><?php echo $in['fechainicio'].' '.'al'.' '.$in['fechafin']?></td>
      <td class='orig'><?php echo $in['origen'];?></td>
    </tr> 
    <?php  
    $concepto = $in['idconcepto'];
    $empleado = $in['idEmpleado'];
  }  
}
echo"</tbody>
<tfoot align='right'>
  <tr style='background-color:#d1d4d5'><th colspan='2' style='text-align:right;font-weight:bold'>Totales</th> <th colspan='1' style='text-align:right;font-weight:bold'></th>
   <th colspan='1' style='text-align:right;font-weight:bold'></th> 
   <th colspan='3' style='text-align:right;font-weight:bold'>Neto Total</th>
   <th colspan='2' style='text-align:right;font-weight:bold'></th></tr>
 </tfoot>    
</table>
<br><br><br><br><br><br>
<table cellpadding=\"0\" id='totales' class=\"tama table table-striped table-bordered\" style=\"border:solid 1px;\" border=\"1\" bordercolor=\"#0000FF\">
 <thead> 
   <tr style='background-color:rgb(180,191,193);color:black' ><th colspan='2' class='tama'></th><th colspan='2'><b>Total Percepciones</b></th><th colspan='2'><b>Total Deducciones</b></th><th colspan='2'><b>Neto Total</b></th></tr>
   <tr>
    <td colspan='2' class='tama' style='background-color:rgb(180,191,193);color:black'><b>Gran Total</b>
    </td>
    <td id='tdSumaPercepciones' colspan='2' style='text-align:right;font-Weight:bold'>
    </td>
    <td id='tdSumaDeducciones' colspan='2' style='text-align:right;font-Weight:bold'>
    </td>
    <td id='resta' colspan='2' style='text-align:right;font-Weight:bold' ></td>
  </tr>
</table>
</div> 
<br></div>";
} 
else{

 if(mysqli_num_rows($reporteAcumulado)==0){
  if ($_REQUEST['nominas'] !="*" && $_REQUEST['nominasdos']!="*" && $_REQUEST['nomi'] && $_REQUEST['nomidos']){

   echo "<p style='padding-top: 15px; font-weight:bold;text-align:center;font-size:17.5px;' class='siz'>"."Periodo"." "."del".$_REQUEST['nomi']." al ".$_REQUEST['nomidos']."</p>";

   echo "<p style='padding-top: 15px;font-weight: bold;text-align:center;font-size:17.5px;' class='siz'>"."Nomina"." "."del"." ".$_REQUEST['nominas']." al ".$_REQUEST['nominasdos']."</p>";
 }
 else if ($_REQUEST['nominas'] =="*" && $_REQUEST['nominasdos']=="*" ) {

   echo "<p style='padding-top: 15px; font-weight: bold;text-align:center;font-size:17.5px;' class='siz'>"."Todos los periodos existentes"."</p>
   <p style='font-weight: bold;text-align:center;font-size:17.5px;' class='siz'>"."Todas las nominas existentes"."</p>";
 }
 echo "<div class='alert alert-info'>
 <table cellpadding=\"0\" class=\"tablacumulado table table-striped table-bordered\" style=\"border:solid 1px;\" border=\"1\" bordercolor=\"#0000FF\" >
  <thead> 
    <tr style='background-color:rgb(180,191,193);color:black' class='gene'>
      <th>Clave</th>
      <th>Concepto</th>
      <th>Percepcion</th>
      <th>Deduccion</th>
      <th>Gravado</th>
      <th>Exento</th>
      <th>Periodo</th>
      <th>Nomina</th>
      <th>Origen</th>
    </tr>
  </thead>
</table>
</div>";
}
}

?>
</div>

<!--GENERA PDF*************************************************-->
<div id="divpanelpdf" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Generar PDF</h4>
      </div>
      <form id="formpdf" action="../cont/libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <label>Escala (%):</label>
              <select id="cmbescala" name="cmbescala" class="form-control">
                <?php
                for($i=100; $i > 0; $i--){
                  echo '<option value='. $i .'>' . $i . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="col-md-6">
              <label>Orientación:</label>
              <select id="cmborientacion" name="cmborientacion" class="form-control">
                <option value='P'>Vertical</option>
                <option value='L'>Horizontal</option>
              </select>
            </div>
          </div>
          <textarea id="contenido" name="contenido" style="display:none"></textarea>
          <input type='hidden' name='tipoDocu' value='hg'>
          <input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
          <input type='hidden' name='nombreDocu' value='Detalle Nomina'>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="col-md-6">
              <input type="submit" value="Crear PDF" autofocus class="btn btn-primary btnMenu">
            </div>
            <div class="col-md-6">
              <input type="button" value="Cancelar" onclick="cancelar_pdf()" class="btn btn-danger btnMenu">
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;z-index:2;">
  <div id="divmsg" style="
  opacity:0.8;
  position:relative;
  background-color:#000;
  color:white;
  padding: 20px;
  -webkit-border-radius: 20px;
  border-radius: 10px;
  left:-50%;
  top:-200px
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
