<?php
include dirname(__FILE__)."/html2pdf/html2pdf.class.php";
include dirname(__FILE__)."/phpqrcode/qrlib.php";
include dirname(__FILE__)."/EnLetras.php";


class CFDIPDF{
	var $pdf;
	var $datosCFD;
	var $datosEmisor;
	var $datosReceptor;
	var $contentConceptos;
	var $total;
	var $metodoPago;
	var $selloDigitales;
	var $lugarExp;
	var $lines;
	var $carray;
	var $color;
	var $observaciones;

	function __construct(){
		$this->pdf = new HTML2PDF('P', 'A4', 'fr'); 
		$this->datosCFD=array('','','','','','','');
		$this->datosEmisor=array('','','','','','','','','','');
		$this->datosReceptor=array('','','','','','','','','');
		$this->contentConceptos="";
		$this->total=array('','','');
		$this->metodoPago=array('','','');
		$this->selloDigitales=array('','','');
		$this->lugarExp="";
		$this->lines=0;
		$this->carray=array();
		$this->color="#D8D8D8";
		$this->observaciones="";
	}

	function __destruct(){
	}  
	
	function logo($imagen){

	}

	function ponerColor($color){
		$this->color=$color;
	}

	function datosCFD($folioF,$serieF,$serieCSDE,$fechaHE,$fechaHC,$serieCSDS,$formaP,$tipoF,$titulo="",$moneda){
		$this->datosCFD[0]=$folioF;
		$this->datosCFD[1]=$serieF;
		$this->datosCFD[2]=$serieCSDE;
		$this->datosCFD[3]=$fechaHE;
		$this->datosCFD[4]=$fechaHC;
		$this->datosCFD[5]=$serieCSDS;
		$this->datosCFD[6]=$formaP;
		$this->datosCFD[7]='';
		$this->datosCFD[69]=$moneda;
		if($tipoF=="ingreso")
			$documento = $this->tipoDoc();
			if($documento==1 || $documento == '1'){
				$this->datosCFD[7]="Factura";
			}else{
				$this->datosCFD[7]="Recibo de Ingresos";
			}
			//$this->datosCFD[7]="Factura";
		if($tipoF=="egreso")
			$this->datosCFD[7]="Nota de Crédito"; 
		if($titulo=="recibo")
			$this->datosCFD[7]="Recibo De Ingresos"; 
	}

	function lugarE($pais){
		$this->lugarExp=$pais;	
	}

	function datosEmisor($razonS,$RFC,$calleN,$ciudad,$colonia,$delegacion,$estado,$codigoP,$pais,$regimenF){
		$this->datosEmisor[0]=$razonS;
		$this->datosEmisor[1]=$RFC;
		$this->datosEmisor[2]=$calleN;
		$this->datosEmisor[3]=$ciudad;
		$this->datosEmisor[4]=$colonia;
		$this->datosEmisor[5]=$delegacion;
		$this->datosEmisor[6]=$estado;
		$this->datosEmisor[7]=$codigoP;
		$this->datosEmisor[8]=$pais;
		$this->datosEmisor[9]=$regimenF;
	}

	function datosReceptor($razonS,$RFC,$calleN,$ciudad,$colonia,$delegacion,$estado,$codigoP,$pais){
		$this->datosReceptor[0]=$razonS;
		$this->datosReceptor[1]=$RFC;
		$this->datosReceptor[2]=$calleN;
		$this->datosReceptor[3]=$ciudad;
		$this->datosReceptor[4]=$colonia;
		$this->datosReceptor[5]=$delegacion;
		$this->datosReceptor[6]=$estado;
		$this->datosReceptor[7]=$codigoP;
		$this->datosReceptor[8]=$pais;
	}

