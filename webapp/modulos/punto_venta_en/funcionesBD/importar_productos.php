<?php
//ini_set('display_errors', 1);
	include("../../../netwarelog/webconfig.php");
	header("Content-Type: text/html; charset=utf-8");
	
	$funcion = $_REQUEST['funcion'];
/*	if($funcion != 'subirArchivo')
	{	echo 'funcion='.$funcion; */
		$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
		$connection->query("CHARACTER SET utf8 COLLATE utf8_general_ci");
		$connection->set_charset("utf8");
		
		$funcion($connection);
		mysqli_close($connection);
/*	}
/	else
	{	echo 'funcion='.$funcion;
		$funcion();
	} */
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
					<!-- <LINK href="../../../netwarelog/design/default/netwarlog.css"   title="estilo" rel="stylesheet" type="text/css" / -->
					<?php include('../../../netwarelog/design/css.php');?>
	    			<LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

					<script type="text/javascript" src="../../punto_venta/js/importar_productos.js"></script>
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
					<h3 class="nmwatitles text-center">Importar productos (Excel)</h3>
					

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
						<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'  class="btn btn-primary btnMenu">
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
				 	$tabla .= "	<div class='row'>
									<div class='col-md-12'>
										<b>Hubo un error al cargar el archivo. Inténtelo nuevamente.</b>
										<br><br>
										<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();'  class='btn btn-primary btnMenu'>
									</div>
								</div>";
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
						?>
								<div class="row">
									<div class="col-md-12">
										<b>Hubo un error al cargar el archivo. Inténtelo nuevamente.</b>
										<br><br>
										<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary'>
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
					$hayProductos = true;
					$caracterInvalido=false;
					$soloLetraNumero = "/[^0-9a-zA-Z]/";
					$soloNumero = "/[^0-9.]/";
					$errores="";
					$nombreCol= array("Nombre","Precio","Clave","Proveedor","Costo","Departamento","Familia","Linea","Color","Talla","Tipo Prodcuto","Vendible","Unidad Compra","Unidad Venta","Desc/corta","Desc/larga","Desc/cenefa","Stock inicial","Stock min","Stock max","almacen","Sucursal","IVA");
					$provedorValido = true;
					$uniComraValido = true;
					$uniVentaValido = true;
					$lineaValida = true;
					$AlmacenValido = true;

					if($highestColumn != "W")
					{
						$formatoIncorrecto = true;
					}
					if($highestRow < 2)
					{
						$hayProductos = false;
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
				
							if(preg_match($soloLetraNumero, $rowData[0][2])){
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [Clave: ".$rowData[0][2]."]" ;
							}
							if(preg_match($soloNumero, $rowData[0][1])){
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [Precio: ".$rowData[0][1]."]";
							}
							if(preg_match($soloNumero, $rowData[0][17])){
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [Stock inicial: ".$rowData[0][17]."]";
							}
							if(preg_match($soloNumero, $rowData[0][18])){
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [Stock mínimo: ".$rowData[0][18]."]";
							}
							if(preg_match($soloNumero, $rowData[0][19])){
								$caracterInvalido=true;
								$errores.= "<br>[Fila: ".$row."] [Stock máximo: ".$rowData[0][19]."]";
							}

							if(substr_count($rowData[0][1],".")>1){
								$caracterInvalido=true;
								$errores.= "<br>Punto repetido - <br>[Fila: ".$row."] [Precio: ".$rowData[0][1]."]";
							}
							if(substr_count($rowData[0][17],".")>1){
								$caracterInvalido=true;
								$errores.= "<br>Punto repetido - [Fila: ".$row."] [Stock inicial: ".$rowData[0][17]."]";
							}
							if(substr_count($rowData[0][18],".")>1){
								$caracterInvalido=true;
								$errores.= "<br>Punto repetido - [Fila: ".$row."] [Stock mínimo: ".$rowData[0][18]."]";
							}
							if(substr_count($rowData[0][19],".")>1){
								$caracterInvalido=true;
								$errores.= "<br>Punto repetido - [Fila: ".$row."] [Stock máximo: ".$rowData[0][19]."]";
							}
	
							$provedor=trim($rowData[0][3]);
							if($rowData[0][12]==""){ $rowData[0][12]="Unidad"; }
							if($rowData[0][13]==""){ $rowData[0][13]="Unidad"; } 
							if($rowData[0][7]==""){ $rowData[0][7]="Linea"; } 
							$uniCompra=trim($rowData[0][12]);
							$uniVenta=trim($rowData[0][13]);
							$linea=trim($rowData[0][7]);
							$almacenT= trim ($rowData[0][20]);
							///Valida proveedor que este en la base de datos
							$q = $connection->query("SELECT idPrv FROM mrp_proveedor WHERE razon_social like '%".$provedor."%';");
							while($r =$q->fetch_object())
							{
								$idProve = $r->idPrv;
							}
							if($idProve==""){
								$provedorValido = false;
								$errores.= "<br>Proveedor invalido - [Fila: ".$row."] [Proveedor: ".$rowData[0][3]."]";
							}
							$idProve='';
							///valida unidad de COMPRA	
							$w = $connection->query("SELECT idUni,compuesto FROM mrp_unidades WHERE compuesto='".$uniCompra."'");
							while($wr =$w->fetch_object())
							{
								$iduniCom = $wr->idUni;
							}
							if($iduniCom==""){
								$uniComraValido = false;
								$errores.= "<br>Unidad compra invalida - [Fila: ".$row."] [Unidad: ".$rowData[0][12]."]";
							}
							$iduniCom='';
							///////////


							///	valida unidad de VENTA					
							$w2 = $connection->query("SELECT idUni,compuesto FROM mrp_unidades WHERE compuesto='".$uniVenta."'");
							while($wr2 =$w2->fetch_object())
							{
								$iduniVenta = $wr2->idUni;
							}
					
							if($iduniVenta==""){
								$uniVentaValido = false;
								$errores.= "<br>Unidad venta invalida - [Fila: ".$row."] [Unidad: ".$rowData[0][13]."]";
							}
							$iduniVenta='';
							
							//////////LINEA
						/*	$w3 = $connection->query("SELECT idLin,nombre FROM mrp_linea WHERE nombre='".$linea."'");
							while($wr3 =$w3->fetch_object())
							{
								$idLinea = $wr3->idLin;
							}
					
							if($idLinea==""){
								$lineaValida = false;
								$errores.= "<br>Linea  invalida - [Fila: ".$row."] [Linea: ".$rowData[0][7]."]";
							}
							$idLinea=''; */
							/// valida el almacen
							$wkr = $connection->query("SELECT idAlmacen, nombre from almacen where nombre ='".$almacenT."'");
							while($wrk =$wkr->fetch_object())
							{
								$idAlmacenT = $wrk->idAlmacen;
							}
							if($idAlmacenT==""){
								$AlmacenValido = false;
								$errores.= "<br>El almacen es invalido  - [Fila: ".$row."] [Unidad: ".$rowData[0][20]."]";
							}
							$idAlmacenT='';




							for($i=0; $i<23; $i++)
							{
								$tabla .= '<td style="border-top: 1px solid #888888; padding: 2px;">'.$rowData[0][$i].'</td>';
								if($i==0 || $i==1 || $i==2)
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
										<b>El archivo no parece tener el formato correcto. ¿Estás seguro de que descargaste la <a href='../views/productos/plantilla.xlsx'>plantilla para importación</a>?</b>
										<br><br>
										<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>
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
										<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>
									</div>
								</div>
					<?php
						unlink($inputFileName);
						//echo $encabezados.$tabla;
					}
					else if($hayProductos == false)
					{
						unset($tabla);
					?>
								<div class="row">
									<div class="col-md-12">
										<b>No parece haber algún producto en el archivo qué importar.</b>
										<br><br>
										<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>
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
										<b>No se han podido agregar sus productos</b>
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
					else if($provedorValido == false)
					{
						unset($tabla);
					?>
								<div class="row">
									<div class="col-md-12">
										<b>No se han podido agregar sus productos</b>
										<br>
										<b>Proveedor no se encuentra registrado en la base de datos<b>
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
					else if($uniComraValido == false)
					{
						unset($tabla);
					?>
								<div class="row">
									<div class="col-md-12">
										<b>No se han podido agregar sus productos</b>
										<br>
										<b>Unidad no se encuentra registrado en la base de datos<b>
										<br>
										<?php echo $errores; ?>
										<br><br>
										<input type='button' value='Volver a la interface de carga' id='btn_volver' onclick='goBack();' class='btn btn-primary btnMenu'>
									</div>
								</div>
					<?
						unlink($inputFileName);
						//echo $encabezados.$tabla;
					}  
					else if($uniVentaValido == false)
					{
						unset($tabla);
					?>
								<div class="row">
									<div class="col-md-12">
										<b>No se han podido agregar sus productos</b>
										<br>
										<b>Unidad no se encuentra registrado en la base de datos<b>
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
					else if($AlmacenValido == false)
					{
						unset($tabla);
					?>
								<div class="row">
									<div class="col-md-12">
										<b>No se han podido agregar sus productos</b>
										<br>
										<b>El almacen no se encuentra registrado en la base de datos<b>
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
								<br><b><div style='color: black; text-align: left;'>Seleccione los productos a importar.</div></b><br>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px; overflow: auto; max-height: 350px; border: 1px solid #98ac31; padding: 10px;'>
										<tr>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31; '> </th>
											<th class='nmcatalogbusquedatit' width=25% style='border-bottom: 1px solid #98ac31; '>Nombre</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Precio de venta</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Clave / Código de barras</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Proveedor</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Costo</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Departamento</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Familia</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Linea</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Color</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Talla</th>

											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Tipo producto</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Vendible</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Unidad de compra</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Unidad de venta</th>

											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Descripcion corta</th>
											<th class='nmcatalogbusquedatit' width=15% style='border-bottom: 1px solid #98ac31; '>Descripcion larga</th>
											<th class='nmcatalogbusquedatit' width=10% style='border-bottom: 1px solid #98ac31; '>Descripcion de cenefa</th>
											
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Stock inicial</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Stock minimo</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Stock máximo</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Almacen</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Sucursal</th>
											<th class='nmcatalogbusquedatit' width=5% style='border-bottom: 1px solid #98ac31;  '>Impuesto</th>
										
										</tr>
									
										<?php
										echo $tabla;
										?>
									</table>
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-4 col-md-offset-8">
								<input type='button' value='Importar productos' id='btn_importar' onclick='registrarProductos("<?php echo $output_dir. $_FILES["myfile"]["name"]; ?>");' class='btn btn-success btnMenu'>
							</div>
						</div>
					<?php
					}
				}
			}
		}
	}

	function registraProductos($connection)
	{
		$inputFileName = $_POST['ruta'];
		$fechaactual=date("Y-m-d H:i:s"); 
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
				    if($rowData[0][18]==""){$rowData[0][18]="1";}
				    if($rowData[0][19]==""){$rowData[0][19]="1";}
				    if($rowData[0][10]==""){$rowData[0][10]="PRODUCTO";}

				    $tipoP=strtoupper(trim($rowData[0][10]));
				    $ven=strtoupper(trim($rowData[0][11]));
				    $provedor = trim($rowData[0][3]);

		
				    if($rowData[0][12]==""){ 
				    	$rowData[0][12]="Unidad"; 
				    }
					if($rowData[0][13]==""){ 
						$rowData[0][13]="Unidad";
					}
					if($rowData[0][7]==""){ 
						$rowData[0][7]="Linea"; 
					}  

					$uniCompra=trim($rowData[0][12]);
					$uniVenta=trim($rowData[0][13]);
					$linea=trim($rowData[0][7]);
					$color = trim($rowData[0][8]);
				    $talla = trim($rowData[0][9]);
				    $nombreAlmacen = trim($rowData[0][20]);

				    switch ($tipoP) {
				    	case 'PRODUCTO':
				    			$tipo_produc=1;
				    		break;
				    	case 'PRODUCIR PRODUCTO':
				    			$tipo_produc=2;
				    		break;	
				    	case 'MATERIAL DE PRODUCCION':
				    			$tipo_produc=3;
				    		break;
				    	case 'KIT DE PRODUCTOS':
				    			$tipo_produc=4;
				    		break;	
				    	case 'PRODUCTO DE CONSUMO':
				    			$tipo_produc=5;
				    		break;
				    	case 'SERVICIO':
				    			$tipo_produc=6;
				    		break;				    						    						    					    	
				    }

				    switch ($ven) {
				    	case 'SI':
				    		$vendible=1;
				    		break;
				    	case 'NO':
				    		$vendible=0;
				    		break;
				    }
			///////////PROVEEDOR
						$q = $connection->query("SELECT idPrv FROM mrp_proveedor WHERE razon_social='".$provedor."'");
						while($r =$q->fetch_object())
						{
							$idProve = $r->idPrv;
						}
			//////////////UNIDAD DE COMPRA			
						$w = $connection->query("SELECT idUni,compuesto FROM mrp_unidades WHERE compuesto='".$uniCompra."'");
						while($wr =$w->fetch_object()){
								$iduniCom = $wr->idUni;
							}
			/////////////UNIDAD DE VENTA
						$w2 = $connection->query("SELECT idUni,compuesto FROM mrp_unidades WHERE compuesto='".$uniVenta."'");
						while($wr2 =$w2->fetch_object())	{
								$iduniVenta = $wr2->idUni;
							}
			
			///////////DEPARTAMENTO	
					$dep=trim($rowData[0][5]);
					$fam=trim($rowData[0][6]);
						$w3 = $connection->query("SELECT idDep,nombre FROM mrp_departamento WHERE nombre='".$dep."'");
						while($wr3 =$w3->fetch_object()){
								$idDepa = $wr3->idDep;
							}
							if($idDepa==''){
								$insertDepa = $connection->query("INSERT into mrp_departamento (nombre) values ('".$dep."')");
								$idDepa=$connection->insert_id;
							}

						

					///////////FAMILIA		
						$w4 = $connection->query("SELECT idFam,nombre FROM mrp_familia WHERE nombre='".$fam."' and idDep=".$idDepa);
						while($wr4 =$w4->fetch_object()){
								$idFami = $wr4->idFam;
							}
							if($idFami==''){
								$insertFamilia = $connection->query("INSERT into mrp_familia (nombre,idDep) values ('".$fam."','".$idDepa."')");
								$idFami=$connection->insert_id;
							}	
					
					
						///////////LINEA				
									$w5 = $connection->query("SELECT idLin,nombre FROM mrp_linea WHERE nombre='".$linea."' and idFam=".$idFami);
									while($wr5 =$w5->fetch_object()){
											$idLinea = $wr5->idLin;
										} 
										if($idLinea==''){ 
											$insertLinea = $connection->query("INSERT into mrp_linea (nombre,idFam) values ('".$linea."','".$idFami."')");
											$idLinea=$connection->insert_id;	
										}
					
					if($color!=''){						
						////////Color					
						$w6 = $connection->query("SELECT idCol,color FROM mrp_color WHERE color='".$color."'");
						while($wr6 =$w6->fetch_object()){
								$idColor = $wr6->idCol;
							}
							if($idColor==''){
								$insertColor= $connection->query("INSERT into mrp_color (color) values ('".$color."')");
								$idColor=$connection->insert_id;
							}		
					}else{
						$idColor='NULL';
					}
					
					if($talla!=''){		
						////////Talla			
						$w7 = $connection->query("SELECT idTal,talla FROM mrp_talla WHERE talla='".$talla."'");
						while($wr7 =$w7->fetch_object()){
								$idTalla = $wr7->idTal;
							}
							if($idTalla==''){
								$insertTalla = $connection->query("INSERT into mrp_talla (talla) values ('".$talla."')");
								$idTalla=$connection->insert_id;
							}			
					}else{
						$idTalla='NULL';
					}		


						if($linea==''){
							$idLinea=1;
						}				


						//echo '['.$idLinea.']';

					$result = $connection->query('INSERT INTO mrp_producto (nombre, codigo, deslarga, descorta, descenefa, color, talla, vendible, consumo, idLinea, maximo, minimo, imagen, barcode, esreceta, precioventa, preciomayoreo, precioliquidacion, stock_inicial, idProveedor, costo, eskit, idunidad,tipo_producto, idunidadCompra) values ("'.$rowData[0][0].'","'.$rowData[0][2].'", "'.$rowData[0][15].'", "'.$rowData[0][14].'", "'.$rowData[0][16].'", '.$idColor.', '.$idTalla.', "'.$vendible.'", "0", "'.$idLinea.'", "'.$rowData[0][19].'", "'.$rowData[0][18].'", "images/noimage.jpeg","001002003001", "0","'.$rowData[0][1].'","0","0", "'.$rowData[0][17].'", "'.$idProve.'", "'.$rowData[0][4].'", 0, "'.$iduniVenta.'", "'.$tipo_produc.'","'.$iduniCom.'")');
				    //echo 'INSERT INTO mrp_producto (nombre, codigo, deslarga, descorta, descenefa, color, talla, vendible, consumo, idLinea, maximo, minimo, imagen, barcode, esreceta, precioventa, preciomayoreo, precioliquidacion, stock_inicial, idProveedor, costo, eskit, idunidad,tipo_producto, idunidadCompra) values ("'.$rowData[0][0].'","'.$rowData[0][2].'", "'.$rowData[0][15].'", "'.$rowData[0][14].'", "'.$rowData[0][16].'", '.$idColor.', '.$idTalla.', "'.$vendible.'", "0", "'.$idLinea.'", "'.$rowData[0][18].'", "'.$rowData[0][19].'", "images/noimage.jpeg","001002003001", "0","'.$rowData[0][1].'","0","0", "'.$rowData[0][17].'", "'.$idProve.'", "'.$rowData[0][4].'", 0, "'.$iduniVenta.'", "'.$tipo_produc.'","'.$iduniCom.'")';
				    //  Insert row data array into your database of choice here

					$idProducto=$connection->insert_id;

					$resultProve = $connection->query('INSERT into mrp_producto_proveedor (idProducto,idPrv,costo,idUni) values("'.$idProducto.'","'.$idProve.'","'.$rowData[0][4].'","'.$iduniVenta.'")');

					if($rowData[0][22]=='SI' || $rowData[0][22]=='si' || $rowData[0][22]=='Si'){
						if($idProducto!=''){
							$inserta = $connection->query('INSERT into producto_impuesto (idProducto,idImpuesto,valor) values("'.$idProducto.'","1","16.00")');
						}
					}

				
					session_start();
					//echo 'X'.$rowData[0][6].'X';
					//if($rowData[0][6]>0)
					//{
						$q = $connection->query("SELECT au.idSuc,mp.nombre FROM administracion_usuarios au,mrp_sucursal mp WHERE mp.idSuc=au.idSuc AND au.idempleado=".$_SESSION['accelog_idempleado']);
							
						if($q->num_rows>0)
						{
							while($r =$q->fetch_object())
							{
								$sucursal_operando = $r->nombre;
								$sucursal_id = $r->idSuc;
							}	
						}	
						
						$q = $connection->query("SELECT idAlmacen FROM mrp_sucursal WHERE idSuc=".$sucursal_id." limit 1");
						
						while($r =$q->fetch_object())
						{
							$almacen = $r->idAlmacen;
						} 
			//$almacen = 1;
						if($nombreAlmacen!=''){
							$wxy = $connection->query("SELECT idAlmacen FROM almacen WHERE nombre='".$nombreAlmacen."'");
							while($wx7 =$wxy->fetch_object()){
									$almacen = $wx7->idAlmacen;
								}
						} 
						//$almacen = 1;


						$e = $connection->query("SELECT cantidad FROM mrp_stock WHERE idProducto=".$idProducto." AND idAlmacen=".$almacen." limit 1");
						//echo "SELECT cantidad FROM mrp_stock WHERE idProducto=".$idProducto." AND idAlmacen=".$almacen." limit 1";
						if($e->num_rows>0 || $e->num_rows!='')
						{
							while($re =$e->fetch_object())
							{
								$vcantidad = $re->cantidad;
							}
							$connection->query("UPDATE mrp_stock SET cantidad=".($vcantidad+$rowData[0][17])." WHERE idProducto=".$idProducto." AND idAlmacen=".$almacen);
						//	echo "UPDATE mrp_stock SET cantidad=".($vcantidad+$rowData[0][6])." WHERE idProducto=".$idProducto." AND idAlmacen=".$almacen;
						///	var_dump("UPDATE mrp_stock SET cantidad=".($vcantidad+$rowData[0][6])." WHERE idProducto=".$idProducto." AND idAlmacen=".$almacen);
						}
						else
						{
							$connection->query("INSERT INTO mrp_stock VALUES('',".$idProducto.",".$rowData[0][17].",".$almacen.",".$iduniVenta.",0)");
						//	echo "INSERT INTO mrp_stock VALUES('',".$idProducto.",".$rowData[0][6].",".$almacen.",1,0)";
						//	var_dump("INSERT INTO mrp_stock VALUES('',".$idProducto.",".$rowData[0][6].",".$almacen.",1)");	
						}
						$fechaactual = date("Y-m-d H:i:s"); 	
						if(is_numeric($idProve))
						{	
							$query0 = $connection->query("INSERT INTO ingreso_mercancia VALUES('','".$fechaactual."','".$idProducto."','".$idProve."',".$rowData[0][17].",'".$sucursal_id."','".$rowData[0][4]."');");
						}
						else
						{
							$query0 = $connection->query("INSERT INTO ingreso_mercancia VALUES('','".$fechaactual."','".$idProducto."',NULL,".$rowData[0][17].",'".$sucursal_id."','".$rowData[0][4]."');");
						}	
							$idProve='';
							$iduniCom='';
							$iduniVenta='';
							$idLinea='';
							$idDepa='';
							$idFami='';
							$idLinea='';
							$idProducto='';
							$idTalla = '';
							$idColor = '';

					//}
				}
			}//for
		unlink($inputFileName);
		echo 1;
	}
?>