<?php

	include("conexionbd.php");

	if(session_id()=='') {
		session_start();
	}
		
	//PARCIALLOG
	include("clases/clparciallog.php");
	$parciallog = new clparciallog($_SESSION['nombreestructura'],$_SESSION["accelog_idperfil"],$conexion);
		
	$idestructura = $_SESSION['idestructura'];
	$nombreestructura = $_SESSION['nombreestructura'];
	$descripcion = $_SESSION['descripcion'];

	$utilizaidorganizacion = $_SESSION['utilizaidorganizacion'];
	$idorganizacion=$_SESSION["accelog_idorganizacion"];
	$campo_idorganizacion = $_SESSION["accelog_campo_idorganizacion"];
	//echo "-".$_SESSION["accelog_campo_idorganizacion"]."- ";
	//echo $url_dominio;

	///// T E M P O R A L   H A S T A   H A C E R   E L   N U E V O   C A T A L O G O   D E   M E S A S   ///// RCA
	if ($idestructura == 232 ) {
		$anexo = " and idDep <> 0 ";
	} else {
		$anexo = "";
	}

	///// PREPARAR PAGINACION SOLICITADA POR USUARIO ////////
	if(!isset($_SESSION["pag_".$_SESSION['nombreestructura']])){
		$_SESSION["pag_".$_SESSION['nombreestructura']] = 0;
	}

	$filas = 0;
	if(isset($_POST["txtf"])) $filas = $_POST["txtf"];
	if($filas==0){
		if($_SESSION["pag_".$_SESSION['nombreestructura']]!=0){
			$_SESSION["pag_".$_SESSION['nombreestructura']]-=$filas_pagina;					
		}
	} else {
		if($_SESSION["pag_".$_SESSION['nombreestructura']."_limite"]!="1"){
			$_SESSION["pag_".$_SESSION['nombreestructura']]+=$filas_pagina;
		}
	}
	//echo "<br><br>pag_nombreestructura:".$_SESSION["pag_".$_SESSION['nombreestructura']]."  --limite:".$_SESSION["pag_".$_SESSION['nombreestructura']."_limite"]."<br>";

	////////////
	//CSRF
	$reset_vars = false;
	include("clases/clcsrf.php");

	$m=$_GET['m'];
	$primeravez=0;
	if(isset($_GET['primeravez'])){
		$primeravez=1;
	} else {
		//CSRF
		if(!$csrf->check_valid('post')){
			$accelog_access->raise_404();
			exit();
		}
	}

	$columnas="";
	$filas="";
	$filtros="";
	$campos=array();
	$tipo=array();
	$llave=array();

	$validables=array();
	$script_paralinks="";

	//Obteniendo encabezado ...
	$sql = "
		select * from catalog_campos
		where idestructura=".$idestructura."
			and formato<>'O' ".$parciallog->get_where_excluircampos()."
		order by orden";
	//echo $sql;
	$result = $conexion->consultar($sql);
	$sqlw = "";

//S E C C I O N   P A R A  M O S T R A R    E S T A T U S      P O R     D E F E C T O
	$nombrecampoestatus="";
	$valorestatus="";

	if($primeravez==1){ //MODIFICACION 2011-05-05
		$sqlestatus = " SELECT c.nombrecampo,me.valordefault
						FROM configuracion_manejadorestatus me 
							inner join catalog_campos c on me.idcampo=c.idcampo
						where me.idestructura=".$idestructura." limit 1";
		
		$resultestatus = $conexion->consultar($sqlestatus);
		if(($rsestatus = $conexion->siguiente($resultestatus))){
			$nombrecampoestatus = $rsestatus{"nombrecampo"};
			$valorestatus = $rsestatus{"valordefault"};

			if($nombrecampoestatus <> ""){
				$sqlw = " ".$nombrecampoestatus." = ".$valorestatus;
			}
		}
		$conexion->cerrar_consulta($resultestatus);

		$_SESSION["pag_".$nombreestructura]=0;
	}