	function agregarConcepto($cantidad,$unidad,$descripcion,$precio,$importe){

		$this->lines+=substr_count('<div style="width:380px">'.wordwrap($descripcion,65,"</div><div style=\"width:380px\">",true).'</div>','<div');
		$this->contentConceptos.='<div><table style="border-collapse:collapse"><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;text-align:right;padding-right:3px">'.number_format((float)$cantidad,2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;padding-left:3px">'.$unidad.'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:383px"><div style="width:381px;padding-top:-2px">'.wordwrap($descripcion,65,"</div><div style=\"width:380px;margin-top:3px\">",true).'</div></td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:75px;text-align:right;padding-right:3px">'.number_format((float)$precio,2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';width:93px;text-align:right">'.number_format((float)$importe,2,'.',',').'</td></tr></table></div>';
	}

	function agregarConceptos($cadena){
	
		$cadena=substr($cadena,1);
		$conceptos=explode("|",$cadena);
		$limit=count($conceptos);

		for($contador=0;$contador<$limit;){
			$this->lines+=substr_count('<div style="width:380px">'.wordwrap($conceptos[$contador+2],65,"</div><div style=\"width:380px\">",true).'</div>','<div');
			$this->contentConceptos.='<div><table style="border-collapse:collapse"><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;text-align:right;padding-right:3px">'.number_format((float)$conceptos[$contador],2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;padding-left:3px">'.$conceptos[$contador+1].'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:383px"><div style="width:381px;padding-top:-2px">'.wordwrap($conceptos[$contador+2],76,"</div><div style=\"width:380px;margin-top:3px\">",true).'</div></td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:75px;text-align:right;padding-right:3px">'.number_format((float)$conceptos[$contador+3],2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';width:93px;text-align:right">'.number_format((float)$conceptos[$contador+4],2,'.',',').'</td></tr></table></div>';
			$contador+=5;
		}
	}

	function borrarConcepto(){
		$this->contentConceptos='';
	}

		/*function agregarTotal($subtotal=0.00,$iva=0.00,$isr=0.00,$ieps=0.00,$total=0.00){
			$this->total[0]=number_format((float)$subtotal,2,'.',',');	
			$this->total[1]=number_format((float)$iva,2,'.',',');
			$this->total[2]=number_format((float)$isr,2,'.',',');
			$this->total[3]=number_format((float)$ieps,2,'.',',');
			$this->total[4]=number_format((float)$total,2,'.',',');
			$this->total[5]=$total;
		}*/
		
		function agregarTotal($subtotal=0.00,$total=0.00,array $cadena){
			$this->total[0]=number_format((float)$subtotal,2,'.',',');	
			$this->total[1]=number_format((float)$total,2,'.',',');
			$this->total[2]=$total;
			$this->carray=$cadena;
		}
		
		function agregarMetodo($metodoP,$cuenta,$moneda){
			$obj=new EnLetras();
			if($moneda!='MXN'){
                $a=$moneda;
                $b='';

            }else{
                $a='pesos';
                $b='M.N';
            }
			$total=strtoupper($obj->ValorEnLetras($this->total[2],$a,$b)); 
			$this->metodoPago[0]=$metodoP;	
			$this->metodoPago[1]=$cuenta;
			$this->metodoPago[2]=$total;
		}
		
		function agregarSellos($cadenaO,$selloDE,$selloDS){
			$this->selloDigitales[0]=$cadenaO;	
			$this->selloDigitales[1]=$selloDE;
			$this->selloDigitales[2]=$selloDS;
		}
		
		function agregarObservaciones($observaciones){
			$this->observaciones=$observaciones;
		}

		function generar($imagen, $plantilla=0, $positionPath="",$facturacion=true){
			
			$imagesize=getimagesize($imagen);
			$porcentaje=0;
			if($imagesize[0]>300 && $imagesize[1]>150){
				if($imagesize[0]>$imagesize[1]){
					$porcentaje=intval(($imagesize[1]*100)/$imagesize[0]);
					$imagesize[0]=300;
					$imagesize[1]=(($porcentaje*300)/100);
				}else{
					$porcentaje=intval(($imagesize[0]*100)/$imagesize[1]);
					$imagesize[0]=300;
					$imagesize[1]=(($porcentaje*300)/100);	
				}
			}
			
			$src="";
			if($imagen!="" && file_exists($imagen))
				$src='<img src="'.$imagen.'" style="width:'.$imagesize[0].'px;height:'.$imagesize[1].'px"/>';	
			
			switch($plantilla){
				case 0:												
				$content='<html><head><title>PDF</title></head><body><table><tr><td><div style="width:300px;height:150px">'.$src.'</div></td><td><div style="margin-left:76px"><table><tr><td><div style="margin-left:12px"><table><tr><td style="font-weight:bold;font-size:11px">Folio Fiscal</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[0].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Serie y Folio</td></tr><tr><td style="font-size:8px;color:#ff0000">'.$this->datosCFD[1].'</td></tr><tr><td style="font-weight:bold;font-size:11px">No. de serie del CSD del emisor</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[2].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Fecha y Hora de emision</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[3].'</td></tr></table></div></td><td><div style="margin-left:20px;margin-bottom:35px"><table><tr><td style="font-weight:bold;font-size:11px">Fecha y hora de certificacion</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[4].'</td></tr><tr><td style="font-weight:bold;font-size:11px">No. de serie del CSD del SAT</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[5].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Forma de Pago</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[6].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Tipo</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[7].'</td></tr></table></div></td></tr></table></div></td></tr></table><div style="font-size:10px;margin-bottom:15px">Lugar De Expedicion: '.$this->lugarExp.'</div><div style="font-weight:bold;font-size:11px;background:'.$this->color.'">Emisor</div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Razon Social:</td><td style="width:550px;font-size:10px">'.$this->datosEmisor[0].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">R.F.C:</td><td style="width:90px;font-size:11px">'.$this->datosEmisor[1].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Calle y Numero:</td><td style="width:218px;font-size:10px">'.$this->datosEmisor[2].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Ciudad:</td><td style="width:138px;font-size:10px">'.$this->datosEmisor[3].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Colonia:</td><td style="width:221px;font-size:10px">'.$this->datosEmisor[4].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Delegacion:</td><td style="width:180px;font-size:10px">'.$this->datosEmisor[5].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Estado:</td><td style="width:153px;font-size:10px">'.$this->datosEmisor[6].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Codigo postal:</td><td style="width:25px;font-size:10px">'.$this->datosEmisor[7].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Pais:</td><td style="width:178px;font-size:10px">'.$this->datosEmisor[8].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Regimen Fiscal:</td><td style="width:670px;font-size:10px">'.$this->datosEmisor[9].'</td></tr></table></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.'">Receptor</div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Razon Social:</td><td style="width:550px;font-size:10px">'.$this->datosReceptor[0].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">R.F.C:</td><td style="width:90px;font-size:11px">'.$this->datosReceptor[1].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Calle y Numero:</td><td style="width:218px;font-size:10px">'.$this->datosReceptor[2].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Ciudad:</td><td style="width:138px;font-size:10px">'.$this->datosReceptor[3].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Colonia:</td><td style="width:221px;font-size:10px">'.$this->datosReceptor[4].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Delegacion:</td><td style="width:180px;font-size:10px">'.$this->datosReceptor[5].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Estado:</td><td style="width:153px;font-size:10px">'.$this->datosReceptor[6].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Codigo postal:</td><td style="width:25px;font-size:10px">'.$this->datosReceptor[7].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Pais:</td><td style="width:178px;font-size:10px">'.$this->datosReceptor[8].'</td></tr></table></div><div><table><tr><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:84px">Cantidad</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:84px">Unidad</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:385px">Descripcion</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:78px">Precio</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:95px">Importe</td></tr></table></div>';
				break;
				
				case 1:
				$content='<html><head><title>PDF</title></head><body><table><tr><td><div style=""><table><tr><td><div style="margin-left:12px"><table><tr><td style="font-weight:bold;font-size:11px">Folio Fiscal</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[0].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Serie y Folio</td></tr><tr><td style="font-size:8px;color:#ff0000">'.$this->datosCFD[1].'</td></tr><tr><td style="font-weight:bold;font-size:11px">No. de serie del CSD del emisor</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[2].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Fecha y Hora de emision</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[3].'</td></tr></table></div></td><td><div style="margin-left:20px;margin-bottom:35px"><table><tr><td style="font-weight:bold;font-size:11px">Fecha y hora de certificacion</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[4].'</td></tr><tr><td style="font-weight:bold;font-size:11px">No. de serie del CSD del SAT</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[5].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Forma de Pago</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[6].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Tipo</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[7].'</td></tr></table></div></td></tr></table></div></td><td><div style="width:300px;height:150px;margin-left:76px">'.$src.'</div></td></tr></table><div style="font-size:10px;margin-bottom:15px">Lugar De Expedicion: '.$this->lugarExp.'</div><div style="font-weight:bold;font-size:13px;background:'.$this->color.';padding-left:315px">Datos De Emisor</div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Razon Social:</td><td style="width:550px;font-size:10px">'.$this->datosEmisor[0].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">R.F.C:</td><td style="width:90px;font-size:11px">'.$this->datosEmisor[1].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Calle y Numero:</td><td style="width:218px;font-size:10px">'.$this->datosEmisor[2].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Ciudad:</td><td style="width:138px;font-size:10px">'.$this->datosEmisor[3].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Colonia:</td><td style="width:221px;font-size:10px">'.$this->datosEmisor[4].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Delegacion:</td><td style="width:180px;font-size:10px">'.$this->datosEmisor[5].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Estado:</td><td style="width:153px;font-size:10px">'.$this->datosEmisor[6].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Codigo postal:</td><td style="width:25px;font-size:10px">'.$this->datosEmisor[7].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Pais:</td><td style="width:178px;font-size:10px">'.$this->datosEmisor[8].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Regimen Fiscal:</td><td style="width:670px;font-size:10px">'.$this->datosEmisor[9].'</td></tr></table></div><div style="font-weight:bold;font-size:13px;background:'.$this->color.';padding-left:310px">Datos De Receptor</div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Razon Social:</td><td style="width:550px;font-size:10px">'.$this->datosReceptor[0].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">R.F.C:</td><td style="width:90px;font-size:11px">'.$this->datosReceptor[1].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Calle y Numero:</td><td style="width:218px;font-size:10px">'.$this->datosReceptor[2].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Ciudad:</td><td style="width:138px;font-size:10px">'.$this->datosReceptor[3].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Colonia:</td><td style="width:221px;font-size:10px">'.$this->datosReceptor[4].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Delegacion:</td><td style="width:180px;font-size:10px">'.$this->datosReceptor[5].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Estado:</td><td style="width:153px;font-size:10px">'.$this->datosReceptor[6].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Codigo postal:</td><td style="width:25px;font-size:10px">'.$this->datosReceptor[7].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Pais:</td><td style="width:178px;font-size:10px">'.$this->datosReceptor[8].'</td></tr></table></div><div><table><tr><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:84px">Cantidad</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:84px">Unidad</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:385px">Descripcion</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:78px">Precio</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:95px">Importe</td></tr></table></div>';
				break;
			}
			$content.=$this->contentConceptos;
					//400px por default
			$px=20;
					/*$px=0;
					if($this->lines<=19)
					$px=290-($this->lines*15);*/
					$impuestos='';
					if($this->total[2]!=0)
						$impuestos='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';padding-left:50px">ISR</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';padding-left:30px">'.$this->total[2].' '.$this->datosCFD[69].'</td></tr>';
					if($this->total[3]!=0)
						$impuestos.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';padding-left:50px">IEPS</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';padding-left:30px">'.$this->total[3].' '.$this->datosCFD[69].'</td></tr>';


					if($_SERVER['SERVER_NAME'] == 'localhost')
					{
						$ruta = 'http://localhost/mlog/webapp/';
					}else
					{
						$ruta='http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']);
						$ruta=substr($ruta,0,strpos($ruta,'modulos'));
					}

					//$content = '';
					$flagim = 0;
					$flagim2 = 0;
					$ruta = $ruta.'modulos/SAT/PDF/';
					$content.='<div style="margin-left:540px;margin-top:'.$px.'px"><table><tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left;width:100px">Subtotal</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right;width:100px">'.$this->total[0].' '.$this->datosCFD[69].'</td></tr>';
					//print_r($this->carray);
					$down ='';
					$up = '';
					foreach($this->carray as $key=>$indiceKey){
						if($key=='IVAR' || $key=='ISR'){
							if($flagim == 0){
								$flagim = 1;
								$down.='<tr><td colspan="2">Impuestos Retenidos</td></tr>';
							}
															foreach($indiceKey as $index => $value){
									$down.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">'.$key.' ('.number_format($index,2).'%)</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.number_format((float)$value['Valor'],2,'.',',').' '.$this->datosCFD[69].'</td></tr>';	
								}
						}
						if($key=='IVA' || $key == 'IEPS'){
							if($flagim2 == 0){
								$up.='<tr><td colspan="2">Impuestos Trasladados</td></tr>';
								$flagim2 = 1;

							}
								foreach($indiceKey as $index => $value){
									$up.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">'.$key.' ('.number_format($index,2).'%)</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.number_format((float)$value['Valor'],2,'.',',').' '.$this->datosCFD[69].'</td></tr>';	
								}

						}
						

						/*foreach($indiceKey as $index => $value){
							$content.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">'.$key.' ('.number_format($index,2).'%)</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.number_format((float)$value['Valor'],2,'.',',').' MXP</td></tr>';	
						}*/
					}
						//foreach($indiceKey as $index => $value)
						//	$content.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">'.$key.' ('.$index.'%)</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.number_format((float)$value['Valor'],2,'.',',').' MXP</td></tr>';

						$content = $content.$up.$down;
		

						$strFileName=dirname(__FILE__)."/".date('Ymdhis').rand(1000, 9999)."-sello.txt";
						$file = fopen($strFileName, 'w');
						fwrite($file, $this->selloDigitales[0]);
						fclose($file);
						$rutaQR = $this->creaQR($this->selloDigitales[0]);

						switch($plantilla){
							case 0:
							$content.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">Total</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.$this->total[1].' '.$this->datosCFD[69].'</td></tr></table></div><div style="margin-left:10px"><table><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';padding-left:30px">Metodo De Pago:</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';padding-left:5px">'.$this->metodoPago[0].'</td></tr><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';padding-left:30px">Cuenta:</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';padding-left:5px">'.$this->metodoPago[1].'</td></tr><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';padding-left:30px;padding-right:10px">TOTAL EN LETRA:</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';padding-left:5px">'.$this->metodoPago[2].'</td></tr></table></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';margin-top:10px">Observaciones</div><div style="font-size:10px;border:1px solid '.$this->color.';padding:5px"><div style="width:381px;padding-top:-2px">'.wordwrap($this->observaciones,112,"</div><div style=\"width:380px;margin-top:3px\">",true).'</div></div><div style="margin-top:10px"><table><tr><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:352px">Codigo original del complemento de certificacion del SAT</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:392px">Cadena original del complemento de certificacion del SAT</td></tr></table></div><div><table><tr><td style="font-size:8px;width:352px"><img style="-webkit-user-select:none" src="' . $rutaQR . '"></td><td style="font-size:8px;width:392px;border-left:1px dashed '.$this->color.';padding-left:7px"><div><div>'.wordwrap($this->selloDigitales[0],58,"</div><div>",true).'</div></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';width:387px;margin-top:10px">Sello digital del emisor</div><div style="width:387px"><div>'.wordwrap($this->selloDigitales[1],58,"</div><div>",true).'</div></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';width:387px;margin-top:10px">Sello digital del SAT</div><div><div>'.wordwrap($this->selloDigitales[2],58,"</div><div>",true).'</div></div></td></tr></table></div><div style="font-size:8px;padding-left:100px"><table><tr><td>ESTE DOCUMENTO ES UNA REPRESENTACION IMPRESA DE UN CFDI</td><td style="padding-left:50px;border-collapse:collapse">Esta Factura Fue Generada Utilizando Herramientas De Netwarmonitor</td></tr></table></div></body></html>';	
							break;

							case 1:
							$content.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">Total</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.$this->total[1].' '.$this->datosCFD[69].'</td></tr></table></div><div style="margin-left:10px"><table><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';padding-left:30px">Metodo De Pago:</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';padding-left:5px">'.$this->metodoPago[0].'</td></tr><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';padding-left:30px">Cuenta:</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';padding-left:5px">'.$this->metodoPago[1].'</td></tr><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';padding-left:30px;padding-right:10px">TOTAL EN LETRA:</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';padding-left:5px">'.$this->metodoPago[2].'</td></tr></table></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';margin-top:10px">Observaciones</div><div style="font-size:10px;border:1px solid '.$this->color.';padding:5px"><div style="width:381px;padding-top:-2px">'.wordwrap($this->observaciones,112,"</div><div style=\"width:380px;margin-top:3px\">",true).'</div></div><div style="margin-top:10px"><table><tr><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:392px">Cadena original del complemento de certificacion del SAT</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:352px">Codigo original del complemento de certificacion del SAT</td></tr></table></div><div><table><tr><td style="font-size:8px;width:392px"><div><div>'.wordwrap($this->selloDigitales[0],58,"</div><div>",true).'</div></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';width:387px;margin-top:10px">Sello digital del emisor</div><div style="width:387px"><div>'.wordwrap($this->selloDigitales[1],58,"</div><div>",true).'</div></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';width:387px;margin-top:10px">Sello digital del SAT</div><div><div>'.wordwrap($this->selloDigitales[2],58,"</div><div>",true).'</div></div></td><td style="font-size:8px;width:352px;border-left:1px dashed '.$this->color.'"><img style="-webkit-user-select:none" src="' . $rutaQR . '"></td></tr></table></div><div style="font-size:8px;padding-left:100px"><table><tr><td>ESTE DOCUMENTO ES UNA REPRESENTACION IMPRESA DE UN CFDI</td><td style="padding-left:50px;border-collapse:collapse">Este Documento Fue Generado Utilizando Herramientas De Netwarmonitor</td></tr></table></div></body></html>';
							break;
						} 
				/*echo $content;
				exit();*/
				//$content='<html><head><title>PDF</title></head><body><table><tr><td><div style="width:300px;height:150px"><img src="../../netwarelog/archivos/1/organizaciones/" style="width:px;height:px"/></div></td><td><div style="margin-left:76px"><table><tr><td><div style="margin-left:12px"><table><tr><td style="font-weight:bold;font-size:11px">Folio Cotizacion</td></tr><tr><td style="font-size:8px">24</td></tr><tr><td style="font-weight:bold;font-size:11px">Tipo</td></tr><tr><td style="font-size:8px;color:#ff0000">Cotizacion</td></tr></table></div></td><td><div style="margin-left:20px;margin-bottom:35px"><table><tr><td style="font-weight:bold;font-size:11px">Fecha y hora</td></tr><tr><td style="font-size:8px">2015-09-26 22:57:48</td></tr></table></div></td></tr></table></div></td></tr></table><div style="font-size:10px;margin-bottom:15px">Lugar De Expedicion: MEXICO</div><div style="font-weight:bold;font-size:11px;background:#D8D8D8">Emisor Cotizacion</div><div><table style="border:1px #D8D8D8"><tr><td style="font-size:10px">Razon Social:</td><td style="width:550px;font-size:10px">Informatica HAGAG, S.A. de C.V.</td><td style="border-left:1px solid #D8D8D8;font-size:10px">R.F.C:</td><td style="width:90px;font-size:11px">IHA000314A38</td></tr></table></div><div><table style="border:1px #D8D8D8"><tr><td style="font-size:10px">Calle y Numero:</td><td style="width:218px;font-size:10px">LOPEZ MATEOS #23</td><td style="border-left:1px solid #D8D8D8;font-size:10px">Ciudad:</td><td style="width:138px;font-size:10px">19</td><td style="border-left:1px solid #D8D8D8;font-size:10px">Colonia:</td><td style="width:221px;font-size:10px">-</td></tr></table></div><div><table style="border:1px #D8D8D8"><tr><td style="font-size:10px">Delegacion:</td><td style="width:180px;font-size:10px">19</td><td style="border-left:1px solid #D8D8D8;font-size:10px">Estado:</td><td style="width:153px;font-size:10px">3</td><td style="border-left:1px solid #D8D8D8;font-size:10px">Codigo postal:</td><td style="width:25px;font-size:10px">-</td><td style="border-left:1px solid #D8D8D8;font-size:10px">Pais:</td><td style="width:178px;font-size:10px">Mexico</td></tr></table></div><div><table style="border:1px #D8D8D8"><tr><td style="font-size:10px"></td><td style="width:670px;font-size:10px"></td></tr></table></div><div style="font-weight:bold;font-size:11px;background:#D8D8D8">Cliente</div><div><table style="border:1px #D8D8D8"><tr><td style="font-size:10px">Razon Social:</td><td style="width:550px;font-size:10px">AIDA ALCANTARA</td><td style="border-left:1px solid #D8D8D8;font-size:10px">R.F.C:</td><td style="width:90px;font-size:11px">XAXX010101000</td></tr></table></div><div><table style="border:1px #D8D8D8"><tr><td style="font-size:10px">Calle y Numero:</td><td style="width:218px;font-size:10px">POCHTECA ESQ. QUINTO SOL Int.</td><td style="border-left:1px solid #D8D8D8;font-size:10px">Ciudad:</td><td style="width:138px;font-size:10px">741</td><td style="border-left:1px solid #D8D8D8;font-size:10px">Colonia:</td><td style="width:221px;font-size:10px">CIUDAD AZTECA 1RA SECCION</td></tr></table></div><div><table style="border:1px #D8D8D8"><tr><td style="font-size:10px">Delegacion:</td><td style="width:180px;font-size:10px">741</td><td style="border-left:1px solid #D8D8D8;font-size:10px">Estado:</td><td style="width:153px;font-size:10px">15</td><td style="border-left:1px solid #D8D8D8;font-size:10px">Codigo postal:</td><td style="width:25px;font-size:10px">55129</td><td style="border-left:1px solid #D8D8D8;font-size:10px">Pais:</td><td style="width:178px;font-size:10px">Mexico</td></tr></table></div><div><table><tr><td style="font-weight:bold;font-size:11px;background:#D8D8D8;width:84px">Cantidad</td><td style="font-weight:bold;font-size:11px;background:#D8D8D8;width:84px">Unidad</td><td style="font-weight:bold;font-size:11px;background:#D8D8D8;width:385px">Descripcion</td><td style="font-weight:bold;font-size:11px;background:#D8D8D8;width:78px">Precio</td><td style="font-weight:bold;font-size:11px;background:#D8D8D8;width:95px">Importe</td></tr></table></div><div><table style="border-collapse:collapse"><tr><td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:81px;text-align:right;padding-right:3px">2.00</td><td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:81px;padding-left:3px">Unidad</td><td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:383px"><div style="width:381px;padding-top:-2px">ipad mini 16 g cotizacion</div></td><td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:75px;text-align:right;padding-right:3px">100.00</td><td style="font-size:11px;border-bottom:1px dashed #D8D8D8;width:93px;text-align:right">200.00</td></tr></table></div><div><table style="border-collapse:collapse"><tr><td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:81px;text-align:right;padding-right:3px">12.00</td><td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:81px;padding-left:3px">Unidad</td><td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:383px"><div style="width:381px;padding-top:-2px">iphone dorado 16 g cotizacion</div></td><td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;width:75px;text-align:right;padding-right:3px">68.97</td><td style="font-size:11px;border-bottom:1px dashed #D8D8D8;width:93px;text-align:right">827.59</td></tr></table></div><div style="margin-left:540px;margin-top:20px"><table><tr><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;text-align:left;width:100px">Subtotal</td><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;text-align:right;width:100px">1,027.59 MXP</td></tr><tr><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;text-align:left">taxes (IVA%)</td><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;text-align:right">0.00 MXP</td></tr><tr><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;text-align:left">taxes (IEPS%)</td><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;text-align:right">0.00 MXP</td></tr><tr>td style="font-size:10px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;text-align:left">taxes (test%)</td><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;text-align:right">0.00 MXP</td></tr><tr><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;text-align:left">taxes (ISH%)</td><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;text-align:right">0.00 MXP</td></tr><tr><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;text-align:left">taxes (ISR%)</td><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;text-align:right">0.00 MXP</td></tr><tr><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;text-align:left">Total</td><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;text-align:right">1,192.00 MXP</td></tr></table></div><div style="margin-left:10px"><table><tr><td style="font-size:11px;border-bottom:1px dashed #D8D8D8;border-right:1px dashed #D8D8D8;padding-left:30px;padding-right:10px">TOTAL EN LETRA:</td><td style="font-size:10px;border-bottom:1px dashed #D8D8D8;padding-left:5px">UN MIL CIENTO NOVENTA Y DOS PESOS 00/100 M.N.</td></tr></table></div><div style="font-weight:bold;font-size:11px;background:#D8D8D8;margin-top:10px">Observaciones</div><div style="font-size:10px;border:1px solid #D8D8D8;padding:5px"><div style="width:381px;padding-top:-2px">ueueueueueuuee</div></div></body></html>';		
			//$cupon = $this->cuponInadem($this->datosReceptor[1]);
				$cupon='';
			//echo 'cupon =('.$cupon.')';
			$this->pdf -> WriteHTML($content);// Indicamos la variable con el contenido que queremos incluir en el pdf    
			if($cupon == null || $cupon ==''){
				$this->pdf -> Output(dirname(__FILE__).'/../../facturas/'.$this->datosCFD[0].'.pdf', 'F'); //Generamos el archivo "archivo_pdf.pdf". Ponemos como parametro 'D' para forzar la descarga del archivo. 
			}else{
				$this->pdf -> Output(dirname(__FILE__).'/../../facturas/'.$this->datosCFD[0].'__'.str_replace(' ','_',$this->datosReceptor[0]).'__'.$cupon.'.pdf', 'F'); //Generamos el archivo "archivo_pdf.pdf". Ponemos como parametro 'D' para forzar la descarga del archivo. 
			}

			@unlink($strFileName); 
		}

