<?php include("funcionesPv.php");
$exist_vS=0;
$q=mysql_query("select a.*, b.nombre from venta_suspendida a inner join comun_cliente b on b.id=a.s_cliente where a.borrado=0");
		if(mysql_num_rows($q)>0)
		{
			$exist_vS=1;
			$arr_vS= array();
			while($r=mysql_fetch_array($q))
			{
				$arr_vS[]=$r;
			}	 
		}
unset($q);
//mysql_close($conexion);
/*
var_dump($_SESSION['caja']);
var_dump($_SESSION['idCliente']);
*/

?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <title>Punto de venta</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="punto_venta.css" />
	<script type="text/javascript" src="punto_venta.js" ></script>
	<script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>
	

	<script>

	function nueva(r){
		$.ajax({
			async:false,
			type: 'POST',
			url:'funcionesPv.php',
			data:{funcion:"nuevaVenta"},
			success: function(resp){
				if(r!=1){
					window.location="../../modulos/punto_venta/index.php";
				}
				
		  	}
  		});
	}

	function elimina_suspendida(){
		id_suspendida=$("#s_cliente").val();
		$.ajax({
			async:false,
			type: 'POST',
			url:'funcionesPv.php',
			data:{funcion:"elimina_suspendida",id_suspendida:id_suspendida},
			success: function(resp){

				window.location="../../modulos/punto_venta/index.php";
				
		  	}
  		});
	}

	function carga_vSuspendida(){

		nueva(1);
		id_suspendida=$("#s_cliente").val();
		if(id_suspendida==0){
			alert('Selecciona una venta');
			return false;
		}
		$("#header-caja").css('display','none');
		$("#header-caja2").css('display','block');
		$("#contenidocaja").css('display','none');

		$.ajax({
			async:false,
			type: 'POST',
			url:'funcionesPv.php',
			dataType:'json',
			data:{funcion:"datosSuspendida",id_suspendida:id_suspendida},
			success: function(resp){

				console.log(resp);
				s_id=resp.datos[0].id;
				s_monto=resp.datos[0].s_monto;
				s_impuestos=resp.datos[0].s_impuestost;
				id_cliente=resp.datos[0].s_cliente;
				almacen=resp.datos[0].s_almacen;
				nombre=resp.datos[0].nombre;
				s_prod=resp.arr1;
				s_prod2=resp.arr2;
				s_cants=resp.s_cants;	
				s_descs=resp.s_descs;
				s_tdescs=resp.s_tdescs;

				s_documento=resp.datos[0].s_documento;
				s_idFact=resp.datos[0].s_idFact;
		  	}
  		});

		array_prod = s_prod.split(',');
		array_prod2 = s_prod2.split(',');
		tamano = array_prod.length;
		x=0;

		$.ajax({
			type: 'POST',
			url:'funcionesPv.php',
			data:{funcion:"cargaRfcs",idCliente:id_cliente},
			success: function(resp){  
				console.log(resp);
				$("#preloader").hide();
				$("#selectrfc").html(resp);
				$("#hidencliente-caja").val(id_cliente);
				$("#cliente-caja").val(nombre);

				$.each(array_prod, function( key, value ) {

				$.ajax({
					async:false,
					type: 'POST',
					url:'funcionesPv.php',
					dataType:'json',
					data:{funcion:"agregaraCaja",idArticulo:value,almacen:almacen},
					success: function(resp){  							
							if(!isNaN(resp[0]))
							{  
								$("#contenidocaja").html(resp[1]); 
								$("#hidensearch-producto").val("");
								$("#search-producto").val("");
								$("#search-producto").focus();
								$("#preloader").hide();
								if(s_cants[key]>1){
									$.ajax({
										async:false,
										type: 'POST',
										url:'funcionesPv.php',
										dataType:'json',
										data:{funcion:"cambiarcantidad",id:value,cantidad:s_cants[key],descuento:s_descs[key],tipodescuento:s_tdescs[key]},
										success: function(resp){  
											$("#contenidocaja").html(resp[1]); 
											$("#hidensearch-producto").val("");
											$("#search-producto").val("");
											$("#boton-pagar").removeAttr("disabled");
										}
									});//end ajax
								}		
							}
								else{ alert(resp[0]); $("#preloader").hide();
							}					
					  	}
			  		});
			  		
				});

			if(s_documento==2){
				$('#documento').val(2).trigger('change');
				$('#rfc').val(s_idFact).trigger('change');
			}
			$("#header-caja2").css('display','none');
			$("#header-caja").css('display','block');
			$("#contenidocaja").css('display','block');
			$("#susp-hids").append('<input id="sp_s_id" type="hidden" value="'+s_id+'">');
			$("#susp-hids").append('<input id="sp_name" type="hidden" value="'+nombre+'">');
			$("#ssnueva").css('visibility','visible');

			pagar(s_monto,s_impuestos,0,array_prod2,s_id,nombre);
		  	}
		 });//end ajax
	}
		
	$(function(){
		
	$("#preloader").hide();
	$("#search-producto").focus();
	/**/
	 $("#search-producto").keypress(function(event){
 	$("#preloader").show();
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13')
	{	
		$('#codigo').val('');
		 $.ajax({
						type: 'POST',
						url:'funcionesPv.php',
						dataType:'json',
						data:{funcion:"agregaraCaja",idArticulo:$("#search-producto").val(),almacen:$("#caja-almacen").val(),susp:0},
						success: function(resp){  							
							if(!isNaN(resp[0]))
							{  
								$("#contenidocaja").html(resp[1]); 
								$("#hidensearch-producto").val("");
								$("#search-producto").val("");
								$("#search-producto").focus();
								$("#preloader").hide();


							if(resp[3]!= '')
								{
									$('#codigo').val(resp[3]);
								}

							//Si la caja es simple se muestra la ventana de propinas
							if(<?php echo propina()?> == 1)
							{
								if(resp[4] != '' && resp[5] != '')
								{
									$('#txtPropina').val(resp[5]);
									$('#modalPropina').dialog({ buttons: 
				                        [ 
				                        { 
				                            text: "Aceptar",
				                            class:'btn btn-success col-sm-5',
				                            click: function() 
				                            { 

				                            	 $.ajax({
													type: 'POST',
													url:'funcionesPv.php',
													dataType:'json',
													data:{funcion:"agregarPropina",idArticulo:resp[4],cantidad:$('#txtPropina').val(),almacen:$("#caja-almacen").val()},
													success: function(resp){  							
														//window.location.reload();
														if(!isNaN(resp[0]))
															{  
																$("#contenidocaja").html(resp[1]); 
																$("#hidensearch-producto").val("");
																$("#search-producto").val("");
																$("#search-producto").focus();
																$("#preloader").hide();
															}
															else{ alert(resp[0]); $("#preloader").hide(); $("#search-producto").val("");}
												  }});//end ajax

				                                $( this ).dialog( "close" ); 
				                            } 
				                        }, 
				                        { 
				                            text: "Cancelar", 
				                            class:'btn btn-danger col-sm-5 pull-right',
				                            click: function() 
				                            { 
				                                $( this ).dialog( "close" ); 
				                            } 
				                        } 
				                        ]
				                    });
								}
							}
							}
							else{ alert(resp[0]); $("#preloader").hide(); $("#search-producto").val("");}
					  }});//end ajax

	}
	
 
});
	  
	/**/	
		
		
		
	$(".float").numeric({allow:"."});
	$('#inicio_caja').hide();
	
  	showclock(); 
  
	$("#labelrfc").hide();
	$("#selectrfc").hide();
    
    $("#cliente-caja" ).autocomplete({
    delay: 0,
    source:"autocompleteClientes.php",
	search: function( event, ui ){  $("#preloader").show(); },
	select: function( event, ui ){
	  
	  $("#hidencliente-caja").val(ui.item.id);
	  
	  
	   $.ajax({
						type: 'POST',
						url:'funcionesPv.php',
						data:{funcion:"cargaRfcs",idCliente:ui.item.id},
						success: function(resp){  
							 $("#preloader").hide();
							$("#selectrfc").html(resp); 
							
					  }});//end ajax
	  
	  	  
	  }});
	  
	  
	   $("#search-producto" ).autocomplete({delay: 0,source:"autocompleteProductos.php",
	  
	  search: function( event, ui ){  $("#preloader").show(); },
	  
	  select: function( event, ui ){
	 
	 /*
	 $("#preloader").show();
	 $.ajax({
		type: 'POST',
		url:'funcionesPv.php',		
		data:{funcion:"checarExistencia",idArticulo:ui.item.id,cantidad:1,almacen: $("#caja-almacen").val()},
		success: function(resp){ $("#preloader").hide();
			if(!isNaN(resp))
			{  
					*/
					$("#preloader").show();
					 $("#hidensearch-producto").val(ui.item.id);  	
					  $.ajax({
						type: 'POST',
						url:'funcionesPv.php',
						dataType:'json',
						data:{funcion:"agregaraCaja",idArticulo:ui.item.id,almacen: $("#caja-almacen").val()},
						success: function(resp){ 
							
							if(!isNaN(resp[0]))
							{  
								$("#contenidocaja").html(resp[1]); 
								$("#hidensearch-producto").val("");
								$("#search-producto").val("");
								$("#preloader").hide();
								$("#search-producto").focus();
							}
							else{ alert(resp[0]); $("#preloader").hide(); $("#search-producto").val("");} 

					  }});//end ajax
				
			//}else{ $("#search-producto").val(""); $("#hidensearch-producto").val(""); alert(resp); $("#preloader").hide(); }
		//}});//end
		

	  }});
	  
	  
	  $('#inicio_caja').dialog({
				position: ['top',0],
				modal: true,
				minWidth: 400,
				draggable: true,
				resizable: false,
				autoOpen:false,
				title:"Inicio de caja",
				closeOnEscape: false,
   				open: function(event, ui)
   				{ 
   					$(".ui-dialog-titlebar-close").hide(); 
			 	},
			   buttons:
				[
				{
					text:'Iniciar',click: function()
					{ 
							
							if(  $("#iniciocaja").val()=="" ){ alert("Debes indicar con cuanto inicia caja, puede ser 0"); return false;}	
							if(  $("#sucursal").val()=="" ){ alert("Debes seleccionar que sucursal estas operando"); return false;}

							$.ajax({
								type: 'POST',
								url:'funcionesPv.php',
								data:{funcion:"Iniciarcaja",sucursal:$("#sucursal").val(),monto:$("#iniciocaja").val()},
								success: function(resp){  
									$("#dalmacen").html(resp); 
									$('#inicio_caja').dialog("close");
							}});//end ajax			
					}
			   }

			   	]}).height('auto');	//end caja-dialog		
	  
	  //si es simple//
	  <?php if(simple()){ ?>
	  //end si es simple//		
	  
		var caja='<?php echo verificainicioCaja();?>';
		if(caja==1)
		$('#inicio_caja').show().dialog('open');	  	
	//end if caja
	 
	 //si es simple//
	 <?php } ?>	
	  //end si es simple//	
	  
	  
	<?php if( isset($_SESSION['idCliente']) && is_numeric($_SESSION['idCliente']) ){ ?>	
	
	  var cliente='<?php echo $_SESSION['idCliente'];?>';
	
							$.ajax({
								type: 'POST',
								url:'funcionesPv.php',
								dataType:'json',
								data:{funcion:"cargacliente",cliente:cliente},
								success: function(resp){  
									
									$("#hidencliente-caja").val(resp[0]);
									$("#cliente-caja").val(resp[1]);
									$("#selectrfc").html(resp[2]);
									
									$("#labelrfc").show();
									$("#selectrfc").show();
									
									
							}});//end ajax	
	  	
	  	
	<?php } ?>
	  
	 
});//function()
		function corte(){
		  	$.post("reportes/corte.php",{},
	function(respues) {
       if(respues=="si"){
       	
	   confirmar=confirm("Esta seguro de realizar el corte de caja?"); 
   			 if (confirmar){
    	$('#contenido').css('display','none');
    			//alert('si iniciara');
    			$('#cortecaja').load('boxCut/views/boxCut.php');
    		}else{
    			//alert('no inicio');
    		}		
	}else{
			alert('No puede realizar el corte de caja, no tiene ventas realizadas');
		}
   	});	
		  	
		}
		
		
	</script>

