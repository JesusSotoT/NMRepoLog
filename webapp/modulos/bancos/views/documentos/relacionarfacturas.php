<script language='javascript'>
$(document).ready(function(){
	$("#buscando_text").hide()
});
$(function(){
	//EXTENDIENDO LA FUNCION CONTAINS PARA HACERLO CASE-INSENSITIVE
	  $.extend($.expr[":"], {
	"containsIN": function(elem, i, match, array) {
	return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
	});
	//-------------------------------------------------------------
	// INICIA GENERACION DE BUSQUEDA
			$("#busqueda2").bind("keyup", function(evt){
				//console.log($(this).val().trim());
				if(evt.type == 'keyup')
				{
					$(".listado tr:containsIN('"+$(this).val().trim()+"')").css('display','table-row');
					$(".listado tr:not(:containsIN('"+$(this).val().trim()+"'))").css('display','none');
					$(".listado tr:containsIN('*1_-{}*')").css('display','table-row');
					if($(this).val().trim() === '')
					{
						$(".listado tr").css('display','table-row');
					}
				}
			});
		// TERMINA GENERACION DE BUSQUEDA
	//listaTemporales();
	$("#subidas").show();
	$("#buscadas").hide();
	$("#buscar_facturas").show()
	$("#subir_facturas").hide()

	$("#subir_facturas").click(function(event) {
		$("#subidas").show();
		$("#buscadas").hide();
		$("#buscar_facturas").show()
		$("#subir_facturas").hide()
	});

	$("#buscar_facturas").click(function(event) {
		$("#subidas").hide();
		$("#buscadas").show();
		$("#subir_facturas").show()
		$("#buscar_facturas").hide()
	});

	$("#asignar_facturas").click(function(event) {
		var copiar = [];
		var borrar = [];
		var lista = '';
		var aux='';
		var sigue = 1;
		for(var i = 0 ; i<=$(".borrar").length; i++)
		{
			if($("#borrar-"+i).is(':checked'))
			{
				//alert($("#borrar-"+i).val())
				borrar.push($("#borrar-"+i).val())
				if(parseInt($("#borrar-"+i).attr('polizas')))
				{
					aux = $("#borrar-"+i).val();
					aux = aux.split('/')
					lista += '\n'+aux[3]
				}
			}
		}
		if(lista != '')
			if(!confirm('Las siguientes facturas ya estan relacionadas.'+lista+'\n\nEsta seguro que quiere asignarlas?'))
				sigue = 0;
			
		
			$.post("ajax.php?c=Cheques&f=copiaFacturaBorra",
		 		{
					idDoc: $('#id').val(),
					idDoctemp: $('#idtemporal').val(),
					Borrar: borrar
				},
				function()
				{
					
						$.post("ajax.php?c=Cheques&f=facturas_dialog",
				 		{
							idDoc: $('#id').val(),
							idDoctemp: $('#idtemporal').val()
						},
						function(data)
						{
						
						 	$('#listaFacturas').html(data)
						 	actualizaListaFac();
						
						});	
						actualizaListaFac();
						$("#subidas").show();
						$("#buscadas").hide();
						$("#buscar_facturas").show()
						$("#subir_facturas").hide()
						listaTemporales();
					
				});
		
		
		

	});

	if(parseInt($("#todas_facturas").val()))
	{
		$("#tipo_busqueda").val(1)
		$("#busqueda").attr('type','hidden').val('*').trigger("change")
		$("#tipo_busqueda,#titulo_tipo_busqueda").hide();
		$("#busqueda2").show()
	}
	else
	{
		$("#tipo_busqueda").show();
		$("#busqueda,#titulo_tipo_busqueda").attr('type','text')
		$("#busqueda2").hide()
	}


});

	function buttonclick(v)
	{
		$("."+v).click();
	}

	function listaTemporales()
	{
		$("#buscando_text").show()
		$.post("ajax.php?c=Cheques&f=listaAlmacenBD",
		 	{
		 		folio_uuid:$("#busqueda").val(),
		 		tipo_busqueda:$("#tipo_busqueda").val()
			},
			function(callback)
			{
				//console.log(callback);
				if(callback)
				{
					$("#buscando_text").hide()
					$(".listado").html(callback);
				}
			});
	}
 	function eliminar(archivo)
 	{
 		var separa  = archivo.split("/");
 		var confirmacion = confirm("Esta seguro de eliminar este archivo: \n"+separa[6]);
 		if(confirmacion)
 		{	$("#verif").show();
 			$.post("ajax.php?c=Cheques&f=EliminarArchivo",
		 	{
				Archivo: archivo,
				idDoc:$("#id").val(),
				idDoctemp: $('#idtemporal').val(),
			},
			function()
			{
			 	//location.reload();
				//alert('Eliminado')
				$.post("ajax.php?c=Cheques&f=borraFacturaForm",
		 		{
					idDoc: $('#id').val(),
					Archivo: archivo
				},
				function()
				{
					$.post("ajax.php?c=Cheques&f=facturas_dialog",
			 		{
						idDoc: $('#id').val(),
						idDoctemp: $('#idtemporal').val()
					},
					function(data)
					{
					
					 	$('#listaFacturas').html(data)
					 	$("#verif").hide();
					 	actualizaListaFac();
					
					});	 				
				});
			});
 		}
 	}

 	function tienePolizas(UUID,Id)
 	{
 		if(!parseInt($('#copiar-'+Id).attr('polizas')) || !parseInt($('#borrar-'+Id).attr('polizas')))
 		{
			$.post("../cont/ajax.php?c=Reports&f=tienePolizas",
			 {
				UUID: UUID
			},
			function(data)
			{
				console.log("tiene polizas: "+data)
				$('#copiar-'+Id).attr('polizas',data)
				$('#borrar-'+Id).attr('polizas',data)
			});
 		}
 	}
</script>
<style>
.nmcatalogbusquedainputtext{
  background-color: #fff;
  border-color: #ddd;
}
.btn-default {
  background: #f5f5f5 !important;
}
.btn-default:hover {
  background: #e5e5e5 !important;
}
.modal-lg {
  transition: width 300ms !important;
}
@media (min-width: 1200px) {
  .modal-lg {
    width: 95%;
  }
}
@media (min-width: 992px) {
  #subir_facturas {

  }
}
#lista td
{
	width:146px;
	text-align: center;
	border:1px solid #BDBDBD;
}

