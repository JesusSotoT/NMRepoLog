<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/declaracionr54.js"></script>
	<!--link rel="stylesheet" type="text/css" href="css/moviprove.css"/-->
	<link href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
	<!--div id='confi' -->
		
		<div class="repTitulo">Declaracion R54 Impuesto Empresarial a Tasa Unica.</div><br>

		<div class="per2">
			<form method="post" id="info" action="index.php?c=Declaracionr54&f=ejecutareporte">
			<div class="per2left">
				<ul>
					<li><label>Ejercicio</label> <select id='ejercicio' name="ejercicio" class="nminputselect">
                	<?php
                	while($eje = $ejercicio->fetch_array()){ ?>
                		<option value="<?php echo $eje['Id']; ?>"><?php echo $eje['NombreEjercicio']; ?></option>
                <?php }
                	?>
                </select></li>
					<li><label>Periodo Inicial</label><select style="margin-right: 10%;" id="delperiodo" name="delperiodo" class="nminputselect">
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
                	
                </select></li>
					<li><label>Estimulo en Operaciones con Publico en General del Periodo</label><input type="text" id="estimuloactual" name="estimuloactual" value="0"></li>
					<li><label>Credito Fiscal por deducciones mayores a los ingresos</label><input type="text" id="deduccioningre" name="deduccioningre" value="0"></li>
					<li><label>Credito Fiscal de inventarios</label><input type="text" id="inventarios" name="inventarios" value="0"></li>
					<li><label>Credito Fiscal sobre perdidas fiscales(Regimen simplificado)</label><input type="text" id="perdidas" name="perdidas" value="0"></li>
					<li><label>Acreditamiento de pagos ISR ante oficinas autorizadas</label><input type="text" id="ISRautorizadas" name="ISRautorizadas" value="0"></li>
					<li><label>Acreditamiento de pagos ISR retenido</label><input type="text" id="ISRretenido" name="ISRretenido" value="0"></li>
					<li><label>Pagos provisionales de IETU anteriores</label>
	            <input type="text" id="proviIETU" name="proviIETU" value="0"></li>
	            	<li><label>Otras cantidades a cargo del contribuyente</label><input type="text" id="cargo" name="cargo" value="0"></li>
	            	<li><input type="hidden" id="excel" name="excel" value="0" />
	            <input type="submit" value="Ejecutar Reporte" class="nminputbutton"  ></li>
				</ul>
			</div>

			<div class="per2right">
				<ul>
					<li><label>Listado de conceptos</label><input type="checkbox" id="detalle" name="detalle"  value="1"/></li>
					<li><label>Lleva a cabo operaciones de maquila.</label><select id="maquilaselect" name="maquilaselect">
	  				<option value="1" selected>NO</option>
	  				<option value="2" >SI</option>
	  			</select></li>
					<li><label >Estimulo Fiscal en Operaciones de Periodos Anteriores</label>
				<input type="text" id="estimuloanterior" name="estimuloanterior" value="0"></li>
					<li><label >Credito Fiscal por inversiones (1998-2007)</label>
				<input type="text" id="inversiones" name="inversiones" value="0"></li>
					<li><label >Credito Fiscal de deduccion inmediata/perdida</label>
				<input type="text" id="inmediataperdida" name="inmediataperdida" style="" value="0"></li>
					<li><label >Credito Fiscal por enajenacion a plazos</label>
				<input type="text" id="enajenacion" name="enajenacion" value="0"></li>
					<li><label >Acreditamiento pagos ISR entregados a la controladora</label>
				<input type="text" id="ISRentregados" name="ISRentregados" style="" value="0"></li>
					<li><label >Acreditamiento para empresas maquiladoras</label>
				<input type="text" id="maquiladoras" name="maquiladoras" style="" value="0"></li>
					<li></li>
					<li><label >Otras cantidades a favor del contribuyente</label>
				<input type="text" id="favor" name="favor" style="" value="0"></li>
				</ul>
			</div>
		</form>
		</div>

        <!--div id="pestanas">
            <ul id="lista">
                <li id="pestana1"><a href='javascript:cambiarPestana(pestanas,pestana1);'>Principal</a></li>
                <li id="pestana2"><a href='javascript:cambiarPestana(pestanas,pestana2);'>Otros Parametros</a></li> 
            </ul>
        </div-->
 
        <!--body onload="javascript:cambiarPestana(pestanas,pestana1);">
 
        <div id="contenidopestanas">
            <div id="cpestana1">
             <form method="post" action="index.php?c=Declaracionr54&f=ejecutareporte">
                
	            
	            <br>
	            
	            <br></br>
	            
            
            </form>  
            </div>
            < <div id="cpestana2">
            	
			 <input type="checkbox" class="nminputcheck" id='fecha'   onclick="" checked=""/>
			 <label>Usar fecha de impresi&oacute;n .</label>
			 <input type="text" class="nminputtext" id="inicio2" style="margin-right: 12%;width:31%;" value="<?php echo date('Y-m-d');?>"/>
			            
            
            </div>
		</div>
	</div-->
</body>
</html>