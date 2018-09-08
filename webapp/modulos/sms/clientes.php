<?php
include("../../netwarelog/webconfig.php");
include_once('funcionesBD/conexion.php');
    $consult = new Consult;
    $conection = $consult -> conection($servidor,$usuariobd,$clavebd,$bd);

    $result=$consult->invitados($conection);
    if($result->num_rows>=1){  
      while($row=$result->fetch_array(MYSQLI_ASSOC)){ 
        $invitados[]=$row;
      }
    }else{
        $invitados=0;
    }

    $result=$consult->estados($conection);
    if($result->num_rows>=1){  
      while($row=$result->fetch_array(MYSQLI_ASSOC)){ 
        $estados[]=$row;
      }
    }else{
        $estados=0;
    }

    $result=$consult->grupos($conection);
    if($result->num_rows>=1){  
      while($row=$result->fetch_array(MYSQLI_ASSOC)){ 
        $grupos[]=$row;
      }
    }else{
        $grupos=0;
    }

    $result=$consult->rubros($conection);
    if($result->num_rows>=1){  
      while($row=$result->fetch_array(MYSQLI_ASSOC)){ 
        $rubros[]=$row;
      }
    }else{
        $rubros=0;
    }

    $result=$consult->ttienda($conection);
    if($result->num_rows>=1){  
      while($row=$result->fetch_array(MYSQLI_ASSOC)){ 
        $ttienda[]=$row;
      }
    }else{
        $ttienda=0;
    }

    $result=$consult->clientes2($conection);
    if($result->num_rows>=1){  
      while($row=$result->fetch_array(MYSQLI_ASSOC)){ 
        $clientes[]=$row;
      }
    }else{
        $clientes=0;
    }
    unset($consult);
    $consult2 = new Consult;
    $conection2 = $consult2 -> conection($servidor,'nmdevel','nmdevel','nmdev_common');
    $result2=$consult2->estados($conection2);
    if($result2->num_rows>=1){  
      while($row2=$result2->fetch_array(MYSQLI_ASSOC)){ 
        $estados2[]=$row2;
      }
    }else{
        $estados2=0;
    }

    $result2=$consult2->grupos($conection2);
    if($result2->num_rows>=1){  
      while($row2=$result2->fetch_array(MYSQLI_ASSOC)){ 
        $grupos2[]=$row2;
      }
    }else{
        $grupos2=0;
    }

    $result2=$consult2->rubros($conection2);
    if($result2->num_rows>=1){  
      while($row2=$result2->fetch_array(MYSQLI_ASSOC)){ 
        $rubros2[]=$row2;
      }
    }else{
        $rubros2=0;
    }

    $result2=$consult2->ttienda($conection2);
    if($result2->num_rows>=1){  
      while($row2=$result2->fetch_array(MYSQLI_ASSOC)){ 
        $ttienda2[]=$row2;
      }
    }else{
        $ttienda2=0;
    }

    $result2=$consult2->clientes2($conection2);
    if($result2->num_rows>=1){  
      while($row2=$result2->fetch_array(MYSQLI_ASSOC)){ 
        $clientes2[]=$row2;
      }
    }else{
        $clientes2=0;
    }

