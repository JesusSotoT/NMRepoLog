<?php

    if(!$a){

       //SI se trata de organizaciones actualizar datos de la tabla ospos_app_config
  		if($_SESSION['idestructura']==1){
  			$munEstado = "SELECT municipios.municipio, estados.estado FROM municipios INNER JOIN estados ON municipios.idestado = estados.idestado AND municipios.idmunicipio =".$_POST['i187'];
  			$munEstado = $conexion->consultar($munEstado);
  			$munEstado = mysql_fetch_array($munEstado);

  			$sql = "update app_config set value='".$_POST['i2']."' where `key`='company'";
  			$conexion->consultar($sql);
  			$sql = "update app_config set value='".$_POST['i185'].", ".$_POST['i189'].", ".$munEstado['municipio'].", ".$munEstado['estado']."' where `key`='address'";
  			$conexion->consultar($sql);
  			$sql = "update rest_app_config set value='".$_POST['i2']."' where `key`='company'";
  			$conexion->consultar($sql);
  			$sql = "update rest_app_config set value='".$_POST['i185'].", ".$_POST['i189'].", ".$munEstado['municipio'].", ".$munEstado['estado']."' where `key`='address'";
  			$conexion->consultar($sql);

        //Validación RFC en mayúculuas
        $sql ="update organizaciones set RFC='".strtoupper($_POST['i183'])."' where RFC='".$_POST['i183']."'";
        $conexion->consultar($sql);
  			unset($sql,$munEstado);


  		}

    }

?>
