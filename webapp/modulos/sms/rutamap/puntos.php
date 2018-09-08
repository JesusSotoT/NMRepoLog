<?php
	 $longitud=$_REQUEST['lon'];
	 $latitud=$_REQUEST['lat'];
	// $puntoslon=$_REQUEST['punlon'];
	// $puntoslat=$_REQUEST['punlat'];
	$puntos=$_REQUEST['punto'];
	$coorde = explode(",", $puntos);
   // $url="http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&v=Heavygoods&fast=1&layer=mapnik&flon=".$longitud."&flat=". $latitud."&tlon=".$puntoslon."&tlat=". $puntoslat."";
	 // $pagina_inicio = file_get_contents($url);
	// $fp = fopen('krmn.txt','w');
 // fwrite($fp, $pagina_inicio);
  // fclose($fp);
//  
 // echo "http://localhost/mlog/webapp/modulos/sms/rutamap/krmn.txt";
for($l=0;$l<count($coorde)-1;$l++){
  if($l==0){
 	 $url="http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&v=Heavygoods&fast=1&layer=mapnik&flon=".$lon."&flat=". $lat."&tlon=".$puntoslon[$l]."&tlat=". $puntoslat[$l]."";
	  $pagina_inicio = file_get_contents($url);
	 $fp = fopen('krmn.txt','w');
 fwrite($fp, $pagina_inicio);
  fclose($fp);
  echo 'http://localhost/mlog/webapp/modulos/sms/rutamap/krmn.txt';
 }
  if($l!=0){
 $url="http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&v=Heavygoods&fast=1&layer=mapnik&flon=".$puntoslon[$l-1]."&flat=". $puntoslat[$l-1]."&tlon=".$puntoslon[$l]."&tlat=". $puntoslat[$l]."";
	 $pagina_inicio = file_get_contents($url);
	 $fp = fopen('ruta.txt','w');
 fwrite($fp, $pagina_inicio);
 fclose($fp);
    echo 'http://localhost/mlog/webapp/modulos/sms/rutamap/krmn.txt';
 
}
 
  if($l==count($puntoslat)-1){
 	 $url="http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&v=Heavygoods&fast=1&layer=mapnik&flon=".$puntoslon[$l-1]."&flat=". $puntoslat[$l-1]."&tlon=".$puntoslon[$l]."&tlat=". $puntoslat[$l]."";
	   $pagina_inicio = file_get_contents($url);
	  $fp = fopen('ruta.txt','w');
	  fwrite($fp, $pagina_inicio);
	  fclose($fp);

	   echo 'http://localhost/mlog/webapp/modulos/sms/rutamap/krmn.txt';
	 
//  	
  }
// for($i=0;$i<count($coorde)-1;$i++){
	// echo $coorde[$i]."--";
	 }
 
 ?>