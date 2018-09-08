 <?php 
//echo json_encode($configuracion['act']);
  ?>
<style>
    .panel-heading{
        height: 50px;
    }
    .blueText{
        color: #48D1CC;
    }
    .subText{
        color: #808080;
    }
    .divdorder{
        border: red;
    }
    .subText2{
      font-family: monospace;
      color: #48D1CC;
    }
    .panel-heading {background-color: blue!important}
    .btnmin{
        min-width: 77px !important;
    }
   	#divpasos{
   		min-height: 390px !important;
   	}
   	#divpasos2{
   		height: 180px !important;
   		overflow-y: auto;
   	}

   	#divpro {
    position: absolute;
    bottom: 5px;
	}
</style>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Implementacion Inicial</title>
    <link rel="stylesheet" href="">
</head>

    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">

	<body> 
        <div class="container">
        	<div class="col-md-7" style="border-style: none; padding: 10px;">        		
                <h2><b>Hola, <?php echo $_SESSION['accelog_login']; ?></b></h2>
				<p>
                Bienvenido a la <strong>Guia de Implementacion Inicial.</strong> Te invitamos a que completes cada paso.
                    Si tienes alguna duda estamos disponibles para ti en el <strong>Chat.</strong>
				</p>

				<!--<button id="sp1_1" class="btn btnmin btn-success sp1" onclick="openMenu(1969);" type="button">Mas Información</button>-->
        	</div>

        	<div class="col-md-5" style="border-style: none; padding: 0px; text-align: center;">
        		<h2><b>PROGRESO GENERAL</b></h2>

        		<div class="progress progress-striped active col-md-12 pull-right" style="padding:0">
	               	<div id="prgressT" class=" progreso progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>                                     
	            </div> 
				<!--
					<div class="col-sm-6" style="padding-top: 20px;">
						<div class="input-group">
						  <input   class="form-control"  type="text" id="fechaI" value="<?php echo $fechaI; ?>">
						  <span class="input-group-addon glyphicon glyphicon-calendar" id=""></span>
						</div>
					</div>
				-->
				
	            <div id="divfechaI" class="col-sm-4" style="display: none;">
	            	<input type="hidden" id="inicio">
         			<div style="text-align: left">Fecha Inicial:<input  style ="width: 100px;" class="form-control"  type="text" id="fechaI" value="<?php echo $fechaI; ?>"></div>
         		</div>
         		
				<!--
		            <div class="col-sm-3">
	         			<div style="text-align: left">Fecha Actual:<input  style ="width: 100px;" class="form-control"  type="text" id="fechaA" value="<?php echo $fechaA; ?>"></div>
	         		</div>
				-->
         		<div id="divfechaF" class="col-sm-4" style="display: none;">
         			<div style="text-align: left">Fecha Final:<input  style ="width: 100px;" class="form-control"  type="text" id="fechaF" value="<?php echo $configuracion['app'][0]['fechaFinal']; ?>"></div>
         		</div>

         		<div id="divdias" class="col-sm-4" style="display: none;">
						<div style="text-align: left">Dias Transcurridos: <input style ="width: 100px; text-align: center;" class="form-control"  type="text" id="diasT" value="<?php echo $diasT; ?>"></div>
				</div>
        		
        	</div>
		
			<div class="col-sm-12" style="text-align: center; border-style: none; padding-top: 5px; white-space: nowrap; display: inline-block;">
				<div class="col-md-1"><br><br><br><br><br><br><br><br><i style="cursor: pointer;" aria-hidden="true" class="fa fa-chevron-left fa-2x" onclick="menos();"></i></div>
	         	<div id="divscroll" class="col-md-10" style="border-style: none;  overflow-x:auto; overflow: hidden; white-space: nowrap; display: inline-block;">	
		         	<?php 
						$c = 0;	
						foreach ($pasos as $key => $value) {	// PASOS	
						$c++;
						if($c > 5){ break; } // solo aparecen los primeros 5 pasos mmosterando en ellos todas las actividades
						if($c > $value['paso']){ break; } // cuando tiene pocos pasos de 2 sistemas
					?>

				<div class="btn"  style="width: 300px; cursor: default;">						
						<div id="divpasos" class="panel panel-primary">
					      	<div class="panel-heading" style="height: 40px; text-align: left; font-size: 22px;" ><b>PASO <?php echo $c; ?></b></div>
					      	<div class="panel-body" style="padding: 5px;">
								<?php 
									if($c == 1){ $wid = 200; $hei = 150; }
									if($c == 2){ $wid = 140; $hei = 150; } 
									if($c == 3){ $wid = 140; $hei = 150; }
									if($c > 3){ $wid = 140; $hei = 150; }

									if($misproductos == '1001|1002|' || $misproductos == '1002|'){ $p = 'f';
									}else if($misproductos == '1001|'){ $p = 'a';
									}else{$p='';}
								?>
								<?php  echo '<img src="images/paso'.$c.$p.'.png" width="'.$wid.'" height="'.$hei.'">'?>

								<p><b><?php echo $value['nombre']; ?></b></p>
								<div id ="divpasos2">
								<?php							
									foreach ($configuracion['act'] as $key => $val) { // ACTIVIDADES
										//if($val['id_paso'] == $value['id_paso']){
										if($val['paso'] == $value['paso']){
								?>

			                                <div class="col-xs-9" style="padding: 0; padding-top: 5px; text-align: left; font-size: 13px;">
			                                    <?php if($val['opcional'] != 1){ echo '<label style="color:red;" title="Obligatorio!">*</label>'; } ?><label style="font-size: 10px;"><?php echo $val['nombre']; ?> <!--<span class="glyphicon glyphicon-info-sign primary" data-toggle="tooltip" width="20px" title=" <?php echo $val['desc_larga']; ?>">--> </label><br>
			                                </div>	
			                                <?php 
			                                	if($val['estatus'] == 1){
			                                 ?>	

			                                 	<?php if($val['opcional'] == 0){ ?>	
													<div class="col-xs-3" style="padding: 0; padding-top: 5px; text-align: right;">
				                                    	<!--<a href="https://<?php echo $val['link']; ?>" target="_blank"><span class="fa fa-eye"></a>-->
				                                    	<button title="Realizar" id="sp<?php echo $value['id_paso'].'_'.$val['id_actividad'];  ?>" type="button" class="btn btn-primary sp<?php echo $value['id_paso']; ?> btn-xs" onclick="openMenu(<?php echo $val['menu'];?>,<?php echo $value['id_paso'];?>,<?php echo $val['id_actividad'];?>,<?php echo $value['paso'];?>,0);" ><i class="fa fa-play" aria-hidden="true"></i></button>				                                    	
				                                	</div>
			                                 	<?php }else{ ?>	
													<div class="col-xs-3" style="padding: 0; padding-top: 5px; text-align: right;">
				                                    	<!--<a href="https://<?php echo $val['link']; ?>" target="_blank"><span class="fa fa-eye"></a>-->
				                                    	<button title="Omitir" id="spo<?php echo $value['id_paso'].'_'.$val['id_actividad'];  ?>" type="button" class="btn btn-default spo<?php echo $value['id_paso']; ?> btn-xs" onclick="omitir(<?php echo $val['menu'];?>,<?php echo $value['id_paso'];?>,<?php echo $val['id_actividad'];?>,<?php echo $value['paso'];?>,1);" ><i class="fa fa-stop" aria-hidden="true"></i></button>				                                    	
				                                    	<button title="Realizar" id="sp<?php echo $value['id_paso'].'_'.$val['id_actividad'];  ?>" type="button" class="btn btn-primary sp<?php echo $value['id_paso']; ?> btn-xs" onclick="openMenu(<?php echo $val['menu'];?>,<?php echo $value['id_paso'];?>,<?php echo $val['id_actividad'];?>,<?php echo $value['paso'];?>,1);" ><i class="fa fa-play" aria-hidden="true"></i></button>				                                    	
				                                	</div>
			                                 	<?php } ?>                                 	
			                                 	
			                                 <?php 
			                                 	}else{	                                 	
			                                  ?>
												<div class="col-xs-3" style="padding: 0; padding-top: 5px; text-align: right;">
													<!--<a href="https://<?php echo $val['link']; ?>" target="_blank"><span class="fa fa-eye"></a>-->
													<button title="Realizado" id="sp<?php echo $value['id_paso'].'_'.$val['id_actividad'];  ?>" type="button" class="btn btn-success sp<?php echo $value['id_paso']; ?> btn-xs" onclick="openMenu(<?php echo $val['menu']; ?>);"><i class="fa fa-check" aria-hidden="true"></i></button>
			                                	</div>
			                                  <?php 
												}
			                                   ?>
			                                                              
								<?php 
										}

									}

							
								?>
								</div>
								<br>
								
								<div width="300px" id="divpro" class="progress progress-striped active col-xs-3" style="padding:0; margin-bottom:29px; margin-left:9px;">
                                    <div id="<?php echo 'sp'.$value['paso'].'pro'; ?>" class=" progreso progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $value['progres'].'%'?>"> <?php if($value['progres'] > 0){ echo number_format($value['progres'],0).'%'; } ?></div> 	                                    
								</div>
							
					      	</div>
					    					      						     
					    </div>

					</div>
				<!---->
				<?php   } 
				?>

	         	</div>
	         	<div class="col-md-1"><br><br><br><br><br><br><br><br><i style="cursor: pointer;" aria-hidden="true" class="fa fa-chevron-right fa-2x" onclick="mas();"></i></div>
	        </div>

	        <div style="border-style: none; padding: 0px; text-align: center;">
	        	<i class="fa fa-phone fa-3x" aria-hidden="true"></i>
	        	<br>
	        	<label style="font-size: 18px; padding:0px;">Si tienes alguna duda, comunícate al:</label><br>
	        	<label style="font-size: 22px; padding:0px;"><b></b>01 800 2777 321</b></label>	        	
	        </div>
			
	<!--
		<div class="container">

			<div class="panel-group" id="accordion">
                    
				<?php 
					$c = 0;	
					foreach ($pasos as $key => $value) {	// PASOS	
					$c++;
					if($c > 5){ break; } // solo aparecen los primeros 5 pasos mmosterando en ellos todas las actividades
					if($c > $value['paso']){ break; } // cuando tiene pocos pasos de 2 sistemas
				?>

					<div class="panel panel-primary">					
						<div class="panel-heading panel-primary">						
							<h4 class="panel-title">							
	                            <a id="serv_<?php echo $value['paso'].'_'.$value['solucion'];?>" class="accordion-toggle acordion2" data-toggle="collapse" data-parent="#accordion" href="#serv<?php echo $value['paso'].'_'.$value['solucion'];?>"> 
                                    <div class="progress progress-striped active col-md-2 pull-right" style="padding:0">
                                        <div id="<?php echo 'sp'.$value['paso'].'pro'; ?>" class=" progreso progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $value['progres'].'%'?>"></div> 	                                    
                                    </div>
                                    <div>Paso <?php echo $value['paso'].': ';  echo $value['nombre']; ?> </div>
	                            </a>
	                            	&nbsp;&nbsp;&nbsp; <a href="https://<?php echo $value['link']; ?>" target="_blank"><span class="fa fa-eye"></a>                            
	                        </h4>
	                    </div>

                        <div id="serv<?php echo $value['paso'].'_'.$value['solucion'];?>" class="panel-collapse collapse">
                            <div class="panel-body">

                            	<?php 
									foreach ($configuracion['act'] as $key => $val) { // ACTIVIDADES
										//if($val['id_paso'] == $value['id_paso']){
										if($val['paso'] == $value['paso']){
								?>
			                                <div class="col-xs-8">
			                                    <?php if($val['opcional'] != 1){ echo '<label style="color:red;" title="Obligatorio!">*</label>'; } ?><label ><?php echo $val['nombre']; ?> <span class="glyphicon glyphicon-info-sign primary" data-toggle="tooltip" width="20px" title=" <?php echo $val['desc_larga']; ?>"> </label><br>
			                                </div>
			                                <?php 
			                                	if($val['estatus'] == 1){
			                                 ?>	

			                                 	<?php if($val['opcional'] == 0){ ?>	
													<div class="col-xs-4 text-right">
				                                    	<a href="https://<?php echo $val['link']; ?>" target="_blank"><span class="fa fa-eye"></a>
				                                    	<button id="sp<?php echo $value['id_paso'].'_'.$val['id_actividad'];  ?>" type="button" class="btn btn-primary btnmin sp<?php echo $value['id_paso']; ?>" onclick="openMenu(<?php echo $val['menu'];?>,<?php echo $value['id_paso'];?>,<?php echo $val['id_actividad'];?>,<?php echo $value['paso'];?>,0);" >Realizar</button>				                                    	
				                                	</div>
			                                 	<?php }else{ ?>	
													<div class="col-xs-4 text-right">
				                                    	<a href="https://<?php echo $val['link']; ?>" target="_blank"><span class="fa fa-eye"></a>
				                                    	<button id="spo<?php echo $value['id_paso'].'_'.$val['id_actividad'];  ?>" type="button" class="btn btn-default btnmin spo<?php echo $value['id_paso']; ?>" onclick="omitir(<?php echo $val['menu'];?>,<?php echo $value['id_paso'];?>,<?php echo $val['id_actividad'];?>,<?php echo $value['paso'];?>,1);" >Omitir</button>				                                    	
				                                    	<button id="sp<?php echo $value['id_paso'].'_'.$val['id_actividad'];  ?>" type="button" class="btn btn-primary btnmin sp<?php echo $value['id_paso']; ?>" onclick="openMenu(<?php echo $val['menu'];?>,<?php echo $value['id_paso'];?>,<?php echo $val['id_actividad'];?>,<?php echo $value['paso'];?>,1);" >Realizar</button>				                                    	
				                                	</div>
			                                 	<?php } ?>                                 	
			                                 	
			                                 <?php 
			                                 	}else{	                                 	
			                                  ?>
												<div class="col-xs-4 text-right">
													<a href="https://<?php echo $val['link']; ?>" target="_blank"><span class="fa fa-eye"></a>
													<button id="sp<?php echo $value['id_paso'].'_'.$val['id_actividad'];  ?>" type="button" class="btn btnmin btn-success sp<?php echo $value['id_paso']; ?>" onclick="openMenu(<?php echo $val['menu']; ?>);"><span class="glyphicon glyphicon glyphicon-check" ></span></button>                                    	
			                                	</div>
			                                  <?php 
												}
			                                   ?>	                              
								<?php 
										}
									}
								 ?>
                            </div>
                        </div>
					</div>
				<?php 
					}
				 ?>
            </div>
        </div> 
    -->

      <!--          Molda Success           -->
	  <div id="modalSuccess" class="modal fade">
	    <div class="modal-dialog">
	        <div class="modal-content panel-success">
	            <div class="modal-header panel-heading">
	                <h4 id="modal-label">Exito!</h4>
	            </div>
	            <div class="modal-body">
	                <p>¡Gracias por tu preferencia! Acabas de completar el primer paso.</p>
	            </div>
	            <div class="modal-footer">
	                <button id="btncon" type="button" class="btn btn-default">Continuar</button> 
	            </div>
	        </div>
	    </div> 
	  </div>

	  <!--          Molda Success           -->
	  <div id="modalFinal" class="modal fade">
	    <div class="modal-dialog">
	        <div class="modal-content panel-success">
	            <div class="modal-header panel-heading">
	                <h4 id="modal-label">Exito!</h4>
	            </div>
	            <div class="modal-body">
	                <p>¡Felicidades! Completaste tu implementación.</p>
	            </div>
	            <div class="modal-footer">
	                <button id="btnMF" type="button" class="btn btn-default">Continuar</button> 
	            </div>
	        </div>
	    </div> 
	  </div>
    </body>