// S E C C I O N   E S P E C I A L :: ACCELOG_NIVELES   --antes para sistema de Intermerk eliminar este fragmento para otros sistemas CCP

	$sql_accelog_niveles = "select nombrecampo_empleados, nombreestructura from accelog_niveles where idestructura=".$idestructura;
	$result_accelog_niveles = $conexion->consultar($sql_accelog_niveles);
	$sql_an_w="";
	$aplicar_an = 0;
	while($rs_an = $conexion->siguiente($result_accelog_niveles)){
		$aplicar_an = -1;

		$nombrecampo_empleados_an = $rs_an{"nombrecampo_empleados"};
		$nombreestructura_an = $rs_an{"nombreestructura"};

		$idempleado = $_SESSION["accelog_idempleado"];
		$sql_an_especial = " select ".$nombrecampo_empleados_an." from ".$nombreestructura_an." where idempleado=".$idempleado;
		$result_an_especial = $conexion->consultar($sql_an_especial);
		while($rs_an_especial = $conexion->siguiente($result_an_especial)){
			if($sql_an_w!=="") $sql_an_w.=" or ";
			$sql_an_w.=" ".$nombrecampo_empleados_an." = '".$rs_an_especial{$nombrecampo_empleados_an}."' ";
		}
	}

	if($aplicar_an){
		if($sqlw!==""){
			$sqlw.=" and ";
		}
		$sqlw.=" (".$sql_an_w.") ";
		//echo $sqlw;
	}

	/* LO ANTERIOR
	if($idestructura==93){
		$idempleado =  $_SESSION["accelog_idempleado"];
		$sql_especial = " select idccp from empleados where idEmpleado=".$idempleado."  ";
		$result_especial = $conexion->consultar($sql_especial);
		if(($rs_especial = $conexion->siguiente($result_especial))){
			$idccp = $rs_especial{"idccp"};
		}
		$conexion->cerrar_consulta($result_especial);

		if($primeravez==1){ //MODIFICACION 2010-09-24 Solo debe tener seleccionado por omisión el ccp pero no bloquear.
			$sqlw = " idccp = ".$idccp;
		}
	}*/
	///////////////

	while($reg = $conexion->siguiente($result)){
		$columnas.="<td style='width:10px' class='nmcatalogbusquedatit'>".$reg{'nombrecampousuario'}."</td>";
				


				//Obteniendo valor ...
				$valor="";
if(!empty($_REQUEST["i".$reg{'idcampo'}])){
$valor= mysql_real_escape_string($conexion->escapalog($_REQUEST["i".$reg{'idcampo'}]));

								if($valor=="TODOS_VAL_2010-08-26") $valor="";

								if($valor!=""){
										if($sqlw!="") $sqlw.=" and ";
										if($reg{'tipo'}=='int'||$reg{'tipo'}=='double'||$reg{'tipo'}=='auto_increment'){
														$sqlw.="".$reg{'nombrecampo'}."='".$valor."'";
										} else {
														$sqlw.="".$reg{'nombrecampo'}." like '%".$valor."%'";
										}
								}

} else {

						if($primeravez==1){
								if($reg{'tipo'}=="date"){
									$valor = "";
//									if($reg{'valor'}!=-1){ 
										//$valor=date("Y-m-d"); 
//										if($sqlw!="") $sqlw.=" and ";
//										$sqlw.="".$reg{'nombrecampo'}." = '".$valor."'";
//									}
								}
								if($reg{'tipo'}=="datetime"){
									$valor = "";
//									if($reg{'valor'}!=-1){ 
										//$valor=date("Y-m-d H:i:s");  
//										if($sqlw!="") $sqlw.=" and ";
//										$sqlw.="".$reg{'nombrecampo'}." = '".$valor."'";
//									}
								}
						}


				}


				//Checando si es validable
				$sql_validable = " select * from catalog_dependencias where idcampo=".$reg{'idcampo'};
//echo $sql_validable." <br> ";
				$result_datos_validable = $conexion->consultar($sql_validable);
$es_validable=false;
				if(($rs_datos_validable=$conexion->siguiente($result_datos_validable))){
	if($rs_datos_validable{'tipodependencia'}!="N"){
		$es_validable=true;						
	}
}

if($es_validable){
								
							$validables["validable"][$reg{'idcampo'}] = "S";
							$validables["tabla"][$reg{'idcampo'}] = $rs_datos_validable{'dependenciatabla'};
							$validables["campodescripcion"][$reg{'idcampo'}] = $rs_datos_validable{'dependenciacampodescripcion'};
							$validables["campollave"][$reg{'idcampo'}] = $rs_datos_validable{'dependenciacampovalor'};
						

		
		//Si es validable anexa el combo en vez de campo de búsqueda
							$filtros.="<td class='nmcatalogbusqueda'>
								<select class=' nmcatalogbusquedaselect '  id='i".$reg{'idcampo'}."' name='i".$reg{'idcampo'}."'
										title='Segmento de búsqueda, aplique un filtro sobre el campo: ".$reg{'nombrecampousuario'}.".'
										onchange='subirinfo()' >";


										$campodesc = $rs_datos_validable{'dependenciacampodescripcion'};
										$campodesc=str_replace(",",",' ',",$campodesc);	
										$campodesc=" concat(".$campodesc.") as catalog_campodesc ";	

										$sql_para_filtro = "
														select ".$reg{'nombrecampo'}.", ".$campodesc."
														from ".$rs_datos_validable{'dependenciatabla'}."
														order by catalog_campodesc ";
										$result_para_filtro = $conexion->consultar($sql_para_filtro);
			
			
			$seltodos = "selected";
			//echo $rs_datos_validable{'dependenciatabla'}."   ";
			if($primeravez==1){
				if(($nombrecampoestatus<>""&&$nombrecampoestatus==$reg{'nombrecampo'})){								
					$seltodos = "";
					$valor = $valorestatus;
				} 							
			}

			
										 $filtros.="<option value='TODOS_VAL_2010-08-26'   ".$seltodos."   >TODOS";
										while($rs_para_filtro=$conexion->siguiente($result_para_filtro)){
												$sel_para_filtro="";
												if($rs_para_filtro{$reg{'nombrecampo'}}==$valor){
														$sel_para_filtro="selected";
												}
												$filtros.="
														<option value='".$rs_para_filtro{$reg{'nombrecampo'}}."'   ".$sel_para_filtro."   >
																".$rs_para_filtro{"catalog_campodesc"};
										}
										$conexion->cerrar_consulta($result_para_filtro);
										$filtros.="</select></td>";    
																																						
				} else {

						$validables["validable"][$reg{'idcampo'}] = "N";
						$validables["tabla"][$reg{'idcampo'}] = "";
						$validables["campodescripcion"][$reg{'idcampo'}] = "";

						$filtros.="<td class='nmcatalogbusqueda'>
									<div class='form-group has-feedback'>
									<input 
										id='i".$reg{'idcampo'}."' 	
										name='i".$reg{'idcampo'}."'	
										value='".$valor."'
										onkeydown='input_keydown(event)'
										class=\"form-control input-sm txtsearch\" 
										type=\"text\" 
										placeholder=\"\"
										title=\"Escriba aquí lo que desee buscar sobre el campo: ".$reg{'nombrecampousuario'}.", y presione la tecla [ENTER].\" 
										><span 
											class=\"glyphicon glyphicon-search form-control-feedback\" 
											aria-hidden=\"true\" style=\"color:silver;\"></span>
									</div></td>"; // filtros input

				}
				$conexion->cerrar_consulta($result_datos_validable);

				//////





	
$campos[$reg{'idcampo'}]=$reg{'nombrecampo'};
$tipo[$reg{'idcampo'}]=$reg{'tipo'};
$formato[$reg{'idcampo'}]=$reg{'formato'};
if($reg{'llaveprimaria'}){
$llave[$reg{'nombrecampo'}]=1;
} else {
$llave[$reg{'nombrecampo'}]=0;
}
}
$conexion->cerrar_consulta($result);





