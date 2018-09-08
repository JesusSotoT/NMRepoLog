<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
 <!-- <link rel="stylesheet" type="text/css" href="../../../../netwarelog/design/default/netwarlog.css" / --> 
<?php include('../../../../netwarelog/design/css.php');?>
<LINK href="../../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
    
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../../punto_venta/js/jquery.alphanumeric.js"></script>
<script type="text/javascript" src="../../../punto_venta/js/importar_clientes.js"></script>

<!-- ///////////////////////////// -->   
     <div height="20">
        <div class="nmwatitles">&nbsp;Importar clientes (INADEM)</div>
        <br>
        </div>
    </div>
    <br>
<!-- ///////////////////////////// -->

    <center>
        
        <div style="width: 80%; display: table; text-align: left; margin-top: 50px;">
            <div class='listadofila' title='Subir archivo' style="width: 90%; display: table; padding: 10px">
                
                <!-- ///////////////////////////// -->
                <center>
                    
                    <div id='upload_div' style='display: table; width: 80%;' title='Subir' >
                        <div style="width: 100%; display: table;">
                            
                            <div align="left" style="display: table-cell; width: 50%;">
                                <img src='../../img/xls_icon.gif'> <a href='plantilla_inadem.xlsx'>Descarga la plantilla para los clientes Inadem</a>
                            </div>
                            
                          <!--  <div align="left" style="display: table-cell; width: 50%;">
                                <img src='../../img/xls_icon.gif'> <a href='estados_municipios.xlsx'>Descarga catálogo de Estados y municipios</a>
                            </div> -->
                        </div>
                        <br>
                        <div style='color: #FF0000;'>(No elimine ninguna columna del formato. Los campos marcados con asterisco son obligatorios)</div>
                        <br>
                            <?php
                                $url = '../../funcionesBD/importar_clientes_inadem.php';
                            ?>
                            <form id="myForm" action=<?php echo $url; ?> method="post" enctype="multipart/form-data">
                                <input type='hidden' value='subirArchivo' name='funcion'>
                                <input type="file" size="100" name="myfile" style="width: 100%;"><br>
                                <div align="right"><input type="submit" value="Previsualizar" id="btnarchivo" class="nminputbutton_color2"></div>
                            </form>
                    </div>
                
                </center>
            </div>
                <!-- ///////////////////////////// -->
            Nota:<br>
            ·La lista de clientes no debe rebasar los 900 elementos por carga.<br>
            ·No se deben insertar comillas (") ni comillas simples (') en ningún campo<br>
            ·En el campo de Código Postal solo deben insertarse  números y un máximo de 5 números<br>
            ·En los campos de datos crediticios, estado y municipio solo deben insertarse números<br>
            ·El valor en estado y municipio deben tomarse del archivo Catalogo de Estados y Municipio<br>
                
        </div> 
    </center>