</html>

     <script>

	     $(document).ready(function() {	
	     	$("#divscroll").scrollLeft(0);     	
	     	$('#inicio').val(<?php echo $app[0]['fechaInicio'];?>);
	     	var contCom = 0;
     	 	var contInc = 0;
     	 	//var contOmt = 0;
        	$(".btn-primary").each(function (index){   
				contInc++;
			 });
			$(".btn-success").each(function (index){   
				contCom++;
			 });
			/*
			$(".btn-default").each(function (index){   
				contOmt++;
			 });
			 */
			if(contCom > 0){
				$("#divfechaI").show();
				$("#divdias").show();
			}
			var fechaFF = '<?php echo $app[0]['fechaFinal']; ?>';
			if(fechaFF != 0 || fechaFF != ''){
				$("#divfechaF").show();
			}
			//alert('Completos'+contCom);
			//alert('Inompletos'+contInc);
			//contCom = contCom - 1;
			var totalP = contInc + contCom;
			var ProgressT = (contCom *  100) / totalP;
			$("#prgressT").attr('style','width: '+ProgressT+'%');
			ProgressTF       = ProgressT.toFixed(0).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
			$("#prgressT").text(ProgressTF+'%');
			//alert(ProgressT);
			fechas();

	    });
     	console.log('<?php echo $misproductos;?>');     	
     	
     	function openMenu(menu,id_paso,id_actividad,paso,opc){ 
 		     		
     		//var fechaI = '<?php echo $app[0]['fechaInicio']; ?>';
     		var fechaI = $('#inicio').val();
     		var fechaF = $('#fechaF').val();
   			//alert(fechaI);
   			if(fechaF != ''){
   				alert('¡Ya ha terminado su implementacion!');
   				return false;
   			}
   			   
   			
     		
     		if(fechaI==0 || fechaI==''){ 
				var r = confirm("¿Está seguro del comienzo de su implementación?");				
				if (r == true) {
					$('#diasT').val(0);
				    $.ajax({
			                url: 'ajax.php?c=implementa&f=saveIncio',		                	                
			        })
			        .done(function(data) {
			        	console.log('Registrado el inicio de Implemnatición');
			        	$("#inicio").prop('title', data);
			        	$('#btncon').attr('onclick', 'menuv('+menu+','+id_paso+','+id_actividad+','+paso+');');
			        	$("#modalSuccess").modal('show');
			        	$('#inicio').val(data);			        	
			        	$("#divfechaI").show();
			        	$("#divdias").show();
			        	
			        	return;
		        	 })
		        	 return; 
				} else {
				    return;
				} 			
     			 	        	 
     		}
     		menuv(menu,id_paso,id_actividad,paso,opc);	 				     	     		 
     	}
     	
     	function menuv(menu,id_paso,id_actividad,paso,opc){

     	 	var contCom = 0;
     	 	var contInc = 0;
     	 	$("#modalSuccess").modal('hide');
 			var i = 0;
 				$.ajax({
	                url: 'ajax.php?c=implementa&f=menu',
	                type: 'post',
	                dataType: 'json',	                
					data:{menu:menu},
					async:false
		        })
		        .done(function(data) {
		        	console.log(data);
		        	$.each(data, function(index, val) {
		        			var url = val.url;
		        			var nombre = val.nombre;
		        			var idmenu = val.idmenu; 

		        			if($("#sp"+id_paso+"_"+id_actividad).hasClass('btn-primary') == true){
		        				$('.sp'+id_paso).each(function(key, element){		        					
								  i++;							  
								  
								});
								var avance = (1 * 100) / i;
								av = $("#sp"+paso+"pro").attr('style');

								//var av2 = av.slice(7, 10);
								var av2 = av.substring(7);
								var av3 = av2.replace('%', '')*1;
								var porcen = av3 + avance;
								//$("#sp"+paso+"pro").removeAttr('style');
								$("#sp"+paso+"pro").attr('style','width: '+porcen+'%');
								porcenT = porcen.toFixed(0).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
								$("#sp"+paso+"pro").text(porcenT+'%');
								fechas();

								if(opc == 1) {

								}
							/*
							if(opc == 1) {

								if($("#spo"+id_paso+"_"+id_actividad).hasClass('btn-default') == true){
			        				$('.sp'+id_paso).each(function(key, element){
									  i++;							  
									});
									var avance = (1 * 100) / i;
									av = $("#sp"+paso+"pro").attr('style');
									var av2 = av.slice(7, 10);
									var av3 = av2.replace('%', '')*1;
									var porcen = av3 + avance;
									$("#sp"+paso+"pro").attr('style','width: '+porcen+'%');
									$("#sp"+paso+"pro").text(porcen+'%');
									fechas();
			        			}

							}	
							*/														

		        			}  
				        
				        $("#sp"+id_paso+"_"+id_actividad).removeClass('btn-primary').addClass('btn-success').html('<i class="fa fa-check" aria-hidden="true"></i>');   

				        if(opc == 1) {
				        	$("#spo"+id_paso+"_"+id_actividad).removeClass('btn-default').addClass('btn-success').html('<i class="fa fa-check" aria-hidden="true"></i>');   
				        }

			        	$(".btn-primary").each(function (index){   
							contInc++;
						 });
						$(".btn-success").each(function (index){   
							contCom++;
						 });
						//alert('Completos'+contCom);
						//alert('Inompletos'+contInc);
						var totalP = contInc + contCom;
						var ProgressT = (contCom *  100) / totalP;
						$("#prgressT").attr('style','width: '+ProgressT+'%');
						ProgressTF       = ProgressT.toFixed(0).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
						$("#prgressT").text(ProgressTF+'%');
						//alert(ProgressT);

						if(opc == 1) {
				        	$("#spo"+id_paso+"_"+id_actividad).hide();
				        }

						if(contInc == 0){
							//alert('¡Felicidades! Completaste tu implementación!');
							$('#btnMF').attr('onclick', 'lastMenu("'+url+'","'+nombre+'","'+idmenu+'",0);');
							$("#modalFinal").modal('show');
						}else{
							window.parent.agregatab(""+url+"",""+nombre+"","",idmenu); 
						}
						
		            });
		        }) 
 			}
 			function lastMenu(url,nombre,idmenu,opc){
 				//alert(opc);

 				$.ajax({
			            url: 'ajax.php?c=implementa&f=saveFin',		                	                
			        })
			        .done(function(data) {
			        	console.log('Registrado el fin de Implemnatición');
			        	$("#fechaF").val(data);	
			        	$("#divfechaF").show();
		        	 })
 				$("#modalFinal").modal('hide');
 				if(opc == 0){
 					window.parent.agregatab(""+url+"",""+nombre+"","",idmenu);
 				}
 			}

 		function fechas(){
 			var fechaI = '';
 			var fechaF = '';
 			var fechaA = '';

 			$.ajax({
			    url: 'ajax.php?c=implementa&f=fechas',
			    dataType:'json'	
	        })
	        .done(function(data) {
	       		$.each(data, function(index, val) {
	                  $("#fechaI").val(val.fechaI);
	                  $("#fechaA").val(val.fechaA);
	                  $("#fechaF").val(val.fechaF);
	                  $("#inicio").prop('title', val.fechaI);
	                  $("#inicio").val(val.fechaI);
	                  $("#diasT").val(val.diasT);

	            });
        	 })
 		}
 		function omitir(menu,id_paso,id_actividad,paso,opc){

 			var contCom = 0;
     	 	var contInc = 0;
     	 	$("#modalSuccess").modal('hide');
 			var i = 0;
 			$.ajax({
	                url: 'ajax.php?c=implementa&f=menu',
	                type: 'post',
	                dataType: 'json',	                
					data:{menu:menu},
					async:false
		    })
		    .done(function(data) {
		        	console.log(data);
		        	$.each(data, function(index, val) { 
		        			var url = val.url;
		        			var nombre = val.nombre;
		        			var idmenu = val.idmenu;  

		        			if($("#spo"+id_paso+"_"+id_actividad).hasClass('btn-default') == true){
		        				///alert('spo');
		        				$('.sp'+id_paso).each(function(key, element){
								  i++;							  
								});
								var avance = (1 * 100) / i;
								av = $("#sp"+paso+"pro").attr('style');
								//var av2 = av.slice(7, 10);
								var av2 = av.substring(7);
								var av3 = av2.replace('%', '')*1;
								var porcen = av3 + avance;
								$("#sp"+paso+"pro").attr('style','width: '+porcen+'%');
								porcenT       = porcen.toFixed(0).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
								$("#sp"+paso+"pro").text(porcenT+'%');
								fechas();
		        			}
																	        			 				        
				        $("#spo"+id_paso+"_"+id_actividad).removeClass('btn-default').addClass('btn-success').html('<i class="fa fa-check" aria-hidden="true"></i>');   
				        $("#sp"+id_paso+"_"+id_actividad).removeClass('btn-primary').addClass('btn-success').html('<i class="fa fa-check" aria-hidden="true"></i>');
				        

			        	$(".btn-primary").each(function (index){   
							contInc++;
						 });
						$(".btn-success").each(function (index){   
							contCom++;
						 });
						//alert('Completos'+contCom);
						//alert('Inompletos'+contInc);
						var totalP = contInc + contCom;
						var ProgressT = (contCom *  100) / totalP;
						$("#prgressT").attr('style','width: '+ProgressT+'%');
						ProgressTF       = ProgressT.toFixed(0).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
						$("#prgressT").text(ProgressTF+'%');
						//alert(ProgressT);

						if(contInc == 0){
							//alert('¡Felicidades! Completaste tu implementación!');
							$('#btnMF').attr('onclick', 'lastMenu("'+url+'","'+nombre+'","'+idmenu+'",1);');
							$("#modalFinal").modal('show');
						}

						$("#spo"+id_paso+"_"+id_actividad).hide();
						
		            });
		        }) 
 		}
 		function mas(){ 			
 			$("#divscroll").scrollLeft(800);
 		}
 		function menos(){
 			$("#divscroll").scrollLeft(0);
 		}
     </script>





