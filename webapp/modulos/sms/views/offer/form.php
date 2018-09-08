<?php
include("../../../../netwarelog/webconfig.php");
include_once('../../funcionesBD/conexion.php');
    $consult = new Consult;
    $conection = $consult -> conection($servidor,$usuariobd,$clavebd,$bd);

    $result=$consult->grupos($conection);
    if($result->num_rows>=1){  
      while($row=$result->fetch_array(MYSQLI_ASSOC)){ 
        $grupos[]=$row;
      }
    }else{
        $grupos=0;
    }

if(!isset($_SESSION))
     session_start();
error_reporting(E_ALL);

?>


<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../css/popup.css" 				   		   title="estilo" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../../sms/js/jquery.alphanumeric.js"></script>
<script src="../../../sms/js/ui.datepicker-es-MX.js"></script>

<script type="text/javascript" src="../../../sms/js/offer.js"></script>
<script type="text/javasctipt" src="../../../sms/js/paginaciongrid.js"></script>
	 
<!--Script del autocompletado del campo de productos !-->
<script>
	

	$.fn.disable = function() {
    return this.each(function() {          
      if (typeof this.disabled != "undefined") {
        $(this).data('jquery.disabled', this.disabled);

	        this.disabled = true;
	      }
	    });
	};
	
	$.fn.enable = function() {
	    return this.each(function() {
	      if (typeof this.disabled != "undefined") {
	        this.disabled = $(this).data('jquery.disabled');
	      }
	    });
	};	
		

$(document).ready(function() {
	
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	//$(".datepicker").datepicker();
	
	$("#cantidad").numeric();
	$("#precio").numeric({allow:"."});
	$("#cantidad_existente").numeric();
	
	 $("#fecha_inicio").datepicker({
	 	dateFormat: 'yy-mm-dd',
        minDate: 0,
        maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#fecha_fin").datepicker("option","minDate", selected)
        }
    });
    
    $("#fecha_fin").datepicker({ 
    	dateFormat: 'yy-mm-dd',
        minDate: 0,
        maxDate:"+60D",
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#fecha_inicio").datepicker("option","maxDate", selected)
        }
    });

    $('#close').click(function(){
        $('#popup').fadeOut('slow');
        $('#formulario_ofertas').css('opacity', '1');
        $('#formulario_ofertas').enable();
		return false;
    });
    
    $("#throwback").click(function(){
    	window.location="../offer/listado_ofertas.php";
    });
    
    $("#preloader_municipio").hide();
    
});
</script>
<script>
	function verificaDisponibilidad(option){
		var id_producto= $('#producto').val();
		var cantidad= $('#cantidad').val();
		
		if(option!=2){
			if(id_producto==''){
				alert("Selecciona un producto");
				return 0;
			}
			if(cantidad==''){
				alert("Ingresa una cantidad");
				return 0;
			}
		}
		a='';
		$('#vdpre').css('display','block');
		$.ajax({
			async:false,
			url:'../../../../modulos/sms/smsAjax.php',
			type: 'POST',
			data: {funcion:"verificaDisponibilidad",id_producto:id_producto,cantidad:cantidad},
			success: function(resp){
				if(option!=2){	
					if(resp==0){
						alert('No hay suficientes producto en inventario.');
						$('#cantidad_existente').val('');
					}else{
						$('#cantidad_existente').val(resp);
					}
				}else{
					if(resp==0){
						//alert('No hay suficientes producto en inventario.');
						$('#cantidad_existente').val('');
					}else{
						puso = $('#cantidad_existente').val();
						if(puso==''){puso=0;}
						if(puso*1>resp*1){
							a=1;
						}else{
							a='';
						}
					}
				}
			}
		});
		$('#vdpre').css('display','none');
		return a;
	}
</script>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

