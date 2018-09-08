<?php

    //Cargar la clase de conexión padre para el modelo
    require_once("models/model_father.php");
    //Cargar los archivos necesarios

    class MrpDepartamentoModel extends Model
    {
        //Definir los atributos de la clase
        public $idDep = null;
        public $nombre = null;

        function __construct($id = null)
        {
            parent::__construct($id);
        }

        function __destruct()
        {

        }

        public static function areas($filtros)
        {
            $consulta = "SELECT DISTINCT m.idDep id, d.nombre AS area 
                        FROM com_mesas m 
                        INNER JOIN mrp_departamento d ON m.idDep = d.idDep 
                        WHERE m.status = 1 AND m.tipo_mesa IS NOT NULL 
                        ORDER BY area;";
            $consulta = DB::queryArray($consulta, array());
            return $consulta;
        }

    }

?>
