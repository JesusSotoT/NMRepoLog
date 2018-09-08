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

week_bounds(date('Y-m-d'), $start, $end);

$SQL = "SELECT a.*, concat('PROV-',a.id,' -  ',b.razon_social_sp) nombre FROM constru_altas a inner join constru_info_sp b on b.id_alta=a.id where  a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=5 AND (a.estatus='Alta' OR a.estatus='Incapacitado') order by b.razon_social_sp;";
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

<div class="row">&nbsp;</div>
<div class="panel panel-default" >
  <!-- Panel Heading -->
  <div class="panel-heading">
  <div class="panel-title">Elaboracion Estimacion Proveedores</div>
  </div><!-- End panel heading -->

  <!-- Panel body -->
  <div class="panel-body" >
    <div class="row">
  <div class="col-sm-6">
    <div class="row">
      <div class="col-sm-12">
      <label>Periodo:</label><br>
        <?php echo $start; ?> al <?php echo $end; ?> | <?php echo 'Semana: '.$semana; ?>
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
      <div class="col-sm-6" style="display:none;">
        <label>Semana:</label>
        <select class="form-control" id="desta">
          
         <?php
          $jump=0;
          $semanaatras=$semana;
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
             <option selected="selected" value="<?php echo $anoexact.'-'.$semaexact; ?>">Semana <?php echo $value; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-6">
        <label>Estimar por:</label>
        <div>
        <input type="radio" name="estpor" checked="checked" value="s" style="cursor:pointer;"> Entrada<br>
        <input type="radio" name="estpor" value="o" style="cursor:pointer;"> Total orden de compra
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <label>&nbsp;</label>
         <button style="width:100%" id="btnGenReq"  class="btn btn-primary btn-xm pull-right" onclick="crearestimacion('pro');"> Nueva Estimacion</button>

      </div>
    </div>
  </div>
  <!--
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
-->
      
  </div><!-- ENd panel body -->
</div>
</div>



<div class="row">
  <div class="col-sm-12" id="estdestajista">
    
  </div>
</div>
