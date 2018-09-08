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

                    <script type="text/javascript" src="../../punto_venta/js/importar_clientes_inadem.js"></script>
                    
                    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
                    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
                    <link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>
        
        
        <div class="nmwatitles"> Importar clientes (Excel) </div>
            <br>
        <br>
        <?php
        $allowed = array('xls','xlsx','xlsm','ods','csv');
        $filename = $_FILES['myfile']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!in_array($ext,$allowed) ) 
        {
            ?>
                                <center>
                                    <div style='width: 80%; margin-top: 50px;'>
                                    <b>Solo se admiten los archivos con extensión .xls, .xlsx, .xlsm, .ods y .csv</b>
                                    <br><br>
                                    <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='nminputbutton'>
                                    </div>
                                </center>
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
                                <center>
                                    <div style='width: 80%; margin-top: 50px;'>
                                    <b>Hubo un error al cargar el archivo. Inténtelo nuevamente.</b>
                                    <br><br>
                                    <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='nminputbutton'>
                                    </div>
                                </center>
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
                                    <center>
                                        <div style='width: 80%; margin-top: 50px;'>
                                        <b>Hubo un error al cargar el archivo. Inténtelo nuevamente.</b>
                                        <br><br>
                                        <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
                                        </div>
                                    </center>
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
                    
                    $hayCamposObligatoriosVacios = false;
                    $codigoPostalNoValido = false;
                    $formatoIncorrecto = false;
                    $hayClientes = true;
                    $caracterInvalido=false;
                    $EstadoInvalido=false;
                    $MunicipioInvalido=false;
                    $errores="";
                    $soloNumero = "/[^0-9]/";
                    $soloNumeroPunto = "/[^0-9.]/";
                    $nombreCol= array("Nombre","Direccion","Exterior","interior","Colonia","CP","Pais","Estado","Municipio","Ciudad","Email","RFC","Razon Social","Regimen","Folio Inadem","Convocatoria","Vitrina","Cupon","Mon. Beneficio","Mon. Aportacion","Organismo Interno","Promotor","Resp. NWM","Fecha","Instancia","Representante Legal");
                    
                    if($highestColumn != "AA")
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

                            if(preg_match($soloNumero, $rowData[0][5])){
                                $caracterInvalido=true;
                                $errores.= "<br>[Fila: ".$row."] [CP: ".$rowData[0][5]."]";
                            }

                            $estado=trim($rowData[0][7]);
                            $munici=trim($rowData[0][8]);
                           
                            ////////Obtencion del estado
                            $resultEstado = $connection->query("SELECT idestado FROM estados WHERE estado = '".$estado."';");
                            $rowEstado = mysqli_fetch_assoc($resultEstado);
                            $idEstado=$rowEstado['idestado'];
                            
                            if($idEstado=='' || $idEstado==0){
                                $EstadoInvalido=true;
                                $errores.= "<br>[Fila: ".$row."] [Estado: ".$rowData[0][7]."]";
                            }
                            ///////Obtencion del minicipio
                            $resultMunicipio = $connection->query("SELECT idmunicipio FROM municipios WHERE municipio = '".$munici."';");
                            $rowMunicipio = mysqli_fetch_assoc($resultMunicipio);
                            $idMunicipio=$rowMunicipio['idmunicipio']; 

                            if($idMunicipio=='' || $idMunicipio==0){
                                $MunicipioInvalido=true;
                                $errores.= "<br>[Fila: ".$row."] [Municipio: ".$rowData[0][8]."]";      
                            }
                          /*  if(preg_match($soloNumero, $rowData[0][4])){
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

                            for($i=0; $i<27; $i++)
                            {
                                /*if($i == 3)
                                {
                                    if(strlen($rowData[0][$i]) > 5)
                                    {
                                        $codigoPostalNoValido = true;
                                    }
                                } */
                              /*  if($i == 4)
                                {
                                    $result = $connection->query("SELECT estado FROM estados WHERE idestado = ".$rowData[0][$i].";");
                                    $row2 = mysqli_fetch_assoc($result);
                                    $tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.utf8_encode($row2['estado']).'</td>';
                                }
                                else if($i == 5)
                                {
                                    $result = $connection->query("SELECT municipio FROM municipios WHERE idmunicipio = ".$rowData[0][$i].";");
                                    $row2 = mysqli_fetch_assoc($result);
                                    $tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.utf8_encode($row2['municipio']).'</td>';
                                }
                                else
                                {  */
                                    $tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.$rowData[0][$i].'</td>';
                               // }//
                               /* if($i==0 || $i==4 || $i==5 )
                                { 
                                    if($rowData[0][$i] == "" || $rowData[0][$i] == " ")
                                    {
                                        $hayCamposObligatoriosVacios = true;    
                                    }
                                } */
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
                                <center>
                                    <div style='width: 80%; margin-top: 50px;'>
                                    <b>El archivo no parece tener el formato correcto. ¿Estás seguro de que descargaste la <a href='../views/clientes/plantilla.xlsx'>plantilla para importación</a>?</b>
                                    <br><br>
                                    <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
                                    </div>
                                </center>
                        <?php
                        unlink($inputFileName);
                        //echo $encabezados.$tabla;
                    }   
                    else if($hayCamposObligatoriosVacios == true)
                    {
                        unset($tabla);
                        ?>
                                <center>
                                    <div style='width: 80%; margin-top: 50px;'>
                                    <b>Hay campos obligatorios vacíos. Recuerde que los campos con asterisco son obligatorios.</b>
                                    <br><br>
                                    <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
                                    </div>
                                </center>
                        <?php
                        unlink($inputFileName);
                        //echo $encabezados.$tabla;
                    }
                    else if($codigoPostalNoValido == true)
                    {
                        unset($tabla);
                        ?>
                                <center>
                                    <div style='width: 80%; margin-top: 50px;'>
                                    <b>Uno o más códigos postales parecen no ser válidos. Recuerda que estos deben tener 5 números solamente.</b>
                                    <br><br>
                                    <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
                                    </div>
                                </center>
                        <?php
                        unlink($inputFileName);
                        //echo $encabezados.$tabla;
                    }
                    else if($hayClientes == false)
                    {
                        unset($tabla);
                        ?>
                                <center>
                                    <div style='width: 80%; margin-top: 50px;'>
                                    <b>No parece haber algún cliente en el archivo qué importar.</b>
                                    <br><br>
                                    <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
                                    </div>
                                </center>
                        <?php
                        unlink($inputFileName);
                        //echo $encabezados.$tabla;
                    }
                    else if($caracterInvalido == true)
                    {
                        unset($tabla);
                        ?>
                                <center>
                                    <div style='width: 80%; margin-top: 50px;'>
                                    <b>No se han podido agregar sus clientes</b>
                                    <br>
                                    <b>Hay caracteres invalidos en:</b>
                                    <br>
                                    <?php echo $errores; ?>
                                    <br><br>
                                    <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
                                    </div>
                                </center>
                        <?php
                        unlink($inputFileName);
                        //echo $encabezados.$tabla;
                    }                   
                    else if($EstadoInvalido == true)
                    {
                        unset($tabla);
                        ?>
                                <center>
                                    <div style='width: 80%; margin-top: 50px;'>
                                    <b>No se han podido agregar sus clientes</b>
                                    <br>
                                    <b>Hay Estados invalidos en:</b>
                                    <br>
                                    <?php echo $errores; ?>
                                    <br><br>
                                    <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
                                    </div>
                                </center>
                        <?php
                        unlink($inputFileName);
                        //echo $encabezados.$tabla;
                    }
                    else if($MunicipioInvalido == true)
                    {
                        unset($tabla);
                        ?>
                                <center>
                                    <div style='width: 80%; margin-top: 50px;'>
                                    <b>No se han podido agregar sus clientes</b>
                                    <br>
                                    <b>Hay Municipios invalidos en:</b>
                                    <br>
                                    <?php echo $errores; ?>
                                    <br><br>
                                    <input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
                                    </div>
                                </center>
                        <?php
                        unlink($inputFileName);
                        //echo $encabezados.$tabla;
                    }
                    else
                    {
                        ?>
                        <center>
                            <div style='width: 80%; margin-top: 50px;'>
                            <div style='text-align: right; width: 100%;'><input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'></div>
                            <br><b><div style='color: black; text-align: left;'>Seleccione los clientes a importar.</div></b><br>
                            <table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 11px; overflow: auto; max-height: 350px; border: 1px solid #98ac31; padding: 10px;'>
                            <tr>
                                <th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31; '> </th>
                                <th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31; '>Nombre</th>
                                
                                <th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31; '>Dirección</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Exterior</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Interior</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Colonia</th>
                                <th class='nmcatalogbusquedatit' width=5%  style='border-bottom: 1px solid #98ac31;  '>CP</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Pais</th>
                                <th class='nmcatalogbusquedatit' width=5%  style='border-bottom: 1px solid #98ac31; '>No. de estado</th>
                                <th class='nmcatalogbusquedatit' width=5%  style='border-bottom: 1px solid #98ac31; '>No. de municipio</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Ciudad</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Email</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Telefono</th>

                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>RFC</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Razon Social</th>
                                
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Regimen</th>
                                <!-- INADEM -->
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Fo.INADEM</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Convocatoria</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Vitrina</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Cupon</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>M.Bene.</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>M.Apo.</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Org.Interno</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Promotor</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Res.NWM</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Fecha</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Instancia</th>
                                <th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Legal</th>
 
    
                            </tr>
                        
                        <?php
                        echo $tabla;
                        ?>
                            </table>
                            </div>
                            <br>
                            <input type='button' value='Importar clientes' id='btn_importar' onclick='registrarClientes("<?php echo $output_dir. $_FILES["myfile"]["name"]; ?>");' class='nminputbutton_color2'>
                        </center>
                        <?php
                    }
                    
                }
            }
        }
    }

    function registraClientes($connection)
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

                    $estado=trim($rowData[0][7]);
                    $munici=trim($rowData[0][8]);
                   
                    ////////Obtencion del estado
                    $resultEstado = $connection->query("SELECT idestado FROM estados WHERE estado = '".$estado."';");
                    $rowEstado = mysqli_fetch_assoc($resultEstado);
                    $idEstado=$rowEstado['idestado'];
                    ///////Obtencion del minicipio
                    $resultMunicipio = $connection->query("SELECT idmunicipio FROM municipios WHERE municipio = '".$munici."';");
                    $rowMunicipio = mysqli_fetch_assoc($resultMunicipio);
                    $idMunicipio=$rowMunicipio['idmunicipio']; 
               
                    //////direccion
                     //$direccion = $rowData[0][1].' '.$rowData[0][2];    

                    $extint='';
                    $direccion='';
                    if($rowData[0][3]!=''){
                        $extint=$rowData[0][2].' Int. '.$rowData[0][3];
                    }else{
                        $extint=$rowData[0][2];
                    }
                    
                    $direccion = $rowData[0][1].' '.$extint;

                    $result = $connection->query('INSERT INTO comun_cliente (nombre, direccion,colonia, cp, idEstado, idMunicipio, email, celular, limite_credito, dias_credito,rfc) values ("'.$rowData[0][0].'", "'.$direccion.'","'.$rowData[0][4].'", "'.$rowData[0][5].'", "'.$idEstado.'", "'.$idMunicipio.'", "'.$rowData[0][10].'", "'.$rowData[0][11].'", "0", "0","'.$rowData[0][12].'");');   

                    $idComCliente=$connection->insert_id; 

                    $resultFact = $connection->query('INSERT INTO comun_facturacion (nombre,rfc,razon_social,correo,pais,regimen_fiscal,domicilio,num_ext,cp,colonia,estado,ciudad,municipio) values ("'.$idComCliente.'","'.$rowData[0][12].'","'.$rowData[0][13].'","'.$rowData[0][10].'","'.$rowData[0][6].'","'.$rowData[0][14].'","'.$rowData[0][1].'","'.$extint.'","'.$rowData[0][5].'","'.$rowData[0][4].'","'.$idEstado.'","'.$rowData[0][9].'","'.$rowData[0][8].'")');                    
                    //  Insert row data array into your database of choice here
                    $resultInadem = $connection->query('INSERT INTO comun_cliente_inadem (idCliente,folio_inadem,convocatoria,vitrina,cupon,monto_beneficio,monto_aportacion,organismo_inter,promotor,resp_nwm,fecha_entrega,instancia,resp_legal) values ("'.$idComCliente.'","'.$rowData[0][15].'","'.$rowData[0][16].'","'.$rowData[0][17].'","'.$rowData[0][18].'","'.$rowData[0][19].'","'.$rowData[0][20].'","'.$rowData[0][21].'","'.$rowData[0][22].'","'.$rowData[0][23].'","'.$rowData[0][24].'","'.$rowData[0][25].'","'.$rowData[0][26].'")');    


                    $idComCliente='';
                   // exit();
                }
            }
        unlink($inputFileName);
        echo 1;
    }
?>