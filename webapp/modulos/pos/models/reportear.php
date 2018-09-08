<?php
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ReportearModel extends Connection
{
	public function clientes(){
		$sql = "SELECT id, nombre FROM comun_cliente";
		$clientes = $this->queryArray($sql);
		return $clientes["rows"];
	}
	public function retiros($desde,$hasta){
		
		$filtro = "1 = 1 ";
		$desde1 = $desde." 00:00:01";
        $hasta1 = $hasta." 23:59:59";

        if($desde!='' && $hasta!=''){
            $filtro .=' and r.fecha BETWEEN "'.$desde1.'" and "'.$hasta1.'" ';
        }

		$sql = "SELECT r.*, concat(e.nombre, ' ', e.apellido1, ' ', e.apellido2) empleado 
				FROM app_pos_retiro_caja r
				LEFT JOIN empleados e on e.idempleado = r.idempleado WHERE ".$filtro;
		$retiros = $this->queryArray($sql);
		return $retiros["rows"];
	}
	public function abonos($tipoA,$cliente,$desde,$hasta){

		$filtro = "1 = 1 ";
		$desde1 = $desde." 00:00:01";
        $hasta1 = $hasta." 23:59:59";

        if($tipoA == 1){
        	$filtro .= "";
        }else{
        	$cliente1      = implode('","', $cliente);
			if($cliente1!=""){
	            if($cliente1=='0'){
	                $filtro .=' and a.idcliente!=0';               
	            }else{
	                $filtro .=' and (c.id IN ("'.$cliente1.'"))';                
	            }
	        }
        }

        if($desde!='' && $hasta!=''){
            $filtro .=' and a.fecha BETWEEN "'.$desde1.'" and "'.$hasta1.'" ';
        }
        

		$sql = "SELECT a.*, concat(e.nombre, ' ', e.apellido1, ' ', e.apellido2) empleado, c.nombre cliente  FROM app_pos_abono_caja a
				LEFT JOIN empleados e on e.idempleado = a.idempleado
				LEFT JOIN comun_cliente c on c.id= a.idcliente 
				WHERE ".$filtro;
				//echo $sql;
		$abonos = $this->queryArray($sql);
		return $abonos["rows"];
	}
	public function moneda(){
		$query = "SELECT * from cont_coin";
		$result = $this->queryArray($query);

		return $result['rows'];
	}
	public function formasDePago(){
		$query = "select * from forma_pago WHERE activo = 1 ORDER BY claveSat ASC ";
		$res = $this->queryArray($query);

		return array('formas' => $res['rows'] );
	}
	public function ventasIndex()
	{   
		//$result2 = $this->touchProducts();

		$result2  = '';
		$query3 = "SELECT * from accelog_usuarios";
		$result3 = $this->queryArray($query3);

		$query45 = "SELECT * from comun_cliente";
		$result5 = $this->queryArray($query45);

		//return $result['rows'];
		return array('productos' => $result2 ,  'usuarios' => $result3['rows'], 'clientes' => $result5['rows']);
							   

	}
} 
?>