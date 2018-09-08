<?php 

require("models/connection_sqli.php"); // funciones mySQLi

class retiroModel extends Connection {

    public function __construct() {
        session_start();
    }

        function agregaretiro($cantidad,$concepto){
            //echo $cantidad.'-'.$concepto;

            date_default_timezone_set("Mexico/General");
            $fechaactual = date("Y-m-d H:i:s");
            //$_SESSION['sucursal'] = $sucursal;
            $this->iniTrans();
            $insertRetiro = "INSERT into venta_retiro_caja(cantidad,concepto,idempleado,fecha) values('" . $cantidad . "','" . $concepto . "'," . $_SESSION['accelog_idempleado'] . ",'".$fechaactual."')";
            //echo $insertRetiro;
            $resultInsert = $this->queryTrans($insertRetiro);
            $idInsert = $resultInsert['insertId'];
            $this->commit();
            return array("status" => true, "type" => 2, 'id' => $idInsert);
        }
        
        function pintatabla(){
                $queryRetiros = "SELECT r.id,r.cantidad,r.concepto, u.usuario, r.fecha from venta_retiro_caja r, accelog_usuarios u where r.idempleado=u.idempleado";
                $resultqueryRetiros = $this->queryArray($queryRetiros);
                return array("rows" => $resultqueryRetiros['rows']);
        }
        function usuarios(){
            $queryUsuarios = "SELECT DISTINCT r.idempleado, u.usuario from accelog_usuarios u, venta_retiro_caja r where u.idempleado=r.idempleado";
            $resultUsuarios = $this->queryArray($queryUsuarios);
            return array('rows' => $resultUsuarios['rows']);
        }
        function filtra($desde,$hasta,$user){
                if($user==0 || $user==''){
                    $filtro1=" r.idempleado=u.idempleado";
                }else{
                    $filtro1 = " r.idempleado=u.idempleado and r.idempleado=".$user;
                }

                if($desde == ''){
                    $filtro2 = '';
                }else{
                    $filtro2 = " and r.fecha between '".$desde."' and '".$hasta."' ";
                }

                $queryRetiros = "SELECT r.id,r.cantidad,r.concepto, u.usuario, r.fecha from venta_retiro_caja r, accelog_usuarios u where ".$filtro1." ".$filtro2;
                $resultqueryRetiros = $this->queryArray($queryRetiros);
                return array("rows" => $resultqueryRetiros['rows']);

        }

}

?>