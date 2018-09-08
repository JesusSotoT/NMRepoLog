<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_api_sqli_manual.php"); // funciones mySQLi

    class RegistroModel extends Connectionapi
    {

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

        public function obtenerRegistro($filtros){
            try{
                $select = "SELECT id FROM usuario_srpago WHERE ". $filtros .";";
                $resultSelect = $this->queryArray($select);
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        public function agregarRegistro($nombre, $email, $telefono){
            try{
                $verifica = $this->obtenerRegistro("email = '". $email ."' OR telefono = '". $telefono ."'");
                if($verifica["total"] > 0) throw new Exception("El teléfono o email ya han sido registrados", 601);

                $queryRegistro = "INSERT INTO usuario_srpago (nombre, email, telefono) VALUES ";
                $queryRegistro .= "(:nombre, :email, :telefono);";

                $parametros = array(
                                'nombre' => $nombre,
                                'email' => $email,
                                'telefono' => $telefono
                                 );

                $insertRegistroResult = $this->queryArray($queryRegistro, $parametros);
                if($insertRegistroResult["status"]){
                    require 'libraries/phpmailer/sendMail.php';

                    $cuerpo_html = "Un nuevo cliente se ha registrado en la aplicación móvil de Appministra Lite, por favor contactalo lo mas pronto posible:<br><br>
                                    <b>Nombre:</b> ". $nombre ."<br>
                                    <b>Email:</b> ". $email ."<br>
                                    <b>Teléfono:</b> ". $telefono;

                    $mail->IsHTML(true);
                    $mail->setFrom("mailer@netwarmonitor.com", "Appministra Lite");
                    $mail->Subject = "Creación de Instancia";
                    $mail->Body = $cuerpo_html;
                    $mail->AddAddress("jsonora@netwarmonitor.com");
                    $mail->AddCC("contacto@appministra.com");
                    $respuesta = $mail->Send();
                }
                return $insertRegistroResult;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => ($e->getCode() == 601) ? $e->getMessage() : "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

    }
?>
