<?php
session_start();
//Actualización de Tipos de Cambio -- ACONTIA
include_once("../../netwarelog/catalog/conexionbd.php");
require("tipos_de_cambio.php");

  //Regla de Reportes Graficos Visibles
  $rg1=0; //Grafico de Productos mas Vendidos
  $rg2=0; //Grafico de Tendencia de Ventas
?>

<!--  DASHBOARD - TRANSVERSAL -->
<html>
<head>

    <!--  ##### BOOTSTRAP & FONT ###### -->
    <link href="../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
    <!--  ##### END & FONT ###### -->

    <!--  ##### BEGIN: BOOTSTRAP & JQUERY ###### -->
		<script src="../../libraries/jquery.min.js"></script>
		<script src="../../libraries/jquery.mobile.touch_events.min.js"></script>
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>
    <script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
		<!--  ##### END: BOOTSTRAP & JQUERY ###### -->


    <div class="panel panel-default">
<!-- APLICACIONES FAVORITAS-->
      <div class="panel-heading">Aplicaciones Favoritas</div>
        <div class="panel-body">
            <?php
              $idempleado = $_SESSION["accelog_idempleado"]; //$catalog_id_utilizado
              $htmltabla="";
              //Carga los menus del usuario
               $sQuery = "select a.idmenu, a.nombre,a.idmenupadre, a.url, ifnull((select idmenu from dashboard_contenido where idempleado=$idempleado and idtipo=1 and idmenu=a.idmenu),-1) sel
                            from  accelog_menu a
                              where idmenu in (select idmenu from accelog_perfiles_me
                                where idperfil in (select idperfil from accelog_usuarios_per where idempleado=$idempleado))";
                          $result = $conexion->consultar($sQuery);
                          while($rs = $conexion->siguiente($result)){
                              if ($rs["idmenu"]=='1572') $rg1=1;
                              if ($rs["idmenu"]=='1569') $rg2=1;
                              if ($rs["idmenu"]=='2106') $rg1=1;
                              if ($rs["idmenu"]=='2106') $rg2=1;
                              if ($rs["sel"]<>-1){
                                $link=$rs["url"];
                                $nombre=$rs["nombre"];
                                $idmenu=$rs["idmenu"];
                                $htmltabla.="<button type='button' class='btn btn-default btn-lg' onclick='abremenu(\"$link\",\"$nombre\",\"$idmenu\");'>".$nombre."</button>";
                              }
                          }
              $conexion->cerrar_consulta($result);
              $htmltabla.="";
              echo $htmltabla;
            ?>
            <button type="button" class="btn btn-default btn-lg" data-toggle="modal" data-target="#popupApps">
              <span class='fa fa-plus' aria-hidden="true"></span> Apps
            </button>
        </div>

        <?php
        //Información Relevante
        require("informacionrelevante.php");

        //Notificaciones
        require("notificaciones.php");
        ?>

  </div>
</head>
<body>



<!-- ACTUALIZANDO FAVORITOS-->
<div class="modal fade" id="popupApps" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title" id="myModalLabel">Administrador de Aplicaciones Favoritas</h4>
      </div>
      <div id="nuevaAventura" class="modal-body">
            <form role="form">
              <div class="form-group">
                <?php
                  //Recupera Variables
                  $idempleado = $_SESSION["accelog_idempleado"]; //$catalog_id_utilizado

                  $htmltabla="<div class='list-group'>
                                <li class='list-group-item active'>
                                  Agregar / Eliminar
                                </li>
                                <li class='list-group-item'>";
                  //Carga los menus del usuario         <input type="checkbox" aria-label="...">
                   $sQuery = "select a.idmenu, a.nombre,a.idmenupadre,a.url,
                              ifnull((select idmenu from dashboard_contenido where idempleado=$idempleado and idtipo=1 and idmenu=a.idmenu),-1) sel
                                from  accelog_menu a
                                  where  a.url<>'' and idmenu in (select idmenu from accelog_perfiles_me
                                    where idperfil in (select idperfil from accelog_usuarios_per
                                    where idempleado=$idempleado))";
                              $result = $conexion->consultar($sQuery);
                              while($rs = $conexion->siguiente($result)){
                                  if ($rs["sel"]==-1){
                                    $checked="";
                                  }else{
                                    $checked=" checked='checked' ";
                                  }

                                  $htmltabla.="<spam class='list-group-item'><input type='checkbox' name=chk[] value=".$rs["idmenu"]." $checked>  ".$rs["nombre"]."</spam>";
                              }
                  $conexion->cerrar_consulta($result);
                  $htmltabla.="</li></div>";

                  echo $htmltabla;
                ?>
              </div>
           </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="botonAventura" onClick="guardarysalir();">Guardar y Salir</button>
      </div>
    </div>
  </div>
</div>


<!-- FUNCIONES JS-->
<script type="text/javascript">
  function abremenu(linkjs,nombrejs,idjs){
    window.parent.agregatab(linkjs,nombrejs,"",idjs);
    window.parent.preguntar=true;
  }

  function guardarysalir(){
      // array que contendrá los valores seleccionados
      var valores = new Array();
      // array con los checkboxes y que están marcados
      var inputs = document.querySelectorAll("[name='chk[]']:checked");
      // atravesamos el array de inputs
      for (var x = 0; x < inputs.length; x++) {
          // insertando su valores en el array de valores
          valores.push(inputs[x].value);
      }
      var ms=valores.join(",");
      window.location.href = "actualizamenus.php?ms="+ms;
  }
</script>
