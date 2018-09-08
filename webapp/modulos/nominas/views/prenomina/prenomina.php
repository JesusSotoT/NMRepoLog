<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/prenomina.js"></script>
	<link   rel="stylesheet" type="text/css" href="css/prenomina.css" />
	<link   rel="stylesheet" type="text/css" href="css/estilomodal.css" /> 
	<script src="../../libraries/dataTable/js/datatables.min.js"></script>
	<link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css"> 
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
<script>
	$(document).ready(function(){
		$("#tdp").attr("colspan", parseInt($("#numconf").val()) + parseInt(2));
	});
</script>
<body><br>
	<div class="container well" style="width: 95%">
		<h3 align="center"> 	Prenomina </h3><hr>
		<a onclick="javascript:irConfiguracion()" style="cursor: pointer">
			<b>Configuracion Prenomina.</b>
		</a>
		<section>
			<div class="col-md-3" style="overflow: auto;height: 200px; ">	
				<ul class="nav">
					<?php 
					if( $nominasPeriodo->num_rows>0 ){ 
						if ( $p = $nominasPeriodo->fetch_object() ){?>
						<li>
							<a data-toggle="tab"  class="" href="" onclick="javascript:contenidoPrenomina(<?php echo ($p->idnomp); ?>,'<?php echo $p->fechafin;?>','<?php echo $p->fechainicio;?>',<?php echo $p->idtipop;?>)">
								<?php echo $p->numnomina."  .-   (".$p->fechainicio." - ".$p->fechafin.")"; ?>
							</a>
						</li>
						
						<script>
						$(document).ready(function(){
							contenidoPrenomina(<?php echo ($p->idnomp); ?>,'<?php echo $p->fechafin;?>','<?php echo $p->fechainicio;?>',<?php echo $p->idtipop;?>);
						});
						</script>
						<?php 
						}
					}else{?>
					<li> 
						<a data-toggle="tab" href="">
							No tiene periodos
						</a>
					</li>
					<?php } ?>
				</ul>
				<input type="hidden" id="numnomina">
				<input type="hidden" id="fechafin">
				<input type="hidden" id="fechainicio">
				<input type="hidden" id="idtipop">
				
			</div>
			
		</a>
		</section>
		<section>
			<!-- ACCIONES DE LA NOMINA -->
			<div align="right">
				<a onclick="javascript:irAutorizacion()" style="cursor: pointer" title="Ir a proceso de autorizacion de nomina">
					<b>Autorizar Nomina.</b>
				</a>
				<button  title="Calcular Nomina" type="button" class="btn btn-info" id="prenomina" data-loading-text="<i class='fa fa-cog fa-spin fa-3x fa-fw margin-bottom'></i>"><i class="fa fa-cogs" aria-hidden="true" ></i> Calcular Nomina</button>
			</div>
			<!-- FIN ACCIONES DE LA NOMINA -->
			<div id="" class="table-responsive col-md-9 alert alert-info" >
				<table  id="tablaprenomina" cellpadding="0" class="table table-striped table-over table-bordered" style="border:solid 1px;" >
					<!-- <table id="tabla" class="table table-bordered table-hover" > -->
					<thead class="" title="Deslizar para ver mas..."><!-- <thead title="Deslizar para ver mas..."> -->
						<tr style="background: #6E6E6E; color: #F5F7F0" align="center">
							<td id="tdp" align="center"   style="background:#6E6E6E; color: #F5F7F0;border:solid 0px;"; >
								<b id="periodo" style="font-size:14px;width=100%">PERIODO</b>
							</td>
						</tr>
						<!-- <tr style="background: #6E6E6E; color: #F5F7F0"> -->
						<tr style="border:solid 0px;background:#6E6E6E; color:#F5F7F0" id="trHeader">
							<th ><b>CODIGO EMPLEADO</b></th>
							<th ><b>NOMBRE EMPLEADO</b>
								<input type="hidden" id="numconf" value="<?php echo $conceptosConfig->num_rows;?>"></th>
								<?php while($c = $conceptosConfig->fetch_object()){?>	
								<th align="center"><b><?php echo  strtoupper($c->descripcion); ?></b></th>			
								<?php } ?>
								<th align="right" style="height:40px;width:110px;border:solid  1px;">NETO A PAGAR</th>
							</tr>
						</thead>
						<tbody class="" style="" id="contenidop">		
						</tbody>
					</table>
				</div>
			</section>
		</div>
		<div id="menu" style="display: none">
			<ul>
				<li id="sobre" >Abrir Sobre-Recibo</li>
				<li id="emple" >Ver empleado</li>
			</ul>
		</div>
	</body>

	<script type="text/javascript">
	
// var table = $('#tablaprenomina').DataTable();
		// table.destroy();
		// setTimeout(function(){
			// $('#tablaprenomina').DataTable( {
				// "language": {
					// "url": "js/Spanish.json"
				// }
			// })
		// }, 1000);
// 
// 		

	</script>
	</html>