<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="js/concentradoivaprove.js"></script>
		<link rel="stylesheet" type="text/css" href="css/moviprove.css"/>
		<link href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
		<style type="text/css">
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
	</head>
	<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-1">
            </div>
            <div class="col-md-4 col-sm-10">
                <section id='confi'>
                    <h3 class="nmwatitles text-center">Concentrado de IVA por Proveedor</h3>
                    <form name='reporte' id='info' method='post' action='index.php?c=EgresosSinIva&f=VerReporte' onsubmit='return valida(this)'>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Considerar periodo de acreditamiento:</label>
                                <input type="checkbox" class="nminputcheck" id='acreditamiento'   onclick="funcion();" checked=""/>
                                <input type="hidden" id='cred' value=1>
                            </div>
                        </div>
                        <section id="eje">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Ejercicio:</label>
                                    <select id='ejercicio' class="form-control">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Periodo Del:</label>
                                    <select id="delperiodo"  class="form-control">
                                        <option selected value='1'>Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Ejercicio:</label>
                                    <select id="alperiodo"  class="form-control" >
                                        <option selected value='1'>Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                            </div>
                        </section>
                        <section id="mov">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Movimientos Del:</label>
                                    <input type="date" class="form-control" id="inicio"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Al:</label>
                                    <input type="date" class="form-control" id="fin" />
                                </div>
                            </div>
                        </section>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Provedores a:</label>
                                <input type="radio" class="nminputradio" name='prove' value='1' onclick="muestra();" checked=""> Todos
                                <input type="radio" class="nminputradio" name='prove' value='2' onclick="muestra();"> Algunos
                            </div>
                        </div>
                        <section id='label' style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Ejem. 1-10,21,51</label>
                                    <input type="text" class="form-control" id='algunos' style='display: none;'>
                                </div>
                            </div>
                        </section>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Ver todas las tasas:</label>
                                <input type="checkbox" class="nminputcheck" id='tasas'    checked=""/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Mostrar todos lo que aplican:</label>
                                <input type="checkbox" class="nminputcheck" id='aplica' value="1" checked=""/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Usar Fecha de Impresión:</label>
                                <input type="checkbox" class="nminputcheck" id='fecha'   onclick="fecImp();" checked=""/>
                            </div>
                        </div>
                        <section id="fimpr">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Fecha de Impresión:</label>
                                    <input type="text" class="form-control" id="inicio2" value="<?php echo date('Y-m-d');?>"/>
                                </div>
                            </div>
                        </section>
                        <div class="row">
                            <div class="col-md-7 col-md-offset-5">
                                <input type="button" class="btn btn-primary btnMenu" id='ejecutar' title="EJECUTAR REPORTE" value='Ejecutar Reporte <F10>' onclick="concentra();">
                            </div>
                        </div>
                    </form>
                </section>
            </div>
            <div class="col-md-4 col-sm-1">
            </div>
        </div>
    </div>

	<div id="acredita" style="display: none;"></div>	
	<div id='detallado' style="display: none;"></div>	
	</body>
</html>