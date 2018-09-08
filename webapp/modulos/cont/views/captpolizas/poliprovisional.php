<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script type="text/javascript" src="js/sessionejer.js"></script>
	<link rel="stylesheet" type="text/css" href="css/clipolizas.css"/>
	<script type="text/javascript" src="js/poliprovisional.js" ></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
<?php 
if(isset($_COOKIE['ejercicio'])){
	$NombreEjercicio = $_COOKIE['ejercicio'];
	$v=1;
}else{
	$NombreEjercicio = $Ex['NombreEjercicio'];
	$v=0;
}
?>
<script>dias_periodo(<?php echo $NombreEjercicio; ?>,<?php echo $v; ?>)</script>
</head>
<body>
<form method="post" ENCTYPE="multipart/form-data" action="index.php?c=CaptPolizas&f=guardaprovision" id="formulario">

<div class=" nmwatitles ">Polizas de provision</div>
<div id="contenedor" class="div" align="right">

Selecione un comprobante: 
<select id="comprobante" name="comprobante" onchange="javascript:cambio()" class="nminputselect">
	<option value="0" selected="">Elija una opcion.</option>
	<option value="1">Ingresos</option>
	<option value="2">Egresos</option>
</select>
<br></br>
<br></br>
	<div id='cargando-mensaje' style='font-size:12px;color:blue;width:20px;display: none;'> Cargando...</div>

<div id="ingresos" style="display: none" style="border: 2px solid gray;border-color: #F2F2F2;">
<div class="nmwatitles" style="width: 70%">&nbsp;Provision de Ingresos.</div>
<br></br>
<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="cuentaingresosact()" src="images/reload.png">
	<br></br>		
Cuenta para ingreso
		<select id="cuentaingre" name="cuentaingre" class="nminputselect">
			<?php while($ingre=$cuentaingresos->fetch_array()){ ?>
				<option value="<?php echo $ingre['account_id']."/".$ingre['description']; ?>"><?php echo $ingre['description']."(".$ingre['manual_code'].")"; ?></option>
			<?php } ?>
		</select><br></br>
</div>
<div id="egresos" style="display: none">
<div class="nmwatitles" style="width: 70%">&nbsp;Provision de Egresos.</div>
<br></br>
<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="cuentaegresosact()" src="images/reload.png">
<br></br>
Cuenta para egreso
		<select id="cuentaegre" name="cuentaegre" class="nminputselect">
			<?php while($egresos=$cuentaegresos->fetch_array()){ ?>
				<option value="<?php echo $egresos['account_id']."/".$egresos['description']; ?>"><?php echo $egresos['description']."(".$egresos['manual_code'].")"; ?></option>
			<?php } ?>
		</select>
	<br>
	
