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

$SQL = "SELECT a.*, concat('PROV-',a.id,' -  ',b.razon_social_sp) nombre FROM constru_altas a inner join constru_info_sp b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=5 AND (a.estatus='Alta' OR a.estatus='Incapacitado');";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $proveedores[]=$row;
    }
  }else{
    $proveedores=0;
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
<div class="row">
<!--
  <div class="col-sm-6">
    <h5>Estimaciones proveedores</h5>
    <div class="row">
      <div class="col-sm-12">
        Periodo del <?php echo $start; ?> al <?php echo $end; ?> <?php echo 'Semana: '.$semana; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>Proveedor:</label>
        <select class="form-control" id="destaver" onchange="cmbest('pro')">
          <option selected="selected" value="0">Selecciona un proveedor</option>
          <?php 
          if($proveedores!=0){
            foreach ($proveedores as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay proveedores dados de alta</option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>Orden de compra:</label>
        <select class="form-control" id="oc_num">
          <option selected="selected" value="0">Selecciona la orden de compra</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>Semana:</label>
        <select class="form-control" id="desta">
          <option selected="selected" value="0">Seleccione una semana</option>
          <?php 
          foreach ($cmbsemanas as $key => $value) { ?>
            <option value="<?php echo $value; ?>">Semana <?php echo $value; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>&nbsp;</label>
        <input class="btn btn-primary btnMenu" type="button" value="Nueva estimacion" onclick="crearestimacion('pro');" style="cursor:pointer;">
      </div>
    </div>
  </div>
  -->
  <div class="col-sm-6">
    <h5>Ver estimaciones proveedores</h5>
    <div class="row">
      <div class="col-sm-6">
        <label>Proveedor:</label>
        <select class="form-control" id="destaver2" onchange="cmbest('pro2')">
          <option selected="selected" value="0">Selecciona un proveedor</option>
          <?php 
          if($proveedores!=0){
            foreach ($proveedores as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay proveedores dados de alta</option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>Estimacion:</label>
        <select class="form-control" id="oc_num2">
          <option selected="selected" value="0">Selecciona la estimacion</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>&nbsp;</label>
        <input class="btn btn-primary btnMenu" value="Ver estimacion" onclick="verest('pro2');" style="cursor:pointer;">
      </div>
      <div class="col-sm-6"></div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12" id="estdestajista">
    
  </div>
</div>