?>
<html>
  <head>
    <link href="css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
    <link href="css/jquery-ui.css" title="estilo" rel="stylesheet" type="text/css" />
    <style type="text/css">
    #tabs.ui-widget-content{
      border:none;

    }
    .ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br {
        border-bottom-right-radius: 0;
    }
    .ui-corner-all, .ui-corner-bottom, .ui-corner-left, .ui-corner-bl {
        border-bottom-left-radius: 0;
    }
    .ui-corner-all, .ui-corner-top, .ui-corner-right, .ui-corner-tr {
        border-top-right-radius: 0;
    }
    .ui-corner-all, .ui-corner-top, .ui-corner-left, .ui-corner-tl {
        border-top-left-radius: 0
    }

    table a:link {
  color: #666;
  font-weight: bold;
  text-decoration:none;
}
table a:visited {
  color: #999999;
  font-weight:bold;
  text-decoration:none;
}
table a:active,
table a:hover {
  color: #bd5a35;
  text-decoration:underline;
}
table {
  font-family:Arial, Helvetica, sans-serif;
  color:#666;
  font-size:11px;
  text-shadow: 1px 1px 0px #fff;
  background:#eaebec;
  margin:0px;
  border:#ccc 1px solid;

  -moz-border-radius:3px;
  -webkit-border-radius:3px;
  border-radius:3px;

  -moz-box-shadow: 0 1px 2px #d1d1d1;
  -webkit-box-shadow: 0 1px 2px #d1d1d1;
  box-shadow: 0 1px 2px #d1d1d1;
}
table th {
  padding:15px 25px 13px 15px;
  border-top:1px solid #fafafa;
  border-bottom:1px solid #e0e0e0;

  background: #ededed;
  background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
  background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
}
table th:first-child {
  text-align: left;
  padding-left:20px;
}
table tr:first-child th:first-child {
  -moz-border-radius-topleft:3px;
  -webkit-border-top-left-radius:3px;
  border-top-left-radius:3px;
}
table tr:first-child th:last-child {
  -moz-border-radius-topright:3px;
  -webkit-border-top-right-radius:3px;
  border-top-right-radius:3px;
}
table tr {
  text-align: center;
  padding-left:20px;
}
table td:first-child {
  text-align: left;
  padding-left:20px;
  border-left: 0;
}
table td {
  padding:14px;
  border-top: 1px solid #ffffff;
  border-bottom:1px solid #e0e0e0;
  border-left: 1px solid #e0e0e0;

  background: #fafafa;
  background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
  background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
}
table tr.even td {
  background: #f6f6f6;
  background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
  background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
}
table tr:last-child td {
  border-bottom:0;
}
table tr:last-child td:first-child {
  -moz-border-radius-bottomleft:3px;
  -webkit-border-bottom-left-radius:3px;
  border-bottom-left-radius:3px;
}
table tr:last-child td:last-child {
  -moz-border-radius-bottomright:3px;
  -webkit-border-bottom-right-radius:3px;
  border-bottom-right-radius:3px;
}
table tr:hover td {
  background: #f2f2f2;
  background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
  background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);  
}
    </style>
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script>
    <script type="text/javascript" src="js/multi.js"></script>
    <script>
    $( document ).ready(function() {
      $( "#tabs" ).tabs();
      $("#selectQry2").multiselect(); 
    });

    function invitar(){

      s  =  $("#selectQry22").multiselect("getChecked");
      c = '';

      $.each( s, function( key, value ) {
        v=$(this).val();
        c+=v+',';
      });
      
      if(c==''){
        alert("Tienes que seleccionar al menos un cliente");
        return false;
      }

      $("#resultados2").css('display','none');
      $("#resultados-load2").css('display','block');
      url='smsAjax.php';
      $.ajax({
        url:url,
        type: 'POST',
        data: {funcion:'invitar_cliente', c:c},
        success: function(obj){
          $("#resultados-load2").css('display','none');
          alert('Se ha mandado una invitacion.');

        }
      });


    }

    function asignar(){

      s  =  $("#selectQry").multiselect("getChecked");
      sg =  $("#selectQry2").multiselect("getChecked");
      c = '';
      g = '';
      $.each( s, function( key, value ) {
        v=$(this).val();
        c+=v+',';
      });

      $.each( sg, function( key, value ) {
        v=$(this).val();
        g+=v+',';
      });
      
      if(c==''){
        alert("Tienes que seleccionar al menos un cliente");
        return false;
      }
      if(g==''){
        alert("Tienes que seleccionar al menos un grupo");
        return false;
      }
      $("#resultados").css('display','none');
      $("#resultados-load").css('display','block');
      url='smsAjax.php';
      $.ajax({
        url:url,
        type: 'POST',
        data: {funcion:'asignar_grupo', c:c, g:g},
        //dataType: 'JSON',
        success: function(obj){
          $("#resultados-load").css('display','none');
          alert('Se han asignado los clientes a los grupos seleccionados');
        }
      });


    }
    function buscar(busqueda){
      $("#resultados-load-not").css('display','none');
      $("#resultados").css('display','none');
      $("#resultados-load").css('display','block');
      var data = new Object()
      data["funcion"]     = "sms_busqueda";
      data["tipo"]        = busqueda;
      url='smsAjax.php';

      if(busqueda==1){
        data["cmbEstados"]  = $('#cmbEstados').val();
        data["cmbGrupos"]   = $('#cmbGrupos').val();
        data["cmbRubros"]   = $('#cmbRubros').val();
        data["cmbTtienda"]  = $('#cmbTtienda').val();
      }
      if(busqueda==0){
        data["cmbClientes"] = $('#cmbClientes').val();
      }
      

      $.ajax({
        url:url,
        type: 'POST',
        data: data,
        dataType: 'JSON',
        success: function(obj){ 
          if(obj==0){
            $("#resultados-load").css('display','none');
            $("#resultados-load-not").css('display','block');
            return false;
          }
          $("#divbusquedaqry").html('<select id="selectQry" style="width:248px;"></select>');
          $.each( obj, function( key, value ) {
            $('#selectQry').append('<option value="'+value.id+'">'+value.nombre+'</option>');
          });
          $("#selectQry").multiselect();
          $(".ui-multiselect-none").trigger( "click" );
          $("#resultados-load").css('display','none');
          $("#resultados").css('display','block');

        }
      });
    }

    function buscar2(busqueda){
      $("#resultados-load-not2").css('display','none');
      $("#resultados2").css('display','none');
      $("#resultados-load2").css('display','block');
      var data = new Object()
      data["funcion"]     = "sms_busqueda";
      data["tipo"]        = busqueda;
      data["con"]        = 1;
      url='smsAjax.php';

      if(busqueda==1){
        data["cmbEstados"]  = $('#cmbEstados2').val();
        data["cmbGrupos"]   = $('#cmbGrupos2').val();
        data["cmbRubros"]   = $('#cmbRubros2').val();
        data["cmbTtienda"]  = $('#cmbTtienda2').val();
      }
      if(busqueda==0){
        data["cmbClientes"] = $('#cmbClientes2').val();
      }
      

      $.ajax({
        url:url,
        type: 'POST',
        data: data,
        dataType: 'JSON',
        success: function(obj){ 
          if(obj==0){
            $("#resultados-load2").css('display','none');
            $("#resultados-load-not2").css('display','block');
            return false;
          }
          $("#divbusquedaqry2").html('<select id="selectQry22" style="width:248px;"></select>');
          $.each( obj, function( key, value ) {
            $('#selectQry22').append('<option value="'+value.id+'">'+value.nombre+'</option>');
          });
          $("#selectQry22").multiselect();
          $(".ui-multiselect-none2").trigger( "click" );
          $("#resultados-load2").css('display','none');
          $("#resultados2").css('display','block');

        }
      });
    }
    </script>
  </head>
  <body>
  <div id="contenedor" style="float:left;width:720px;">
  <div id="tabs">
  <ul>

    <li><a href="#tabs-1">Asignacion de grupos</a></li>
    <li><a href="#tabs-2">Invitar clientes</a></li>
    <li><a href="#tabs-3">Estatus invitaciones</a></li>
  </ul>
  <div id="tabs-1" style="font-size:11px;">
    <div style="float:left; width:100%; margin:0px 0 5px 0; font-size:18px;">
      Asignacion de clientes y grupos
    </div>
    <div style="float:left; width:100%; margin:15px 0 5px 0; font-size:12px; text-decoration: underline;">
      Busqueda
    </div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      Estado:
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <select id="cmbEstados" style="width:150px;">
        <option value="0" selected>Todos</option>
        <?php 
        if($estados!=0){
          foreach ($estados as $key => $value) { ?>
            <option value="<?php echo $value['idestado']; ?>"><?php echo $value['estado']; ?></option>
          <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      Grupos:
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <select id="cmbGrupos" style="width:150px;">
        <option value="0" selected>Todos</option>
        <?php 
        if($grupos!=0){
          foreach ($grupos as $key => $value) { ?>
            <option value="<?php echo $value['id']; ?>"><?php echo $value['grupo']; ?></option>
          <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div id="bo"></div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      Rubro:
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <select id="cmbRubros" style="width:150px;">
        <option value="0" selected>Todos</option>
        <?php 
        if($rubros!=0){
          foreach ($rubros as $key => $value) { ?>
            <option value="<?php echo $value['idRubro']; ?>"><?php echo $value['nombre']; ?></option>
          <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      Tipo tienda:
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <select id="cmbTtienda" style="width:150px;">
        <option value="0" selected>Todos</option>
        <?php 
        if($ttienda!=0){
          foreach ($ttienda as $key => $value) { ?>
            <option value="<?php echo $value['idTipotienda']; ?>"><?php echo $value['nombre']; ?></option>
          <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div id="bo"></div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <input type="button" value="Buscar" onclick="buscar(1);">
    </div>
    <div style="float:left; width:100%; margin:15px 0 5px 0; font-size:12px; text-decoration: underline;">
      Busqueda por cliente
    </div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      Cliente:
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <select id="cmbClientes" style="width:150px;">
        <option value="0" selected>Selecciona</option>
        <?php 
        if($clientes!=0){
          foreach ($clientes as $key => $value) { ?>
            <option value="<?php echo $value['id']; ?>"><?php echo $value['nombre']; ?></option>
          <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div id="bo"></div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <input type="button" value="Buscar" onclick="buscar(0);">
    </div>
    <div id="bo"></div>
    <div id="resultados-load" style="margin-top:10px; float:left;width:100%; display:none;">
      <img src="images/preloader.gif" >
    </div>
    <div id="resultados-load-not" style="margin-top:10px; background-color: #ffd0d0;border: 1px solid #ff8080;display: none;float: left;padding: 2px 0 2px 5px;width: 100%;">
      No se encontraron clientes.
    </div>
    <div id="resultados" style="float:left;width:100%; display:none;">
      <div style="float:left; width:100%; margin:15px 0 5px 0; font-size:12px; text-decoration: underline;">
        Resultados de la busqueda
      </div>
      <div style="float:left; width:100px; margin:10px 0 5px 0; font-size:12px;">
        Clientes:
      </div>
      <div id="divbusquedaqry" style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
        
      </div>
      <div id="bo"></div>
      <div style="float:left; width:100px; margin:10px 0 5px 0; font-size:12px;">
        Grupos:
      </div>
      <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
        <select id="selectQry2" style="width:248px;">
          <?php 
          if($grupos!=0){
            foreach ($grupos as $key => $value) { ?>
              <option value="<?php echo $value['id']; ?>"><?php echo $value['grupo']; ?></option>
            <?php } ?>
          <?php } ?>
        </select>
      </div>
      <div id="bo"></div>
      <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
        
      </div>
      <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
        <input type="button" value="Asignar" onclick="asignar();">
      </div>
    </div>
  </div>

  <div id="tabs-2" style="font-size:11px;">
    <div style="float:left; width:100%; margin:0px 0 5px 0; font-size:18px;">
      Invitar clientes
    </div>
    <div style="float:left; width:100%; margin:15px 0 5px 0; font-size:12px; text-decoration: underline;">
      Busqueda
    </div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      Estado:
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <select id="cmbEstados2" style="width:150px;">
        <option value="0" selected>Todos</option>
        <?php 
        if($estados2!=0){
          foreach ($estados2 as $key => $value) { ?>
            <option value="<?php echo $value['idestado']; ?>"><?php echo $value['estado']; ?></option>
          <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      Grupos:
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <select id="cmbGrupos2" style="width:150px;">
        <option value="0" selected>Todos</option>
        <?php 
        if($grupos2!=0){
          foreach ($grupos2 as $key => $value) { ?>
            <option value="<?php echo $value['id']; ?>"><?php echo $value['grupo']; ?></option>
          <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div id="bo"></div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      Rubro:
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <select id="cmbRubros2" style="width:150px;">
        <option value="0" selected>Todos</option>
        <?php 
        if($rubros2!=0){
          foreach ($rubros2 as $key => $value) { ?>
            <option value="<?php echo $value['idRubro']; ?>"><?php echo $value['nombre']; ?></option>
          <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      Tipo tienda:
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <select id="cmbTtienda2" style="width:150px;">
        <option value="0" selected>Todos</option>
        <?php 
        if($ttienda2!=0){
          foreach ($ttienda2 as $key => $value) { ?>
            <option value="<?php echo $value['idTipotienda']; ?>"><?php echo $value['nombre']; ?></option>
          <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div id="bo"></div>
    <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
      
    </div>
    <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
      <input type="button" value="Buscar" onclick="buscar2(1);">
    </div>



    <div id="bo"></div>
    <div id="resultados-load2" style="margin-top:10px; float:left;width:100%; display:none;">
      <img src="images/preloader.gif" >
    </div>
    <div id="resultados-load-not2" style="margin-top:10px; background-color: #ffd0d0;border: 1px solid #ff8080;display: none;float: left;padding: 2px 0 2px 5px;width: 100%;">
      No se encontraron clientes.
    </div>
    <div id="resultados2" style="float:left;width:100%; display:none;">
      <div style="float:left; width:100%; margin:15px 0 5px 0; font-size:12px; text-decoration: underline;">
        Resultados de la busqueda
      </div>
      <div style="float:left; width:100px; margin:10px 0 5px 0; font-size:12px;">
        Clientes:
      </div>
      <div id="divbusquedaqry2" style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
        
      </div>
      <div id="bo"></div>
      <div style="float:left; width:100px; margin:5px 0 5px 0; font-size:12px;">
        
      </div>
      <div style="float:left; width:230px; margin:5px 0 5px 0; font-size:12px;">
        <input type="button" value="Invitar" onclick="invitar();">
      </div>
    </div>
  </div>

  <div id="tabs-3" style="font-size:11px;">
    <div style="float:left; width:100%; margin:0px 0 5px 0; font-size:18px;">
      Estatus invitaciones
    </div>
    <div style="float:left; width:100%; margin:15px 0 5px 0; font-size:12px; text-decoration: underline;">
      <?php if($invitados==0){ ?>
        No tienes invitaciones.
      <?php }else{ ?>
      <table cellspacing='0'>
        <!-- Table Header -->
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Tienda</th>
            <th>Estatus</th>
            <th>Fecha invitacion</th>
          </tr>
        </thead>
        <!-- Table Header -->

        <!-- Table Body -->
        <tbody>
          <?php 
            foreach ($invitados as $key => $value) { ?>
              <tr>
                <td><?php echo $value['nombre']; ?></td>
                <td><?php echo $value['nombretienda']; ?></td>
                <td><?php echo $value['estatus']; ?></td>
                <td><?php echo $value['fecha_invitacion']; ?></td>
            </tr>
          <?php  } ?>
        </tbody>
      </table><br><br>
      <?php } ?>
    </div>  
  </div>

  </div>
  </div>
  </body>
</html>