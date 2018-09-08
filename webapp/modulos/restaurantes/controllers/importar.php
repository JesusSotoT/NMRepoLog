<?php
/**
 * @author chais
 */
 
require('common.php');
require("models/importar.php");

class importar extends Common{
	public $importarModel;

	function __construct(){
		$this->importarModel = new importarModel();
		//$this->importarModel->connect();
	}
		
	function importar(){
		require('views/importar/importar.php');
	}
	function parseToXML($xmlStr)  {

		    $xmlStr = trim($xmlStr);

		    $xmlStr = str_replace(
		        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
		        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
		        $xmlStr
		    );

		    $xmlStr = str_replace(
		        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
		        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
		        $xmlStr
		    );

		    $xmlStr = str_replace(
		        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
		        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
		        $xmlStr
		    );

		    $xmlStr = str_replace(
		        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
		        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
		        $xmlStr
		    );

		    $xmlStr = str_replace(
		        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
		        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
		        $xmlStr
		    );

		    $xmlStr = str_replace(
		        array('ñ', 'Ñ', 'ç', 'Ç'),
		        array('n', 'N', 'c', 'C',),
		        $xmlStr
		    );
			/*
			$xmlStr = str_replace("[<>]","-",$xmlStr);
			$xmlStr = str_replace('<','&lt;',$xmlStr); 
			$xmlStr = str_replace('>','&gt;',$xmlStr); 
			$xmlStr = str_replace('"','&quot;',$xmlStr); 
			*/
			return $xmlStr;
	}
	function subirclientes(){

		$xmlStr = file_get_contents('temp_archivos/clientes.xml');
		$xmlStr = $this->parseToXML($xmlStr);
		$xmlObj = simplexml_load_string($xmlStr);	
		
		foreach ($xmlObj->curtemp as $key) {
 	
		 	$clientes[] = array(
		        idcliente    			=> (string) $key->idcliente,
		        nombre       			=> (string) $key->nombre,
		        direccion       		=> (string) $key->direccion,                                    
		        codigopostal       		=> (string) $key->codigopostal,
		        poblacion       		=> (string) $key->poblacion,
		        pais       				=> (string) $key->pais,
		        email       			=> (string) $key->email,
		        rfc       				=> (string) $key->rfc,
		        curp       				=> (string) $key->curp,
		        cumpleanos       		=> (string) $key->cumpleanos,
		        limitecredito       	=> (string) $key->limitecredito,
		        limitecreditodiario     => (string) $key->limitecreditodiario,
		        notas       			=> (string) $key->notas,
		        foliofiscal       		=> (string) $key->foliofiscal,
		        idtipodescuento       	=> (string) $key->idtipodescuento,
		        tipofacturacion       	=> (string) $key->tipofacturacion,
		        procesadoweb       		=> (string) $key->procesadoweb,
		        nocobrarimpuestos       => (string) $key->nocobrarimpuestos,
		        contacto       			=> (string) $key->contacto,
		        targetamonedero       	=> (string) $key->targetamonedero,
		        telefono1       		=> (string) $key->telefono1,
		        femextipocliente       	=> (string) $key->femextipocliente,
		        giro       				=> (string) $key->giro,
		        tipocredito       		=> (string) $key->tipocredito,
		        idtipocliente       	=> (string) $key->idtipocliente,
		        idtipomenu       		=> (string) $key->idtipomenu,
		        fotografia       		=> (string) $key->fotografia,
		        fechaalta       		=> (string) $key->fechaalta,
		        retenerimpuesto       	=> (string) $key->retenerimpuesto,
		        tipocuenta       		=> (string) $key->tipocuenta,
		        tipoclientenfc       	=> (string) $key->tipoclientenfc
		    );
		    
		 }

		$clientes = $this->importarModel->saveClientes($clientes);
		//echo json_encode($clientes);
	}
	function subirproductos(){
		$xmlStr = file_get_contents('temp_archivos/productos.xml');
		$xmlStr = $this->parseToXML($xmlStr);
		$xmlObj = simplexml_load_string($xmlStr);	
		
		foreach ($xmlObj->curtemp as $key) {
 	
		 	$productos[] = array(
		        idcliente    			=> (string) $key->idcliente,
		        nombre       			=> (string) $key->nombre,
		        direccion       		=> (string) $key->direccion,                                    
		        codigopostal       		=> (string) $key->codigopostal,
		        poblacion       		=> (string) $key->poblacion,
		        pais       				=> (string) $key->pais,
		        email       			=> (string) $key->email,
		        rfc       				=> (string) $key->rfc,
		        curp       				=> (string) $key->curp,
		        cumpleanos       		=> (string) $key->cumpleanos,
		        limitecredito       	=> (string) $key->limitecredito,
		        limitecreditodiario     => (string) $key->limitecreditodiario,
		        notas       			=> (string) $key->notas,
		        foliofiscal       		=> (string) $key->foliofiscal,
		        idtipodescuento       	=> (string) $key->idtipodescuento,
		        tipofacturacion       	=> (string) $key->tipofacturacion,
		        procesadoweb       		=> (string) $key->procesadoweb,
		        nocobrarimpuestos       => (string) $key->nocobrarimpuestos,
		        contacto       			=> (string) $key->contacto,
		        targetamonedero       	=> (string) $key->targetamonedero,
		        telefono1       		=> (string) $key->telefono1,
		        femextipocliente       	=> (string) $key->femextipocliente,
		        giro       				=> (string) $key->giro,
		        tipocredito       		=> (string) $key->tipocredito,
		        idtipocliente       	=> (string) $key->idtipocliente,
		        idtipomenu       		=> (string) $key->idtipomenu,
		        fotografia       		=> (string) $key->fotografia,
		        fechaalta       		=> (string) $key->fechaalta,
		        retenerimpuesto       	=> (string) $key->retenerimpuesto,
		        tipocuenta       		=> (string) $key->tipocuenta,
		        tipoclientenfc       	=> (string) $key->tipoclientenfc
		    );
		    
		 }

		$productos = $this->importarModel->saveProductos($productos);
	}
	function deleteFiles(){			
		$dir = 'temp_archivos/';     
		$files = scandir($dir); 
		$ficherosEliminados = 0;
		foreach($files as $f){
		   if (is_file($dir.$f)) {
		      unlink($dir.$f);
		    }
		}
		echo 1;
	}

} ?>



















