	
	<?php //include('../../netwarelog/design/css.php');?>
<!--LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
	<style type="text/css">
		.cuerpo{width: 420px; height: 380px;  padding: 7px; border: 0px solid; font-family: arial;}
		#mov{display: none;}
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
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/Auxiliar_Impuestos.js"></script>
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<script>
	$(document).ready(function() {
		$('#nmloader_div',window.parent.document).hide();
		
		$("#rad_ingr").change(function() {
			/*$(".acred100").css("display",'none');
			$(".pago_prov").css("display",'none');
			$(".tipo_op").css("display",'none');
			$(".tipo_iva").css("display",'none');*/
			$("#eg").hide('slow');
		});
		$("#rad_egr").change(function() {
			/*$(".acred100").css("display",'block');
			$(".pago_prov").css("display",'block');
			$(".tipo_op").css("display",'block');
			$(".tipo_iva").css("display",'block');*/
			$("#eg").show('slow');

		});
	});
	</script>
	
	<div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-1">
            </div>
            <div class="col-md-4 col-sm-10">
                <h3 class="nmwatitles text-center">Auxiliar de impuestos</h3>
                <form name='reporte' method='post' id='info' action='index.php?c=auxiliar_impuestos&f=reporte'>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Considerar periodo de causacion:</label>
                            <input type="checkbox" class="nminputcheck" id="considera_per" name="considera_per" value="1" checked>
                        </div>
                    </div>
                    <section id="eje">
                    	<div class="row">
	                        <div class="col-md-12">
	                            <label>Ejercicio:</label>
	                            <select id="sel_ejercicio" name="sel_ejercicio" class="form-control">
									<?php
										$res=$ejercicio->fetch_object();
										echo "<option id='ej_".$res->id."' value='".$res->id."' selected>".$res->NombreEjercicio."</option>";
										while($res=$ejercicio->fetch_object()){
											echo "<option id='ej_".$res->id."' value='".$res->id."'>".$res->NombreEjercicio."</option>";
										}
									?>
								</select>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Periodo Inicial:</label>
	                            <select id="per_ini" name="per_ini" class="form-control">
									<option id="per_ini_1" value="1" selected>Enero</option>
									<option id="per_ini_2" value="2">Febrero</option>
									<option id="per_ini_3" value="3">Marzo</option>
									<option id="per_ini_4" value="4">Abril</option>
									<option id="per_ini_5" value="5">Mayo</option>
									<option id="per_ini_6" value="6">Junio</option>
									<option id="per_ini_7" value="7">Julio</option>
									<option id="per_ini_8" value="8">Agosto</option>
									<option id="per_ini_9" value="9">Septiembre</option>
									<option id="per_ini_10" value="10">Octubre</option>
									<option id="per_ini_11" value="11">Noviembre</option>
									<option id="per_ini_12" value="12">Diciembre</option>
								</select>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Periodo Final:</label>
	                            <select id="per_fin" name="per_fin" class="form-control">
									<option id="per_fin_1" value="1" selected>Enero</option>
									<option id="per_fin_2" value="2">Febrero</option>
									<option id="per_fin_3" value="3">Marzo</option>
									<option id="per_fin_4" value="4">Abril</option>
									<option id="per_fin_5" value="5">Mayo</option>
									<option id="per_fin_6" value="6">Junio</option>
									<option id="per_fin_7" value="7">Julio</option>
									<option id="per_fin_8" value="8">Agosto</option>
									<option id="per_fin_9" value="9">Septiembre</option>
									<option id="per_fin_10" value="10">Octubre</option>
									<option id="per_fin_11" value="11">Noviembre</option>
									<option id="per_fin_12" value="12">Diciembre</option>
								</select>
	                        </div>
	                    </div>
                    </section>
                    <section id="mov">
                    	<div class="row">
	                        <div class="col-md-12">
	                            <label>Movimientos Del:</label>
	                            <input type="date" class="form-control" id="fecha_ini" name="fecha_ini" placeholder="aaaa-mm-dd" disabled>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Al:</label>
	                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" placeholder="aaaa-mm-dd" disabled>
	                        </div>
	                    </div>
                    </section>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Movimientos De:</label>
                            <input type="radio" class="nminputradio" id="rad_ingr" name="radio_mov" value="0"> Ingresos 
							<input type="radio" class="nminputradio" id="rad_egr" name="radio_mov" value="1" checked> Egresos
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Tasas de IVA:</label>
                            <select id="tasa_sel" name="tasa_sel" class="form-control">
								<option id='tasa_0' value="-" selected>Todos</option>
								<option value="16%">16%</option>
								<option value="11%">11%</option>
								<option value="0%">0%</option>
								<option value="Exenta">Exenta</option>
								<option value="15%">15%</option>
								<option value="10%">10%</option>
							</select>
                        </div>
                    </div>
                    <section id="eg">
                    	<div class="row">
	                        <div class="col-md-12">
	                            <label>Acreditar 100% del IVA retenido:</label>
	                            <input type="checkbox" class="acred100" name="acred100">
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Pagos A:</label>
	                            <select name="pago_prov" class="form-control pago_prov">
									<option value="0" selected>Todos los proveedores</option>
									<option value="1">Proveedores nacionales</option>
								</select>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Tipo de Operacion:</label>
	                            <select name="tipo_op" class="form-control tipo_op">
									<option id='tipo_op_0' value='0' selected>Todos</option>
									<?php
									while($rqry_eje=$operacion->fetch_object()){
										echo "<option id='tipo_op_".$rqry_eje->id."' value='".$rqry_eje->id."'>".$rqry_eje->tipoOperacion."</option>";	
									}
									?>
								</select>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <label>Tipo para IVA:</label>
	                            <select name="tipo_iva" class="form-control tipo_iva">
									<option id='tipo_iva_0' value='0' selected>Todos</option>
									<?php
										while($rqry_eje=$tipoIVA->fetch_object()){
											echo "<option id='tipo_iva_".$rqry_eje->id."' value='".$rqry_eje->id."'>".$rqry_eje->tipoiva."</option>";	
										}
									?>
								</select>
	                        </div>
	                    </div>
                    </section>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                        	<input type="submit" class="btn btn-primary btnMenu" value="Ejecutar Reporte">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 col-sm-1">
            </div>
        </div>
    </div>