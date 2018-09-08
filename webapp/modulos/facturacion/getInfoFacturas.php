<?php
//set_time_limit(3000);
	//ini_set('display_errors', 1);
	include "../../netwarelog/webconfig.php";
	include "../../netwarelog/catalog/conexionbd.php";
	

	class getInfoFacturas{
		
		var $conexion;

	    function getInfoFacturas($conexion_enviada){
	        $this->conexion=$conexion_enviada;
	    }	
		
		function cancelFactura($idVenta){
			$clave='cancel';
			require_once("pre_facturacion.php");
	    }
	    function cuponNombre($rfc,$id){

	    	$result = $this->conexion->consultar("SELECT nombre,razon_social FROM comun_facturacion where rfc='".$rfc."' order by nombre desc");
				
			if($rs = $this->conexion->siguiente($result)){
				$idCliente=$rs{'nombre'};
				$razonsocial = $rs{'razon_social'};
				$formas = $rs{'serieCsdEmisor'};
				//break;
		    }
		    $result1 = $this->conexion->consultar("SELECT serieCsdEmisor FROM pvt_respuestaFacturacion where id=".$id);
				
			if($rs1 = $this->conexion->siguiente($result1)){
				$formas = $rs1{'serieCsdEmisor'};
				//break;
		    }

		    //echo "SELECT cupon from comun_cliente_inadem where idCliente=".$idCliente;
		   /* $result2 = $this->conexion->consultar("SELECT cupon from comun_cliente_inadem where idCliente=".$idCliente);
		    if($rs2 = $this->conexion->siguiente($result2)){
				$cuponInadem=$rs2{'cupon'};
				//break;
		    } */
		    $cuponInadem='';
		    if($cuponInadem==null || $cuponInadem==''){
		    	return array("receptor" => str_replace(' ','_',$razonsocial), "cupon" => false, "formas" => $formas);
		    }else{
		    	return array("receptor" => str_replace(' ','_',$razonsocial), "cupon" => $cuponInadem, "formas" => $formas);
		    }
		    
	    }
	    function guardaTrackid($trackid,$id){
	    	$result = $this->conexion->consultar("UPDATE pvt_respuestaFacturacion SET trackid='".$trackid."' WHERE id='".$id."'");

	    	return array("estatus" => true);
	    }

	    function allfs($cadena){
			$clave='allfs';

			require_once("pre_facturacion.php");
	    }
		function enviadaCancelar($id){
			$result = $this->conexion->consultar("UPDATE pvt_respuestaFacturacion SET borrado='10' WHERE id='".$id."'");

	    	return array("estatus" => true);
		}
		function cancelCredit($id){
			$result = $this->conexion->consultar("SELECT total FROM pvt_contadorFacturas;");
			if($rs = $this->conexion->siguiente($result)){
				$totals=$rs{'total'};
		    }
		    if($totals>100){
		    	echo 100;
		    	exit();
		    }
			$pp='pos';
			$clave='genericFact';
			require_once("pre_facturacion.php");
			require_once('../../modulos/WS_facturacion.php');
			$result = $this->conexion->consultar("INSERT INTO pvt_respuestaFacturacion (idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,tipoComp) VALUES ('".$iids."','0','".$UUID."','".$folio."','".$noCertificadoSAT."','".$noCertificado."','".$selloSAT."','".$selloCFD."', NOW(), '".$f_comp."')");
            $result = $this->conexion->consultar("UPDATE pvt_respuestaFacturacion SET borrado=1 WHERE id='$idVenta'");
            $result = $this->conexion->consultar("UPDATE pvt_serie_folio SET folio=folio+1");
            $result = $this->conexion->consultar("UPDATE pvt_contadorFacturas SET total=total+1");
		}	
		
		function genericFact($idVenta){
			$result = $this->conexion->consultar("SELECT total FROM pvt_contadorFacturas;");
			if($rs = $this->conexion->siguiente($result)){
				$totals=$rs{'total'};
		    }
		    if($totals>100){
		    	echo 100;
		    	exit();
		    }
			$pp='pos';
			$clave='genericFact';
			require_once("pre_facturacion.php");
			require_once('../../modulos/WS_facturacion.php');
			$result = $this->conexion->consultar("INSERT INTO pvt_respuestaFacturacion (idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,tipoComp) VALUES ('".$iids."','0','".$UUID."','".$folio."','".$noCertificadoSAT."','".$noCertificado."','".$selloSAT."','".$selloCFD."', NOW(), '".$f_comp."')");
            $result = $this->conexion->consultar("UPDATE pvt_respuestaFacturacion SET borrado=1 WHERE id='$idVenta'");
            $result = $this->conexion->consultar("UPDATE pvt_serie_folio SET folio=folio+1");
            $result = $this->conexion->consultar("UPDATE pvt_contadorFacturas SET total=total+1");
            //$result = $this->conexion->consultar("UPDATE comun_parametros_licencias SET valor=valor-1 WHERE parametro='Facturas'");
		}	
		
		function guardaNc($idVenta,$iva,$montosiniva,$total,$folio){
			$result = $this->conexion->consultar("UPDATE pvt_serie_folio SET folio_nc=folio_nc+1");
			//var_dump($result);
			//exit();
			$clave='guardaNc';
			require_once("pre_facturacion.php");
		}	
		
		function guardaCancel($id){
			//echo $_POST['ffolio'];
			/*$result = $this->conexion->consultar("select idSale, idFact from pvt_respuestaFacturacion where id='$id'");
			if($rs = $this->conexion->siguiente($result)){
				$sale=$rs{'idSale'};
				$fact=$rs{'idFact'};
				$result = $this->conexion->consultar("INSERT INTO pvt_respuestaFacturacion (idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha) VALUES ('".$sale."','".$fact."','".$_POST['ffiscal']."','".$_POST['ffolio']."','".$_POST['fnoCerS']."','".$_POST['fnocertf']."','".$_POST['fsellosat']."','".$_POST['fsellocfd']."', NOW())");
			}*/
			
		}
		
		function configSaveRfc($rfc,$razon){
			$result = $this->conexion->consultar("select rfc from configFactura");
			if($rs = $this->conexion->siguiente($result)){
				$result = $this->conexion->consultar("UPDATE configFactura  SET rfc='".strtoupper($rfc)."', razonsocial='".strtoupper($razon)."' WHERE id=1");
			}
			else{
				$result = $this->conexion->consultar("INSERT INTO configFactura (rfc,razonsocial,fecha) values ('".strtoupper($rfc)."','".strtoupper($razon)."',NOW())");
			}
			$this->conexion->cerrar_consulta($result);
		}
		
		function refacturarInsert($idVenta,$rfc,$razons,$regimenf,$calle,$numext,$colonia,$municipio,$ciudad,$cp,$estado,$pais,$correos){
			$result = $this->conexion->consultar("select rfc from comun_facturacion where rfc='$rfc'");
			if($rs = $this->conexion->siguiente($result)){
				$this->conexion->cerrar_consulta($result);
				return "-1";
			}
			else{
				$result = $this->conexion->consultar("select c.idCliente from pvt_respuestaFacturacion b inner join venta c on c.idVenta=b.idSale where b.id='$idVenta'");
				if($rs = $this->conexion->siguiente($result)){
					$result = $this->conexion->consultar("INSERT INTO comun_facturacion (nombre,rfc,razon_social,correo,pais,regimen_fiscal,domicilio,num_ext,cp,colonia,estado,ciudad,municipio) values ('".$rs{'idCliente'}."','".strtoupper($rfc)."','".strtoupper($razons)."','".strtoupper($correos)."','".strtoupper($pais)."','".strtoupper($regimenf)."','".strtoupper($calle)."','".strtoupper($numext)."','".strtoupper($cp)."','".strtoupper($colonia)."','".strtoupper($ciudad)."','".strtoupper($municipio)."','".strtoupper($municipio)."')");
					$iddFact=$this->conexion->insert_id();
					$clave='reff';
					$pp='pos';
					require_once("pre_facturacion.php");
					require_once('../../modulos/WS_facturacion.php');
					$result = $this->conexion->consultar("INSERT INTO pvt_respuestaFacturacion (idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,tipoComp) VALUES ('".$iids."','".$iddFact."','".$UUID."','".$folio."','".$noCertificadoSAT."','".$noCertificado."','".$selloSAT."','".$selloCFD."', NOW(), '".$f_comp."')");
					$result = $this->conexion->consultar("UPDATE pvt_serie_folio SET folio=folio+1");
            		$result = $this->conexion->consultar("UPDATE pvt_contadorFacturas SET total=total+1");
            		//$result = $this->conexion->consultar("UPDATE comun_parametros_licencias SET valor=valor-1 WHERE parametro='Facturas'");
		            if(isset($f_tc) && $f_tc=='C'){
	                	$result = $this->conexion->consultar("UPDATE pvt_respuestaFacturacion SET borrado=2 WHERE id='$idVenta'");
		            }else{
		              	$result = $this->conexion->consultar("UPDATE pvt_respuestaFacturacion SET borrado=1 WHERE id='$idVenta'");
		            }
				}
				$this->conexion->cerrar_consulta($result);
				return "0";
			}	
		}
		
		function refacturarListo($idVenta){
			$result = $this->conexion->consultar("SELECT total FROM pvt_contadorFacturas;");
			if($rs = $this->conexion->siguiente($result)){
				$totals=$rs{'total'};
		    }
		    if($totals>100){
		    	echo 100;
		    	exit();
		    }
			$iddFact=$_POST['idFact'];
			$clave='reff';
			$pp='pos';
			require_once("pre_facturacion.php");
			require_once('../../modulos/WS_facturacion.php');
			$result = $this->conexion->consultar("INSERT INTO pvt_respuestaFacturacion (idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,tipoComp) VALUES ('".$iids."','".$iddFact."','".$UUID."','".$folio."','".$noCertificadoSAT."','".$noCertificado."','".$selloSAT."','".$selloCFD."', NOW(), '".$f_comp."')");
            if(isset($f_tc) && $f_tc=='C'){
                $result = $this->conexion->consultar("UPDATE pvt_respuestaFacturacion SET borrado=2 WHERE id='$idVenta'");
            }else{
              $result = $this->conexion->consultar("UPDATE pvt_respuestaFacturacion SET borrado=1 WHERE id='$idVenta'");
            }
            $result = $this->conexion->consultar("UPDATE pvt_serie_folio SET folio=folio+1");
            $result = $this->conexion->consultar("UPDATE pvt_contadorFacturas SET total=total+1");
            //$result = $this->conexion->consultar("UPDATE comun_parametros_licencias SET valor=valor-1 WHERE parametro='Facturas'");
		}

		
		function emailFactura($id,$pdf,$netwarelog_correo_usu,$netwarelog_correo_pwd){

			require_once('../../modulos/phpmailer/sendMail.php');
			
			//$result = $this->conexion->consultar("select b.correo correos, a.folio from pvt_respuestaFacturacion a inner join comun_facturacion b on b.id=a.idFact where a.id='$id'");
			$result = $this->conexion->consultar("select cadenaOriginal,folio from pvt_respuestaFacturacion where id='$id'");

			$content="";
			$correo="";
			if($rs = $this->conexion->siguiente($result)){
				//$content.=$rs{'correos'};
				//echo $rs{'cadenaOriginal'}.'puto';
				$x = base64_decode($rs{'cadenaOriginal'});
				$x = json_decode($x);
				$azurian = $this->object_to_array($x);
				$result2 = $this->conexion->consultar("select correo from comun_facturacion where rfc='".$azurian['Receptor']['rfc']."' limit 1");
				if($rs2 = $this->conexion->siguiente($result2)){
					$azurian['Correo']['Correo'] = $rs2{'correo'};
				}
			
				$correos=explode(';',$rs{'correos'});

				$mail->From = "mailer@netwarmonitor.com";
				$mail->FromName = "NetwareMonitor";
				$mail->Subject = "Factura Generada";
				$mail->AltBody = "NetwarMonitor";
				$mail->MsgHTML('Factura Generada');
				$mail->AddAttachment("../../modulos/facturas/".$rs{'folio'}.'.pdf');
				$mail->AddAttachment("../../modulos/facturas/".$rs{'folio'}.'.xml');
				$mail->AddAttachment("../../modulos/cont/xmls/facturas/temporales/".$rs{'folio'}.'.xml');
				$mail->AddAddress($azurian['Correo']['Correo'],$azurian['Correo']['Correo']);
					
				
				if(!$mail->Send()) {
					$content="Error al intentar enviar los correos, porfavor vuelva a intentarlo!";
					//$content="Se enviaron los siguientes correos a: </br>".$correo;
				} else {
					$content="Se envio el correo</br>";
				}
			}	
			$this->conexion->cerrar_consulta($result);
			return $content;	
		}
	

		function loadSelect($id){
			$result = $this->conexion->consultar("select b.idCliente from pvt_respuestaFacturacion a inner join venta b on b.idVenta=a.idSale where a.id='".$id."'");
			if($rs = $this->conexion->siguiente($result)){
				$result = $this->conexion->consultar("select id, rfc from comun_facturacion where nombre='".$rs{'idCliente'}."'");
				$content='<select onchange="activaformfact()" id="combo_fact"><option selected="selected" value="0">Selecciona un RFC</option>';
				while($rs = $this->conexion->siguiente($result)){
					$content.="<option value='".$rs{'id'}."'>".$rs{'rfc'}."</option>";
				}
				$this->conexion->cerrar_consulta($result);
				return $content;
			}	
			else{
				$this->conexion->cerrar_consulta($result);
				return "";
			}
		}
		
		function getSales(){
			$result = $this->conexion->consultar("select b.idVenta id, date(b.fecha) fecha from pvt_pendienteFactura a inner join venta b on b.idVenta=a.id_sale where a.facturado=0 group by date(b.fecha)");
			$content='Fecha: <select class="selectFact"><option value="0">Seleccione una fecha</option>';
			//$content="";	
			while($rs = $this->conexion->siguiente($result)){
				$content.="<option value='".$rs{'id'}."'>".$rs{'fecha'}."</option>";
				//$content.=$rs{'id'}." ".$rs{'fecha'}." </br> ";
			}
			$content.='</select>';
			$this->conexion->cerrar_consulta($result);
			return $content;
		}

		function allFacts($fecha){
			$result = $this->conexion->consultar("SELECT total FROM pvt_contadorFacturas;");
			if($rs = $this->conexion->siguiente($result)){
				$totals=$rs{'total'};
		    }
		    if($totals>100){
		    	echo 100;
		    	exit();
		    }
			$pp='pos';
			$clave='allFacts';
			require_once("pre_facturacion.php");
			ob_start();
			require_once('../../modulos/WS_facturacion.php');
			if($er==1){
				ob_end_clean();
				echo 'Ha ocurrido un error al intentar facturar > ';
        		echo $result['faultstring'];
        		exit();
			}
			$result = $this->conexion->consultar("INSERT INTO facturacion_os (ids_sales,total) VALUES ('".$id_sale_array."','".$montoTotal."')");
			$last_id=$this->conexion->insert_id();
			$result = $this->conexion->consultar("INSERT INTO pvt_respuestaFacturacion (idSale,idFact,idOs,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,tipoComp) VALUES ('".$iids."','0','".$last_id."','".$UUID."','".$folio."','".$noCertificadoSAT."','".$noCertificado."','".$selloSAT."','".$selloCFD."', NOW(), '".$f_comp."')");
			$result =$this->conexion->consultar("UPDATE pvt_pendienteFactura SET facturado=1 WHERE id in(".$array.")");
			$result = $this->conexion->consultar("UPDATE pvt_serie_folio SET folio=folio+1");
            $result = $this->conexion->consultar("UPDATE pvt_contadorFacturas SET total=total+1");
            //$result = $this->conexion->consultar("UPDATE comun_parametros_licencias SET valor=valor-1 WHERE parametro='Facturas'");
			$this->conexion->cerrar_consulta($result);
			return "Se ha Facturado correctamente";
		}
		
		function oneFact($id,$rrfc,$addo){
			$clave='oneFact';
			require_once("pre_facturacion.php");
		}

		/*function timbresFacts($id){
			
			$res = $this->conexion->consultar("SELECT valor from comun_parametros_licencias WHERE parametro='Facturas'");
			while($rs = $this->conexion->siguiente($res))
				$fact=$rs{'valor'};
			
			if($fact==0){
				//$content="<label id='permiso'>No puedes facturar</label>";
				return 0;
			}else{
				return 1;
				/*if($id > 0){
					$content="<label id='ho'>No puedes facturar</label>";
				}else{
					$result = $this->conexion->consultar("select b.rfc, b.id from pvt_pendienteFactura a inner join comun_facturacion b on b.nombre=a.id_cliente where a.id_sale=".$id);
					$content="<select id='cmbRFCs'>";
					while($rs = $this->conexion->siguiente($result))
						$content.="<option value='".$rs{'id'}."'>".$rs{'rfc'}."</option>";
					$content.="<option value='0'>XAXX010101000</option></select>";	
				} */
			
			/*}

			return $content;
		}	*/
			function object_to_array($data){
			    if (is_array($data) || is_object($data))
			      {
			          $result = array();
			          foreach ($data as $key => $value){
			              $result[$key] = $this->object_to_array($value);
			          }
			          return $result;
			      }
			      return $data;
  			}

		function getFacts($id){
			
			/*$res = $this->conexion->consultar("SELECT valor from comun_parametros_licencias WHERE parametro='Facturas'");
			while($rs = $this->conexion->siguiente($res))
				$fact=$rs{'valor'}; */
			
			
					$result = $this->conexion->consultar("select b.rfc, b.id from pvt_pendienteFactura a inner join comun_facturacion b on b.nombre=a.id_cliente where a.id_sale=".$id);
					$content="<select id='cmbRFCs'>";
					while($rs = $this->conexion->siguiente($result))
						$content.="<option value='".$rs{'id'}."'>".$rs{'rfc'}."</option>";
					$content.="<option value='0'>XAXX010101000</option></select>";	
		/*	if($fact==0){
				$content="<label id='permiso'>No puedes facturar</label>";
			}else{
				if($id==0){
					$content="<label id='ho'>No puedes facturar</label>";
				}else{
					$result = $this->conexion->consultar("select b.rfc, b.id from pvt_pendienteFactura a inner join comun_facturacion b on b.nombre=a.id_cliente where a.id_sale=".$id);
					$content="<select id='cmbRFCs'>";
					while($rs = $this->conexion->siguiente($result))
						$content.="<option value='".$rs{'id'}."'>".$rs{'rfc'}."</option>";
					$content.="<option value='0'>XAXX010101000</option></select>";	
				} 
			
			} */
			return $content;
		}
	}

	if(isset($_POST["accion"]) && isset($_POST["id"])){
		$menus = new getInfoFacturas($conexion);
		$id=$_POST["id"];
		switch($_POST["accion"]){
			case "cancelfact":
				exit($menus->cancelFactura($id));				
			break;
			
			case "cancelcredit":
				exit($menus->cancelCredit($id));				
			break;
			
			case "genericFact":
				exit($menus->genericFact($id));				
			break;
			
			case "guardaCancel":
				exit($menus->guardaCancel($id));				
			break;
			
			case "guardanc":

				exit($menus->guardaNc($id,$_POST['iva'],$_POST['montosiniva'],$_POST['total'],$_POST['folio']));				
			break;
			
			case "configSaveRfc":
				exit($menus->configSaveRfc($_POST['rfc'],$_POST['razon']));				
			break;
			
			case "refacturarInsert":
				exit($menus->refacturarInsert($id,$_POST['rfc'],$_POST['razons'],$_POST['regimenf'],$_POST['calle'],$_POST['numext'],$_POST['colonia'],$_POST['municipio'],$_POST['ciudad'],$_POST['cp'],$_POST['estado'],$_POST['pais'],$_POST['correos']));				
			break;
			
			case "refacturarListo":
				exit($menus->refacturarListo($id));				
			break;
			
			case "loadSelect":
				exit($menus->loadSelect($id));				
			break;
				
			case "email":
				echo $menus->emailFactura($id,$_POST['pdf'],$netwarelog_correo_usu,$netwarelog_correo_pwd);
			break;
			
			case "getSales":
				echo $menus->getSales();
			break;
			
			case "allFacts":
				echo $menus->allFacts($_POST["fecha"]);
			break;

			case "allfs":
				echo $menus->allfs($id);
			break;
			
			case "oneFact":
				$rrfc=$_POST['rrfc'];
				$addo=$_POST['addo'];
				echo $menus->oneFact($id,$rrfc,$addo);
			break;
			
			case "getFacts":
				echo $menus->getFacts($id);
			break;
			case "cuponNombre":
				$rfc = $_POST['rfc'];
				$id = $_POST['id'];
				echo json_encode($menus->cuponNombre($rfc,$id));
			break;
			case "guardaTrackid":
				$trackid = $_POST['xxxx'];
				echo json_encode($menus->guardaTrackid($trackid,$id));
			case "enviadaCancelar":
				$id = $_POST['id'];
				echo json_encode($menus->enviadaCancelar($id));	
			break;	
			//case "timbresFacts";
			//	echo $menus->timbresFacts();	*/
			//break;
		}

		//exit($texto);
	}

?>
