<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../../punto_venta/js/jquery.alphanumeric.js"></script>

<script type="text/javascript" src="../../../punto_venta/js/importar_productos.js"></script>
<!-- ///////////////////////////// -->	 
	 <div height="20">
	    <div class="descripcion">Importar productos (Excel)</div>
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
					<div style="width: 100%">
						<div align="left"><img src='../../img/xls_icon.gif'> <a href='plantilla.xlsx'>Descarga la plantilla para los productos</a>
						<br><div style='color: #FF0000;'>(No elimine ninguna columna del formato. Los campos marcados con asterisco son obligatorios)</div></div>
						<br>
						<?php
							$url = '../../funcionesBD/importar_productos.php';
						?>
						<form id="myForm" action=<?php echo $url; ?> method="post" enctype="multipart/form-data">
						    <input type='hidden' value='subirArchivo' name='funcion'>
						    <input type="file" size="100" name="myfile" style="width: 100%;"><br>
						  	<div align="right"><input type="submit" value="Previsualizar" id="btnarchivo"></div>
						</form>
					</div>
				</div>
				
				</center>
				<!-- ///////////////////////////// -->
				<center>
					
				<div id='upload_div' style='display: table; width: 100%;' title='Subir' >
					<div id='tabla_div'>
					</div>
				</div>
				
				</center>
				<!-- ///////////////////////////// -->
			</div> 
		</div>
	</center>


































<?php
	/*include '../../Classes/PHPExcel/IOFactory.php';
	
	$inputFileName = '../productos/test.xlsx';
	
	//  Read your Excel workbook
	try 
	{
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objPHPExcel = $objReader->load($inputFileName);
	} 
	catch(Exception $e) 
	{
		die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}
	//------------------------------------------------------------------
	
	$worksheet = $objPHPExcel->getActiveSheet();
	foreach ($worksheet->getRowIterator() as $row) 
	{
    	echo '<br><br>Row number: ' . $row->getRowIndex() . "<br>";

    	$cellIterator = $row->getCellIterator();
    	$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    	foreach ($cellIterator as $cell) 
	    {
	        if (!is_null($cell))
	        {
	            echo 'Cell: ' . $cell->getCoordinate() . ' - ' . $cell->getValue() . "<br>";
	        }
	    }
	
    //------------------------------------------------------------------
	//  Get worksheet dimensions
	$sheet = $objPHPExcel->getSheet(0); 
	$highestRow = $sheet->getHighestRow(); 
	$highestColumn = $sheet->getHighestColumn();
	
	//  Loop through each row of the worksheet in turn
		for ($row = 1; $row <= $highestRow; $row++)
		{ 
		    //  Read a row of data into an array
		    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
		                                    NULL,
		                                    TRUE,
		                                    FALSE);
		    //  Insert row data array into your database of choice here
		}
}*/
?>









