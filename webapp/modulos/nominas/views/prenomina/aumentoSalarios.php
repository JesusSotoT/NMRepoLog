<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>  
  <script type="text/javascript" src='js/aumentoSalario.js'></script>
  <link rel="stylesheet" type="text/css" href="css/aumentoSalario.css">
  <link   rel="stylesheet" href="css/bootstrap-datetimepicker.css">

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

  <!-- L I B R E R I A S   P A R A   M U L T I S E L E C T  -->
  <!-- <link href="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> -->

  <script type="text/javascript" src="../../libraries/bootstrap.min.js"></script>
  <link href="../../libraries/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
  <script src="../../libraries/bootstrap-multiselect.js" type="text/javascript"></script>

  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="../../libraries/numeral.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.js"></script>
  <script type="text/javascript" src="../../libraries/numeral.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/es.js"></script>
  <link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
  <link   rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css"> 
  <link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
  <link   rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
  <script src="../../libraries/dataTable/js/datatables.min.js"></script>
  <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script> 
  <link rel="stylesheet" href="css/stylesheet-pure-css.css">
  <link rel="stylesheet" type="text/css" href="css/stylesheet-image-based.css">
  <title>Aumento Salarios</title>
  <script type="text/javascript">
    $(document).ready(function(){
      $( "#radio1" ).trigger( "click" ); 
    });
  </script> 
</head>
<body>

  <div class="container-fluid" class='encabezado' style="text-align:center;font-family: Courier;background-color:#F5F5F5;font-size: 25px;">
    <b>Aumento de Salario</b>
  </div>
  <br> 

  <div style="width: 100%" class="container well">
    <fieldset class="scheduler-border">
      <legend class="scheduler-border" align="center">Búsqueda</legend>
      <div class="row">
        <div class="col-md-3 form-group">
          <label for="first_name" style="text-align: center;font-size: 15px;">Registro patronal:</label>
          <select id="registro" class="form-control selectpicker btn-sm" data-live-search="true" name="registro">
            <option value="">Seleccione</option>
            <?php 
            while ($e = $registroPatronal->fetch_object()){
              $b = "";
              if(isset($datos)){ if($e->idregistrop == $datos->idregistrop){ $b="selected"; } }
              echo '<option value="'. $e->idregistrop .'" '. $b .'>'. $e->registro .'  </option>';
            }
            ?>     
          </select>    
        </div>
        <div class="col-md-3">
          <label style="text-align: center;font-size: 15px;">Tipo de periodo:</label>
          <select id="idtipop"  name='idtipop' class="form-control selectpicker btn-sm" data-live-search="true" name="idtipop">
            <option value="" align="left">Seleccione</option>
            <?php 
            while ($e = $tipoperiodo->fetch_object()){
              $b = "";
              if(isset($datos)){ if($e->idtipop == $datos->idtipop){ $b="selected"; } }
              echo '<option  idtipop="'.$e->idtipop.'" finicio="'.$e->fechainicio.'" ffin="'.$e->fechafin.'" idnomp="'.$e->idnomp.'"  value="'.$e->idtipop.'" '. $b .'>'.$e->nombre.'  </option>';
            }
            ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="dep" style="text-align: center;font-size: 15px;">Departamento:</label>
          <select id="dep" class="form-control selectpicker btn-sm" data-live-search="true" name="dep">
            <option value="">Seleccione</option>
            <option value="*">Todos</option>
            <?php 
            while ($e = $departamentos->fetch_object()){
              $b = "";
              if(isset($datos)){ if($e->idDep == $datos->idDep){ $b="selected"; } }
              echo '<option value="'. $e->idDep .'" '. $b .'>'. $e->nombre .'  </option>';
            }
            ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="empleado" style="font-size: 15px;">Empleado:</label>
          <br>
          <select id="empleado" class="sel form-control  btn-sm" multiple="multiple" data-live-search="true"  name="empleado">
          </select> 
          <input type="hidden" id="emple" class="form-control " name="emple" value ="<?php echo @$_REQUEST['emple'] ?>" align="left"  required />
        </div>
      </div>
    </fieldset>
    <div class="panel panel-default">
      <div class="panel-heading alert alert-info"><b style="font-size: 19px;">Incremento de Salarios</b></div>
      <div class="panel-body">
        <div style="text-align: center;color: black;font-size: 19px;" class="col-md-12 alert alert-info">
          <div class="col-md-12">
            <div class="col-md-6">
              <div class="" style="text-align: right;">
                <input id="checkbox1" type="checkbox" name="checkbox" value="1"   checked>
                <label for="checkbox1"><span></span>Actualiza salario diario</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-inline" style="text-align: left;">
                <input id="checkbox2" type="checkbox" name="checkbox" value="2" checked><label for="checkbox2"><span></span>Actualiza salario integrado</label>
              </div>
            </div>
          </div>
        </div>
        <fieldset class="scheduler-border row">
          <legend class="scheduler-border" align="center">Seleccione la forma de incremento de salario</legend>
          <div class="col-md-12 form-inline" style="text-align: center;font-size: 19px;" id="radiogeneral">
            <input id="radio1" class="form-inline" type="radio" name="radio" value="1" checked="checked"><label for="radio1"><span><span></span></span>Porcentaje</label>
            <input id="radio2"  class="form-inline" type="radio" name="radio" value="2"><label for="radio2"><span><span></span></span>Incremento en cantidad</label>
            <input id="radio3" type="radio" name="radio" value="3"><label for="radio3"><span><span></span></span>Nuevo salario</label>
          </div>
          <br>
          <br>
          <div class="col-md-12"> 
            <div class="col-md-5">
            </div>
            <div class="col-md-2">
              <div class="input-group" style="width: 250px;"> 
                <span class="input-group-addon" id="etiqtit"></span>
                <input type="text" id="montosalario" class="form-control numbersOnly" name="montosalario" value ="<?php echo @$_REQUEST['montosalario'] ?>"  required />
              </div>
              <label id="etiqueta" style="font-size: 16px;text-align: center"></label>  
            </div>
            <div class="col-md-5" style="padding-left:90px;">
              <input type="submit" class="btn btn-primary btn-sm" id="load" required="" 
              style="center;width: 120px;" data-loading-text="Consultando<i class='fa fa-refresh fa-spin'></i>" value="Cálculo" /> 
            </div>
          </div>
        </fieldset>
      </div>
    </div>



    <!-- T A B L E -->


    <div class="alert alert-info wrap" cellspacing="0" width="100%">
      <table id="aumentosalario" class="table table-striped table-bordered nowrap aumentosalario" cellspacing="0" width="100%" style="font-size: 19px;">
        <thead> 
          <tr style="background-color:#B4BFC1;color:#000000;">
            <th  style="font-weight: bold">Codigo</th>
            <th  style="font-weight: bold">Salario diario</th>
            <th  style="font-weight: bold">Salario nuevo</th>
            <th  style="font-weight: bold">Salario integrado</th>
            <th  style="font-weight: bold">Salario nuevo integrado</th>
            <th  style="font-weight: bold">Nombre Empleado</th>
          </tr>
        </thead>
