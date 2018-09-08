<?php
/* 
 * Este modulo arma el sql de un id dado
 */
 	include("../../netwarelog/webconfig.php");
 	set_time_limit($tiempo_timeout);

 	$mtotales=0;
	session_start();
	$idorg = $_SESSION["accelog_idorganizacion"];
	$sql = $_SESSION["sequel"];
	$descripcion = $_SESSION["desc"];
	$idestiloomision = $_SESSION["iestilo"];
	$url_include = $_SESSION["url_include"];
	$url_include_despues = $_SESSION["url_include_despues"];
	
	include("parametros.php");  

	//echo $sql;
	//OBTENCION DE FILTROS SELECCIONADOS EN MODO HUM
	$filtros_seleccionados_tit = "";
	$filtros_seleccionados_tr = "";
	
	if(isset($_SESSION["repolog_filtros"])){
		$filtros_etiquetas =$_SESSION["repolog_filtros"];
		$filtros_valores_hum = $_SESSION["repolog_valores_hum"];
		$filtros_cuantos = $_SESSION["repolog_cuantos"];
	} else {
		$filtros_cuantos = 0;
	}
	
	//  AGRUPACIONES   SUBTOTALES
	//Obteniendo la información para subtotales y agrupaciones de repolog
		$subtotales_agrupaciones = array_map('trim',explode(",",$_SESSION["subtotales_agrupaciones"]));
		$subtotales_agrupaciones[] = "TOTAL";
		$subtotales_funciones_desordenada = array_map('trim',explode(",",$_SESSION["subtotales_funciones"]));
		$subtotales_funciones = array();

		function regresa_campo_de_funcion($sfuncion){
			$campo_funcion = substr($sfuncion,strrpos($sfuncion, "(")+1);
			$campo_funcion = substr($campo_funcion,0,strrpos($campo_funcion, ")"));
			return $campo_funcion;
		}

		function regresa_funcion_de_campo($sfuncion){
			$campo_funcion = substr($sfuncion,strrpos($sfuncion, "(")+1);
			$campo_funcion = substr($campo_funcion,0,strrpos($campo_funcion, ")"));
			return substr($sfuncion,0,strrpos($sfuncion, "("));
		}

		//Invierte el orden ya que así debe ser su aparición
		$subtotales_subtotal = array_map('trim',explode(",",$_SESSION["subtotales_subtotal"]));
		$subtotales_subtotal[] = "TOTAL";
		$subtotales_subtotal = array_reverse($subtotales_subtotal);

		$control_agrupaciones = array();
		$control_funciones = array();
		$control_subtotal = array();

		$total_campos_visibles=0;
		$total_campos_visibles_grupo=0;
		$fuente_size_niveles=16;
	//////////

	$incluir = 1;
	for($i = 1; $i<=$filtros_cuantos; $i++) {
		$incluir=1;
		
		if($filtros_valores_hum[$i]!="") {

			$pos_barra=strrpos($filtros_etiquetas[$i],"#");
			if(is_numeric($pos_barra)){
				$filtros_seleccionados_tit.=strtoupper(substr($filtros_etiquetas[$i],1));
				$filtros_seleccionados_tr.=strtoupper(substr($filtros_etiquetas[$i],1));
			} else {

				$pos_barra=strrpos($filtros_etiquetas[$i],"@");
				if(is_numeric($pos_barra)){
					$caracter_pregunta = ";";
					$pos_etiqueta=strpos($filtros_etiquetas[$i],$caracter_pregunta);
					$pos_barra+=1;
					$etiqueta = substr($filtros_etiquetas[$i], $pos_barra, $pos_etiqueta-$pos_barra);                                                                                                
					$filtros_seleccionados_tit.= strtoupper($etiqueta);
					$filtros_seleccionados_tr.= strtoupper($etiqueta);

				} else {

					$pos_barra=strrpos($filtros_etiquetas[$i],"!");
					if(is_numeric($pos_barra)){
						$incluir = 0;
						//ESTOS NO SE IMPRIMEN POR SER SESION 
						//QUEDA EL CODIGO POR SI ALGUNA VEZ SE DECIDE DEJARLOS
						//$filtros_seleccionados_tit.=strtoupper(substr($filtros_etiquetas[$i],1));
						//$filtros_seleccionados_tr.=strtoupper(substr($filtros_etiquetas[$i],1));
					} else {
						$filtros_seleccionados_tit.= strtoupper($filtros_etiquetas[$i]);
						$filtros_seleccionados_tr.= strtoupper($filtros_etiquetas[$i]);
					}
				}
			}

			if($incluir==1){
				$filtros_seleccionados_tit.= "=<b>".strtoupper($filtros_valores_hum[$i])."</b> &nbsp; ";
				$filtros_seleccionados_tr.= "= ".strtoupper($filtros_valores_hum[$i])."   ";				
			}
		}
	}

	//REGISTRO TRANSACCIONES -- 2010-10-01
	if($filtros_seleccionados_tr!=""){
		$filtros_seleccionados_tr="   Filtros -  ".$filtros_seleccionados_tr;
	}
	$conexion->transaccion("REPOLOG - ".$descripcion."  ".$filtros_seleccionados_tr,$sql);
