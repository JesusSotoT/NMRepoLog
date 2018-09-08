<?php
    //ini_set("display_errors", 1); error_reporting(E_ALL);
    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class EmpleadoModel extends Connection
    {
        private $Seguridad;

        function __construct($seguridad)
        {
            $this->Seguridad = $seguridad;
        }

        public function object_to_array($data) {
            if (is_array($data) || is_object($data)) {
                $result = array();
                foreach ($data as $key => $value) {
                    $result[$key] = $this->object_to_array($value);
                }
                return $result;
            }
            return $data;
        }

       
        public function lista(){

            try{
                $empleados = "SELECT ne.codigo, ne.apellidoPaterno, ne.apellidoMaterno, ne.nombreEmpleado, ne.idEmpleado FROM nomi_empleados as ne WHERE not exists (SELECT eh.idEmpleado FROM nomi_empleadoReHuella  as eh WHERE ne.idEmpleado=eh.idEmpleado) order by nombreEmpleado asc;";
                $empleados = $this->queryArray($empleados);
                if(!$empleados["status"]) throw new Exception($empleados["msg"], 1);
                $json = array("status" => true, "rows" => $empleados["rows"]);
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function checarHuella($empleado){
            date_default_timezone_set("Mexico/General");
            $dias = array("Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab");
            $mensaje = "";
            $insertar = false;
            $checada_hora = date("H:i:s");
            $checada_fecha = date("Y-m-d");
            $idEmple = $empleado["idEmpleado"];
            $idnomp = $empleado["idnomp"];
            try{
                $ultima_huella = "SELECT idregistro, iniciocomida, fincomida, horaentrada, horasalida FROM nomi_registro_entradas WHERE idEmpleado = $idEmple AND fecha = '$checada_fecha' ORDER BY idRegistro DESC LIMIT 1;";
                $ultima_huella = $this->queryArray($ultima_huella);
                if(!$ultima_huella["status"]) throw new Exception($ultima_huella["msg"], 1);
                if(count($ultima_huella["rows"]) > 0){
                    $ultima_huella = $ultima_huella["rows"][0];
                    $tolerancia = date("H:i:s", strtotime("+10 minutes", strtotime($ultima_huella["horaentrada"])));
                    if(strtotime($checada_hora) < strtotime($tolerancia)){
                        throw new Exception("Ya marco entrada a las ". $ultima_huella['horaentrada'], 1);
                    } else if(date("w") == 5) {
                        $update = "UPDATE nomi_registro_entradas SET horasalida = '$checada_hora' WHERE idEmpleado = $idEmple AND fecha = '$checada_fecha';";
                        $update = $this->queryArray($update);
                        if(!$update["status"]) throw new Exception($update["msg"], 1);
                    } else {
                        if(strtotime($checada_hora) >= strtotime("12:00:00") && strtotime($checada_hora) <= strtotime("17:00:00") && $ultima_huella["iniciocomida"] == null){
                            $update = "UPDATE nomi_registro_entradas SET iniciocomida = '$checada_hora' WHERE idEmpleado = $idEmple AND fecha = '$checada_fecha';";
                            $update = $this->queryArray($update);
                            if(!$update["status"]) throw new Exception($update["msg"], 1);
                        } else if(strtotime($checada_hora) >= strtotime("12:00:00") && strtotime($checada_hora) <= strtotime("17:00:00") && $ultima_huella["iniciocomida"] != null){
                            $tolerancia_comida = date("H:i:s", strtotime("+10 minutes", strtotime($ultima_huella["iniciocomida"])));
                            if(strtotime($checada_hora) < strtotime($tolerancia_comida)) throw new Exception("Ya marco inicio de comida a las ". $ultima_huella['iniciocomida'], 1);
                            $update = "UPDATE nomi_registro_entradas SET fincomida = '$checada_hora' WHERE idEmpleado = $idEmple AND fecha = '$checada_fecha';";
                            $update = $this->queryArray($update);
                            if(!$update["status"]) throw new Exception($update["msg"], 1);
                        } else {
                            if(strtotime($checada_hora) >= strtotime("17:00:00") && strtotime($checada_hora) <= strtotime("17:10:00") && $ultima_huella["fincomida"] != null) throw new Exception("Ya marco fin de comida a las ". $ultima_huella['fincomida'], 1);
                            if($ultima_huella["horasalida"] != null) throw new Exception("Ya marco salida a las ". $ultima_huella['horasalida'], 1);
                            $update = "UPDATE nomi_registro_entradas SET horasalida = '$checada_hora' WHERE idEmpleado = $idEmple AND fecha = '$checada_fecha';";
                            $update = $this->queryArray($update);
                            if(!$update["status"]) throw new Exception($update["msg"], 1);
                        }
                    }
                } else {
                    $insert = "INSERT INTO nomi_registro_entradas VALUES(null, '$checada_hora', null, null, null, '$idEmple', '$checada_fecha', '". $dias[date("w")] ."', '$idnomp');";
                    $insert = $this->queryArray($insert);
                    if(!$insert["status"]) throw new Exception($insert["msg"], 1);
                }
                $json = array("status" => true);
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "[". $e->getMessage() ."]");
            }
            return $json;
        }

        public function identificarHuella(){
          try{             

                $huella = "SELECT nr.idEmpleado,nr.huella,nh.nombreEmpleado,np.idnomp,
                                nh.apellidoPaterno,nh.apellidoMaterno,nh.imagen,np.autorizado
                                FROM nomi_empleadoReHuella as nr 
                                LEFT join nomi_empleados as nh 
                                on nr.idEmpleado = nh.idEmpleado 
                                left join  nomi_nominasperiodo as np 
                                on np.idtipop=nh.idtipop where np.autorizado=0 GROUP BY
                                nr.idEmpleado;";

                $huella = $this->queryArray($huella);
                if(!$huella["status"]) throw new Exception($huella["msg"], 1);
                $json = array("status" => true, "rows" => $huella["rows"]);
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function guardarHuella($huella){

            try{
                if(Input::tieneArchivo("datosHuella")){
                    $archivo = date("YmdHis") .".". Input::extencion("datosHuella");
                    $datosHuella = "../webapp/modulos/checador/imagenes/huellas/". $archivo;
                    if (Input::tieneArchivo("datosHuella") && !move_uploaded_file($_FILES["datosHuella"]["tmp_name"], $datosHuella)){
                        throw new Exception("No fue posible subir el datosHuella, intentalo nuevamente", 1);
                    }

                    $guardar = "INSERT INTO nomi_empleadoReHuella VALUES(:id, :huella, :contenido, :dato);";
                    $guardar = $this->queryArray($guardar, array("id" => null, "huella" => $huella["idEmpleado"], "contenido" => $archivo, "dato" => $huella["dato"]));
                    
                    if(!$guardar["status"]) throw new Exception($guardar["msg"], 1);
                    $json = array("status" => true, "rows" => $guardar["rows"]);

                } else{
                    throw new Exception("No se selecciono ninguna imagen", 4);
                }

            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido guardar la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

    }
?>
