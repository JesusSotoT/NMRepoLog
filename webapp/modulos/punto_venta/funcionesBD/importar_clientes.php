<?php
	include("../../../netwarelog/webconfig.php");
	header("Content-Type: text/html; charset=utf-8");
	
	$funcion = $_REQUEST['funcion'];
	$opt = $_REQUEST['opt'];
	
	$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
	$connection->query("CHARACTER SET utf8 COLLATE utf8_general_ci");
	$connection->set_charset("utf8");
		
	$funcion($connection,$opt);
	mysqli_close();
	
	function clasificando($clave, $connection) {
		    $clave = strtoupper($clave);
		    $res = $connection->query("SELECT id FROM app_clasificadores WHERE clave = '$clave'");
		    $res = $res->fetch_assoc();
		    return $res['id'];
	}
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------

	function subirArchivo($connection,$opt)
	{
		$tabla = "";
		$encabezados="";
		?>
					<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
					<LINK href="../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
					<!--	<LINK href="../../../netwarelog/design/default/netwarlog.css"   title="estilo" rel="stylesheet" type="text/css" / -->
					<?php include('../../../netwarelog/design/css.php');?>
	   				<LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

					<script type="text/javascript" src="../../punto_venta/js/importar_clientes.js"></script>
					
					<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
					<script src="../../../libraries/jquery.min.js"></script>

					<!--<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
					<script src="../../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>

					<!--<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
					<link rel="stylesheet" type="text/css" href="../../../modulos/cont/css/jquery-ui.css"/>
		
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
					<h3 class="nmwatitles text-center">Importar clientes (Excel)</h3>
		
		<?php
		$allowed = array('xls','xlsx','xlsm','ods','csv');
		$filename = $_FILES['myfile']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if(!in_array($ext,$allowed) ) 
		{
			?>
		  		 				<div class="row">
									<div class="col-md-12" style="text-align: center;">
										<b>Solo se admiten los archivos con extensión .xls, .xlsx, .xlsm, .ods y .csv</b>
										<br><br>
										<!--<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>-->
										<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary'>
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
									<div class="col-md-12" style="text-align: center;">
										<b>Hubo un error al cargar el archivo. Inténtelo nuevamente.</b>
										<br><br>
										<!--<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>-->
										<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary'>
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
					
					$hayCamposObligatoriosVacios = false;
					$codigoPostalNoValido = false;
					$formatoIncorrecto = false;
					$hayClientes = true;
					$caracterInvalido=false;
					$errores="";
					$soloNumero = "/[^0-9]/";
					$soloNumeroPunto = "/[^0-9.]/";
					
					//$nombreCol= array("Nombre","Direccion","Exterior","Interior","Colonia","CP","Pais","Estado","Municipio","Ciudad","Email", "Celular","Límite/cred.","Días/cred.", "RFC", "Codigo", "id_Clasificacion", "id_listaPrecios", "Razon Social", "Regimen","Datos Facturacion");
					//						0 	      1       2           3          4       5     6       7         8         9        10       11           12           13          14     15           16                   17               18               19           20  
					$nombreCol= array("status","codigo","razon","rfc","Nombre","paginaweb","Direccion","Exterior","Interior","Colonia","CP","Pais","Estado","Municipio","Ciudad","Email", "Celular","telefono.","Días/cred.", "limiteC", "regimen_fiscal", "datos_facturacion", "id_Clasificacion");
					//                    0       1        2      3       4          5          6           7          8       9        10.   11        12.         13       14.     15        16        17           18           19                20                   21            22 
					
					if($highestColumn != "W")
					{
						$formatoIncorrecto = true;
					}
					if($highestRow < 10)
					{
						$hayClientes = false;
					}
					//  Barre sobre cada fila en turno
						for ($row = 10; $row <= $highestRow; $row++)
						{ 
						    //  Mueve a un arreglo el contenido de cada fila
						    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

						    $tabla .= '<td style="border-top: 1px solid #888888;"><input type="checkbox" id="chk_'.$row.'" checked></td>';

						    if(preg_match($soloNumero, $rowData[0][10])){
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [CP: ".$rowData[0][10]."]";
							}
							if(preg_match($soloNumero, $rowData[0][12])){
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [Estado: ".$rowData[0][12]."]";
							}
							if(preg_match($soloNumero, $rowData[0][13])){
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [Municipio: ".$rowData[0][13]."]";
							}
							if(preg_match($soloNumeroPunto, $rowData[0][19])){
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [Límite/cred.: ".$rowData[0][19]."]";
							}
							if(preg_match($soloNumero, $rowData[0][18])){
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [Días/cred.: ".$rowData[0][18]."]";
							}

							$verifica_Casificacion;
							if(preg_match($soloNumero, clasificando(trim($rowData[0][22]), $connection))) {
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [id_clasif: ".$rowData[0][22]."]";
							}
							/*
							if(preg_match($soloNumero, $rowData[0][17])){
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [id_lista: ".$rowData[0][17]."]";
							}
							*/
						
							for($i=0; $i<23; $i++)
							{
								if($i == 10) 
								{
									if(strlen($rowData[0][$i]) > 10)
									{
										$codigoPostalNoValido = true;
									}
								}
								if($i == 12)
								{
									$result = $connection->query("SELECT estado FROM estados WHERE idestado = ".$rowData[0][$i].";");
									$row2 = mysqli_fetch_assoc($result);
									$tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.utf8_encode($row2['estado']).'</td>';
								}
								else if($i == 13)
								{
									$result = $connection->query("SELECT municipio FROM municipios WHERE idmunicipio = ".$rowData[0][$i].";");
									$row2 = mysqli_fetch_assoc($result);
									$tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.utf8_encode($row2['municipio']).'</td>';
								}
								else
								{
									$tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.$rowData[0][$i].'</td>';
								}
								//if($i==0 || $i==7 || $i==8)
								if($i==1 || $i==2 || $i==3 || $i==11 || $i==12)
								{
									if($rowData[0][$i] == "" || $rowData[0][$i] == " ")
									{
										$hayCamposObligatoriosVacios = true;	
									}
								}
								if(strstr($rowData[0][$i],"'")==true){$caracterInvalido=true; $errores.= "<br>[fila ".$row."] [".$nombreCol[$i].": ".$rowData[0][$i]."]";}
								if(strstr($rowData[0][$i],'"')==true){$caracterInvalido=true; $errores.= "<br>[fila ".$row."] [".$nombreCol[$i].": ".$rowData[0][$i]."]";}
							}
							$tabla .= "	</tr>";
						}
						$tabla .= "<input type='hidden' id='contador_filas' value='".$highestRow."'>";
						
					if($formatoIncorrecto == true)
					{
						unset($tabla);
						?>
								<div class="row">
									<div class="col-md-12">
										<b>El archivo no parece tener el formato correcto. ¿Estás seguro de que descargaste la <a href='../views/clientes/plantilla.xls'>plantilla para importación</a>?</b>
										<br><br>
										<input class="btn btn-primary btnMenu" type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
									</div>
								</div>
						<?php
						unlink($inputFileName);
						//echo $encabezados.$tabla;
					}	
					else if($hayCamposObligatoriosVacios == true)
					{
						unset($tabla);
						?>
								<div class="row">
									<div class="col-md-12">
										<b>Hay campos obligatorios vacíos. Recuerde que los campos con asterisco son obligatorios.</b>
										<br><br>
										<input class="btn btn-primary btnMenu" type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
									</div>
								</div>
						<?php
						unlink($inputFileName);
						//echo $encabezados.$tabla;
					}
					else if($codigoPostalNoValido == true)
					{
						unset($tabla);
						?>
								<div class="row">
									<div class="col-md-12">
										<b>Uno o más códigos postales parecen no ser válidos. Recuerda que estos deben tener 5 números solamente.</b>
										<br><br>
										<input class="btn btn-primary btnMenu" type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
									</div>
								</div>
						<?php
						unlink($inputFileName);
						//echo $encabezados.$tabla;
					}
					else if($hayClientes == false)
					{
						unset($tabla);
						?>
								<div class="row">
									<div class="col-md-12">
										<b>No parece haber algún cliente en el archivo qué importar.</b>
										<br><br>
										<input class="btn btn-primary btnMenu" type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'>
									</div>
								</div>
						<?php
						unlink($inputFileName);
						//echo $encabezados.$tabla;
					}
					else if($caracterInvalido == true)
					{
						unset($tabla);
						?>
								<div class="row">
									<div class="col-md-12">
										<b>No se han podido agregar sus clientes</b>
										<br>
										<b>Hay caracteres invalidos en:</b>
										<br>
										<?php echo $errores; ?>
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
								<!--<div style='text-align: right; width: 100%;'><input class="btn btn-primary btnMenu" type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'></div>-->
								<?php
									if ($opt == 1) { $titulo = "Seleccione los clientes a Importar."; }
									if ($opt == 2) { $titulo = "Seleccione los clientes a Modificar."; }
									if ($opt == 3) { $titulo = "Seleccione los clientes a Activar/Inactivar."; }
								?>
								<b><div style='color: black; text-align: left;'> <?php echo $titulo; ?> </div></b><br>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 11px; overflow: auto; max-height: 350px; border: 1px solid #98ac31; padding: 10px;'>
										<tr>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31; '> </th>
											<th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31; '>Estatus</th>
											
											<th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31; '>Código</th>
											<th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31; '>Razón Social</th>
											<th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31; '>RFC</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Nombre</th>
											<th class='nmcatalogbusquedatit' width=5%  style='border-bottom: 1px solid #98ac31;  '>Pagina Web</th>
											<th class='nmcatalogbusquedatit' width=5%  style='border-bottom: 1px solid #98ac31;  '>Dirección</th>
											<th class='nmcatalogbusquedatit' width=5%  style='border-bottom: 1px solid #98ac31; '>Exterior</th>
											<th class='nmcatalogbusquedatit' width=5%  style='border-bottom: 1px solid #98ac31; '>Interior</th>
											<th class='nmcatalogbusquedatit' width=5%  style='border-bottom: 1px solid #98ac31;  '>Colonia</th>
											
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>CP</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>País</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Estado</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Municipio</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Ciudad</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Correo</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Celular</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Telefono</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;  '>Días crédito</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Límite crédito</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;  '>Régimen Fiscal</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;  '>Datos Facturacion</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;  '>Id Clasificación</th>
											
										</tr>
									
										<?php
										echo $tabla;
										?>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
						<!--
							<div class="col-md-4 col-sm-4 col-md-offset-8 col-sm-offset-8">
								<input type='button' value='Importar clientes' id='btn_importar' onclick='registrarClientes("<?php echo $output_dir. $_FILES["myfile"]["name"]; ?>");' class='btn btn-success btnMenu'>
							</div>
						-->
							<div style="text-align: center;">
							<br><br>
								<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary'>
								<input type='button' value='Importar clientes' id='btn_importar' onclick='registrarClientes("<?php echo $output_dir. $_FILES["myfile"]["name"]; ?>","<?php echo $opt; ?>");' class='btn btn-success'>
								<br><br>
								<label style="color:white;">.</label>
							</div>
						</div>
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
		$opt = $_POST['opt'];
		
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
			for ($row = 10; $row <= $highestRow; $row++)
			{
				if(in_array($row, $check)) 
				{
					//  Read a row of data into an array
				    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                        NULL,
                        TRUE,
                        FALSE);

				    $status = $rowData[0][0];
				    $codigo = $rowData[0][1];
				    $razon_social = $rowData[0][2];
				    $rfc = $rowData[0][3];
				    
				    if ($rowData[0][4] == "") {
				    	$nombre = $rowData[0][2];
				    } else {
				    	$nombre = $rowData[0][4];
				    }

				    $paginaweb = $rowData[0][5];
				    $direccion = $rowData[0][6];
				    $domicilio = $rowData[0][6];
				    $num_ext = $rowData[0][7];
				    $num_int = $rowData[0][8];
				    $colonia = $rowData[0][9];
				    $cp = $rowData[0][10];
				    $pais = $rowData[0][11];
				    $id_estado = $rowData[0][12];
				    $id_municipio = $rowData[0][13];
				    $ciudad = $rowData[0][14];
				    $email = $rowData[0][15];
				    $cel = $rowData[0][16];
				    $telefono = $rowData[0][17];
				    $dias_credito = $rowData[0][18];
				    $limite_credito = $rowData[0][19];
				    $regimen_fiscal = $rowData[0][20];
				    $datos_facturacion = $rowData[0][21];
				    //$id_clasificacion = $rowData[0][22];
				    $id_clasificacion = clasificando(trim($rowData[0][22]), $connection);

				    // NEW
					    $extint='';
	                    $direccion='';
	                    if($rowData[0][8]!=''){
	                        $extint=$rowData[0][7].' Int. '.$rowData[0][8];
	                    }else{
	                        $extint=$rowData[0][7];
	                    }
	                    
	                    $direccion = $rowData[0][6].' #'.$extint;

	                    $resultMunicipio = $connection->query("SELECT municipio FROM municipios WHERE idmunicipio = '".$rowData[0][13]."';");
                    	$rowMunicipio = mysqli_fetch_assoc($resultMunicipio);
                    	$municipio=$rowMunicipio['municipio']; 
			    	// NEW FIN

				    if($opt == 1){
				    	
				    	$result = $connection->query('INSERT INTO comun_cliente (nombre, nombretienda, direccion, colonia, cp, idEstado, idMunicipio, email, celular, limite_credito, dias_credito, num_ext, num_int, rfc, codigo, id_clasificacion, ciudad, telefono1) values ("'.$nombre.'", "'.$razon_social.'", "'.$domicilio.'", "'.$colonia.'", "'.$cp.'", "'.$id_estado.'", "'.$id_municipio.'", "'.$email.'", "'.$cel.'", "'.$limite_credito.'", "'.$dias_credito.'", "'.$num_ext.'", "'.$num_int.'","'.$rfc.'","'.$codigo.'","'.$id_clasificacion.'","'.$ciudad.'","'.$telefono.'");');
				    	// NEW 
					    $idComCliente=$connection->insert_id;
					    if($rowData[0][21] == '1'){
					    	
					    	$resultFact = $connection->query('INSERT INTO comun_facturacion (nombre,rfc,razon_social,correo,pais,regimen_fiscal,domicilio,num_ext,cp,colonia,estado,ciudad,municipio) 
					    	values ("'.$idComCliente.'","'.$rfc.'","'.$razon_social.'","'.$email.'","'.$pais.'","'.$regimen_fiscal.'","'.$domicilio.'","'.$num_ext.'","'.$cp.'","'.$colonia.'","'.$id_estado.'","'.$ciudad.'","'.$municipio.'");');                    
					    	
					    }
					    $idComCliente='';
				    // NEW FIN
				    }
				    if($opt == 2){
				    	$sql = $connection->query('UPDATE comun_cliente SET 
				    		rfc = "'.$rfc.'",							
							nombre = "'.$nombre.'",
							direccion = "'.$domicilio.'",
							colonia = "'.$colonia.'",
							cp = "'.$cp.'",
							idPais = "1",
							idEstado = "'.$id_estado.'",
							idMunicipio = "'.$id_municipio.'",
							nombretienda = "'.$razon_social.'", 
							ciudad = "'.$ciudad.'",
							email = "'.$email.'",
							celular = "'.$cel.'",																										
							telefono1 = "'.$telefono.'",
							dias_credito = "'.$dias_credito.'",
							num_ext = "'.$num_ext.'",
							num_int = "'.$num_int.'",
							limite_credito = "'.$limite_credito.'",							
							id_clasificacion = "'.$id_clasificacion.'"						
							where codigo = "'.$codigo.'";');

						$sql2 = $connection->query('SELECT id from comun_cliente where codigo = "'.$codigo.'"');
						$rowId = mysqli_fetch_assoc($sql2);						
						$id=$rowId['id']; 
						

						$sql = $connection->query('UPDATE comun_facturacion SET
							rfc = "'.$rfc.'",
							razon_social = "'.$razon_social.'",
							correo = "'.$email.'",
							pais = "'.$pais.'",
							regimen_fiscal = "'.$regimen_fiscal.'",
							domicilio = "'.$domicilio.'",
							num_ext = "'.$num_ext.'",
							cp = "'.$cp.'",
							colonia = "'.$colonia.'",
							estado = "'.$idestado.'",
							ciudad = "'.$ciudad.'",
							municipio = "'.$municipio.'"
							WHERE nombre = "'.$id.'";');
				    }
				    
				    if($opt == 3){

//				    	$borrado = 0;	
					    if ($status == 0) { $borrado = 0; } else { $borrado = 1; }
					    
				    	$sql='UPDATE comun_cliente SET 
						borrado = "'.$borrado.'"
						WHERE codigo = "'.$codigo.'"';
						$connection->query($sql);
				    }
		
	/*
				    // NEW
					    $extint='';
	                    $direccion='';
	                    if($rowData[0][3]!=''){
	                        $extint=$rowData[0][2].' Int. '.$rowData[0][3];
	                    }else{
	                        $extint=$rowData[0][2];
	                    }
	                    
	                    $direccion = $rowData[0][1].' #'.$extint;


	                    $resultMunicipio = $connection->query("SELECT municipio FROM municipios WHERE idmunicipio = '".$rowData[0][8]."';");
                    	$rowMunicipio = mysqli_fetch_assoc($resultMunicipio);
                    	$municipio=$rowMunicipio['municipio']; 
				    // NEW FIN

					$result = $connection->query('INSERT INTO comun_cliente (nombre, direccion, colonia, cp, idEstado, idMunicipio, email, celular, limite_credito, dias_credito,rfc,codigo,id_clasificacion, id_lista_precios) values ("'.$rowData[0][0].'", "'.$direccion.'", "'.$rowData[0][4].'", "'.$rowData[0][5].'", "'.$rowData[0][7].'", "'.$rowData[0][8].'", "'.$rowData[0][10].'", "'.$rowData[0][11].'", "'.$rowData[0][12].'", "'.$rowData[0][13].'","'.$rowData[0][14].'","'.$rowData[0][15].'",'.intval($rowData[0][16]).','.intval($rowData[0][17]).');');                            
				    //  Insert row data array into your database of choice here

				    // NEW 
					    $idComCliente=$connection->insert_id;
					    if($rowData[0][20] == '1'){
					    	$resultFact = $connection->query('INSERT INTO comun_facturacion (nombre,rfc,razon_social,correo,pais,regimen_fiscal,domicilio,num_ext,cp,colonia,estado,ciudad,municipio) values ("'.$idComCliente.'","'.$rowData[0][14].'","'.$rowData[0][18].'","'.$rowData[0][10].'","'.$rowData[0][6].'","'.$rowData[0][19].'","'.$rowData[0][1].'","'.$extint.'","'.$rowData[0][5].'","'.$rowData[0][4].'","'.$rowData[0][7].'","'.$rowData[0][9].'","'.$municipio.'");');                    
					    	
					    }
					    $idComCliente='';
				    // NEW FIN

	*/
				}
			}
		unlink($inputFileName);
		echo 1;
	}
?>