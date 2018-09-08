<?php

//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class PantallaElectronicaModel extends Connection
{
    function listarPantalla($where)
        {
            $myQuery = "SELECT DISTINCT a.no_economico, b.capacidad,a.estatus,j.estatus estatus2, c.idordenservicio, d.nombreEmpleado operador, h.estado destino, f.nombretienda cliente, concat('FECHA: ', e.fecha,' / ','HORA:', e.hora) solicitud, concat(c.fecha) asignacion from tran_unidades a
                        left join tran_capacidadunidad b on b.idcapacidadunidad = a.idcapacidadunidad
                        left join tran_ordenservicio c on c.idunidad = a.idunidad
                        left join nomi_empleados d on d.idEmpleado = c.idEmpleado
                        left join tran_solicitud e on e.idsolicitud = c.idsolicitud
                        left join comun_cliente f on f.id = e.id
                        left join tran_datosentrega g on g.iddatosentrega = e.iddatosentrega
                        left join estados h on h.idestado = g.idestado
                        left join comun_destinatario i on i.id = e.id_destinatario
                        left join tran_estatusunidad j on j.idest_uni = a.estatus
                       
                        ".$where.";";
            $pantalla = $this->queryArray($myQuery);
            return $pantalla["rows"];
        }

function listarPantallaCH($where)
        {
            $myQuery = "SELECT a.idunidad, c.estatus, a.idunidad, a.no_economico, b.idcapacidadunidad,b.capacidad, a.estatus, c.idordenservicio, d.idEmpleado, d.nombreEmpleado operador, g.iddatosentrega, j.municipio origen, h.municipio destino, f.id, concat(f.nombre, ' ', f.nombretienda) cliente, concat('FECHA: ', e.fecha, 'HORA:', e.hora) solicitud, c.fecha asignacion from tran_unidades a
                        left join tran_capacidadunidad b on b.idcapacidadunidad = a.idcapacidadunidad
                        left join tran_ordenservicio c on c.idunidad = a.idunidad
                        left join nomi_empleados d on d.idEmpleado = c.idEmpleado
                        left join tran_solicitud e on e.idsolicitud = c.idsolicitud
                        left join comun_cliente f on f.id = e.id
                        left join tran_datosentrega g on g.iddatosentrega = e.iddatosentrega
                        left join municipios h on h.idmunicipio = g.idmunicipio
                        left join tran_datoscarga i on i.iddatoscarga = e.iddatoscarga
                        left join municipios j on j.idmunicipio = i.idmunicipio
                        ".$where.";";
            
            $pantallaCH = $this->queryArray($myQuery);
            return $pantallaCH["rows"];
        }

    function listarReportesMax($idordenservicio)
        {
            $myQuery = "SELECT max(a.num_reportes) num_reporte, b.idEmpleado, b.idunidad, b.idcajatractor 
                        from tran_reportes a 
                        left join tran_ordenservicio b on b.idordenservicio = a.idordenservicio
                        where a.idordenservicio = $idordenservicio;";
            $pantalla = $this->queryArray($myQuery);
            return $pantalla["rows"];
        }
    function listarOperadores()
        {
            $myQuery = "SELECT a.idEmpleado, concat(a.nombreEmpleado, ' ', a.apellidoPaterno) operador FROM nomi_empleados a;";
            $operadores = $this->queryArray($myQuery);
            return $operadores["rows"];
        }
    function listarUnidades()
        {
            $myQuery = "SELECT a.idunidad, a.no_economico from tran_unidades a order by a.idunidad;";
            $unidades = $this->queryArray($myQuery);
            return $unidades["rows"];
        }
    function listarDestinos()
        {
            $myQuery = "SELECT a.iddatosentrega, CONCAT(a.entrega_en, ' - ', b.estado, ', ', c.municipio) destino 
                        FROM tran_datosentrega a
                        left join estados b on b.idestado = a.idestado
                        left join municipios c on c.idmunicipio = a.idmunicipio;";
            $destinos = $this->queryArray($myQuery);
            return $destinos["rows"];
        }
    function listarReportesEsp($idordenservicio)
        {
            $myQuery = "SELECT DISTINCT a.num_reportes, a.idordenservicio, c.nombreEmpleado operador, f.estado destino, a.fecha, a.hora, a.km, a.ubicacion, a.observaciones  
                        from tran_reportes a 
                        left join tran_ordenservicio b on b.idordenservicio = a.idordenservicio
                        left join nomi_empleados c on c.idEmpleado= b.idEmpleado
                        left join tran_solicitud d on d.idsolicitud = b.idsolicitud
                        left join tran_datosentrega e on e.iddatosentrega = d.iddatosentrega
                        left join estados f on f.idestado = e.idestado
                        where a.idordenservicio = '$idordenservicio';";
            $pantalla = $this->queryArray($myQuery);
            return $pantalla["rows"];
        }

    function savedReporte($num_reporte,$idordenservicio,$fecha,$hora,$km,$ubicacion,$observ,$estatus){
    		$registro = 0;
            $myQuery = "INSERT INTO tran_reportes (num_reportes,idordenservicio,fecha,hora,km,ubicacion,observaciones,estatus) VALUES 
            ($num_reporte,'$idordenservicio','$fecha','$hora','$km','$ubicacion','$observ','$estatus');";
            if($this->query($myQuery)){
                $registro = 1;
            }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
    }
    function savedReporteF($num_reporte,$idordenservicio,$fecha,$hora,$km,$ubicacion,$observ,$estatus,$idEmpleado,$idunidad,$idcajatractor){
            $registro = 0;
            
            $myQuery = "INSERT INTO tran_reportes (num_reportes,idordenservicio,fecha,hora,km,ubicacion,observaciones,estatus) VALUES 
            ($num_reporte,'$idordenservicio','$fecha','$hora','$km','$ubicacion','$observ','$estatus');";
            

            $myQuery .= "UPDATE nomi_empleados SET nomi_empleados.estatus_tran = 'DISPONIBLE' WHERE nomi_empleados.idEmpleado = '$idEmpleado';";


            $myQuery .= "UPDATE tran_unidades SET tran_unidades.estatus = '1' WHERE tran_unidades.idunidad = '$idunidad';";


            $myQuery .= "UPDATE tran_cajatractor SET tran_cajatractor.estatus = '1' WHERE tran_cajatractor.idcajatractor = '$idunidad';";


            $myQuery .= "UPDATE tran_ordenservicio SET tran_ordenservicio.estatus = '5' WHERE tran_ordenservicio.idordenservicio = '$idordenservicio';";


            $myQuery .= "UPDATE tran_ordenservicio SET tran_ordenservicio.reporte = '1' WHERE tran_ordenservicio.idordenservicio = '$idordenservicio';";


            if($this->multi_query($myQuery)){
            $registro = 1;
           }
            if ($registro == 1)
            {
                echo 1;
            }else{
                echo "Registro Falido!!";
            }
            
    }

}
?>


