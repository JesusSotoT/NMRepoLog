<?php
  include('conexiondb.php');

  $fechai=$_POST['fecha1'];
    $fechaf=$_POST['fecha2'];

 $servidor  = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
        $usuariobd = "nmdevel";
        $clavebd = "nmdevel";
        $bd = "nmdev";
        $accelog_variable = "netappmitranetwarelog1";
$strSqlG ="Select * from constru_notilog where fecha>='$fechai' and fecha<='$fechaf' order by fecha desc";
$objConG = mysqli_connect($servidor,$usuariobd , $clavebd,$bd );
      $result = mysqli_query($objConG, $strSqlG);
      if($result->num_rows>0) {
        while($row = $result->fetch_array() ) {
          $vernoti[]=$row;
        }
      }else{
        $vernoti=0;
      }       
?>


<table style="border: 1px solid #ccc;" id='notifi' class="table table-striped">

  <tr><th>Fecha</th><th>Observaciones</th><th>Modulo</th></tr>
<?php 
      if($vernoti!=0){
          foreach ($vernoti as $k => $v) { 
            $v['fecha']=substr($v['fecha'],0,10);?>
            <tr><td><?php echo $v['fecha']; ?></td><td><?php echo $v['observaciones'];?></td><td><?php echo $v['modulo'];?></td></tr>
          <?php } }?>
</table>