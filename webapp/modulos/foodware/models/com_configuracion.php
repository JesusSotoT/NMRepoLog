<?php

    //Cargar la clase de conexión padre para el modelo
    require_once("models/model_father.php");
    //Cargar los archivos necesarios

    class ComConfiguracionModel extends Model
    {
        //Definir los atributos de la clase
        public $id = null;
        public $propina = null;
        public $consumo = null;
        public $reservaciones = null;
        public $seguridad = null;
        public $tipo_operacion = null;
        public $pedir_pass = null;
        public $mostrar_dolares = null;
        public $mostrar_info_comanda = null;
        public $calculo_automatico = null;
        public $mostrar_sd = null;
        public $switch_propina = null;
        public $facturar_propina = null;
        public $idioma = null;
        public $mostrar_nombre = null;
        public $mostrar_domicilio = null;
        public $mostrar_tel = null;
        public $switch_info_ticket = null;
        public $mostrar_info_empresa = null;
        public $imprimir_pedido_general = null;
        public $mostrar_iva = null;
        public $mostrar_info_correo = null;
        public $mostrar_logo_correo = null;
        public $imagen_promo = null;
        public $imagen_felicitaciones = null;
        public $informacion_adicional = null;
        public $enviar_promociones = null;
        public $enviar_menu = null;
        public $enviar_felicitaciones = null;
        public $menu_digital = null;
        public $mostrar_logo_qr = null;
        public $mostrar_info_qr = null;
        public $tipo_vista_qr = null;
        public $mostrar_opciones_menu = null;
        public $imagen_fondo = null;

        function __construct($id = null)
        {
            parent::__construct($id);
        }

        function __destruct()
        {

        }

    }

?>
