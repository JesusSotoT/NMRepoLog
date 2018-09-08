<?php
    $sestmp=time();
    $semana = strftime('%V');
    $ano = NumeroSemanasTieneUnAno(date('Y'));

week_bounds(date('Y-m-d'), $start, $end);

$SQL = "SELECT id, semana, fecha FROM constru_bit_cobros a where a.id_obra='$idses_obra' AND estatus=1 order by semana desc;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $remesas[]=$row;
    }
  }else{
    $remesas=0;
  }

 $SQL = "SELECT * FROM forma_pago;";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    while($row = $result->fetch_array() ) {
      $fpago[]=$row;
    }
  }else{
    $fpago=0;
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

$startm    = new DateTime($obra_ini);
$startm->modify('first day of this month');
$endm      = new DateTime($obra_fin);
$endm->modify('first day of next month');
$intervalm = DateInterval::createFromDateString('1 month');
$periodm   = new DatePeriod($startm, $intervalm, $endm);


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
?>

<div class="row">&nbsp;</div>
<div class="panel panel-default" >
  <!-- Panel Heading -->
  <div class="panel-heading">
  <div class="panel-title">Cuentas Cobradas</div>
  </div><!-- End panel heading -->

  <!-- Panel body -->
  <div class="panel-body" >
      <div class="row">
        <div class="col-sm-3 col-xs-8">
    <label>Semana:</label>
      <select class="form-control" id="filtro_semana" onchange="filtros('achica','sem');">
        <option selected="selected" value="0">Todas</option>
        <?php 
        if($cmbsemanas!=0){
          foreach ($cmbsemanas as $key => $value) { 
            $expano=explode('(', $value);
            $anoexact=$expano=explode('-', $expano[1]);
            $anoexact=$anoexact[0];

            $expsema=explode(' ', $value);
            $semaexact=$expsema[0];
            ?>
            <option value="<?php echo $anoexact.''.$semaexact; ?>">Semana <?php echo $value; ?></option>
          <?php } ?>
        <?php }else{ ?>
          <option value="0">No hay Estimaciones</option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-3 col-xs-8">
    <label>Mes:</label>
      <select class="form-control" id="filtro_mes" onchange="filtros('achica','mes');">
        <option selected="selected" value="0">Todos</option>
        <?php 
        if($periodm!=0){
          foreach ($periodm as $dt) { ?>
            <option value="<?php echo $dt->format("Y-m"); ?>"><?php echo $dt->format("Y-m"); ?></option>
          <?php } ?>
        <?php }else{ ?>
          <option value="0">No hay Estimaciones</option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-3 col-xs-8">
    <label>Estado:</label>
      <select class="form-control" id="filtro_estatus" onchange="filtros('achica','est');">
        <option selected="selected" value="x">Todos</option>
            <option value="0">Pendientes</option>
            <option value="1">Autorizadas</option>
            <option value="2">Canceladas</option>
      </select>
    </div>
     <div class="col-sm-3">
        <label>Metodo de pago:</label>
        <select id="filtro_proveedor" class="form-control" onchange="filtros('achica','est');">
          <option selected="selected" value="0">Todos</option>
          <?php 
          if($fpago!=0){
            foreach ($fpago as $k => $v) { ?>
              <option value="<?php echo $v['idFormapago']; ?>"><?php echo utf8_encode($v['nombre']); ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay formas de pago disponibles</option>
          <?php } ?>
        </select>
      </div>
  <!--<div class="col-md-7">
    <div class="row">
      <div class="col-md-6">
        <label>Selecciona la semana:</label><br>
        <select class="form-control" id="desta2">
          <option selected="selected" value="0">Seleccione un cobro</option>
          <?php 
          if($remesas!=0){
            foreach ($remesas as $k => $v) { ?>
              <option value="<?php echo $v['id']; ?>">Cobro semana: <?php echo $v['semana']; ?></option>
            <?php } ?>
          <?php }else{ ?>
            <option value="0">No hay Cobros</option>
          <?php } ?>
        </select>
      </div>
      <div class="col-md-6">
        <label>&nbsp;</label>
         <button style="width:100%"  class="btn btn-primary btn-xm pull-right" onclick="vercheques2();" style="cursor:pointer;"> Generar Requisicion</button>

      </div>
    </div>
  </div>-->
</div>
      
  </div><!-- ENd panel body -->
</div>


<div class="row">
  <div class="col-sm-12" id="estdestajista">
    
  </div>
</div>

<script>
$( document ).ready(function() {
    vercheques2();
});
</script>
