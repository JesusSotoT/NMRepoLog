<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/rep_fiscal.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
		.cuerpo{width: 420px; height: 250px;  padding: 7px; border: 0px solid; font-family: arial;}
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
	<script type="text/javascript" src="js/resumenGeneralR21.js"></script>
</head>
<body>

	<div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-1">
            </div>
            <div class="col-md-4 col-sm-10">
                <h3 class="nmwatitles text-center">Resumen General R21</h3>
                <section id='info'>
                	<div class="row">
	                    <div class="col-md-12">
	                        <label>Ejercicio:</label>
	                    	<select id="sel_ejercicio" class="form-control">
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
	                    	<select id="per_ini" class="form-control">
								<option id="per_ini_1" value="1"selected>Enero</option>
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
	                    	<select id="per_fin" class="form-control">
								<option id="per_fin_1" value="1"selected>Enero</option>
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
	                <div class="row">
	                    <div class="col-md-12">
	                        <label>Acreditar 100% del IVA retenido:</label>
	                    	<input type="checkbox" id="considera_per" >
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-md-12">
	                        <label>Proporción:</label>
	                    	<input type="text" class="form-control" value="0.0000" id="prop">
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-md-12">
	                        <label>Usar Proporción:</label>
	                    	<select id="use_prop" class="form-control">
								<option value="1">Conforme articulo 5 LIVA</option>
								<option value="2">Conforme articulo 5-B LIVA</option>
							</select>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-md-12">
	                        <label>Tasas de Iva:</label>
	                    	<select  class="form-control">
								<option id='0' value="0" selected>Todos</option>
									<?php
										while($rqry_eje=$tasaIVA->fetch_object()){
											echo "<option id='tasa_".$rqry_eje->id."' value='".$rqry_eje->valor."'>".$rqry_eje->tasa."</option>";	
										}
									?>
								<option>Otra tasa</option>
							</select>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-md-12">
	                        <label>Resumen R21:</label>
	                    	<input type="radio" class="nminputradio" name="sel_rep" id="rep1" value="1" checked>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-md-12">
	                        <label>Exportar a excel:</label>
	                    	<input type="checkbox" class="nminputcheckbox" id="toexcel" value="1">
	                    </div>
	                </div>
	                <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                        	<input type="button" class="btn btn-primary btnMenu" value="Ejecutar Reporte" onclick="reporte_post()">
                        </div>
                    </div>
	            </section>
	        </div>
	        <div class="col-md-4 col-sm-1">
            </div>
        </div>
    </div>

	
<div id="div_reporte"></div>
</body>
</html>