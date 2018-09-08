<?php


    include "../../../netwarelog/catalog/conexionbd.php";
    $conexion->cerrar();


    include "../clases.php";
    $nmdev_commun = new clnmdev_common();
	$invitations = $nmdev_commun->get_invitations($bd);
    $invitations_count = count($invitations);
    $nmdev_commun->disconnect();

?>
<!doctype html>
<html>
	<head>
		<title>Comunidad</title>
		<link rel = "stylesheet" type="text/css" href="../../../netwarelog/catalog/css/estilo.css">
		<style>
			.search { 
				border:none; border: solid 1px lightgray; font-size:15px; 
				padding: 5px; width: 100%; -webkit-appearance: none;
			}
			.search:focus {
				background-color: white;
			}
			#msg { color: gray; font-size: 11px; padding: 5px; }
			body { font-family: "Arial"; font-size: 12px; }
			#result { width: 100%;}
			.contactos{ width:100%; }
			.contactos tr td { 
				padding: 7px;
				background: #efefef;
			}
			.radius{	
				background: #ffffff; /* Old browsers */
				background: -moz-linear-gradient(top,  #ffffff 0%, #d8d8d8 100%); /* FF3.6+ */
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#d8d8d8)); /* Chrome,Safari4+ */
				background: -webkit-linear-gradient(top,  #ffffff 0%,#d8d8d8 100%); /* Chrome10+,Safari5.1+ */
				background: -o-linear-gradient(top,  #ffffff 0%,#d8d8d8 100%); /* Opera 11.10+ */
				background: -ms-linear-gradient(top,  #ffffff 0%,#d8d8d8 100%); /* IE10+ */
				background: linear-gradient(to bottom,  #ffffff 0%,#d8d8d8 100%); /* W3C */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#d8d8d8',GradientType=0 ); /* IE6-9 */
			}
			#img_loading {
				visibility: hidden;
			}

			.notifications {
 				border-radius: 10%;
    			behavior: url(PIE.htc); /* remove if you don't care about IE8 */
    			padding: 4px 8px 4px 8px;
			    background: red;
			    border: 3px solid red;
			    color:white;
			    font-size: 15px;
			    cursor: pointer;
			    text-align: center;
			}

		</style>
		<script type="text/javascript" src="../../../netwarelog/catalog/js/jquery.js"></script>
	</head>
	<body>
		<div>
			<table width="100%">
				<tbody>
					<tr>
						<?php 
						if($invitations_count>0){
							?>
							<td title="¡ <?php echo $invitations_count; ?> empresari@s quieren hacer hazbizne ! ">
								<div id="btnnotifications" class="notifications"><b><?php echo $invitations_count; ?></b>
								</div>
							</td>
							<?php
							}
						?>
						<td width="100%">
							<input id="txtpersona" class="search" type="search" width="100%" placeholder="Buscar persona...">
						</td>					
					</tr>
					<tr><th align="left" colspan=2><div id="msg"></div></th></tr>
				</tbody>
			</table>
		</div>
		<div id="result"></div>
		<table id="table_result" class='contactos'><tbody></tbody></table>
		<img id="img_loading" src='../../../netwarelog/repolog/img/loading.gif' height='20'>
		<script type="text/javascript">
			var nombre="";
			var mensaje="";
			var pagina=0;
			var buscar_contactos;
			var i = "";
			var buscador_contactos;

			$(document).ready(function(){

				$("#txtpersona").bind('keyup',function(){
					clearTimeout(buscador_contactos);
					pagina = 0;
					nombre = $("#txtpersona").attr("value");
					mensaje = "Localizando a: "+nombre+" ...";
					$("#msg").html(mensaje);
					i ="";
					buscador_contactos = setInterval(function () { get_contactos(); }, 1000);
				});


				$("#btnnotifications").bind('click',function(){
					pagina = 0;
					nombre = "%";
					mensaje = "Obteniendo empresarios que desean hacer hazbizne ...";
					$("#msg").html(mensaje);
					i='<?php 
						foreach($invitations as $db_contacto){
							echo $db_contacto."|";
						}
					?>';
					get_contactos();	
				});



				$("#btnnotifications").click();
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
						$("#table_result tbody ").html(response);	
					} else {
						//$("#table_result > tbody > tr").eq(i-1).after(response);	
						$("#table_result tbody tr:last ").after(response);
					}
					$("#msg").html("<br>");
					$("#img_loading").css("visibility","hidden");
				});
				pagina = pagina + 1;
			}

		
			function btnhazbizne_click(correo, rfc2, nombre_db2){


				$("#btn_"+nombre_db2).hide();
				$("#img_"+nombre_db2).show();

				hacer_bizne = $.ajax({
					type: "POST",
					url: "contact_hazbizne.php",
					async: true,
					data: { rfc_2: rfc2, nombre_db_2: nombre_db2 }
				}).done(function(response){

					var msg = "";	
					if(response=="1"){
						msg = "Usted ya esta vínculado en hazbizne con el empresario";
					} else if (response=="2") {
						msg = "El empresario ya recibió notificación de hazbizne con usted.";
					} else if (response=="3") {
						msg = "Se ha enviado un hazbizne con el empresario.";
					} else if (response=="4") {
						msg = "Listo, ya están conectados. ";
					} else {
						msg = "Ha habido un falló estamos trabajando en ello, intente nuevamente más tarde";
					}
					alert(msg);


					//alert(response);
					if(response=="4"){
						$("#img_"+nombre_db2).attr("src","../img/form_ok.png");	
					} else {
						$("#img_"+nombre_db2).attr("src","../img/invitation.png");
					}
					
				});

				//alert(estatus);	

				
			}
	

		</script>
	</body>
</html>

