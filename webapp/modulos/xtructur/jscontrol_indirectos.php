<?php

if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
  }else{
      $cookie_xtructur = unserialize($_COOKIE['xtructur']);
      $id_obra = $cookie_xtructur['id_obra'];
      $obra_ini = $cookie_xtructur['obra_ini'];
      $obra_fin = $cookie_xtructur['obra_fin'];


  }


$start    = new DateTime($obra_ini);
$start->modify('first day of this month');
$end      = new DateTime($obra_fin);
$end->modify('first day of next month');
$interval = DateInterval::createFromDateString('1 month');
$period   = new DatePeriod($start, $interval, $end);


  $semana = strftime('%V');
  $ano = NumeroSemanasTieneUnAno(date('Y'));
  week_bounds(date('Y-m-d'), $start, $end);

  $SQL = "SELECT a.*, concat('DEST-',a.id,' -  ',b.nombre,' ',b.paterno,' ',b.materno) nombre FROM constru_altas a inner join constru_info_tdo b on b.id_alta=a.id where a.id_obra='$idses_obra' and a.borrado=0 AND a.id_tipo_alta=2;";
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


?>
<div class="row">&nbsp;</div>
<div class="panel panel-default" >
  <!-- Panel Heading -->
  <div class="panel-heading">
  <div class="panel-title">Control de indirectos</div>
  </div><!-- End panel heading -->

  <!-- Panel body -->
  <div class="panel-body" >
      <div class="row">
        <div class="col-sm-3">
          <label>Mes:</label>
          <select class="form-control" id="mes">
            <option selected="selected" value="0">Seleccione un mes</option>
            <?php 
              
            foreach ($period as $dt) {
               switch($dt->format("m")){
               case 1:$x='Enero '.$dt->format("Y");
               break;

               case 2:$x='Febrero '.$dt->format("Y");

               break;
               case 3:$x='Marzo '.$dt->format("Y");
               break;
               case 4:$x='Abril '.$dt->format("Y");
               break;
               case 5:$x='Mayo '.$dt->format("Y");
               break;
               case 6:$x='Junio '.$dt->format("Y");
               break;
               case 7:$x='Julio '.$dt->format("Y");
               break;
               case 8:$x='Agosto '.$dt->format("Y");
               break;
               case 9:$x='Septiembre '.$dt->format("Y");
               break;
               case 10:$x='Octubre '.$dt->format("Y");
               break;
               case 11:$x='Noviembre '.$dt->format("Y");
               break;
               case 12:$x='Diciembre '.$dt->format("Y");
               break;

               case 13:$x='Septubre '.$dt->format("Y");

               break;
               }?>

              <option value='<?php echo $dt->format('Y-m'); ?>'>Mes <?php echo $x ?></option>
           <?php } ?>
          </select>
        </div>
        <div class="col-sm-3" style="padding-top: 25px;">
           <button class="btn btn-primary btn-xm" onclick="controlind();"> Ver</button>

        </div>
      </div>
      
  </div><!-- ENd panel body -->
</div>



<div class="row">
  <div class="col-sm-12" id="estdestajista">
    
  </div>
</div>


