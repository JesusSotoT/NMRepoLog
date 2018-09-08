
<?php
include("../../../netwarelog/webconfig.php");
//ini_set('display_errors', 1);
header("Content-Type: text/html; charset=utf-8");

$funcion = $_REQUEST['funcion'];
$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$connection->query("CHARACTER SET utf8 COLLATE utf8_general_ci");
$connection->set_charset("utf8");

$funcion($connection);
mysqli_close($connection);


//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------

function subirArchivo($connection)
{
	$tabla = "";
	$encabezados = "";
	?>
	<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<LINK href="../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
	<!--<LINK href="../../../netwarelog/design/default/netwarlog.css"   title="estilo" rel="stylesheet" type="text/css" / -->
		<?php include('../../../netwarelog/design/css.php');?>
	    <LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
	<script type="text/javascript" src="../../punto_venta/js/importar_proveedores.js"></script>

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
					<h3 class="nmwatitles text-center">Importar proveedores (Excel)</h3>
	
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
							<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>
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
				$formatoIncorrecto = false;
				$hayProveedores = true;
				$errores="";
				$soloNumero = "/[^0-9]/";
				$soloNumeroPunto = "/[^0-9.]/";
				$checkRFC = "/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?/";
				$nombreCol= array("RFC","Razón Social","Domicilio","Estado","Municipio","Teléfono","Email","Sitio web");


				if($highestColumn != "R")
				{
					$formatoIncorrecto = true;
				}
				if($highestRow < 2)
				{
					$hayProveedores = false;
				}
					//  Barre sobre cada fila en turno
				for ($row = 2; $row <= $highestRow; $row++)
				{ 
						    //  Mueve a un arreglo el contenido de cada fila
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
					$tabla .= '<td style="border-top: 1px solid #888888;"><input type="checkbox" id="chk_'.$row.'" checked></td>';

					if(preg_match($soloNumero, $rowData[0][9])){
						$caracterInvalido=true;
						$errores.= "<br>[Fila: ".$row."] [Estado: ".$rowData[0][9]."]";
					}
					if(preg_match($soloNumero, $rowData[0][10])){
						$caracterInvalido=true;
						$errores.= "<br>[Fila: ".$row."] [Municipio: ".$rowData[0][10]."]";
					}
					if(!preg_match($checkRFC, $rowData[0][1])){
						if($rowData[0][1]!=""){
							$caracterInvalido=true;
							$errores.= "<br>(RFC formato invalido)<br>[Fila: ".$row."] [RFC: ".$rowData[0][1]."]";
						}

					}

					for($i=0; $i<18; $i++)
					{
						if($i == 9)
						{
							$result = $connection->query("SELECT estado FROM estados WHERE idestado = ".$rowData[0][$i].";");
							$row2 = mysqli_fetch_assoc($result);
							$tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.utf8_encode($row2['estado']).'</td>';
						}
						else if($i == 10)
						{
							$result = $connection->query("SELECT municipio FROM municipios WHERE idmunicipio = ".$rowData[0][$i].";");
							$row2 = mysqli_fetch_assoc($result);
							$tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.utf8_encode($row2['municipio']).'</td>';
						}
						else
						{
							$tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.$rowData[0][$i].'</td>';
						}
						if($i==3 || $i==9 || $i==10)
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
					?>
					<div class="row">
						<div class="col-md-12">
							<b>El archivo no parece tener el formato correcto. ¿Estás seguro de que descargaste la <a href='../views/proveedores/plantilla.xlsx'>plantilla para importación</a>?</b>
							<br><br>
							<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>
						</div>
					</div>
					<?php
					unlink($inputFileName);
					echo $encabezados.$tabla;
				}

				else if($hayCamposObligatoriosVacios == true)
				{
					?>
					<div class="row">
						<div class="col-md-12">
							<b>Hay campos obligatorios vacíos. Recuerde que los campos con asterisco son obligatorios.</b>
							<br><br>
							<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>
						</div>
					</div>
					<?php
					unlink($inputFileName);
					echo $encabezados.$tabla;
				}	

				else if($hayProveedores == false)
				{
					?>
					<div class="row">
						<div class="col-md-12">
							<b>No parece haber algún proveedor en el archivo qué importar.</b>
							<br><br>
							<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>
						</div>
					</div>
					<?php
					unlink($inputFileName);
					echo $encabezados.$tabla;
				}
				else if($caracterInvalido == true)
				{
					unset($tabla);
					?>
					<div class="row">
						<div class="col-md-12">
							<b>No se han podido agregar sus proveedores</b>
							<br>
							<b>Hay caracteres invalidos en:</b>
							<br>
							<?php echo $errores; ?>
							<br><br>
							<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>
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
							<div style='text-align: right; width: 100%;'><input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'></div>
							<br><b><div style='color: black; text-align: left;'>Seleccione los proveedores a importar.</div></b><br>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
							<div class="table-responsive">
								<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 11px; overflow: auto; max-height: 350px; border: 1px solid #98ac31; padding: 10px;'>
									<tr>
										<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31; '> </th>
										<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31; '>Codigo</th>
										
										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>RFC</th>
										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>Curp</th>

										<th class='nmcatalogbusquedatit' width=20% style='border-bottom: 1px solid #98ac31; '>Razón social</th>
										<th class='nmcatalogbusquedatit' width=20% style='border-bottom: 1px solid #98ac31; '>No. Comercial</th>
										<th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31;'>Domicilio</th>
										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>Exterior</th>
										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>Interior</th>
										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>CP</th>
										<th class='nmcatalogbusquedatit' width=10%  style='border-bottom: 1px solid #98ac31; '>No. de estado</th>
										<th class='nmcatalogbusquedatit' width=10%  style='border-bottom: 1px solid #98ac31; '>No. de municipio</th>

										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>Teléfomo</th>
										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>Email</th>

										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>Sitio web</th>
										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>Dias Credito</th>
										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>Limie Credito</th>
										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>Clasificacion</th>
										<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31;'>Moneda</th>
									</tr>
									<?php
									echo $tabla;
									?>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-sm-4 col-md-offset-8 col-sm-offset-8">
							<input type='button' value='Importar proveedores' id='btn_importar' onclick='registrarProveedores("<?php echo $output_dir. $_FILES["myfile"]["name"]; ?>");' class='btn btn-success btnMenu'>
						</div>
					</div>
					<?php
				}

			}
		}
	}
}

