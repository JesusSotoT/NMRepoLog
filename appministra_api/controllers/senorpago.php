<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/senorpago.php");

    class Senorpago extends Commonapi
    {
        public $SenorpagoModel;

        public static $CREARSUBAFILIADOSENORPAGO = array(
                                 "email" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "contrasena" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "primer_apellido" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "segundo_apellido" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "calle" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "colonia" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "ciudad" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "codigo_postal" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "cumpleanos" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "tarjeta" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "ife_delantera" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "ife_posterior" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "comprobante_domicilio" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "contrato_pagina_1" => array("nulo" => true, "vacio" => false, "tipo" => "string"),
                                 "contrato_pagina_2" => array("nulo" => true, "vacio" => false, "tipo" => "string"),
                                 "contrato_pagina_3" => array("nulo" => true, "vacio" => false, "tipo" => "string"),
                                 "firma" => array("nulo" => true, "vacio" => false, "tipo" => "string"),
                                 "compania" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "ventas_mensuales" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "ventas_promedio" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                 "tipo_compania" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                                );

        public static $OBTENERSENORPAGO = array();

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->SenorpagoModel = new SenorpagoModel($this->Seguridad);
            $this->SenorpagoModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->SenorpagoModel->close();
        } 

        function crearSubAfiliadoSenorpago(){
            $email = $_REQUEST["email"];
            $contrasena = $_REQUEST["contrasena"];
            $nombre = $_REQUEST["nombre"];
            $primer_apellido = $_REQUEST["primer_apellido"];
            $segundo_apellido = $_REQUEST["segundo_apellido"];
            $calle = $_REQUEST["calle"];
            $colonia = $_REQUEST["colonia"];
            $ciudad = $_REQUEST["ciudad"];
            $codigo_postal = $_REQUEST["codigo_postal"];
            $cumpleanos = $_REQUEST["cumpleanos"];
            $tarjeta = $_REQUEST["tarjeta"];
            $ife_delantera = $_REQUEST["ife_delantera"];
            $ife_posterior = $_REQUEST["ife_posterior"];
            $comprobante_domicilio = $_REQUEST["comprobante_domicilio"];
            $contrato_pagina_1 = (isset($_REQUEST["contrato_pagina_1"])) ? $_REQUEST["contrato_pagina_1"] : null;
            $contrato_pagina_2 = (isset($_REQUEST["contrato_pagina_2"])) ? $_REQUEST["contrato_pagina_2"] : null;
            $contrato_pagina_3 = (isset($_REQUEST["contrato_pagina_3"])) ? $_REQUEST["contrato_pagina_3"] : null;
            $firma = (isset($_REQUEST["firma"])) ? $_REQUEST["firma"] : null;
            $compania = $_REQUEST["compania"];
            $ventas_mensuales = $_REQUEST["ventas_mensuales"];
            $ventas_promedio = $_REQUEST["ventas_promedio"];
            $tipo_compania = $_REQUEST["tipo_compania"];

            $resultado = $this->SenorpagoModel->crearSubAfiliadoSenorpago(  $email, $contrasena, $nombre, $primer_apellido, $segundo_apellido, 
                                                    $calle, $colonia, $ciudad, $codigo_postal, $cumpleanos, $tarjeta, 
                                                    $ife_delantera, $ife_posterior, $comprobante_domicilio, $contrato_pagina_1, 
                                                    $contrato_pagina_2, $contrato_pagina_3, $firma, $compania, 
                                                    $ventas_mensuales, $ventas_promedio, $tipo_compania);

            parent::responder($resultado);
        }

        function obtenerSenorpago(){
            $resultado = $this->SenorpagoModel->obtenerSenorpago();
            $resultado_validar = $this->SenorpagoModel->validarSubAfiliadoSenorpago();
            //parent::responder($resultado_validar);
            if($resultado["status"] && $resultado_validar["status"]) $resultado["rows"][0]["validacion"] = $resultado_validar; 
            parent::responder($resultado);
        }

        function validarSubAfiliadoSenorpago(){
            $resultado = $this->SenorpagoModel->validarSubAfiliadoSenorpago();
            parent::responder($resultado);
        }

        function editarSubAfiliadoSenorpago(){

            $email = $_REQUEST["email"];
            $contrasena = $_REQUEST["contrasena"];
            $nombre = $_REQUEST["nombre"];
            $primer_apellido = $_REQUEST["primer_apellido"];
            $segundo_apellido = $_REQUEST["segundo_apellido"];
            $calle = $_REQUEST["calle"];
            $colonia = $_REQUEST["colonia"];
            $ciudad = $_REQUEST["ciudad"];
            $codigo_postal = $_REQUEST["codigo_postal"];
            $cumpleanos = $_REQUEST["cumpleanos"];
            $tarjeta = $_REQUEST["tarjeta"];
            $ife_delantera = $_REQUEST["ife_delantera"];
            $ife_posterior = $_REQUEST["ife_posterior"];
            $comprobante_domicilio = $_REQUEST["comprobante_domicilio"];
            $contrato_pagina_1 = (isset($_REQUEST["contrato_pagina_1"])) ? $_REQUEST["contrato_pagina_1"] : null;
            $contrato_pagina_2 = (isset($_REQUEST["contrato_pagina_2"])) ? $_REQUEST["contrato_pagina_2"] : null;
            $contrato_pagina_3 = (isset($_REQUEST["contrato_pagina_3"])) ? $_REQUEST["contrato_pagina_3"] : null;
            $firma = (isset($_REQUEST["firma"])) ? $_REQUEST["firma"] : null;
            $compania = $_REQUEST["compania"];
            $ventas_mensuales = $_REQUEST["ventas_mensuales"];
            $ventas_promedio = $_REQUEST["ventas_promedio"];
            $tipo_compania = $_REQUEST["tipo_compania"];

            $resultado = $this->SenorpagoModel->editarSubAfiliadoSenorpago(  $email, $contrasena, $nombre, $primer_apellido, $segundo_apellido, $calle, $colonia, $ciudad, $codigo_postal, $cumpleanos, $tarjeta, $ife_delantera, $ife_posterior, $comprobante_domicilio, $contrato_pagina_1, $contrato_pagina_2, $contrato_pagina_3, $firma, $compania, $ventas_mensuales, $ventas_promedio, $tipo_compania);

            parent::responder($resultado);
        }

        function testSrPago(){
            
            $resultado = $this->SenorpagoModel->cargarImagenSubAfiliadoSenorPago("signature", "https://store.netwarmonitor.com/images/iconos/appministramax.png");
            
            echo json_encode($resultado);
            //$a = false;
            //echo $a ? 'true' : 'false';
            //echo $resultado["mensaje"];
            //echo $resultado["status"];
            //echo var_dump($resultado["status"]);
        }

    }

?>
