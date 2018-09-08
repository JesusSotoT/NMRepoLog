<?php
$strDB="--NOLAENCONTRE--"; 
$con=mysqli_connect("nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com","nmdevel","nmdevel","netwarstore");
$rst=mysqli_query($con,"SELECT nombre_db FROM customer WHERE instancia = '" . $argv[1] . "';");
while($row=mysqli_fetch_array($rst)){
	$strDB=$row['nombre_db'];
};
mysqli_close($con);
echo $strDB;
;?>
