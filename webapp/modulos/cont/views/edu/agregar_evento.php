<!-- CSS -->
<link rel="stylesheet" href="css/multiple_select.css" type="text/css"/>
<!-- JS -->
<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="../cont/js/jquery-ui.js"></script>
<script type="text/javascript" src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/multi_select.js"></script>
<script src="js/moment.js" type="text/javascript"></script>
<script src="js/eventos.js" type="text/javascript"></script>
<script>
  $(document).ready(function(){
    obtener_lista_usuarios();
    $('#fecha_antes').datepicker({dateFormat: 'dd-mm-yy'});
    $('#fecha_despues').datepicker({dateFormat: 'dd-mm-yy'});
    if($('.title').attr('data-id') != null || $('.title').attr('data-id') != undefined){
      obtener_evento($('.title').attr('data-id'));
    }
    $('#mostrar_correo').on('change', function(){
      if ($(this).val() == 1) {
        $('#correo-container').show();
      } else {
        $('#correo-container').hide();
      }
    });
  });
</script>
<style>
  #mensaje{
    resize: none;
    height: 7em;
    overflow-y: scroll;
  }
</style>
<div class="container">
  <h2 class="title" <?php echo($id); ?> style="text-align: center;">
    <?php echo($titulo);?>
  </h2>
  <hr>  
  <!-- Alinear formulario -->
  <div class="col-md-8 col-md-offset-2" id="form-container">
    <form method="POST" id="formNotificacion">
      <div class="row">

        <!-- Clientes -->
        <div class="col-sm-12 col-md-6">
          <div class="form-group">
            <label for="clientes">Clientes:</label><br>
            <select id="clientes" style="display: none;" multiple> 
            </select>
          </div>
        </div> <!-- // Clientes -->

        <!-- Titulo -->
        <div class="col-sm-12 col-md-6">
          <div class="form-group">
            <label for="">Titulo del Evento:</label>
            <input type="text" class="form-control" name="titulo" id="titulo">
          </div> 
        </div> <!-- // Titulo -->
      </div>

      <div class="row">
      <!-- Fecha Inicio -->
        <div class="col-xs-12 col-md-6">
          <div class="form-group">
            <label for="fecha_antes">Fecha inicio:</label>
            <input type="text" class="form-control" name="fecha_antes" id="fecha_antes" placeholder="Fecha inicial">
          </div> 
        </div> <!-- // Fecha Inicio -->
        
        <!-- Fecha Fin -->
        <div class="col-xs-12 col-md-6">
          <div class="form-group">
            <label for="fecha_despues">Fecha fin:</label>
            <input type="text" class="form-control" name="fecha_despues" id="fecha_despues" placeholder="Fecha fin">
          </div> 
        </div> <!-- // Fecha Fin -->
      </div>

      <div class="row">
        <!-- Instancia -->
        <input type="hidden" class="form-control" name="instancia" id="instancia" 
        value="<?php echo($instancia); ?>">

        <!-- Estatus -->
        <div class="col-md-3">
          <div class="form-group">
            <label for="">Activo:</label><br>
            <select class="form-control" name="estatus" id="estatus" required> 
              <option value="1">Si</option>
              <option value="0">No</option>
            </select>
          </div> 
        </div> <!-- // Estatus -->

        <!-- Mostrar Correo -->
        <div class="col-md-3">
          <div class="form-group">
            <label for="">¿Enviar Correo?</label><br>
            <select class="form-control" id="mostrar_correo">
              <option value="0" selected>No</option>
              <option value="1">Si</option>
            </select>
          </div> 
        </div> <!-- // Mostrar Correo --> 
      </div>

      <section id="correo-container" style="display: none;">
        <hr>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="text" name="email" id="email" class="form-control" 
              title="Si desea mandar el correo a muchos destinatarios utilize una coma para separarlos."
              placeholder="email1@domain.com,email2@domain.com,etc..">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="asunto">Asunto:</label>
              <input type="text" name="asunto" id="asunto" class="form-control">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="mensaje">Mensaje:</label>
              <textarea name="mensaje" id="mensaje" class="form-control"></textarea>
            </div>
          </div>
        </div>
      </section>

      <div class="row">
        <div class="col-md-3 col-md-offset-6" style="margin-bottom: 1em;">
          <a onclick="agregar_editar_evento();" class="btn btn-primary btn-block">
            <?php echo($boton);?>
          </a>
        </div>
        <div class="col-md-3" style="margin-bottom: 1em;">
          <a href="index.php?c=edu&f=ver_eventos" class="btn btn-default btn-block">Volver</a>
        </div>
      </div>
    </form> <!-- // Formulario -->
  </div> <!-- // Alinear formulario -->
</div>