<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ImplementacionModel extends Connection
{
	public function lista()
	{
		$myQuery = "SELECT* FROM cont_ejercicios";
		$resultados = $this->query($myQuery);
		return $resultados;
	}

    public function cuenta($nc)
    {
        $myQuery = "SELECT description FROM cont_accounts WHERE account_id = $nc";
        $cuenta = $this->query($myQuery);
        $cuenta = $cuenta->fetch_assoc();
        return $cuenta['description'];
    }
    public function cargarInicial(){
        $query = "SELECT(select if(logoempresa is null, -1, 1) otro from organizaciones limit 1) logo, 
                  (select if(id is null, -1, 1) datosF from pvt_configura_facturacion limit 1) datosF,
                  (select if(idSuc is null, -1, 1) sucursal from mrp_sucursal limit 1) sucursal,
                  (select if(coin_id is null, -1, 1) moneda from cont_coin limit 1) moneda,
                  (select if(idbanco is null, -1, 1) banco from cont_bancos limit 1) banco,
                  (select if(id is null, -1, 1) listaP from app_lista_precio limit 1) listaP,
                  (select if(id is null,-1, 1) productos from app_productos limit 1) productos;";
        $result = $this->queryArray($query);
        return $result['rows'];
    }
}
?>
