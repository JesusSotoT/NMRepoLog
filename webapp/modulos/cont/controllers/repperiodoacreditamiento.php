<?php
    require('common.php');

//Carga el modelo para este controlador
require("models/RepPeriodoAcreditamiento.php");

class RepPeriodoAcreditamiento extends Common
{
	public $RepPeriodoAcreditamientoModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->RepPeriodoAcreditamientoModel = new RepPeriodoAcreditamientoModel();
		
	
	}
	function ver()
	{
		
		require('views/fiscal/diot/MovimientosConProveedores.php');
		
	}
	function verdeta()
	{
		$empresa=$this->organizacion();
			$fecha=$_REQUEST['fechaimprecion'];
		//$contenido.'->'.$empres.'<-'.$fechaimprecion; 
		if( isset($_REQUEST['delperiodo' ]) ){
			$delperiodo=$this->mes($_REQUEST['delperiodo']);
			$alperiodo=$this->mes($_REQUEST['alperiodo']);
			$body=$this->reporteacredita($_REQUEST['ejercicio'],$_REQUEST['delperiodo'],$_REQUEST['alperiodo'],$_REQUEST['proveedores'],$_REQUEST['aplica']);
			$r=explode('//',$body);
			$periodo='Periodo de Acreditamiento de '.$delperiodo.' a '.$alperiodo.' '.$r[0];
			$bodyfi=$r[1];
		}
		if( isset($_REQUEST['inicio']) ){
			$inicio=$_REQUEST['inicio'];
			$fin=$_REQUEST['fin'];
			$body=$this->reporteacredita($_REQUEST['ejercicio'],$_REQUEST['inicio'],$_REQUEST['fin'],$_REQUEST['proveedores'],$_REQUEST['aplica']);
			$r=explode('//',$body);
			$periodo='Del '.$inicio.' al '.$fin.' del '.$r[0];
			$bodyfi=$r[1];
			
		}
		
		 require('views/fiscal/diot/RepPeriodoAcreditamiento.php');
		
		// $('#periodo').text(mes(delperiodo,alperiodo)+' '+r[0]);
						 		 			// $('#empresa').html(r3[0]);
						 		 			// $('#fech').html(r3[1]); 
						 		 			// $('#datos tbody').prepend(respues);
						 		 			// $('#estilo').append(r3[2]);
	}
	function verrepexcel()
	{
		if( isset($_REQUEST['delperiodo' ]) ){
			$delperiodo=$this->mes($_REQUEST['delperiodo']);
			$alperiodo=$this->mes($_REQUEST['alperiodo']);
			$body=$this->reporteacredita($_REQUEST['ejercicio'],$_REQUEST['delperiodo'],$_REQUEST['alperiodo'],$_REQUEST['proveedores'],$_REQUEST['aplica']);
			$r=explode('//',$body);
			$r2=explode('->',$body);
			$r3=explode('<-',$r2[1]);
			$periodo='Periodo de Acreditamiento de '.$delperiodo.' a '.$alperiodo.' '.$r[0];
			$empresa=$this->organizacion();
			$fecha=$_REQUEST['fecha'];
			$relle=explode('->',$r[1]);
			$bodyfi=$relle[0];
			
		}
		if( isset($_REQUEST['inicio']) ){
			$inicio=$_REQUEST['inicio'];
			$fin=$_REQUEST['fin'];
			$body=$this->reporteacredita($_REQUEST['ejercicio'],$_REQUEST['inicio'],$_REQUEST['fin'],$_REQUEST['proveedores'],$_REQUEST['aplica']);
			$r=explode('//',$body);
			$r2=explode('->',$body);
			$r3=explode('<-',$r2[1]);
			$periodo='Del '.$inicio.' al '.$fin.' del '.$r[0];
			$empresa=$this->organizacion();
			$fecha=$_REQUEST['fecha'];
			$relle=explode('->',$r[1]);
			$bodyfi=$relle[0];
			
		}
		
		 require('views/fiscal/diot/excel/reppriodoacreditaexcel.php');
		
	}
	function verrepdetallado()
	{
		//echo $_REQUEST['idpoliza'];
		$fecha=$_REQUEST['fecha'];
		$empresa=$this->organizacion();
		$bodyempr=$this->detallado($_REQUEST['idpoliza'],$_REQUEST['idProveedor']);
		$bod=explode('//',$bodyempr);
		$body=$bod[1];
		$direccion=$bod[0];
		require('views/fiscal/diot/visualizarep.php');
		
	
	}
	function verrepdetalladoexcel()
	{
		//echo $_REQUEST['idpoliza'];
		$fecha=$_REQUEST['fecha'];
		$empresa=$this->organizacion();
		$bodyempr=$this->detallado($_REQUEST['idpoliza'],$_REQUEST['idProveedor']);
		$bod=explode('//',$bodyempr);
		$body=$bod[1];
		$direccion=$bod[0];
		require('views/fiscal/diot/excel/visualizarepoexcel.php');
		
	
	}
	function ejercicio()
	{
	 $datos= $this->RepPeriodoAcreditamientoModel->ejercicio();
	 while($d = $datos->fetch_array()){
	 	echo '<option value="'.$d['Id'].'" selected>'.$d['NombreEjercicio'].'</option>';
	 }
		
	}
	function reporteacredita($ejercicio,$delperiodo,$alperiodo,$proveedores,$aplica){
		$contenido='';
		
			if( $proveedores!='' ){
				$ejer = explode( ',', $proveedores );
				$i = 0;
				$acreditabletotal = 0;
				$importebasetotal = 0;
				$totalfinal = 0;
					foreach( $ejer as $num ){
						$var = strrpos( $num,'-' );
							if( $var !== false){
								$v2 = str_replace('-',' and ',$num);
									$fecha = strrpos( $delperiodo,'-' );
									if( $fecha === false){
										$datos = $this->RepPeriodoAcreditamientoModel->cargadatos($ejercicio,$delperiodo,$alperiodo,$v2,$aplica);
									}
									else{
										$datos = $this->RepPeriodoAcreditamientoModel->cargadatos2($ejercicio,$delperiodo,$alperiodo,$v2,$aplica);
									}
				//echo $v2;
									$razon;
									$acreditablet;
									$importebase;
									$total;
									if($datos!='0'){
/////////////////////// INICIA RANGO ///////////////////////////////////////////		
			 							if($d = $datos->fetch_assoc())
		   								{
		   	////para totalgeneral
		   									$tasa =($d['tasa']/100)+1;
											   if( $importebasetotal != 0 ){
												   	$importebasetotal = $importebasetotal + ($d['importe'] / $tasa);
													$acreditabletotal = $acreditabletotal + ($d['importe'] - $importebasetotal);
													$totalfinal = $totalfinal + $d['importe'];
												}else{
													$importebasetotal = ($d['importe'] / $tasa);
													$acreditabletotal = $d['importe'] - $importebasetotal;
													$totalfinal = $d['importe'];
												}
		   	///////////////////////
											$importebase = ($d['importe'] / $tasa);
											$acreditable = $d['importe'] - $importebase;
											$total = $d['importe'];
											$razon = $d['razon_social'];
											$ejerci=$d['NombreEjercicio'];
											$contenido.= '
													<tr class="prove" >
														 <td colspan="12">'.$d['idProveedor'].' '.$d['razon_social'].'</td></tr>
														 <tr class="busqueda_fila">
														 <td align="center">'.$d['fecha'].'</td>
														 <td align="center">'.$d['titulo'].'</td>
														 <td align="center" class="tdlink">
														 <a  class="detalle" href="javascript: vistalink('.$d['id'].','.$d['idProveedor'].');">'.$d['id'].'</a></td>
														 <td align="center">'.$d['concepto'].'</td>
														 <td align="center">'.$d['NombreEjercicio'].'</td>
														 <td align="center">'.$p1=$this->mes($d['idperiodo']).'</td>
														 <td>'.$this->redondeado($importebase,2).'</td>
														 <td>'.$this->redondeado($acreditable,2).'</td>
														 <td>'.$this->redondeado($d['importe'],2).'</td>
													<tr>
												 ';	
		 							  	}//if	
		 							  	$i=1;

	 									while($d = $datos->fetch_assoc())
					   					{
					   
							 					$tasa2 = ($d['tasa'] / 100) + 1;
							 					$importebase2 = ( $d['importe'] / $tasa2);
												$acreditable2 = $d['importe'] - $importebase2;
					
					
					//if($i<count($d)-1){
												if($razon==$d['razon_social'])
												{
													$fil = ''; $fil2 = ''; 
													$acreditable = $acreditable + $acreditable2;
													$importebase = $importebase + $importebase2;
													$total =	$total + $d['importe'];
												}else{
													
													$fil = ' <tr class="prove" ><td colspan="12">'.$d['idProveedor'].' '.$d['razon_social'].'</td></tr>';
													$razon = $d['razon_social'];
													$fil2 = '<tr class="busqueda_fila"  >
															<td colspan="6" style="text-align:right;">Total</td>
															<td class="total">
															'.$this->redondeado($importebase,2).'</td><td class="total">
															'.$this->redondeado($acreditable,2).'</td><td class="total">
															'.$this->redondeado($total,2).'</td>'; 
															
													$importebase = ($d['importe'] / $tasa2);
													$acreditable = $d['importe'] - $importebase;
													$total = $d['importe'];	
												}
												 
										$contenido.= $fil2.''.$fil.'
							 					<tr class="busqueda_fila">
													<td align="center">'.$d['fecha'].'</td>
													<td align="center">'.$d['titulo'].'</td>
										 			<td align="center" class="tdlink"><a class="detalle" href="javascript: vistalink('.$d['id'].','.$d['idProveedor'].');" >'.$d['id'].'</a></td>
													<td align="center">'.$d['concepto'].'</td>
													<td align="center">'.$d['NombreEjercicio'].'</td>
													<td align="center">'.$p1=$this->mes($d['idperiodo']).'</td>
													<td>'.$this->redondeado($importebase2,2).'</td>
													<td>'.$this->redondeado($acreditable2,2).'</td>
													<td>'.$this->redondeado($d['importe'],2).'</td>
												</tr>
										 ';	
								 		$importebasetotal = $importebasetotal + ($d['importe'] / $tasa2);
										$acreditabletotal = $acreditabletotal + ($d['importe'] - $importebase2);
										$totalfinal = $totalfinal + $d['importe'];
					  				 }//while
					 
						   $contenido.= '<tr class="busqueda_fila"  >
										<td colspan="6" style="text-align:right;">Total</td>
										<td class="total">
										'.$this->redondeado($importebase,2).'</td><td class="total">
										'.$this->redondeado($acreditable,2).'</td><td class="total">
										'.$this->redondeado($total,2).'</td>';
									}//if $datos!='0'

/////////////////TERMINA RANGO///////////////////////////////////////////////////

		 
///////////////INICIA ALGUNOS PERO SIN RANGO///////////////////////////////
							}//if $var !== false
							else{
					
					
								if( isset($_REQUEST['delperiodo']) ){
									$datos = $this->RepPeriodoAcreditamientoModel->cargadatos($_REQUEST['ejercicio'],$_REQUEST['delperiodo'],$_REQUEST['alperiodo'],$num,$aplica);
								}
								if( isset($_REQUEST['inicio']) ){
									$datos = $this->RepPeriodoAcreditamientoModel->cargadatos2($_REQUEST['ejercicio'],$_REQUEST['inicio'],$_REQUEST['fin'],$num,$aplica);
								}	
							//echo $num;
								if( $datos != '0' ){
									$i = 1;
									if($d = $datos->fetch_assoc())
								   {
									   	////para totalgeneral
									   	$tasa=($d['tasa']/100)+1;
									   	if($importebasetotal!=0){
										   	$importebasetotal=$importebasetotal+($d['importe']/$tasa);
											$acreditabletotal=$acreditabletotal+($d['importe']-($d['importe']/$tasa));
											$totalfinal=$totalfinal+$d['importe'];
										}else{
											$importebasetotal=($d['importe']/$tasa);
											$acreditabletotal=$d['importe']-$importebasetotal;
											$totalfinal=$d['importe'];
										}
		   	///////////////////////
										$importebase=($d['importe']/$tasa);
										$acreditable=$d['importe']-$importebase;
										$total=$d['importe'];
										$razon=$d['razon_social'];
										$ejerci=$d['NombreEjercicio'];
										 
										$contenido.= '<tr class="prove" >
												 <td colspan="12">'.$d['idProveedor'].' '.$d['razon_social'].'</td></tr>
												 <tr class="busqueda_fila">
												 <td align="center">'.$d['fecha'].'</td>
												 <td align="center">'.$d['titulo'].'</td>
												 <td align="center" class="tdlink"><a href="javascript: vistalink('.$d['id'].','.$d['idProveedor'].');" class="detalle" >'.$d['id'].'</a></td>
												 <td align="center">'.$d['concepto'].'</td>
												 <td align="center">'.$d['NombreEjercicio'].'</td>
												 <td align="center">'.$p1=$this->mes($d['idperiodo']).'</td>
												 <td>'.$this->redondeado($importebase,2).'</td>
												 <td>'.$this->redondeado($acreditable,2).'</td>
												 <td>'.$this->redondeado($d['importe'],2).'</td>
											<tr>';	
		  					 	}	$i=1;


	  							while($d = $datos->fetch_assoc())
		   						{
								  	$tasa2 = ($d['tasa'] / 100) + 1;	
									$importebase2 = ($d['importe'] / $tasa2);
									$acreditable2 = $d['importe'] - $importebase2;
									
									//if($i<count($d)-1){
									if($razon == $d['razon_social']){
										$fil = ''; $fil2 = ''; 
										$acreditable = $acreditable + $acreditable2;
										$importebase = $importebase + $importebase2;
										$total =	$total + $d['importe'];
									}else{
											
										$fil=' <tr class="prove" >
										<td colspan="12">'.$d['idProveedor'].' '.$d['razon_social'].'</td></tr>';
										
										$razon = $d['razon_social'];
										
										$fil2 = '<tr class="busqueda_fila"  >
										<td colspan="6" style="text-align:right;">Total<td class="total">
										'.$this->redondeado($importebase,2).'</td><td class="total">
										'.$this->redondeado($acreditable,2).'</td><td class="total">
										'.$this->redondeado($total,2).'</td>'; 
										
										$importebase = ($d['importe'] / $tasa2);
										$acreditable = $d['importe'] - $importebase;
										$total = $d['importe'];
	
										 };
							 
					$contenido.= $fil2.''.$fil.'
						<tr class="busqueda_fila">
							<td align="center">'.$d['fecha'].'</td>
							<td align="center">'.$d['titulo'].'</td>
							<td align="center" class="tdlink"><a href="javascript: vistalink('.$d['id'].','.$d['idProveedor'].');" class="" >'.$d['id'].'</a></td>
							<td align="center">'.$d['concepto'].'</td>
							<td align="center">'.$d['NombreEjercicio'].'</td>
							<td align="center">'.$p1 = $this->mes($d['idperiodo']).'</td>
							<td>'.$this->redondeado($importebase2,2).'</td>
							<td>'.$this->redondeado($acreditable2,2).'</td>
							<td>'.$this->redondeado($d['importe'],2).'</td>
						</tr>
					 ';	
		   
					    $importebasetotal = $importebasetotal + ($d['importe'] / $tasa2);
						$acreditabletotal = $acreditabletotal + ($d['importe'] - $importebase2);
						$totalfinal = $totalfinal + $d['importe'];
					
		  			 }//while
		  
				 $contenido.= '<tr class="busqueda_fila"  >
							<td colspan="6" style="text-align:right;">Total</td>
							<td class="total">
							'.$this->redondeado($importebase,2).'</td><td class="total">
							'.$this->redondeado($acreditable,2).'</td><td class="total">
							'.$this->redondeado($total,2).'</td>';
				}//$datos != '0' 
// 	 
			}//else algunos peor sin rango

//////////////////TERMINA ALGUNOS SIN RANGO///////////////////////////////////////////

		}/////////////////TERMINA FOREACH
			/////TOTAL FINALLL//////////////////////////////////////////////////
			$contenido.= '<tr></tr><tr class="busqueda_fila"  >
					<td class="cierre" colspan="6" style="text-align:right;">TOTAL </td>
					<td class="total" >
			
					'.$this->redondeado($importebasetotal,2).'</td><td class="total">
					'.$this->redondeado($acreditabletotal,2).'</td><td class="total">
					'.$this->redondeado($totalfinal,2).'</td>';
					//////////////////////////////////////////////////////////////////////
///////////////////SI SON TODOS LOS PROVEEDORES/////////////////////
		}else{
			if(isset($_REQUEST['delperiodo'])){	  
				$datos = $this->RepPeriodoAcreditamientoModel->cargadatos($_REQUEST['ejercicio'],$_REQUEST['delperiodo'],$_REQUEST['alperiodo'],$_REQUEST['proveedores' ],$aplica);
			}
			if(isset($_REQUEST['inicio'])){
				$datos = $this->RepPeriodoAcreditamientoModel->cargadatos2($_REQUEST['ejercicio'],$_REQUEST['inicio'],$_REQUEST['fin'],$_REQUEST['proveedores' ],$aplica);
			}
			//echo '//<td>cuando son todos</td>';
					
			if($datos!='0'){
			  if($d = $datos->fetch_assoc())
		  	  {
		  	  	$tasa=($d['tasa']/100)+1;
				$importebase=($d['importe']/$tasa);
				$acreditable=$d['importe']-$importebase;	
				$total=$d['importe'];

				$importebasetotal=($d['importe']/$tasa);
				$acreditabletotal=$d['importe']-$importebasetotal;
				$totalfinal=$d['importe'];

				$razon=$d['razon_social'];
				$ejerci=$d['NombreEjercicio'];
		$contenido.= '
		 		<tr class="prove"><td colspan="12">'.$d['idProveedor'].' '.$d['razon_social'].'</td></tr>
					 <tr class="busqueda_fila">
					 <td align="center">'.$d['fecha'].'</td>
					 <td align="center">'.$d['titulo'].'</td>
					 <td align="center" class="tdlink"><a href="javascript: vistalink('.$d['id'].','.$d['idProveedor'].');" class="detalle" >'.$d['id'].'</a></td>
					 <td align="center">'.$d['concepto'].'</td>
					 <td align="center">'.$d['NombreEjercicio'].'</td>
					 <td align="center">'.$p1=$this->mes($d['idperiodo']).'</td>
					 <td>'.$this->redondeado($importebase,2).'</td>
					 <td>'.$this->redondeado($acreditable,2).'</td>
					 <td>'.$this->redondeado($d['importe'],2).'</td>
				<tr>';	
		   	}	$i=1;

	 while($d = $datos->fetch_assoc())
	 {
		  	$tasa2=($d['tasa']/100)+1;	
			$importebase2=($d['importe']/$tasa2);
			$acreditable2=$d['importe']-$importebase2;

			if($razon==$d['razon_social']){ $fil=''; $fil2=''; 
				$acreditable=$acreditable+$acreditable2;
				$importebase=$importebase+$importebase2;
				$total=	$total+$d['importe'];
			}else{
				$fil='<tr class="prove" ><td colspan="12">'.$d['idProveedor'].' '.$d['razon_social'].'</td></tr>';
				
				$razon=$d['razon_social'];
				
				$fil2='<tr class="busqueda_fila"  >
				<td colspan="6" style="text-align:right;">Total</td>
				<td class="total">
				'.$this->redondeado($importebase,2).'</td><td class="total">
				'.$this->redondeado($acreditable,2).'</td><td class="total">
				'.$this->redondeado($total,2).'</td>'; 
			
				$importebase=($d['importe']/$tasa2);
				$acreditable=$d['importe']-$importebase;
				$total=$d['importe'];
	
				}
							 
		     $contenido.=$fil2.''.$fil.'
		 			<tr class="busqueda_fila">
						<td align="center">'.$d['fecha'].'</td>
						<td align="center">'.$d['titulo'].'</td>
						<td align="center" class="tdlink"><a href="javascript: vistalink('.$d['id'].','.$d['idProveedor'].');" class="detalle" >'.$d['id'].'</a></td>
						<td align="center">'.$d['concepto'].'</td>
						<td align="center">'.$d['NombreEjercicio'].'</td>
						<td align="center">'.$p1=$this->mes($d['idperiodo']).'</td>
						<td>'.$this->redondeado($importebase2,2).'</td>
						<td>'.$this->redondeado($acreditable2,2).'</td>
						<td>'.$this->redondeado($d['importe'],2).'</td>
					</tr>
					 ';	
		   // }		else{
				   	// echo '//<tr class="busqueda_fila"  >
					// <td></td><td></td><td></td><td></td><td></td><td></td>
					// <td style="font-weight: bold;background:#424242;">
					// '.$importebase.'</td><td style="font-weight: bold;background:#424242;">'.$acreditable.'</td><td style="font-weight: bold;background:#424242;">'.$total.'</td>
					// ';
		   			// }
		//$i++;
			$importebasetotal=$importebasetotal+($d['importe']/$tasa2);
			$acreditabletotal=$acreditabletotal+($d['importe']-$importebase2);
			$totalfinal=$totalfinal+$d['importe'];
		
   }//while
		 	$contenido.= '<tr class="busqueda_fila"  >
					<td colspan="6" style="text-align:right;">Total</td>
					<td class="total">
					'.$this->redondeado($importebase,2).'</td><td class="total">
					'.$this->redondeado($acreditable,2).'</td><td class="total">
					'.$this->redondeado($total,2).'</td><tr></tr>
					<tr class="busqueda_fila">
					<td class="cierre" colspan="6" style="text-align:right;">TOTAL </td>
					<td class="total" >
					'.$this->redondeado($importebasetotal,2).'</td><td class="total">
					'.$this->redondeado($acreditabletotal,2).'</td><td class="total">
					'.$this->redondeado($totalfinal,2).'</td>';
		}
			
		 else{
			$contenido.=  '<td height="30%" colspan="12"  class="nodato">
					      No hay datos que coincidan con la busqueda</td>';
		 }
		} 	 
		//echo 'siiiiiii';
		//resul
return $ejerci.'//'.$contenido;
		
	}

 	function detallado($idpoliza,$idProveedor){
 		
	$datos= $this->RepPeriodoAcreditamientoModel->detalladomo($idpoliza,$idProveedor);
	$empr= $this->RepPeriodoAcreditamientoModel->empresa();
	if($empre=$empr->fetch_assoc()){
		$direccion='Direccion: '.$empre['domicilio'].'  Codigo Postal: '.$empre['cp'];
	}
	$valor2='';
	$cargo=0;
	$abono=0;
		 if($d = $datos->fetch_assoc())
		   {if($d['TipoMovto']=='Cargo'){ $cargo=$d['importe']; $fila='<td align="center">'.$d['importe'].'</td><td></td>';}
			if($d['TipoMovto']=='Abono'){ $abono=$d['importe']; $fila='<td></td><td align="center">'.$d['importe'].'</td>';}
		   	////////////
		   	
		 $valor= '<tr class="busqueda_fila" >
		   			<td align="center">'.$d['fecha'].'</td>
		   			<td></td>
		   			<td >'.$d['titulo'].'</td>
		   			<td></td>
		   			<td align="center">'.$d['numpol'].'</td>
		   			<td></td>
		   			<td >'.$d['concepto'].'</td>
		   			<td></td>
		   			<td></td>
		   		</tr>
		   		<tr class="busqueda_fila" >
					 <td align="center">1</td>
					 <td>'.$d['Referencia'].'</td>
					 <td></td>
					 <td align="center">'.$d['manual_code'].'</td>
					 <td></td>
					 <td align="center">'.$d['description'].'</td>
					 <td></td>
					 '.$fila.'
					
				  </tr>
		   			';
			
						
		   }
		$i=2;
		while($d = $datos->fetch_assoc())
		   {
		   	
		   	if($d['TipoMovto']=='Cargo'){
		   		if($cargo!=0){$cargo=$cargo+$d['importe'];}
					else{$cargo=$d['importe'];}
					 $fila='<td align="center">'.$d['importe'].'</td><td></td>';}
			if($d['TipoMovto']=='Abono'){
				if($abono!=0){$abono=$abono+$d['importe'];}
					else{$abono=$d['importe'];}
				  $fila='<td></td><td align="center">'.$d['importe'].'</td>';}
		$valor.= '<tr class="busqueda_fila" >
					 <td align="center">'.$i.'</td>
					 <td>'.$d['Referencia'].'</td>
					 <td></td>
					 <td align="center">'.$d['manual_code'].'</td>
					 <td></td>
					 <td align="center">'.$d['description'].'</td>
					 <td></td>
					 '.$fila.'
				  </tr>
					 ';
			$i++;
			
		   }
				$valor.='<tr  style="text-align: right;" ><td colspan="7">TOTAL POLIZA:</td><td>'.$cargo.'</td><td>'.$abono.'</td></tr>';
//informacion de diot////
$datos2= $this->RepPeriodoAcreditamientoModel->detallado2($idpoliza,$idProveedor);
while($d = $datos2->fetch_assoc())
		   {
				$seis=$d['importe']-$d['importeBase'];
						
						$valor2.='<tr>
							<td>'.$d['idProveedor' ].'~'.$d['razon_social'].'</td>
							<td align="center">'.$d['importe'].'</td>
							<td align="center">'.$d['valor'].'%'.'</td>
							<td>'.$d['importeBase'].'</td>
							<td>'.$seis.'</td>
							<td>'.$d['otrasErogaciones'].'</td>
							<td>'.$d['ivaRetenido'].'</td>
							<td>'.$d['isrRetenido'].'</td>
							<td>'.$d['ivaPagadoNoAcreditable'].'</td>
							<td>SI</td>
							<td>'.$p1=$this->mes($d['periodoAcreditamiento']).'-'.$d['NombreEjercicio'].'</td>
						</tr>	';							
		   }		
				$valor.='</table><br></br>I N F O R M A C I Ó N &nbsp;&nbsp;P A R A &nbsp;&nbsp;D I O T';
		  		$valor.='<br></br><table align="center" class="busqueda" id="detallado" width="100%" cellpadding="1" cellspacing="1" border="1">
						<th>Prov.</th>
						<th>Total Erogacion</th>
						<th align="center">Tasa</th>
						<th>Importe Base</th>
						<th>Importe IVA</th>
						<th>Otras Erogaciones</th>
						<th>IVA retenido</th>
						<th>ISR retenido</th>
						<th>IVA no acreditable</th>
						<th>Aplica IVA</th>
						<th>Periodo acreditamiento</th>
						'.$valor2.'
						</table>';
		  
		   return $direccion.'//'.$valor;
		
 	}

///////////////////////////////
function redondeado ($numero, $decimales) {
   return (round($numero,$decimales)); } 

function organizacion(){
	$empr= $this->RepPeriodoAcreditamientoModel->empresa();
	if($empre=$empr->fetch_assoc()){
		return '<img src="../../../webapp/netwarelog/archivos/1/organizaciones/'.$empre['logoempresa'].'" width="40px" height="40px"/>'.$empre['nombreorganizacion'];
	}

}
function mes($idperiodo){
			$anio = Array(
		'Enero',
		'Febrero',
		'Marzo',
		'Abril',
		'Mayo',
		'Junio',
		'Julio',
		'Agosto',
		'Septiembre',
		'Octubre',
		'Noviembre',
		'Diciembre');
		$p1 = $anio[ ($idperiodo-1)];
	return $p1;
}



}


?>