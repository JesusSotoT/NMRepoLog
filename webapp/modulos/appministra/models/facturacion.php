<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class FacturacionModel extends Connection
{

    //////////MI ORGANIZACION////////////
    public function estados($idpais){
        $sql = "SELECT idestado, estado from estados;";
        $estados = $this->queryArray($sql);
        return $estados["rows"];
    }

    public function datos(){
    	$sql = "SELECT * FROM pvt_configura_facturacion";
    	$datos = $this->queryArray($sql);
    	return $datos['rows'];
    }

    public function serieFolio(){
    	$sql = "SELECT id, serie, folio,serie_h, folio_h, serie_nc, folio_nc  FROM pvt_serie_folio";
    	$datos = $this->queryArray($sql);
    	return $datos['rows'];
    }

    public function regimen(){
    	$sql = "SELECT * FROM c_regimenfiscal";
    	$datos = $this->queryArray($sql);
    	return $datos['rows'];
    }

    public function save($id,$rfc,$regimen,$pais,$razon,$domicilio,$num_ext,$colonia,$ciudad,$municipio,$estado,$cp,$cer,$key,$clave,$lugar_exp,$ticket,$pac,$userFC,$passFC,$passCiec,$noVersion){

        $cer =str_replace("C:\\fakepath\\", "", $cer);
        $key =str_replace("C:\\fakepath\\", "", $key);

        $razon = trim($razon);
        $domicilio = trim($domicilio);
    	$sql = "UPDATE pvt_configura_facturacion SET 
    				rfc = '$rfc', 
			    	regimen = $regimen, 
			    	pais = '$pais', 
			    	razon_social = '$razon', 
			    	calle = '$domicilio', 
			    	num_ext = '$num_ext', 
			    	colonia = '$colonia',
                    ciudad = '$ciudad', 
			    	municipio = '$municipio', 
			    	estado = '$estado', 
			    	cp = '$cp', 
			    	cer = '$cer', 
			    	llave = '$key', 
			    	clave = '$clave',			    	
			    	ticket_config = '$ticket',			    	 
			    	pac = '$pac',
			    	fc_user = '$userFC',
			    	fc_password = '$passFC',
			    	lugar_exp = '$lugar_exp',
			    	pass_ciec = '$passCiec',
                    version = '$noVersion'
			    WHERE id='$id';";
    	$datos = $this->queryArray($sql);

    /////////////////////////////////////////////////////////validacion cer///////////////////////////////////////////////////////
    if($cer != '' and $key != '' and $clave != ''){


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
        session_start();
        //print_r($_SESSION['bd']);
        $bd = $_SESSION['bd'];
        $devolver=0;
        //echo "SELECT * FROM ".$bd.".pvt_configura_facturacion WHERE id=1;";
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
                //echo '<font color=#ff0000>**El archivo .cer es incorrecto y no podra facturar, favor de actualizar el archivo.</font><br><div style="display:none;">';
                echo 'El archivo CSD es incorrecto y no podra facturar, favor de actualizar el archivo.';
                $devolver=1;
            }

            $validoCer = generaNoCertificado($rfc_cliente,$cer_cliente,$pathdc);
            if($validoCer==''){
                //echo '<font color=#ff0000>**El archivo .cer es incorrecto y no podra facturar, favor de actualizar el archivo.</font><br><div style="display:none;">';
                echo 'El archivo .cer es incorrecto y no podra facturar, favor de actualizar el archivo.';
                $devolver=1;
            }

            $validoKey = generaPem($rfc_cliente,$key_cliente,$pwd_cliente,$pathdc);
            if($validoKey==''){
                //echo '<font color=#ff0000>**El archivo .key o la clave son incorrectos y no podra facturar, favor de verificar los archivos.</font><br><div style="display:none;">';
                echo 'El archivo .key o la clave son incorrectos y no podra facturar, favor de verificar los archivos.';
                $devolver=1;
            }
        }else{
            //echo '<font color=#ff0000>**No existen datos capturados, favor de verificar.</font><br><div style="display:none;">';
            echo 'No existen datos capturados, favor de verificar.';
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

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        	return $datos['rows']; // Datos del primer query
        }
        return $datos['rows']; // Datos del primer query
    }

    public function saveSF($idSF,$serie,$folio,$serie_h,$folio_h,$serie_nc,$folio_nc,$serie_cp,$folio_cp){
    	$sql = "UPDATE pvt_serie_folio SET serie = '$serie', folio = '$folio', serie_h = '$serie_h', folio_h = '$folio_h', serie_nc = '$serie_nc', folio_nc = '$folio_nc' , serie_cp = '$serie_cp', folio_cp = '$folio_cp' WHERE id='$idSF';";
    	$datos = $this->queryArray($sql);
    	return $datos['rows'];
    }


    /* Javier w/h */
    public function obtener_series(){
      $myQuery = "SELECT id, serie, folio FROM pvt_serie_folio;";
      $Result = $this->query($myQuery);
      return $Result;
    }
    
    public function agregar_serie($arr){
      $serie = $arr['serie']; 
      $folio = $arr['folio'];

      $myQuery = "INSERT INTO pvt_serie_folio (serie, folio) VALUES ('$serie', '$folio');";
      $Result = $this->query($myQuery);
      return $Result;
    }

    public function modificar_serie($arr){
      $id = $arr['id'];
      $serie = $arr['serie']; 
      $folio = $arr['folio'];

      $myQuery = "UPDATE pvt_serie_folio SET serie='".$serie."', folio='".$folio."' WHERE id=$id;";
      $Result = $this->query($myQuery);
      return $Result;
    }

    public function eliminar_serie($arr){
      $id = $arr['id'];

      $myQuery = "DELETE FROM pvt_serie_folio WHERE id = $id";
      $Result = $this->query($myQuery);
      return $Result;
    }

}
?>