<?php
    include('../../../netwarelog/catalog/conexionbd.php');  //Se conecta a la base de datos del cliente
    include_once('funcionesBD/conexionbda.php');  //Se instancia a la base de datos del usuario
    
    
    $consult = new Consult();
    $conection = $consult -> conection($servidor, 'nmdevel', 'nmdevel', 'nmdev_common');
    
    $result = $consult->articulos($conection);
    if($result->num_rows>=1) {
        while($row=$result->fetch_array(MYSQLI_ASSOC)) {
            $articulos[] = $row;
        }
    }else{
        $articulos = 0;
    }
?>

<html>
    <head>
        <title>Art&iacute;culos de proveedores</title>
        
        <link title="estilo" rel="stylesheet" type="text/css" href="css/estilo.css">
        <link title="estilo" rel="stylesheet" type="text/css" href="css/jquery-ui.css">
        
        <style type="text/css">
            #tabs.ui-widget-content{
                border: none;
            }
            
            .ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br {
                border-bottom-right-radius: 0;
            }
            
            .ui-corner-all, .ui-corner-bottom, .ui-corner-left, .ui-corner-br {
                border-bottom-left-radius: 0;
            }
            
            .ui-corner-all, .ui-corner-top, .ui-corner-right, .ui-corner-tr {
                border-top-right-radius: 0;
            }
            
            .ui-corner-all, .ui-corner-top, .ui-corner-left, .ui-corner-tl {
                border-top-left-radius: 0;
            }
            
            table a:link {
                color: #666;
                font-weight: bold;
                text-decoration: none;
            }
            
            table a:visited {
                color: #999999;
                font-weight: bold;
                text-decoration: none;
            }
            
            table a:active, table a:hover {
                color: #bd5a35;
                text-decoration: underline;
            }
            
            table {
                font-family: Arial, Helvetica, sans-serif;
                color: #666;
                font-size: 11px;
                text-shadow: 1px 1px 0px #fff;
                background: #eaebec;
                margin: 0px;
                border: #ccc 1px solid;
                -moz-border-radius: 3px;
                -webkit-border-radius: 3px;
                border-radius: 3px;
                -moz-box-shadow: 0 1px 2px #d1d1d1;
                -webkit-box-shadow: 0 1px 2px #d1d1d1;
                box-shadow: 0 1px 2px #d1d1d1;
            }
            
            table th {
                padding: 15px 25px 13px 15px;
                border-top: 1px solid #fafafa;
                border-bottom: 1px solid #fafafa;
                background: #ededed;
                background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
                background: -moz-linear-gradient(top, #ededed, #ebebeb);
            }
            
            table th:first-child {
                text-align: left;
                padding-left: 20px;
            }
            
            table:first-child th:first-child{
                -moz-border-radius-topleft: 3px;
                -webkit-border-top-left-radius: 3px;
                border-top-left-radius: 3px;
            }
            
            table:first-child th:last-child {
                -moz-border-radius-topright: 3px;
                -webkit-border-top-right-radius: 3px;
                border-top-right-radius: 3px;
            }
            
            table tr {
                text-align: center;
                padding-left: 20px;
            }
            
            table td:first-child {
                text-align: left;
                padding-left: 20px;
                border-left: 0;
            }
            
            table td {
                padding: 14px;
                border-top: 1px solid #ffffff;
                border-bottom: 1px solid #e0e0e0;
                border-left: 1px solid #e0e0e0;
                background: #fafafa;
                background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
                background: -moz-linear-gradient(top, #fbfbfb, #fafafa);
            }
            
            table tr.even td {
                background: #f6f6f6;
                background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
                background: -moz-linear-gradient(top, #f8f8f8, #f6f6f6);
            }
            
            table tr:last-child td {
                border-bottom: 0;
            }
            
            table tr:last-child td:first-child {
                -moz-border-radius-bottomleft: 3px;
                -webkit-border-bottom-left-radius: 3px;
                border-bottom-left-radius: 3px;
            }
            
            table tr:last-child td:last-child {
                -moz-border-radius-bottomright: 3px;
                -webkit-border-bottom-right-radius: 3px;
                border-bottom-right-radius: 3px;
            }
            
            table tr:hover td {
                background: #f2f2f2;
                background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
                background: -moz-linear-gradient(top, #f2f2f2, #f0f0f0);
            }
        </style>
        
        <!--<script type="text/javascript" src="js/jquery-1-10-2-min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script type="text/javascript" src="js/multi.js"></script>-->
    </head>
    
    <body>
        <form method="get" action="contenido.php">
            <div id="contenedor" style="float:left; width: 720px;">
                <div id="tabs-1" style="font-size: 11px;">
                    <div style="float: left; width: 100%; margin: 0px 0 15px 0; font-size: 18px;">
                        Art&iacute;culos publicados
                    </div>
                    <input type="hidden" name="id_articulo_click" id="id_articulo_click" value="">
                    <?php
                        foreach ($articulos as $key => $value) { ?>
                        <div class="ctitle_t" id="title_<?php echo $value['id_articulo']; ?>_t" style="float: left; width: 700px; background-color: #fafafa; color: #000;">
                            <div style="float: left; width: 670px; margin: 5px 0 5px 10px; font-size: 12px;">
                                <!--<input type="hidden" name="lblArticulo" value="<?php echo $value['id_articulo']; ?>" />-->
                                <input type="submit" value="Ver" onclick="buttonver_click('<?php echo $value['id_articulo']; ?>')">
                                <?php echo $value['titulo']; ?>
                            </div>
                        </div>

                        <div class="loadsart" id="load_<?php echo $value['id_articulo']; ?>_load" style="float: left; display: none;">
                            <div id="lod_" style="float: left; width: 700px; margin: 20px 0 20px 0; text-align: center;">
                                Cargando...
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </form>
    </body>
    <script type="text/javascript">
        function buttonver_click(id_articulo_click){
            document.getElementById("id_articulo_click").value=id_articulo_click;
        }
    </script>
</html>
