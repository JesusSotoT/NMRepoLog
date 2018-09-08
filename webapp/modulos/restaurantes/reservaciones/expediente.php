<?php include_once("../../netwarelog/catalog/conexionbd.php"); ?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<!-- ////////////////////	- -					CSS					- -	 //////////////////// -->
<!-- JQuery -->
	<link href="jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet">

<!-- Datetimepicker -->
	<link href='datetimepicker/datetimepicker.css' rel='stylesheet' />

<!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css"> 

<!--NETWARLOG CSS-->
	<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" />
	
<!-- fancyBox -->
	<link rel="stylesheet" type="text/css" href="fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

<!-- form -->
	<link href='form.css' rel='stylesheet' />
	
<!-- ////////////////////	- -					FIN CSS					- -	 //////////////////// -->


<!-- ////////////////////	- -					JS					- -	 //////////////////// -->

<!-- JQuery-ui -->
	<script src="jquery-ui/js/jquery-1.9.1.js"></script>
	<script src="jquery-ui/js/jquery-ui-1.10.3.custom.js"></script>
	
<!-- Datetimepicker -->
	<script src='datetimepicker/datetimepicker.js'></script>
	<script src='datetimepicker/jquery-ui-timepicker-es.js'></script>
	<script src='datetimepicker/ui.datepicker-es.js'></script>
	
<!-- Bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	
<!-- fancyBox -->
	<script type="text/javascript" src="fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
	<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	
<!-- ////////////////////	- -					JS					- -	 //////////////////// -->


<?php include('../../netwarelog/design/css.php');?>

<script>
	$(function() {
    	$( ".accordion" ).accordion();
    	
  		$('.fancybox').fancybox({
			openEffect  : 'elastic',
			closeEffect : 'elastic',
		});
		
		$( ".subaccordion" ).accordion({collapsible: true, active: false });
	});
  
	function addgrup(){
		$('.dialogoConfirmarEliminar').dialog({
			modal: true,
			minWidth: 390,
			draggable: true,
			resizable: false,
			title:"Agregar grupo",
			open: function(){
				$(this).empty().append('Nombre<input type="text" id="nombregrupo">');
			},
			
			buttons:[{
				text:'Guardar',click: function(){
					$.ajax({
						url:'form.php',
						type: 'POST',
						data: {funcion:'guardagrupo',nombre:$("#nombregrupo").val()},
						success: function(cbores){
							$("#loadgrupos").html(cbores);
							$('.dialogoConfirmarEliminar').dialog('close');
						}
					});
				}
			},{
				text: 'Salir',
				click: function(){
					$(this).dialog('close');
				}
			}]
		}).height('auto');			
	}

///
function deletegrup()
{
	if( $("#grupo").val()!="" ){
$('.dialogoConfirmarEliminar').dialog({
		modal: true,
		minWidth: 390,
		draggable: true,
		resizable: false,
		title:"Eliminar grupo",
		open: function(){
		$(this).empty().append('¿Estas seguro que deseas eliminar el grupo?');},
		buttons:[{text:'Eliminar',click: function(){ 
					
					 $.ajax({
					url:'form.php',
					type: 'POST',
					data: {funcion:'eliminargrupo',id:$("#grupo").val()},
					success: function(cbores){
						$("#loadgrupos").html(cbores);
						$('.dialogoConfirmarEliminar').dialog('close');

				}});
				
			}},{text: 'Salir',click: function(){$(this).dialog('close');}}]}).height('auto');			
	}
}

  
  function Agendarcita()
  {
   if($("#cliente").val()=="")
	  {
	  	alert("Debes seleccionar un cliente"); return false;
	  }
	  
	  /**/
	  $.ajax({
		type: 'POST',
		url:'form.php',
		data: {funcion:"form",cliente:$("#cliente").val()},
		success: function(resp){   

$('.opciones-evento').dialog({
		modal: true,
		draggable: true,
		resizable: true,
		width:390,
		height:400,
		open: function(){$(this).empty().append(resp);
		
	
	$("#fin").datetimepicker({minDate: new Date(),dateFormat: "yy-mm-dd",timeFormat: "HH:mm tt"});
		$("#inicio").datetimepicker({minDate: new Date(),dateFormat:"yy-mm-dd",timeFormat: "HH:mm tt",
		onSelect: function (dateText, inst) {
         //alert(dateText);
		var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
		$('#fin').datetimepicker('setDate', parsedDate);
		$('#fin').datepicker( "option", "minDate", parsedDate);
	//	$('#fin').datepicker( "option", "minDate", new Date(2013, 9 - 1, 1) );
		//$('#fin').datetimepicker('setDate', +1);
		
		

		
			  
	   }
		});
		
	
		},
	// Botones Agregar y Salir del formulario 
		buttons:{
		/*INICIO AGREGAR UN EVENTO*/
			"Agregar": function(){
				if($("#titulo").val()==""){
					alert("Debes ingresar el nombre del evento"); return false;
				}
				
				if($("#cliente").val()==""){
					alert("Debes seleccionar el cliente"); return false;
				}
				
				$.ajax({
					url:'form.php',
					type: 'POST',
					data: {
						funcion:'agregarevento',
						cliente:$("#cliente").val(),
						grupo:$("#grupo").val(),
						titulo:$("#titulo").val(),
						descripcion:$("#descripcion").val(),
						inicio:$("#inicio").val(),
						fin:$("#fin").val()
					},success: function(resp){
						if(resp==3){ 
							alert("Estas tratando de ingresar una cita que se transpapela con otra , checa tu disponibilidad"); 
							return false;
						}
						
						loadexpediente();
						$('.opciones-evento').dialog('close');
					}
				});
			},
		/*FIN AGREGAR UN EVENTO*/
		
		/*Salir*/
			"Salir": function(){
				$(this).dialog('close');
			}
		/*FIN Salir*/
		}
	// FIN Botones Agregar y Salir del formulario 
	}).height('auto');


	}});
	  /**/
  }
  
  function loadexpediente()
  {
	  if($("#cliente").val()=="")
	  {
	  	alert("Debes seleccionar un cliente"); return false;
	  }
	  
 $.ajax({
					url:'form.php',
					type: 'POST',
					data: {funcion:'loadexpediente',cliente:$("#cliente").val()},
					success: function(expe){
						$("#expediente").html(expe);
						 $( ".accordion" ).accordion();
					 
					 $('.fancybox').fancybox({
				openEffect  : 'elastic',
				closeEffect : 'elastic',
			});
			
				  $( ".subaccordion" ).accordion({collapsible: true, active: false });
						
				}});

}

