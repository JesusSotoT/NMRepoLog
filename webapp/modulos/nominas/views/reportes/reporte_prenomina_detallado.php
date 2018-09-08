<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>   
<link   rel="stylesheet" type="text/css" href="css/reporteacumulado.css"> 
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script>
<script type="text/javascript" src="../../libraries/numeral.min.js"></script>
<script type="text/javascript" src='js/reportePrenominaDetallado.js'></script>
<link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
<link   rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
<link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<link   rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
<script src="../../libraries/dataTable/js/datatables.min.js"></script>
<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
<script src="../cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<body>
<div class="container-fluid" style="text-align:center;font-family: Courier;background-color:#F5F5F5;font-size: 25px;">
<b>Reporte de Prenomina Detallado</b>
</div>
<br>

<div class="container well" style="width: 96%;">
<form class="ocultos" method="post" action="index.php?c=Reportes&f=reportePrenominaDetallado" id="formDetallado"> <fieldset class="scheduler-border">
<legend class="scheduler-border" align="center">Búsqueda</legend>
<!-- <div class="row" style="text-align: center;"> -->

 <div class="form-inline">
<div class="col-md-4">
<label>Periodo</label>
<select id="nombre" class="selectpicker" data-live-search="true" name="nombre" data-width="70%">
  <option value="*" selected="selected">Todos</option>
  <?php 
  while ($e = $tipoperiodo->fetch_object()){
    $b = "";
    if(isset($datos)){ if($e->idtipop == $datos->idtipop){ $b="selected"; } }
    echo '<option value="'. $e->idtipop .'" '. $b .'>'. $e->nombre .'  </option>';
  }
  ?>
</select>
<input type="text" id="period" name="period" class="selectpicker btn-sm form-control" value="<?php if (isset($_POST['nombre'])) echo $_POST['nombre']; ?>"  style="display:none"/>
</div>
<div class="col-md-4">
<label>Nomina:</label>
<select id="nominas" class="selectpicker" data-live-search="true" name="nominas" data-width="70%">
  <option value="*">Todos</option>
  <?php 
  while ($e = $nominas->fetch_object()){
    $b = ""; 
    if(isset($datos)){ if($e->idtipop == $datos->idtipop){ $b="selected"; } }
    echo '<option  value="'. $e->idtipop .'" '.$b .'>'.'('.$e->numnomina.')'.'  '.$e->fechainicio.' '.$e->fechafin.'</option>';
  }
  ?> </select>
  <input type="text" id="nomi" name="nomi" class="selectpicker btn-sm form-control" value="<?php if (isset($_POST['nominas'])) echo $_POST['nominas']; ?>"   style="display:none" />
  <input type="text" id="fechainic" name="fechainic" value="<?php if (isset($_POST['nominas'])) echo $_POST['nominas']; ?>" style="display:none" />
  <input type="text" id="fechafina" name="fechafina" value="<?php if (isset($_POST['nominas'])) echo $_POST['nominas']; ?>" style="display:none"/>
</div>

<div class="col-md-4"> <label>Empleado</label>
  <select id="empleados" class="sel selectpicker" data-live-search="true" name="empleados" data-width="70%">
    <option value="*">Todos</option>
    <?php 
    while ($e = $empleados->fetch_object()){
      $b = "";
      if(isset($datos)){ if($e->idEmpleado == $datos->idEmpleado){ $b="selected"; } }
      echo '<option value="'. $e->idEmpleado .'" '. $b .'>'. $e->apellidoPaterno .' '.$e->apellidoMaterno .' '.$e->nombreEmpleado.' </option>';
    }
    ?>
  </select>
</div>
</div>
<div class="col-md-12" style="text-align: center;padding-top: 15px;">
  <button type="button" class="btn btn-primary btn-sm" id="load" style="text-align: center;" data-loading-text="Consultando<i class='fa fa-refresh fa-spin'></i>">Generar Reporte</button>
   <a type="button" class="btn btn-sm" style="background-color:#d67166"  href="javascript:pdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif"  
    title ="Generar reporte en PDF" border="0"> 
  </a>  
</div>
</fieldset>  
</form>

<div id="imprimible">
<?php
// $concepto =0;
$empleado =0;
$numero=0;

