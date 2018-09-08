<?php
$strDB="--NOLAENCONTRE--"; 
$con=mysqli_connect("nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com","nmdevel","nmdevel","netwarstore");
$sql =" select c.idclient, c.instancia, c.nombre_db, a.idapp, a.* ";
$sql.=" from customer c inner join appclient a on c.id = a.idcustomer ";
$sql.=" where idapp='".$argv[1]."' and instancia='".$argv[2]."' ";
$rst=mysqli_query($con,$sql);
while($row=mysqli_fetch_array($rst)){
	$strDB=$row['nombre_db'];
};
mysqli_close($con);
echo $strDB;
;?>
