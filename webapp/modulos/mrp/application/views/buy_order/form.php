<?php
include_once("../../netwarelog/catalog/conexionbd.php"); 
$this->load->helper('url');
$base_url=str_replace("modulos/mrp/","",base_url());
?>

<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />

 <!-- <link rel="stylesheet" type="text/css" href="../../../../netwarelog/design/default/netwarlog.css" /-->  	
<?php include('../../netwarelog/design/css.php');?>
<LINK href="../../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->


<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../../../modulos/mrp/js/jquery.alphanumeric.js"></script>
<!-- <script type="text/javascript" src="../../js/funciones.js"></script> -->
<script type="text/javascript" src="../../../../modulos/punto_venta/punto_venta.js"></script>
<script type="text/javascript" src="../../../../modulos/mrp/js/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../../../modulos/mrp/js/select2/select2.css" />
<script type="text/javascript">
	var baseurl = '<?php echo base_url(); ?>';
</script>

<script type="text/javascript" src="../../../../modulos/mrp/js/buy_order.js"></script>
<script src="../../../../modulos/mrp/js/ui.datepicker-es-MX.js"></script>
<script>
$(document).ready(function() {
	$("#producto").select2({
         width : "300px"
    });
    $("#componente").select2({
         width : "150px"
    });  
});
	function cargaLineas2($iddepa,$idFamilia)
{ $("#preloader_filtros").show();
	$.ajax({
		type: 'POST',
		url:'../../../mrp/filtros.php/',
		data:{funcion:"cargaLineas2",iddepa:$iddepa,idFamilia:$idFamilia},
		success: function(resp){  $("#span-lineas").html(resp);   
		$("#preloader_filtros").hide();    }});//end ajax	
}
function cargaFamilias2($idDepartamento)
{ $("#preloader_filtros").show();
		$.ajax({
		type: 'POST',
		url:'../../../mrp/filtros.php/',
		data:{funcion:"cargaFamilias2",idDepartamento:$idDepartamento},
		success: function(resp){  
			$("#span-familias").html(resp);
$("#preloader_filtros").hide();      }});//end ajax	
}
function cargaProductos($idLinea)
{ $("#preloader_filtros").show();
		$.ajax({
		type: 'POST',
		url:'../../../mrp/filtros.php/',
		data:{funcion:"cargaProductos",idLinea:$idLinea},
		success: function(resp){  $("#span-productos").html(resp);  
		 $("#preloader_filtros").hide();    }});//end ajax	
}
function loadproductos($iddepa,$idfamilia,$idLinea)
{ $("#preloader_filtros").show();
		$.ajax({
		type: 'POST',
		url:'../../../mrp/filtros.php/',
		data:{funcion:"productosexistencias",iddepa:$iddepa,idfamilia:$idfamilia,idlinea:$idLinea},
		success: function(resp){  
             $("#span-productos").html(resp);  
		   $("#preloader_filtros").hide();  }});//end ajax	
	
}	
	
</script>
	 
<!--Script del autocompletado del campo de productos !-->
<script>
$(document).ready(function() {
	
	$("#preloader_grid").hide();
	$("#preloader_preview").hide();
	$("#preloader_filtros").hide();
	$("#preloader_generar").hide();
	$(".preloader_preview_elemento").hide();
	
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	//$(".datepicker").datepicker();
	
	$(".positive").numeric();
	$("#ultimo_costo").numeric({allow:"."});
	
	$("#cantidad_producto").numeric({allow:"."});
	
	 $("#fecha_pedido").datepicker({
	 	dateFormat: 'yy-mm-dd',
        minDate: 0,
        maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#fecha_entrega").datepicker("option","minDate", selected)
        }
    });
    
    $("#fecha_entrega").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        minDate: 0,
        maxDate:"+60D",
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#fecha_pedido").datepicker("option","maxDate", selected)
        }
    }); 
    
    // $( "#busca_cliente" ).autocomplete({
      // source: availableTags
    // });
	
});
</script>


<?php

if(!isset($_SESSION)) {
     session_start();
	
}

