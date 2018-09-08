
<?php
include("../../netwarelog/webconfig.php");

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$opc=$_REQUEST['opc'];
switch ($opc) {
	case 1://caso 1 para agregar
 $idalmacen=$_REQUEST['a'];
 $idsucursal=$_REQUEST['s'];
 $agrega=$conection->query("insert into almacen_sucursal(idAlmacen,idSucursal)values(".$idalmacen.",".$idsucursal.") ");
if($agrega){
 	
		$almsur=$conection->query("select s.nombre, a.nombre, a.idAlmacen,s.idSuc  from almacen a, almacen_sucursal als,mrp_sucursal s where s.idSuc=".$idsucursal." and a.idAlmacen=".$idalmacen);
   if($almasucu=$almsur->fetch_array(MYSQLI_BOTH)){ 
echo '<tr id='.$almasucu[2].' class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
		<td align="center">
	'.$almasucu[0].'		
		</td>
		<td align="center">
	'.$almasucu[1].'	
		</td>
		
		<td align="center">
		<img src="img/bor.png" onclick="borra(';
		echo "'".$almasucu[2]."'";
		echo ",'".$almasucu[3]."'";
		
		echo')" />
		</td>
		<td>
			<input type="radio" name="prima" id="radio_'.$almasucu[2].'" value="'.$almasucu[2].'"><label name="lblprima" id="label_'.$almasucu[2].'" style="color: red; visibility:hidden;">Primario</label>
		</td>
		</tr>'; } 
   ?>
	$("#"+<?php echo $idalmacen; ?>).remove();	
 <?php }
 break;	
	case 2:
		 $idsucursal=$_REQUEST['s'];
  		 
$alma=$conection->query("select * from almacen  where 
idAlmacen NOT in (select idAlmacen from almacen_sucursal where idSucursal=".$idsucursal.")
 and idAlmacen NOT in (select idAlmacen from mrp_sucursal where idSuc=".$idsucursal.")");
if($alma->num_rows>0){
  	echo '<option selected>----- Elija un almacen -----</option>';
  
   while($almacen=$alma->fetch_array(MYSQLI_ASSOC)){ 
  echo '<option id="'.$almacen['idAlmacen'].'" value="'.$almacen['idAlmacen'].'">'.$almacen['nombre'].'</option>';
   }
    }else{
   	   echo '<option >--No hay nuevos almacenes--</option>';   	
   }
		break;	 
		
	case 3:
		$iva=$_REQUEST['iva'];
		$i=$conection->query("update parametros_pv set iva=".$iva." where id=1");
		if($i){
			echo "Cambio realizado";
		}else{
			echo "Error en el cambio";
		}
		break;
}
$conection->close();
 ?>