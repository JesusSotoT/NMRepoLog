<!DOCTYPE html>
<html>
<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>	
	<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/borra.js"></script>
<script type="text/javascript">
	 // $(document).ready(function(){
		 // // document.all['#alma'].style.visibility ="visible";
		 // $('#alma').css('display','none');
// // 		 
	// });
	function agregado(val){ 
		//alert('entro');
		switch (val) {
		case 1:
		var almacen =jQuery('#almacen').val();
   		var sucursal =jQuery('#sucursal').val();
   		if(almacen!="----- Elija un almacen -----"){
   		 $.post("consultalmacen.php",{a:almacen,s:sucursal,opc:val},
                 function(respuesta) {
   		$('#tabl').append(respuesta);
   		//var sucursal =jQuery('#sucursal').val();
   		 $.post("consultalmacen.php",{opc:2,s:sucursal},
                 function(respuesta) {
                 	
   		$('#almacen').html(respuesta);
   	});
   	
   	});
   	}else{
   		alert("Elija un almacen");
   	}
   	break;	
   	
   	    case 2:
   	   
   		var sucursal =jQuery('#sucursal').val();
   		
   		 $.post("consultalmacen.php",{opc:val,s:sucursal},
                 function(respuesta) {
                 	
   		$('#almacen').html(respuesta);
   	});
   	    break;		
		}
		
			
		}
   		
   		
</script>
<body>
<?php
include("../../netwarelog/webconfig.php");

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

 $ele=$_REQUEST['v'];
  
  if($ele=="new"){  
?>
<div class="tipo">
	<table><tbody><tr>
	<td><a href="javascript:window.print();">
	<img src="../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
	<td><b style="font-size: 13px;">Relaci&oacute;n Sucursal - Almac&eacute;n</b></td></tr></tbody></table></div><br>
	<br></br>

<table id="tab"  class="busqueda"  cellpadding="3" cellspacing="1" width="95%" height="95%" >
	<tr>
		<td>
 	<label style="font-size: 13pt;color: #1C1C1C">Sucursal</label>
 <select id="sucursal"  onchange="agregado(2);" style=" cursor:pointer; margin-top:25px; font-size:14px; ">
      <option selected>-Elija una Sucursal-</option>
  		<?php 
  		$sucu=$conection->query("select * from mrp_sucursal");
   while($sucursal=$sucu->fetch_array(MYSQLI_ASSOC)){ 
  
   	?>
      <option value="<?php echo $sucursal['idSuc']; ?>"><?php echo $sucursal['nombre']; ?></option>
      
<?php } ?></select>
         </td>
    <td id="alma">
	<label style=" font-size: 13pt; color:#1C1C1C" >Elija Almac&eacute;n</label>
	<select id="almacen"  name="almacen" align="center" style="  cursor:pointer; margin-top:25px;  font-size:14px; ">
      <option selected>----- Elija un almac&eacute;n -----</option>
</select>
    </td>
   <td>
 <input type="button"  value="Agregar"  onclick='agregado(1);' style=" cursor:pointer; margin-top:25px;  font-size:12px; "/>
 
  </td>
 	
	</tr>
	
</table>	
	

<br></br>
<!--nuevo agregar -->
<div align="center">
	<table  id="tabl"  class="busqueda"  cellpadding="3" cellspacing="1" width="60%" height="95%" >
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 10pt;">
	<th>Sucursal</th>
	
	<th>Almac&eacute;n</th>
	
</tr>	
</table>
</div>
<br></br><br></br>
<input type="button" align="left" value="Guardar" onclick="window.location='listadosucuralma.php';" />


<?php 
}else{ ?>

<!-- editar sucur -->
<table>
	<tr >
		<td>
 Sucursal<select id="sucursal"  onchange="agregado(2);" style=" cursor:pointer; margin-top:25px;  font-size:12px; ">
  
  		<?php 
  		$sucu=$conection->query("select * from mrp_sucursal where idSuc=".$ele);
 if($sucursal=$sucu->fetch_array(MYSQLI_ASSOC)){ 
  
   	?>
 <option value="<?php echo $sucursal['idSuc']; ?>" selected><?php echo $sucursal['nombre']; ?></option>
      
<?php } ?>
      </select>
      <script type="text/javascript">
	agregado(2);
	//var aler =jQuery('#sucursal').val();
	//alert(aler);
	</script>
         </td>
         
    <td id="alma" align="center">
	<label style=" font-size: 15; color:#1C1C1C" >Elija Almac&eacute;n</label>
	<select id="almacen"  name="almacen" align="center" style="  cursor:pointer; margin-top:25px; font-size:12px; ">
       <option selected>----- Elija un almac&eacute;n -----</option>

</select>
    </td>
   <td align="center">
 <input type="button"  value="Agregar"  onclick='agregado(1);' style=" cursor:pointer; margin-top:25px; font-size:12px; "/>
  </td>
 	
	</tr>
</table>	

<br></br>

<table  class="busqueda"  cellpadding="3" cellspacing="1" width="95%" height="95%" >
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
	
    <th align="center">Sucursal</th>
	<th align="center">Almac&eacute;n</th>
</tr>	
	
<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
	<?php
	
$almsur=$conection->query("select  a.idAlmacen,s.nombre, a.nombre from almacen a,mrp_sucursal s where s.idSuc=".$ele." and a.idAlmacen=s.idAlmacen GROUP BY s.nombre");
   if($almasucu=$almsur->fetch_array(MYSQLI_BOTH)){
   	 ?>
	<td align="center">
	<?php echo $almasucu[1]; ?>	
		
	</td>
		
		<td align="center">
	<?php echo $almasucu[2]; ?>	
		</td>
		
		<td align="center">
			<label style="color: red">Primario</label>
	 <!--
		<img src="img/bor.png" onclick="borra(<?php echo $almasucu[0]; ?>,<?php echo $ele; ?>)"/>
		-->
		</td>
</tr>
		<?php } 
   $almsuri=$conection->query("select  a.idAlmacen,s.nombre, 
   a.nombre from almacen a,mrp_sucursal s,almacen_sucursal alsu
    where alsu.idSucursal=".$ele." and a.idAlmacen=alsu.idAlmacen 
    and s.idSuc=alsu.idSucursal");
   while($almasucu=$almsuri->fetch_array(MYSQLI_BOTH)){
   	 ?>
<tr id="<?php echo $almasucu[0]; ?>" class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
	
	<td align="center">
	<?php echo $almasucu[1]; ?>		
	</td>
		
		<td align="center">
	<?php echo $almasucu[2]; ?>	
		</td>
		
		<td align="center">
		<img src="img/bor.png" onclick="borra(<?php echo $almasucu[0]; ?>,<?php echo $ele; ?>)"/>
		</td>
		</tr>
		<?php } 
	?>
		
</table>

<br></br>

<input type="button" value="Guardar" onclick="window.location='listadosucuralma.php';" />
<?php
}
$conection->close();
?>

</body>

</html>