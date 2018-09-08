<?php

  //Generando fechas
  $fecha = date('Y-m-d');
  $initDate = strtotime ( '-7 day' , strtotime ( $fecha ) ) ;
  $initDate = date ( 'Y-m-d' , $initDate );
  $instancia="";
  $idempleado = $_SESSION["accelog_idempleado"];
  if(isset($_SESSION["accelog_nombre_instancia"])){
    $instancia=$_SESSION["accelog_nombre_instancia"];
  }
  $finalDate=$fecha;
  //echo "Del: ".$initDate." Al: $finalDate";

    //Obtiene Informacion
    $sql  = "select * from dashboard_comunica
              where (instancia='$instancia' or instancia=-1)
                and (fechainicio<='$fecha 23:59:59' and fechafin>='$fecha 00:00:00')
                and (idempleado='$idempleado' or idempleado=-1);";
    $contenedores="<div class=\"panel-heading\">Notificaciones Importantes</div>";
    $msg="";

    $np=1;
    $result = $conexion->consultar($sql);
    while($rs = $conexion->siguiente($result)){
      $msg=$rs{"msg"};
      $contenedores.="<div class=\"well\">
                        $msg $np
                      </div>";
      $np++;
    }
    $conexion->cerrar_consulta($result);

  echo $contenedores;

?>
