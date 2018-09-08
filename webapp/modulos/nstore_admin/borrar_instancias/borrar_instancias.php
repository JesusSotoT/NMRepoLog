<?php

//recibe el parametro por GET
$idinstancia=$_GET["id"];

$con=mysqli_connect("nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com","nmdevel",
                    "nmdevel","netwarstore");
$instancia = mysqli_real_escape_string($con, $idinstancia);
$sql =" SELECT * FROM customer WHERE id = " . $instancia;

$rst=mysqli_query($con,$sql);
while($row=mysqli_fetch_array($rst)){
        $nInstancia=$row['instancia'];
        $nBD=$row['nombre_db'];
};

// OBTENIENDO VARIABLES
echo "Número de instancia a borrar: " . $instancia . "<br>";
echo "Nombre de instancia a borrar: " . $nInstancia . "<br>";
echo "Base de dadtos a borrar: " . $nBD . "<br>";

// BORRANDO BASE DE DATOS
$sql = "DROP DATABASE " . $nBD;

if (mysqli_query($con, $sql))
  echo "La base de datos " . $nBD . " ha sido borrada con éxito <BR>";
else
  echo "No fue posible borrar la base de datos " . $nBD . ", porque " . mysqli_error($con) .  "<br>";


// MODIFICANDO TABLA DE INSTANCIAS
$sql = "UPDATE customer SET status_instancia= 4 WHERE id = " . $instancia;

if (mysqli_query($con, $sql))
  echo "La instancia " . $nInstancia . " ha sido actualizada con éxito <BR>";
else
  echo "No fue posible actualizar la instancia " . $nInstancia . ", porque " . mysqli_error($con) . "<BR>";

mysqli_close($con);

$folder = "/srv/www/htdocs/clientes/" . $nInstancia;
if ($folder != "/srv/www/htdocs/clientes/") {
  /*
  $output=`sudo su`;
  echo $output;
  echo "Super user ..." . "<br>";
  $output=`chmod -R 777 $folder`;
  echo $output;
  echo "Permisos cambiados ..." . "<br>";
  */
  $output=`rm -fR $folder`;
  echo $output;

  echo "La carpeta " . $folder . " ha sido eliminada <BR>";
}

echo "<A href='http://www.netwarmonitor.mx/clientes/nmadmin/webapp/netwarelog/repolog/reporte.php'>Regresar</A>";

/*
if(exec($command))
  echo "El comando " . $command . " fue borrada con éxito";
else
  echo "Hubo un error al borrar la carpeta " . $folder;
*/
/*
function rrmdir($rem_dir, &$files_count, &$dir_count){
  if (is_dir($rem_dir)) {
    $files = array_diff(scandir($rem_dir), array('.','..'));
    foreach ($files as $file) {
      $mensaje = "Borrando %s<br>";
      echo sprintf($mensaje,"$rem_dir/$file");
      if (!is_dir("$rem_dir/$file")){
        if (unlink ("$rem_dir/$file")){
          $files_count++;
          $mensaje = "-----El archivo %s ha sido borrado con éxito. Van %d archivos. <br>";
          //echo sprintf($mensaje, "$rem_dir/$file", $files_count);
        }
        else
          $mensaje = "-----Error al borrar el archivo %s<br>";
          //echo sprintf($mensaje, "$rem_dir/$file");
      }
      else
        rrmdir("$rem_dir/$file", $files_count, $dir_count);
    }
    if (rmdir($rem_dir)) {
      $dir_count++;
      $mensaje = "EL DIRECTORIO %s HA SIDO BORRADO CON EXITO. VAN %d DIRECTORIOS<br>";
      //echo sprintf($mensaje, $rem_dir, $dir_count);
    }
    else {
      $mensaje = "NO SE PUDO BORRAR EL DIRECTORIO %s";
      //echo sprintf($mensaje, $rem_dir);
    }
  }
  else {
    $mensaje = "NO FUE POSIBLE BORRAR EL DIRECTORIO RAIZ %s <br>";
    echo sprintf($mensaje, $rem_dir);
  }

}
*/

?>
