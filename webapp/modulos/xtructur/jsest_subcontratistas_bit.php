<?php
//Sacar lista de años semanas
    $a1=explode('-', $obra_ini);
    $a1=$a1[0]*1;

    $b1=explode('-', $obra_fin);
    $b1=$b1[0]*1;

    $ini_anos=array();

    $semana = strftime('%V');
    $elano=date('Y');
    week_bounds(date('Y-m-d'), $start, $end);


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

            $lolo=getStartAndEndDate($add,$value);
            $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';
          }
        }else if ( $key+1>1 && $key+1!=$numanos ){
          $fsemanos = NumeroSemanasTieneUnAno($value);
          for ($i=1; $i <= $fsemanos; $i++) { 
            if(strlen($i)==1){
              $add='0'.$i;
            }else{
              $add=$i;
            }
            $lolo=getStartAndEndDate($add,$value);
            $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';
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
            $lolo=getStartAndEndDate($add,$value);
            $cmbsemanas[]=$add.' ('.$lolo[0].' - '.$lolo[1].')';
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

    
    $ano = NumeroSemanasTieneUnAno(date('Y'));






 $SQL = "SELECT a.*, concat('SUBC-',a.id,' -  ',b.razon_social_sp) nombre FROM constru_altas a inner join constru_info_sp b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=4 AND (a.estatus='Alta' OR a.estatus='Incapacitado');";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $maestros[]=$row;
    }
  }else{
    $maestros=0;
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

  $SQL = "SELECT a.id, a.nombre FROM constru_agrupador a where a.id_obra='$idses_obra' AND a.borrado=0;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $agrupadores[]=$row;
    }
  }else{
    $agrupadores=0;
  }


?>

<div class="row">
<!--
  <div class="col-sm-6">
    <h5>Estimaciones subcontratistas</h5>
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
        <select class="form-control" id="cargaesp" onchange="chesp2();">
          <option selected="selected" value="0">Selecciona un area</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>Especialidad:</label>
        <select class="form-control" id="cargaare" onchange="charea2();">
          <option selected="selected" value="0">Selecciona una especialidad</option>
        </select>
      </div>
      <div class="col-sm-6">
        <label>Partida:</label>
        <select class="form-control" id="cargapart">
          <option selected="selected" value="0">Selecciona una partida</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>Subcontratista:</label>
        <select class="form-control" id="desta">
          <option selected="selected" value="0">Seleccione un subcontratista</option>
          <?php 
          if($maestros!=0){
            foreach ($maestros as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay subcontratistas dados de alta</option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>Semana:</label>
        <select class="form-control" id="sema">
          <option selected="selected" value="0">Seleccione una semana</option>
          <?php 
          $jump=0;
          $semanaatras=$semana-2;
          if($semanaatras<=0){
            $semanaatras=0;
          }
          
          foreach ($cmbsemanas as $key => $value) { 
            $expano=explode('(', $value);
            $anoexact=$expano=explode('-', $expano[1]);
            $anoexact=$anoexact[0];

            $expsema=explode(' ', $value);
            $semaexact=$expsema[0];

            if($anoexact!=$elano){
              continue;
            }
            if($semaexact<$semanaatras){
              continue;
            }
            if($jump>0){
              continue;
            }
            if($elano==$anoexact && $semana==$semaexact){
              $jump++;
            }

            ?>
            <option value="<?php echo $value; ?>">Semana <?php echo $value; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <input class="btn btn-primary btnMenu" type="button" value="Nueva estimacion" onclick="crearestimacion('sub');" style="cursor:pointer;">
      </div>
      <div class="col-sm-6">
        <label></label>
      </div>
    </div>
  </div>
  -->
  <div class="col-sm-6">
    <h5>Ver estimaciones subcontratistas</h5>
    <div class="row">
      <div class="col-sm-6">
        <label>Subcontratista:</label>
        <select class="form-control" id="destaver" onchange="cmbest('sub')">
          <option selected="selected" value="0">Selecciona un subcontratista</option>
          <?php 
          if($maestros!=0){
            foreach ($maestros as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay subcontratistas dados de alta</option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>Estimacion:</label>
        <select class="form-control" id="estimacion_num">
          <option selected="selected" value="0">Selecciona la estimacion</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>&nbsp;</label>
        <input class="btn btn-primary btnMenu" value="Ver estimacion" onclick="verest('sub');" style="cursor:pointer;">
      </div>
      <div class="col-sm-6"></div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12" id="estdestajista">
    
  </div>
</div>

