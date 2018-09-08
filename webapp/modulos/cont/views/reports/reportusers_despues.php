<html lang="sp">
	<head>
    <!--LINK href="../../../webapp/netwarelog/utilerias/css_repolog/estilo-1.css" title="estilo" rel="stylesheet" type="text/css" / -->
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--TITULO VENTANA-->
		<title>Reporte de Usuarios</title>
		<meta name="generator" content="Netbeans">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-08-07 -->
		<meta name="author-icons" content="Rachel Fu"><!-- Date: 2010-08-07 -->

    <!--PLUG IN CATALOG-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>


    <script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
    <script type="text/javascript">

			function pdf(){
				/*
				var anchopadre = document.getElementById("tabla_reporte").parent.style.width;
				alert(anchopadre);
				var anchotabla = document.getElementById("tabla_reporte").style.width;
			  var pleft = (anchopadre / 2)+(anchotabla/2);
				alert(pleft);
				*/
				var contenido_html = $("#idcontenido_reporte").html();
				//contenido_html = contenido_html.replace(/\"/g,"\\\"");
				$("#contenido").attr("value",contenido_html);

				$("#divpanelpdf").modal('show');
			}

			function generar_pdf(){
				$("#divpanelpdf").modal('hide');
				//$("#loading").fadeIn(500);
			}

			function cancelar_pdf(){
				$("#divpanelpdf").modal('hide');
			}

			function pdf_generado(){
				alert("OK");
			}
			//$('#frpdf').load(function() {
  		//	alert("the iframe has been loaded");
			//});
			//document.getElementById("frpdf").onload = function() {
  		//	alert("myframe is loaded");
			//};

			//$('#frpdf').ready(function () {
			//  alert("perfecto");
			//});
			function mail(){
				var msg = "Registre el correo electrónico a quién desea enviarle el reporte:";
				var a = prompt(msg,"@netwaremonitor.com");
				if(a!=null){
					var html_contenido_reporte;
					html_contenido_reporte = $("#idcontenido_reporte").html();
					$("#loading").fadeIn(500);
					$("#divmsg").load("../../../webapp/netwarelog/repolog/mail.php?a="+a, {reporte:html_contenido_reporte});
				}
			}
		</script>
    <script language='javascript'>
    $(document).ready(function(){
    	$('table.bh tbody tr').append("<td><a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a></td>")

    	//$("th:contains('Subtotal [')").text('Subtotal').next().next().after("<th style='border:solid 1px;background-color:#efefef;text-align:left;font-size:12px;'></th>");
    	//$("th:contains('TOTAL')").text('Total de la Cuenta').next().next().after("<th style='border:solid 1px;background-color:#efefef;text-align:left;font-size:12px;'></th>");
    });
    function generaexcel()
  	{
  		$().redirect('views/fiscal/generaexcel.php', {'cont': $("#idcontenido_reporte").html(), 'name': $("#titulo").val()});
  	}
    </script>
    <link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
    <style type="text/css">
    	.tit_tabla_buscar td
    	{
    		font-size:medium;
    	}

    	#logo_empresa /*Logo en pdf*/
    	{
    		display:none;
    	}

    	@media print
    	{
    		#imprimir,#filtros,#excel,#email_icon, #botones
    		{
    			display:none;
    		}

    		#logo_empresa
    		{
    			display:block;
    		}

    		.table-responsive{
    			overflow-x: unset;
          display: block;
    		}
    	}

    	.btnMenu
      {
        border-radius: 0;
      	width: 100%;
      	margin-bottom: 0.3em;
      	margin-top: 0.3em;
      }

    	.row
    	{
    	  margin-top: 0.5em !important;
    	}

    	h4, h3
      {
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

    	.select2-container
      {
        width: 100% !important;
    	}
    	.select2-container .select2-choice
      {
        background-image: unset !important;
       	height: 31px !important;
    	}
    	.twitter-typeahead{
    		width: 100% !important;
    	}
      .hidden {
        display: hidden;
      }
    </style>
	</head>

	<body>
	<?php //Nuevo Commit
  	$url = explode('/modulos',$_SERVER['REQUEST_URI']);
  	if($logo == 'logo.png') $logo = 'x.png';
  	$logo = str_replace(' ', '%20', $logo);
	?>
		<!--GENERA PDF*************************************************-->
	   <div id="divpanelpdf" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog modal-sm">
	    	<div class="modal-content">
	      	<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	          <h4 class="modal-title">Generar PDF</h4>
	        </div>
	        <form id="formpdf" action="libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
	        	<div class="modal-body">
	          	<div class="row">
	            	<div class="col-md-6">
	              	<label>Escala (%):</label>
									<select id="cmbescala" name="cmbescala" class="form-control">
										<?php
											for($i=100; $i > 0; $i--){
												echo '<option value='. $i .'>' . $i . '</option>';
											}
										?>
									</select>
	        			</div>
	              <div class="col-md-6">
	              	<label>Orientación:</label>
	              	<select id="cmborientacion" name="cmborientacion" class="form-control">
										<option value='P'>Vertical</option>
										<option value='L'>Horizontal</option>
									</select>
	              </div>
	           	</div>
	            <textarea id="contenido" name="contenido" style="display:none"></textarea>
							<input type='hidden' name='nombreDocu' value='reporte_usuarios'>
							<input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
	          </div>
	          <div class="modal-footer">
	          	<div class="row">
	              <div class="col-md-6">
	              	<input type="submit" value="Crear PDF" autofocus class="btn btn-primary btnMenu">
	              </div>
	              <div class="col-md-6">
	              	<input type="button" value="Cancelar" onclick="cancelar_pdf()" class="btn btn-danger btnMenu">
	              </div>
	            </div>
		      	</div>
	      	</form>
	    	</div>
	    </div>
		</div>
		<!--GENERA PDF*************************************************-->

		<!-- MAIL -->
		<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;z-index:500;">
		<div
			id="divmsg"
			style="
				opacity:0.8;
				position:relative;
				background-color:#000;
				color:white;
				padding: 20px;
				-webkit-border-radius: 20px;
				border-radius: 10px;
				left:-50%;
				top:-30%
			">
			<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...
			</center>
		</div>
		</div>
		<script>
			function cerrarloading(){
				$("#loading").fadeOut(0);
				var divloading="<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...</center>";
				$("#divmsg").html(divloading);
			}

      function modalFacturas(id) {
        $.ajax({
             type: "POST",
             url: 'ajax.php?c=ReportsMovPolizas&f=modal_facturas',
             data:{
               id: id
             },
             success:function(data) {
               $('#modalFacturas').modal('show');
               $('.modal-content').html("Cargando..");
               $('.modal-content').html(data);
             }
        });
      }

		</script>

		<div class="container" style="width:100%">
			<div class="row">
				<div class="col-md-12">
					<h3 class="nmwatitles text-center">
						Reporte de Usuarios<br>
						<section id="botones">
  						<a href="javascript:window.print();"><img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0"></a>
  	          <a href="index.php?c=Report_User&f=reportusers"> <img src="../../../webapp/netwarelog/repolog/img/filtros.png" title ="Haga clic aqui para cambiar filtros..." border="0"> </a>
  						<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>
  						<a href="javascript:mail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"> </a>
  						<a href="javascript:pdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"> </a>
						</section>
					</h3>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<form id="reporte" name="reporte"  method="post" action="redirecciona.php">
								<input type='hidden' value='reporte_usuarios' id='titulo'>
								<section id='idcontenido_reporte'>
									<div class="row">
										<div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:10px;text-align:right;color:gray;">
											<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 col-sm-12" style='text-align:center;font-size:18px;'>
											<b style='text-align:center;'><?php echo $empresa;?></b>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 col-sm-12" style='background:white;color:#576370;text-align:center;font-size:12px'>
											<b style='font-size:16px;'>Reporte de Usuarios</b><br>
											Del <b><?php echo $fecha_antes;?> </b>Al <b> <?php echo $fecha_despues;?></b>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
													<div class="table-responsive">
														<!-- TABLA REPORTE -->
														<table  style="text-align:center; width:100%;" id="tabla_reporte" class="reporte table" border="0" align="center" >
  					                	<tr style="background: #ddd; font-weight:bold;" class="table-header">
  						                	<td>Fecha</td>
  						                	<td>Usuario</td>
  						                	<td>Nombre del Proceso</td>
  						                	<td>IP</td>
  						                </tr>

															<?php
															while($info = mysqli_fetch_assoc($datos)){
	                              echo("
	                                <tr>
	                                  <td>".$info['fecha']."</td>
	                                  <td>".$info['usuario']."</td>
	                                  <td>".$info['nombreproceso']."</td>
	                                  <td>".$info['ip']."</td>
	                                </tr>
	                              ");
															}
															?>
									          </table> <!-- Tabla -->
													</div> <!-- // table responsive -->
												</div> <!-- // col -->
											</div> <!-- // row -->
										</div> <!-- // col -->
									</div> <!-- // row -->
								</section> <!-- // Contenido reporte -->
							</form> <!-- // Reporte -->
						</div> <!-- // col 2 -->
					</div> <!-- // row 2 -->
				</div> <!-- // main col -->
			</div> <!-- // main row -->
		</div> <!-- // container -->

    <!-- Modal para mostrar cuando un registro tiene facturas multiples -->
    <!-- El ajax esta en este mismo archivo tiene el nombre de modalFacturas -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalFacturas">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <!-- Se rellena con ajax -->
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

  </body>
</html>
