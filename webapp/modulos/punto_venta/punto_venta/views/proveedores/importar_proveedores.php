<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../../libraries/jquery-1.9.1.js"></script>
<script src="../../../libraries/jquery-ui.js"></script>
<link href="../../../libraries/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../../punto_venta/js/jquery.alphanumeric.js"></script>
<script type="text/javascript" src="../../../punto_venta/js/importar_proveedores.js"></script>

<!-- ///////////////////////////// -->	 
	 <div height="20">
	    <div class="descripcion">&nbsp;Importar proveedores (Excel)</div>
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
						<div align="left" style="width: 50; display: table-cell;">
							<img src='../../img/xls_icon.gif'> <a href='plantilla.xlsx'>Descarga la plantilla para los proveedores</a>
						</div>
						<div align="left" style="width: 50; display: table-cell;">
							<img src='../../img/xls_icon.gif'> <a href='estados_municipios.xlsx'>Descarga catálogo de Estados y municipios</a>
						</div>
						
						<br>
						<div style='color: #FF0000;'>(No elimine ninguna columna del formato. Los campos marcados con asterisco son obligatorios)</div>
						<br>
						<?php
							$url = '../../funcionesBD/importar_proveedores.php';
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
				
			</div> 
		</div>
	</center>