if($utilizaidorganizacion){
		if($sqlw!=""){
				$sqlw=" where  (".$sqlw.") and ".$campo_idorganizacion." = ".$idorganizacion;
		} else {
				$sqlw=" where  ".$campo_idorganizacion." = ".$idorganizacion;
		}
} else {
		if($sqlw!="") $sqlw=" where ".$sqlw;
}

//echo $sqlw;


//PAGINACION
if(!isset($_SESSION["pag_".$nombreestructura])){
$_SESSION["pag_".$nombreestructura]=0;			
} else {
if($_SESSION["pag_".$nombreestructura]<0){
$_SESSION["pag_".$nombreestructura]=0;					
}			
}
$pagina = $_SESSION["pag_".$nombreestructura];					
////////


///// T E M P O R A L   H A S T A   H A C E R   E L   N U E V O   C A T A L O G O   D E   M E S A S   ///// RCA
if ($idestructura == 232) {
	if ($sqlw == "") {
		$anexo = " Where idDep <> 0 ";
	}
}

// CONSULTA PRINCIPAL ...
//Obteniendo datos ...
$sql = " select * from ".$nombreestructura."  ".$sqlw." ".$anexo."limit ".$pagina.",".$filas_pagina;
//echo $sql;

//MOSTRAR SQL
//echo $sql;


