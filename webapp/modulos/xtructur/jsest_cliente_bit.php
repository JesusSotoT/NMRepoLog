<?php

//Sacar lista de años semanas
    $a1=explode('-', $obra_ini);
    $a1=$a1[0]*1;

    $b1=explode('-', $obra_fin);
    $b1=$b1[0]*1;

    $ini_anos=array();


    $cmbsemanas=array();
    if($a1<$b1){
      for ($i=$a1; $i <= $b1; $i++) { 
        $ini_anos[]=$i;
      }

      $numanos = count($ini_anos);
      $x=1;
      foreach ($ini_anos as $key => $value) {
        if($key+1==1){
          $fsemanos = NumeroSemanasTieneUnAno($value);
          $fsemactual = getweek($obra_ini);
          for ($i=$fsemactual; $i <= $fsemanos; $i++) { 
            if(strlen($i)==1){
              $add='0'.$i;
            }else{
              $add=$i;
            }
            $cmbsemanas[]=$value.'-'.$add;
          }
        }else if ( $key+1>1 && $key+1!=$numanos ){
          $fsemanos = NumeroSemanasTieneUnAno($value);
          for ($i=1; $i <= $fsemanos; $i++) { 
            if(strlen($i)==1){
              $add='0'.$i;
            }else{
              $add=$i;
            }
            $cmbsemanas[]=$value.'-'.$add;
          }
        }else if ( $key+1==$numanos ) {
          $fsemanos = NumeroSemanasTieneUnAno($value);
          $fsemactual = getweek($obra_fin);
          for ($i=1; $i <= $fsemactual; $i++) { 
            if(strlen($i)==1){
              $add='0'.$i;
            }else{
              $add=$i;
            }
            $cmbsemanas[]=$value.'-'.$add;
          }
        }else{
          echo "Error en las fechas de inicio y fin de obra";
          exit();
        }
      }
    }else if($a1==$b1){
      $fsemanos = NumeroSemanasTieneUnAno($a1);
      $fsemaini = getweek($obra_ini);
      $fsemafin = getweek($obra_fin);
      for ($i=$fsemaini; $i <= $fsemafin; $i++) { 
        if(strlen($i)==1){
          $add='0'.$i;
        }else{
          $add=$i;
        }
        $lolo=getStartAndEndDate($add,$a1);
        $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';

      }
    }else{
      echo "Error en las fechas de inicio y fin de obra";
      exit();
    }

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

  $SQL = "SELECT a.id, a.nombre FROM constru_agrupador a where a.id_obra='$idses_obra' AND a.borrado=0;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $agrupadores[]=$row;
    }
  }else{
    $agrupadores=0;
  }

  $SQL = "SELECT id, concat('ESTCLI-',id,' - Semana: ',xxano) as estimacion FROM constru_estimaciones_bit_cliente where id_obra='$idses_obra' order by id desc;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $estimas[]=$row;
    }
  }else{
    $estimas=0;
  }


?>

<div class="row">
<!--
  <div class="col-sm-6">
    <h5>Estimaciones cliente</h5>
    <div class="row">
      <div class="col-sm-12">
        Periodo del <?php echo $start; ?> al <?php echo $end; ?> <?php echo 'Semana: '.$semana; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>Agrupador:</label>
        <select class="form-control" id="cargaagr" onchange="chagru2();">
          <option selected="selected" value="0">Seleccione un agrupador</option>
          <?php 
          if($agrupadores!=0){
            foreach ($agrupadores as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay agrupadores dados de alta</option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>Area:</label>
        <select class="form-control" id="cargaesp">
          <option selected="selected" value="0">Selecciona un area</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>Semana:</label>
        <select class="form-control" id="sema">
          <option selected="selected" value="0">Seleccione una semana</option>
          <?php 
          foreach ($cmbsemanas as $key => $value) { ?>
            <option value="<?php echo $value; ?>">Semana <?php echo $value; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>&nbsp;</label>
        <input class="btn btn-primary btnMenu" type="button" value="Nueva estimacion" onclick="crearestimacion('cli');" style="cursor:pointer;">
      </div>
    </div>
  </div>
  -->
  <div class="col-sm-6">
    <h5>Ver estimaciones cliente</h5>
    <div class="row">
      <div class="col-sm-6">
        <label>Estimacion:</label>
        <select class="form-control" id="estimacion_num">
          <option selected="selected" value="0">Seleccione una estimacion</option>
            <?php 
            if($estimas!=0){
              foreach ($estimas as $k => $v) { ?>
                <option value="<?php echo $v['id']; ?>"><?php echo $v['estimacion']; ?></option>
              <?php } ?>
            <?php }else{ ?>
              <option value="0">No hay estimaciones a cliente creadas</option>
            <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>&nbsp;</label>
        <input class="btn btn-primary btnMenu" type="button" value="Ver estimacion" onclick="verest('cli');" style="cursor:pointer;">
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>&nbsp;</label>
        <input class="btn btn-primary btnMenu" type="button" value="Graficar" onclick="graficar_ret('est_cliente',<?php echo $idses_obra?>)" style="cursor:pointer;">
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12" id="estdestajista">
    
  </div>
</div>

