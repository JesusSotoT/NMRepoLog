<?php 
include("../../../../modulos/sms/funcionesBD/gridliquidacionrutas.php");
$datos_liquidacion=datosliquidacion($_POST["id"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
<head>						
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>
<body >		
<input type="hidden" id="id" value="<?php echo $_POST["id"]; ?>">

<table width="100%" align="center" border=3 bgcolor="#91C313" style="color:#FFFFFF">
	
	<tr>    <td width="40%"><label>Cantidad de promociones pedidas:</label></td> <td width="60%"><input value="<?php echo $_POST["cantidad"]; ?>" readonly="readonly" style="width:230px;" type="text"></td>   </tr>
	
	<tr>    <td width="40%"><label>Cantidad de promociones entregadas:</label></td> <td width="60%"><input value="<?php if($datos_liquidacion!=0){ echo $datos_liquidacion["cantidadentregada"]; } ?>" id="cantidad" style="width:230px;" type="text"></td>   </tr>
	<tr>	<td><label>Se entrego factura:</label></td> <td><input <?php if($datos_liquidacion!=0 && $datos_liquidacion["recibiofactura"]==1){ echo "checked='checked'"; } ?>  id="factura" type="checkbox"></td>  </tr>
	<tr>	<td><label>Comentarios:</label></td> <td><textarea id="comentarios" rows="10" cols="35"><?php if($datos_liquidacion!=0){ echo $datos_liquidacion["comentarios"]; } ?></textarea></td>       
		
		
 </tr>
	
</table>		

</body>
</html> 