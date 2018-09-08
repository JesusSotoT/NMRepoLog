<?php 

require("models/connection_sqli_manual.php"); // funciones mySQLi

class retiroModel extends Connection {

    /*public function __construct() {
        session_start();
    } */

        public function agregaretiro($cantidad,$concepto){
            session_start();
            $selSuc = 'SELECT idSuc from administracion_usuarios where idempleado='.$_SESSION['accelog_idempleado'];
            $res = $this->queryArray($selSuc);

            date_default_timezone_set("Mexico/General");
            $fechaactual = date("Y-m-d H:i:s");
            $_SESSION['sucursal'] = $res['rows'][0]['idSuc'];
            $insertRetiro = "INSERT into app_pos_retiro_caja(cantidad,concepto,idempleado,fecha,idSucursal) values('" . $cantidad . "','" . $concepto . "'," . $_SESSION['accelog_idempleado'] . ",'".$fechaactual."','".$_SESSION["sucursal"]."')";

            $resultInsert = $this->queryArray($insertRetiro);
            $idInsert = $resultInsert['insertId'];
            return array("status" => true, "type" => 2, 'id' => $idInsert);
        }
        public function pintatabla(){
                $queryRetiros = "SELECT r.id,r.cantidad,r.concepto, u.usuario, r.fecha from app_pos_retiro_caja r, accelog_usuarios u where r.idempleado=u.idempleado";
                $resultqueryRetiros = $this->queryArray($queryRetiros);
                return $resultqueryRetiros['rows'];
        }
        public function pintatablaAbonos(){
                $queryRetiros = "SELECT r.id,r.cantidad,r.concepto, u.usuario, r.fecha from app_pos_abono_caja r, accelog_usuarios u where r.idempleado=u.idempleado";
                $resultqueryRetiros = $this->queryArray($queryRetiros);
                return $resultqueryRetiros['rows'];
        }
        public function usuarios(){
            $queryUsuarios = "SELECT DISTINCT r.idempleado, u.usuario from accelog_usuarios u, app_pos_retiro_caja r where u.idempleado=r.idempleado";
            $resultUsuarios = $this->queryArray($queryUsuarios);
            return array('rows' => $resultUsuarios['rows']);
        }
        public function filtra($desde,$hasta,$user){
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

                $queryRetiros = "SELECT r.id,r.cantidad,r.concepto, u.usuario, r.fecha from app_pos_retiro_caja r, accelog_usuarios u where ".$filtro1." ".$filtro2;
                $resultqueryRetiros = $this->queryArray($queryRetiros);
                return array("rows" => $resultqueryRetiros['rows']);

        }
        public function clientes(){
            $sel = "SELECT id, codigo, nombre from comun_cliente;";
            $res = $this->queryArray($sel);

            return $res['rows'];
        }
        public function formaPagos(){
            $sel = 'SELECT * from forma_pago';
            $res = $this->queryArray($sel);

            return $res['rows'];

        }
        public function moneda(){
            $sel = "SELECT * from cont_coin ";
            $res = $this->queryArray($sel);

            return $res['rows'];
        }
        public function buscaCargos($cliente){
            $select = "SELECT * from app_pagos where id_prov_cli='".$cliente."' and cobrar_pagar=0";
            $res = $this->queryArray($select);

            return $res['rows'];
        }
        public function guardaAbono($cliente,$importe,$concepto,$moneda,$formaPago,$cargo){
            date_default_timezone_set("Mexico/General");
            $fechaactual = date("Y-m-d H:i:s");


            $insert1 = "INSERT INTO app_pos_abono_caja(cantidad,concepto,idempleado,fecha,idSucursal,idcliente,id_forma_pago) values('".$importe."','".$concepto."','".$_SESSION['accelog_idempleado']."','".$fechaactual."','1','".$cliente."','".$formaPago."')";
            //echo $insert1;
            $res1 = $this->queryArray($insert1);

            if($cliente > 0){
                $insert2 = "INSERT INTO app_pagos(cobrar_pagar,id_prov_cli,abono,fecha_pago,concepto,id_forma_pago,id_moneda,tipo_cambio,origen) values('0','".$cliente."','".$importe."','".$fechaactual."','".$concepto."','".$formaPago."','".$moneda."','1','2')";
                $res2 = $this->queryArray($insert2);
                $idPago = $res2['insertId'];

                /*$insert3 = "INSERT INTO app_pagos_relacion(id_pago,id_tipo,id_documento,abono) values('".$idPago."','0','".$cargo."','".$importe."')";
                $res3 = $this->queryArray($insert3); */


            }

            return $res1['insertId'];
        }





}


















?>