<!--  <tfoot align="right" style="background-color: white">
<tr><th></th><th></th><th></th><th></th><th></th><th></th></tr>
</tfoot> -->
<tbody id="nomb"> 
</tbody>
</table>
<br>
<div class="row">
  <div class="col-md-3">
    <div class='input-group date' id='fecha'>
      <input type='text' name="txtfecha" id="txtfecha" class="form-control numbersOnly"  value="<?php echo @$_REQUEST['txtfecha'];?>" placeholder="Fecha de Aplicación">
      <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
      </span>
    </div>

  </div>
  <div class="col-md-9" style="text-align: right">
    <button type="button" class="btn btn-primary btn-md" id="guardarAumento" name="guardarAumento"  
    style="text-align:center;width: 120px;" data-loading-text="Guardando<i class='fa fa-refresh fa-spin'></i>">Guardar</button> 
    <?php  
    if($cargartablaaumento){ 
      echo "<script type='text/javascript'>
      datosAumentoSalario = '".json_encode($encode)."';
    </script>
    ";
  }
  ?> 
</div>
</div>
<br> 
<div class="col-md-12 alert-warning " style="font-size: 15px;">
  <a class="alert-warning glyphicon glyphicon-info-sign"></a>
  La fecha de aplicación debe ser en inicio de periodo.
</div> 
<br>
</div>
</div> 
</body>
</html>