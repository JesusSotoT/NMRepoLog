<style>
	#lista td
	{
		width:146px;
		text-align: center;
		border:1px solid #BDBDBD;
	}

	#buscar
	{
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	-o-border-radius: 4px;
	border-radius: 4px;
	}

	#cargando
	{
		display:none;
		position:absolute;
		z-index:1;
	}

	.btnMenu{
	    border-radius: 0; 
	    width: 100%;
	    margin-bottom: 0.3em;
	    margin-top: 0.3em;
	}

	.row
	{
	    margin-top: 0.1em !important;
	}
	.select2-container{
		width: 100% !important;
	}
	.select2-container .select2-choice{
		background-image: unset !important;
		height: 31px !important;
	}
	#copiarPoliza {
	    background-color: unset;
	    border: unset;
	    height: unset;
	    width: unset;
	}
</style>

<div id="copiarPoliza" class="modal fade" tabindex="-1" role="dialog" >
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title">Copiar Poliza</h4>
      		</div>
      		<div class="modal-body">
      			<form action="index.php?c=CaptPolizas&f=copiaPoliza" method="post">
      				<input type='hidden' value='<?php echo $numPoliza['id']; ?>' name='idpoliza' id='idpoliza'>
      				<div class="row">
      					<div class="col-md-6">
      						<label>Copiar la poliza:</label>
      						<select onchange="copia()" id="ele" name="ele" class="form-control">
								<option value="1">Completa</option>
								<option value="2">Movimientos</option>
							</select>
      					</div>
      				</div>
      				<div class="row">
      					<div class="col-md-4">
      						<label id="conc">Concepto:</label>
      						<input type="text" id="conceptocopy" name="conceptocopy" class="form-control" placeholder="Concepto..."/>
      					</div>
      					<div class="col-md-4">
      						<label id="fechaco">Fecha:</label>
      						<input type="date" id="fechacopy" name="fechacopy" class="form-control" />
      					</div>
      				</div>
      				<h4 style="display: none" id="slm">Selecciona los moviminetos:</h4>
      				<div class="row">
      					<div class="col-md-8 col-md-offset-2">
      						<select id="movi" multiple="" name="movimientoscopi[]" style="display: none" class="form-control">
							</select>
      					</div>
      				</div>
      				<section id="selectpoliza">
	      				<h4>Copiar a:</h4>
	      				<div class="row">
	      					<div class="col-md-4">
	      						<label>Desde:</label>
	      						<input  type="date" class="form-control" id="desde" name="desde"/>
	      					</div>
	      					<div class="col-md-4">
	      						<label>Hasta:</label>
	      						<input  type="date" class="form-control" id="hasta" name="hasta"/>
	      					</div>
	      					<div class="col-md-2">
	      						<button style="margin-top: 1.4em;" type="button" class="btn btn-primary btnMenu" id="filtrofecha" onclick="filtrapolizas()">Filtrar</button>
	      					</div>
	      				</div>
	      				<div class="row">
	      					<div class="col-md-6">
	      						<label>Poliza:</label>
	      						<select id="idpolicopy" name="idpolicopy"> 
								</select>
	      					</div>
	      				</div>
	      			</section>
	      			<input type="submit" value="" id="submit" style="display: none" > 
      			</form>
      		</div>
      		<div class="modal-footer">
      			<div class="row">
      				<div class="col-md-8">
  					</div>
  					<div class="col-md-4">
  						<button class="btn btn-primary btnMenu" onclick="javascript:$('#submit').click();">Copiar</button>
  					</div>
  				</div>
      		</div>
      	</div>
    </div>
</div>