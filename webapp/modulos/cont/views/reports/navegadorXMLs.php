<style rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css"></style>
<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script language='javascript'>
$(document).ready(function()
{
	ordenar('.itemList',true)
});

 	function eliminar(archivo)
 	{
 		var confirmacion = confirm("Esta seguro de eliminar este archivo: \n"+archivo);
 		if(confirmacion)
 		{
 			$.post("ajax.php?c=Reports&f=EliminarArchivo",
		 	{
				Archivo: archivo
			 },
			 function(data)
			 {
			 	location.reload();
				//alert('Eliminado')
			 });
 		}
 	}
 	function ordenar(elementos, orden){
	var lista = $(elementos).parent();
	var elemLista = $(elementos).get();
	elemLista.sort(function(a, b) {
	   var compA = omitirAcentos($(a).text().toUpperCase());
	   var compB = omitirAcentos($(b).text().toUpperCase());
	   return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
	})
	if(orden){
		$(elemLista).each( function(ind, elem) { $(lista).append(elem); });
	}else{
		$(elemLista).each( function(ind, elem) { $(lista).prepend(elem); });
	}
}
function omitirAcentos(text) {
    var acentos = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç";
    var original = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc";
    for (var i=0; i<acentos.length; i++) {
        text = text.replace(acentos.charAt(i), original.charAt(i));
    }
    return text;
}
</script>
<style type="text/css">
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
</style>

<?php 
	if($directorio == "balanzas")
	{
		require('xmls/funciones/generarXML.php');
		$function = "balanzaComprobacionXML";
		$logo = "xml.jpg";
	}
	if($directorio == "cuentas")
	{
		require('xmls/funciones/generarXML.php');
		$function = "catalogoXML";
		$logo = "xml.jpg";
	}
	if($directorio == "auxcuentas")
	{
		require('xmls/funciones/generarXML.php');
		$function = "auxCuentasXML";
		$logo = "xml.jpg";
	}
	if($directorio == "a29")
	{
		require('xmls/funciones/generarTXT.php');
		$function = "a29Txt";
		$logo = "txt.png";
	}
	if($directorio == "polizas")
	{   
	    require('xmls/funciones/generarXML.php');
	    $function = "polizasXML";
	    $logo = "xml.jpg";
	}
	if($directorio == "folios")
	{   
	    require('xmls/funciones/generarXML.php');
	    $function = "foliosXML";
	    $logo = "xml.jpg";
	}
?>

<div class="container">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
			<h3 class="nmwatitles text-center">
				<?php 
					if($directorio == "balanzas")
					{
						echo "Lista de XML de Comprobaci&oacute;n";
					}
					if($directorio == "cuentas")
					{
						echo "Lista de XML de Cat&aacute;logo";
					}
					if($directorio == "auxcuentas")
					{
						echo "Lista de XML de Auxiliar de Cuentas y/o Subcuentas";
					}
					if($directorio == "a29")
					{
						echo "Lista de TXT del A29";
					}
					if($directorio == "polizas")
					{   
					    echo "Lista de Polizas del mes XML";
					}
					if($directorio == "folios")
					{   
					    echo "Lista de Folios Fiscales del mes XML";
					}
				?>
			</h3>
			<?php
			if($_GET['sub'])
			{
				echo "<div class='row'>
						<div class='col-md-3'>
							<a href='index.php?c=Reports&f=$function'>Regresar</a>
						</div>
					 </div>";
			}
			else
			{
				$boton = str_replace('.jpg', '', $logo);
				$boton = str_replace('.png', '', $boton);
				echo "<div class='row'>
						<div class='col-md-4'>
							<input type='button' class='btn btn-primary btnMenu' value='Generar $boton'  id='generar' title='ctrl + g' tipo='$directorio'>
						</div>
					 </div>";
			}
			$carpeta = "$directorio/".$_GET['sub'];
			$ruta = $this->path()."xmls/".$carpeta;
			$directorio = opendir($ruta); //ruta actual
			sort($directorio);
			echo "<div class='row'>
					<div class='col-md-12 col-sm-12 col-xs-12'>
						<div class='table-responsive'>
							<table class='table'>";
								while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
								{
									if($archivo != '.' AND $archivo != '..' AND $archivo != '.DS_Store' AND $archivo != '.file' AND $archivo != '.file.rtf')
									{
										$extension = substr($archivo,-4,4);
										if ( $extension != '.xml' && $extension != '.txt')//verificamos si es o no un directorio
								   		{
								        	echo "<tr><td width=70><img src='xmls/imgs/carpeta.jpg'></td><td><b><a href='index.php?c=Reports&f=$function&sub=".$archivo."'>[".$archivo."]</a></b></td><td></td><td></td><td></td><td></td><td></td></tr>"; //de ser un directorio lo envolvemos entre corchetes
								    	}
								    	else
								    	{
								    		if($extension == '.xml')
								    		{
								    			$exten = 'XML';
								    		}
								    		if($extension == '.txt')
								    		{
								    			$exten = 'TXT';
								    		}
								        	echo "<tr style='text-align:center;height:50px;' class='itemList'><td><img src='xmls/imgs/$logo' width=30></td><td><b>".$archivo . "</b></td><td width=100> <a href='$ruta/$archivo' target='_blank'>Ver</a> </td><td width=150>Descarga: <a href='xmls/funciones/descargaXML.php?ruta=".$carpeta."&nombre=".$archivo."' target='_blank'>$exten</a> / <a href='xmls/funciones/descargaXML.php?ruta=".$carpeta."&nombre=".$archivo."&tipo=1' target='_blank'>ZIP</a></td><td><a href='xmls/funciones/descargaExcel.php?ruta=".$carpeta."&nombre=".$archivo."&tipo=".$function."' target='_blank'>Excel</a></td><td>Creado  o modificado el: ".date ("d/m/Y H:i:s",filectime($ruta."/".$archivo))."</td><td><a href='javascript:eliminar(\"".$ruta."/".$archivo."\")'><img src='images/eliminado.png' title='Eliminar'></a></td></tr>";
								    	}
									}
								}
			echo "			</table>
						</div>
					</div>
				</div>";
			?>
		</div>
	</div>
</div>