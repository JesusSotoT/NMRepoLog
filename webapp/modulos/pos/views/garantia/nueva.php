<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Nueva Garantía</title>
    <link rel="stylesheet" href="">

    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <!-- Modificaciones RC -->
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    
    <script src="js/garantia.js"></script>
</head>

<body>
    <?php 
    if($editar) {
     ?>
     <div id="templateIdGarantia" style="display: none;"><?= $idGarantia ?></div>
     <script>
     	$(document).ready(function() {
     		var idGarantia = $('#templateIdGarantia').text().trim('string');
     		$.ajax({
                        type: "GET",                                            
                        url: "ajax.php?c=garantia&f=obtenerUna",  
                        dataType : 'json', 
                        data: { "idGarantia" : idGarantia },                                      
                        timeout: 1500,                                          
                        beforeSend: function(data) {                                 
                        },
                        complete: function(data) {                  
                        },
                        success: function(data) { 
                        	
                            $('#idd').val(data.id);
                            $('#nombreGarantia').val(data.nombre);
                            $('#tipoGarantia').val(data.tipo);
                            $('#duracionGarantia').val(data.duracion);
                            $('#idPolitica').val(data.politica);
                            $('#derechoGarantia').val(data.derecho);
							
							$.ajax({
							    type: "GET",                                            
							    url: "ajax.php?c=garantia&f=descripcionPolitica",
							    data: { "id" : data.politica },                                          
							    timeout: 2000, 
							    dataType: 'json',                                         
							    beforeSend: function() {                                      
							    },
							    complete: function() {                                                                 
							    },
							    success: function(data) {
							        $('#idPolitica').val(data.id);
							        $('#terminosGarantia').val(data.descripcion);
							    },
							    error: function() {  
							        alert("Error al cargar política");                                   
							    }
							});
                            
                            if(data.tipo == "1"){
                            	$('.garantia2').hide();
            					$('.garantia1').show();
                            	$(data.tabla).each(function(index, el) {
								
								let clasificado;
								switch( el.idTipoClasificador ) {
								    case "1": clasificado = "Departamento"; break;
								    case "2": clasificado = "Familia"; break;
								    case "3": clasificado = "Linea"; break;
								    default:
								}
	                                $('#tablaProductosClasificados tbody').append(`
	                                    <tr>
	                                    <td>` + clasificado + `</td>
	                                    <td>` + el.idClasificador + `</td>
	                                    <td>` + el.nombre + `</td>
	                                    <td> <button type="button" class="btn-warning"> Eliminar </button> </td>
	                                    </tr>
	                                    `
	                                    );
	                            });
	                            $('#tablaProductosClasificados button').off('click',  "**" );
	                            $('#tablaProductosClasificados button').on('click', function() {
	                                $(this).parent().parent().remove();
	                            });
                            } 
                            else {
                            	$('.garantia1').hide();
            					$('.garantia2').show();
                            	$(data.tabla).each(function(index, el) {
	                                $('#tablaProductos tbody').append(`
	                                    <tr>
	                                    <td>` + el.id + `</td>
	                                    <td>` + el.nombre + `</td>
	                                    <td> <button type="button" class="btn-warning"> Eliminar </button> </td>
	                                    </tr>
	                                    `
	                                    );
	                            });
	                            $('#tablaProductos button').off('click',  "**" );
	                            $('#tablaProductos button').on('click', function() {
	                                $(this).parent().parent().remove();
	                            });
                            }
                            

                        },
                        error: function() {                                     
                            alert("Error al cargar datos de garantía");
                        }
                    });
     	});
     </script>

     <?php } ?>

