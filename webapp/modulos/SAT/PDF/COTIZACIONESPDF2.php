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
			$this->datosCFD=array('','','','');
			$this->datosEmisor=array('','','','','','','','','','');
			$this->datosReceptor=array('','','','','','','','','');
			$this->contentConceptos="";
			$this->total=array('','','');
			$this->desc=array('','','');
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

		function datosCFD($folioC,$fecha,$tipo,$moneda,$idOV,$solicito,$fecha_entrega){
			if($tipo=='Orden de compra' || $tipo=='Requisicion'){
				$this->datosCFD[99]='Proveedor';
			}else{
				$this->datosCFD[99]='Cliente';
			}
			if($idOV!=0){
				$folioC=$idOV;
			}
			$this->datosCFD[0]=$folioC; //folio de la cotizacion
			$this->datosCFD[1]=$fecha; //fecha y hora
			$this->datosCFD[2]=$tipo; //Cotizacion
			$this->datosCFD[3]=$moneda; //Moneda

			//ch@
			$this->datosCFD[5]=$solicito; //solicito
			$this->datosCFD[6]=$fecha_entrega; //fecha_entrega


			//Contenido HTML del pagare
			$this->datosCFD[4] = '';
				/* Modificacion RCA para el pagaré */

			/* $this->datosCFD[3]=$fechaHE;
			$this->datosCFD[4]=$fechaHC;
			$this->datosCFD[5]=$serieCSDS;
			$this->datosCFD[6]=$formaP;
			$this->datosCFD[7]='Cotizacion';
			if($tipoF=="ingreso")
				$this->datosCFD[7]="Factura";
			if($tipoF=="egreso")
				$this->datosCFD[7]="Nota de Crédito";
			if($titulo=="recibo")
				$this->datosCFD[7]="Recibo De Ingresos"; */
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

		function agregarConcepto($cantidad,$unidad,$descripcion,$precio,$importe,$imagen){

			$this->lines+=substr_count('<div style="width:380px">'.wordwrap($descripcion,65,"</div><div style=\"width:380px\">",true).'</div>','<div');
			$this->contentConceptos.='<div><table style="border-collapse:collapse"><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;text-align:right;padding-right:3px">'.number_format((float)$cantidad,2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;padding-left:3px">'.$unidad.'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:383px"><div style="width:381px;padding-top:-2px">'.wordwrap($descripcion,65,"</div><div style=\"width:380px;margin-top:3px\">",true).'</div></td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:75px;text-align:right;padding-right:3px">'.number_format((float)$precio,2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';width:93px;text-align:right">'.number_format((float)$importe,2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';width:93px;text-align:right">'.$imagen.'</td></tr></table></div>';
		}

		function agregarConceptos($cadena){

		   /* $cadena=substr($cadena,1);
			$conceptos=explode("|",$cadena);
			$limit=count($conceptos); */
			$l = substr($cadena, 0, 1);
			$aimagen = "";

			if($l=='.'){
				$cadena=substr($cadena,2);
				$conceptos=explode("|",$cadena);
				$limit=count($conceptos);


				for($contador=0;$contador<$limit;){
					
					
					if($conceptos[$contador] == null || $conceptos[$contador] == ''){
					   $conceptos[$contador] = dirname(__FILE__).'/na.jpg'; 
					}else{
					   $conceptos[$contador] =  '../pos/'.$conceptos[$contador].'';
				   }
					
					//echo $conceptos[$contador];
					//echo $conceptos[$contador];
				  //ch@  para cotizaciones y ordenes de venta tabla de productos
					$this->lines+=substr_count('<div style="width:280px">'.wordwrap($conceptos[$contador+3],65,"</div><div style=\"width:280px\">",true).'</div>','<div');
					$this->contentConceptos.='<div><table style="border-collapse:collapse"><tr><td style="border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:95px; text-align:center;"><img src="'.$conceptos[$contador].'" height="45" width="45"></td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;text-align:right;padding-right:3px">'.number_format((float)$conceptos[$contador+1],2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;padding-left:3px">'.$conceptos[$contador+2].'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:283px"><div style="width:281px;padding-top:-2px">'.wordwrap($conceptos[$contador+3],76,"</div><div style=\"width:280px;margin-top:3px\">",true).'</div></td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:75px;text-align:right;padding-right:3px">'.number_format((float)$conceptos[$contador+4],2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';width:93px;text-align:right">'.number_format((float)$conceptos[$contador+5],2,'.',',').'</td></tr></table></div>';
					$contador+=6;  
				
				}

			}else{
				foreach ($cadena as $key => $value) {
				   if($key!='cargos' && $value['cantidad'] > 0 && $key!='descGeneral' && $key!='descGeneralCant'){
						$this->lines+=substr_count('<div style="width:380px">'.wordwrap($value['nombre'],65,"</div><div style=\"width:380px\">",true).'</div>','<div');
						$this->contentConceptos.='<div><table style="border-collapse:collapse"><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;text-align:right;padding-right:3px">'.number_format($value['cantidad'],2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;padding-left:3px">'.$value['unidad'].'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:383px"><div style="width:381px;padding-top:-2px">'.wordwrap($value['nombre'],76,"</div><div style=\"width:380px;margin-top:3px\">",true).'</div></td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:75px;text-align:right;padding-right:3px">'.number_format($value['precio'],2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';width:93px;text-align:right">'.number_format($value['importe'],2,'.',',').'</td></tr>'.$aimagen.'</table></div>';
						//$this->contentConceptos.='<div><table style="border-collapse:collapse"><tr><td></td>'.$value['producto'].'<td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:40px;text-align:right;padding-right:3px">'.number_format($value['cantidad'],2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;padding-left:3px">'.$value['unidad'].'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:383px"><div style="width:381px;padding-top:-2px">'.wordwrap($value['nombre'],76,"</div><div style=\"width:380px;margin-top:3px\">",true).'</div></td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:75px;text-align:right;padding-right:3px">'.number_format($value['precio'],2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';width:93px;text-align:right">'.number_format($value['importe'],2,'.',',').'</td></tr>'.$aimagen.'</table></div>';
					//$contador+=5;
				   }
				}

			}

		  /*  for($contador=0;$contador<$limit;){
				$this->lines+=substr_count('<div style="width:380px">'.wordwrap($conceptos[$contador+2],65,"</div><div style=\"width:380px\">",true).'</div>','<div');
				$this->contentConceptos.='<div><table style="border-collapse:collapse"><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;text-align:right;padding-right:3px">'.number_format((float)$conceptos[$contador],2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:81px;padding-left:3px">'.$conceptos[$contador+1].'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:383px"><div style="width:381px;padding-top:-2px">'.wordwrap($conceptos[$contador+2],76,"</div><div style=\"width:380px;margin-top:3px\">",true).'</div></td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';width:75px;text-align:right;padding-right:3px">'.number_format((float)$conceptos[$contador+3],2,'.',',').'</td><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';width:93px;text-align:right">'.number_format((float)$conceptos[$contador+4],2,'.',',').'</td></tr></table></div>';
				$contador+=5;
			} */
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
				$this->total[0]=number_format($subtotal,2,'.',',');
				$this->total[1]=number_format($total,2,'.',',');
				$this->total[2]=$total;
				$this->carray=$cadena;
			}

			function descuento($desc,$cant,$aux=0){
				$this->desc[0]=number_format($desc,2,'.',',');
				$this->desc[1]=number_format($cant,2,'.',',');
			}

			function agregarMetodo($metodoP,$cuenta,$moneda){
				$obj=new EnLetras();
				if($moneda!='MXN'){
					$a='';
					$b=$moneda;
				}else if($moneda=='MXN'){
					$a='pesos';
					$b='M.N';
				}else{
					$a='';
					$b='';
				}
				$total=strtoupper($obj->ValorEnLetras($this->total[2],$a,$b));
				$this->metodoPago[0]=$metodoP;
				$this->metodoPago[1]=$cuenta;
				$this->metodoPago[2]=$total;
			}

			function agregarSellos($cadenaO,$selloDE,$selloDS){
				$this->selloDigitales[0]='w3747474';
				$this->selloDigitales[1]='6734646464';
				$this->selloDigitales[2]='uu44747474747';
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


				//$this->datosCFD[2] = "Remision";
				switch($plantilla){
					case 0:
						//ch@
						$content='<html><head><title>PDF</title></head><body><table><tr><td><div style="width:300px;height:150px">'.$src.'</div></td><td><div style="margin-left:76px"><table><tr><td><div style="margin-left:12px"><table><tr><td style="font-weight:bold;font-size:11px">Folio de '.$this->datosCFD[2].': '.$this->datosCFD[0].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Fecha y hora de emision: '.$this->datosCFD[1].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Lugar de Expedición: '.$this->lugarExp.' </td></tr><tr><td style="font-weight:bold;font-size:11px">Ejecutivo de venta: '.$this->datosCFD[5].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Fecha de vigencia: '.$this->datosCFD[6].'</td></tr></table></div></td></tr></table></div></td></tr></table><div style="font-weight:bold;font-size:11px;background:'.$this->color.';color:#000000;" >Emisor </div><div><table style="border:0.5px '.$this->color.'"><tr><td style="font-size:10px">Razon Social:</td><td style="width:550px;font-size:10px">'.$this->datosEmisor[0].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">R.F.C:</td><td style="width:90px;font-size:11px">'.$this->datosEmisor[1].'</td></tr></table></div><div><table style="border:0.5px '.$this->color.'"><tr><td style="font-size:10px">Calle y Numero:</td><td style="width:218px;font-size:10px">'.$this->datosEmisor[2].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Ciudad:</td><td style="width:138px;font-size:10px">'.$this->datosEmisor[3].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Colonia:</td><td style="width:221px;font-size:10px">'.$this->datosEmisor[4].'</td></tr></table></div><div><table style="border:0.5px '.$this->color.'"><tr><td style="font-size:10px">Delegacion:</td><td style="width:180px;font-size:10px">'.$this->datosEmisor[5].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Estado:</td><td style="width:153px;font-size:10px">'.$this->datosEmisor[6].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Codigo postal:</td><td style="width:25px;font-size:10px">'.$this->datosEmisor[7].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Pais:</td><td style="width:178px;font-size:10px">'.$this->datosEmisor[8].'</td></tr></table></div><div></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';color:#000000;">'.$this->datosCFD[99].'</div><div><table style="border:0.5px '.$this->color.'"><tr><td style="font-size:10px">Razon Social:</td><td style="width:550px;font-size:10px">'.$this->datosReceptor[0].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">R.F.C:</td><td style="width:90px;font-size:11px">'.$this->datosReceptor[1].'</td></tr></table></div><div><table style="border:0.5px '.$this->color.'"><tr><td style="font-size:10px">Calle y Numero:</td><td style="width:218px;font-size:10px">'.$this->datosReceptor[2].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Ciudad:</td><td style="width:138px;font-size:10px">'.$this->datosReceptor[3].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Colonia:</td><td style="width:221px;font-size:10px">'.$this->datosReceptor[4].'</td></tr></table></div><div><table style="border:0.5px '.$this->color.'"><tr><td style="font-size:10px">Delegacion:</td><td style="width:180px;font-size:10px">'.$this->datosReceptor[5].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Estado:</td><td style="width:153px;font-size:10px">'.$this->datosReceptor[6].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Codigo postal:</td><td style="width:25px;font-size:10px">'.$this->datosReceptor[7].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Pais:</td><td style="width:178px;font-size:10px">'.$this->datosReceptor[8].'</td></tr></table></div><div><table><tr><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:95px;color:#000000;">Producto</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:84px;color:#000000;">Cantidad</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:84px;color:#000000;">Unidad</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:280px;color:#000000;">Descripcion</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:78px;color:#000000;">Precio</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:95px;color:#000000;">Importe</td></tr></table></div>';                
					break; 

					case 1:
						$content='<html><head><title>PDF</title></head><body><table><tr><td><div style=""><table><tr><td><div style="margin-left:12px"><table><tr><td style="font-weight:bold;font-size:11px">Folio Cotizacion</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[0].'</td></tr><tr><td style="font-weight:bold;font-size:11px">Tipo</td></tr><tr><td style="font-size:8px;color:#ff0000">'.$this->datosCFD[2].'</td></tr></table></div></td><td><div style="margin-left:20px;margin-bottom:35px"><table><tr><td style="font-weight:bold;font-size:11px">Fecha y Hora</td></tr><tr><td style="font-size:8px">'.$this->datosCFD[1].'</td></tr></table></div></td></tr></table></div></td><td><div style="width:300px;height:150px;margin-left:76px">'.$src.'</div></td></tr></table><div style="font-size:10px;margin-bottom:15px">Lugar De Expedicion: '.$this->lugarExp.'</div><div style="font-weight:bold;font-size:13px;background:'.$this->color.';padding-left:315px">Datos De Emisor</div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Razon Social:</td><td style="width:550px;font-size:10px">'.$this->datosEmisor[0].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">R.F.C:</td><td style="width:90px;font-size:11px">'.$this->datosEmisor[1].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Calle y Numero:</td><td style="width:218px;font-size:10px">'.$this->datosEmisor[2].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Ciudad:</td><td style="width:138px;font-size:10px">'.$this->datosEmisor[3].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Colonia:</td><td style="width:221px;font-size:10px">'.$this->datosEmisor[4].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Delegacion:</td><td style="width:180px;font-size:10px">'.$this->datosEmisor[5].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Estado:</td><td style="width:153px;font-size:10px">'.$this->datosEmisor[6].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Codigo postal:</td><td style="width:25px;font-size:10px">'.$this->datosEmisor[7].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Pais:</td><td style="width:178px;font-size:10px">'.$this->datosEmisor[8].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Regimen Fiscal:</td><td style="width:670px;font-size:10px">'.$this->datosEmisor[9].'</td></tr></table></div><div style="font-weight:bold;font-size:13px;background:'.$this->color.';padding-left:310px">Datos De Receptor</div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Razon Social:</td><td style="width:550px;font-size:10px">'.$this->datosReceptor[0].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">R.F.C:</td><td style="width:90px;font-size:11px">'.$this->datosReceptor[1].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Calle y Numero:</td><td style="width:218px;font-size:10px">'.$this->datosReceptor[2].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Ciudad:</td><td style="width:138px;font-size:10px">'.$this->datosReceptor[3].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Colonia:</td><td style="width:221px;font-size:10px">'.$this->datosReceptor[4].'</td></tr></table></div><div><table style="border:1px '.$this->color.'"><tr><td style="font-size:10px">Delegacion:</td><td style="width:180px;font-size:10px">'.$this->datosReceptor[5].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Estado:</td><td style="width:153px;font-size:10px">'.$this->datosReceptor[6].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Codigo postal:</td><td style="width:25px;font-size:10px">'.$this->datosReceptor[7].'</td><td style="border-left:1px solid '.$this->color.';font-size:10px">Pais:</td><td style="width:178px;font-size:10px">'.$this->datosReceptor[8].'</td></tr></table></div><div><table><tr><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:84px">Cantidad</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:84px">Unidad</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:385px">Descripcion</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:78px">Precio</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:95px">Importe</td></tr></table></div>';

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
							$impuestos='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';padding-left:50px">ISR</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';padding-left:30px">'.$this->total[2].' '.$this->datosCFD[3].'</td></tr>';
						if($this->total[3]!=0)
							$impuestos.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';padding-left:50px">IEPS</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';padding-left:30px">'.$this->total[3].' '.$this->datosCFD[3].'</td></tr>';


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

						if(isset($_SESSION['caja']['descGeneral'])){
							$content.='<div style="margin-left:540px;margin-top:'.$px.'px"><table><tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left;width:100px">Descuento</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right;width:100px">'.number_format($_SESSION['caja']['descGeneral'],2).' '.$this->datosCFD[3].'</td></tr><tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left;width:100px">Subtotal</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right;width:100px">'.$this->total[0].' '.$this->datosCFD[3].'</td></tr>';
						}else{
							$content.='<div style="margin-left:540px;margin-top:'.$px.'px"><table><tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left;width:100px">Subtotal</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right;width:100px">'.$this->total[0].' '.$this->datosCFD[3].'</td></tr>';
						}


						
						//print_r($this->carray);
						$down ='';
						$up = '';
						foreach($this->carray as $key=>$indiceKey){
							if($key=='IVAR' || $key=='ISR' || $key=='RTP'){
								if($flagim == 0){
									$flagim = 1;
									$down.='<tr><td colspan="2">Impuestos Retenidos</td></tr>';
								}
									foreach($indiceKey as $index => $value){
										if(isset($value['Valor'])){
											$down.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">'.$key.' ('.number_format($index,2).'%)</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.number_format((float)$value['Valor'],2,'.',',').' '.$this->datosCFD[3].'</td></tr>'; 
										}else{
											$down.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">'.$key.' ('.number_format($index,2).'%)</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.number_format((float)$value,2,'.',',').' '.$this->datosCFD[3].'</td></tr>'; 
										}


										
									}
							}
							if($key=='IVA' || $key == 'IEPS'){
								if($flagim2 == 0){
									$up.='<tr><td colspan="2">Impuestos Trasladados</td></tr>';
									$flagim2 = 1;

								}
									foreach($indiceKey as $index => $value){
										if(isset($value['Valor'])){
											$up.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">'.$key.' ('.number_format($index,2).'%)</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.number_format((float)$value['Valor'],2,'.',',').' '.$this->datosCFD[3].'</td></tr>';
										}else{
											$up.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">'.$key.' ('.number_format($index,2).'%)</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.number_format((float)$value,2,'.',',').' '.$this->datosCFD[3].'</td></tr>';
										}   
									}

							}
							

							/*foreach($indiceKey as $index => $value){
								$content.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">'.$key.' ('.number_format($index,2).'%)</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.number_format((float)$value['Valor'],2,'.',',').' MXP</td></tr>';  
							}*/
						}
							//foreach($indiceKey as $index => $value)
							//  $content.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">'.$key.' ('.$index.'%)</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.number_format((float)$value['Valor'],2,'.',',').' MXP</td></tr>';

							if($this->desc[0] > 0){
								$descuento ='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">Descuento('.$this->desc[0].'%)</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.$this->desc[1].' '.$this->datosCFD[3].'</td></tr>'; 
							}else{
								$descuento = '';
							}
							

							$content = $content.$up.$down.$descuento;

						   /* $strFileName=dirname(__FILE__)."/".date('Ymdhis').rand(1000, 9999)."-sello.txt";
							$file = fopen($strFileName, 'w');
							fwrite($file, $this->selloDigitales[0]);
							fclose($file);
							$rutaQR = $this->creaQR($this->selloDigitales[0]); */

							switch($plantilla){
								case 0:
								/* $content.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">Total</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.$this->total[1].' '.$this->datosCFD[3].'</td></tr></table></div><div style="margin-left:10px"><table><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';padding-left:30px;padding-right:10px">TOTAL EN LETRA:</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';padding-left:5px">'.$this->metodoPago[2].'</td></tr></table></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';margin-top:10px;color:#000000;">Observaciones</div><div style="font-size:10px;border:1px solid '.$this->color.';padding:5px"><div style="width:381px;padding-top:-2px">'.wordwrap($this->observaciones,112,"</div><div style=\"width:380px;margin-top:3px\">",true).'</div></div><div>'.$this->datosCFD[4].'</div></body></html>'; */

								$content.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">Total</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.$this->total[1].' '.$this->datosCFD[3].'</td></tr></table></div><div style="margin-left:10px"><table><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';padding-left:30px;padding-right:10px">TOTAL EN LETRA:</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';padding-left:5px">'.$this->metodoPago[2].'</td></tr></table></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';margin-top:10px;color:#000000;">Observaciones</div><div style="font-size:10px;border:1px solid '.$this->color.';padding:5px"><div style="text-align: justify; width:730px;padding-top:-2px">'.$this->observaciones.'</div></div><div>'.$this->datosCFD[4].'</div></body></html>';

								break;

								case 1:
								/*$content.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">Total</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.$this->total[1].' '.$this->datosCFD[3].'</td></tr></table></div><div style="margin-left:10px"><table><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';padding-left:30px;padding-right:10px">TOTAL EN LETRA:</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';padding-left:5px">'.$this->metodoPago[2].'</td></tr></table></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';margin-top:10px">Observaciones</div><div style="font-size:10px;border:1px solid '.$this->color.';padding:5px"><div style="width:381px;padding-top:-2px">'.wordwrap($this->observaciones,112,"</div><div style=\"width:380px;margin-top:3px\">",true).'</div></div><div style="margin-top:10px"><table><tr><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:392px">Cadena original del complemento de certificacion del SAT</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:352px">Codigo original del complemento de certificacion del SAT</td></tr></table></div><div><table><tr><td style="font-size:8px;width:392px"><div><div>'.wordwrap($this->selloDigitales[0],58,"</div><div>",true).'</div></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';width:387px;margin-top:10px">Sello digital del emisor</div><div style="width:387px"><div>'.wordwrap($this->selloDigitales[1],58,"</div><div>",true).'</div></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';width:387px;margin-top:10px">Sello digital del SAT</div><div><div>'.wordwrap($this->selloDigitales[2],58,"</div><div>",true).'</div></div></td><td style="font-size:8px;width:352px;border-left:1px dashed '.$this->color.'"><img style="-webkit-user-select:none" src=""></td></tr></table></div><div style="font-size:8px;padding-left:100px"><table><tr><td>ESTE DOCUMENTO ES UNA REPRESENTACION IMPRESA DE UN CFDI</td><td style="padding-left:50px;border-collapse:collapse">Este Documento Fue Generado Utilizando Herramientas De Netwarmonitor</td></tr></table></div><div>Este Documento de creo con herramientas de Netwarmonitor</div><div>'.$this->datosCFD[4].'</div></body></html>'; */
								$content.='<tr><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';text-align:left">Total</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';text-align:right">'.$this->total[1].' '.$this->datosCFD[3].'</td></tr></table></div><div style="margin-left:10px"><table><tr><td style="font-size:11px;border-bottom:1px dashed '.$this->color.';border-right:1px dashed '.$this->color.';padding-left:30px;padding-right:10px">TOTAL EN LETRA:</td><td style="font-size:10px;border-bottom:1px dashed '.$this->color.';padding-left:5px">'.$this->metodoPago[2].'</td></tr></table></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';margin-top:10px">Observaciones</div><div style="font-size:10px;border:1px solid '.$this->color.';padding:5px"><div style="text-align: justify; width:730px;padding-top:-2px">'.$this->observaciones.'</div></div><div style="margin-top:10px"><table><tr><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:392px">Cadena original del complemento de certificacion del SAT</td><td style="font-weight:bold;font-size:11px;background:'.$this->color.';width:352px">Codigo original del complemento de certificacion del SAT</td></tr></table></div><div><table><tr><td style="font-size:8px;width:392px"><div><div>'.wordwrap($this->selloDigitales[0],58,"</div><div>",true).'</div></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';width:387px;margin-top:10px">Sello digital del emisor</div><div style="width:387px"><div>'.wordwrap($this->selloDigitales[1],58,"</div><div>",true).'</div></div><div style="font-weight:bold;font-size:11px;background:'.$this->color.';width:387px;margin-top:10px">Sello digital del SAT</div><div><div>'.wordwrap($this->selloDigitales[2],58,"</div><div>",true).'</div></div></td><td style="font-size:8px;width:352px;border-left:1px dashed '.$this->color.'"><img style="-webkit-user-select:none" src=""></td></tr></table></div><div style="font-size:8px;padding-left:100px"><table><tr><td>ESTE DOCUMENTO ES UNA REPRESENTACION IMPRESA DE UN CFDI</td><td style="padding-left:50px;border-collapse:collapse">Este Documento Fue Generado Utilizando Herramientas De Netwarmonitor</td></tr></table></div><div>Este Documento de creo con herramientas de Netwarmonitor</div><div>'.$this->datosCFD[4].'</div></body></html>';
						  
								break;
							}

				
				if($this->datosCFD[2]=='Cotizacion' || $this->datosCFD[2]=='Prefactura' || $this->datosCFD[2]=='Remision'){

					$this->datosCFD[2] = 'cotizacion';

				}else if($this->datosCFD[2]=='Orden de venta'){

					$this->datosCFD[2] = 'Oventa';

				}else if($this->datosCFD[2]=='Envio'){
					$this->datosCFD[2] = 'Envio';
				}else{
					$this->datosCFD[2] = 'pedido'; 
				}
		 $content;
				$this->pdf -> WriteHTML($content);// Indicamos la variable con el contenido que queremos incluir en el pdf
				$this->pdf -> Output(dirname(__FILE__).'/../../cotizaciones/cotizacionesPdf/'.$this->datosCFD[2].'_'.$this->datosCFD[0].'.pdf', 'F'); //Generamos el archivo "archivo_pdf.pdf". Ponemos como parametro 'D' para forzar la descarga del archivo.
				//@unlink($strFileName);
			}

		   /* function creaQR($texto) {
				include dirname(__FILE__) . "phpqrcode/qrlib.php";
				$ruta = dirname(__FILE__).'/../../facturas/'.$this->datosCFD[0].'.png';
				QRcode::png($texto, $ruta);
				return $ruta;

			} */
	}
?>
