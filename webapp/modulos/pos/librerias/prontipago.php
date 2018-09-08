<?php

	class Prontipago
	{
		
		const URL_WEBSERVICE = "https://ws.prontipagos.mx/siveta-endpoint-ws-1.0-SNAPSHOT/ProntipagosTopUpServiceEndPoint?wsdl";
		const NS_WEBSERVICE = "http://prontipagos.ws.com";

		private $usuario;
		private $contrasena;

		function __construct($usuario, $contrasena)
		{
			$this->usuario = $usuario;
			$this->contrasena = $contrasena;
		}

		public function obtenerListadoProductos() {
			$xml_metodo = 'obtainCatalogProducts';
			$xml_cuerpo = '
			    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siv="'. self::NS_WEBSERVICE .'">
			        <soapenv:Header/>
			        <soapenv:Body>
			            <siv:obtainCatalogProducts/>
			        </soapenv:Body>
			    </soapenv:Envelope>
			';

			$peticion = $this->peticion($xml_metodo, $xml_cuerpo);

			try{
				if(strpos($peticion, "This request requires HTTP authentication ().") === false) {
					$lector = new DOMDocument();
				    $lector->loadXML($peticion);
				    $lector_nodos = $lector->getElementsByTagName('products');
				    $productos = array();
				    
				    $productos[] = array("descripcion" => "TIEMPO AIRE TELCEL RECARGA 20 MNX", "cuota" => "false", "precio" => "20", "nombre" => "TIEMPO AIRE TELCEL RECARGA 20 MNX", "sku" => "TELCEL20");
				    $productos[] = array("descripcion" => "TIEMPO AIRE TELCEL RECARGA 30 MNX", "cuota" => "false", "precio" => "30", "nombre" => "TIEMPO AIRE TELCEL RECARGA 30 MNX", "sku" => "TELCEL30");
				    $productos[] = array("descripcion" => "TIEMPO AIRE TELCEL RECARGA 50 MNX", "cuota" => "false", "precio" => "50", "nombre" => "TIEMPO AIRE TELCEL RECARGA 50 MNX", "sku" => "TELCEL50");
				    $productos[] = array("descripcion" => "TIEMPO AIRE TELCEL RECARGA 100 MNX", "cuota" => "false", "precio" => "100", "nombre" => "TIEMPO AIRE TELCEL RECARGA 100 MNX", "sku" => "TELCEL100");
				    $productos[] = array("descripcion" => "TIEMPO AIRE TELCEL RECARGA 200 MNX", "cuota" => "false", "precio" => "200", "nombre" => "TIEMPO AIRE TELCEL RECARGA 200 MNX", "sku" => "TELCEL200");
				    $productos[] = array("descripcion" => "TIEMPO AIRE TELCEL RECARGA 500 MNX", "cuota" => "false", "precio" => "500", "nombre" => "TIEMPO AIRE TELCEL RECARGA 500 MNX", "sku" => "TELCEL500");

				    foreach($lector_nodos as $nodo){
				    	$descripcion = $nodo->getElementsByTagName('description')->item(0)->nodeValue;
				    	$cuota_fija = $nodo->getElementsByTagName('fixedFee')->item(0)->nodeValue;
				    	$precio = $nodo->getElementsByTagName('price')->item(0)->nodeValue;
				    	$nombre = $nodo->getElementsByTagName('productName')->item(0)->nodeValue;
				    	$sku = $nodo->getElementsByTagName('sku')->item(0)->nodeValue;
				    	$productos[] = array("descripcion" => $descripcion, "cuota" => $cuota_fija, "precio" => $precio, "nombre" => $nombre, "sku" => $sku);
				    }
				    
				    $json = array("status" => true, "productos" => $productos);
				}else{
					throw new Exception("Usuario o contraseña incorrectos, favor de verificar", 1);
				}
			}catch(Exception $e){
				$json = array("status" => false, "mensaje" => $e->getMessage());
			}

		    return $json;
		}

		private function peticion($metodo, $cuerpo) {
		    $autentificacion = $this->usuario . ":" . $this->contrasena;
		    $encabezados = array(
		        'Content-Type: text/xml; charset="utf-8"',
		        'Content-Length: ' . strlen($cuerpo),
		        'Accept: text/xml',
		        'Cache-Control: no-cache',
		        'Pragma: no-cache',
		        'SOAPAction: "' . $metodo . '"'
		    );
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($curl, CURLOPT_URL, self::URL_WEBSERVICE);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_TIMEOUT, 180);
		    curl_setopt($curl, CURLOPT_HTTPHEADER, $encabezados);
		    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		    curl_setopt($curl, CURLOPT_POST, true);
		    curl_setopt($curl, CURLOPT_POSTFIELDS, $cuerpo);
		    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		    curl_setopt($curl, CURLOPT_USERPWD, $autentificacion);
		    $resultado = curl_exec($curl);
		    curl_close($curl);
		    unset($curl);
		    return $resultado;
		}

	}

?>