<div class="container well col-sm-12">
	<br/>
	<div class="row">
	    <div class="col-sm-1">
	        <button class="btn btn-default" id="btnRegresar">
	        <i class="fa fa-arrow-left" aria-hidden="true" ></i> Regresar
	        </button>
	    </div>

	    <div class="col-sm-1">
	        <div id="btnGuargar">
	          <button class="btn btn-primary" >
	          <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
	          </button>
	        </div>
	    </div>
    </div>

	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#basicos" aria-controls="basicos" role="tab" data-toggle="tab">Básicos</a></li>
		<li role="presentation"><a href="#politicas" aria-controls="politicas" role="tab" data-toggle="tab">Políticas</a></li>
		<li role="presentation"><a href="#configuracion" aria-controls="configuracion" role="tab" data-toggle="tab">Configuración</a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="basicos">
		<br>

			<div class="row" style="display: none;"> 
	            <div class="col-sm-3">
	                <label for="idd"> ID </label>
	                <input type="text" id="idd" class="form-control" disabled>
	                </input>
	            </div>
	        </div>
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label for="nombreGarantia">Nombre Garantía</label>
						<input type="text" class="form-control" id="nombreGarantia" >
					</div>

					<div class="form-group">
						<label for="tipoGarantia">Tipo de Garantía</label>
						<select class="form-control" id="tipoGarantia" placeholder="Tipo de garantía">
							<option value="1" selected="selected">Por clasificador</option>
							<option value="2">Por producto</option>
						</select>
					</div>

					<div class="form-group">
						<label for="derechoGarantia">Drerecho de Garantía</label>
						<select class="form-control" id="derechoGarantia" placeholder="Tipo de garantía">
							
							<option value="1" selected="selected">Cambio</option>
							<option value="2">Reparación</option>
							<option value="3">Ambos</option>
						</select>
					</div>

					<div class="form-group">
						<label for="duracionGarantia">Duración de Garantía</label>
						<input type="number" class="form-control" id="duracionGarantia" placeholder=" días ">
					</div>
				</div>
				<div class="col-md-7">
					<div class="garantia1" >
						<p>Permite establecer una política de garantía sobre un conjunto de productos que pertenezcan a un mismo clasificador ( por departamento, familia o linea ). </p>
						<p>Primero se deberá establecer los días que se manejarán como plazo de garantía, posteriormente se establecen los términos y condiciones. Para finalizar el registro de la garantía  se deberá seleccionar los clasificadores que correspondan con  esta garantía. </p>
						<p><strong>Nota:</strong> Los productos que pertenezcan a los clasificadores seleccionados respetarán la garantía previamente configurada. </p>


					</div>
					<div class="garantia2" hidden="true">
						<p>Permite establecer una política de garantía sobre un conjunto de productos ( no se requere que los productos pertenezcan a un mismo clasificador ). </p>
						<p>Primero se deberá establecer los días que se manejarán como plazo de garantía, posteriormente se establecen los términos y condiciones. Para finalizar el registro de la garantía  se deberá seleccionar los productos correspondan con esta garantía. </p>


					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="politicas">
			<br>

			<div class="row">
				<div class="col-sm-10">
                    <label for="buscadorPoliticas"> Buscar política existente </label>
                    <select id="buscadorPoliticas" class="form-control" >
                    </select>   
                </div>
			</div>

			<div class="row" style="display: none;"> 
	            <div class="col-sm-3">
	                <label for="idPolitica"> ID </label>
	                <input type="text" id="idPolitica" class="form-control" disabled>
	                </input>
	            </div>
	        </div>

		    <div class="row">
			    <div class="col-sm-10">
			        <div class="form-group">
						<label for="terminosGarantia">Términos y condiciones</label>
						<textarea class="form-control" id="terminosGarantia" rows="20" placeholder="Escribir aquí " style="background-color:##F0F0F0 "></textarea>
					</div>
			    </div>

			    <div class="col-sm-2">
			    	<div>

			    		<label for="nombreNuevaPolitica"> Nombre </label>
			                <input type="text" id="nombreNuevaPolitica" class="form-control">
			    	</div>

			        <div id="btnGuargarPolitica">
			          <button class="btn btn-primary" >
			          <span class="glyphicon glyphicon-floppy-disk"></span> Guardar <br> política
			          </button>
			          <p>
			          	NOTA: Al guardar esta política se estara generando una nueva.
			          </p>
			        </div>
			    </div>
		    </div>

		    <div class="col-sm-offset-4">
		      <button class="btn btn-primary">Imprimir</button>
		    </div>
		</div>
		<div role="tabpanel" class="tab-pane" id="configuracion">
			<br>
			<div class="garantia1" >
				<div class="row">
                	<div class="col-sm-12">
						<label for="clasificador"> Clasificador </label>
						<select class="form-control" id="clasificador">
							<option value="1" selected="selected"> Departamento </option>
							<option value="2"> Familia </option>
							<option value="3"> Linea </option>
						</select>
					</div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <label for="buscadorClasificado" id="lblBuscadorClasificado"> Departamento </label>
                        <select id="buscadorClasificado" class="form-control" >
                        </select>   
                    </div>
                    <div class="col-sm-3">
                        <label for="btnAgregarClasificado">  </label>
                        <button id="btnAgregarClasificado" class="btn btn-primary form-control" > Agregar </button>
                    </div>
                </div>

                <div class="row" style="height: 50px;">
                </div>

                <div class="row" >
	                <div class="col-sm-12">
		                <table id="tablaProductosClasificados" class="table">
		                    <thead>
		                    <tr>
		                    	<th> Clasificador </th>
		                        <th> ID </th>
		                        <th> Nombre </th>
		                        <th ><i class="fa fa-times" aria-hidden="true"></i> Eliminar </th>
		                    </tr>
		                    </thead>
		                    <tbody style="height: 10px !important; overflow: scroll;" ></tbody>

		                </table> 
	                </div>
	                
                </div>

                
			</div>
			<div class="garantia2" hidden="true">
				<div class="row">
                    <div class="col-sm-8">
                        <label for="buscadorProducto"> Producto </label>
                        <select id="buscadorProducto" class="form-control" >
                        </select>   
                    </div>
                    <div class="col-sm-3">
                        <label for="btnAgregarProducto">  </label>
                        <button id="btnAgregarProducto" class="btn btn-primary form-control" > Agregar </button>
                    </div>
                </div>

                <div class="row" style="height: 50px;">
                </div>

                <div class="row" >
	                <div class="col-sm-12">
		                <table id="tablaProductos" class="table">
		                    <thead>
		                    <tr>
		                        <th> ID </th>
		                        <th> Nombre </th>
		                        <th ><i class="fa fa-times" aria-hidden="true"></i> Eliminar </th>
		                    </tr>
		                    </thead>
		                    <tbody style="height: 10px !important; overflow: scroll;" ></tbody>

		                </table> 
	                </div>
	                
                </div>

			</div>
		</div>
	</div>
</div>
</body>
</html>