<?php

  $SQL = "SELECT accelog_perfiles.idperfil, accelog_perfiles.nombre, (SELECT COUNT(accelog_usuarios_per.idempleado) FROM accelog_usuarios_per WHERE accelog_usuarios_per.idperfil = accelog_perfiles.idperfil) AS 'count' FROM accelog_perfiles WHERE accelog_perfiles.visible = -1 AND accelog_perfiles.idperfil <> 2 ORDER BY accelog_perfiles.nombre;";
    $result = $mysqli->query($SQL);

    while($row = $result->fetch_array() ) {
      $usuarios[]=$row;
    }

    $SQL = "SELECT id,obra FROM constru_generales WHERE borrado=0 ORDER BY obra;";
    $result = $mysqli->query($SQL);

    while($row = $result->fetch_array() ) {
          $obras[]=$row;
      }
?>

<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css" type="text/css">
<style>
  @media print{
    #imprimir,#filtros,#excel, #botones
    {
      display:none;
    }
    #logo_empresa
    {
      display:block;
    }
    .table-responsive{
      overflow-x: unset;
    }
    #imp_cont{
      width: 100% !important;
    }
  }
  .btnMenu{
    border-radius: 0; 
    width: 100%;
    margin-bottom: 0.3em;
    margin-top: 0.3em;
  }
  .row
  {
      margin-top: 0.5em !important;
  }
  .titulo, h4, h3{
      background-color: #eee;
      padding: 0.4em;
  }
  .modal-title{
    background-color: unset !important;
    padding: unset !important;
  }
  .nmwatitles, [id="title"] {
    padding: 8px 0 3px !important;
    background-color: unset !important;
  }
  .select2-container{
    width: 100% !important;
  }
  .select2-container .select2-choice{
    background-image: unset !important;
    height: 31px !important;
  }
  .twitter-typeahead{
    width: 100% !important;
  }
  .tablaResponsiva{
      max-width: 100vw !important; 
      display: inline-block;
  }
  .table tr, .table td{
    border: none !important;
  }
  .ms-container{
    width: 100% !important;
  }
  .ms-selectable, .ms-selection{
    margin-top: 1em;
  }
</style>


<body>
  <div class="container" style="width:100%">
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Administracion Usuarios - Obras</div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <select id="selobra" class="form-control">
              <option value="0" selected="selected">Selecciona un usuario</option>
              <?php 
                foreach ($usuarios as $key => $r) { ?>
                  <option value="<?php echo $r['idperfil']; ?>"><?php echo $r['nombre']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-sm-4">
          <button onclick="usuario_acceder('<?php echo $modulo; ?>');" class="btn btn-primary btn-sx"> Seleccionar</button>
          </div>
        </div>
        <div class="row" style="padding-top:10px;">
          <div class="col-sm-12">
            <div id="tabletas" style="display:none;">
              <div class="titulo col-xs-6 text-center">Obras sin asignar</div>
              <div class="titulo col-xs-6 text-center">Obras asignadas</div>
              <select id="callbacks" multiple='multiple'>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>


<script>
$( document ).ready(function() {
  
});

function usuario_acceder(modulo){
$('#callbacks').empty();
$("#callbacks").multiSelect("destroy");

      id_usuario = $('#selobra').val();
        $.ajax({
            url:"get_obras_user.php",
            type: 'POST',
            dataType:'JSON',
            data:{id_usuario:id_usuario,modulo:modulo},
            success: function(r){
              console.log(r);
                if(r.success==1){

                  if(r.datono==1){
                    $.each( r.ono, function( key, value ) {
                      $('#callbacks').append('<option value="'+value.id+'">'+value.obra+'</option>');
                    });
                  }else{

                  }
                  if(r.datosi==1){
                    $.each( r.osi, function( key, value ) {
                      $('#callbacks').append('<option value="'+value.id+'" selected>'+value.obra+'</option>');
                    });
                  }else{

                  }
                  $('#callbacks').multiSelect({
                      afterSelect: function(values){
                        $.ajax({
                          url:"saveobrauser.php",
                          type: 'POST',
                          data:{id_usuario:id_usuario,values:values,opt:1},
                          success: function(r){
                          
                          }
                        });
                    },
                      afterDeselect: function(values){
                        $.ajax({
                          url:"saveobrauser.php",
                          type: 'POST',
                          data:{id_usuario:id_usuario,values:values,opt:2},
                          success: function(r){
                          
                          }
                        });
                    }
                  });
                  $('#tabletas').css('display','block');
                  
                }else{
                   // alert('Error al seleccionar esta obra');
                }
                
                //
            }
        });
    }
</script>