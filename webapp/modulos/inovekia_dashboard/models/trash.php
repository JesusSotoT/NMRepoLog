public function reporteTrimestral($folio, $fecha_inicial, $fecha_final, $consultor){
        $sql = "SELECT iec.id_consultor, au.correoelectronico FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico <> '@' GROUP BY iec.id_consultor;";
        $respuesta = $this->queryArray($sql);
        
        //$this->inadem($respuesta["rows"][0]["correoelectronico"]);
        mkdir("views/plantilla/reporteTrimestral", 0700);
        foreach ($respuesta["rows"] as $value) {
            if($value["correoelectronico"] == $consultor){
                echo "entro al if";
                $consultor_respuesta = $this->inadem($value["correoelectronico"]);
                foreach ($consultor_respuesta as $valor) {
                    print_r($valor["ReportePDF"]);
                    exit();
                    $url = $valor["ReportePDF"];
                    $source = file_get_contents($url);
                    file_put_contents("views/plantilla/reporteTrimestral/".$valor["rfc"]."_reporte.pdf", $source);
                    echo " Se ha descargado el pdf de ".$valor["rfc"];
                    exit();
                }
            }
            
        }
    }

    public function reporteTrimestral($folio, $fecha_inicial, $fecha_final){
        $sql = "SELECT iec.id_consultor, au.correoelectronico FROM netwarstore.inovekia_empresario_consultor as iec 
        INNER JOIN netwarstore.inovekia_empresario_folio as ief on iec.id_empresario = ief.id_empresario
        INNER JOIN _dbmlog0000010592.administracion_usuarios as au on au.idempleado = iec.id_consultor  
        WHERE ief.folio = '".$folio."' and ief.creado >= '".$fecha_inicial."' and ief.creado <= '".$fecha_final."' and au.correoelectronico <> '@' GROUP BY iec.id_consultor;";
        $respuesta = $this->queryArray($sql);
        
        //$this->inadem($respuesta["rows"][0]["correoelectronico"]);
        mkdir("views/plantilla/reporteTrimestral", 0700);
        foreach ($respuesta["rows"] as $value) {
            $consultor_respuesta = $this->inadem($value["correoelectronico"]);

            foreach ($consultor_respuesta as $valor) {
                print_r($valor["ReportePDF"]);
                exit();
            
                $url = $valor["ReportePDF"];
                $source = file_get_contents($url);
                file_put_contents("views/plantilla/reporteTrimestral/".$valor["rfc"]."_reporte.pdf", $source);
                echo " Se ha descargado el pdf de ".$valor["rfc"];
            }
        }
    }