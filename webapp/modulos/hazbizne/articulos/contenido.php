<?php
    include('../../../netwarelog/catalog/conexionbd.php');  //Se conecta a la base de datos del cliente
    include_once('funcionesBD/conexionbda.php');  //Se instancia a la base de datos del usuario
        
    $consult = new Consult;
    $conection = $consult->conection($servidor, 'nmdevel', 'nmdevel', 'nmdev_common');

    $index = $_GET["id_articulo_click"];
    
    $result = $consult->desplegar_articulos($conection,$index);
    if($result->num_rows >= 1) {
        while($row=$result->fetch_array(MYSQLI_ASSOC)) {
            $titulo_articulo = $row["titulo"];
            $contenido_articulo = $row["contenido"];
        }
    } else {
        $articulos = 0;
    }

    
    $result = $consult->desplegar_articulos_comentarios($conection,$index);
    if($result->num_rows >= 1) {
        while($row=$result->fetch_array(MYSQLI_ASSOC)) {
            $comentarios[] = $row["comentario"];
        }
    } else {
        $articulos = 0;
    }

?>

<!Doctype html>
<html>
    <head>
        <title>Art&iacute;culos de proveedores</title>
        
        <link rel="stylesheet" type="text/css" href="css/hb_art.css">
        <link rel="stylesheet" type="text/css" href="css/estilo.css">
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="css/hb_art_comentarios.css">
        
        
        <script type="text/javascript" src="../../sms/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="../../sms/js/jquery-ui.js"></script>
        <script type="text/javascript" src="../../sms/js/multi.js"></script>
        <script type="text/javascript" src="js/hb_art_comentarios.js"></script>
        
        
    </head>
    
    <body>
        <div id="contenedor">
            <div id="tabs">
                <!-- INICIANDO CON EL TEMPLATE-->
                <div id="title" class="ctitle_t">
                    <div class="ctitle_t_p"><?php echo $titulo_articulo?></div>
                    <!--<div style="float:right; width:20px; margin:6px 0 6px 0; font-size:12px;">
                        <img onclick="desplegar('43');" style="cursor:pointer;" src="images/add.png">
                    </div>-->
                </div>
                <div id="callback">
                </div>
                <div id="load" class="loadsart">
                    <div id="cont__art">
                        <div id="foto__desc">
                            <div id="ft__ft">undefined</div>
                            <div id="des__des"><?php echo $contenido_articulo?></div>
                        </div>
                    </div>
                    <div id="cont__resp">
                        <div id="resp__desc">
                            <div id="txt__area">
                                <textarea placeholder="Escribe un comentario" id="val_area"></textarea>
                            </div>
                            <div id="btn__res">
                                <input type="button" onclick="respuesta_com(<?php echo $index?>);" value="Responder" id="br__br">
                                <img src="images/wait.gif" id="wa__wa">
                            </div>
                        </div>
                    </div>
                    <div id="cont__coments">
                        <div id="resp__coments">
                            <div id="tit_com">Comentarios</div>
                            <div id="coms_container"><!--
                            <div style="float:left; width:700px;margin:5px 0 5px 0;" id="area_43_com">
                                <textarea align="center" style="width:660px;border: 1px none #cecece;min-height:50px;margin-left:20px; resize: none; color:#color:#666;" id="val_43_area" disabled="">Me interesa la informaciÃ³n</textarea>
                            </div>
                            <div style="float:left; width:700px;margin:5px 0 5px 0;" id="area_43_com">
                                <textarea align="center" style="width:660px;border: 1px none #cecece;min-height:50px;margin-left:20px; resize: none; color:#color:#666;" id="val_43_area" disabled="">Me interesa la opciÃ³n</textarea>
                            </div>
                            -->
                            <?php 
                                foreach ($comentarios as $comentario) {
                                    ?>
                                    <div class="area__com">
                                        <textarea align="center" id="val_x_area" disabled=""><?php echo $comentario?></textarea>
                                    </div>
                                    <?php
                                }
                            ?>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- FINALIZANDO CON EL TEMPLATE-->
            </div>
        </div>
    </body>
</html>