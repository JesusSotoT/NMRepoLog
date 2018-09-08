<?php

	include_once("../../netwarelog/catalog/conexionbd.php"); 
	include_once('../../modulos/lib/nusoap.php');

	function validacion($clave,$var){
		if($clave=='pem'){
			$open = fopen($var, "r");
			$contenido = fread($open, filesize($var));
			fclose($open);
			if($contenido!=''){
				return 1;
			}else{
				return 0;
			}
			
		}
	}

	function validaCSD($cer_cliente,$pathdc){
		$comando='openssl x509 -inform DER -in '.$pathdc.'/'.$cer_cliente.' -noout -subject > "'.$pathdc.'/validoCSD.txt"';
	    exec($comando);
	    $validoCSD_open = fopen("".$pathdc."/validoCSD.txt", "r");
	    $validoCSD = fread($validoCSD_open, filesize($pathdc.'/validoCSD.txt'));
	    fclose($validoCSD_open);

	    if(preg_match('/(\/OU=)|(\/ou=)|(\/Ou=)|(\/oU=)/', $validoCSD) ){
	    	return 1;
	    }else{
	    	return 0;
	    }
	}

	function generaNoCertificado($rfc_cliente,$cer_cliente,$pathdc){
		$comando='openssl x509 -inform DER -in '.$pathdc.'/'.$cer_cliente.' -noout -serial > "'.$pathdc.'/noCertificado.txt"';
	    exec($comando);

	    $noCertificado_open = fopen("".$pathdc."/noCertificado.txt", "r");
	    $noCertificado = fread($noCertificado_open, filesize($pathdc.'/noCertificado.txt'));
	    fclose($noCertificado_open);

	    $noCertificado=  preg_replace("/serial=/", "", trim($noCertificado));
	    $temporal=  str_split($noCertificado);
	    $noCertificado="";
	    $i=0;
	    foreach ($temporal as $value) {
	        if(($i%2))
	        $noCertificado .= $value;
	        $i++;
	    }

    	return $noCertificado;

	}

	function generaPem($rfc_cliente,$key_cliente,$pwd_cliente,$pathdc){
		$pem = $pathdc.'/'.$rfc_cliente.'.pem';
		$comando="openssl pkcs8 -inform DER -in ".$pathdc."/".$key_cliente." -passin pass:'".$pwd_cliente."' -out ".$pem."";
    	exec($comando);

    	$validacion = validacion('pem',$pem);
    	return $validacion; 
	}
	$devolver=0;
	$q=mysql_query("SELECT * FROM ".$bd.".pvt_configura_facturacion WHERE id=1;");
	if(mysql_num_rows($q) >0){				
		$rs = mysql_fetch_assoc($q);
		$reg=$rs{'regimen'};
		$pai=$rs{'pais'};
		$raz=$rs{'razon_social'};
		$cal=$rs{'calle'};
		$num=$rs{'num_ext'};
		$col=$rs{'colonia'};
		$ciu=$rs{'ciudad'};
		$mun=$rs{'municipio'};
		$est=$rs{'estado'};
		$cp=$rs{'cp'};

		$rfc_cliente=$rs{'rfc'};
		$cer_cliente=$rs{'cer'};
		$key_cliente=$rs{'llave'};
		$pwd_cliente=$rs{'clave'};
		$pathdc='../../modulos/SAT/cliente';

		$esCSD = validaCSD($cer_cliente,$pathdc);
		if($esCSD==0){
			echo '<font color=#ff0000>**El archivo .cer es incorrecto y no podra facturar, favor de actualizar el archivo.</font><br><div style="display:none;">';
			$devolver=1;
		}

		$validoCer = generaNoCertificado($rfc_cliente,$cer_cliente,$pathdc);
		if($validoCer==''){
			echo '<font color=#ff0000>**El archivo .cer es incorrecto y no podra facturar, favor de actualizar el archivo.</font><br><div style="display:none;">';
			$devolver=1;
		}

		$validoKey = generaPem($rfc_cliente,$key_cliente,$pwd_cliente,$pathdc);
		if($validoKey==''){
			echo '<font color=#ff0000>**El archivo .key o la clave son incorrectos y no podra facturar, favor de verificar los archivos.</font><br><div style="display:none;">';
			$devolver=1;
		}
    }else{
    	echo '<font color=#ff0000>**No existen datos capturados, favor de verificar.</font><br><div style="display:none;">';
    	$devolver=1;
 
    }

		$link=$_SERVER['REQUEST_URI'];

    if($devolver==1){
    	mysql_query("UPDATE ".$bd.".pvt_configura_facturacion SET cer='', llave='', clave='' where id=1;");
    	$dir = "../../modulos/SAT/cliente/";
		$od = opendir($dir);
		while($file = readdir($od)){
			if(is_file($dir.$file)){
		  		unlink($dir.$file);
		 	}
		}

		unset($conexion);
    	$conexion=new conexion('nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com','nmdevel','nmdevel','nmdev_common',0,'mysql');
    	mysql_query("INSERT INTO nmdev_common.pvt_configura_facturacion (rfc,regimen,pais,razon_social,calle,num_ext,colonia,ciudad,municipio,estado,cp,cer,llave,clave,link,bd,seriali,ok) VALUES ('".$rfc_cliente."','".$reg."','".$pai."','".$raz."','".$cal."','".$num."','".$col."','".$ciu."','".$mun."','".$est."','".$cp."','".$cer_cliente."','".$key_cliente."','".$pwd_cliente."','".$link."','".$bd."','".$validoCer."',0)");

    }else{

    	unset($conexion);
    	$conexion=new conexion('nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com','nmdevel','nmdevel','nmdev_common',0,'mysql');
    	mysql_query("INSERT INTO nmdev_common.pvt_configura_facturacion (rfc,regimen,pais,razon_social,calle,num_ext,colonia,ciudad,municipio,estado,cp,cer,llave,clave,link,bd,seriali) VALUES ('".$rfc_cliente."','".$reg."','".$pai."','".$raz."','".$cal."','".$num."','".$col."','".$ciu."','".$mun."','".$est."','".$cp."','".$cer_cliente."','".$key_cliente."','".$pwd_cliente."','".$link."','".$bd."','".$validoCer."');");
    }

?>