$result = $conexion->consultar($sql);
$i=1;
$f=0;
while($reg = $conexion->siguiente($result)){
$f=$f+1;

if($i==0){
	$filas.="<tr class='nmcatalogbusquedacont_1'>";
	$i=1;
} else {
	$filas.="<tr class='nmcatalogbusquedacont_2'>";
$i=0;
}
$filas.="<tr>";
$sqlw="";
foreach($campos as $idcampo => $nombrecampo){

												//echo $nombrecampo;
if($llave[$nombrecampo]==1){
	if($sqlw!="") $sqlw.="%20and%20";
	$sqlw.=$nombrecampo."%20%3D%20%27".$reg{$nombrecampo}."%27";
}

												if($validables["validable"][$idcampo]=="S"){


														$campodesc = $validables["campodescripcion"][$idcampo];
														$campodesc=str_replace(",",",' ',",$campodesc);	
														$campodesc=" concat(".$campodesc.") as catalog_campodesc ";	

														$sql_validable = " 
																select ".$validables["campodescripcion"][$idcampo].", ".$campodesc."
																from ".$validables["tabla"][$idcampo]."
																where ".$validables["campollave"][$idcampo]." = '".$reg{$nombrecampo}."'                                    
																		";
														//echo $sql_validable;
														 $result_datos_validable = $conexion->consultar($sql_validable);
														 if(($rs_datos_validable = $conexion->siguiente($result_datos_validable))){
																$info=$rs_datos_validable{"catalog_campodesc"};
														 } else {
																 $info=$reg{$nombrecampo};
														 }
														 $conexion->cerrar_consulta($result_datos_validable);

												} else { // else de if($validables["validable"][$idcampo]=="S")


					if($tipo[$idcampo]=="archivo_base"){
						$info = $reg{$nombrecampo."_name"};
					} else {
																
															$info=$reg{$nombrecampo};

						if($formato[$idcampo]=="#"){
							$info = "****";
						}
																
					} //if($tipo[$idcampo]=="archivo_base")
					
					
												} //if($validables["validable"][$idcampo]=="S")


													
																if($i==0) {
																	$filas_class = "nmcatalogbusquedacont_1";
																}else{
																	$filas_class = "nmcatalogbusquedacont_2";
																}
																$filas.="<td class='".$filas_class."'>";
																$filas.="<a id='a".$idcampo.$f."' class=' nmcatalogbusquedacontregistro ' href='#' title='Seleccionar registro.'>";
																$filas.=$info;
																$filas.="</a>";
																$filas.="</td>";

}

foreach($campos as $idcampo => $nombrecampo){

if($m==0){ 
	$script_paralinks.="\n $(\"#a".$idcampo.$f."\").attr('href', 'javascript:eliminar(\"".$sqlw."\")');";			
	$script_paralinks.="\n $(\"#a".$idcampo.$f."\").parent().parent().attr('onclick', 'eliminar(\"".$sqlw."\");');";	
} else {
	//$script_paralinks.="\n $(\"#a".$idcampo.$f."\").attr('href', 'f.php?a=0&sw=".$sqlw."');";	
	//$script_paralinks.="\n $(\"#a".$idcampo.$f."\").parent().parent().attr(\"onclick\", \"open('f.php?a=0&sw=".$sqlw."');\");";
	$script_paralinks.="\n $(\"#a".$idcampo.$f."\").parent().parent().bind('click', function(){ open('f.php?a=0&sw=".$sqlw."'); });";
}				

//$script_paralinks.="\n $(\"#a".$idcampo.$f."[href]\")='".$link."sw=".$sqlw."'; ";
//$script_paralinks.="\n $(\"#a".$idcampo.$f."\").attr('href', '".$link."sw=".$sqlw."'); ";				
}

$filas.="<td class='".$filas_class."'>&nbsp;</td></tr>";
}

//PAGINACION
if($f<$filas_pagina){
$_SESSION["pag_".$nombreestructura."_limite"] = "1";			
} else {
$_SESSION["pag_".$nombreestructura."_limite"] = "0";
}
////////

