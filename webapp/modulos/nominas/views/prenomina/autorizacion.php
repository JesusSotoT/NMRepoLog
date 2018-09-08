<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/autorizacion.js"></script>
</head>
<body><br>
	<div class="container well" style="width: 90%">
		<h3 align="center"> Autorización de Nomina </h3><hr><br>
		<div class="alert alert-danger col-md-12">
	        <button type="button" class="close" data-dismiss="alert">
	            <span aria-hidden="true">×</span>
	            <span class="sr-only">Cerrar</span>
	        </button>
	         <i class="fa fa-info-circle fa-lg"></i> 
	         INFORMACION.<br>
	         Si desea autorizar otro periodo diferente al actual debe cambiarlo en <a href="" title="Ir a Configuracion" onclick="irConfiguracion()"><b>Configuracion</b></a>
	         
	   </div>
		<div class="col-md-12 alert alert-success" align="center">
				<b style="font-size: 17px;">Periodo activo para Autorizar</b><hr><br>
				<input type="hidden" id="idnomina" value="<?php echo $periodoactual['idnomp']; ?>" />
				<input type="hidden" id="fechafin" value="<?php echo $periodoactual['fechafin']; ?>" />
				<input type="hidden" id="fechainicio" value="<?php echo $periodoactual['fechainicio']; ?>" />
				<input type="hidden" id="idtipoperiodo" value="<?php echo $periodoactual['idtipop']; ?>" />
				<input type="hidden" id="numnomina" value="<?php echo $periodoactual['numnomina']; ?>" />
				<div class="col-md-4 alert alert-info" align="center">
					<b><?php echo $periodoactual['nombre']; ?></b><br>
					<b><?php echo "Nomina ".$periodoactual['numnomina']." <br>".$periodoactual['fechainicio']." al ".$periodoactual['fechafin']; ?></b><br><br>
					<button title="Autorizar nomina" type="button" class="btn btn-info" id="auto" data-loading-text="<i class='fa fa-cog fa-spin fa-3x fa-fw margin-bottom'></i>"><i class="fa fa-cogs" aria-hidden="true"></i> Autorizar</button>
				</div>
		</div>
	</div>
</body>
</html>