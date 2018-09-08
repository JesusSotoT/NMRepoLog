<!-- <script>$('#i1391').empty();
<?php
//este ver porq se ocupa asi sisi se ocupa ese orden ay que tomar en cuenta modificar porq no esta 
// para edicion solo agregar y no esta en el link antes de la base de datos
include("../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$cadena="";
$sql=$conection->query('select * from meses order by id asc');

while($row=$sql->fetch_array()){?>
	$('#i1391').append("<?php echo "<option value='".$row['id']."'>".$row['mes']."</option>";?>");
<?php }
$conection->close();
?></script> -->