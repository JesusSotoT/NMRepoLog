<!DOCTYPE html>
<html>
<head>
<link href='form.css' rel='stylesheet' />
<link href='fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<link href="jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
<?php include('../../netwarelog/design/css.php');?>
<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

	<script src="jquery-ui/js/jquery-1.9.1.js"></script>
	<script src="jquery-ui/js/jquery-ui-1.10.3.custom.js"></script>
 <!--  <script src="jquery/jquery.validate.min.js"></script> -->
<script src='fullcalendar/fullcalendar.js'></script>

<script src='datetimepicker/datetimepicker.js'></script>
<script src='datetimepicker/jquery-ui-timepicker-es.js'></script>
<script src='datetimepicker/ui.datepicker-es.js'></script>
<link href='datetimepicker/datetimepicker.css' rel='stylesheet' />

<!-- **  Librerias bootstrap  **-->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
	
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<!-- **  FIN Librerias bootstrap  **-->

<!-- ** Select con buscador **  -->
		<script src="select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="select2/select2.css" />
<!-- ** FIN Select con buscador **  -->

<script>

$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		var agregando=true;

	var calendar = $('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		
		disableDragging: true,
		disableResizing:true,
		eventClick: function(calEvent, jsEvent, view) {
			$.ajax({
				type: 'POST',
				url:'form.php',
				data: {funcion:"form",id:calEvent.id,titulo:calEvent.title,descripcion:calEvent.description},
				success: function(resp){
					$('.opciones-evento').dialog({
						modal: true,
						draggable: true,
						resizable: true,
						width:400,
						height:600,
						open: function(){
							$(this).empty().append(resp);
							$("#fin").datetimepicker({
								minDate: new Date(),
								dateFormat: "yy-mm-dd",
								timeFormat: "HH:mm tt"
							});
							
							$("#inicio").datetimepicker({
								minDate: new Date(),
								dateFormat:"yy-mm-dd",
								timeFormat: "HH:mm tt",
								onSelect: function (dateText, inst) {
							  		var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
									$('#fin').datetimepicker('setDate', parsedDate);
									$('#fin').datepicker( "option", "minDate", parsedDate);
						   		}
							});
						},
				
						buttons:{
						/*INICIO ACTUALIZAR EVENTO*/
							"Actualizar": function(){
								if($("#todoeldia").is(':checked')){
									allDay=true;
								}else{
									allDay=false;
								}
					
								$.ajax({
									url:'form.php',
									type: 'POST',
									data: {
										funcion:'agregarevento',
										inicio:$("#inicio").val(),
										fin:$("#fin").val(),
										id:$("#id").val(),
										grupo:$("#grupo").val(),
										cliente:$("#cliente").val(),
										titulo:$("#titulo").val(),
										descripcion:$("#descripcion").val(),
										todoeldia:allDay
									},success: function(resp){
										if(resp==3){ 
											alert("Estas tratando de ingresar una cita que se transpapela con otra , checa tu disponibilidad"); 
											return false;
										}
										
										$('#calendar').fullCalendar( 'refetchEvents' );
										$('.opciones-evento').dialog('close');
									}
								});	
								
								$(this).dialog('close');
							},
						/*END ACTUALIZAR UN EVENTO*/
						
						/*INICIO ELIMINAR EVENTO*/	
							"Eliminar": function(){
								//alert(calEvent.id);
								$('.dialogoConfirmarEliminar').dialog({
									modal: true,
									minWidth: 390,
									draggable: true,
									resizable: false,
									title:"Eliminar evento",
									open: function(){
										$(this).empty().append('¿Estas seguro que deseas eliminar el evento?');
									},
									
									buttons:[{
											text:'Eliminar',
											click: function(){ 
												$.ajax({
													url:'form.php',
													type: 'POST',
													data: {funcion:'eliminarevento',id:calEvent.id},
													success: function(resp){
														if(resp==1){
															$('#calendar').fullCalendar( 'refetchEvents' );
															$('.dialogoConfirmarEliminar').dialog('close');
															$('.opciones-evento').dialog('close');
														}
													}
												});	
											}
										},{
											text: 'Salir',
											click: function(){
												$(this).dialog('close');
											}
										}
									]
								}).height('auto');
							},
						//*END ELIMINAR EVENTO*/
					
						//*INICIO Salir EVENTO*/
							"Salir": function(){
								$(this).dialog('close');
							}
						//*END Salir EVENTO*/
						}
					}).height('auto');
				//* FIN  $('.opciones-evento').dialog */
				}
			//* FIN  succsses Ajax */
			});	
		//* FIN Ajax */
		},
	// * FIN eventClick: function */
	
		selectable: true,
		selectHelper: true,
		select: function(start, end, allDay) {
			var now = new Date();
				
			if(allDay){
				now.setDate(now.getDate()-1);
			}
		
			if( (new Date(start).getTime() < now)){
	   			alert("No puedes hacer una cita de una fecha y hora anterior a la actual"); return false;
			}		
		
			start=$.fullCalendar.formatDate(start,"yyyy-MM-dd HH:mm:ss");
			end=$.fullCalendar.formatDate( end,"yyyy-MM-dd HH:mm:ss");		
		//alert(start);		
			$.ajax({
				type: 'POST',
				url:'form.php',
				data: {funcion:"form",inicio:start,fin:end,todoeldia:allDay},
				success: function(resp){
					$('.opciones-evento').dialog({
						modal: true,
						draggable: true,
						resizable: true,
						width:400,
						height:600,
						open: function(){
							$(this).empty().append(resp);
							
							$("#fin").datetimepicker({
								minDate: new Date(),
								dateFormat: "yy-mm-dd",
								timeFormat: "HH:mm tt"
							});
							
							$("#inicio").datetimepicker({
								minDate: new Date(),
								dateFormat:"yy-mm-dd",
								timeFormat: "HH:mm tt",
								onSelect: function (dateText, inst) {
								 	var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
									$('#fin').datetimepicker('setDate', parsedDate);
									$('#fin').datepicker( "option", "minDate", parsedDate);
								}
							});
						},
						
						buttons: {
						/*INICIO AGREGAR UN EVENTO*/
							"Agregar": function() {
								if ($("#titulo").val() == "") {
								alert("Debes ingresar el nombre del evento");
									return false;
								}
									
								if ($("#cliente").val() == "") {
									alert("Debes seleccionar el cliente");
									return false;
								}
					
								if ($("#todoeldia").is(':checked')) {
									allDay = true;
								} else {
									allDay = false;
								}
					
								$.ajax({
									url : 'form.php',
									type : 'POST',
									data : {
										funcion : 'agregarevento',
										cliente : $("#cliente").val(),
										grupo : $("#grupo").val(),
										titulo : $("#titulo").val(),
										descripcion : $("#descripcion").val(),
										inicio : $("#inicio").val(),
										fin : $("#fin").val(),
										todoeldia : allDay
									},success : function(resp) {
										if (resp == 5) {
											alert("Debes seleccionar una hora de fin mayor a la inicial");
											return false;
										}
					
										if (resp == 4) {
											console.log('Ini: '+$("#inicio").val()+' Fin: '+$("#fin").val())
											alert("Debes seleccionar  una hora mayor a la actual");
											return false;
										}
					
										if (resp == 3) {
											alert("Estas tratando de ingresar una cita que se transpapela con otra , checa tu disponibilidad");
											return false;
										}
				
										$('#calendar').fullCalendar('refetchEvents');
										$('.opciones-evento').dialog('close');
									}
								});
							},
						/*FIN AGREGAR UN EVENTO*/
						
							"Salir": function() {
								$(this).dialog('close');
							}
						}
					}).height('auto');
				}
			});
		// * FIN Ajax */
		},
	// * FIN SELECT function */
		editable: true,
		events:{url:'eventos.php',cache:false}
	});
