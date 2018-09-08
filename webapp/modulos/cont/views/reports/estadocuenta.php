<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/rep_fiscal.css">
	<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
		.cuerpo{width: 520px; height: auto  padding: 7px; font-family: arial;}
		.tamanoSel{width: 200px;	text-overflow: ellipsis;}
		#conv{display: none;}
	</style>
	<script src="../cont/js/jquery-1.10.2.min.js"></script>
	<script src="../cont/js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../cont/js/select2/select2.css" />
	<script type="text/javascript">
	
	$(document).ready(function()
	{
		 $("#periodo,#ejercicio,#cuentabancaria").select2({width : "150px",});

	});
		
		
	</script>
</head>
<body>

	<div class="repTitulo">Estado de Cuenta</div>
	<div class="per">
		<form  method='post' action='index.php?c=conciliacionAcontia&f=reporteEstadoCuenta' >
			<ul><li>
				<table width="50%">
					<tr>Ejercicio:</tr>
					<tr>
						<td><select name='ejercicio' id='ejercicio' class="nminputselect" style="width: 100px">
								<?php 
								while($p = $ejercicio->fetch_object())
								{
									echo "<option value='".$p->Id."'>".$p->NombreEjercicio."</option>";
								}
								?>
							</select></td>
					</tr>
				</table>
				</li>
				<li>
					<table width="50%">
					<tr>Periodo:</tr>
					<tr>
						<td><select id="periodo" name="periodo" class="nminputselect">
				<?php while($p = $periodo->fetch_assoc()){
						if($_SESSION['datos']['periodo']==$p['id']){$se = "selected"; }else{$se="";} ?>?>
					<option value="<?php echo $p['id'];?>" <?php echo $se;?> > <?php echo $p['mes'];?></option>
				<?php } ?>
						</select></td>
								</tr>
					</table>
					
			</li>	
			<li>
				<table width="50%">
					<tr>Cuenta Bancaria:</tr>
					<tr>
						<td>
							<select id="cuentabancaria" name="cuentabancaria" class="nminputselect">
							<?php 
								while($b=$cuentasB->fetch_array()){
									if($_SESSION['datos']['idbancaria']==$b['idbancaria']){$s = "selected"; }else{$s="";} ?>
									<option value="<?php echo  $b['idbancaria']; ?>" <?php echo $s;?> > <?php echo $b['nombre']." (".$b['cuenta'].")"; ?> </option>
						<?php   } ?>
							</select>
						</td>
					</tr>
					</table>
				</li>	
				<li><input type="submit" class="nminputbutton" value="Ejecutar Reporte"></li>
			
			</ul>
			
		</form>
	</div>
	
</body>
</html>