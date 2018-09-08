<?php
	include "../../../netwarelog/catalog/conexionbd.php";
    $conexion->cerrar();
    include "../clases.php";
    $nmdev_commun = new clnmdev_common();
    $conversaciones = $nmdev_commun->get_contactos_chat($bd);
	$nmdev_commun->disconnect();
	$netwarstore = new clnetwarstore();
	$conversaciones_detalles = $netwarstore->get_contactos($conversaciones);
	$netwarstore->disconnect();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Interactúa</title>
	<style type="text/css">
		.table_chat {
			text-align: left;
		}
		.table_chat td {
			border:1px solid silver;
			border-radius: 4px;
			vertical-align: top;
		}
		.table_chat td.contactos {
			width: 40%;
		}
		.table_chat td.mensajes {
			width: 60%;
		}
		#titulo {
			padding: 7px;
		}			
		#chats_abiertos {
			padding: 0px 7px 7px 7px;
		}
		.chat_contacto {
			padding: 5px;
		}
		.chat_contacto:hover {
			background-color: yellow;
		}
		.search { 
			border:none; border: solid 1px lightgray; font-size:15px; 
			padding: 5px; width: 95%; -webkit-appearance: none;
		}
		body {
			color: gray;
			font-size: 12px;
			font-family: "Arial";
		}
		.contactos{ width:100%; }
		#resultado_busqueda tr td { 
			padding: 7px;
			background: #efefef;
		}


	</style>


	<script type="text/javascript" src="../../../netwarelog/catalog/js/jquery.js"></script>
	<script>

		function ajusta_altura(){
			var altura = $("#tblurls",parent.document).css("height");
		

			altura = parseInt(altura) - 20;	
			console.log(altura);
			//alert("Prueba");

			$(".table_chat").css("height",altura+"px");
		}

		$(document).ready(function(){
			ajusta_altura();
		});

		$(window).resize(function(){
			ajusta_altura();

		});

	</script>

</head>
<body>

<table width="100%" class="table_chat">
	<tbody>
		<tr>
			<td class="contactos">
				<!-- CONTACTOS -->
				<div id="titulo"><span>CONVERSACIONES RECIENTES</span></div>
				<div id="chats_abiertos"></div>
				<input id="txtpersona" class="search" type="search" placeholder="Buscar persona...">
				<div id="resultado_busqueda"></div>
			</td>
			<td class="mensajes">
				<input id="contacto_hidden" name="contacto_hidden" type="hidden" value="">
				
				<input id="contacto_email_hidden" name="contacto_email_hidden" type="hidden" value="">
				<input id="contacto_rfc_hidden" name="contacto_rfc_hidden" type="hidden" value="">
				<input id="contacto_bd_hidden" name="contacto_bd_hidden" type="hidden" value="">

				<input id="rfc_remitente_hidden" name="rfc_remitente_hidden" type="hidden" value="">
				<input id="bd_remitente_hidden" name="bd_remitente_hidden" type="hidden" value="">
				<input id="rfc_emisor_hidden" name="rfc_emisor_hidden" type="hidden" value="">
				<input id="bd_remitente_hidden" name="bd_remitente_hidden" type="hidden" value="">
				
				
				<input id="txtmensaje" class="search" type="search" width="100%" placeholder="Mensaje ..." disabled>
				<div id="historial"></div>
				<div id="respuesta"></div>
			</td>
		</tr>
	</tbody>
</table>
	<script type="text/javascript">
		var nombre="";
		//var mensaje="";
		var pagina=0;
		var buscar_contactos;
		var i = "";
		var buscador_contactos;


		$(document).ready(function(){

			$("#txtpersona").bind('keyup',function(){
				clearTimeout(buscador_contactos);
				pagina = 0;
				nombre = $("#txtpersona").attr("value");
				//mensaje = "Localizando a: "+nombre+" ...";
				//$("#msg").html(mensaje);
				i ="";
				buscador_contactos = setInterval(function () { get_contactos(); }, 1000);
				
			});


			$(".chat_contacto a").bind('click', function(){
				get_historial(this.id);
				document.getElementById("contacto_hidden").value=this.id;
			});

			$("#txtmensaje").bind('click', function(){
				this.value = '';
			});

			$("#txtmensaje").bind('keypress', function(e){
				if (e.keyCode==13) {
					do_grabar_mensaje(document.getElementById("rfc_remitente_hidden").value, this.value, document.getElementById("bd_remitente_hidden").value);
					this.value='';
					get_contactos_chat();
				}
			});

			get_contactos_chat();

		});

		function get_contactos(){

			clearTimeout(buscador_contactos);
			$("#img_loading").css("visibility","visible");
			

			if(buscar_contactos){
				buscar_contactos.abort();
			}

			buscar_contactos = $.ajax({
				type: "POST",
				url: "contact_find.php",
				async: true,
				data: { name: nombre, page: pagina, i: i }
			}).done(function(response){
				if(pagina==1){
					$("#resultado_busqueda").html(response);	
				} else {
					//$("#table_result > tbody > tr").eq(i-1).after(response);	
					$("#resultado_busqueda").after(response);
				}
				//$("#msg").html("<br>");
				//$("#img_loading").css("visibility","hidden");
				$("#txtpersona").val('');
			});
			pagina = pagina + 1;
		}

		function get_contactos_chat(){
			buscar_contactos_chat = $.ajax({
				type: "POST",
				url: "contactos_chat_find.php",
				async: true
			}).done(function(response){
				$("#chats_abiertos").html(response);;		
			});
		}						

		function get_historial(id){
			buscar_chat = $.ajax({
				type: "POST",
				url: "get_chat.php",
				async: true,
				data: {user:id}
			}).done(function(response){
				$("#historial").html(response);
				$("#txtmensaje").removeAttr('disabled');
			});
			get_contactos_chat();
		}

		function do_grabar_mensaje(rfc_remitente, mensaje, bd_remitente){
			grabar_mensaje = $.ajax({
				type: "POST",
				url: "graba_mensaje_completa.php",
				async: true,
				data: {rfc_r:rfc_remitente, msj:mensaje, bd_r:bd_remitente}
			}).done(function(response){
				//$("#respuesta").html(response);
				get_historial(bd_remitente);
			});	
		}		

		function btnconversacion_click(rfc_remitente, bd_remitente){
			document.getElementById("rfc_remitente_hidden").value=rfc_remitente;
			document.getElementById("bd_remitente_hidden").value=bd_remitente;
		
			get_historial(bd_remitente);
			$("#txtmensaje").removeAttr('disabled');
			$("#txtmensaje").focus();	
			$("#resultado_busqueda").html("");
			$("#txtpersona").value = '';
		}

		function linkcontactochat_click(rfc_remitente, bd_remitente){
			document.getElementById("rfc_remitente_hidden").value=rfc_remitente;
			document.getElementById("bd_remitente_hidden").value=bd_remitente;

			get_historial(bd_remitente);
			$("#txtmensaje").removeAttr('disabled');
			$("#txtmensaje").focus();
			$("#")
		}
	</script>
</body>
</html>