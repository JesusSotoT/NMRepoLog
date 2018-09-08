<?php

include("../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

$sql = $conection->query("select * from nomi_configuracion");
$configuracion = $sql->fetch_object();
$fechainicio = explode('-',$configuracion->fechainicio);
	

$sql = $conection->query("select * from nomi_tiposdeperiodos where activo=-1 order by idtipop desc limit 1");
$Ejer = $sql->fetch_object();


if($Ejer->diasperiodo == 15){
	$fecha = $fechainicio[0]."-01-01";
	$numnomia = 1;
	for ($peri = 1; $peri <= 12; $peri++) {
		$menos = 0;
		$dias1 = 14;//siempre seran 14 dias para tomar la quincena porque ya estara en dia 1 mas 14 son los 15
		$menos += 1;	//sumamos siempre un dia porq asi iniciaria el periodo en 1 o 16 y se debe contar
		if($peri != 1 ){
			$nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
			$fecha = $nuevafecha;
		}
		
		/* definimos los inicios de bimestre */
		$iniciobimentre = 0;
		if($peri == 1 || $peri == 3 || $peri == 5 || $peri == 7 || $peri == 9 || $peri == 11){
			$iniciobimentre = 1;
		} 
		/* definimos los fin de bimestre */
		$finbimentre = 0;
		if($peri == 2 || $peri == 4 || $peri == 6 || $peri == 8 || $peri == 10 || $peri == 12){
			$finbimentre = 1;
		} 
		/* definimos los inicio ejercicio */
		$inicioejercicio = 0;
		if($peri == 1){
			$inicioejercicio = 1;
		}
		/* definimos los fin ejercicio */
		$finejercicio = 0;
		if($peri == 12){
			$finejercicio = 1;
		}
		
		
		
		//$array[$peri][] = $fecha;
		$nuevafecha = strtotime ( '+'.$dias1.' day' , strtotime ( $fecha ) ) ;
		$menos += $dias1;
		$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
		$fecha2 = $nuevafecha;
		//$array[$peri][] = $fecha;
		
		$sql=$conection->query("INSERT INTO nomi_nominasperiodo ( idtipop, numnomina, fechainicio, fechafin, ejercicio, mes, diaspago, iniciomes, iniciobimentreimss, inicioejercicio, finmes, finbimentreimss, finejercicio)
		VALUES
			( ".$Ejer->idtipop.", $numnomia, '$fecha', '$fecha2', ".$fechainicio[0].", $peri, ".$Ejer->diaspago.", 1, $iniciobimentre, $inicioejercicio, 0, 0, 0);
			");
// 			
// 			
		$nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha2 ) ) ;
		$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
		$menos += 1;
		$fecha3 = $nuevafecha;
		//$array[$peri][] = $fecha;
		
		//sacamos todos los dias del mes para saber de cuanto sera la segunda quincena
		$numero = cal_days_in_month(CAL_GREGORIAN,$peri, $fechainicio[0]) ;
		//y restamos a esos dias la primera quincena
		$numero -= $menos ;
		$nuevafecha = strtotime ( '+'.$numero.' day' , strtotime ( $fecha3 ) ) ;
		$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
		$fecha = $nuevafecha;
		//$array[$peri][] = $fecha;
		$numnomia++;
		$sql=$conection->query("INSERT INTO nomi_nominasperiodo ( idtipop, numnomina, fechainicio, fechafin, ejercicio, mes, diaspago, iniciomes, iniciobimentreimss, inicioejercicio, finmes, finbimentreimss, finejercicio)
		VALUES
			( ".$Ejer->idtipop.", $numnomia, '$fecha3', '$fecha', ".$fechainicio[0].", $peri, ".$Ejer->diaspago.", 0, 0, 0, 1, $finbimentre, $finejercicio);
			");
// 			
		$numnomia++;
 
			
	}
}else{
	
	$datetime1 = new DateTime($configuracion->fechainicio);
			$datetime2 = new DateTime($Ejer->fechainicio);
			$interval = $datetime1->diff($datetime2);
			//echo $interval->format('%R%a días')."---".$Ejer->fechainicio;
			//echo "fechainicio".$configuracion->fechainicio;
			$diasano = 365 - $interval->format('%R%a');
			//sacamos el total de nominas del ano
			$totalano = $diasano/$Ejer->diasperiodo;
			//echo number_format($totalquincena);
			
			//dividimos el total de nominas por los dias trabajados 
			//para saber cuantas nominas iran en un mes
			$nominaenunmes =  number_format($totalano)/12;
			
			//echo "en un mes hay ".number_format($nominaenunmes);
			$fecha = $Ejer->fechainicio;
			$numnomia = $mes = 1;
			$finmes = 0;
			for ($peri = 1; $peri <= number_format($totalano); $peri++) {
				
				$inicioejercicio = 0;
				
				if($peri == 1 ){
					
					$inicioejercicio = 1;
					$iniciomes = 1;
					$iniciobimentre = 1;
					
				}else{
				
					/* buscamos si es divisible por el numero de nominas en un mes
					 * para saber cuando inicia y cuando finaliza el periodo
					 */
					$iniciomes = 0;
				 if ($peri % $nominaenunmes == 0) {
				 	$iniciomes = 1;
				 	$mes++;
   					
				}
					/* multiplicamos por 2 ya que $nominaenunmes corresponde
					 * a un mes entonces multiplicado por 2 son bimestre
					 */
					$iniciobimentre = 0;
					if ($peri % $nominaenunmes * 2 == 0) {
	   					$iniciobimentre = 1;
					}
					
				}
				
				/*para saber el fin de mes
				 * le resto -1 a las nominas del mes a si
				 * sabre q una antes es cierre
				 */
				 
					// $finmes = 0;
					// if ($peri % $nominaenunmes == 0) {
// 	   					
						// $finmes = 1;
						// $mes++;
					// }
				
				
				/* para saber el fin de bimestre aplicamos la misma
				 * restamos un -1 pa saber q en el anterior sera fin
				 */
				$finbimentre = 0;
				if ($peri % ($nominaenunmes * 2)-1 == 0) {
   					$finbimentre = 1;
				}
				/* si peri es igual al totalquincena
				 * es que es el ultimo y debe ser el fin d eejericico
				 */
				$finejercicio = 0;
				if ( $peri ==  number_format($totalquincena)){
					$finejercicio = 1;
				}
				
				
				
				if($peri != 1 ){
					$nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
					$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
					$fecha = $nuevafecha;
				}
				//$array[$peri][]=$fecha;
				
				
				
				$dias = $Ejer->diasperiodo - 1;
				$nuevafecha = strtotime ( '+'.$dias.' day' , strtotime ( $fecha ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
				$fecha2 = $nuevafecha;
				//$array[$peri][] = $fecha;
				
				$conection->query("INSERT INTO nomi_nominasperiodo ( idtipop, numnomina, fechainicio, fechafin, ejercicio, mes, diaspago, iniciomes, iniciobimentreimss, inicioejercicio, finmes, finbimentreimss, finejercicio)
				VALUES
					( ".$Ejer->idtipop.", $numnomia, '$fecha', '$fecha2', ".$fechainicio[0].", $mes, ".$Ejer->diaspago.", $iniciomes, $iniciobimentre,$inicioejercicio, $finmes,  $finbimentre, $finejercicio);
					");
					if ($peri % $nominaenunmes  == 0) {
						$finmes = 1;
					}else{
						$finmes = 0;
					}
				$numnomia++;	
				$fecha = $fecha2;
			}
	
	
}
$conection->close();

?>