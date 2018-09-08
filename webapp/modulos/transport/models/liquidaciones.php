<?php

//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class LiquidacionesModel extends Connection
{

    function listarOS(){

            $myQuery = "SELECT a.fecha, a.idordenservicio, a.idunidad, b.viaje, concat (e.nombre, ' - Dias de Credito(', e.dias_credito,')') cliente, concat(c.nombreEmpleado, ' ', c.apellidoPaterno, ' ', c.apellidoMaterno) operador,  concat(g.municipio, ' - ', h.estado) origen,  concat(j.municipio, ' - ', k.estado) destino
                from tran_ordenservicio a
                left join tran_solicitud b on b.idsolicitud = a.idsolicitud
                left join nomi_empleados c on c.idEmpleado = a.idEmpleado
                left join comun_cliente e on e.id = b.id
                left join tran_datosentrega f on f.iddatosentrega = b.iddatosentrega
                left join municipios g on g.idmunicipio = f.idmunicipio
                left join estados h on h.idestado = f.idestado
                left join tran_datoscarga i on i.iddatoscarga = b.iddatoscarga
                left join municipios j on j.idmunicipio = i.idmunicipio
                left join estados k on k.idestado = i.idestado
                where a.estatus = '5';";
            $os = $this->queryArray($myQuery);
            return $os["rows"];
    } 
    

    function listarGastosConcepto(){

            $myQuery = "SELECT a.iddesccorta_gastos id, a.concepto FROM tran_desccorta_gastos a";
            $gstos = $this->queryArray($myQuery);
            return $gstos["rows"];
    }
    function listarAnticipos($idordenservicio){

            $myQuery = "SELECT a.idanticipo, a.idordenservicio,a.referencia, a.fecha_captura, a.importe, a.estatus FROM tran_anticipo a where a.idordenservicio = '$idordenservicio'";
            $anticipos = $this->queryArray($myQuery);
            return $anticipos["rows"];
    } 
    function datosLiquidacionM($idordenservicio){
            $myQuery = "SELECT a.idordenservicio , concat (b.nombreEmpleado, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) operador,  
                        c.no_economico unidad, e.nombretienda, f.carga_en, 
                        concat(g.estado, ' / ', h.municipio)origen, i.entrega_en, 
                        concat(j.estado, ' / ', k.municipio)destino, d.temperatura, l.capacidad, c.kmtotal, a.idsolicitud,d.id
                        FROM tran_ordenservicio a 
                        left join nomi_empleados b on b.idEmpleado = a.idEmpleado
                        left join tran_unidades c on c.idunidad =  a.idunidad
                        left join tran_solicitud d on d.idsolicitud = a.idsolicitud
                        left join comun_cliente e on e.id = d.id
                        left join tran_datoscarga f on f.iddatoscarga = d.iddatoscarga
                        left join estados g on g.idestado = f.idestado
                        left join municipios h on h.idmunicipio = f.idmunicipio
                        left join tran_datosentrega i on i.iddatosentrega = d.iddatosentrega
                        left join estados j on j.idestado = i.idestado
                        left join municipios k on k.idmunicipio = i.idmunicipio
                        left join tran_capacidadunidad l on l.idcapacidadunidad = c.idcapacidadunidad
                        where a.idordenservicio = '$idordenservicio';";
            $datos = $this->queryArray($myQuery);
            return $datos["rows"];
    }
    function datosAnticipoOperadorM($idordenservicio){
            $myQuery = "SELECT b.idEmpleado, concat(b.nombreEmpleado, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) operador 
            FROM tran_ordenservicio a
            left join nomi_empleados b on b.idEmpleado = a.idEmpleado 
            where a.idordenservicio = '$idordenservicio';";
            $datos = $this->queryArray($myQuery);
            return $datos["rows"];  
    }
    function listarFormaspago($idordenservicio){
            $myQuery = "SELECT  a.idFormapago, a.nombre FROM forma_pago a;";
            $listFormapago = $this->queryArray($myQuery);
            return $listFormapago["rows"];
        }
    function listarCuenta(){
            $myQuery = "SELECT a.idbancaria, CONCAT(a.cuenta,' - ', b.nombre) cuenta  FROM bco_cuentas_bancarias a
                        left join cont_bancos b on b.idbanco = a.idbanco;";
            $listcuenta = $this->queryArray($myQuery);
            return $listcuenta["rows"];
        }
    function listarConvenios($idsolicitud){
            $myQuery = "SELECT DISTINCT a.idconvenio, a.cantidad, b.descripcion, c.concepto, b.retencion, b.precio_cliente, b.comision_porcentual 
                        from tran_relacion_sol_con a
                        left join tran_convenio b on b.idconvenio = a.idconvenio
                        left join tran_desccorta c on c.iddesccorta = b.iddesccorta
                        where a.idsolicitud = '$idsolicitud' and a.incluir = 1;";
                        
            $convenios = $this->queryArray($myQuery);
            return $convenios["rows"];
        }
     
        function savedAnticipo($Anfecha,$AnOS,$Anoperador,$Anformapago,$Ancuenta,$Anreferencia,$Animporte,$estatus,$tipo){
            
            $registro = 0;
            $myQuery = "INSERT INTO tran_anticipo (idordenservicio,fecha_captura,idEmpleado,idFormapago,idbancaria,referencia,importe,estatus,tipo) 
            VALUES ('$AnOS','$Anfecha','$Anoperador','$Anformapago','$Ancuenta','$Anreferencia','$Animporte','$estatus','$tipo');";
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



    function listadorendimientos($idsolicitud){
        $myQuery = "SELECT a.idsolicitud, b.no_economico, b.idunidad , b.modelo , b.idcapacidadunidad, d.combustible, b.tanque_tamano , b.tanque_rendimiento_foraneo , b.tanque_rendimiento_local,b.tanque_tamano_thermo,b.tanque_rendimiento_foraneo_thermo,b.tanque_rendimiento_local_thermo,c.viaje from tran_ordenservicio a
                    left join tran_unidades b on b.idunidad = a.idunidad
                    left join tran_solicitud c on c.idsolicitud = a.idsolicitud
                    left join tran_tipocombustible d on d.idtipocombustible = b.idtipocombustible
                    where a.idsolicitud = '$idsolicitud';";
         $rendimientos = $this->queryArray($myQuery);
            return $rendimientos["rows"];
             $rendimientos = $this->queryArray($myQuery);
            return $rendimientos["rows"];
    }





            function listarporcentajes()
{
        $myQuery = "SELECT b.idempleado , a.porcentaje ,concat(b.nombreEmpleado,' ',b.apellidoPaterno,' ',b.apellidoMaterno) nombre FROM tran_porcentaje_ope a
            left join nomi_empleados b on b.idEmpleado = a.idEmpleado";

            $listar = $this->queryArray($myQuery);
    return $listar["rows"];
}


function listarconductores(){
   $myQuery = "SELECT a.idempleado , concat(a.nombreEmpleado,' ',a.apellidoPaterno,' ',a.apellidoMaterno) nombre from nomi_empleados a 
   WHERE a.id_clasificacion = 6 ;";

    $id = $this->queryArray($myQuery);
    return $id["rows"];

}

function savedporcentaje($operador,$porcentaje){
     $registro = 0;
            $myQuery = "INSERT INTO tran_porcentaje_ope (idEmpleado,porcentaje) 
                                                 VALUES ('$operador','$porcentaje');";
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


function editedope($operador){
    $myQuery ="SELECT b.idEmpleado , concat(a.nombreEmpleado,' ',a.apellidoPaterno,' ',a.apellidoMaterno) nombre , b.porcentaje from nomi_empleados a 
               left join tran_porcentaje_ope b on b.idempleado = a.idEmpleado
               WHERE a.idEmpleado = '$operador';";
    $listar = $this->queryArray($myQuery);
    return $listar["rows"];
}

function Save1partliqM($idsolicitud,$idordenservicio,$idcliente,$kminicial,$kmdescarga,$kmfinal,$kmrecorridos,$kmcargado,$kmvacio){
   $registro = 0;
            $myQuery = "INSERT INTO tran_liquidaciones (id_solicitud, id_ordenservicio, idcliente,estatus)
                        VALUES
                                                      ('$idsolicitud','$idordenservicio','$idcliente','1');";

            if($this->query($myQuery)){
                $registro = 1;
            }
            if ($registro == 1)
            {
                echo $myQuery;
            }else{
                echo "Registro Falido!!";
            }  
}

function SaveCMBTargM($inpNoValeCMBTarg,$inpCantCMBTarg,$CMBTargcostlit,$idordenservicio,$litXcant){
     $registro = 0;
            $myQuery = "INSERT INTO tran_combustiblesliq (ordenservicio, no_vale, cantidad, pre_x_lit, tipo,litros)
                        VALUES
                                ('$idordenservicio','$inpNoValeCMBTarg','$inpCantCMBTarg','$CMBTargcostlit','1',$litXcant);";

            if($this->query($myQuery)){
                $registro = 1;
            }
            if ($registro == 1)
            {
                echo $myQuery;
            }else{
                echo "Registro Falido!!";
            }  
}

function showliqComplete($idsolicitud,$idordenservicio,$idcliente,$kminicial,$kmdescarga,$kmfinal,$kmrecorridos,$kmcargado,$kmvacio){
    $myQuery = "SELECT id_liquidacion , id_solicitud , id_ordenservicio , idcliente FROM tran_liquidaciones 
                 WHERE id_solicitud = '$idsolicitud' AND id_ordenservicio = '$idordenservicio' ;";
    $listar = $this->queryArray($myQuery);
                return $listar["rows"];

}






}

?>


