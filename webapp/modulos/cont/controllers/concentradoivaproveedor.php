<?php
  require('common.php');

//Carga el modelo para este controlador
require("models/ConcentradoIVAProveedor.php");

class ConcentradoIVAProveedor extends Common
{
	public $ConcentradoIVAProveedorModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->ConcentradoIVAProveedorModel = new ConcentradoIVAProveedorModel();
		
	
	}
	function verconcentrado()
	{
		
		require('views/fiscal/diot/ConcentradoIVAporProveedor.php');
		
	}
	function contenido(){
		$empresa = $this->organizacion();
		$logo=$this->logo();

		if( isset($_REQUEST['delperiodo' ]) ){
			$p1=$_REQUEST['delperiodo' ];
			$p2=$_REQUEST['alperiodo'];
			$e=$_REQUEST['ejercicio'];
			$periodoAcreditamiento='1'; 
			$delperiodo=$this->mes($_REQUEST['delperiodo']);
			$alperiodo=$this->mes($_REQUEST['alperiodo']);
			$datos=$this->concentrado($_REQUEST['ejercicio'],$_REQUEST['delperiodo'],$_REQUEST['alperiodo'],$_REQUEST['proveedores'],$_REQUEST['aplica']);
			//$r=explode('//', $datos);
			$muestra=$_REQUEST['t'];
			$dato=$datos[0];
			$taenviar=$datos[1];
			$periodo='Periodo de Acreditamiento de '.$delperiodo.' a '.$alperiodo.' ';
			$fecha=$_REQUEST['fechaimprecion'];
			
		}
		if( isset($_REQUEST['inicio']) ){
			$periodoAcreditamiento='';
			$inicio=$_REQUEST['inicio'];
			$fin=$_REQUEST['fin'];//krmn
			$datos=$this->concentrado($_REQUEST['ejercicio'],$_REQUEST['inicio'],$_REQUEST['fin'],$_REQUEST['proveedores'],$_REQUEST['aplica']);
			//$r=explode('//', $datos);
			$dato=$datos[0];
			$muestra=$_REQUEST['t'];
			$taenviar=$datos[1];
			$periodo='Del '.$inicio.' al '.$fin;
			$fecha=$_REQUEST['fechaimprecion'];

			
		}
		 require('views/fiscal/diot/RepConcentradoIvaProveedor.php');
	}