function ReloadSubcliente(id)
{
 $.ajax({
					url:'form.php',
					type: 'POST',
					data: {funcion:'reloadgrupo',cliente:$("#frm-evento #cliente").val()},
					success: function(cbores){
						$("#loadgrupos").html(cbores);
				}});
}

function Adddescription(id)
{
	
   $.getScript("http://malsup.github.com/jquery.form.js", function() { 	
	//$.getScript("ckeditor/ckeditor.js", function() { 
  /**/
	  $.ajax({
		type: 'POST',
		url:'adddescription.php',
		data: {id:id},
		success: function(resp){   



$('.opciones-evento').dialog({
		modal: true,
		draggable: true,
		resizable: true,
		width:390,
		height:400,
		title:"Actualizar expediente",
		open: function(){$(this).empty().append(resp);
		// $(this).find('textarea').ckeditor();
		},
		buttons:{
			"Agregar": function(){
				/*INICIO AGREGAR UN EVENTO*/				
				$.ajax({
					url:'form.php',
					type: 'POST',
					data: {funcion:'actualizardescripcion',des:$("#descripcion").val(),id:id},
					success: function(resp){
					loadexpediente();	 
					$('.opciones-evento').dialog('close');
				}});	
				/*FIN AGREGAR UN DESCRIPCION*/
				},
			"Salir": function(){$(this).dialog('close');}
		        }
	}).height('auto');


	}});
	  /**/
	});
}
  
  </script>
  	<style>

	.ui-accordion-header {  
    background-color: #4c4c4c !important;
    background-image: none !important;  
    color: white !important;
    margin: 0px;  
}

		#botonexp{
			  position: relative;
   				left: 50px;
		}

		fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}
legend{
	margin-bottom: 30px !important;
}
		</style>
<title>Expediente</title>
</head>


<body>
<div class="nmwatitles">Expediente</div>
<fieldset class="scheduler-border">
	<legend class="scheduler-border">Expediente de la Agenda</legend>
	<div class="control-group">
<?php
$frm='<table width="100%" border="0">
<tr>
<td width="10%"><label>Cliente</label></td><td width="20%">
<select id="cliente" name="cliente" style="width:250px;" class="nminputselect">';
$frm.='<option value="">-Seleccione-</option>';
$qc=mysql_query("Select id, nombre from comun_cliente");
while($rowc=mysql_fetch_array($qc))  
{
	if($cliente==$rowc["id"])
	{
		$frm.='<option selected value="'.$rowc["id"].'">'.utf8_encode($rowc["nombre"]).'</option>';
	}
	else
	{
		$frm.='<option value="'.$rowc["id"].'">'.utf8_encode($rowc["nombre"]).'</option>';
	}
}
$frm.='</select><td width="10%">
<div float="left" id="botonexp">
<input type="button" value="Ver expediente" onClick="loadexpediente();" class="nminputbutton">
</div>
</TD>

<TD width="60%" align="right"><input type="button" value="Agendar nueva cita" onClick="Agendarcita();" class="nminputbutton_color2"></TD>
</tr></table>';

echo $frm;
?>


<span id="expediente" border="1"></span>
	</div>
</fieldset>

</body>
</html>
<div class="opciones-evento" title="Agregar un evento a la agenda"></div>

<div class="dialogoConfirmarEliminar"></div>