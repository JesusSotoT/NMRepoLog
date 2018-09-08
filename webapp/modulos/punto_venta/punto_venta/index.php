<?php include("funcionesPv.php"); 


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
		
	$(function(){
		
	$("#preloader").hide();
	$("#search-producto").focus();
	/**/
	 $("#search-producto").keypress(function(event){
 	$("#preloader").show();
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13')
	{	
		 $.ajax({
						type: 'POST',
						url:'funcionesPv.php',
						dataType:'json',
						data:{funcion:"agregaraCaja",idArticulo:$("#search-producto").val(),almacen:$("#caja-almacen").val()},
						success: function(resp){  							
							
							if(!isNaN(resp[0]))
							{  
								$("#contenidocaja").html(resp[1]); 
								$("#hidensearch-producto").val("");
								$("#search-producto").val("");
								$("#search-producto").focus();
								$("#preloader").hide();
							}
							else{ alert(resp[0]); $("#preloader").hide(); }
					  }});//end ajax

	}
	
 
});
	  
	/**/	
		
		
		
	$(".float").numeric({allow:"."});
	$('#inicio_caja').hide();
	
  	showclock(); 
  
	$("#labelrfc").hide();
	$("#selectrfc").hide();
    
    $("#cliente-caja" ).autocomplete({delay: 0,source:"autocompleteClientes.php",
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
							else{ alert(resp[0]); $("#preloader").hide(); } 

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
	  {
			$('#inicio_caja').show();
			$('#inicio_caja').dialog('open');	  	
	  }//end if caja
	 
	 //si es simple//
	 <?php } ?>	
	  //end si es simple//	
	 
});//function()
		
	</script>

</head>
<body>	
    	<div id="header-caja">
    	<table border="0" width="100%"> 
		<tr>
		<td width="1%">Cliente:</td><td width="25%">
		<input type="hidden" id="hidencliente-caja">	
		<input type="text" id="cliente-caja" placeholder="P&uacute;blico en general" maxlength="45" /></td>
		<td width="1%">Art&iacute;culo:</td><td><input type="text" id="search-producto" placeholder="Ingrese código o descripción......"  />
		<input type="hidden" id="hidensearch-producto">	
		<input type="button" id="btn_buscar_producto" value="...." onclick="buscarProducto();" />	
		
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

<div id="caja-dialog"></div>
<div id="caja-dialog-confirmacion"></div>

<div id="inicio_caja" style="font-size: 12px;">
	<?php echo iniciocaja(); ?>
</div>
	
</body>	
</html>