function contenidoexcel(){
		$empresa = $this->organizacion();
		if( isset($_REQUEST['delperiodo' ]) ){
			$p1=$_REQUEST['delperiodo' ];
			$p2=$_REQUEST['alperiodo'];
			$e=$_REQUEST['ejercicio'];
			$periodoAcreditamiento='1'; 
			$delperiodo=$this->mes($_REQUEST['delperiodo']);
			$alperiodo=$this->mes($_REQUEST['alperiodo']);
			$datos=$this->concentrado($_REQUEST['ejercicio'],$_REQUEST['delperiodo'],$_REQUEST['alperiodo'],$_REQUEST['proveedores'],$_REQUEST['aplica']);
			//$r=explode('//', $datos);
			$dato=$datos[0];
			$taenviar=$datos[1];
			$periodo='Periodo de Acreditamiento de '.$delperiodo.' a '.$alperiodo.' ';
			$fecha=$_REQUEST['fechaimprecion'];
			
		}
		if( isset($_REQUEST['inicio']) ){
			$periodoAcreditamiento='';
			$inicio=$_REQUEST['inicio'];
			$fin=$_REQUEST['fin'];
			$datos=$this->concentrado($_REQUEST['ejercicio'],$_REQUEST['inicio'],$_REQUEST['fin'],$_REQUEST['proveedores'],$_REQUEST['aplica']);
			//$r=explode('//', $datos);
			$dato=$datos[0];
			$taenviar=$datos[1];
			$periodo='Del '.$inicio.' al '.$fin;
			$fecha=$_REQUEST['fechaimprecion'];

			
		}
		 require('views/fiscal/diot/excel/ConcentradoIVAporProveedorexcel.php');
	}
	function concentrado($ejercicio,$delperiodo,$alperiodo,$proveedores,$aplica){
		//$fechaimprecion = $fechaimprecion;
		$contenido='';
		if( $proveedores!='' ){
			$ejer = explode( ',', $proveedores );
			foreach( $ejer as $num ){
				$var = strrpos( $num,'-' );
					if( $var !== false){
						$v2 = str_replace('-',' and ',$num);
						$fecha = strrpos( $delperiodo,'-' );
						if( $fecha == false){
							$datos = $this->ConcentradoIVAProveedorModel->tasaacredita($ejercicio,$delperiodo,$alperiodo,$v2,$aplica);
						}
						else{
							$datos = $this->ConcentradoIVAProveedorModel->tasaacredita2($ejercicio,$delperiodo,$alperiodo,$v2,$aplica);
						}
						
						
						if($datos->num_rows>0){
									$i=0;
						
									$contenido=array();
										//$razon='';
										$cont=0;
									while($d = $datos->fetch_array(MYSQLI_ASSOC)){
										//$d2=array();
										$d3=array();
										if($cont==0){
											$contenido[$d['razon_social']]=$d;
											$razon=$d['razon_social'];
										}
									if($razon!=$d['razon_social'] && $cont!=0){
										$contenido[$d['razon_social']]=$d;
										$razon=$d['razon_social'];
									}
									if( $fecha == false){
										$suma = $this->ConcentradoIVAProveedorModel->suma($d['idProveedor'],$d['idtasa'],$ejercicio,$delperiodo,$alperiodo);
									}
									else{
										$suma = $this->ConcentradoIVAProveedorModel->suma2($d['idProveedor'],$d['idtasa'],$ejercicio,$delperiodo,$alperiodo);
									}
										$mastas = $this->ConcentradoIVAProveedorModel->otrastasas($d['idProveedor'],$d['tasa']);
										//if (!array_key_exists($d['tasa'], $contenido[$d['razon_social']]['tasas'])) {
												$contenido[$d['razon_social']]['tasas'][$d['tasa']]=$d;
												$tasas[$d['razon_social']][$d['valor']]=$d['valor'];
											//}
										while($d2 = $mastas->fetch_array(MYSQLI_ASSOC)){
											if (!array_key_exists($d2['tasa'], $contenido[$d['razon_social']]['tasas'])) {
												$contenido[$d['razon_social']]['tasas'][$d2['tasa']]=0;
											}
										}
										//$contenido[$d['razon_social']]['mastasa']=$d2;
										while($d3[] = $suma->fetch_array(MYSQLI_ASSOC)){
										}
										$contenido[$d['razon_social']]['suma']=$d3;
										
										unset($d2);
										unset($d3);
											
										$cont++;
									}
									
								}	
					   
					}//if false
//krmn///////////////////////////////////////	
							else{//unos sin rango
						
									$fecha = strrpos( $delperiodo,'-' );
									if( $fecha == false){
										$datos = $this->ConcentradoIVAProveedorModel->tasaacredita($ejercicio,$delperiodo,$alperiodo,$num,$aplica);
									}
									else{
										$datos = $this->ConcentradoIVAProveedorModel->tasaacredita2($ejercicio,$delperiodo,$alperiodo,$num,$aplica);
									}
									if($datos->num_rows>0){
									$i=0;
						
									//$contenido=array();
										//$razon='';
										$cont=0;
									while($d = $datos->fetch_array()){
										$d2=array();//krmn
										$d3=array();
										if($cont==0){
											$contenido[$d['razon_social']]=$d;
											$razon=$d['razon_social'];
										}
									if($razon!=$d['razon_social'] && $cont!=0){
										$contenido[$d['razon_social']]=$d;
										$razon=$d['razon_social'];
									}
									if( $fecha == false){
										$suma = $this->ConcentradoIVAProveedorModel->suma($d['idProveedor'],$d['idtasa'],$ejercicio,$delperiodo,$alperiodo);
									}
									else{
										$suma = $this->ConcentradoIVAProveedorModel->suma2($d['idProveedor'],$d['idtasa'],$ejercicio,$delperiodo,$alperiodo);
									}
										$mastas = $this->ConcentradoIVAProveedorModel->otrastasas($d['idProveedor'],$d['tasa']);
										//if (!array_key_exists($d['tasa'], $contenido[$d['razon_social']]['tasas'])) {
												$contenido[$d['razon_social']]['tasas'][$d['tasa']]=$d;
												$tasas[$d['razon_social']][$d['valor']]=$d['valor'];
											//}
										while($d2 = $mastas->fetch_array()){
											if (!array_key_exists($d2['tasa'], $contenido[$d['razon_social']]['tasas'])) {
												$contenido[$d['razon_social']]['tasas'][$d2['tasa']]=0;
											}
										}
										//$contenido[$d['razon_social']]['mastasa']=$d2;
										while($d3[] = $suma->fetch_array()){
										}
										$contenido[$d['razon_social']]['suma']=$d3;
										
										unset($d2);
										unset($d3);
											
										$cont++;
									}
									
								}
							}
						}//false	
					}else{//todos los proveedores
						
								$fecha = strrpos( $delperiodo,'-' );
								if( $fecha == false){
									
									$datos = $this->ConcentradoIVAProveedorModel->tasaacredita($ejercicio,$delperiodo,$alperiodo,$proveedores,$aplica);
								}
								else{
									$datos = $this->ConcentradoIVAProveedorModel->tasaacredita2($ejercicio,$delperiodo,$alperiodo,$proveedores,$aplica);
								}
								if($datos->num_rows>0){
									$i=0;
						
									$contenido=array();
										//$razon='';
										$tasas=array();
										$cont=0;
										$t=1;
									while($d = $datos->fetch_array(MYSQLI_ASSOC)){
										//$d2=array();
										$d3=array();
										if($cont==0){
											$contenido[$d['razon_social']]=$d;
											$razon=$d['razon_social'];
										}
										if($razon!=$d['razon_social'] && $cont!=0){
											$contenido[$d['razon_social']]=$d;
											$razon=$d['razon_social'];
										}
										if( $fecha == false){
										
											$suma = $this->ConcentradoIVAProveedorModel->suma($d['idProveedor'],$d['idtasa'],$ejercicio,$delperiodo,$alperiodo);
										}
										else{
											$suma = $this->ConcentradoIVAProveedorModel->suma2($d['idProveedor'],$d['idtasa'],$ejercicio,$delperiodo,$alperiodo);
										}
										$mastas = $this->ConcentradoIVAProveedorModel->otrastasas($d['idProveedor'],$d['tasa']);
										//if (!array_key_exists($d['tasa'], $contenido[$d['razon_social']]['tasas'])) {
												$contenido[$d['razon_social']]['tasas'][$d['tasa']]=$d;
												$tasas[$d['razon_social']][$d['valor']]=$d['valor'];
											//}
										while($d2 = $mastas->fetch_array(MYSQLI_ASSOC)){
											if (!array_key_exists($d2['tasa'], $contenido[$d['razon_social']]['tasas'])) {
												$contenido[$d['razon_social']]['tasas'][$d2['tasa']]=0;
											}
										}
										//$contenido[$d['razon_social']]['mastasa']=$d2;
										while($d3[] = $suma->fetch_array(MYSQLI_ASSOC)){
										}
										$contenido[$d['razon_social']]['suma']=$d3;
										
										unset($d2);
										unset($d3);
											
										$cont++;
										$t++;
									}
									
								}
							}//else
			//print_r($contenido);
			
	return array($contenido,$tasas);
	//return $tasas;
	}

function organizacion(){
		$empr= $this->ConcentradoIVAProveedorModel->empresa();
		if($empre=$empr->fetch_assoc()){
			return $empre['nombreorganizacion'];
		}
}

function logo(){
		$log=$this->ConcentradoIVAProveedorModel->empresa();
		$logo1=$log->fetch_assoc();
		return $logo1['logoempresa'];
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