if(isset($_SESSION["nombre_array"]))
{
		$cantidad = $_SESSION["cantidad_array"];
		$unidad = $_SESSION["unidad_array"];
		$nombre = $_SESSION["nombre_array"];
		$proveedor = $_SESSION["proveedor_array"];
		$costo = $_SESSION["costo_array"];
		$subtotal = $_SESSION["subtotal_array"];
		$unidad_texto = $_SESSION["unidad_texto"];
		$nombre_texto = $_SESSION["nombre_texto"];
		$proveedor_texto = $_SESSION["proveedor_texto"];
				
		for($i=0;$i<count($_SESSION["nombre_array"]);$i++)
		{
			if(isset($_REQUEST["chk".$i]))
			{
				unset($cantidad[$i]);
				unset($unidad[$i]);
				unset($nombre[$i]);
				unset($proveedor[$i]);
				unset($costo[$i]);
				unset($subtotal[$i]);
				unset($unidad_texto[$i]);
				unset($nombre_texto[$i]);
				unset($proveedor_texto[$i]);
				
			}
		}
				$_SESSION["cantidad_array"] = array_values($cantidad);
				$_SESSION["unidad_array"] = array_values($unidad); 
				$_SESSION["nombre_array"] = array_values($nombre);
				$_SESSION["proveedor_array"] = array_values($proveedor);
				$_SESSION["costo_array"] = array_values($costo);
				$_SESSION["subtotal_array"] = array_values($subtotal);
				$_SESSION["unidad_texto"] = array_values($unidad_texto);
				$_SESSION["nombre_texto"] = array_values($nombre_texto);
				$_SESSION["proveedor_texto"] = array_values($proveedor_texto);
?>

		<script>
		imprime_grid();
		$('#borrar').prop('disabled', false);
		</script>
		
<?php

}

?>
<style>
	.ui-accordion-header {  
    background-color: #4c4c4c !important;
    background-image: none !important;  
    color: white !important;
    margin: 0px;  
}
body{
	background-color: #e5e5e5 !important;
}
</style>
<!--Contenido de la ventana de ordenes!-->
<body>

<div id="formulario_ordenes">
<div id="registro_nuevo">
	    <div class="tipo">
	    <a href="javascript:window.print();">
	    <img border="0" src="../../../../netwarelog/repolog/img/impresora.png">
	    </a>
	    <b>Registro nuevo</b>
	    </div>
    <br>
</div>

<center>
<div class="campos" width="65%">
 
<!-- /////////////////////////////////////////////////////// !-->

	<div class="listadofila" title="Encabezado solicitud" style="width: 80%; text-align: left; margin-bottom: 20px;">
	    <div style="display: table-cell" style="width: 65%;">
	    	<div class="campo" align="center" style="text-align: left; width: 80%; margin-bottom: 10px;">
			    <label> Sucursal que solicita: </label>
			    <font color="silver">*</font>
			    <br>
			    <div id='suc'>
			    		
					<?php echo $suc; ?>
				</div>
				<label>Almacen </label><font color="silver">*</font>
				 <div id='almacenes' >
					<select class="nminputselect" style="width: 80%;" ><option>-Seleccione-</option></select>
				</div>
				
			</div>
		</div>
		<div class="campo" style="display: table-cell; width: 35%; vertical-align: middle; text-align: right;">
			
		    <div class="campo" style="display: table-cell; padding-right: 70px; width: 0%;">
			    <label>Fecha del pedido: </label>
			    <font color="silver">*</font>
			    
			    <input readonly class="nminputtext" id="fecha_pedido" type="text" style="width: 50%;"
			    									<?php 
			    									if (isset($_SESSION['fecha_pedido_temporal']))
			    									{echo "value='".$_SESSION['fecha_pedido_temporal']."'";}
													else
													{

													 $hoy = getdate();

													 echo "value='".date('Y-m-d')."'";	 
													}
													?> />
				<br>
		       <label>Fecha de entrega parcial: </label>
			    
			    <input readonly class="nminputtext" id="fecha_entrega" type="text" style="width: 50%;"
			    									<?php 
			    									if (isset($_SESSION['fecha_entrega_temporal']))
			    									{echo "value='".$_SESSION['fecha_entrega_temporal']."'";} ?> />
		    </div>
	    </div>
	    
	    
	    
		
	</div>
	