function registraProveedores($connection)
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
			$result = $connection->query('INSERT INTO mrp_proveedor (codigo,razon_social, rfc, domicilio, telefono, email, web,diascredito, idestado, idmunicipio,curp,nombre_comercial,moneda,clasificacion,limite_credito,calle,no_ext,no_int,cp) values ("'.$rowData[0][0].'", "'.$rowData[0][3].'", "'.$rowData[0][1].'", "'.$rowData[0][5].' #'.$rowData[0][6].' '.$rowData[0][7].'", "'.$rowData[0][11].'", "'.$rowData[0][12].'", "'.$rowData[0][13].'", "'.$rowData[0][14].'","'.$rowData[0][9].'","'.$rowData[0][10].'","'.$rowData[0][2].'","'.$rowData[0][4].'","'.$rowData[0][17].'","'.$rowData[0][16].'","'.$rowData[0][15].'","'.$rowData[0][5].'","'.$rowData[0][6].'","'.$rowData[0][7].'","'.$rowData[0][8].'");');  
			//echo 'INSERT INTO mrp_proveedor (codigo,razon_social, rfc, domicilio, telefono, email, web,diascredito, idestado, idmunicipio,curp,nombre_comercial,moneda,clasificacion,limite_credito,calle,no_ext,no_int,cp,) values ("'.$rowData[0][0].'", "'.$rowData[0][3].'", "'.$rowData[0][1].'", "'.$rowData[0][5].' #'.$rowData[0][6].' '.$rowData[0][7].'", "'.$rowData[0][11].'", "'.$rowData[0][12].'", "'.$rowData[0][13].'", "'.$rowData[0][14].'","'.$rowData[0][9].'","'.$rowData[0][10].'","'.$rowData[0][2].'","'.$rowData[0][4].'","'.$rowData[0][17].'","'.$rowData[0][16].'","'.$rowData[0][15].'","'.$rowData[0][5].'","'.$rowData[0][6].'","'.$rowData[0][7].'","'.$rowData[0][8].'");';                          
				    //  Insert row data array into your database of choice here
		}
	}
	unlink($inputFileName);
	echo 1;
}
?>



























