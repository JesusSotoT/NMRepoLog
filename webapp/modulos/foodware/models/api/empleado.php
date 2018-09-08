<?php

    //Cargar la clase de conexión padre para el modelo
    require_once("models/model_father.php");
    //Cargar los archivos necesarios

    class EmpleadoModel extends Model
    {
        //Definir los atributos de la clase
        public $idempleado = null;
        public $codigo = null;
        public $nombre = null;
        public $apellido1 = null;
        public $apellido2 = null;
        public $idorganizacion = null;
        public $visible = null;
        public $administrador = null;
        public $turno = null;

        function __construct($id = null)
        {
            parent::__construct($id);
        }

        function __destruct()
        {

        }
        
        public static function index()
        {
            return array("status" => true, "registros" => array());
        }

        public static function selectorVisual($filtros)
        {
            // Si viene el id del empleado Filtra por empleado
            $condicion = (!empty($filtros['id'])) ? ' AND idempleado = \'' . $filtros['id'] . '\'' : '';
            // Elimina los administradores del listado
            $condicion .= (!empty($filtros['vista_empleados'])) ? ' AND idperfil != 2' : '';
            // Filtra por el tipo de usuario si existe
            $condicion .= (!empty($filtros['ocultar_empleados'])) ? ' AND u.mostrar_comanda = 1' : '';
            // Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
            $orden = (!empty($filtros['orden'])) ? ' ' . $filtros['orden'] : ' nombreusuario ASC';
            
            $consulta = "SELECT idempleado AS id, nombreusuario AS usuario, permisos, asignacion, u.mostrar_comanda
                    FROM administracion_usuarios u
                    LEFT JOIN com_meseros m ON m.id_mesero = u.idempleado
                    WHERE idempleado != 'null'
                    AND u.idSuc =  " . $filtros["sucursal"] . " " . $condicion . "
                    ORDER BY " . $orden;

            $resultado = DB::queryArray($consulta, array());

            return $resultado;
        }

    }

?>