</head>

<body>	
	<!--  <input type="button" id="corte" value="Corte de Caja" onclick="corte()"/>
<iframe id="cortecaja" style="display:none;width:98%"></iframe> -->
<div id="contenido">
	<div id="laobs" style="font-size:12px;display:none;">
		<textarea id="txtlaobs" style="width:570px;height:100px"></textarea>
	</div>
	<?php if($exist_vS==1){ ?>
		
	<div id="header-caja1">
		<div style="margin:5px;">
			Ventas suspendidas: 
			<select id="s_cliente">
				<option value="0" selected>Selecciona</option>
				<?php  foreach ($arr_vS as $row){ ?>
					<option value="<?php echo $row['id']; ?>"><?php echo $row['identi']; ?></option>
				<?php  }  ?>
			</select>
		</div>
		<div style="margin:5px 0 5px 10px;">
			<input type="button" value="Cargar" onclick="carga_vSuspendida();"> <input id="sselimina" type="button" value="Eliminar" onclick="elimina_suspendida();"> <input style="visibility:hidden;" id="ssnueva"  type="button" value="Realizar nueva venta" onclick="nueva();">
		</div>
	</div>
	<br>
	<?php } ?>
	<div id="header-caja2" style="display:none;">
		<div style="margin:5px;">
			Cargando datos, favor de esperar...
		</div>
	</div>

    	<div id="header-caja">
    	<table border="0" width="100%"> 
		<tr>
		<td width="1%">Cliente:</td><td width="25%">
		<input type="hidden" id="hidencliente-caja">	
		<input type="text" id="cliente-caja" placeholder="P&uacute;blico en general" maxlength="45" /></td>
		<td width="1%">Art&iacute;culo:</td><td><input type="text" id="search-producto" placeholder="Ingrese código o descripción......"  />
		<input type="hidden" id="hidensearch-producto">	
		<input type="button" id="btn_buscar_producto" value="...." onclick="buscarProducto();" />	
		

		<input type="hidden"  id="codigo" value="">
		</td>
		<td width="5%"> <img src="img/preloader.gif" id="preloader"> </td>
		<td id="num_caja">Caja</td>
		</tr>
		<tr>
		<td width="10">Documento:</td><td><select id="documento" onchange="tipoDocumento(this.value);">
		
		<option value="1">Ticket</option>
		<option value="2">Factura</option>
			
		</select></td>
	<td width="10"><span id="labelrfc">RFC:</span></td><td><span id="selectrfc">
		<select><option value=""></option></select></span></td>
		
		<td width="20%" colspan="2" ><div id="fecha-caja"><?php echo fechaActual(); ?>
			<span id="liveclock" ></span></div>
		</td>
		
		</tr>
		
		</table>
		</div>
    	
    	<div id="contenidocaja">
    	<?php 
    	$caja=json_decode(imprimecaja());
    	echo ($caja[1]);
    	?>
    	</div>
    	
    	</div>

<div id="caja-dialog"></div>
<div id="caja-dialog-confirmacion"></div>
<div id='susp-hids'></div>
<div id="inicio_caja" style="font-size: 12px;"><?php echo iniciocaja(); ?></div>

<div id="modalPropina" style="display:none;">
	<div>
		¿Deseas dar Propina?
		Se recomienda el 10% del total de tu compra
	</div>
	<input type="text" id="txtPropina" value="">
</div>
	
</body>	
</html>