?>

<html lang="sp">
	<head>
		<LINK href="../utilerias/css_repolog/estilo-<?php echo $idestiloomision; ?>.css" title="estilo" rel="stylesheet" type="text/css" />
		<?php include('../design/css.php');?>
		<LINK href="../design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--TITULO VENTANA-->
		<title><?php echo $descripcion; ?></title>
		<meta name="generator" content="Netbeans">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-08-07 -->
		<meta name="author-icons" content="Rachel Fu"><!-- Date: 2010-08-07 -->

		<!--PLUG IN CATALOG-->
		<script type="text/javascript" src="../catalog/js/jquery.js"></script>		

		<script type="text/javascript">	
			function pdf() {
				/*	
				var anchopadre = document.getElementById("tabla_reporte").parent.style.width;
				alert(anchopadre);
				var anchotabla = document.getElementById("tabla_reporte").style.width;
			  var pleft = (anchopadre / 2)+(anchotabla/2);
				alert(pleft);
				*/

				var contenido_html = $("#idcontenido_reporte").html();
				//contenido_html = contenido_html.replace(/\"/g,"\\\"");
				$("#contenido").attr("value",contenido_html);
				$("#divpanelpdf").fadeIn(500);
			}

			function generar_pdf() {
				$("#divpanelpdf").fadeOut();
				//$("#loading").fadeIn(500);
			}

			function cancelar_pdf() {
				$("#divpanelpdf").fadeOut();
			}

			function pdf_generado(){
				alert("OK");
			}

			//$('#frpdf').load(function() {
			//	alert("the iframe has been loaded");
			//});	
			//document.getElementById("frpdf").onload = function() {
			//	alert("myframe is loaded");
			//};
	
			//$('#frpdf').ready(function () {
			//  alert("perfecto");
			//});

			function mail() {
				var msg = "Registre el correo electrónico a quién desea enviarle el reporte:";
				var a = prompt(msg,"@netwaremonitor.com");
				if(a!=null){
					var html_contenido_reporte;
					html_contenido_reporte = $("#idcontenido_reporte").html();
					$("#loading").fadeIn(500);
					$("#divmsg").load("mail.php?a="+a, {reporte:html_contenido_reporte});
				}
			}
		</script>
	</head>

	<body>
		<!-- PDF -->
		<!--<iframe name="frpdf" style="width:600px;height:400px;"
		onload="pdf_generado()"></iframe>-->

		<div 
			id="divpanelpdf"
			style="
				position: absolute; top:30%; left: 40%;
				opacity:0.9;
				padding: 20px;
				-webkit-border-radius: 20px;
				border-radius: 10px;
				background-color:#000;
				color:white;
				display:none;
			">
			
			<form id="formpdf" action="pdf.php" method="post" target="_blank" onsubmit="generar_pdf()">
				<center>
					<b> Generar PDF </b> <br> <br>
					<table style="border:none;">
						<tbody>
							<tr>
								<td style="color:white;font-size:13px;">Escala:</td>
								<td style="color:white;font-size:13px;">
									<select id="cmbescala" name="cmbescala">
										<?php
											for($p=100;$p>=1;$p--) {
												echo "<option value=".$p.">".$p."</option>\n";
											}
										?>
									</select> %
								</td>
							</tr>
							<tr>
								<td style="color:white;font-size:13px;">Orientación:</td>
								<td style="color:white;">
									<select id="cmborientacion" name="cmborientacion">
										<option value='P'>Vertical</option>
										<option value='L'>Horizontal</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>  <br>

					<textarea id="contenido" name="contenido" style="display:none"></textarea>
					<input type="submit" value="Crear PDF" autofocus >
					<input type="button" value="Cancelar" onclick="cancelar_pdf()">
				</center>
			</form>
		</div>

		<!-- MAIL -->
		<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;">
			<div 
				id="divmsg"
				style="
					opacity:0.8;
					position:relative;
					background-color:#000;
					color:white;
					padding: 20px;
					-webkit-border-radius: 20px;
					border-radius: 10px;
					left:-50%;
					top:-30%
				">
				<center> <img src='img/loading-black.gif' width='50'><br>Cargando... </center>
			</div>
		</div>

		<script>
			function cerrarloading() {
				$("#loading").fadeOut(0);
				var divloading="<center><img src='img/loading-black.gif' width='50'><br>Cargando...</center>";
				$("#divmsg").html(divloading);
			}
		</script>

		<!-- //////////////////////////////////////////////////////  -->
		<!-- BARRA DE HERRAMIENTAS DEL REPOLOG  -->
		<div class="bh">
			<table class="bh" align="right" border="0">
				<tr>
					<td width=16 align=right>
						<a href="javascript:window.print();"><img src="img/impresora.png" border="0"></a>
					</td>
					<td width=16  align=right>
						<a href="repolog.php?i=<?php  echo $_SESSION['repolog_idreporte'] ?>" onclick="$('#nmloader_div',window.parent.document).show();"> <img src="img/filtros.png"
							title ="Haga clic aqui para cambiar filtros..." border="0"> </a>
					</td>
					<td width=16 align=right>
						<a href="javascript:mail();"> <img src="img/email.png"
							title ="Enviar reporte por correo electrónico" border="0"> 
						</a>
					</td>
					<td width=16 align=right>
						<a href="javascript:pdf();"> <img src="img/pdf.gif"
							title ="Generar reporte en PDF" border="0">
						</a>
					</td>
				</tr>
			</table>
		</div>
		<!-- //////////////////////////////////////////////////////  -->

		<form id="reporte" name="reporte"  method="post" action="redirecciona.php">
			<center>
				<div id="idcontenido_reporte">
					<table style="border:none; width:100%;">
						<tr>
							<td style="width:50%">
								<?php
									//// OBTENIENDO EL LOGO
									$sqlLogo = "select logoempresa from organizaciones where idorganizacion=".$idorg;
									$resultLogo = $conexion->consultar($sqlLogo);
									$bexiste_logo=true;

									if($rsLogo = $conexion->siguiente($resultLogo)) {
										$filename="../archivos/".$idorg."/organizaciones/".$rsLogo{"logoempresa"};
										//echo $filename." --".(!file_exists($filename));
										if(!file_exists($filename)) {
											$bexiste_logo=false;
										}
									} else {
										$bexiste_logo=false;
									}

									if(!$bexiste_logo) {
										$filename="../archivos/".$idorg."/organizaciones/x.png";
										//$filename="http://www.paginasprodigy.com/arquitejas/ph_img/GOOGLE.gif";
										//$filename="http://www.netwarmonitor.com/img/logo.png";
									}

									$conexion->cerrar_consulta($resultLogo);
									$url_img="http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
									$url_img=substr($url_img,0,strlen($url_img)-11).$filename;
								?>

								<img src='<?php echo $url_img; ?>' alt='Logo empresa.' height='55'>
							</td>

							<td style='width:50%;text-align:right;font-size:11px;'>
								<?php
									echo "<b>".date('d/m/Y h:i:s A')."</b>";
								?>
							</td>
						</tr>
					</table> <br>

					<?php
						//PROCESO
						if($url_include!=null) {
							if($url_include!="") {
								include($url_include);
							}
						}
					?> <br>

					<!-- FILTROS -->
					<table style="width:100%;border:none;">
						<tr>
							<td class="nmrepologtitle">
								<span><?php echo $descripcion; ?></span> <br> <br> <?php echo $filtros_seleccionados_tit; ?>
							</td>
						</tr>

						<!--</table>-->
						<tr> 
							<td align="center" width="100%" style="width:100%;text-align:center;">
								<!-- TABLA REPORTE --> 
								<table id="tabla_reporte" class="reporte" border="0" style="width:100%;">
									<tbody>
										<?php
											/////////////////////////////////////////////////////////////
											/////////////////////////////////////////////////////////////
											//SQL DEL REPORTE////////////////////////////////////////////
											/////////////////////////////////////////////////////////////
											/*
											if($fase_desarrollo==1){
												echo "<!--\n\n\n\n\n\n\n\n/////////////////////////////////////////////////\n";
												echo "/////////////////// SQL--FASE DESARROLLO ACTIVADA\n\n";
												echo $sql;
												echo "\n\n/////////////////////////////////////////////////";
												echo "\n/////////////////////////////////////////////////";
												echo "\n\n\n\n\n\n\n\n-->";
											}*/
											$resultado = $conexion->consultar($sql);

											/////////////////////////////////////////////////////////////
											/////////////////////////////////////////////////////////////
											/////////////////////////////////////////////////////////////
										?>

										<tr class="trencabezado" height="10">
											<?php
												$i=0;
												$encontro_campo_funciones=false;
												//$numero_de_campos = mysql_num_fields($resultado);
												//echo $numero_de_campos;
												//$porcentaje_ancho_columna= 100 / $numero_de_campos;
												//echo "<br>".$porcentaje_ancho_columna."<br>";
												while($i < mysql_num_fields($resultado)) {
													$meta = mysql_fetch_field($resultado, $i);
													if(!$meta) {
														echo "información no disponible";
													} else {

														//Cambio 2012-03-09 -- SUBTOTALES Y AGRUPACIONES
														if(array_search($meta->name,$subtotales_agrupaciones)===false) {  
															?>
															<td> <?php echo $meta->name; ?> </td>
															<?php

															//Ordenando los campos de las funciones
															foreach($subtotales_funciones_desordenada as $sfuncion){
																if(regresa_campo_de_funcion($sfuncion)==$meta->name) {
																	$subtotales_funciones[] = $sfuncion;
																	$encontro_campo_funciones=true;
																}
															}
															//////

															if(!$encontro_campo_funciones) $total_campos_visibles++;
															$total_campos_visibles_grupo++;
														} else {
															//  echo "<td class='border:none;background:white;'> &nbsp;</td>";
														} //if($agrupacion===false)
														///////
													}

													//Inicializa Totales
													$tot[$i]=0;
													$i++;
												}
											?>
										</tr>

										<?php
											$valortotal="Sub Total";
											$promedio_filas=0;
											$primeravuelta=true;

											while($rs = $conexion->siguiente($resultado)) {
												$linea="";
												$cambiaestilo=false;

												////////////////////////////////////////////////////////////////////////////
												////////////////////////////////////////////////////////////////////////////
												/////////////////// Líneas de Subtotales ///////////////////////////////////

												//validando los cambios
												$forzarcambio = false;
												$subtotales_subtotal_normal = array_reverse($subtotales_subtotal);
												foreach($subtotales_subtotal_normal as $sagrupacion) {
													if($forzarcambio) {
														$control_subtotal[$sagrupacion]["forzarcambio"]=true; //se forza el cambio por que su nivel superior ha cambiado.
													}

													if((($control_subtotal[$sagrupacion]["dato"]!=$rs{$sagrupacion})&&(!$primeravuelta))&&($sagrupacion!="TOTAL")) {
														$forzarcambio = true;
													}
												}

												$nivel=count($subtotales_subtotal)+1;
												foreach($subtotales_subtotal as $sagrupacion) {
													$nivel--;
													$fuente_nivel = ($fuente_size_niveles - 2) - ($nivel*2);
													$lineatotales = "";

													if( ($sagrupacion!="TOTAL") && ((($control_subtotal[$sagrupacion]["dato"]!=$rs{$sagrupacion})&&(!$primeravuelta))||($control_subtotal[$sagrupacion]["forzarcambio"])) ) {
														$control_subtotal[$sagrupacion]["forzarcambio"] = false;
														$campoant="";

														foreach($subtotales_funciones as $sfuncion) {
															$lineatotales_parcial = "";

															if(regresa_funcion_de_campo($sfuncion)=="suma") {
																//$lineatotales_parcial.=" ".regresa_campo_de_funcion($sfuncion)."=";
																$lineatotales_parcial.="<b>".number_format($control_funciones[$nivel][$sfuncion]["suma"],2)."</b>";
																$control_funciones[$nivel][$sfuncion]["suma"]=0;
															}

															if(regresa_funcion_de_campo($sfuncion)=="promedio") {
																$valor = $control_funciones[$nivel][$sfuncion]["suma"] / $control_funciones[$nivel][$sfuncion]["cuantos"];
																//$valor.=" cuantos:".$control_funciones[$nivel][$sfuncion]["cuantos"];
																//$lineatotales_parcial.=" Promedio de ".regresa_campo_de_funcion($sfuncion)."=";
																$lineatotales_parcial.="<b>Prom: ".number_format($valor,2)."</b>";

																$control_funciones[$nivel][$sfuncion]["suma"]=0;
																$control_funciones[$nivel][$sfuncion]["cuantos"]=0;
															}

															if($campoant!=regresa_campo_de_funcion($sfuncion)) {
																if($lineatotales!="") $lineatotales.="</td>   ";
																//$lineatotales.="</td>";
																$lineatotales.="\n<td style='background-color:#efefef;'>";
																$lineatotales.=$lineatotales_parcial;
															} else {
																$lineatotales.="<br>".$lineatotales_parcial;
															}
															$campoant = regresa_campo_de_funcion($sfuncion);
														}
														$lineatotales.="</td>";

														///////////////////////////////////////////////////////////
														///////////////////////////////////////////////////////////
														/////// IMPRESION DE LINEA DE SUBTOTALES //////////////////
														$linea.="
															\n<tr><th
																style='border:solid 1px;background-color:#efefef;text-align:left;font-size:".$fuente_nivel."px;'
																	class='subtotal'
																	colspan='".$total_campos_visibles."'>
																	Subtotal [".$sagrupacion.": ".$control_subtotal[$sagrupacion]["dato"]."]
																</th>".$lineatotales."</tr><tr>
															\n<td style='border:none;'><br></td></tr>
														";

														///////////////////////////////////////////////////////////
														///////////////////////////////////////////////////////////
														///////////////////////////////////////////////////////////
													}

													/////////////////// Calculando funciones ///////////////////////////////////
													foreach($subtotales_funciones as $sfuncion) {
														//echo "<br>".$sfuncion." ".regresa_campo_de_funcion($sfuncion);
														//if(regresa_funcion_de_campo($sfuncion)=="suma"){

														if(!isset($control_funciones[$nivel][$sfuncion]["suma"])) {
															$control_funciones[$nivel][$sfuncion]["suma"] = 0;
															$control_funciones[$nivel][$sfuncion]["cuantos"] = 0;
														}

														$control_funciones[$nivel][$sfuncion]["suma"]=str_replace(',', '',$control_funciones[$nivel][$sfuncion]["suma"])+str_replace(',', '',$rs{regresa_campo_de_funcion($sfuncion)});
														if(regresa_funcion_de_campo($sfuncion)=="promedio") {
															$control_funciones[$nivel][$sfuncion]["cuantos"]++;
															//echo regresa_funcion_de_campo($sfuncion);
															//echo $control_funciones[$nivel][$sfuncion]["cuantos"];
														}
														//}
													}
													$control_subtotal[$sagrupacion]["dato"]=$rs{$sagrupacion};
												}
												if($primeravuelta==true) $primeravuelta = false;

												/////////////////// Agrupaciones ////////////////////////////////////
												$nivel = 0;
												$forzarcambio = false;
												foreach($subtotales_agrupaciones as $sagrupacion) {
													$nivel++;
													//echo $total_campos_visibles;

													if((($control_agrupaciones[$sagrupacion]!=$rs{$sagrupacion})||($forzarcambio))&&($sagrupacion!="TOTAL")) {
														$fuente_nivel = $fuente_size_niveles - ($nivel*2);
														$linea.="\n<tr class='border:solid 1px;'>
															\n<th style='background-color:silver;
																border:solid 1px;font-weight:normal;
																color:black;text-align:left;
																font-size:".$fuente_nivel."px';
																colspan='".$total_campos_visibles_grupo."';
																>".$sagrupacion.":<b>".$rs{$sagrupacion}."</b>
															</th></tr>\n<tr>";
														$forzarcambio = true;
													}
													$control_agrupaciones[$sagrupacion]=$rs{$sagrupacion};
												}

												/*aqui aqui
												//Líneas de subtotales...
												$nivel = 0;
												foreach($subtotales_subtotal as $sagrupacion) {
													$nivel++;
													//echo $total_campos_visibles;
													if($control_agrupaciones[$sagrupacion]!=$rs{$sagrupacion}) {
														if($primeravuelta==false) {
															$linea.="<tr><td>totales</td></tr>";
														}
													}
													$control_agrupaciones[$sagrupacion]=$rs{$sagrupacion};

													//Totales y promedios por nivel
													foreach($subtotales_funciones as $sfuncion) {

														$campo_funcion = substr($sfuncion,strrpos($sfuncion, "(")+1);
														$campo_funcion = substr($campo_funcion,0,strrpos($campo_funcion, ")"));

														if(substr($sfuncion,0,strrpos($sfuncion, "("))=="suma") {
															$control_funciones[$sfuncion]=$control_funciones[$sfuncion]+$rs{$campo_funcion};
														}

														if(substr($sfuncion,0,strrpos($sfuncion, "("))=="promedio") {
															$promedio_filas++;
															$control_funciones[$nivel,$sfuncion]=+$rs{"campo_funcion"};
														} 
													}
													////////////////////////////////////////////////////////////////////////////
												}

												if($primeravuelta==true) $primeravuelta = false;
												*/
												////////////////////////////////////////////////////////////////////////////
												////////////////////////////////////////////////////////////////////////////

												$i=0;
												while($i < mysql_num_fields($resultado)) {
													$meta = mysql_fetch_field($resultado, $i);
													if(!$meta) {
														echo "información no disponible";
													} else {
														$d = $rs{$meta->name};
														if (strtolower($d)=="sub total" || strtolower($d)=="subtotal" || strtolower($d)=="total" ) $cambiaestilo=true;
														//if(strpos($d,"TOTAL")!=false) $cambiaestilo=true;

														$estilotd = "tdcontenido";
														if($cambiaestilo) {
															$estilotd = "tdsubtotal";
														} else {
															$signopesos = strpos($rs{$meta->name},"$"); //echo "      --".$rs{$meta->name}."  ".$signopesos."--    ";
															if($signopesos !== false) {
																$estilotd = "tdmoneda";
															}
															$signoporcentaje = strpos($rs{$meta->name},"%");
															if($signoporcentaje !== false) {
																$estilotd = "tdmoneda";
															}
														}

														///////////////////////////////////////////////////
														///////////////////////////////////////////////////
														//IMPRESION NORMAL DEL DATO DEL CAMPO
														if(array_search($meta->name,$subtotales_agrupaciones)===false) {
															$linea.="\n<td class='".$estilotd."' title='".$meta->name."'>".$rs{$meta->name}."</td>";
														} else {
															//No hacer nada ya que se trata de un campo agrupador.
														}

														///////////////////////////////////////////////////
														///////////////////////////////////////////////////
														//Acumula Totales e todos los campos numericos
														$valor=str_replace(",","",$rs{$meta->name});
														if(is_numeric($valor)) {
															$tot[$i]+=$valor*1;
														} else {
															$tot[$i]+=0;
														}
													}
													$i++;
												}

												if($cambiaestilo) {
													$linea = "\n<tr class='trsubtotal'>".$linea."</tr>";
												} else {
													$linea = "\n<tr class='trcontenido'>".$linea."</tr>";
												}
												echo $linea;
											} //while principal

											//Debido a que ya no hay más detección de cambios estas líneas son necesarias para imprimir la última línea de subtotales...
											$linea = "";
											/////////////////// Líneas de Subtotales ///////////////////////////////////
											$nivel=count($subtotales_subtotal)+1;
											foreach($subtotales_subtotal as $sagrupacion) {
												$nivel--;
												$fuente_nivel = ($fuente_size_niveles - 2) - ($nivel*2);
												$lineatotales = "";
												//if(($control_subtotal[$sagrupacion]!=$rs{$sagrupacion})&&(!$primeravuelta)){

												$campoant="";
												foreach($subtotales_funciones as $sfuncion) {
													$lineatotales_parcial="";

													if(regresa_funcion_de_campo($sfuncion)=="suma") {
														//$lineatotales.=" ".regresa_campo_de_funcion($sfuncion)."=";
														$lineatotales_parcial.="<b>".number_format($control_funciones[$nivel][$sfuncion]["suma"],2)."</b>";
														$control_funciones[$nivel][$sfuncion]["suma"]=0;
													}

													if(regresa_funcion_de_campo($sfuncion)=="promedio"){
														$valor = $control_funciones[$nivel][$sfuncion]["suma"] / $control_funciones[$nivel][$sfuncion]["cuantos"];
														//$valor.=" cuantos:".$control_funciones[$nivel][$sfuncion]["cuantos"];
														//$lineatotales.=" Promedio de ".regresa_campo_de_funcion($sfuncion)."=";
														$lineatotales_parcial.="<b>Prom: ".number_format($valor,2)."</b>";
														$control_funciones[$nivel][$sfuncion]["suma"]=0;
														$control_funciones[$nivel][$sfuncion]["cuantos"]=0;
													}

													if($campoant!=regresa_campo_de_funcion($sfuncion)) {
														if($lineatotales!="") $lineatotales.="</td>   ";
														$lineatotales.="\n<td style='background-color:#efefef;'>  ";
														$lineatotales.=$lineatotales_parcial;
													} else {
														$lineatotales.="<br>".$lineatotales_parcial;
													}
													$campoant = regresa_campo_de_funcion($sfuncion);
												} //foreach($subtotales_funciones as $sfuncion)
												$lineatotales.="</td>";

												if($sagrupacion=="TOTAL") {
													$linea_TOTAL = $lineatotales;
													$lineatotales="";
												} else {

													///////////////////////////////////////////////////////////
													///////////////////////////////////////////////////////////
													/////// IMPRESION DE LA ULTIMA LINEA DE SUBTOTALES ////////
													$linea.="
														\n<tr>
															<th style='border:solid 1px;background-color:#efefef;text-align:left;font-size:".$fuente_nivel."px;'
																class='subtotal'
																colspan='".$total_campos_visibles."'>
																Subtotal [".$sagrupacion.": ".$control_subtotal[$sagrupacion]["dato"]."]
															</th>".$lineatotales."</tr>
														\n<tr><td style='border:none;'><br></td></tr>
													";
													///////////////////////////////////////////////////////////
													///////////////////////////////////////////////////////////
													///////////////////////////////////////////////////////////
												}
											}

											//Si no hay mas de un elemento (el elemento TOTAL) entonces no quiere agrupaciones.
											//echo "<br>".$subtotales_subtotal[0]."<br>";
											//echo $subtotales_agrupaciones[0];
											//if((count($subtotales_agrupaciones)>=2)&&($subtotales_agrupaciones[0]!="")){
												//echo count($subtotales_subtotal)." ".$subtotales_subtotal[0]." ".$subtotales_subtotal[1];
											if((count($subtotales_subtotal)>=2)&&($subtotales_subtotal[1]!="")) {
												echo $linea;   //con subtotales

												//LINEA DEL TOTAL GENERAL
												$ultima_linea= "
														\n<tr style='color:black'>
															<th
															style='border:solid 1px gray;background-color:#efefef;text-align:left;font-size:".$fuente_nivel."px;'
															class='subtotal'
															colspan='".$total_campos_visibles."'>
															TOTAL
															</th>".$linea_TOTAL."</tr>
														\n<tr><td style='border:none;'><br></td></tr>
												";
												echo $ultima_linea;
												//echo "<textarea>".$ultima_linea."\n\n'".$linea_TOTAL."'</textarea>";
											}

											//Solo funciona si en un proceso previo se actualiza el arreglo de ctotales
											if($mtotales==1) {
												$linea="";
												for ($z=0; $z<=$i-1; $z++) {
													//Busca elemento $ctotales(mostrar,decimales,valordefecto)-Configuracion Totales
													if($ctotales["mostrar"][$z]==1) {
														$d=$ctotales["decimales"][$z];
														$linea.="\n<td class='tdsubtotal'>".number_format($tot[$z],$d)."</td>";
													} else {
														$linea.="\n<td class='tdsubtotal'>".$ctotales["valordefecto"][$z]."</td>";
													}
												}
												$linea="\n<tr class='trsubtotal'>".$linea."</tr>";
												echo $linea;
											}
											$conexion->cerrar_consulta($resultado);

											//ESTO SE DESHABILITO PARA PODER UTILIZAR LA OPCION DEL PDF
											//unset($_SESSION["sequel"]);
											//unset($_SESSION["repolog_valores"]);
											//unset($_SESSION["repolog_valores_hum"]);
											//unset($_SESSION["repolog_cuantos"]);
											//unset($_SESSION["repolog_filtros"]);

											//ENVIO NUEMERO DE REPORTE
										?>
									</tbody>
								</table>
							</td>
						</tr>
					</table><!--TABLA DESDE LOS FILTROS  -->
				</div> <!-- idcontenido_reporte -->
			</center>

			<input id="txtidreporte" type="hidden" name="txtidreporte" value="<?php echo $_SESSION["repolog_idreporte"]; ?>">
			<?php
				//PROCESO
				if($url_include_despues!=null) {
					if($url_include_despues!="") {
						include($url_include_despues);
					}
				}
			?>
		</form>
	</body>
</html>

<script>
	$('#nmloader_div',window.parent.document).hide();
</script>