if($reportePrenominaDetallado){
//$conceptos = $this->ReportesModel->conceptos();
//echo "<input type = 'hidden' id = 'conceptos' value='".$conceptos->fetch_assoc()['conceptos']."' />";
            
echo "<table  style='font-size:12px;'>
<tr>
  <td rowspan='4' style='width:200px;padding-right:20px;'>";
    $url = explode('/modulos',$_SERVER['REQUEST_URI']);
    if($logo1 == 'logo.png') $logo1= 'x.png';
    $logo1 = str_replace(' ', '%20', $logo1);
    echo "<img src=http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo1 style='width: 200px;height: 45px;'>"; 
    echo"</td>
    <td>";echo "<b>".$infoEmpresa['nombreorganizacion'].' '.$infoEmpresa['RFC']."</b>";
      echo"</td></tr><tr><td>"; 
      echo "<b>Cálculo de la nómina</b>";
      echo"</td></tr><tr><td>";  
      if ($_REQUEST['nominas'] =="*") {
        echo"<b>Nomina periodo:</b>".' '."Todas nominas"."</p>";
      }else {
        echo"<b>Nomina periodo:</b>".' '.$_REQUEST['nomi'];
      }
      echo"</td></tr><tr><td>"; 
      if ($_REQUEST['nominas'] =="*") {
      }else {
        echo"<b>Fecha Inicial:</b>".' '.$_REQUEST['fechainic'].' '.' '."<b>Fecha Final:</b>".' '.$_REQUEST['fechafina'] ;
      }
      echo"</td></tr>
    </table><br>";


    if($reportePrenominaDetallado->num_rows>0) {
      while($in = $reportePrenominaDetallado->fetch_assoc()){


        echo"<div class='alert alert-info' border:'2px'; style='overflow-x: scroll;'>"; 
        echo  $in['codigo'].' '.$in['nombreEmpleado'].' '.$in['apellidoPaterno'].' '.$in['apellidoMaterno'];
        ?>
        <br>
        &ensp;  
        <table cellpadding="0" class="tablepreDetallado table nowrap table-striped table-bordered table-responsive" style="border: 1px;width:100%;" border='1px' bordercolor="#0000FF\">
          <thead> 
            <tr style='background-color:rgb(180,191,193);color:black'>
              <th>Jornada</th>
              <th>Días Cheq</th>
              <th>Sal Hora</th>
              <th>Sal.Base Diario</th>
              <th>Sal.Inte Diario</th>
              <th>Sueldo</th>
              <th>Premio Asist.</th>
              <th>Premio Punt.</th>
              <th>Base</th>
              <th>ISPT</th>
              <th>Subs</th>
              <th>Reten</th>
              <th>Entreg</th>
              <th>IMSS</th>
              <th>P.Vac</th>
              <th>Vacac</th>
              <th>Días Vac</th>
              <th>Min-</th>
              <th>Min+</th>
              <th>T.Ext$</th>
              <th>Neto</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $in['horas'];?></td>
              <td style="text-align: center;"><?php echo $in['DiasChe'];?></td>
              <td style="text-align: right;"><?php echo (number_format($in['salarioHora'],2,'.',','));?></td>
              <td style="text-align: right;"><?php echo (number_format($in['salario'],2,'.',','));?></td>
              <td style="text-align: right;"><?php echo (number_format($in['sbcfija'],2,'.',','));?></td>     
              <td style="text-align: right;" class='sumasueldo'><?php echo (number_format($in['sueldo'],2,'.',','));?></td>
              <td style="text-align: right;" class='tdpremioasist'><?php echo (number_format($in['primaAsistencia'],2,'.',','));?></td>
              <td style="text-align: right;" class="tdpremioaPunt"><?php echo (number_format($in['puntualidad'],2,'.',','));?></td>
              <td style="text-align: right;" class="tdbase"><?php echo (number_format($in['base'],2,'.',','));?></td>
              <td style="text-align: right;" class="tdispt"><?php echo (number_format($in['ispt'],2,'.',','));?></td>
              <td style="text-align: right;" class="tdsubs"><?php echo (number_format($in['subsid'],2,'.',','));?></td>
              <td style="text-align: right;" class="tdretenido"><?php echo (number_format($in['retenido'],2,'.',','));?></td>
              <td style="text-align: right;" class="tdentregado"><?php echo (number_format($in['entregado'],2,'.',','));?></td>
              <td style="text-align: right;" class="tdimss"><?php echo (number_format($in['imss'],2,'.',','));?></td>
              <td style="text-align: right;" class="tdprimVac"><?php echo (number_format($in['primavacacional'],2,'.',','));?></td>
              <td style="text-align: right;" class="tdvaca"><?php echo (number_format($in['vacaciones'],2,'.',','));?></td>
              <td style="text-align: center;" ><?php echo $in['diasvacaciones'];?></td>
              <td style="text-align: right;"><?php echo $in['minutosdemenos'];?></td>
              <td style="text-align: right;"><?php echo $in['minutosdemas'];?></td>
              <td style="text-align: right;"><?php echo (number_format($in['tiempoextra'],2,'.',','));?></td>
              <td></td>
            </tr> 
            <?php 
            echo "<tr>  
            <td colspan='21' style='text-align:right;'>"."Sueldo Neto:"." ".(number_format($in['sueldoneto'],2,'.',','))." "."-"." "."Infonavit:"." ".(number_format($in['infonavit'],2,'.',','))." "."="." ".(number_format($in['suelinfon'],2,'.',','))."</td>   
          </tr>";

          $neto = $in['neto'];    
          $empleado = $in['idEmpleado'];

          $tabladetallepre = $this->ReportesModel->tabladetallepre($in['idEmpleado'],$in['idtipop'],$in['idnomp']);

          if($tabladetallepre->num_rows>0) { 
            while($d = $tabladetallepre->fetch_assoc()){

                if($d["idtipo"] == 1 || $d["idtipo"] == 4){
                $neto+=$d['importe']; 
              
              }else{
                $neto-=$d['importe'];
              }
              echo 
              "<tr class='tdconcepto'> 
              <td class='concepto'>".$d['concepto']."</td>  
              <td>".$d['descripcion']."</td>
              <td style='text-align:right;' data-value='".$d['concepto']."' class='importeConcep'>".(number_format($d['importe'],2,'.',','))."</td>
              <td colspan='18'></td>";

              
            }
          }

          // <td colspan='21' style='text-align:right;' class='tdneto'>"."$"." ".(number_format($neto,2,'.',','))."</td></tr> "; 

          echo"<tr>            
          <td colspan='21' style='text-align:right;' class='tdneto'>".(number_format($neto,2,'.',','))."</td></tr> "; 


          echo"</tbody></table>";

          echo"</div>";  
      }
    }?>
   
  

 <table class="tablepreDetallado table nowrap table-striped table-bordered table-responsive" style="border: 1px;width:100%;" border="1px" bordercolor="#0000FF">
      
        <thead> 
         <tr style="background-color:rgb(180,191,193);color:black">
             <!--  <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th> -->
              <th>Sueldo Total:</th>
              <th>Total Prem. Asist.:</th>
              <th>Total Prem. Punt.:</th>
              <th>Total Base:</th>
              <th>Total ISPT:</th>  
              <th>Total subs:</th> 
              <th>Total Retenido:</th>
              <th>Total Entregado:</th>
              <th>Total IMSS:</th>
              <th>Total Prima Vac.:</th>
              <th>Total Vaca:</th>
             <!--  <th></th>
              <th></th>
              <th></th>
              <th></th> -->
              <th>Total Neto:</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            <!--   <td></td>
              <td ></td>
              <td ></td>
              <td ></td>
              <td ></td> -->     
              <td id='tdsumasueldo'></td>
              <td id='tdpremioasist'></td>
              <td id='tdpremioaPunt'></td>
              <td id='tdbase'></td>
              <td id='tdispt'></td> 
              <td id='tdsubs'></td>
              <td id='tdretenido'></td>
              <td id='tdentregado'></td>
              <td id='tdimss'></td>
              <td id='tdprimVac'></td>
              <td id='tdvaca'></td>
             <!--  <td ></td>
              <td ></td>
              <td ></td>
              <td ></td> -->
              <td id="tdneto"></td>
            </tr> 
</tbody></table>
 <table class="tablepreDetallado table nowrap table-striped table-bordered table-responsive" style="border: 1px;width:100%;" border='1px' bordercolor='#0000FF' id="sumaconceptos">
       
        <thead> 
        <tr style='background-color:rgb(180,191,193);color:black'>
        <th colspan='3'>Suma de conceptos:</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>Concepto</td><td>Descripción</td><td>Importe</td>
        </tr>
        <?php if($sumasconceptos->num_rows>0) {
      while($con = $sumasconceptos->fetch_assoc()){ ?>
    
        <tr>
                  <td><?php echo $con['concepto']?></td>
                  <td><?php echo $con['descripcion']?></td>
                  <td><?php echo $con['importe']?></td>
                </tr>
   <?php  
    }   
    }?>
    
        </tbody>
        </table>
 
 <?php }?>

 
   </div>

</div>
 <!--   <div id='tdsumasueldo'>LOL</div> -->



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
              <!-- <option value='P'>Vertical</option> -->
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