		function creaQR($texto) {
			//include dirname(__FILE__) . "phpqrcode/qrlib.php";
			$ruta = dirname(__FILE__).'/../../facturas/'.$this->datosCFD[0].'.png';
			QRcode::png($texto, $ruta);
			return $ruta;
			
		}
		function cuponInadem($rfc){

			$servidor = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
			$objConG = mysqli_connect($servidor, "nmdevel", "nmdevel", "_dbmlog0000005471");

		    $strSqlG = "SELECT nombre from comun_facturacion where rfc='".$rfc."' order by nombre desc";
		    $rstWebconfigG = mysqli_query($objConG, $strSqlG);
		    while ($objWebconfigG = mysqli_fetch_assoc($rstWebconfigG)) {
		        $idClienteReceptor = $objWebconfigG['nombre'];
		        break;
		    }
		    $queryCupon = "SELECT cupon from comun_cliente_inadem where idCliente=".$idClienteReceptor;
		    $result = mysqli_query($objConG, $queryCupon);
		    while ($resultados = mysqli_fetch_assoc($result)) {
		        $cuponCliente = $resultados['cupon'];
		        break;
		    } 
		    return $cuponCliente;
		}
		function tipoDoc(){

			$servidor = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
			$objConG = mysqli_connect($servidor, "nmdevel", "nmdevel", "_dbmlog0000005471");

		    $strSqlG = "SELECT * from pvt_configuraPdf";
		    $rstWebconfigG = mysqli_query($objConG, $strSqlG);
		    while ($objWebconfigG = mysqli_fetch_assoc($rstWebconfigG)) {
		        $tipo = $objWebconfigG['tipo_documento'];
		        break;
		    }
		    return $tipo;
		}
	}	
	?>
