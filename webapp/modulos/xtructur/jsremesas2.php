<?php
    $sestmp=time();
    $semana = strftime('%V');
    $ano = NumeroSemanasTieneUnAno(date('Y'));

week_bounds(date('Y-m-d'), $start, $end);

$SQL = "SELECT id, semana, fecha FROM constru_bit_remesa a where a.id_obra='$idses_obra' AND estatus=2 order by semana desc;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $remesas[]=$row;
    }
  }else{
    $remesas=0;
  }

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

<div class="row">&nbsp;</div>

<div class="panel panel-default" >
  <!-- Panel Heading -->
  <div class="panel-heading">
  <div class="panel-title">Cuentas pagadas</div>
  </div><!-- End panel heading -->

  <!-- Panel body -->
  <div class="panel-body" style="display:none;">
      <div class="row">
  <div class="col-md-7">
    <div class="row">
      <div class="col-md-6">

        <select class="form-control" id="desta2">
          <option selected="selected" value="0">Seleccione una remesa (pago)</option>
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
      <div class="col-md-6">

         <button style="width:100%" id="btnGenReq"  class="btn btn-primary btn-xm pull-right" onclick="vercheques();"> Ver Pago</button>


      </div>
    </div>
  </div>
</div>
      
  </div><!-- ENd panel body -->
</div>



<div class="row">
  <div class="col-sm-12" id="estdestajista">
    
  </div>
</div>

<script>
$( document ).ready(function() {
    vercheques();
});
</script>