#buscar
{
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-o-border-radius: 4px;
border-radius: 4px;
}
#loading
{
	background-color:#BDBDBD;
	color:white;
	text-align:center;
	font-weight:bold;
}
</style>
<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
<?php
if($datos['id']){
	$datosid=$datos['id'];
}else{
	$datosid=0;
}
?>
<div id='Facturas' class="modal fade" tabindex="-1" role="dialog" >
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title">Lista de Facturas</h4>
      		</div>
      		<div class="modal-body">
        		<div class="row">
        			<div class="col-md-4">
        				<button id='subir_facturas' class="btn btn-default btn-block">Regresar</button>
        				<button id='buscar_facturas' class="btn btn-default btn-block">Almacén Digital</button>
        			</div>
            </div>
        		<div class="row" id='subidas'>
        			<span id='verif' style='color:green;display:none;'>Verificando...</span>
        			<div class="col-md-12">
        				<form name='subexml' id='subexml' action='' method='post' enctype='multipart/form-data'>
	        				<div class="row">
			        			<div class="col-md-4">
			        				<input type='file' name='factura[]' id='factura' multiple>
			        				<input type='hidden' name='idDocfact' id='idDocfact' value='<?php echo $datosid; ?>'><input type='hidden' name='idDocfactemp' id='idDocfactemp' value='<?php echo $idtemporal; ?>'>
			        			</div>
			        			<div class="col-md-4">
			        			</div>
			        			<div class="col-md-4">
			        				<button type="submit" id='buttonFactura' class="btn btn-default btn-block">Asociar Facturas</button>
			        			</div>
			        		</div>
			        		<div class="row">
								<div class="col-md-12">
									<div class="table-responsive" style="overflow:scroll; height:300px;">
										<table class="table table-striped" id='listaFacturas'>
										</table>
									</div>
								</div>
							</div>
						</form>
	        		</div>
        		</div>
        		<div class="row" id='buscadas'>
        			<div class="col-md-12">
        				<div class="row">
		        			<div class="col-md-3">
		        				<input type='text' class="form-control" id='busqueda' name='busqueda' placeholder='Buscar Factura' onchange='listaTemporales()'>
		        				<input type='text' class="form-control" id='busqueda2' name='busqueda2' placeholder='Buscar Factura'>
		        			</div>
		        			<div class="col-md-2">
		        			<input type='hidden' id='todas_facturas' value='<?php echo $todas_facturas; ?>'>
		        			<select id='tipo_busqueda' class='form-control' onchange='listaTemporales()'>
		        					<option value='1'>Por Folio Fiscal (UUID)</option>
		        					<option value='0'>Por Folio</option>
		        					<option value='2'>Por Razon Social</option>
		        				</select>
		        				<!--<a id="loadalmacen" href="#" title="Actualizar Almacen"><i id="update1" class="fa fa-refresh "/></i>Actualizar Almacen</a>--><span style='font-size:10px;' id='titulo_tipo_busqueda'>Escribe el Folio Fiscal (UUID) o Folio para buscar la factura.</span>
		        			</div>
		        			<div class="col-md-4">
		        				<button class="btn btn-default btn-block" id='asignar_facturas' title='alt + k'>Asignar a poliza</button>
		        			</div>
		        		</div>
                <br>
		        		<div class="row">
							<div class="col-md-12">
								<div class="table-responsive"  style="overflow:scroll; height:300px;">
								<span id='buscando_text' style='font-size:12px;color:blue;'><i>Buscando facturas...</i></span>
									<table class="table listado table-striped">
									</table>
								</div>
							</div>
						</div>
	        		</div>
        		</div>
	      	</div>
      		<div class="modal-footer">
      			<div class="row">
      				<div class="col-md-10">
		        	</div>
		        	<div class="col-md-2">
		        		<button onclick="$('#Facturas').modal('hide');" type="button" class="btn btn-default btn-block btn-sm">Cerrar</button>
		        	</div>
      			</div>
      		</div>
    	</div>
  	</div>
</div>
<script>
$(document).ready(function(){
	
	$( "#loadalmacen" ).on( "click", function( e ) {
       $("#update1").addClass("fa-spin");
     	$.post("ajax.php?c=Cheques&f=listaAlmacen",
		 	{
		
			},
			function(callback)
			{
				$(".listado").html(callback);
				$("#update1").removeClass("fa-spin");
			});
      
    }); 
});
</script>
