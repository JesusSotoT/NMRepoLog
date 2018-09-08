
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
		$encabezados = ' <LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
					<LINK href="../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
					<script type="text/javascript" src="../../punto_venta/js/importar_clientes.js"></script>
					
					<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
					<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
					<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>
		
		
		<div height="20" class="descripcion"> Importar clientes (Excel) </div>
		    <br>
		<br>';
	
		$allowed = array('xls','xlsx','xlsm','ods','csv');
		$filename = $_FILES['myfile']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if(!in_array($ext,$allowed) ) 
		{
		   $tabla .= "	<center>
									<div style='width: 80%; margin-top: 50px;'>
									<b>Solo se admiten los archivos con extensión .xls, .xlsx, .xlsm, .ods y .csv</b>
									<br><br>
									<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
									</div>
								</center>";
			echo $encabezados.$tabla;
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
				 	$tabla .= "	<center>
									<div style='width: 80%; margin-top: 50px;'>
									<b>Hubo un error al cargar el archivo. Inténtelo nuevamente.</b>
									<br><br>
									<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
									</div>
								</center>";
					echo $encabezados.$tabla;
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
						$tabla .= "	<center>
										<div style='width: 80%; margin-top: 50px;'>
										<b>Hubo un error al cargar el archivo. Inténtelo nuevamente.</b>
										<br><br>
										<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
										</div>
									</center>";
								
						unlink($inputFileName);
						echo $encabezados.$tabla;
						exit();
					}
					//------------------------------------------------------------------
					$tabla .= "	<center>
									<div style='width: 80%; margin-top: 50px;'>
									<div style='text-align: right; width: 100%;'><input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'></div>
									<br><b><div style='color: #0000FF; text-align: left;'>Seleccione los clientes a importar.</div></b><br>
									<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 11px; overflow: auto; max-height: 350px; border: 1px solid #006efe; padding: 10px;'>
									<tr>
										<th width=5% style='border-bottom: 1px solid #006efe; '> </th>
										<th width=15% style='border-bottom: 1px solid #006efe; '>Nombre</th>
										
										<th width=15% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD;'>Dirección</th>
										<th width=10% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD;'>Colonia</th>
										<th width=5%  style='border-bottom: 1px solid #006efe; background-color: #DDDDDD; '>CP</th>
										<th width=5%  style='border-bottom: 1px solid #006efe; '>No. de estado</th>
										<th width=5%  style='border-bottom: 1px solid #006efe; '>No. de municipio</th>
										
										<th width=10% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD;'>Email</th>
										<th width=10% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD;'>Celular</th>
										
										<th width=10% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD;'>Límite crédito</th>
										<th width=10% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD; '>Días crédito</th>
									</tr>";
								
					$worksheet = $objPHPExcel->getActiveSheet();
					$sheet = $objPHPExcel->getSheet(0); 
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
					
					$hayCamposObligatoriosVacios = false;
					$codigoPostalNoValido = false;
					$formatoIncorrecto = false;
					$hayClientes = true;
					
					if($highestColumn != "J")
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
							for($i=0; $i<10; $i++)
							{
								if($i == 3)
								{
									if(strlen($rowData[0][$i]) > 5)
									{
										$codigoPostalNoValido = true;
									}
								}
								if($i == 4)
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
								{
									$tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.$rowData[0][$i].'</td>';
								}
								if($i==0 || $i==4 || $i==5)
								{
									if($rowData[0][$i] == "" || $rowData[0][$i] == " ")
									{
										$hayCamposObligatoriosVacios = true;	
									}
								}
							}
							$tabla .= "	</tr>";
						}
						$tabla .= "<input type='hidden' id='contador_filas' value='".$highestRow."'>";
						
					if($formatoIncorrecto == true)
					{
						$tabla = "	<center>
									<div style='width: 80%; margin-top: 50px;'>
									<b>El archivo no parece tener el formato correcto. ¿Estás seguro de que descargaste la <a href='../views/clientes/plantilla.xlsx'>plantilla para importación</a>?</b>
									<br><br>
									<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
									</div>
								</center>";
						unlink($inputFileName);
						echo $encabezados.$tabla;
					}	
					else if($hayCamposObligatoriosVacios == true)
					{
						$tabla = "	<center>
									<div style='width: 80%; margin-top: 50px;'>
									<b>Hay campos obligatorios vacíos. Recuerde que los campos con asterisco son obligatorios.</b>
									<br><br>
									<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
									</div>
								</center>";
						unlink($inputFileName);
						echo $encabezados.$tabla;
					}
					else if($codigoPostalNoValido == true)
					{
						$tabla = "	<center>
									<div style='width: 80%; margin-top: 50px;'>
									<b>Uno o más códigos postales parecen no ser válidos. Recuerda que estos deben tener 5 números solamente.</b>
									<br><br>
									<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
									</div>
								</center>";
						unlink($inputFileName);
						echo $encabezados.$tabla;
					}
					else if($hayClientes == false)
					{
						$tabla = "	<center>
									<div style='width: 80%; margin-top: 50px;'>
									<b>No parece haber algún cliente en el archivo qué importar.</b>
									<br><br>
									<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
									</div>
								</center>";
						unlink($inputFileName);
						echo $encabezados.$tabla;
					}
					else 
					{
						$tabla .= "</table>
						</div>";
						$tabla.= "
						<br><input type='button' value='Importar clientes' id='btn_importar' onclick='registrarClientes(\"".$output_dir. $_FILES["myfile"]["name"]."\");'></center>";
						echo $encabezados.$tabla;
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
					$result = $connection->query('INSERT INTO comun_cliente (nombre, direccion, colonia, cp, idEstado, idMunicipio, email, celular, limite_credito, dias_credito) values ("'.$rowData[0][0].'", "'.$rowData[0][1].'", "'.$rowData[0][2].'", "'.$rowData[0][3].'", "'.$rowData[0][4].'", "'.$rowData[0][5].'", "'.$rowData[0][6].'", "'.$rowData[0][7].'", "'.$rowData[0][8].'", "'.$rowData[0][9].'");');                            
				    //  Insert row data array into your database of choice here
				}
			}
		unlink($inputFileName);
		echo 1;
	}
?>



























