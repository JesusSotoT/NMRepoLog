<?php
	//include("../../../netwarelog/catalog/conexionbd.php");
?>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/rep_fiscal.css">
	<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<?php include('../../netwarelog/design/css.php');?>
<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
		.cuerpo{width: 520px; height: auto;  padding: 7px; font-family: arial;}
		.tamanoSel{width: 200px;	text-overflow: ellipsis;}
		#mov{display:none;}		
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
	<script src="../cont/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function()
	{
		$('#nmloader_div',window.parent.document).hide();
		desbloqueaProv(0);
	});
		function valida(f)
		{
			if(!$('#periodoAcreditamiento:checked').val() &&  f.fecha_ini.value == '')
			{
				alert("Falta la fecha de inicio.");
				f.fecha_ini.focus();
				return false;
			}

			if(!$('#periodoAcreditamiento:checked').val() && f.fecha_fin.value == '')
			{
				alert("Falta la fecha fin.");
				f.fecha_fin.focus();
				return false;
			}

			if($('#provalgunos:checked').val() && $('#pinicial').val() > $('#pfinal').val())
			{
				alert("El proveedor inicial no puede ser menor al proveedor final");
				f.pinicial.focus();
				return false;
			}

		}

		function cambiaRango()
		{
			
			if($('#periodoAcreditamiento:checked').val())
			{
				$('#ejercicio').removeAttr('disabled')
				$('#periodo_inicio').removeAttr('disabled')
				$('#periodo_fin').removeAttr('disabled')
				$('#fecha_ini').attr('disabled','disabled')
				$('#fecha_fin').attr('disabled','disabled')
				$('#mov').hide('slow')
				$('#eje').show('slow')	
			}
			else
			{
				$('#ejercicio').attr('disabled','disabled')
				$('#periodo_inicio').attr('disabled','disabled')
				$('#periodo_fin').attr('disabled','disabled')
				$('#fecha_ini').removeAttr('disabled')
				$('#fecha_fin').removeAttr('disabled')
				$('#eje').hide('slow')
				$('#mov').show('slow')
			}
		}

		function desbloqueaProv(valor)
		{
			if(valor)
			{
				$('#pinicial').removeAttr('disabled')
				$('#pfinal').removeAttr('disabled')
				$('#verPro').show('slow')
			}
			else
			{
				$('#pinicial').attr('disabled','disabled')
				$('#pfinal').attr('disabled','disabled')
				$('#verPro').hide('slow')
			}
		}
	</script>
</head>
<body>

	<div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-1">
            </div>
            <div class="col-md-4 col-sm-10">
                <h3 class="nmwatitles text-center">Auxiliar del Formato A29</h3>
                <form name='reporte' method='post' id='info' action='index.php?c=auxiliar_a29&f=VerReporte' onsubmit='return valida(this)'>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Considerar periodo de acreditamiento:</label>
                            <input type='checkbox' value='1' id='periodoAcreditamiento' name='periodoAcreditamiento' onchange='cambiaRango()' checked>
                        </div>
                    </div>
                    <section id='eje'>
                    	<div class="row">
	                        <div class="col-md-12">
	                            <label>Ejercicio:</label>
	                        	<select name='ejercicio' id='ejercicio' class="form-control">
									<?php 
									while($p = $periodos->fetch_object())
									{
										echo "<option value='".$p->Id."'>".$p->NombreEjercicio."</option>";
									}
									?>
								</select>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Movimientos Del:</label>
	                        	<select name='periodo_inicio' id='periodo_inicio' class="form-control">
									<option value='1'>Enero</option>
									<option value='2'>Febrero</option>
									<option value='3'>Marzo</option>
									<option value='4'>Abril</option>
									<option value='5'>Mayo</option>
									<option value='6'>Junio</option>
									<option value='7'>Julio</option>
									<option value='8'>Agosto</option>
									<option value='9'>Septiembre</option>
									<option value='10'>Octubre</option>
									<option value='11'>Noviembre</option>
									<option value='12'>Diciembre</option>
								</select>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Al:</label>
	                        	<select name='periodo_fin' id='periodo_fin' class="form-control">
									<option value='1'>Enero</option>
									<option value='2'>Febrero</option>
									<option value='3'>Marzo</option>
									<option value='4'>Abril</option>
									<option value='5'>Mayo</option>
									<option value='6'>Junio</option>
									<option value='7'>Julio</option>
									<option value='8'>Agosto</option>
									<option value='9'>Septiembre</option>
									<option value='10'>Octubre</option>
									<option value='11'>Noviembre</option>
									<option value='12'>Diciembre</option>
								</select>
	                        </div>
	                    </div>
                    </section>
                    <section id='mov'>
                    	<div class="row">
	                        <div class="col-md-12">
	                            <label>Movimientos Del:</label>
	                        	<input type="date" class="form-control" id="fecha_ini" name='fecha_ini' placeholder="aaaa-mm-dd" disabled>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Al:</label>
	                        	<input type="date" class="form-control" id="fecha_fin" name='fecha_fin' placeholder="aaaa-mm-dd" disabled>
	                        </div>
	                    </div>
                    </section>
                    <div class="row">
                        <div class="col-md-12">
                        	<fieldset>
								<legend style="margin-bottom: unset; border: unset;">Proveedores a imprimir</legend>
								<input type='radio' name='prov' id='provtodos' onclick="desbloqueaProv(0)" value='todos' checked> Todos<br />
								<input type='radio' name='prov' id='provalgunos' onclick="desbloqueaProv(1)" value='algunos'> Algunos
							</fieldset>
                        </div>
                    </div>
                    <section id='verPro'>
                    	<div class="row">
	                        <div class="col-md-12">
	                            <label>Proveedor Inicial:</label>
	                        	<select name='pinicial' id='pinicial' class='form-control'>
									<?php 
										while($p = $proveedores1->fetch_object())
										{
											echo "<option value='".$p->idPrv."'>".$p->idPrv."/".$p->razon_social."</option>";
										}
									?>
								</select>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Proveedor Final:</label>
	                        	<select name='pfinal' id='pfinal' class='form-control'>
									<?php 
										while($p = $proveedores2->fetch_object())
										{
											echo "<option value='".$p->idPrv."'>".$p->idPrv."/".$p->razon_social."</option>";
										}
									?>
								</select>
	                        </div>
	                    </div>
                    </section>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <input type="submit" onclick="$('#nmloader_div',window.parent.document).show();" class="btn btn-primary btnMenu" value="Ejecutar Reporte">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 col-sm-1">
            </div>
        </div>
    </div>

			
</body>
</html>