<!-- /////////////////////////////////////////////////////// !-->

	<div class="listadofila" title="Pedido" style="border-top: 1px solid #DDDDDD; padding-top: 20px; padding-bottom: 20px; width: 80%">

		<div style="display: table-cell; vertical-align: middle; padding-right: 25px; width: 20%; text-align: left;">
			<div>
			    <label id="lbl357">Cantidad: </label>
			    <font color="silver">*</font>
			    <br>
			    <input  type="text" id="cantidad_producto" style="width: 60%" maxlength="8" class="nminputtext"/>
		    </div>
		    <!--<div class="campo">
			    <label id="lbl357">Unidad: </label>
			    <br>
			    <div id='uni'>
					<?= $uni ?>
				</div>
		    </div>-->
		</div>
	    
	    <div style="display: table-cell; width: 70%; vertical-align: middle; text-align: left; border-left: 1px solid #DDDDDD; padding-left: 25px; padding-right: 20px;">
		    
		    
		    <fieldset style="border-color: #EFFFFF;">
		    	<legend>Filtros de producto</legend>
		    	
				    <div class="campo">
					    <div class="campo" align="center" 	style="display: table-cell; text-align: left; width: 30%;">
						    <label> Departamento: </label>
						    <br>
						    <div id='dep_producto'>
								<?php echo $dep_prod; ?>
							</div>
						</div>
						<div class="campo" align="center" 	style="display: table-cell; text-align: left; width: 30%;">
						    <label> Familia: </label>
						    <br>
						    <span id='span-familias'>
						    	
								<?php echo $fam; ?>
							</span>
						</div>
						<div class="campo" align="center" 	style="display: table-cell; text-align: left; width: 30%;">
						    <label> Linea: </label>
						    <br>
						    <span id='span-lineas'>
								<?php echo $lin; ?>
							</span>
						</div>
						
					</div>
			</fieldset>
			<p>
		    <div>
			    <div class="campo" style="display: table-cell; padding-right: 18px;" >
				    <label id="lbl357">Productos que vendo: </label>
					<div style="width: 100%">
							<span id="span-productos">
								<?php echo $pro; ?>
								<!-- <select id="producto" name="producto" onchange="cargaProducto(this.value);">
									
								</select> -->
							</span>
							
							</div>
					</div>
					
					<div style="display: table-cell; vertical-align: middle;" > 
				<!--		<input type="button" id="agregar_producto" value="Agregar a lista..." onClick="ver_componente()" /> -->
					<input type="button" id="agregar_producto" value="Agregar a lista..." onClick="ver_producto()" class="nminputbutton_color2"/> 

					</div>
					
				</div>
				
				<div class="campo" style="display: table-cell; padding-right: 18px;">
				    <label id="lbl357">Productos de consumo ó material de producción: </label>
					<div style="width: 100%">
							<span id="componente_div">
							<?php echo $com; ?>
							</span>
					</div>
					
				</div>	<div style="display: table-cell; vertical-align: middle;" > 
						<input type="button" id="agregar_componente" value="Agregar a lista..." onClick="ver_componente()" class="nminputbutton_color2"/>
					</div>
					
				
			</div>
			
			
		 		
			<!--<div class="campo" style="width: 100%">
			    
			    <div class="campo" style="width: 65%; display: table-cell; text-align: left;">
			    	<label id="lbl357">Proveedor del producto: </label>
					<div style="width: 100%">
						<div id="proveedor_producto_div">
						<?= $proveedor_producto ?>
						</div>
					</div>
				</div> 
				
				<div class="campo" style="width: 35%; display: table-cell;">
				    <label id="lbl357">Ultimo costo de compra: </label>
					<input type="text" id="ultimo_costo" value=""/>
				</div> 
				
			</div>!-->
			
		</div>

		<div style="display: table-cell; width: 10%; vertical-align: middle; border-left: 1px solid #DDDDDD; padding-left: 25px; padding-right: 20px;">
				<div class="campo" align="center" 	style="display: table-cell; text-align: left; width: 10%;">
					<img id="preloader_filtros" src="<?php echo base_url();?>/images/preloader.gif">
				</div>
		</div>

	</div>

<!-- /////////////////////////////////////////////////////// !-->

	<div style="width: 80%; margin-top: 10px; border-bottom: 1px solid #DDDDDD; padding-bottom: 20px;">
						
			<img id="preloader_preview" src="<?php echo base_url();?>/images/preloader.gif">
			<div class="preview">
			 
			</div>
			
	</div>  

<!-- /////////////////////////////////////////////////////// !-->

	<div style="width: 100%; margin-top: 10px; border-bottom: 1px solid #DDDDDD; padding-bottom: 20px;">
				
			
			<div id="grid">
			</div>
			<img id="preloader_grid" src="<?php echo base_url();?>/images/preloader.gif">		
		
	</div>

<!-- /////////////////////////////////////////////////////// !-->

	<div class="listadofila" valign="middle" title="Nombre" style="margin-bottom: 50px; padding-top: 20px; width: 40%">
    
	    <label id="lbl357">Elaborado por: </label>
	    <font color="silver">*</font>
	    <br>
	    <input readonly  class="nminputtext" maxlength="100" type="text" id="elaborado_por" style="width: 100%" 
	    											<?php
	    											if (isset($_SESSION['elaborado_por_temporal']))
			    									{echo "value='".$_SESSION['elaborado_por_temporal']."'";}
													else if (isset($_SESSION['accelog_login']))
													{echo "value='".$_SESSION['accelog_login']."'";}
			    									?>
			    									/>

	</div>

<!-- /////////////////////////////////////////////////////// !-->

	<div style="text-align: left; width: 85%">
		Las fechas de entrega y costos unitarios pueden editarse de forma individual posteriormente.
	</div>

	<div style="text-align: right; width: 80%">
		<img id="preloader_generar" src="<?php echo base_url();?>/images/preloader.gif">
		<br>	
		<input id="send" type="button" value="Generar" onClick="generar()" class="nminputbutton_color2" />
		<div id="divdepurar"></div>
	</div>


</div>
</center>
</div>


</body>