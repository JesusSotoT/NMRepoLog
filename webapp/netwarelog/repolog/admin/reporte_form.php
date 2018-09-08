<?php
        include("parametros.php");
  
	//CSRF
	$reset_vars = true;
	include "../../catalog/clases/clcsrf.php";

 
	$idreporte=$_GET['id'];
	$nombrereporte="";
	$descripcion="";
	$titulo="Nuevo reporte";
        $idestiloomision = 0;
        $sql_select = "";
        $sql_from = "";
        $sql_where = "";
        $sql_groupby = "";
        $sql_having = "";
        $sql_orderby = "";
        $url_include ="";
        $url_include_despues="";
        $subtotales_agrupaciones="";
        $subtotales_funciones="";
        $subtotales_subtotal="";
        //En caso de que el reporte no sea nuevo entonces se carga su info
	if($idreporte!=-1){
		
                $sql = "select *
			from repolog_reportes
			where idreporte=".$idreporte;
		$result = $conexion->consultar($sql);
		if($reg=$conexion->siguiente($result)){
			$nombrereporte = $reg{'nombrereporte'};
			$descripcion = $reg{'descripcion'};
                        $idestiloomision = $reg{'idestiloomision'};
                        $sql_select = $reg{'sql_select'};
                        $sql_from = $reg{'sql_from'};
                        $sql_where = $reg{'sql_where'};
                        $sql_groupby = $reg{'sql_groupby'};
                        $sql_having = $reg{'sql_having'};
                        $sql_orderby = $reg{'sql_orderby'};
                        $url_include = $reg{'url_include'};
                        $url_include_despues =$reg{'url_include_despues'};
                        $subtotales_agrupaciones =$reg{'subtotales_agrupaciones'};
                        $subtotales_funciones =$reg{'subtotales_funciones'};
                        $subtotales_subtotal =$reg{'subtotales_subtotal'};
		}
		$conexion->cerrar_consulta($result);
		                
		$titulo = "Editar reporte";				
			
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
		<LINK href="<?php echo $link_catalog_local; ?>/admin/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $titulo?></title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->
                <style type="text/css" >
                    textarea{
                        width:98%;
                        height:60px;
                        font-size:12px;
                        font-family: Tahoma, Arial;
                    }
                    textarea.subtotales{
                    	width:100%;
                    	height:60px;
                    }
                    table.subtotales{
                    	border:none;
                    }
                    td.subtotales{
                    	border:none;	
                    }
                </style>
	</head>

	<body>		
		<div class="titulo"><?php echo $titulo?></div>
		<br>
		<a title="Guardar datos" class="nuevo" href="javascript:guardar();"><img class="btn" src="<?php echo $link_catalog_local; ?>/img/guardar.png"></a>
		<a title="Regresar ..." class="regresar" href="javascript:regresar();"><img class="btn" alt="nuevo" src="<?php echo $link_catalog_local; ?>/img/regresar.png"></a>
		<form id="frm" action="reporte_guardar.php" method="post">
			<?php 
				//CSRF - FORM
				echo $csrf->input_token($token_id,$token_value);	 
			?>


                        <input name="txtidreporte" type="hidden" value="<?php echo $idreporte?>">
			<table class="formulario" width="800">
				<tbody>
					<tr class="listadofila">
						<td>Nombre:</td>
						<td><input name="txtnombrereporte" id="txtnombrereporte"
							onKeypress="if (event.keyCode == 32 ) event.returnValue = false;"
							type="text" maxlength="50" size="50" value="<?php echo $nombrereporte?>"></td>
					</tr>
					<tr class="listadofila">
						<td>Descripción:</td>
						<td><input name="txtdesc" id="txtdesc" type="text" maxlength="80" size="70" value="<?php echo $descripcion?>"></td>
					</tr>
					<tr class="listadofila">
						<td>Estilo por omisión:</td>
                                                <td>
                                                    <select id="cmbestilo" name="cmbestilo">
                                                        <?php
                                                            $resultestilo = $conexion->consultar("select * from repolog_estilos");
                                                            $sel = "";
                                                            while($rs=$conexion->siguiente($resultestilo)){                                                                 
                                                                if($idestiloomision==$rs{'idestilo'}) $sel = "selected";
                                                                 echo "<option value='".$rs{'idestilo'}."'  ".$sel."   >".$rs{'nombre'}."</option>";
                                                            }
                                                            $conexion->cerrar_consulta($resultestilo);
                                                        ?>                                                                                                                
                                                    </select>
                                                </td>
					</tr>
					<tr class="listadofila" height="20">
						<th colspan="2"><b>SQL</b></th>
					</tr>
					<tr class="listadofila">
						<td><b>SELECT</b></td>
						<td>
                                                    <textarea name="txtselect" id="txtselect"onKeyUp="return maximaLongitud(this,5000)" ><?php echo $sql_select; ?></textarea>
                                                </td>
					</tr>
					<tr class="listadofila">
						<td><b>FROM</b></td>
						<td>
                                                    <textarea name="txtfrom" id="txtfrom"onKeyUp="return maximaLongitud(this,5000)" ><?php echo $sql_from; ?></textarea>
                                                </td>
					</tr>
					<tr class="listadofila">
						<td><b>WHERE</b></td>
						<td>
                                                    <textarea name="txtwhere" id="txtwhere"onKeyUp="return maximaLongitud(this,2000)"
                                                              title ="Para agregar un filtro la sintaxis es WHERE/HAVING campo = [Etiqueta], si es de tipo fecha entonces utilizar campo = [#Etiqueta] ejemplo: WHERE/HAVING nombre like '%[Escriba el nombre]%' and fecha>='[#Del]' and fecha<='[#Al]', para una dependencia utilizar WHERE/HAVING campo = [@Etiqueta;CampoValor;CampoDescripcion;SQL] ejemplo: HAVING  idcliente = [@Cliente;idcliente;nombrecliente;select idcliente, nombre from clientes]"
                                                              ><?php echo $sql_where; ?></textarea>
                                                </td>
					</tr>
					<tr class="listadofila">
						<td><b>GROUP BY</b></td>
						<td>
                                                    <textarea name="txtgroupby" id="txtgroupby"onKeyUp="return maximaLongitud(this,5000)" ><?php echo $sql_groupby; ?></textarea>
                                                </td>
					</tr>
					<tr class="listadofila">
						<td><b>HAVING</b></td>
						<td>
                                                    <textarea name="txthaving" id="txthaving"onKeyUp="return maximaLongitud(this,5000)"
                                                              title ="Para agregar un filtro la sintaxis es WHERE/HAVING campo = [Etiqueta], si es de tipo fecha entonces utilizar campo = [#Etiqueta] ejemplo: WHERE/HAVING nombre like '%[Escriba el nombre]%' and fecha>='[#Del]' and fecha<='[#Al]', para una dependencia utilizar WHERE/HAVING campo = [@Etiqueta;CampoValor;CampoDescripcion;SQL] ejemplo: HAVING  idcliente = [@Cliente;idcliente;nombrecliente;select idcliente, nombre from clientes]"
                                                              ><?php echo $sql_having; ?></textarea>
                                                </td>
					</tr>
					<tr class="listadofila">
						<td><b>ORDER BY</b></td>
						<td>
                                                    <textarea name="txtorderby" id="txtorderby"onKeyUp="return maximaLongitud(this,5000)" ><?php echo $sql_orderby; ?></textarea>
                                                </td>
					</tr>
					<tr class="listadofila">
						<td><b>URL INCLUDE</b></td>
						<td>
                                                    <textarea name="txturl_include" id="txturl_include"onKeyUp="return maximaLongitud(this,2000)" ><?php echo $url_include; ?></textarea>
                                                </td>
					</tr>
					<tr class="listadofila">
						<td><b>URL INCLUDE DESPUES</b></td>
						<td>
                                                    <textarea name="txturl_include_despues" id="txturl_include_despues"onKeyUp="return maximaLongitud(this,2000)" ><?php echo $url_include_despues; ?></textarea>
                                                </td>
					</tr>
					<tr class="listadofila">
						<td><b>SUBTOTALES</b></td>
						<td>
							<table class='subtotales' width="100%">
								<tr>

									<td class='subtotales'>
										Agrupaciones de:<br>
										<textarea name="txtagrupaciones" id="txtagrupaciones"
											class='subtotales'
											onKeyUp="return maximaLongitud(this,2000)" 
											title="cliente,producto,"
											><?php echo $subtotales_agrupaciones; ?></textarea>
									</td>

									<td class='subtotales'>
										Funciones:<br>
										<textarea name="txtfunciones" id="txtfunciones"
											class='subtotales'
											onKeyUp="return maximaLongitud(this,2000)" 
											title="suma(precio),promedio(precio),"
											><?php echo $subtotales_funciones; ?></textarea>
									</td>	

									<td class='subtotales'>
										Línea de subtotal en:<br>
										<textarea name="txtsubtotales" id="txtsubtotales"
											class='subtotales'
											onKeyUp="return maximaLongitud(this,2000)" 
											title="suma(precio),promedio(precio),"
											><?php echo $subtotales_subtotal; ?></textarea>
									</td>	

								</tr>
							</table>
                             
                        </td>
					</tr>


				</tbody>
				
			</table>
			<script>
				function guardar(){
					var txtnombre = document.getElementById("txtnombrereporte");
					var txtdesc = document.getElementById("txtdesc");
					if(txtnombre.value=='') {
						alert('Capture el nombre.');						
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


                                function maximaLongitud(texto,maxlong)
                                {
                                    var tecla, int_value, out_value;

                                    if (texto.value.length > maxlong)
                                    {
                                        /*con estas 3 sentencias se consigue que el texto se reduzca
                                        al tamaño maximo permitido, sustituyendo lo que se haya
                                        introducido, por los primeros caracteres hasta dicho limite*/
                                        in_value = texto.value;
                                        out_value = in_value.substring(0,maxlong);
                                        texto.value = out_value;
                                        alert("La longitud máxima es de " + maxlong + " caractéres");
                                        return false;
                                    }
                                        return true;
                                 }
                                 
			</script>
		</form>
	</body>
</html>

<?php

$conexion->cerrar();

?>
