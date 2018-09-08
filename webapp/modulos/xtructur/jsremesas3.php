<?php
    $sestmp=time();
    $semana = strftime('%V');
    $ano = NumeroSemanasTieneUnAno(date('Y'));

week_bounds(date('Y-m-d'), $start, $end);


$SQL = "SELECT a.id, a.nombre from constru_especialidad a inner join constru_agrupador b on b.id=a.id_agrupador
 where b.id_obra='$idses_obra' group by a.nombre";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $areas[]=$row;
    }
  }else{
    $areas=0;
  }
  



  
?>

<body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="navbar navbar-default"  style="margin-top:10px;">
          <div class="navbar-header">
              <div class="navbar-brand" style="color:#333;">Cuentas por pagar</div>
          </div>
        </div>
      </div>
    </div>

<div class="panel panel-default" >
  <!-- Panel Heading -->
  <div class="panel-heading">
  <div class="panel-title">Pagos</div>
  </div><!-- End panel heading -->

  <!-- Panel body -->
  <div class="panel-body" >
    <div class="row">
  <div class="col-sm-6">

    <div class="row">
      <div class="col-sm-12">
        Periodo del <?php echo $start; ?> al <?php echo $end; ?> <?php echo 'Semana: '.$semana; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
       <button class="btn btn-primary btn-xm" onclick="actualizarRemesa();"> Actualizar pagos</button>

      </div>
      <!--
      <div class="col-sm-6">
        <label>Monto:</label>
        <input class="form-control" id="monto" type="text" placeholder="$0.00">
      </div>
      -->
    </div>
    <!--
    <div class="row">
      <div class="col-sm-6">
        <input class="btn btn-primary btnMenu" type="button" value="Crear" onclick="crearremesa();" style="cursor:pointer;">
      </div>
      <div class="col-sm-6"></div>
    </div>
    -->
  </div>
  <!--
  <div class="col-sm-6">
    <h5>Visualizar Remesas</h5>
    <div class="row">
      <div class="col-sm-6">
        <label>Remesa:</label>
        <select class="form-control" id="desta2">
          <option selected="selected" value="0">Seleccione una remesa</option>
          <?php 
          if($remesas!=0){
            foreach ($remesas as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>">Remesa semana: <?php echo $v['semana']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay remesas</option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>&nbsp;</label>
        <input class="btn btn-primary btnMenu" type="button" value="Ver remesa" onclick="verremesa();" style="cursor:pointer;">
      </div>
    </div>
  </div>
  -->
</div>
      
  </div><!-- ENd panel body -->
</div>




<div class="row">
  <div class="col-sm-12" id="estdestajista">
  </div>
</div>

</body>

<script>
$.ajax({
  url:"jsest_remesas_view.php",
  type: 'POST',
  data:{ano:'<?php echo $ano; ?>',username_global:'<?php echo $username_global; ?>',id_username_global:'<?php echo $id_username_global; ?>'},
  success: function(r){
    $('#estdestajista').html(r);
    $('#rea').val(monto);
  }
});
</script>

