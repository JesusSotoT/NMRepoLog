<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>SESIÓN INICIAL</title>
	<link href="../../libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../libraries/sweetalert/css/sweetalert.css" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=0.5">
	<style type="text/css">
		.row {
			margin-bottom: 1em;
			margin-top: 1em;
		}

		.btn {
			width: 100% !important;
			padding: 1em;
		}
	</style>
</head>
<body>

	<form id=formulariouno>
	<!--Datos Generales -->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>DATOS GENERALES DEL EMPRESARIO</label>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Folio de proyecto (últimos seis digitos)</label>
				                 <input type="text" id="f1p1a" name="f1p1a" class="form-control requerido" placeholder="Folio">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Razón social</label>
				                 <input type="text" id="f1p2a" name="f1p2a" class="form-control requerido" placeholder="(Nombre)">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Nombre del representante legal</label>
				                 <input type="text" id="f1p3a" name="f1p3a" class="form-control requerido" placeholder="Representate legal">
				            </div>
				            
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>RFC de laempresa</label>
				                 <input type="text" id="f1p4a" name="f1p4a" class="form-control requerido" placeholder="RFC">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Foto de la constancia del RFC actualizada</label>
				            </div>
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Hoja 1</label>
				                <input type="file" id="f1p5a" name="f1p5a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Hoja 2</label>
				                <input type="file" id="f1p5b" name="f1p5b" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Hoja 3</label>
				                <input type="file" id="f1p5c" name="f1p5c" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>   
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Teléfono</label>
				                 <input type="text" id="f1p6a" name="f1p6a" class="form-control requerido" placeholder="Teléfono">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Correo electrónico</label>
				                 <input type="text" id="f1p7a" name="f1p7a" class="form-control requerido" placeholder="Correo electrónico">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>Tamaño de la empresa</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f1p8a" id="f1p8a" value="si" checked>
											Micro Empresa
									</label>
									<label style="margin-right: 3em">
									    <input type="radio" name="f1p8a" id="f1p8a" value="no">
										    pequeña Empresa
									</label>
									<label>
									    <input type="radio" name="f1p8a" id="f1p8a" value="no">
										    Mediana Empresa
									</label>
								</div>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>Giro de la empresa</label> 
				            	<div class="radio">
									<label style="margin-right: 3em">
									    <input type="radio" name="f1p9a" id="f1p9a" value="si" checked>
											Comercio
									</label>
									<label style="margin-right: 3em">
									    <input type="radio" name="f1p9a" id="f1p9a" value="no">
										    Servicio
									</label>
									<label>
									    <input type="radio" name="f1p9a" id="f1p9a" value="no">
										    Manufactura
									</label>
								</div>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Rubro de la empresa</label>
				                 <input type="text" id="f1p10a" name="f1p10a" class="form-control requerido" placeholder="(a que se dedica ej. abarrotes, refaccionaria, etc)">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>Tipo de vialidad</label> 
				            	<div class="radio">
									<label style="margin-right: 1em">
									    <input type="radio" name="f1p11a" id="f1p11a" value="si" checked>
											Calle
									</label>
									<label style="margin-right: 1em">
									    <input type="radio" name="f1p11a" id="f1p11a" value="no">
										    Avenida
									</label>
									<label style="margin-right: 1em">
									    <input type="radio" name="f1p11a" id="f1p11a" value="no">
										    Andador
									</label>
									<label style="margin-right: 1em">
									    <input type="radio" name="f1p11a" id="f1p11a" value="si" checked>
											Bulevar
									</label>
									<label style="margin-right: 1em">
									    <input type="radio" name="f1p11a" id="f1p11a" value="no">
										    Carretera
									</label>
									<label>
									    <input type="radio" name="f1p11a" id="f1p11a" value="no">
										    Cerrada
									</label>
								</div>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Nombre de vialidad</label>
				                 <input type="text" id="f1p12a" name="f1p12a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Número exterior</label>
				                 <input type="text" id="f1p13a" name="f1p13a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				            	<label>Tipo de asentamiento</label> 
				            	<div class="radio">
									<label style="margin-right: 1em">
									    <input type="radio" name="f1p14a" id="f1p14a" value="si" checked>
											Colonia
									</label>
									<label style="margin-right: 1em">
									    <input type="radio" name="f1p14a" id="f1p14a" value="no">
										    Fraccionamiento
									</label>
									<label style="margin-right: 1em">
									    <input type="radio" name="f1p14a" id="f1p14a" value="no">
										    Manzana
									</label>
									<label>
									    <input type="radio" name="f1p14a" id="f1p14a" value="no">
										    Ejido
									</label>
								</div>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Nombre del asentamiento</label>
				                 <input type="text" id="f1p15a" name="f1p15a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Código postal</label>
				                 <input type="text" id="f1p16a" name="f1p16a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Localidad</label>
				                 <input type="text" id="f1p17a" name="f1p17a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Municipio</label>
				                 <input type="text" id="f1p18a" name="f1p18a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Entidad federativa</label>
				                 <input type="text" id="f1p19a" name="f1p19a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Entre vialidades</label>
				                 <input type="text" id="f1p20a" name="f1p20a" class="form-control requerido" placeholder="Tipo y nombre">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Vialidad posterior</label>
				                 <input type="text" id="f1p21a" name="f1p21a" class="form-control requerido" placeholder="Tipo y nombre">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Descripción de la ubicación</label>
				                 <input type="text" id="f1p22a" name="f1p22a" class="form-control requerido" placeholder="(Casa balnca, a un lado del banco, frente a la iglesia, etc)">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Documento de empleo-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>DOCUMENTO DE EMPLEO</label>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Carta de auto empleo</label>
				                <input type="file" id="f1p23a" name="f1p23a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>   
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>IFE de empresario</label>
				            </div> 
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Frente</label>
				                <input type="file" id="f1p24a" name="f1p24a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div> 
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Reverso</label>
				                <input type="file" id="f1p24b" name="f1p24b" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>   
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Lista de rayas de hombres</label>
				                <input type="file" id="f1p25a" name="f1p25a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>   
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>IFE de hombres</label>
				            </div> 
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Frente</label>
				                <input type="file" id="f1p26a" name="f1p26a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div> 
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Reverso</label>
				                <input type="file" id="f1p26b" name="f1p26b" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>   
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Lista de rayas de mujeres</label>
				                <input type="file" id="f1p27a" name="f1p27a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>   
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>IFE de mujeres</label>
				            </div> 
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Frente</label>
				                <input type="file" id="f1p28a" name="f1p28a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div> 
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Reverso</label>
				                <input type="file" id="f1p28b" name="f1p28b" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>   
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Comprobante de domicilio-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>COMPROBANTE DE DOMICILIO</label>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Fotografia de recibo de luz</label>
				            </div> 
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Frente</label>
				                <input type="file" id="f1p29a" name="f1p29a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div> 
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Reverso</label>
				                <input type="file" id="f1p29b" name="f1p29b" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>   
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<!--Indicadores de prodictividad-->
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>INDICADORES DE PRODUCTIVIDAD</label>
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>¿Cuantos empleados tienes?</label>
				            </div>
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Hombre</label>
				                 <input type="text" id="f1p30a" name="f1p30a" class="form-control requerido" placeholder="">
				            </div>
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Mujer</label>
				                 <input type="text" id="f1p30b" name="f1p30b" class="form-control requerido" placeholder="">
				            </div>
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Indigena</label>
				                 <input type="text" id="f1p30c" name="f1p30c" class="form-control requerido" placeholder="">
				            </div>
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Discapacitado</label>
				                 <input type="text" id="f1p30d" name="f1p30d" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Ventas totales del periodo anterior</label>
				                 <input type="text" id="f1p31a" name="f1p31a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Ventas totales del periodo actual</label>
				                 <input type="text" id="f1p32a" name="f1p32a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Costo de nomina</label>
				                 <input type="text" id="f1p33a" name="f1p33a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Valor de los activos fijos</label>
				                 <input type="text" id="f1p34a" name="f1p34a" class="form-control requerido" placeholder="(Renta, auto, equipo de computo, etc)">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		



























		<!--
		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>RFC</label>
				                <input type="file" id="f1p2a" name="f1p2a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>DOMICILIO FISCAL</label>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <input type="file" id="f1p3a" name="f1p3a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>
				        </div>
						<div class="row">
				        	<div class="col-sm-12 col-md-6 col-md-offset-3">
				    			<input type="text" id="f1p3b" name="f1p3b" class="form-control requerido" placeholder="Calle">
				            </div>  
				        </div>
				        <div class="row">
				        	<div class="col-sm-12 col-md-6 col-md-offset-3">
				    			<input type="text" id="f1p3c" name="f1p3c" class="form-control requerido" placeholder="Colonia">
				            </div>
				        </div>

				        <div class="row">
				        	<div class="col-sm-12 col-md-6 col-md-offset-3">
				    			<input type="text" id="f1p3d" name="f1p3d" class="form-control requerido" placeholder="Código postal">
				            </div>
				        </div>
						 <div class="row">
				        	<div class="col-sm-12 col-md-6 col-md-offset-3">
				    			<input type="text" id="f1p3e" name="f1p3e" class="form-control requerido" placeholder="Ciudad">
				            </div>
				        </div>
						<div class="row">
				        	<div class="col-sm-12 col-md-6 col-md-offset-3">
				    			<input type="text" id="f1p3f" name="f1p3f" class="form-control requerido" placeholder="Estado">
				            </div>
				        </div>
						<div class="row">
				        	<div class="col-sm-12 col-md-6 col-md-offset-3">
				    			<input type="text" id="f1p3g" name="f1p3g" class="form-control requerido" placeholder="Contacto">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>VENTAS ANTERIORES</label>
				                <input type="text" id="f1p4a" name="f1p4a" class="form-control requerido" placeholder="Ventas Anteriores">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>RECIBO DE ENTREGA DE APOYO</label>
				                <input type="file" id="f1p5a" name="f1p5a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>NUMERO DE EMPLEADOS</label>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Hombres</label>
				                <input type="text" id="f1p6a" name="f1p6a" class="form-control requerido" placeholder="Cantidad">
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Mujeres</label>
				                <input type="text" id="f1p6b" name="f1p6b" class="form-control requerido" placeholder="Cantidad">
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Indigena</label>
				                <input type="text" id="f1p6c" name="f1p6c" class="form-control requerido" placeholder="Cantidad">
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Discapacitado</label>
				                <input type="text" id="f1p6d" name="f1p6d" class="form-control requerido" placeholder="Cantidad">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>NOMINA</label>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>CARTA DE AUTOEMPLEO + IFE</label>
				                <input type="file" id="f1p7a" name="f1p7a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>SUB</label>
				                <input type="file" id="f1p7b" name="f1p7b" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>Lista de raya + IFE</label>
				                <input type="file" id="f1p7c" name="f1p7c" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>INDICADORES DE PRODUCTIVIDAD</label>
				                <input type="text" id="f1p8a" name="f1p8a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>RECIBO DE LUZ/JUSTIFICACIÓN</label>
				                <input type="file" id="f1p9a" name="f1p9a" class="archivo imagen requerido" accept="image/x-png, image/jpeg">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>

		<div class="panel panel-default">
	    	<div class="panel-footer">
	        	<div class="container">
					<div class="panel-title">
				   		<div class="row">
				            <div class="col-sm-12 col-md-6 col-md-offset-3">
				                <label>ENCUESTA DE SATISFACCIÓN DEL CONSULTOR</label>
				                <input type="text" id="f1p10a" name="f1p10a" class="form-control requerido" placeholder="">
				            </div>
				        </div>
					</div>		
	        	</div>
	    	</div>
		</div>
		-->

	</form>

	<div class="panel panel-default">
		<div class="panel-footer">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-md-offset-3" align="center">
					<button type="submit" class="btn btn-primary" id="enviar">ENVIAR</button>
				</div>
			</div>
		</div>
	</div>


	<!-- jQuery -->
    <script src="../../libraries/jquery/js/jquery.min.js"></script>
	<!-- SweetAlert -->
    <script src="../../libraries/sweetalert/js/sweetalert.min.js"></script>

    <script src="../general.js"></script>
	<script src="../java_script/formulario_uno.js"></script>
</body>
</html>


