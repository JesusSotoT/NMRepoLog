<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/unidadesTree.css">
	<!--<LINK href="../../../netwarelog/design/default/netwarlog.css"   title="estilo" rel="stylesheet" type="text/css" / -->
		<?php include('../../netwarelog/design/css.php');?>
	    <LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

	<script src="../js/unidadesTree.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	
	<script>
	$(function() {
		arbolUnidades.init();
	});
	</script>

	<title>Tree</title>
	<link rel="stylesheet" type="text/css" href="../css/imprimir_bootstrap.css" />
	<style type="text/css">
	    .btnMenu{
	        border-radius: 0; 
	        width: 100%;
	        margin-bottom: 0.3em;
	        margin-top: 0.3em;
	    }
	    .row
	    {
	        margin-top: 0.5em !important;
	    }
	    h4, h3{
	        background-color: #eee;
	        padding: 0.4em;
	    }
	    .nmwatitles, [id="title"] {
	        padding: 8px 0 3px !important;
	        background-color: unset !important;
	    }
	    .modal-title{
	  		background-color: unset !important;
	  		padding: unset !important;
	  	}
	    .select2-container{
	        width: 100% !important;
	    }
	    .select2-container .select2-choice{
	        background-image: unset !important;
	        height: 31px !important;
	    }
	</style>
</head>
<body>

	<div class="container" style="width:100%" id="divUnidades">
		<div class="row">
			<div class="col-sm-1">
			</div>
			<div class="col-sm-10" id="imp_cont">
				<h3 class="nmwatitles text-center">
					Arbol de Unidades
				</h3>
				<div class="row">
					<div class="col-sm-3">
						<label>Unidad:</label>
						<select id="cboUnidadesTree" onchange="arbolUnidades.desglosadas();" class="form-control">
							<option value="">Selecciona</option>
							<?php 
								foreach ($uniBasicas as $key => $value) {
									echo "<option id='".$value['id']."' value='".$value['identificadores']."'>".$value['tipo']."</option>";
								}
								echo "<option value='000'>Sin unidad Basica</option>";
							 ?>
						</select>
					</div>
					<div class="col-sm-3">
						<label>&nbsp;</label>
						<input type="button" id="btnNuevaUnidad" class="btn btn-success btnMenu" value="Nuevo Tipo de Unidad">
					</div>
					<div class="col-sm-3">
						<label>&nbsp;</label>
						<input type="button" id="btnModificarUnidad" class="btn btn-warning btnMenu" value="Modificar Tipo de Unidad">
					</div>
					<div class="col-sm-3">
						<label>&nbsp;</label>
						<input type="button" id="btnEliminarUnidad" class="btn btn-danger btnMenu" value="Eliminar Tipo de Unidad">
					</div>
				</div>
				<div class="row" id="unidadesDesglosadas" style="margin-bottom:5em;">
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-6 text-center"><h4>Unidad</h4></div>
							<div class="col-xs-6 text-center"><h4>Acciones</h4></div>
						</div>
						<div class="row">
							<div class="col-xs-12" id="resultUnidades"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="diagloNuevaUnidad" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog modal-sm">
	        <div class="modal-content">
	            <div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title">Unidad</h4>
	            </div>
	            <div class="modal-body">
	                <div class="row">
	                	<div class="col-md-12">
	                		<label>Nombre de la nueva Unidad Basica:</label>
	                		<input type="text" id="txtNuevaUnidad" value="" class="form-control">
	                	</div>
	                </div>
	            </div>
	            <div class="modal-footer">
	            	<div class="row">
	                    <div class="col-md-6">
	                        <button type="button" class="btn btn-primary btnMenu" onclick="javascript:arbolUnidades.nuevaAgregar();">Agregar</button>
	                    </div>
	                    <div class="col-md-6">
	                    	<button type="button" class="btn btn-danger btnMenu" onclick="javascript:$('#diagloNuevaUnidad').modal('hide');">Cancelar</button>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>				

	<div id="dialogModificarUnidad" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog modal-sm">
	        <div class="modal-content">
	            <div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title">Unidad</h4>
	            </div>
	            <div class="modal-body">
	                <div class="row">
	                	<div class="col-md-12">
	                		<label>Nombre anterior:</label>
	                		<section id="lblAnteriorNombre2"></section>
	                	</div>
	                </div>
	                <div class="row">
	                	<div class="col-md-12">
	                		<label>Nuevo Nombre:</label>
	                		<input type="text" id="txtModificaNombre" value="" class="form-control">
	                	</div>
	                </div>
	            </div>
	            <div class="modal-footer">
	            	<div class="row">
	                    <div class="col-md-6">
	                        <button type="button" class="btn btn-primary btnMenu" onclick="javascript:arbolUnidades.modificarCambiar();">Cambiar</button>
	                    </div>
	                    <div class="col-md-6">
	                    	<button type="button" class="btn btn-danger btnMenu" onclick="javascript:$('#dialogModificarUnidad').modal('hide');">Cancelar</button>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>		

	<div id="dialogEliminarUnidad" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog modal-sm">
	        <div class="modal-content">
	            <div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title">Unidad</h4>
	            </div>
	            <div class="modal-body">
	                <div class="row">
	                	<div class="col-md-12">
	                		<label>¿Estas Seguro de eliminar la Unidad Basica?</label>
	                		<section id="lblUnidadEliminar"></section>
	                	</div>
	                </div>
	            </div>
	            <div class="modal-footer">
	            	<div class="row">
	                    <div class="col-md-6">
	                        <button type="button" class="btn btn-primary btnMenu" onclick="javascript:arbolUnidades.eliminarAceptar();">Eliminar</button>
	                    </div>
	                    <div class="col-md-6">
	                    	<button type="button" class="btn btn-danger btnMenu" onclick="javascript:$('#dialogEliminarUnidad').modal('hide');">Cancelar</button>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>				

</body>
</html>