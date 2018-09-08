<!-- CSS -->
<style>
  th, td{
    text-align: center;
    vertical-align: middle !important;
  }
</style>
<!-- JS -->
<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="js/eventos.js" type="text/javascript"></script>
<script>
  $(document).ready(function(){
    obtener_eventos(1, <?php echo("'".$instancia)."'"; ?>);
  });
</script>

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <h2 class="title" style="text-align: center;">Ver Eventos</h2>
    </div>
    <div class="col-md-2" style="margin-top: 1.75em;">
      <a href="index.php?c=edu&f=capturar_evento" class="btn btn-primary btn-block">Agregar</a>
    </div>    
  </div>
  <hr>  
  <!-- Alinear tabla -->
  <div class="col-md-8 col-md-offset-2">
    <div class="row">
      <div class="col-md-12">
        <div id="loading">
          <h4 class="black-text">Cargando...</h4>
        </div>
        <div class="col-md-12 table-responsive">
          <table class="table table-striped" id="tabla-eventos">
            <thead>
              <th>Evento</th>
              <th>Fecha Inicio</th>
              <th>Fecha Fin</th>
              <th>Estatus</th>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div> <!-- // Alinear tabla -->
  </div>
</div>