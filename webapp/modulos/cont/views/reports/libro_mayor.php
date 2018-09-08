<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../../webapp/netwarelog/repolog/js/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="../../../webapp/netwarelog/catalog//css/view.css" />
<link rel="stylesheet" type="text/css" href="../../../webapp/netwarelog/utilerias/css_repolog/estilo-1.css" />
<?php include('../../netwarelog/design/css.php');?>
<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
<link rel="stylesheet" type="text/css" href="css/style.css" />
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
  	.modal-title{
  		background-color: unset !important;
  		padding: unset !important;
  	}
  	.nmwatitles, [id="title"] {
      	padding: 8px 0 3px !important;
     	background-color: unset !important;
  	}
  	.select2-container{
      	width: 100% !important;
  	}
  	.select2-container .select2-choice{
      	background-image: unset !important;
     	height: 31px !important;
  	}
  	.twitter-typeahead{
  		width: 100% !important;
  	}
  	.tablaResponsiva{
        max-width: 100vw !important; 
        display: inline-block;
    }
    #info label {
	    margin-right: unset !important;
	    text-align: unset !important;
	    width: unset !important;
	}
</style>
<script languahe='javascript'>

function valida(f)
		{
			
			if(!$('#cuentas').val())
			{
				alert("Elija al menos una cuenta");
				f.cuentas.focus();
				return false;
			}
			
			if($('#cuentas').val().length > 2 && f.rango.checked)
			{
				alert("El Rango requiere solo dos cuentas");
				f.cuentas.focus();
				return false;
			}
			
			if($('#cuentas').val().length < 2 && f.rango.checked)
			{
				alert("El Rango requiere de dos cuentas ");
				f.cuentas.focus();
				return false;
			}
			var anterior = $("#f3_3").val()+$("#f3_1").val()+$("#f3_2").val()
			var posterior = $("#f4_3").val()+$("#f4_1").val()+$("#f4_2").val()
			if(parseInt(anterior) > parseInt(posterior))
			{
				alert("La fecha inicial no puede ser posterior a la fecha final.");
				f.f3_2.focus();
				return false;
			}
			
		}
</script>
<?php
if(intval(isset($_GET['t'])))
{
	$titulo = "De Mayor";
	$otro = "Afectables";
	$liga = "index.php?c=Reports&f=libro_mayor";
}
else
{
	$titulo = "Afectables";
	$otro = "De Mayor";
	$liga = "index.php?c=Reports&f=libro_mayor&t=1";
}

//Nuevo commit
?>


<div class="container">
	<div class="row">
		<div class="col-md-4 col-sm-1">
		</div>
		<div class="col-md-4 col-sm-10">
			<h3 class="nmwatitles text-center">Libro de Mayor (<?php echo $titulo; ?>)</h3>
			<form name='mov' method='post' id='info' action='index.php?c=Reports&f=libro_mayor_despues' onsubmit='return valida(this)'>
				<div class="row">
					<div class="col-md-12">
						<strong><a href='<?php echo $liga; ?>' >Por Cuentas <?php echo $otro; ?></a></strong>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
					<input type='hidden' id='cuentas' name='cuentas' value='todos'>
					<input type='hidden' id='rango' name='rango' value='0'>
					<input type='hidden' id='segmento' name='segmento' value='todos'>
					<input type='hidden' id='sucursal' name='sucursal' value='todos'>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<label>Del:</label><br>
						<input style="width:20% !important; display: inline;" id="f3_2" name="f3_2" title="Día" size="2" maxlength="2" value="<?php echo date("d"); ?>" type="text" class="form-control"> / 
						<input style="width:20% !important; display: inline;" id="f3_1" name="f3_1" title="Mes" size="2" maxlength="2" value="<?php echo date("m"); ?>" type="text" class="form-control"> / 
						<input style="width:20% !important; display: inline;" id="f3_3" name="f3_3" title="Año" size="4" maxlength="4" value="<?php echo date("Y"); ?>" type="text" class="form-control">
						<img id="f3_img" class="datepicker" src="../../../webapp/netwarelog/repolog/img/calendar.gif" alt="Seleccione una fecha." title="Haga clic para seleccionar una fecha.">		
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label>Al:</label><br>
						<input style="width:20% !important; display: inline;" id="f4_2" name="f4_2" title="Día" size="2" maxlength="2" value="<?php echo date("d"); ?>" type="text" class="form-control"> / 
						<input style="width:20% !important; display: inline;" id="f4_1" name="f4_1" title="Mes" size="2" maxlength="2" value="<?php echo date("m"); ?>" type="text" class="form-control"> / 
						<input style="width:20% !important; display: inline;" id="f4_3" name="f4_3" title="Año" size="4" maxlength="4" value="<?php echo date("Y"); ?>" type="text" class="form-control">
						<img id="f4_img" class="datepicker" src="../../../webapp/netwarelog/repolog/img/calendar.gif" alt="Seleccione una fecha." title="Haga clic para seleccionar una fecha.">
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label>Saldos:</label>
						<input type='checkbox' id="saldos" name="saldos" title="Saldos" value='1'>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label>No incluir Cuentas con Saldo Finales en Cero:</label>
						<input type='checkbox' id="saldosFin" name="saldosFin" title="Saldos Finales" value='1'>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-md-offset-6">
						<input type='hidden' name='tipo' value='<?php echo $_GET['t']; ?>'>
						<input type='submit' name='envia' class="btn btn-primary btnMenu">
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-4 col-sm-1">
		</div>
	</div>
</div>

<script type="text/javascript">
															Calendar.setup({
																inputField	 : 'f3_3',
																baseField    : 'f3',
																displayArea  : 'f3_area',
																button		 : 'f3_img',
																ifFormat	 : '%B %e, %Y',
																onSelect	 : selectDate
															});

															Calendar.setup({
																inputField	 : 'f4_3',
																baseField    : 'f4',
																displayArea  : 'f4_area',
																button		 : 'f4_img',
																ifFormat	 : '%B %e, %Y',
																onSelect	 : selectDate
															});
														</script>
