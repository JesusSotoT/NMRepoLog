<?php

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

<h4>Estado de resultados</h4>
<h5>Filtros</h5>
<div class="row">
  <div class="col-sm-3">
    <label>Mes:</label>
    <select id="mes" class="form-control">
      <option selected="selected" value="0">Seleccione un mes</option>
      <?php 
      for($x=1; $x<=12; $x++){ ?>
        <option value="<?php echo $x; ?>">Mes <?php echo $x; ?></option>
     <?php } ?>
    </select>
  </div>
  <div class="col-sm-3">
    <label>Tipo de reporte:</label>
    <select class="form-control" id="estadorep">
      <option selected="selected" value="0">Seleccione un reporte</option>
        <option value="1">Estimaciones al cliente</option>
        <option value="2">Almacen</option>
        <option value="3">Egresos</option>
        <option value="4">Ingresos</option>
    </select>
  </div>
  <div class="col-sm-3">
    <label>&nbsp;</label>
    <input class="btn btn-primary btnMenu" type="button" value="Ver" onclick="estadorep();" style="cursor:pointer;">
  </div>
</div>
<h5>&nbsp;</h5>
<div class="row">
  <div class="col-sm-12" id="estdestajista">
    
  </div>
</div>