$conexion->cerrar_consulta($result);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
<head>						
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $descripcion ?></title>
<meta name="generator" content="TextMate 	http://macromates.com/">

	<!--RECURSOS EXTERNOS CSS-->		
	<LINK href="css/view.css" title="estilo" rel="stylesheet" type="text/css" />
	<LINK href="css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />

	<?php include('../design/css.php');?>
	<LINK href="../design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
	
	<!--  ##### BOOTSTRAP & FONT ###### -->
	<link href="../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<link href="../../libraries/select2/dist/css/select2.min.css" rel="stylesheet">
	
	<style>
		.fa-search { color: #7B7B7B; cursor:pointer; left: -18px; position: relative;
					 top: -1px; width: 0; z-index: 1; }
		.txtsearch { min-width: 80px; padding-right: 22px !important; }    	
		.form-group { margin-bottom: 0px !important; }
		.select2 { min-width: 150px; font-weight: normal !important; font-size: 12px; }
		.select2-search__field { width: 100% !important; }
		.select2-container .select2-selection--single { height: 30px !important; }
	</style>
	
	<!--  ##### BEGIN: BOOTSTRAP & JQUERY ###### -->
	<script src="../../libraries/jquery.min.js"></script>
	<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script> 
	<script src="../../libraries/select2/dist/js/select2.min.js"></script>
	<script src="../../libraries/select2/dist/js/i18n/es.js"></script>
	<!--  ##### END: BOOTSTRAP & JQUERY ###### -->   

<script type="text/javascript">
//INICIALIZA EL JQUERY
$(document).ready(function(){

	window.parent.$("li").removeClass("focus");
	if(<?php echo $m; ?>==1){
		window.parent.$("#liedit").addClass("focus");
	} else {
		window.parent.$("#lidelete").addClass("focus");
	}
	
	<?php echo $script_paralinks; ?>

	$(".nmcatalogbusquedaselect").select2({
		language: "es"
	});
	$(".nmcatalogbusquedaselect").next(".select2").find(".select2-selection").focus(function() {
		//console.log($(this).parent().parent().prev());
		$(this).parent().parent().prev().select2("open");
	});	  
});		

function eliminar(sw){
	if(confirm("¿Esta seguro de querer eliminar el registro?")){
		window.location="e.php?sw="+sw;					
	}
}

function open(link){
	window.parent.parent.$("#divloading_tab").fadeIn('slow');
	document.location=link;	
}

function input_keydown(evt){
	var key = evt.keyCode;
	if(key==13){
		subirinfo();
	}
}

function subirinfo(){
	window.parent.parent.$("#divloading_tab").fadeIn("slow");
	document.getElementById("frm").submit();
}

//si f=1 muestra las siguientes $filas_pagina filas si es 0 muestra las $filas_pagina filas previas
function mostrar_filas(f){
	window.parent.parent.$("#divloading_tab").fadeIn("slow");
	/*var surl = "b_paginacion.php?f="+f+"&m=<?php echo $m; ?>";
	alert(surl);
	document.location = surl;*/
	document.getElementById("txtf").value = f;			
	//alert("ok");
	subirinfo();
}

$(document).ready(function (){
	window.parent.parent.$("#divloading_tab").fadeOut("slow");
});


</script>
</head>
<body>
	
	<div class="table-responsive" height="100%">
	<table class=" nmcatalogbusqueda table table-hover table-striped table-responsive">
		<thead>
		<tr>
			<?php echo $columnas; ?>
			<td class="nmcatalogbusquedatit">&nbsp;</td>
		</tr>
		</thead>
		<!--  borrar <tr class="titulo_filtros" title='Segmento de búsqueda'> -->
		<tr class="titulo_filtros" title='Segmento de búsqueda'>
			<!--FORMULARIO-->
			<form id="frm" name="frm" method="post" action="b.php?m=<?php echo $m; ?>" >
				<?php
				//CSRF - FORM
				echo $csrf->input_token($token_id,$token_value);
				?>
				<?php echo $filtros; ?>
				<td class='nmcatalogbusqueda'>&nbsp;</td>
				<input type="hidden" name="txtf" id="txtf" />
				<input type="hidden" name="rand" value="<?php echo rand(1,100); ?>" />
			</form>
		</tr>
		<tbody>
		<?php echo $filas; ?>
		</tbody>
	</table>
	</div>
	
	<script>
		window.parent.parent.$("#divloading").fadeOut("slow");
	</script>
	</body>
</html>
<?php
	$conexion->cerrar();
?>
