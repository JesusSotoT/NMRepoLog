<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ValesCajaModel extends Connection
{

    function listarVales($estatus,$where){
        $myquery ="SELECT a.idanticipo folio, a.fecha_captura fecha, concat(b.nombreEmpleado, ' ', b.apellidoPaterno,' ', b.apellidoMaterno) operador, a.idordenservicio os, c.idFormapago, c.nombre forma_pago, a.referencia, a.importe cantidad, a.idbancaria
                from tran_anticipo a
                left join nomi_empleados b on b.idEmpleado = a.idEmpleado
                left join forma_pago c on c.idFormapago = a.idFormapago
                WHERE a.estatus = ".$estatus."".$where;
        $vales = $this->queryArray($myquery);
        return $vales["rows"];
    }
    function listarVales1($where){
        $myquery ="SELECT a.idanticipo folio, a.fecha_captura fecha, concat(b.nombreEmpleado, ' ', b.apellidoPaterno,' ', b.apellidoMaterno) operador, a.idordenservicio os, c.idFormapago, c.nombre forma_pago, a.referencia, a.importe cantidad, a.idbancaria
                from tran_anticipo a
                left join nomi_empleados b on b.idEmpleado = a.idEmpleado
                left join forma_pago c on c.idFormapago = a.idFormapago
                WHERE (a.estatus = 1 or a.estatus = 2 or a.estatus = 3)".$where;
        $vales = $this->queryArray($myquery);
        return $vales["rows"];
    }
    function listarCuentas($where){
        $myquery ="SELECT a.idbancaria, CONCAT(a.cuenta,' - ', b.nombre) cuenta  FROM bco_cuentas_bancarias a
                        left join cont_bancos b on b.idbanco = a.idbanco;".$where;
        $cuentas = $this->queryArray($myquery);
        return $cuentas["rows"];
    }
    function listarOperadores($where){
        $myquery ="SELECT a.idEmpleado, concat(a.nombreEmpleado, ' ', a.apellidoPaterno, ' ', a.apellidoMaterno) operador from nomi_empleados a".$where;
        $cuentas = $this->queryArray($myquery);
        return $cuentas["rows"];
    }
    function listarOS($where){
        $myquery ="SELECT a.idordenservicio, a.fecha, a.idunidad, b.idEmpleado, concat(b.nombreEmpleado, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) operador from tran_ordenservicio a 
                    left join nomi_empleados b on b.idEmpleado = a.idEmpleado".$where;
        $os = $this->queryArray($myquery);
        return $os["rows"];
    }
    function listarOS1($where){
        $myquery ="SELECT a.idordenservicio, a.fecha, a.idunidad, concat(b.nombreEmpleado, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) operador from tran_ordenservicio a 
                    left join nomi_empleados b on b.idEmpleado = a.idEmpleado".$where;
        $os = $this->queryArray($myquery);
        return $os["rows"];
    }
    function listarFormaspago($where){
        $myQuery = "SELECT  a.idFormapago, a.nombre FROM forma_pago a".$where.";";
        $listFormapago = $this->queryArray($myQuery);
        return $listFormapago["rows"];
    }
    function listarConceptos(){
        $myQuery = "SELECT  a.idconceptoprestamo, a.concepto FROM tran_conceptoprestamo a;";
        $conceptos = $this->queryArray($myQuery);
        return $conceptos["rows"];
    }
    
    function updatedAnticipo($folio,$idcuenta,$estatus)
    {
        $registro = 0;
        $myQuery = "UPDATE tran_anticipo SET 
                    tran_anticipo.idbancaria = '$idcuenta',
                    tran_anticipo.estatus = '$estatus' 
                    where tran_anticipo.idanticipo = '$folio';";
        
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
    function canceledAnticipo($folio,$estatus)
    {
        $registro = 0;
        $myQuery = "UPDATE tran_anticipo SET 
                    tran_anticipo.estatus = '$estatus' 
                    where tran_anticipo.idanticipo = '$folio';";
        
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
    
    function savedAnticipo($idOS,$fechaCaptura,$idcuenta,$idEmpleado,$idFormapago,$referencia,$importe,$estatus,$tipo){
        $registro = 0;
        $myQuery = "INSERT INTO tran_anticipo (idordenservicio,fecha_captura,idEmpleado,idFormapago,idbancaria,referencia,importe,estatus,tipo) 
            VALUES ('$idOS','$fechaCaptura','$idEmpleado','$idFormapago','$idcuenta','$referencia','$importe','$estatus','$tipo');";

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
    function savedPrestamoOperadores($idOS,$fechaCaptura,$idcuenta,$idEmpleado,$idFormapago,$referencia,$importe,$estatus,$tipo){

        $myQuery = "INSERT INTO tran_anticipo (idordenservicio,fecha_captura,idEmpleado,idFormapago,idbancaria,referencia,importe,estatus,tipo) 
            VALUES ('$idOS','$fechaCaptura','$idEmpleado','$idFormapago','$idcuenta','$referencia','$importe','$estatus','$tipo');";

        if(($lastid = $this->insert_id($myQuery))){
            $idanticipo = $lastid;

            $myQuery1 = "INSERT INTO tran_prestamos (idanticipo) 
            VALUES ('$idanticipo');";

            if(($lastid1 = $this->insert_id($myQuery1))){
                $idprestamo = $lastid1;
                return $idprestamo;
               }
        }
    }
    function savedDiverso($idOS,$fechaCaptura,$idoperador,$idconcepto,$idformapago,$idcuenta,$observaciones,$importe,$estatus,$tipo){
        $myQuery = "INSERT INTO tran_anticipo (idordenservicio,fecha_captura,idEmpleado,idFormapago,idbancaria,referencia,importe,estatus,tipo,idconcepto) 
                    VALUES ('$idOS','$fechaCaptura','$idoperador','$idformapago','$idcuenta','$observaciones','$importe','$estatus','$tipo',$idconcepto);";

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
    function pagosjsonM($query){
            $registro = 0;
            if($this->multi_query($query)){
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


