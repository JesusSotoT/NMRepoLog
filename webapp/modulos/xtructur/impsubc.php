<link rel="stylesheet" type="text/css" href="dhtmlxgantt.css"   title="estilo"  />
<script src="dhtmlxgantt.js" type="text/javascript"></script>
<script src="gant.espanol.js" type="text/javascript"></script>
<script src="dhtmlxgantt_tooltip.js"></script>

<style type="text/css">
	.grid_h0{ background-color: #c3c3c3 !important; border: 1px solid #c3c3c3;}
	.grid_h1{ background-color: #d3d3d3 !important;}
	.grid_h2{ background-color: #e3e3e3 !important;}
	.grid_h3{ background-color: #f3f3f3 !important;}

	.colores{ background-color: #f3f3f3 !important; color:#ff0000;}


#lc_chat_layout{
	display:none;
}




</style>

<style>
	.row {
		margin-top: 0.5em !important;
	}
	h5, h4, h3 {
		background-color: #eee;
		padding: 0.4em;
	}
	.modal-title {
		background-color: unset !important;
		padding: unset !important;
	}
	.nmwatitles, [id="title"] {
		padding: 8px 0 3px !important;
		background-color: unset !important;
	}
	@media only screen and (max-width: 520px){
		.smart{
			font-size: 2.5em !important;
			margin-left: 2em !important;
		}
	}
	.btn2{
		background-color: white; 
		color: 40542a; 
		opacity: 0.8;
		border-radius: 10px; 
		padding: 0.4em 1.5em;
		margin-bottom: 0.5em;
		margin-right: 1em;
		border-color: transparent;
	}
	.btn2:hover{
		background-color: #333;
		color: white;
	}
	.btn3{
		background-color: transparent;
		border: 1px solid white;
		color: white;
		border-radius: 3px;
		padding: 0.4em 0.4em;
		margin-bottom: 0.5em;
		margin-right: 1em;
		margin-top: 1em;
	}
	.btn3:hover{
		background-color: white;
	}

</style>

<body>

    <div id="divcuandohayobra" class="row">
      <div class="col-sm-12">
        <div class="panel panel-default" >
			<!-- Panel Heading -->
			<div class="panel-heading">
			<div class="panel-title">Importar subcontratistas</div>
			</div><!-- End panel heading -->

			<!-- Panel body -->
						<div class="panel-body" >
			<!-- ff-->			
		<div class="col-md-1 col-sm-1"> </div>
		<div class="col-md-15 col-sm-15">
			<section style="min-height: 650px; background: transparent url('subcontratista.png') no-repeat scroll center center / cover ;">
				<br> <br>
				<div class="row">
					<div class="col-md-9 col-sm-9 col-xs-9">
						<label style="font-weight: 100; font-size: 4.5em; padding-top: 0.75em; padding-left: 0.8em; color: white; letter-spacing: 0.03em;"></label>
					</div>
					<br> <br>
			
				</div>
				<div class="row" style="margin-top: 0.5em !important;">
					<div class="col-md-10 col-sm-10 col-xs-10">
						<label class="smart" style="color: #2795C8; font-weight: 500; font-size: 5em; letter-spacing: 0.03em;">&nbsp; </label>
					</div>
				</div>

				
					<div class="row" style="margin-top: 15em !important;">
						<div class="col-md-12 text-center">
							<button type="button" class="btn2 btn_2"  style="font-size: 1.5em;">&nbsp;&nbsp;Descargar&nbsp;&nbsp;</button>
							<button type="button" class="btn2 btn_3" style="font-size: 1.5em;">&nbsp;&nbsp;&nbsp;Examinar&nbsp;&nbsp;&nbsp;</button>
							<button type="button" class="btn2 btn_1" id="jojo" style="font-size: 1.5em;">&nbsp;&nbsp;&nbsp;Importar&nbsp;&nbsp;&nbsp;</button>
							<input type="file" id="miarchi" size="100" name="Filedata" style="display: none;">
							<br><center><label id='labelf' style="margin-top: 10px; color: #ffffff; font-weight: 500; letter-spacing: 0.03em;"></label><center>

						</div>
					</div>
				

				<script type="text/javascript">
					$(".btn_1:first").on("click", function(){
						
					if($('#miarchi').val()!==''){ 

						$('input[type=file]').simpleUpload('subirArchivo.php', {
							start: function(file){
								//upload started
								console.log("upload started");
							},

							progress: function(progress){
								//received progress
								console.log("upload progress: " + Math.round(progress) + "%");
							},

							success: function(data){
								//upload successful
								console.log(data);
								var objresp = $.parseJSON(data);
								console.log(objresp);

								if(objresp.success==true){
									$.ajax({
								        url:"importacion_subc.php",
								        type: 'POST',
								        async: false,
								        data:{file:objresp.archivo},
								        success: function(r){
								          if(r==1){
								            alert('Importacion creada exitosamente');
								            window.location='index.php?modulo='+modulo;
								          }else{
								            alert('Error durante el proceso de importacion');
								          }
								            
								        }
								    });
								}else{
									alert(objresp.message);
								}

							},

							error: function(error){
								alert('Error al subir el archivo');
								console.log("upload error: " + error.name + ": " + error.message);
							}
						});
					 }
					 else{

					 	$("#labelf").text('Ningun archivo seleccionado');}


					});
					$(".btn_2:first").on("click", function(){ programaObra(1); });
					$(".btn_3:first").on("click", function(){

                       
					 $('#miarchi').trigger('click'); 
                      

					});

					$("#miarchi").change(function(){

						$("#labelf").text($('#miarchi').val());
         //submit the form here
 });
				</script>
			</section>
		</div>

	<!--ff-->



			</div>
			</div>
      </div>
    </div>



</body>





<!--
<div class="row">
<button class="btn btn-primary btn-xs" onclick="guardarGantt();"> Guardar</button>
<div class="col-md-12" style="padding:0px;height:200px;">
</div>
</div>
-->


 <script>
$(function() {


});

	 function programaObra(option){
	 	window.open('plantilla_subc.xlsx');
	 }


</script>