<body>
<div id="formulario_ofertas">
	
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
	<div style="width: 80%;">
				<div style="width: 90%; text-align: right"><input id='throwback' type="button" value="Regresar al listado"></div>
	<div style="width: 80%; text-align: left;">
	<div class="listadofila" title="Fechas de oferta" style="text-align: left; max-width: 450px;">
			<fieldset>
			<legend>Vigencia de la oferta</legend>
			<div class="campo" style="display: table-cell; vertical-align: middle; text-align: left;">
				
			    <div class="campo" style="display: table-cell; padding-right: 10px;">
				    <label>Fecha del inicio: </label>
				    <font color="silver">*</font>
				    <br>
				    <input readonly id="fecha_inicio" type="text" style="width: 200px;"
				    									<?php 
				    									if (isset($_SESSION['fecha_pedido_temporal']))
				    									{echo "value='".$_SESSION['fecha_pedido_temporal']."'";}
														else
														{
														 $hoy = getdate();
														 echo "value='".$hoy['year']."-".$hoy['mon']."-".$hoy['mday']."'";	 
														}
														?> />
	
			    </div>
			    <div class="campo" style="display: table-cell; padding-left: 10px;  width: 200px;"
				    <label>Fecha de expiracion: </label>
				    <font color="silver">*</font>
				    <br>
				    <input readonly id="fecha_fin" type="text" style="width: 100%;"
				    									<?php 
				    									if (isset($_SESSION['fecha_entrega_temporal']))
				    									{echo "value='".$_SESSION['fecha_entrega_temporal']."'";} ?> />
			    </div>
		    </div>
		    </fieldset>
		</div>
		
	</div>
	</div>
	</center>
	
		
	<center>
	<div class="campos" style='width: 90%;'>		
	
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

		<center>
		<div class="listadofila" title="Oferta" style="padding-top: 20px; padding-right: 20px; padding-left: 20px; width: 80%; text-align: left;">	
			
			<div style="display: table-cell; vertical-align: middle; width: 10%; text-align: left;">
				<div>
				    <label id="lbl357">Cantidad: </label>
				    <font color="silver">*</font>
				    <br>
				    <input class="positive" type="text" id="cantidad" style="width: 100%" maxlength="8"/>
			    </div>
			</div>
		    
		    <div style="display: table-cell; vertical-align: middle; width: 50%; min-width: 50%; text-align: left; padding: 0 25px 0 25px;">
				
				    <label id="lbl357">Producto a ofertar: </label>
				    <font color="silver">*</font>
					<div style="width: 100%;">
							<div id="productos">
							</div>				
								
					</div>
			</div>
			
			<div style="display: table-cell; vertical-align: middle; width: 20%; min-width: 20%; text-align: left; padding: 0 25px 0 0;">
				
				    <label id="lbl357">Unidad: </label>
				    <font color="silver">*</font>
					<div style="width: 100%;">
							<div id="unidades">
							</div>					
					</div>
			</div>
			
			<div style="display: table-cell; vertical-align: middle; width: 10%; text-align: right;">
				    <label id="lbl357">Precio: </label>
					<font color="silver">*</font>
				    <br>
					<input class="positive" type="text" id="precio" style="width: 100%" maxlength="8"/>
			</div>
			
		</div>
		<!--................................-->
		<div class="listadofila" title="Oferta" style="border-bottom: 1px solid #DDDDDD; width: 80%; padding-top: 10px; padding-bottom: 20px; padding-right: 20px; padding-left: 20px; text-align: left;">		
			<div style="display: table-cell; vertical-align: middle; text-align: left;">
				    <label>Numero de Ofertas: </label>
				    <font color="silver">*</font>
				    <br>
				    <input class="positive" maxlength="8" type="text" id="cantidad_existente" style="float:left;" />	
				    &nbsp;<input type="button" style="200px;float:left;margin-left:5px;" value="Verificar maxima disponibilidad" onclick="verificaDisponibilidad();" />
				    <div id="vdpre" style="float:left; width:20px;margin-left:5px;display:none;"><img src="../../images/preloader.gif" width="17" height="17"></div>	
			</div>	
		</div>
		
		</center>
		
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

		<div class="listadofila" title="Pedido" style="border-bottom: 1px solid #DDDDDD; padding: 20px; width: 80%; text-align: left;">
		    <center>
		    <div style="display: table-cell; vertical-align: middle; min-width: 100%; width: 100%;">
				<p>
					
			    <div class="campo" style="display: table-cell; width: 100%; min-width: 100%;" >
				    <b><label id="lbl357">Seleccione el grupo al que le desea enviar esta oferta</label></b>
				    <p>
				   	<!-- Filtros -->
				   	<div style="">
						<select id="grupofil">
							<option value="0">Todos los grupos</option>
							<?php 
					        if($grupos!=0){
					          foreach ($grupos as $key => $value) { ?>
					            <option value="<?php echo $value['id']; ?>"><?php echo $value['grupo']; ?></option>
					          <?php } ?>
					        <?php } ?>
						</select>
						<input id="btnagregargp" type="button" onclick="filtraClientes();" value="Agregar grupo">
				   	</div>
				    <!--
				    <div id="filtros" style='display: table; width: 80%;'>
				    	
				    		<fieldset>
							<legend>Filtros</legend>
					
							<div style='display: table; width: 100%;'>
								<div style='display: table-cell; width: 25%; text-align: left'> 
									<label>Filtro por giro</label><br> 
									<div id='filtro_giro_div' ></div>
								</div>
								
								<div style='display: table-cell; width: 25%; padding-left: 10px; padding-right: 10px; text-align: left'> 
									<label>Filtro por rubro</label><br> 
									<div id='filtro_rubro_div'></div>
								</div>
								
								<div style='display: table-cell; width: 25%; padding-right: 10px; text-align: left'> 
									<label>Filtro por estado</label><br> 
									<div id='filtro_estado_div'></div>
								</div>
								
								<div style='display: table-cell; width: 25%; text-align: left'> 
									<img class="preloader" id="preloader_municipio" src="../../images/preloader.gif">
									<label>Filtro por municipio</label><br>
									<div id='filtro_municipio_div'></div> 
								</div>
							</div>
								
							<div style='display: table; width: 100%; padding-top: 10px;'>
								<div style='display: table-cell; text-align: left; width: 20%'> 
									<label>Filtro por nombre ó c.p.:</label>
								</div>
								
								<div style='display: table-cell; padding-left: 10px; text-align: left; width: 80%'>
									<input type="text" style="border: 1px solid #006efe;width: 100%;" id="busca_cliente">
								</div>
				    		</div>
				    		
				    		<div style="display: table; width: 100%; padding-top: 10px; text-align: right">
				    			<input id="filtrar_clientes_btn" type="button" onclick="filtraClientes();" value="Buscar">
				   			</div>
								
							</fieldset>	
					</div>
					-->
					
					<!-- Fin de filros -->
					
				    
					<div style="width: 100%">
					
							<p>
							<div id="clientes">
							</div>
					</div>
				</div>
			</div>
			</center>
	
		</div>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

		<div class="listadofila" valign="middle" title="Nombre" style="margin-bottom: 50px; padding-top: 20px; width: 50%">
		    
			    <label id="lbl357">Elaborado por: </label>
			    <font color="silver">*</font>
			    <br>
			    <input maxlength="100" type="text" id="elaborado_por" style="width: 100%" 
			    											<?php
			    											if (isset($_SESSION['elaborado_por_temporal']))
					    									{echo "value='".$_SESSION['elaborado_por_temporal']."'";}
															else if (isset($_SESSION['accelog_login']))
															{echo "value='".$_SESSION['accelog_login']."'";}
					    									?>
					    									/>
				<label id="lbl357">Sucursal que expide: </label>
				<font color="silver">*</font>
				<br>
				<div style="width: 50%;">
							<div id="sucursales">
							</div>					
				</div>
		</div>

	<div style="text-align: right; width: 80%">
		<input id="send" type="button" value="Generar..." onClick="generar()" />
	</div>

	</div>
	</center>


</div>
</body>

<div id="popup" style="display: none;">
    <div class="content-popup">
        <div class="close"><a href="#" id="close"><img src="../../images/close.gif"/></a></div>
        <div id='popup_generar'></div>
    </div>
</div>