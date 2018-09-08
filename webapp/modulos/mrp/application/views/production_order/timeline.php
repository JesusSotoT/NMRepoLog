<!--<span id="orden_produccion"><?php //echo $detalle;?></span>-->

  <div style="border: 1px solid #303030; background-color: #F0F0F0;
    float: left;
    font-size: 11px;
    margin-top: 10px;
    padding: 5px 8px;
    width: auto;">
    Inicio
    </div>
    <div style="float:left;width:10px;border-top:1px solid #333333;margin-top:23px;">
        
    </div>
    <?php foreach ($etapas as $row) { ?>
    <div onclick="openEtapa('<?php echo $row["id"]; ?>','<?php echo $row["cadti"]; ?>','<?php echo $idord; ?>');" style="border: 1px solid #303030;
    cursor: pointer; background-color: #91C313;
    float: left;
    font-size: 11px;
    margin-top: 10px;
    padding: 5px 8px;
    width: auto;">

    
      <?php echo 'Etapa - '.$row["etapa"]; ?>
    </div>
    <div style="float:left;width:10px;border-top:1px solid #333333;margin-top:23px;">
        
    </div>
   
  <?php } ?>
  <div style="border: 1px solid #303030; background-color: #F0F0F0;
    float: left;
    font-size: 11px;
    margin-top: 10px;
    padding: 5px 8px;
    width: auto;">
    Fin
  </div>

  <div id="procesos-cont" style="clear:both; float:left;margin: 0 0 30px 0; display:none;">

  </div>
