<!DOCTYPE >
<html>
<head>
	<meta charset="utf-8">
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/conceptos.js"></script>
</head>
<script>
$(document).ready(function(){
<?php
if(isset($datos)){ ?>
tipoconcepto(1);		
var horas="<?php echo $datos->idhora; ?>";
var pago="<?php echo $datos->idFormapago; ?>";
$.each(horas.split(","), function(i,e){
	$("#idhora option[value='" + e + "']").prop("selected", true);
});
$.each(pago.split(","), function(i,e){
	$("#idFormapago option[value='" + e + "']").prop("selected", true);
});
<?php

if($datos->global == 1){?>
	$("#global").prop("checked",true);

	<?php	}

if($datos->liquidacion == 1){?>
	$("#liquidacion").prop("checked",true);	
	<?php	}

if($datos->especie == 1){ ?>
	$("#especie").prop("checked",true);
	<?php	} 

$funcion = "&opc=0"; 
if($datos->idtipo != 3){//percepcion
?>
$.post("../../modulos/nominas/ajax.php?c=Catalogos&f=listapercepdeduc&t="+<?php echo $datos->idtipo;?>,//select llena tipo operacion
function(data) 
{
$("#idAgrupador").empty();
$("#idAgrupador").html("<option value='0'>-----</option>");
$("#idAgrupador").append(data).val(<?php echo $datos->idAgrupador;?>).selectpicker("refresh");
if($("#idtipo").val() == 1){
if($("#idAgrupador").val() == 16){ //si del listado selecciona horas extras
$("#idhora").attr("disabled",false).selectpicker("refresh");//mostrara el catalogo de horas extras
}else{
$("#idhora").val(0).attr("disabled",true).selectpicker("refresh");
}
}
});
<?php	}
} else{
$funcion = "&opc=1"; ?>	

tipoconcepto(0);			
<?php		} ?>

liquiglobal();
especies();


});


</script>
<body>
	<div class="container">
		<form id="formconceptos" action="ajax.php?c=Catalogos&f=almacenaConcepto<?php echo $funcion;?>" method="post">
			<input type="hidden"  value="<?php if(isset($datos)){ echo $datos->idconcepto; } ?>" id="idconcepto" name="idconcepto"/>

			<ul class="nav nav-tabs">
			</li>
			<li>
				<a data-toggle="tab" href="">
					<a data-toggle="tab"  href=""  onclick="atraslistado()" title="Regresar listado">
						<i class="fa fa-arrow-left" aria-hidden="true"  ></i> Regresar
					</a>
				</a>
			</li>
			<li>
				<a data-toggle="tab" href="">
					<a data-toggle="tab"  href=""  onclick="Guardar()" title="Guardar concepto">
						<i class="fa fa-floppy-o" aria-hidden="true" id="guarda" ></i> Guardar
						<i class='fa fa-refresh fa-spin ' id="carga" style="display: none"></i>
					</a>
				</a>
			 </li>
		    </ul>

		<div class="tab-content">
			<div id="general" class="tab-pane fade in active">
				<div class="alert alert-warning">
					<div class="row">
						<div class="col-md-12">
							<div class="col-xs-3">
								Concepto:<b style="color:red">*</b>
								<input type="text"<?php if ($consulte==1) { ?> disabled<?php } ?> id="codigo" name="codigo" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->concepto; }  ?>"/>
							</div>
							<div class="col-xs-3">
								Tipo:
								<select id="idtipo" type="select"<?php if ($consulte==1) { ?> disabled<?php } ?> name="idtipo" class="selectpicker" data-width="100%" data-live-search="true" onchange="tipoconcepto(<?php if(isset($datos)){ echo $datos->idconcepto; }else{ echo 0; }  ?>);">

									<?php while ($e = $tipoconcepto->fetch_object()){ $f="";
									if(isset($datos)){ if ($e->idtipo == $datos->idtipo ){  $f="selected";} } ?>
									<option value="<?php echo $e->idtipo;?>" <?php echo $f; ?>><?php echo $e->tipo;?> </option>
									<?php } ?>
								</select>
							</div>
							<div class="col-xs-3">
								Descripcion:<b style="color:red">*</b>
								<input type="text"<?php if ($consulte==1) { ?> disabled<?php } ?> id="descripcion" name="descripcion" class="form-control input-md"  value="<?php if(isset($datos)){ echo $datos->descripcion; }  ?>"/>
							</div>
							<div class="col-xs-3">
								<input type="checkbox" name="global" id="global" value="0"  onclick="liquiglobal()"/>Automatico global
							</div>
							<div class="col-xs-3">
								<input type="checkbox" name="liquidacion" id="liquidacion" value="0"  onclick="liquiglobal()"/>Automatico liquidacion
							</div>
							<div class="col-xs-3">
								<input type="checkbox" name="especie" id="especie" value="0" onclick="especies();"/>Especie
							</div>
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-12">
							<div class="col-xs-3">
								Clave agrupadora SAT:<b style="color:red">*</b>
								<select id="idAgrupador" name="idAgrupador" class="selectpicker" data-width="100%" data-live-search="true" onchange="sat();">
								</select>
							</div>
							<div class="col-xs-3">
								Metodo de pago:
								<select id="idFormapago" name="idFormapago[]" multiple="" class="selectpicker" data-width="100%" data-live-search="true">

									<?php while ($e = $formapago->fetch_object()){ $f="";
									if(isset($datos)){ if ($e->idFormapago == $datos->idFormapago ){  $f="selected";} } ?>
									<option value="<?php echo $e->idFormapago;?>" <?php echo $f; ?>><?php echo $e->nombre;?> </option>
									<?php } ?>
								</select>
							</div>
							<div class="col-xs-3">
								Tipo hora extra:
								<select id="idhora" name="idhora[]" multiple="" class="selectpicker" data-width="100%" data-live-search="true">

									<?php while ($e = $horasext->fetch_object()){ $f="";?>
									<option value="<?php echo $e->idhora;?>" ><?php echo $e->descripcion;?> </option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<br><br>
				</div>
			</div>
		</form>
	</div>
</body>
</html>