<?php
	$idestructura=$_GET['idestructura'];
	$nombreestructura="";
	$descripcion="";
	$titulo="Nueva estructura";
    $linkproceso="";
	$linkprocesoantes="";
	$columnas=0;
    $utilizaidorganizacion=0;

	//CSRF
	session_start();
	$reset_vars = true;
	include "../clases/clcsrf.php";


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
			
	} else {
		include("../../webconfig.php");
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
		
		<script>
			function evaluacaracter(evt){

					//console.error("Entre...");
					var charCode = (evt.which) ? evt.which : event.keyCode
					var inp = String.fromCharCode(event.keyCode);
					
					//console.log("Tecleó: "+charCode+" inp:"+inp);

					if((charCode==9)||(charCode==8)||(charCode==37)||(charCode==39)||(charCode==189)){

						//Backspace, Cursor Keys or underscore.

					} else {

						if((inp=="´")||(inp=="`")){
					  	 return false;	
						}

						if (/[a-zA-Z0-9-_]/.test(inp)){
    						//alert("input was a letter, number, hyphen or underscore");
						}	else {
							return false;
						}

					}


/*
					if(((charCode >= 65)&&(charCode <= 90))
							||((charCode >= 49)&&(charCode <=57))
							||(charCode==8)||(charCode==37)||(charCode==39)){ 
						// Caracteres permitidos...
					} else {
						return false;
					}
*/
					/*
					if((charCode == 32)||(charCode == 190)||(charCode == 188)){
						//e.returnValue = false;
						return false;
						console.error("Carácter bloqueado: "+charCode);
					}	*/	
					//console.error(event.KeyCode); 
			}
		</script>


		<form id="frm" action="estructura_guardar.php" method="post">
			<?php 
				//CSRF - FORM
				echo $csrf->input_token($token_id,$token_value);	 
			?>
			<table class="formulario">
				<tbody>
					<tr class="listadofila">
						<td>Nombre:</td>
						<td><input name="txtnombre" id="txtnombre" 
							onkeypress="javascript:return evaluacaracter(event);"
							onkeydown="javascript:return evaluacaracter(event);"
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
                                    title="Cantidad de columnas en las que se divirá el formulario. esto es, no se trata de la cantidad de campos, es una opción en el sentido del diseño del formulario la forma en que se divirá la captura en columnas."
                                    value="<?php echo $columnas; ?>"></td>
					</tr>
					<?php
						$camposespeciales="hidden";
						if($_SESSION["accelog_login"]=$super_usu){
							$camposespeciales="text";	
						}
					?>	
					
					<!--LINK PROCESO ANTES-->
					<tr class="listadofila">
						<td>Link antes del botón Guardar:</td>
						<td><input name="txtlinkprocesoantes" id="txtlinkprocesoantes" 
									type="<?php echo $camposespeciales; ?>" maxlength="200" size="70"
                                    title="El link se llamara a través de una instrucción include() si es solo el nombre de un archivo php este archivo se buscara desde la carpeta de catalog por lo que en caso de que el archivo se encuentre en otra carpeta añadir la ruta relativa con: '../'  "
                                    value="<?php echo $linkprocesoantes; ?>"></td>
					</tr>
					
					
					<tr class="listadofila">
						<td>Link a proceso:</td>
						<td><input name="txtlinkproceso" id="txtlinkproceso" type="<?php echo $camposespeciales; ?>" maxlength="200" size="70"
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
