<?php
	$idestructura=$_GET['idestructura'];
	$nombreestructura="";
	$descripcion="";
	$titulo="Nueva estructura";
    $linkproceso="";
	$linkprocesoantes="";
	$columnas=0;
    $utilizaidorganizacion=0;

	if($idestructura!=-1){
		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../conexionbd.php");
		
		$sql = "select nombreestructura, descripcion, utilizaidorganizacion, linkproceso, linkprocesoantes, columnas
			from catalog_estructuras
			where idestructura=".$idestructura;
                
		$result = $conexion->consultar($sql);
		if($reg = $conexion->siguiente($result)){
			$nombreestructura = $reg{'nombreestructura'};
			$descripcion = $reg{'descripcion'};
            $utilizaidorganizacion=$reg{'utilizaidorganizacion'};
            $linkproceso=$reg{'linkproceso'};
			$linkprocesoantes=$reg{'linkprocesoantes'};
			$columnas = $reg{'columnas'};
		}
		$conexion->cerrar_consulta($result);
		
		$titulo = "Editar estructura";
		
		$conexion->cerrar();
			
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
		<LINK href="estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $titulo?></title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->
	</head>

	<body>		
		<div class="titulo"><?php echo $titulo?></div>
		<br>
		<a title="Guardar datos" class="nuevo" href="javascript:guardar();"><img class="btn" src="../img/guardar.png"></a>
		<a title="Regresar ..." class="regresar" href="javascript:regresar();"><img class="btn" alt="nuevo" src="../img/regresar.png"></a>		
		<form id="frm" action="estructura_guardar.php" method="post">
			<table class="formulario">
				<tbody>
					<tr class="listadofila">
						<td>Nombre:</td>
						<td><input name="txtnombre" id="txtnombre" 
							onKeypress="if (event.keyCode == 32 ) event.returnValue = false;"
							type="text" maxlength="50" size="50" value="<?php echo $nombreestructura?>"></td>
					</tr>
					<tr class="listadofila">
						<td>Descripción:</td>
						<td><input name="txtdesc" id="txtdesc" type="text" maxlength="80" size="70" value="<?php echo $descripcion?>"></td>
					</tr>
					<tr class="listadofila">
						<td>Utiliza el campo Id. Organización:</td>
                                                <?php
                                                    if($utilizaidorganizacion){
                                                        $sel = "checked";
                                                    }else{
                                                        $sel = "";
                                                    }
                                                ?>
						<td><input name="chkorg" id="chkorg" type="checkbox"  value="1" <?php echo $sel; ?>  ></td>
					</tr>


					<!--COLUMNAS-->
					<tr class="listadofila">
						<td>Columnas del formulario:</td>
						<td><input name="txtcolumnas" id="txtcolumnas" 
									type="text" maxlength="200" size="70"
                                    title="El link se llamara a través de una instrucción include() si es solo el nombre de un archivo php este archivo se buscara desde la carpeta de catalog por lo que en caso de que el archivo se encuentre en otra carpeta añadir la ruta relativa con: '../'  "
                                    value="<?php echo $columnas; ?>"></td>
					</tr>
					
					
					<!--LINK PROCESO ANTES-->
					<tr class="listadofila">
						<td>Link antes del botón Guardar:</td>
						<td><input name="txtlinkprocesoantes" id="txtlinkprocesoantes" 
									type="text" maxlength="200" size="70"
                                    title="El link se llamara a través de una instrucción include() si es solo el nombre de un archivo php este archivo se buscara desde la carpeta de catalog por lo que en caso de que el archivo se encuentre en otra carpeta añadir la ruta relativa con: '../'  "
                                    value="<?php echo $linkprocesoantes; ?>"></td>
					</tr>
					
					
					<tr class="listadofila">
						<td>Link a proceso:</td>
						<td><input name="txtlinkproceso" id="txtlinkproceso" type="text" maxlength="200" size="70"
                                                           title="El link se llamara a través de una instrucción include() si es solo el nombre de un archivo php este archivo se buscara desde la carpeta de catalog por lo que en caso de que el archivo se encuentre en otra carpeta añadir la ruta relativa con: '../'  "
                                                           value="<?php echo $linkproceso; ?>"></td>
					</tr>
					
					
					
					
					
				</tbody>
				<input name="txtidestructura" type="hidden" value="<?php echo $idestructura?>">
			</table>
			<script>
				function guardar(){
					var txtnombre = document.getElementById("txtnombre");
					var txtdesc = document.getElementById("txtdesc");
					if(txtnombre.value=='') {
						alert('Capture el nombre de la estructura.');						
					} else {
						if(txtdesc.value==''){
							alert('Capture la descripción.');							
						} else {
							var frm = document.getElementById("frm");
							frm.submit();
						}
					}
				}			
				function regresar(){
					document.location = "index.php";
				}
			</script>
		</form>
	</body>
</html>