// FIN fullCalendar
});
//	
function addgrup()
{
if($("#cliente").val()==""){alert("Debes seleccionar el cliente primero");return false;}
$('.dialogoConfirmarEliminar').dialog({
		modal: true,
		minWidth: 390,
		draggable: true,
		resizable: false,
		title:"Agregar grupo",
		open: function(){
		$(this).empty().append('Nombre<input type="text" id="nombregrupo">');},
		buttons:[{text:'Guardar',click: function(){ 
					
					 $.ajax({
					url:'form.php',
					type: 'POST',
					data: {funcion:'guardagrupo',nombre:$("#nombregrupo").val(),cliente:$("#cliente").val()},
					success: function(cbores){
						$("#loadgrupos").html(cbores);
						$('.dialogoConfirmarEliminar').dialog('close');

				}});
				
			}},{text: 'Salir',click: function(){$(this).dialog('close');}}]}).height('auto');			
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
					data: {funcion:'eliminargrupo',id:$("#grupo").val(),cliente:$("#cliente").val()},
					success: function(cbores){
						$("#loadgrupos").html(cbores);
						$('.dialogoConfirmarEliminar').dialog('close');

				}});
				
			}},{text: 'Salir',click: function(){$(this).dialog('close');}}]}).height('auto');			
	}
}

function ReloadSubcliente(id)
{
 $.ajax({
					url:'form.php',
					type: 'POST',
					data: {funcion:'reloadgrupo',cliente:$("#cliente").val()},
					success: function(cbores){
						$("#loadgrupos").html(cbores);
				}});
}


function guardaGrupo()
{
		$("#intercambio").html('<select id="grupo" name="grupo"><option>-Seleccione-</option></select><input type="button" id="addgrupo" value="+" class="add">');	
		agregando=true;
}

///////////////// ******** ---- 		select_buscador		------ ************ //////////////////

	//////// Cambia los select por select con buscador.
		// Como parametros puede recibir:
			// Array con los id de los select
		
			function select_buscador ($objeto) {
			// Recorre el arreglo y establece las propiedades del buscador
				$.each( $objeto, function( key, value ) {
					$("#"+value).select2({
						width : "150px"
					});
				});
			}

///////////////// ******** ---- 		FIN select_buscador		------ ************ //////////////////

</script>
<style>

	body {
		margin-top: 40px;
		text-align: center;
		font-size: 12px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}

	#calendar 
	{
		width: 900px;
		margin: 0 auto;
		}

</style>
</head>
<body>
<div id='calendar'></div>

<div class="opciones-evento" title="Agregar un evento a la agenda">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span></p>
</div>
</body>
</html>

<div class="dialogoConfirmarEliminar"></div>