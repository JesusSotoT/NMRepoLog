<?php
    include("../../../netwarelog/webconfig.php");
    header("Content-Type: text/html; charset=utf-8");
    
    $funcion = $_REQUEST['funcion'];
    
    $connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
    $connection->query("CHARACTER SET utf8 COLLATE utf8_general_ci");
    $connection->set_charset("utf8");
        
    $funcion($connection);
    mysqli_close();
    
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------

    function subirArchivo($connection)
    {
        $tabla = "";
        $encabezados="";
        ?>
                    <LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
                    <LINK href="../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
                    <!--    <LINK href="../../../netwarelog/design/default/netwarlog.css"   title="estilo" rel="stylesheet" type="text/css" / -->
                    <?php include('../../../netwarelog/design/css.php');?>
                    <LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

                    <script type="text/javascript" src="../../punto_venta/js/importar_series.js"></script>
                    
                    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
                    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
                    <link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>
        
        <link href="../../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <style>

          .tit_tabla_buscar td
          {
            font-size:medium;
          }

          #logo_empresa /*Logo en pdf*/
          {
            display:none;
          }

          @media print
          {
            #imprimir,#filtros,#excel, #botones
            {
              display:none;
            }
            #logo_empresa
            {
              display:block;
            }
            .table-responsive{
              overflow-x: unset;
            }
            #imp_cont{
              width: 100% !important;
            }
          }
          .btnMenu{
              border-radius: 0; 
              width: 100%;
              margin-bottom: 0.3em;
              margin-top: 0.3em;
          }
          .row
          {
              margin-top: 0.5em !important;
          }
          h5, h4, h3{
              background-color: #eee;
              padding: 0.4em;
          }
          .modal-title{
            background-color: unset !important;
            padding: unset !important;
          }
          .nmwatitles, [id="title"] {
              padding: 8px 0 3px !important;
            background-color: unset !important;
          }
          .select2-container{
              width: 100% !important;
          }
          .select2-container .select2-choice{
              background-image: unset !important;
            height: 31px !important;
          }
          .twitter-typeahead{
            width: 100% !important;
          }
          .tablaResponsiva{
              max-width: 100vw !important; 
              display: inline-block;
          }
          .table tr, .table td{
            border: none !important;
          }
        </style>
        <div class="container">
            <div class="row">
                <div class="col-md-1 col-sm-1">
                </div>
                <div class="col-md-10 col-sm-10">
                    <h3 class="nmwatitles text-center">Importar Series (Excel)</h3>
        <br>
        <br>
        <?php
        $allowed = array('xls','xlsx','xlsm','ods','csv');
        $filename = $_FILES['myfile']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!in_array($ext,$allowed) ) 
        {
            ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>Solo se admiten los archivos con extensión .xls, .xlsx, .xlsm, .ods y .csv</b>
                                        <br><br>
                                        <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>
                                    </div>
                                </div>
        <?php
            //echo $encabezados.$tabla;
            exit();
        }
        else
        {
            $output_dir = "../temp_archivos/";
            if(isset($_FILES["myfile"]))
            {
                //Filter the file types , if you want.
                if ($_FILES["myfile"]["error"] > 0)
                {
                    ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>Hubo un error al cargar el archivo. Inténtelo nuevamente.</b>
                                        <br><br>
                                        <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>
                                    </div>
                                </div>
                    <?php
                    //echo $encabezados.$tabla;
                    exit();
                }
                else
                {
                    //Mueve el archivo a la carpeta temp
                    move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.$_FILES["myfile"]["name"]);
                    //echo $output_dir. $_FILES["myfile"]["name"];
                            
                    include '../Classes/PHPExcel/IOFactory.php';
                    $inputFileName = $output_dir. $_FILES["myfile"]["name"];
                    
                    // Lee el libro de trabajo de excel
                    try 
                    {
                        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($inputFileName);
                    } 
                    catch(Exception $e) 
                    {
                        ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <b>Hubo un error al cargar el archivo. Inténtelo nuevamente.</b>
                                            <br><br>
                                            <input class="btn btn-primary btnMenu" type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
                                        </div>
                                    </div>
                        <?php       
                        unlink($inputFileName);
                        //echo $encabezados.$tabla;
                        exit();
                    }
                    //------------------------------------------------------------------
                    
                                
                    $worksheet = $objPHPExcel->getActiveSheet();
                    $sheet = $objPHPExcel->getSheet(0); 
                    $highestRow = $sheet->getHighestRow(); 
                    $highestColumn = $sheet->getHighestColumn();
                    
                    $caracterInvalido=false;
                    $errores="";
                    $soloNumero = "/[^0-9]/";
                    $soloNumeroPunto = "/[^0-9.]/";
                    $nombreCol= array("ID Lote","Producto","Serie","Telefono");
                    
                    if($highestColumn != "D")
                    {
                        $formatoIncorrecto = true;
                    }
                    if($highestRow < 2)
                    {
                        $hayClientes = false;
                    }
                    //  Barre sobre cada fila en turno
                        for ($row = 2; $row <= $highestRow; $row++)
                        { 
                            //  Mueve a un arreglo el contenido de cada fila
                            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                            NULL,
                                                            TRUE,
                                                            FALSE);
                            $tabla .= '<td style="border-top: 1px solid #888888;"><input type="checkbox" id="chk_'.$row.'" checked></td>';

                         /*   if(preg_match($soloNumero, $rowData[0][3])){
                                $caracterInvalido=true;
                                $errores.= "<br>[Fila: ".$row."] [CP: ".$rowData[0][3]."]";
                            }
                            if(preg_match($soloNumero, $rowData[0][4])){
                                $caracterInvalido=true;
                                $errores.= "<br>[Fila: ".$row."] [Estado: ".$rowData[0][4]."]";
                            }
                            if(preg_match($soloNumero, $rowData[0][5])){
                                $caracterInvalido=true;
                                $errores.= "<br>[Fila: ".$row."] [Municipio: ".$rowData[0][5]."]";
                            }
                            if(preg_match($soloNumeroPunto, $rowData[0][8])){
                                $caracterInvalido=true;
                                $errores.= "<br>[Fila: ".$row."] [Límite/cred.: ".$rowData[0][8]."]";
                            }
                            if(preg_match($soloNumero, $rowData[0][9])){
                                $caracterInvalido=true;
                                $errores.= "<br>[Fila: ".$row."] [Días/cred.: ".$rowData[0][9]."]";
                            } */

                            for($i=0; $i<4; $i++)
                            {
                               /* if($i == 3)
                                {
                                    if(strlen($rowData[0][$i]) > 5)
                                    {
                                        $codigoPostalNoValido = true;
                                    }
                                } */
                                if($i == 1)
                                {
                                    $result = $connection->query("SELECT nombre FROM mrp_producto WHERE idProducto = ".$rowData[0][$i].";");
                                    $row2 = mysqli_fetch_assoc($result);
                                    $tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.utf8_encode($row2['nombre']).'</td>';
                                }else{
                                    $tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.$rowData[0][$i].'</td>';
                                }


                              /*  else if($i == 5)
                                {
                                    $result = $connection->query("SELECT municipio FROM municipios WHERE idmunicipio = ".$rowData[0][$i].";");
                                    $row2 = mysqli_fetch_assoc($result);
                                    $tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.utf8_encode($row2['municipio']).'</td>';
                                }
                                else
                                {
                                    $tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.$rowData[0][$i].'</td>';
                                } */
                                
                                if($i==0 || $i==2 || $i==1)
                                {
                                    if($rowData[0][$i] == "" || $rowData[0][$i] == " ")
                                    {
                                        $hayCamposObligatoriosVacios = true;    
                                    }
                                }
                                if(strstr($rowData[0][$i],"'")==true){$caracterInvalido=true; $errores.= "<br>[fila ".$row."] [".$nombreCol[$i].": ".$rowData[0][$i]."]";}
                                if(strstr($rowData[0][$i],'"')==true){$caracterInvalido=true; $errores.= "<br>[fila ".$row."] [".$nombreCol[$i].": ".$rowData[0][$i]."]";}
                            }
                            $tabla .= " </tr>";
                        }
                        $tabla .= "<input type='hidden' id='contador_filas' value='".$highestRow."'>";
                        
                    if($formatoIncorrecto == true)
                    {
                        unset($tabla);
                        ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>El archivo no parece tener el formato correcto. ¿Estás seguro de que descargaste la <a href='../views/clientes/plantilla.xlsx'>plantilla para importación</a>?</b>
                                        <br><br>
                                        <input class="btn btn-primary btnMenu" type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
                                    </div>
                                </div>
                        <?php
                        unlink($inputFileName);
                        //echo $encabezados.$tabla;
                    }   
                    else
                    {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div style='text-align: right; width: 100%;'><input class="btn btn-primary btnMenu" type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'></div>
                                <br><b><div style='color: black; text-align: left;'>Seleccione las Series a importar.</div></b><br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                                <div class="table-responsive">
                                    <table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 11px; overflow: auto; max-height: 350px; border: 1px solid #98ac31; padding: 10px;'>
                                        <tr>
                                            <th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31; '> </th>
                                            <th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31; '>ID Lote</th>
                                            <th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31; '>Producto</th>
                                            <th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31; '>Serie</th>
                                            <th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31; '>Telefono</th>
                                        </tr>
                                    
                                        <?php
                                        echo $tabla;
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type='button' value='Importar Series' id='btn_importar' onclick='registrarSeries("<?php echo $output_dir. $_FILES["myfile"]["name"]; ?>");' class='btn btn-success btnMenu'>
                            </div>
                        </div>
                        <?php
                    }
                    
                }
            }
        }
    }

    function registraSeries($connection)
    {
        $inputFileName = $_POST['ruta'];
        $check = $_POST['check'];
        
        include '../Classes/PHPExcel/IOFactory.php';
        
        // Lee el libro de trabajo de excel
        try 
        {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } 
        catch(Exception $e) 
        {
            die('Error cargando el archivo "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        //--------------------------------------
                    
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        
        //  Loop through each row of the worksheet in turn
            for ($row = 2; $row <= $highestRow; $row++)
            {
                if(in_array($row, $check)) 
                {
                    //  Read a row of data into an array
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                    NULL,
                                                    TRUE,
                                                    FALSE);
                   // echo 'INSERT INTO mrp_lote_series (idLote, idProducto, serie, telefono) values ("'.$rowData[0][0].'", "'.$rowData[0][1].'", "'.$rowData[0][2].'", "'.$rowData[0][3].'");';
                    $result = $connection->query('INSERT INTO mrp_lote_series (idLote, idProducto, serie, telefono) values ("'.$rowData[0][0].'", "'.$rowData[0][1].'", "'.$rowData[0][2].'", "'.$rowData[0][3].'");');                            
                    //  Insert row data array into your database of choice here
                }
            }
        unlink($inputFileName);
        echo 1;
    }
?>