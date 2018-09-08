<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class Formulario_dosModel extends Connection
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

        public function guardarFormularioDos($request, $consultor){
            try{

                $select = "SELECT * FROM inovekia_formulario_dos WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor;";
                $resultSelect = $this->queryArray($select, array("id_empresario" => $request['empresario'], "id_consultor" => $consultor));

                if($resultSelect["total"] == 0){
                    $insert = "INSERT INTO inovekia_formulario_dos (f2p1a, f2p2a, f2p2b, f2p3a, f2p4a, f2p4b, f2p5a, f2p5b, f2p6a, f2p6b, f2p6c, f2p6d, f2p7a, f2p8a, f2p9a, f2p10a, id_empresario, id_consultor) VALUES ";

                    $insert .= "(:f2p1a, :f2p2a, :f2p2b, :f2p3a, :f2p4a, :f2p4b, :f2p5a, :f2p5b, :f2p6a, :f2p6b, :f2p6c, :f2p6d, :f2p7a, :f2p8a, :f2p9a, :f2p10a, :id_empresario, :id_consultor);";

                        $archivo1 = "noArchivo";
                        $archivo2 = "noArchivo";
                        $archivo3 = "noArchivo";
                        $archivo4 = "noArchivo";
                        $archivo5 = "noArchivo";
                        $archivo6 = "noArchivo";
                        $archivo7 = "noArchivo";
                        $archivo8 = "noArchivo";

                        if(Input::tieneArchivo("f2p1a")){
                            $archivo1 = "f2p1a". date("YmdHis") .".". Input::extencion("f2p1a");
                            $directorio = "public/archivos/formularios/". $archivo1;
                                if (!move_uploaded_file($_FILES["f2p1a"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                         if(Input::tieneArchivo("f2p2a")){
                            $archivo2 = "f2p2a". date("YmdHis") .".". Input::extencion("f2p2a");
                            $directorio = "public/archivos/formularios/". $archivo2;
                                if (!move_uploaded_file($_FILES["f2p2a"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                         if(Input::tieneArchivo("f2p2b")){
                            $archivo3 = "f2p2b". date("YmdHis") .".". Input::extencion("f2p2b");
                            $directorio = "public/archivos/formularios/". $archivo3;
                                if (!move_uploaded_file($_FILES["f2p2b"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                         if(Input::tieneArchivo("f2p3a")){
                            $archivo4 = "f2p3a". date("YmdHis") .".". Input::extencion("f2p3a");
                            $directorio = "public/archivos/formularios/". $archivo4;
                                if (!move_uploaded_file($_FILES["f2p3a"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                         if(Input::tieneArchivo("f2p4a")){
                            $archivo5 = "f2p4a". date("YmdHis") .".". Input::extencion("f2p4a");
                            $directorio = "public/archivos/formularios/". $archivo5;
                                if (!move_uploaded_file($_FILES["f2p4a"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                         if(Input::tieneArchivo("f2p4b")){
                            $archivo6 = "f2p4b". date("YmdHis") .".". Input::extencion("f2p4b");
                            $directorio = "public/archivos/formularios/". $archivo6;
                                if (!move_uploaded_file($_FILES["f2p4b"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                         if(Input::tieneArchivo("f2p5a")){
                            $archivo7 = "f2p5a". date("YmdHis") .".". Input::extencion("f2p5a");
                            $directorio = "public/archivos/formularios/". $archivo7;
                                if (!move_uploaded_file($_FILES["f2p5a"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                         if(Input::tieneArchivo("f2p5b")){
                            $archivo8 = "f2p5b". date("YmdHis") .".". Input::extencion("f2p5b");
                            $directorio = "public/archivos/formularios/". $archivo8;
                                if (!move_uploaded_file($_FILES["f2p5b"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                        
                    $parametros = array(
                                        "f2p1a" => $archivo1,
                                        "f2p2a" => $archivo2,
                                        "f2p2b" => $archivo3,
                                        "f2p3a" => $archivo4,
                                        "f2p4a" => $archivo5,
                                        "f2p4b" => $archivo6,
                                        "f2p5a" => $archivo7,
                                        "f2p5b" => $archivo8,
                                        "f2p6a" => $request['f2p6a'],
                                        "f2p6b" => $request['f2p6b'],
                                        "f2p6c" => $request['f2p6c'],
                                        "f2p6d" => $request['f2p6d'],
                                        "f2p7a" => $request['f2p7a'],
                                        "f2p8a" => $request['f2p8a'],
                                        "f2p9a" => $request['f2p9a'],
                                        "f2p10a" => $request['f2p10a'],
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor
                                        ); 
                    $resultInsert = $this->queryArray($insert, $parametros);
                    
                    return $resultInsert;
                }else{
                    $update = "UPDATE inovekia_formulario_dos SET f2p1a = :f2p1a, f2p2a = :f2p2a, f2p2b = :f2p2b, f2p3a = :f2p3a, 
                                f2p4a = :f2p4a, f2p4b = :f2p4b, f2p5a = :f2p5a, f2p5b = :f2p5b, f2p6a = :f2p6a, f2p6b = :f2p6b, 
                                f2p6c = :f2p6c, f2p6d = :f2p6d, f2p7a = :f2p7a, f2p8a = :f2p8a, f2p9a = :f2p9a, f2p10a = :f2p10a, 
                                id_empresario = :id_empresario, id_consultor = :id_consultor WHERE id_empresario = :id_empresario 
                                AND id_consultor = :id_consultor;";

                        $archivo1 = "noArchivo";
                        $archivo2 = "noArchivo";
                        $archivo3 = "noArchivo";
                        $archivo4 = "noArchivo";
                        $archivo5 = "noArchivo";
                        $archivo6 = "noArchivo";
                        $archivo7 = "noArchivo";
                        $archivo8 = "noArchivo";

                    if(strpos($request['f2p1a'], ".jpg") !== false){
                        $archivo1 = $request['f2p1a'];
                    }else{
                        if(Input::tieneArchivo("f2p1a")){
                            $archivo1 = "f2p1a". date("YmdHis") .".". Input::extencion("f2p1a");
                            $directorio = "public/archivos/formularios/". $archivo1;
                            if (!move_uploaded_file($_FILES["f2p1a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if(strpos($request['f2p2a'], ".jpg") !== false){
                        $archivo2 = $request['f2p2a'];
                    }else{
                        if(Input::tieneArchivo("f2p2a")){
                            $archivo2 = "f2p2a". date("YmdHis") .".". Input::extencion("f2p2a");
                            $directorio = "public/archivos/formularios/". $archivo2;
                                if (!move_uploaded_file($_FILES["f2p2a"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                    }

                    if(strpos($request['f2p2b'], ".jpg") !== false){
                        $archivo3 = $request['f2p2b'];
                    }else{
                        if(Input::tieneArchivo("f2p2b")){
                            $archivo3 = "f2p2b". date("YmdHis") .".". Input::extencion("f2p2b");
                            $directorio = "public/archivos/formularios/". $archivo3;
                                if (!move_uploaded_file($_FILES["f2p2b"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                    }

                    if(strpos($request['f2p3a'], ".jpg") !== false){
                        $archivo4 = $request['f2p3a'];
                    }else{
                        if(Input::tieneArchivo("f2p3a")){
                            $archivo4 = "f2p3a". date("YmdHis") .".". Input::extencion("f2p3a");
                            $directorio = "public/archivos/formularios/". $archivo4;
                                if (!move_uploaded_file($_FILES["f2p3a"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                    }

                    if(strpos($request['f2p4a'], ".jpg") !== false){
                        $archivo5 = $request['f2p4a'];
                    }else{
                        if(Input::tieneArchivo("f2p4a")){
                            $archivo5 = "f2p4a". date("YmdHis") .".". Input::extencion("f2p4a");
                            $directorio = "public/archivos/formularios/". $archivo5;
                                if (!move_uploaded_file($_FILES["f2p4a"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                    }

                    if(strpos($request['f2p4b'], ".jpg") !== false){
                        $archivo6 = $request['f2p4b'];
                    }else{
                        if(Input::tieneArchivo("f2p4b")){
                            $archivo6 = "f2p4b". date("YmdHis") .".". Input::extencion("f2p4b");
                            $directorio = "public/archivos/formularios/". $archivo6;
                                if (!move_uploaded_file($_FILES["f2p4b"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                    }

                    if(strpos($request['f2p5a'], ".jpg") !== false){
                        $archivo7 = $request['f2p5a'];
                    }else{
                        if(Input::tieneArchivo("f2p5a")){
                            $archivo7 = "f2p5a". date("YmdHis") .".". Input::extencion("f2p5a");
                            $directorio = "public/archivos/formularios/". $archivo7;
                                if (!move_uploaded_file($_FILES["f2p5a"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                    }
                         
                    if(strpos($request['f2p5b'], ".jpg") !== false){
                        $archivo8 = $request['f2p5b'];
                    }else{
                        if(Input::tieneArchivo("f2p5b")){
                            $archivo8 = "f2p5b". date("YmdHis") .".". Input::extencion("f2p5b");
                            $directorio = "public/archivos/formularios/". $archivo8;
                                if (!move_uploaded_file($_FILES["f2p5b"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                        }
                    }
                         
                        
                    $parametros = array(
                                        "f2p1a" => $archivo1,
                                        "f2p2a" => $archivo2,
                                        "f2p2b" => $archivo3,
                                        "f2p3a" => $archivo4,
                                        "f2p4a" => $archivo5,
                                        "f2p4b" => $archivo6,
                                        "f2p5a" => $archivo7,
                                        "f2p5b" => $archivo8,
                                        "f2p6a" => $request['f2p6a'],
                                        "f2p6b" => $request['f2p6b'],
                                        "f2p6c" => $request['f2p6c'],
                                        "f2p6d" => $request['f2p6d'],
                                        "f2p7a" => $request['f2p7a'],
                                        "f2p8a" => $request['f2p8a'],
                                        "f2p9a" => $request['f2p9a'],
                                        "f2p10a" => $request['f2p10a'],
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor
                                        ); 
                    $resultInsert = $this->queryArray($update, $parametros);
                    
                    return $resultInsert;
                }

            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

         public function obtenerFormularioDos(){
            try{
                $select = "SELECT * FROM inovekia_formulario_dos;";
                $resultSelect = $this->queryArray($select, array());
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

    }

?>

