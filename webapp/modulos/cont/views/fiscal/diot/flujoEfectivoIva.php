<?php
	//include("../../../netwarelog/catalog/conexionbd.php");
?>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/rep_fiscal.css">
	<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
		.cuerpo{width: 520px; height: auto  padding: 7px; font-family: arial;}
		.tamanoSel{width: 200px;	text-overflow: ellipsis;}
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
		desbloqueaFlux(0);
	});
		function valida(f)
		{
			if(f.fecha_ini.value == '')
			{
				$('#nmloader_div',window.parent.document).hide();
				alert("Falta la fecha de inicio.");
				f.fecha_ini.focus();
				return false;
			}

			if(f.fecha_fin.value == '')
			{
				$('#nmloader_div',window.parent.document).hide();
				alert("Falta la fecha fin.");
				f.fecha_fin.focus();
				return false;
			}

			if($('#provalgunos:checked').val() && $('#finicial').val() > $('#ffinal').val())
			{
				$('#nmloader_div',window.parent.document).hide();
				alert("La cuenta inicial no puede ser menor a la cuenta final");
				f.finicial.focus();
				return false;
			}

		}


		function desbloqueaFlux(valor)
		{
			if(valor)
			{
				$('#finicial').removeAttr('disabled')
				$('#ffinal').removeAttr('disabled')
				$('#ver').show('slow')

			}
			else
			{
				$('#finicial').attr('disabled','disabled')
				$('#ffinal').attr('disabled','disabled')
				$('#ver').hide('slow')
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
                <h3 class="nmwatitles text-center">Conciliaci&oacute;n de Flujo de Efectivo e IVA para DIOT.</h3>
                <form name='reporte' method='post' id='info' action='index.php?c=flujoEfectivoIva&f=VerReporte' onsubmit='return valida(this)'>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Polizas Del:</label>
                            <input type="date" class="form-control" id="fecha_ini" name='fecha_ini' placeholder="aaaa-mm-dd">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Al:</label>
                            <input type="date" class="form-control" id="fecha_fin" name='fecha_fin' placeholder="aaaa-mm-dd">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
								<legend style="margin-bottom: unset; border: unset;">Ctas de Flujo de Efectivo a imprimir</legend>
								<input type='radio' class="nminputradio" name='prov' id='provtodos' onclick="desbloqueaFlux(0)" value='todos' checked> Todos<br />
								<input type='radio' class="nminputradio" name='prov' id='provalgunos' onclick="desbloqueaFlux(1)" value='algunos'> Algunos
							</fieldset>
                        </div>
                    </div>
                    <section id="ver">
                    	<div class="row">
	                        <div class="col-md-12">
	                            <label>Cuenta Inicial:</label>
	                            <select name='finicial' id='finicial' class='form-control'>
									<?php 
										while($f = $flujo1->fetch_object())
									{
										echo "<option value='$f->account_id'>$f->manual_code / $f->description</option>";
									}
										?>
									
								</select>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Cuenta Final:</label>
	                            <select name='ffinal' id='ffinal' class='form-control'>
									<?php 
										while($f = $flujo2->fetch_object())
									{
										echo "<option value='$f->account_id'>$f->manual_code / $f->description</option>";
									}
										?>
								</select>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Imprimir detalle por proveedor:</label>
	                            <input type='checkbox' class="nminputcheck" name='impDetalleProv' id='impDetalleProv' value='1'>
	                        </div>
	                    </div>
                    </section>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Mostrar s&oacute;lo las que aplican:</label>
                            <input type='checkbox' class="nminputcheck" name='soloAplican' id='soloAplican' value='1'>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <input class="btn btn-primary btnMenu" onclick="$('#nmloader_div',window.parent.document).show();" type="submit" value="Ejecutar Reporte">
                        </div>
                    </div>
                </form>
                <div class="col-md-4 col-sm-1">
                </div>
            </div>
        </div>
    </div>

	
</body>
</html>