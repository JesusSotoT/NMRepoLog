<?php

	//Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    /**
    * 
    */
    class Formulario_tresModel extends Connection
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

        public function guardarFormularioTres($request, $consultor){
            try{
                $select = "SELECT * FROM inovekia_formulario_tres WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor;";
                $resultSelect = $this->queryArray($select, array("id_empresario" => $request['empresario'], "id_consultor" => $consultor));

                if($resultSelect["total"] == 0){
                    $insert = "INSERT INTO inovekia_formulario_tres (f3p1a, f3p2a, f3p2b, f3p3a, f3p4a, f3p4b, f3p5a, f3p5b, f3p6a, f3p6b, f3p6c, f3p6d, f3p7a, f3p8a, f3p9a, f3p10a, f3p11a, id_empresario, id_consultor) VALUES ";

                    $insert .= "(:f3p1a, :f3p2a, :f3p2b, :f3p3a, :f3p4a, :f3p4b, :f3p5a, :f3p5b, :f3p6a, :f3p6b, :f3p6c, :f3p6d, :f3p7a, :f3p8a, :f3p9a, :f3p10a, :f3p11a, :id_empresario, :id_consultor);";

                    $archivo1 = "noArchivo";
                    $archivo2 = "noArchivo";
                    $archivo3 = "noArchivo";
                    $archivo4 = "noArchivo";
                    $archivo5 = "noArchivo";
                    $archivo6 = "noArchivo";
                    $archivo7 = "noArchivo";
                    $archivo8 = "noArchivo";
                    $archivo9 = "noArchivo";

                    if(Input::tieneArchivo("f3p1a")){
                        $archivo1 = "f3p1a". date("YmdHis") .".". Input::extencion("f3p1a");
                        $directorio = "public/archivos/formularios/". $archivo1;
                            if (!move_uploaded_file($_FILES["f3p1a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                     if(Input::tieneArchivo("f3p2a")){
                        $archivo2 = "f3p2a". date("YmdHis") .".". Input::extencion("f3p2a");
                        $directorio = "public/archivos/formularios/". $archivo2;
                            if (!move_uploaded_file($_FILES["f3p2a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                     if(Input::tieneArchivo("f3p2b")){
                        $archivo3 = "f3p2b". date("YmdHis") .".". Input::extencion("f3p2b");
                        $directorio = "public/archivos/formularios/". $archivo3;
                            if (!move_uploaded_file($_FILES["f3p2b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                     if(Input::tieneArchivo("f3p3a")){
                        $archivo4 = "f3p3a". date("YmdHis") .".". Input::extencion("f3p3a");
                        $directorio = "public/archivos/formularios/". $archivo4;
                            if (!move_uploaded_file($_FILES["f3p3a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                     if(Input::tieneArchivo("f3p4a")){
                        $archivo5 = "f3p4a". date("YmdHis") .".". Input::extencion("f3p4a");
                        $directorio = "public/archivos/formularios/". $archivo5;
                            if (!move_uploaded_file($_FILES["f3p4a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                     if(Input::tieneArchivo("f3p4b")){
                        $archivo6 = "f3p4b". date("YmdHis") .".". Input::extencion("f3p4b");
                        $directorio = "public/archivos/formularios/". $archivo6;
                            if (!move_uploaded_file($_FILES["f3p4b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                     if(Input::tieneArchivo("f3p5a")){
                        $archivo7 = "f3p5a". date("YmdHis") .".". Input::extencion("f3p5a");
                        $directorio = "public/archivos/formularios/". $archivo7;
                            if (!move_uploaded_file($_FILES["f3p5a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                     if(Input::tieneArchivo("f3p5b")){
                        $archivo8 = "f3p5b". date("YmdHis") .".". Input::extencion("f3p5b");
                        $directorio = "public/archivos/formularios/". $archivo8;
                            if (!move_uploaded_file($_FILES["f3p5b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                     if(Input::tieneArchivo("f3p11a")){
                        $archivo9 = "f3p11a". date("YmdHis") .".". Input::extencion("f3p11a");
                        $directorio = "public/archivos/formularios/". $archivo9;
                            if (!move_uploaded_file($_FILES["f3p11a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                     
                    
                    $parametros = array(
                                        "f3p1a" => $archivo1,
                                        "f3p2a" => $archivo2,
                                        "f3p2b" => $archivo3,
                                        "f3p3a" => $archivo4,
                                        "f3p4a" => $archivo5,
                                        "f3p4b" => $archivo6,
                                        "f3p5a" => $archivo7,
                                        "f3p5b" => $archivo8,
                                        "f3p6a" => $request['f3p6a'],
                                        "f3p6b" => $request['f3p6b'],
                                        "f3p6c" => $request['f3p6c'],
                                        "f3p6d" => $request['f3p6d'],
                                        "f3p7a" => $request['f3p7a'],
                                        "f3p8a" => $request['f3p8a'],
                                        "f3p9a" => $request['f3p9a'],
                                        "f3p10a" => $request['f3p10a'],
                                        "f3p11a" => $archivo9,
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor
                                        ); 
                    
                    $resultInsert = $this->queryArray($insert, $parametros);
                    
                    return $resultInsert;
                }else{
                    $update = "UPDATE inovekia_formulario_tres SET f3p1a = :f3p1a, f3p2a = :f3p2a, f3p2b = :f3p2b, 
                                f3p3a = :f3p3a, f3p4a = :f3p4a, f3p4b = :f3p4b, f3p5a = :f3p5a, f3p5b = :f3p5b, 
                                f3p6a = :f3p6a, f3p6b = :f3p6b, f3p6c = :f3p6c, f3p6d = :f3p6d, f3p7a = :f3p7a, 
                                f3p8a = :f3p8a, f3p9a = :f3p9a, f3p10a = :f3p10a, f3p11a = :f3p11a, 
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
                    $archivo9 = "noArchivo";

                    if(strpos($request['f3p1a'], ".jpg") !== false){
                        $archivo1 = $request['f3p1a'];
                    }else{
                        if(Input::tieneArchivo("f3p1a")){
                        $archivo1 = "f3p1a". date("YmdHis") .".". Input::extencion("f3p1a");
                        $directorio = "public/archivos/formularios/". $archivo1;
                            if (!move_uploaded_file($_FILES["f3p1a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if(strpos($request['f3p2a'], ".jpg") !== false){
                        $archivo2 = $request['f3p2a'];
                    }else{
                        if(Input::tieneArchivo("f3p2a")){
                        $archivo2 = "f3p2a". date("YmdHis") .".". Input::extencion("f3p2a");
                        $directorio = "public/archivos/formularios/". $archivo2;
                            if (!move_uploaded_file($_FILES["f3p2a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                    }

                    if(strpos($request['f3p2b'], ".jpg") !== false){
                        $archivo3 = $request['f3p2b'];
                    }else{
                        if(Input::tieneArchivo("f3p2b")){
                        $archivo3 = "f3p2b". date("YmdHis") .".". Input::extencion("f3p2b");
                        $directorio = "public/archivos/formularios/". $archivo3;
                            if (!move_uploaded_file($_FILES["f3p2b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                    }

                    if(strpos($request['f3p3a'], ".jpg") !== false){
                        $archivo4 = $request['f3p3a'];
                    }else{
                        if(Input::tieneArchivo("f3p3a")){
                        $archivo4 = "f3p3a". date("YmdHis") .".". Input::extencion("f3p3a");
                        $directorio = "public/archivos/formularios/". $archivo4;
                            if (!move_uploaded_file($_FILES["f3p3a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                    }
                    }

                    if(strpos($request['f3p4a'], ".jpg") !== false){
                        $archivo5 = $request['f3p4a'];
                    }else{
                        if(Input::tieneArchivo("f3p4a")){
                        $archivo5 = "f3p4a". date("YmdHis") .".". Input::extencion("f3p4a");
                        $directorio = "public/archivos/formularios/". $archivo5;
                            if (!move_uploaded_file($_FILES["f3p4a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if(strpos($request['f3p4b'], ".jpg") !== false){
                        $archivo6 = $request['f3p4b'];
                    }else{
                        if(Input::tieneArchivo("f3p4b")){
                        $archivo6 = "f3p4b". date("YmdHis") .".". Input::extencion("f3p4b");
                        $directorio = "public/archivos/formularios/". $archivo6;
                            if (!move_uploaded_file($_FILES["f3p4b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                     
                    if(strpos($request['f3p5a'], ".jpg") !== false){
                        $archivo7 = $request['f3p5a'];
                    }else{
                        if(Input::tieneArchivo("f3p5a")){
                        $archivo7 = "f3p5a". date("YmdHis") .".". Input::extencion("f3p5a");
                        $directorio = "public/archivos/formularios/". $archivo7;
                            if (!move_uploaded_file($_FILES["f3p5a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                     

                    if(strpos($request['f3p5b'], ".jpg") !== false){
                        $archivo8 = $request['f3p5b'];
                    }else{
                        if(Input::tieneArchivo("f3p5b")){
                        $archivo8 = "f3p5b". date("YmdHis") .".". Input::extencion("f3p5b");
                        $directorio = "public/archivos/formularios/". $archivo8;
                            if (!move_uploaded_file($_FILES["f3p5b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                     
                    if(strpos($request['f3p11a'], ".jpg") !== false){
                        $archivo9 = $request['f3p11a'];
                    }else{
                        if(Input::tieneArchivo("f3p11a")){
                            $archivo9 = "f3p11a". date("YmdHis") .".". Input::extencion("f3p11a");
                            $directorio = "public/archivos/formularios/". $archivo9;
                                if (!move_uploaded_file($_FILES["f3p11a"]["tmp_name"], $directorio)){
                                    throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                                }
                            }
                    }
                    
                    $parametros = array(
                                        "f3p1a" => $archivo1,
                                        "f3p2a" => $archivo2,
                                        "f3p2b" => $archivo3,
                                        "f3p3a" => $archivo4,
                                        "f3p4a" => $archivo5,
                                        "f3p4b" => $archivo6,
                                        "f3p5a" => $archivo7,
                                        "f3p5b" => $archivo8,
                                        "f3p6a" => $request['f3p6a'],
                                        "f3p6b" => $request['f3p6b'],
                                        "f3p6c" => $request['f3p6c'],
                                        "f3p6d" => $request['f3p6d'],
                                        "f3p7a" => $request['f3p7a'],
                                        "f3p8a" => $request['f3p8a'],
                                        "f3p9a" => $request['f3p9a'],
                                        "f3p10a" => $request['f3p10a'],
                                        "f3p11a" => $archivo9,
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

        public function obtenerFormularioTres(){
            try{
                $select = "SELECT * FROM inovekia_formulario_tres;";
                $resultSelect = $this->queryArray($select, array());
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }
    }

?>