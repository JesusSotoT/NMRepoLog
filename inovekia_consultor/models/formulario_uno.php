<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones 
    require_once("libraries/input.php");

    class Formulario_unoModel extends Connection
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

        public function guardarFormularioUno($request, $consultor){
            try{
                $select = "SELECT * FROM inovekia_formulario_uno WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor;";
                $resultSelect = $this->queryArray($select, array("id_empresario" => $request['empresario'], "id_consultor" => $consultor));
                
                if($resultSelect["total"] == 0){
                    $insert = "INSERT INTO inovekia_formulario_uno (f1p1a, f1p2a, f1p3a, f1p4a, f1p5a, f1p5b, f1p5c, f1p6a, f1p7a, f1p8a, f1p9a, f1p10a, f1p11a, f1p12a, f1p13a, f1p14a, f1p15a, f1p16a, f1p17a, f1p18a, f1p19a, f1p20a, f1p21a, f1p22a, f1p23a, f1p24a, f1p24b, f1p25a, f1p26a, f1p26b, f1p27a, f1p28a, f1p28b, f1p29a, f1p29b, f1p30a, f1p30b, f1p30c, f1p30d, f1p31a, f1p32a, f1p33a, f1p34a, f1p35a, f1p36a, id_empresario, id_consultor) VALUES ";

                    $insert .= "(:f1p1a, :f1p2a, :f1p3a, :f1p4a, :f1p5a, :f1p5b, :f1p5c, :f1p6a, :f1p7a, :f1p8a, :f1p9a, :f1p10a, :f1p11a, :f1p12a, :f1p13a, :f1p14a, :f1p15a, :f1p16a, :f1p17a, :f1p18a, :f1p19a, :f1p20a, :f1p21a, :f1p22a, :f1p23a, :f1p24a, :f1p24b, :f1p25a, :f1p26a, :f1p26b, :f1p27a, :f1p28a, :f1p28b, :f1p29a, :f1p29b, :f1p30a, :f1p30b, :f1p30c, :f1p30d, :f1p31a, :f1p32a, :f1p33a, :f1p34a, :f1p35a, :f1p36a, :id_empresario, :id_consultor);";
                    
                    $archivo1 = "noArchivo";
                    $archivo2 = "noArchivo";
                    $archivo3 = "noArchivo";
                    $archivo4 = "noArchivo";
                    $archivo5 = "noArchivo";
                    $archivo6 = "noArchivo";
                    $archivo7 = "noArchivo";
                    $archivo8 = "noArchivo";
                    $archivo9 = "noArchivo";
                    $archivo10 = "noArchivo";
                    $archivo11 = "noArchivo";
                    $archivo12 = "noArchivo";
                    $archivo13 = "noArchivo";
                    $archivo14 = "noArchivo";

                    if((strpos($request['f1p5a'], ".jpg") !== false) || ($request['f1p5a'] == "noAplica")){
                        $archivo1 = $request['f1p5a'];
                    }else{
                        if(Input::tieneArchivo("f1p5a")){
                            $archivo1 = "f1p5a". date("YmdHis") .".". Input::extencion("f1p5a");
                            $directorio = "public/archivos/formularios/". $archivo1;
                            if (!move_uploaded_file($_FILES["f1p5a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                    
                    if((strpos($request['f1p5b'], ".jpg") !== false) || ($request['f1p5b'] == "noAplica")){
                        $archivo2 = $request['f1p5b'];
                    }else{
                        if(Input::tieneArchivo("f1p5b")){
                            $archivo2 = "f1p5b". date("YmdHis") .".". Input::extencion("f1p5b");
                            $directorio = "public/archivos/formularios/". $archivo2;
                            if (!move_uploaded_file($_FILES["f1p5b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
//ASD
                    if((strpos($request['f1p5c'], ".jpg") !== false) || ($request['f1p5c'] == "noAplica")){
                        $archivo3 = $request['f1p5c'];
                    }else{
                        if(Input::tieneArchivo("f1p5c")){
                            $archivo3 = "f1p5c". date("YmdHis") .".". Input::extencion("f1p5c");
                            $directorio = "public/archivos/formularios/". $archivo3;
                            if (!move_uploaded_file($_FILES["f1p5c"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p23a'], ".jpg") !== false) || ($request['f1p23a'] == "noAplica")){
                        $archivo4 = $request['f1p23a'];
                    }else{
                        if(Input::tieneArchivo("f1p23a")){
                            $archivo4 = "f1p23a". date("YmdHis") .".". Input::extencion("f1p23a");
                            $directorio = "public/archivos/formularios/". $archivo4;
                            if (!move_uploaded_file($_FILES["f1p23a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p24a'], ".jpg") !== false) || ($request['f1p24a'] == "noAplica")){
                        $archivo5 = $request['f1p24a'];
                    }else{
                        if(Input::tieneArchivo("f1p24a")){
                            $archivo5 = "f1p24a". date("YmdHis") .".". Input::extencion("f1p24a");
                            $directorio = "public/archivos/formularios/". $archivo5;
                            if (!move_uploaded_file($_FILES["f1p24a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p24b'], ".jpg") !== false) || ($request['f1p24b'] == "noAplica")){
                        $archivo6 = $request['f1p24b'];
                    }else{
                        if(Input::tieneArchivo("f1p24b")){
                            $archivo6 = "f1p24b". date("YmdHis") .".". Input::extencion("f1p24b");
                            $directorio = "public/archivos/formularios/". $archivo6;
                            if (!move_uploaded_file($_FILES["f1p24b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p25a'], ".jpg") !== false) || ($request['f1p25a'] == "noAplica")){
                        $archivo7 = $request['f1p25a'];
                    }else{
                        if(Input::tieneArchivo("f1p25a")){
                            $archivo7 = "f1p25a". date("YmdHis") .".". Input::extencion("f1p25a");
                            $directorio = "public/archivos/formularios/". $archivo7;
                            if (!move_uploaded_file($_FILES["f1p25a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p26a'], ".jpg") !== false) || ($request['f1p26a'] == "noAplica")){
                        $archivo8 = $request['f1p26a'];
                    }else{
                        if(Input::tieneArchivo("f1p26a")){
                            $archivo8 = "f1p26a". date("YmdHis") .".". Input::extencion("f1p26a");
                            $directorio = "public/archivos/formularios/". $archivo8;
                            if (!move_uploaded_file($_FILES["f1p26a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p26b'], ".jpg") !== false) || ($request['f1p26b'] == "noAplica")){
                        $archivo9 = $request['f1p26b'];
                    }else{
                        if(Input::tieneArchivo("f1p26b")){
                            $archivo9 = "f1p26b". date("YmdHis") .".". Input::extencion("f1p26b");
                            $directorio = "public/archivos/formularios/". $archivo9;
                            if (!move_uploaded_file($_FILES["f1p26b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                     
                    if((strpos($request['f1p27a'], ".jpg") !== false) || ($request['f1p27a'] == "noAplica")){
                        $archivo10 = $request['f1p27a'];
                    }else{
                        if(Input::tieneArchivo("f1p27a")){
                            $archivo10 = "f1p27a". date("YmdHis") .".". Input::extencion("f1p27a");
                            $directorio = "public/archivos/formularios/". $archivo10;
                            if (!move_uploaded_file($_FILES["f1p27a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                     
                    if((strpos($request['f1p28a'], ".jpg") !== false) || ($request['f1p28a'] == "noAplica")){
                        $archivo11 = $request['f1p28a'];
                    }else{
                        if(Input::tieneArchivo("f1p28a")){
                            $archivo11 = "f1p28a". date("YmdHis") .".". Input::extencion("f1p28a");
                            $directorio = "public/archivos/formularios/". $archivo11;
                            if (!move_uploaded_file($_FILES["f1p28a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p28b'], ".jpg") !== false) || ($request['f1p28b'] == "noAplica")){
                        $archivo12 = $request['f1p28b'];
                    }else{
                        if(Input::tieneArchivo("f1p28b")){
                            $archivo12 = "f1p28b". date("YmdHis") .".". Input::extencion("f1p28b");
                            $directorio = "public/archivos/formularios/". $archivo12;
                            if (!move_uploaded_file($_FILES["f1p28b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p29a'], ".jpg") !== false) || ($request['f1p29a'] == "noAplica")){
                        $archivo13 = $request['f1p29a'];
                    }else{
                        if(Input::tieneArchivo("f1p29a")){
                            $archivo13 = "f1p29a". date("YmdHis") .".". Input::extencion("f1p29a");
                            $directorio = "public/archivos/formularios/". $archivo14;
                            if (!move_uploaded_file($_FILES["f1p29a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                     
                    if((strpos($request['f1p29b'], ".jpg") !== false) || ($request['f1p29b'] == "noAplica")){
                        $archivo14 = $request['f1p29b'];
                    }else{
                        if(Input::tieneArchivo("f1p29b")){
                            $archivo14 = "f1p29b". date("YmdHis") .".". Input::extencion("f1p29b");
                            $directorio = "public/archivos/formularios/". $archivo14;
                            if (!move_uploaded_file($_FILES["f1p29b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    
                    $parametros = array(
                                        "f1p1a" => $request['f1p1a'],
                                        "f1p2a" => $request['f1p2a'],
                                        "f1p3a" => $request['f1p3a'],
                                        "f1p4a" => $request['f1p4a'],
                                        "f1p5a" => $archivo1,
                                        "f1p5b" => $archivo2,
                                        "f1p5c" => $archivo3,
                                        "f1p6a" => $request['f1p6a'],
                                        "f1p7a" => $request['f1p7a'],
                                        "f1p8a" => $request['f1p8a'],
                                        "f1p9a" => $request['f1p9a'],
                                        "f1p10a" => $request['f1p10a'],
                                        "f1p11a" => $request['f1p11a'],
                                        "f1p12a" => $request['f1p12a'],
                                        "f1p13a" => $request['f1p13a'],
                                        "f1p14a" => $request['f1p14a'],
                                        "f1p15a" => $request['f1p15a'],
                                        "f1p16a" => $request['f1p16a'],
                                        "f1p17a" => $request['f1p17a'],
                                        "f1p18a" => $request['f1p18a'],
                                        "f1p19a" => $request['f1p19a'],
                                        "f1p20a" => $request['f1p20a'],
                                        "f1p21a" => $request['f1p21a'],
                                        "f1p22a" => $request['f1p22a'],
                                        "f1p23a" => $archivo4,
                                        "f1p24a" => $archivo5,
                                        "f1p24b" => $archivo6,
                                        "f1p25a" => $archivo7,
                                        "f1p26a" => $archivo8,
                                        "f1p26b" => $archivo9,
                                        "f1p27a" => $archivo10,
                                        "f1p28a" => $archivo11,
                                        "f1p28b" => $archivo12,
                                        "f1p29a" => $archivo13,
                                        "f1p29b" => $archivo14,
                                        "f1p30a" => $request['f1p30a'],
                                        "f1p30b" => $request['f1p30b'],
                                        "f1p30c" => $request['f1p30c'],
                                        "f1p30d" => $request['f1p30d'],
                                        "f1p31a" => $request['f1p31a'],
                                        "f1p32a" => $request['f1p32a'],
                                        "f1p33a" => $request['f1p33a'],
                                        "f1p34a" => $request['f1p34a'],
                                        "f1p35a" => $request['f1p35a'],
                                        "f1p36a" => $request['f1p36a'], 
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor
                                        ); 

                    $resultInsert = $this->queryArray($insert, $parametros);

                    return $resultInsert;
                }else{
                    $update = "UPDATE inovekia_formulario_uno SET f1p1a = :f1p1a, f1p2a = :f1p2a, f1p3a = :f1p3a, f1p4a = :f1p4a, 
                                f1p5a = :f1p5a, f1p5b = :f1p5b, f1p5c = :f1p5c, f1p6a = :f1p6a, f1p7a = :f1p7a, f1p8a = :f1p8a, 
                                f1p9a = :f1p9a, f1p10a = :f1p10a, f1p11a = :f1p11a, f1p12a = :f1p12a, f1p13a = :f1p13a, f1p14a = :f1p14a, 
                                f1p15a = :f1p15a, f1p16a = :f1p16a, f1p17a = :f1p17a, f1p18a = :f1p18a, f1p19a = :f1p19a, f1p20a = :f1p20a, 
                                f1p21a = :f1p21a, f1p22a = :f1p22a, f1p23a = :f1p23a, f1p24a = :f1p24a, f1p24b = :f1p24b, f1p25a = :f1p25a, 
                                f1p26a = :f1p26a, f1p26b = :f1p26b, f1p27a = :f1p27a, f1p28a = :f1p28a, f1p28b = :f1p28b, f1p29a = :f1p29a, 
                                f1p29b = :f1p29b, f1p30a = :f1p30a, f1p30b = :f1p30b, f1p30c = :f1p30c, f1p30d = :f1p30d, f1p31a = :f1p31a, 
                                f1p32a = :f1p32a, f1p33a = :f1p33a, f1p34a = :f1p34a, f1p35a = :f1p35a, f1p36a = :f1p36a, 
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
                    $archivo10 = "noArchivo";
                    $archivo11 = "noArchivo";
                    $archivo12 = "noArchivo";
                    $archivo13 = "noArchivo";
                    $archivo14 = "noArchivo";

                    
                    if((strpos($request['f1p5a'], ".jpg") !== false) || ($request['f1p5a'] == "noAplica")){
                        $archivo1 = $request['f1p5a'];
                    }else{
                        if(Input::tieneArchivo("f1p5a")){
                            $archivo1 = "f1p5a". date("YmdHis") .".". Input::extencion("f1p5a");
                            $directorio = "public/archivos/formularios/". $archivo1;
                            if (!move_uploaded_file($_FILES["f1p5a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                    
                    if((strpos($request['f1p5b'], ".jpg") !== false) || ($request['f1p5b'] == "noAplica")){
                        $archivo2 = $request['f1p5b'];
                    }else{
                        if(Input::tieneArchivo("f1p5b")){
                            $archivo2 = "f1p5b". date("YmdHis") .".". Input::extencion("f1p5b");
                            $directorio = "public/archivos/formularios/". $archivo2;
                            if (!move_uploaded_file($_FILES["f1p5b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
//ASD
                    if((strpos($request['f1p5c'], ".jpg") !== false) || ($request['f1p5c'] == "noAplica")){
                        $archivo3 = $request['f1p5c'];
                    }else{
                        if(Input::tieneArchivo("f1p5c")){
                            $archivo3 = "f1p5c". date("YmdHis") .".". Input::extencion("f1p5c");
                            $directorio = "public/archivos/formularios/". $archivo3;
                            if (!move_uploaded_file($_FILES["f1p5c"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p23a'], ".jpg") !== false) || ($request['f1p23a'] == "noAplica")){
                        $archivo4 = $request['f1p23a'];
                    }else{
                        if(Input::tieneArchivo("f1p23a")){
                            $archivo4 = "f1p23a". date("YmdHis") .".". Input::extencion("f1p23a");
                            $directorio = "public/archivos/formularios/". $archivo4;
                            if (!move_uploaded_file($_FILES["f1p23a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p24a'], ".jpg") !== false) || ($request['f1p24a'] == "noAplica")){
                        $archivo5 = $request['f1p24a'];
                    }else{
                        if(Input::tieneArchivo("f1p24a")){
                            $archivo5 = "f1p24a". date("YmdHis") .".". Input::extencion("f1p24a");
                            $directorio = "public/archivos/formularios/". $archivo5;
                            if (!move_uploaded_file($_FILES["f1p24a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p24b'], ".jpg") !== false) || ($request['f1p24b'] == "noAplica")){
                        $archivo6 = $request['f1p24b'];
                    }else{
                        if(Input::tieneArchivo("f1p24b")){
                            $archivo6 = "f1p24b". date("YmdHis") .".". Input::extencion("f1p24b");
                            $directorio = "public/archivos/formularios/". $archivo6;
                            if (!move_uploaded_file($_FILES["f1p24b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p25a'], ".jpg") !== false) || ($request['f1p25a'] == "noAplica")){
                        $archivo7 = $request['f1p25a'];
                    }else{
                        if(Input::tieneArchivo("f1p25a")){
                            $archivo7 = "f1p25a". date("YmdHis") .".". Input::extencion("f1p25a");
                            $directorio = "public/archivos/formularios/". $archivo7;
                            if (!move_uploaded_file($_FILES["f1p25a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p26a'], ".jpg") !== false) || ($request['f1p26a'] == "noAplica")){
                        $archivo8 = $request['f1p26a'];
                    }else{
                        if(Input::tieneArchivo("f1p26a")){
                            $archivo8 = "f1p26a". date("YmdHis") .".". Input::extencion("f1p26a");
                            $directorio = "public/archivos/formularios/". $archivo8;
                            if (!move_uploaded_file($_FILES["f1p26a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p26b'], ".jpg") !== false) || ($request['f1p26b'] == "noAplica")){
                        $archivo9 = $request['f1p26b'];
                    }else{
                        if(Input::tieneArchivo("f1p26b")){
                            $archivo9 = "f1p26b". date("YmdHis") .".". Input::extencion("f1p26b");
                            $directorio = "public/archivos/formularios/". $archivo9;
                            if (!move_uploaded_file($_FILES["f1p26b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                     
                    if((strpos($request['f1p27a'], ".jpg") !== false) || ($request['f1p27a'] == "noAplica")){
                        $archivo10 = $request['f1p27a'];
                    }else{
                        if(Input::tieneArchivo("f1p27a")){
                            $archivo10 = "f1p27a". date("YmdHis") .".". Input::extencion("f1p27a");
                            $directorio = "public/archivos/formularios/". $archivo10;
                            if (!move_uploaded_file($_FILES["f1p27a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                     
                    if((strpos($request['f1p28a'], ".jpg") !== false) || ($request['f1p28a'] == "noAplica")){
                        $archivo11 = $request['f1p28a'];
                    }else{
                        if(Input::tieneArchivo("f1p28a")){
                            $archivo11 = "f1p28a". date("YmdHis") .".". Input::extencion("f1p28a");
                            $directorio = "public/archivos/formularios/". $archivo11;
                            if (!move_uploaded_file($_FILES["f1p28a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p28b'], ".jpg") !== false) || ($request['f1p28b'] == "noAplica")){
                        $archivo12 = $request['f1p28b'];
                    }else{
                        if(Input::tieneArchivo("f1p28b")){
                            $archivo12 = "f1p28b". date("YmdHis") .".". Input::extencion("f1p28b");
                            $directorio = "public/archivos/formularios/". $archivo12;
                            if (!move_uploaded_file($_FILES["f1p28b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }

                    if((strpos($request['f1p29a'], ".jpg") !== false) || ($request['f1p29a'] == "noAplica")){
                        $archivo13 = $request['f1p29a'];
                    }else{
                        if(Input::tieneArchivo("f1p29a")){
                            $archivo13 = "f1p29a". date("YmdHis") .".". Input::extencion("f1p29a");
                            $directorio = "public/archivos/formularios/". $archivo14;
                            if (!move_uploaded_file($_FILES["f1p29a"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                     
                    if((strpos($request['f1p29b'], ".jpg") !== false) || ($request['f1p29b'] == "noAplica")){
                        $archivo14 = $request['f1p29b'];
                    }else{
                        if(Input::tieneArchivo("f1p29b")){
                            $archivo14 = "f1p29b". date("YmdHis") .".". Input::extencion("f1p29b");
                            $directorio = "public/archivos/formularios/". $archivo14;
                            if (!move_uploaded_file($_FILES["f1p29b"]["tmp_name"], $directorio)){
                                throw new Exception("No fue posible subir la imagen, intentalo nuevamente", 1);
                            }
                        }
                    }
                    
                    $parametros = array(
                                        "f1p1a" => $request['f1p1a'],
                                        "f1p2a" => $request['f1p2a'],
                                        "f1p3a" => $request['f1p3a'],
                                        "f1p4a" => $request['f1p4a'],
                                        "f1p5a" => $archivo1,
                                        "f1p5b" => $archivo2,
                                        "f1p5c" => $archivo3,
                                        "f1p6a" => $request['f1p6a'],
                                        "f1p7a" => $request['f1p7a'],
                                        "f1p8a" => $request['f1p8a'],
                                        "f1p9a" => $request['f1p9a'],
                                        "f1p10a" => $request['f1p10a'],
                                        "f1p11a" => $request['f1p11a'],
                                        "f1p12a" => $request['f1p12a'],
                                        "f1p13a" => $request['f1p13a'],
                                        "f1p14a" => $request['f1p14a'],
                                        "f1p15a" => $request['f1p15a'],
                                        "f1p16a" => $request['f1p16a'],
                                        "f1p17a" => $request['f1p17a'],
                                        "f1p18a" => $request['f1p18a'],
                                        "f1p19a" => $request['f1p19a'],
                                        "f1p20a" => $request['f1p20a'],
                                        "f1p21a" => $request['f1p21a'],
                                        "f1p22a" => $request['f1p22a'],
                                        "f1p23a" => $archivo4,
                                        "f1p24a" => $archivo5,
                                        "f1p24b" => $archivo6,
                                        "f1p25a" => $archivo7,
                                        "f1p26a" => $archivo8,
                                        "f1p26b" => $archivo9,
                                        "f1p27a" => $archivo10,
                                        "f1p28a" => $archivo11,
                                        "f1p28b" => $archivo12,
                                        "f1p29a" => $archivo13,
                                        "f1p29b" => $archivo14,
                                        "f1p30a" => $request['f1p30a'],
                                        "f1p30b" => $request['f1p30b'],
                                        "f1p30c" => $request['f1p30c'],
                                        "f1p30d" => $request['f1p30d'],
                                        "f1p31a" => $request['f1p31a'],
                                        "f1p32a" => $request['f1p32a'],
                                        "f1p33a" => $request['f1p33a'],
                                        "f1p34a" => $request['f1p34a'],
                                        "f1p35a" => $request['f1p35a'],
                                        "f1p36a" => $request['f1p36a'], 
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

         public function obtenerFormularioUno(){
            try{
                $select = "SELECT * FROM inovekia_formulario_uno;";
                $resultSelect = $this->queryArray($select, array());
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

    }

?>

