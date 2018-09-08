<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../../../libraries/bootstrap/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/imprimir_bootstrap.css" />
<style>
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
  	h4, h3{
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
    .table thead, .table tbody tr {
	    display:table;
	    width:100%;
	   	table-layout: fixed;/* even columns width , fix width of table too*/
	}
	@media print{
    	.pagination, input[type='button'], input[type='submit'], img{
    		display: none;
    	}
    	.table-responsive{
			overflow-x: unset;
		}
		#imp_cont{
			width: 100% !important;
		}
    }
    .nmcatalogbusquedatit {
	    background-color: #c2c2c2;
	    border: 1px solid silver !important;
	}
</style>
<script language='javascript'>
$(document).ready(function() {
	
	$.ajax({
		url: '../funcionesBD/inv.php',
		type: 'POST',
		dataType: 'json',
		data: {Operacion: 4},
	})
	.done(function(data) {
		console.log('perroo');
	console.log(data);
		$('#depDiv').html(data.dep);
		$('#tallaDiv').html(data.talla);
		$('#colorDiv').html(data.col);

	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
});
function excel()
	{
		window.location = '../../cont_repolog/excel/generaexcel.php?nombreseccion=reporte punto de reorden'
	}
function buscaFam(){
	var idDep = $('#departamento').val();
	$.ajax({
		url: '../funcionesBD/inv.php',
		type: 'POST',
		dataType: 'json',
		data: {Operacion: 5,idDep:idDep},
	})
	.done(function(data) {
		console.log(data);
		$('#famDiv').empty();
		$('#famDiv').html(data.fam);

		$('#linDiv').empty();
		$('#linDiv').append('<select name="linea" class="form-control" id="linea"><option value="0" selected >-Linea-</option></select>');
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function buscaLin(){
	var idFam = $('#familia').val();
	$.ajax({
		url: '../funcionesBD/inv.php',
		type: 'POST',
		dataType: 'json',
		data: {Operacion: 6,idFam:idFam},
	})
	.done(function(data) {
		console.log(data.lin);
		$('#linDiv').empty();
		$('#linDiv').append(data.lin);
	
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}	

</script>

<?php
session_start();
include("../../../netwarelog/webconfig.php");
$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$almacenes = $connection->query("SELECT idAlmacen, nombre FROM almacen");
?>

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Reporte Punto de Reorden (Minimos)<br>
				<section id="botones">
					<a href="javascript:window.print();"><img class"nmwaicons" src="../../../netwarelog/design/default/impresora.png" border="0"></a>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<section id='imprimible'>
						<!--Formulario de busqueda-->
						<form name='stock' method='get' action=''>
							<div class="row">
								<div class="col-md-3 col-sm-3">
									<input type='text' name='nombre' placeholder='Nombre del art&iacute;culo' class="form-control">
								</div>
								<div class="col-md-3 col-sm-3">
									<select name='almacenes' id='almacenes' class="form-control">
										<option value='0'>Todos los almacenes</option>
										<?php
										while($obj = $almacenes->fetch_object())
										{
											echo "<option value='".$obj->idAlmacen."'>".$obj->nombre."</option>";
										}
										?>
									</select>
								</div>
								<div class="col-md-3 col-sm-3" id="depDiv">
								</div>
								<div class="col-md-3 col-sm-3" id="famDiv">
									<select name="familia" class="form-control">
						     			<option value="0" selected >-Familia-</option>
						     		</select> 
								</div>
							</div>
							<div class="row">
								<div class="col-md-3 col-sm-3" id="linDiv">
									<select name="linea" class="form-control" id='linea'>
						     			<option value="0" selected >-Linea-</option>
						     		</select>
								</div>
								<div class="col-md-3 col-sm-3" id="colorDiv">
								</div>
								<div class="col-md-3 col-sm-3" id="tallaDiv">
								</div>
								<div class="col-md-2 col-sm-2">
									<input type='submit' value='Enviar' name='enviar' class="btn btn-primary btnMenu">
								</div>
								<div class="col-md-1 col-sm-1">
									<input type="button" name="crea" value="Excel" onClick="javascript:excel()" class="btn btn-success btnMenu">
								</div>
							</div>
						</form>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva" style="margin-bottom:5em!important;">
								<div class="table-responsive">
									<?php
										//Al dar click hace busqueda
										if(isset($_GET['enviar']))
										{
											$filtro = '';
											if($_GET['nombre'] != '' AND $_GET['almacenes'] != 0)
											{
												$filtro = "AND (p.nombre LIKE '%".$_GET['nombre']."%' or p.codigo LIKE '%".$_GET['nombre']."%') AND a.idAlmacen = ".$_GET['almacenes'];
											}

											if($_GET['nombre'] != '' AND $_GET['almacenes'] == 0)
											{
												$filtro = "AND (p.nombre LIKE '%".$_GET['nombre']."%' or p.codigo LIKE '%".$_GET['nombre']."%')";
											}
											
											if($_GET['nombre'] == '' AND $_GET['almacenes'] != 0)
											{
												$filtro = "AND a.idAlmacen = ".$_GET['almacenes'];
											}
											if($_GET['linea']!=0){
												$filtro .=' and p.idLinea='.$_GET['linea']; 
											}
											if($_GET['color']!=0){
												$filtro .=' and p.color ='.$_GET['color'];
											}
											if($_GET['talla']!=0){
												$filtro .=' and p.talla='.$_GET['talla'];
											}

											$Query = "SELECT p.idProducto AS Clave,p.nombre AS Nombre, p.descorta AS Descripcion, s.cantidad AS Cantidad, p.minimo AS Minimo, a.nombre AS Almacen
											FROM mrp_stock s 
											INNER JOIN mrp_producto p ON p.idProducto = s.idProducto 
											INNER JOIN almacen a ON a.idAlmacen = s.idAlmacen WHERE p.estatus=1 AND s.cantidad<=p.minimo ".$filtro." ORDER BY p.nombre";

											///////////////////////////////////////////////////////////////////////// * Inicia Paginacion * ///////////////////////////////////
											if(isset($_GET['p']))
											{
												$limite = $_GET['p'];//Si la variable get esta seteada toma su valor
											}
											else
											{
												$limite=0; //si no esta seteada es igual a 0
											}

											//--------------------------------------------------------------------
											$valorLimit = 30;//Valor de la paginacion busca de 30 en 30 limit 0,30
											//--------------------------------------------------------------------

											$inicial = 0;//Valor inicial de la paginacion limit 0,30
											$num=$connection->query($Query);
											$numero = $num->num_rows/$valorLimit; //Consultamos cuantas paginas seran necesarias para la paginacion
											if(is_float($numero))//Si el resultado no es un entero se crea una pagina mas para tomar los registros sobrantes
											{
												$numero+=1;
											}

											//Se crean los botones de la paginacion
											for($nn = 1;$nn<=$numero;$nn++)
											{
												if($_GET['p'] == $inicial)//Si es el actual no se crea el boton solo se queda el numero de pagina
												{
													$paginacion.="| <b>$nn</b> | ";
												}
												else//Si no es el actual se crea el boton de paginacion
												{
													$paginacion.="| <a href='stock.php?nombre=".$_GET['nombre']."&almacenes=".$_GET['almacenes']."&enviar=".$_GET['enviar']."&p=$inicial'>$nn</a> | ";
												}
												$inicial+=$valorLimit;//Incrementa la el valor de busqueda para el limit
											}
											///////////////////////////////////////////////////////////////////////// * Termina Paginacion * ///////////////////////////////////

											$_SESSION['consulta'] = $Query." LIMIT ".$limite.",$valorLimit"; //La consulta que se despliega paginada
											$_SESSION['excel'] = $Query;//La consulta con todos los registros para excel,
											$consulta=$connection->query($_SESSION['consulta']);
											//echo $_SESSION['consulta'];
											//Muestra resultados
									?>
									<table border='1' style='font-size: 10px; min-width: 1000px;' class="table table-striped">
										<tr class='tit_tabla_buscar'>
											<td class="nmcatalogbusquedatit">Id Producto</td>
											<td class="nmcatalogbusquedatit">Nombre</td>
											<td class="nmcatalogbusquedatit">Descripci&oacute;n</td>
											<td class="nmcatalogbusquedatit">Cantidad</td>
											<td class="nmcatalogbusquedatit">M&iacute;nimo</td>
											<td class="nmcatalogbusquedatit">Almacen</td>
										</tr>
										<?php
										$cont=1;//Contador
										while($lista = $consulta->fetch_object())
										{
											if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
											{
										    	$color='nmcatalogbusquedacont_1';
											}
											else//Si es impar pinta esto
											{
										    	$color='nmcatalogbusquedacont_2';
											}

											echo "<tr class='$color'>
												<td>".$lista->Clave."</td><td>".substr($lista->Nombre,0,15)."</td><td title='".$lista->Descripcion."'>".substr($lista->Descripcion,0,15)."</td><td>".$lista->Cantidad."</td><td>".$lista->Minimo."</td><td>".$lista->Almacen."</td>
											</tr>";
											$cont++;//Incrementa contador
										} 
										//-------------------------Aqui comienza generador de excel---------------------
										echo "<div class='pagination' style='margin-left:10px;margin-bottom:10px'>Resultados: ".$paginacion."</div>";//Pinta la paginacion
										?>
									</table>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>

<?php

//-------------------------Aqui termina generador de excel----------------------


//Termina if isset
}
//Cierra conexion
$connection->close();
?>