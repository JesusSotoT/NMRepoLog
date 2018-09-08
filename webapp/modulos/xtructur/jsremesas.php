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
<div style="float:left; width:800px;">
  <div style="float:left; width:390px;">
  <fieldset style="width: 380px; height:80px; border-color: #ffffff;margin:0 0 10px; -1px;">
    <legend>
      Remesas
    </legend>
      <table style="font-size:12px;">
     <tr>
        <td width="530">Periodo del <?php echo $start; ?> al <?php echo $end; ?> <?php echo 'Semana: '.$semana; ?></td>
      </tr>
      <tr>
          <td>
            <select id="desta">
              <option selected="selected" value="0">Seleccione una semana</option>
              <?php 
              for($x=1; $x<=$ano; $x++){ ?>
                <option value="<?php echo $x; ?>">Semana <?php echo $x; ?></option>
             <?php } ?>
            </select>
            <input type="button" value="Crear." onclick="crearremesa();" style="cursor:pointer;">
          </td>
      </tr>
    </table>
  </fieldset>
  </div>
<!--
  <div style="float:left; width:390px; margin:0 0 0 20px;">
  <fieldset style="width: 380px; height:80px; border-color: #ffffff;margin:0 0 10px; -1px;">
  <legend>
    Ver remesas por semana
  </legend>
    <table style="font-size:14px;">
    <tr>
        <td>
          <select id="ssem">
            <option selected="selected" value="0">Selecciona una semana</option>
            <?php 
              for($x=1; $x<=$ano; $x++){ ?>
                <option value="<?php echo $x; ?>">Semana <?php echo $x; ?></option>
             <?php } ?>
          </select>
        </td>
        <input type="button" value="Ver" onclick="verremesa();" style="cursor:pointer;">
    </tr>
  </table>
  </fieldset>
  </div>
-->
</div>
<div id="preload" style="display:none;">
  Cargando...
</div>
<div id="estdestajista">

</div>