</div>
<br></br>
<fieldset style="width: 120%">
<legend>D A T O S  &nbsp;  D EL   &nbsp;  E J E R C I C I O</legend>
	<table border=0>
		
		<tr>
			<?php 
			
			//echo $_COOKIE['ejercicio'];
			if(isset($_COOKIE['ejercicio'])){
				$InicioEjercicio = explode("-","01-0".$_COOKIE['periodo']."-".$_COOKIE['ejercicio']); 
				$FinEjercicio = explode("-","31-0".$_COOKIE['periodo']."-".$_COOKIE['ejercicio']);  
				$peridoactual = $_COOKIE['periodo'];
				$ejercicioactual = $_COOKIE['ejercicio'];
			}else{
				$InicioEjercicio = explode("-",$Ex['InicioEjercicio']); 
				$FinEjercicio = explode("-",$Ex['FinEjercicio']); 
				$peridoactual = $Ex['PeriodoActual'];
				$ejercicioactual = $Ex['EjercicioActual'];
				
			}
			
			?>
		<td style='height:30px;'><b>Ejercicio Vigente:</b> 
			<?php
			if($Ex['PeriodosAbiertos'])
				{
					if($ejercicioactual > $firstExercise)
					{
						?><a href='javascript:cambioEjercicio(<?php echo $peridoactual; ?>,<?php echo $ejercicioactual-1; ?>);' title='Ejercicio Anterior'><img class='flecha' src='images/flecha_izquierda.png'></a>
				<?php }
				} ?>
	
			del (<?php echo $InicioEjercicio['2']."-".$InicioEjercicio['1']."-".$InicioEjercicio['0']; ?>) al (<?php echo $FinEjercicio['2']."-".$FinEjercicio['1']."-".$FinEjercicio['0']; ?>)
			<?php if($Ex['PeriodosAbiertos'])
				{
					if($ejercicioactual < $lastExercise)
					{
						?><a href='javascript:cambioEjercicio(<?php echo $peridoactual; ?>,<?php echo $ejercicioactual+1; ?>)' title='Ejercicio Siguiente'><img class='flecha' src='images/flecha_derecha.png'></a>
				<?php }
				} ?> </td>

			<td>
			</tr>
			<tr>
				<td>	<b>Periodo actual:</b> 

		<?php 
				if($Ex['PeriodosAbiertos'])
				{
					if($peridoactual>1)
					{
						?><a href='javascript:cambioPeriodo(<?php echo $peridoactual-1; ?>,<?php echo $ejercicioactual; ?>);' title='Periodo Anterior'><img class='flecha' src='images/flecha_izquierda.png'></a>
				<?php }
				} ?>  
				<label id='PerAct'><?php echo $peridoactual; ?></label><input type='hidden' id='Periodo' value='<?php echo $peridoactual; ?>'> del (<label id='inicio_mes'></label>) al (<label id='fin_mes'></label>)  
			<?php if($Ex['PeriodosAbiertos'])
				{
					if($peridoactual<13)
					{
						?><a href='javascript:cambioPeriodo(<?php echo $peridoactual+1; ?>,<?php echo $ejercicioactual; ?>)' title='Periodo Siguiente'><img class='flecha' src='images/flecha_derecha.png'></a>
				<?php }
				} ?> </td>

		</tr>
		<td>
			Acorde a configuracion:<img src="images/reload.png" onclick="periodoactual()" title="Ejercicio y periodo de configuracion por defecto" style="vertical-align:middle;">
		</td>
		
	</table>
	<input type="hidden" id="diferencia" value="<?php echo number_format($Cargos['Cantidad']-$Abonos['Cantidad'], 2); ?>" />

<?php 
// echo $_SESSION['periodo'];
// echo "->".$_SESSION['ejercicio'];
// 
// unset($_SESSION['periodo']);
// unset($_SESSION['ejercicio']);
?>
</fieldset><br>
<fieldset style="width: 120%">
<legend>D A T O S  &nbsp;  D E   &nbsp;  R E G I S T R O</legend>
<table>
	<tr>
		<td>
			<input type="text" id="referencia" name="referencia" placeholder="Concepto.."/>
		</td>
		<td>Segmento de Negocio:</td>
		<td>
			<select name='segmento' id='segmento' style='width: 150px;text-overflow: ellipsis;'  class="nminputselect">
				<?php
					while($LS = $ListaSegmentos->fetch_assoc())
					{
						echo "<option value='".$LS['idSuc']."//".$LS['nombre']."'>".$LS['nombre']."</option>";
					}
					?>
			</select>
		</td>
		<td>Sucursal:</td>
		<td><select name='sucursal' id='sucursal' style='width: 150px;text-overflow: ellipsis;'  class="nminputselect">
		<?php
			while($LS = $ListaSucursales->fetch_assoc())
			{
				echo "<option value='".$LS['idSuc']."//".$LS['nombre']."'>".$LS['nombre']."</option>";
			}
			?>
			</select>
		</td>
	</tr>
</table>
</fieldset>
<br></br>
<input type="file"	name="xml" id="xml" style="" ><br>
<input type="button" name="Submit" class="nminputbutton" value="Previsualizar" onclick="comprueba_extension(this.form, this.form.xml.value)"> 	
<input type="submit"  id="envia" style="display: none" onclick=""><img src="images/loading.gif" style="display: none" id="load">
</div>	
</form>

</body>
</html>