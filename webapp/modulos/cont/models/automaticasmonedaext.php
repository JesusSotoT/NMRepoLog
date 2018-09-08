<?php

class automaticasMonedaExtModel extends CaptPolizasModel
{
	function listaTipoCambio($idmoneda,$periodo){
		if($idmoneda){
			$filtro = "and c.moneda=".$idmoneda;
		}else{
			$filtro = "";
		}
		$sql = $this->query("select c.*,m.codigo from cont_tipo_cambio c,cont_coin m where m.coin_id=c.moneda $filtro and c.fecha like '$periodo%'");
		return $sql;
	}
	
	public function clientesCuentas($idmoneda){
		$sql=$this->query("SELECT c.account_id, c.description,c.manual_code  FROM cont_accounts c,cont_config co WHERE c.status=1 AND c.removed=0 AND c.affectable=1 AND c.main_father =co.CuentaClientes and c.currency_id=".$idmoneda." "); // hijos
		return $sql;
	}
	function cuentasprove($idmoneda){//todas als cuentas d provee 
		$sql=$this->query("select cu.account_id,cu.description,cu.manual_code from cont_accounts cu,cont_config c where  cu.`affectable`=1 and cu.removed=0 and cu.status=1 and cu.main_father=(select c.main_father from cont_accounts c,cont_config co  where c.account_id=co.CuentaProveedores ) and cu.currency_id=".$idmoneda." ");
		return $sql;
	}
	function tipomoneda(){
		$sql = $this->query("select * from cont_coin");
		return $sql;
	}
	public function bancosext($idmoneda){
		$sql=$this->query("select c.* from cont_accounts c,cont_config co where  c.affectable=1 and c.main_father = co.CuentaBancos and c.currency_id=".$idmoneda);
		return $sql;
	}
	 function bancosexttodas(){
		$sql=$this->query("select c.* from cont_accounts c,cont_config co where  c.affectable=1 and (c.main_father = co.CuentaBancos || c.main_father = co.CuentaCaja) and c.currency_id!=1");
		return $sql;
	}
	function proveedorcuentasext(){//cuentas que no estan en el padron para mostrar
			$sql=$this->query("select cu.account_id,cu.description,cu.manual_code from cont_accounts cu,cont_config c where  cu.`affectable`=1 and cu.removed=0 and cu.currency_id!=1 and cu.status=1 and cu.main_father=(select c.main_father from cont_accounts c,cont_config co  where c.account_id=co.CuentaProveedores)");
			return $sql;
		}
	function proveedor(){//prv asociados a cuenta extranjera
			$sql=$this->query("SELECT cuenta,idPrv,razon_social FROM mrp_proveedor m,cont_accounts c where m.cuenta!='' and m.cuenta!=0  and m.cuenta=c.account_id  and c.currency_id=1 order by m.razon_social asc ");
			return $sql;
		}
	///// co moneda
	public function bancosextXmoneda($idmoneda){
		$sql=$this->query("select c.* from cont_accounts c,cont_config co where  c.affectable=1 and c.main_father = co.CuentaBancos and c.currency_id=".$idmoneda);
		return $sql;
	}
	function proveedorcuentasextXmoneda($idmoneda){//cuentas que no estan en el padron para mostrar
			$sql=$this->query("select cu.account_id,cu.description,cu.manual_code from cont_accounts cu,cont_config c where  cu.`affectable`=1 and cu.removed=0 and cu.currency_id=".$idmoneda." and cu.status=1 and cu.main_father=c.CuentaProveedores");
			return $sql;
		}
	
	function proveedorXmoneda($idmoneda){//prv asociados a cuenta extranjera
			$sql=$this->query("SELECT cuenta,idPrv,razon_social FROM mrp_proveedor m,cont_accounts c where m.cuenta!='' and m.cuenta!=0  and m.cuenta=c.account_id  and c.currency_id=".$idmoneda." order by m.razon_social asc ");
			return $sql;
		}
	public function clientesCuentaExt(){
			$sql=$this->query("SELECT c.account_id, c.description,c.manual_code  FROM cont_accounts c,cont_config co WHERE c.status=1 AND c.removed=0 AND  c.currency_id!=1 and c.affectable=1 AND c.main_father =co.CuentaClientes"); // hijos
			return $sql;
			
		}
		public function clientesCuentaExtXmoneda($idmoneda){
			$sql=$this->query("SELECT c.account_id, c.description,c.manual_code  FROM cont_accounts c,cont_config co WHERE c.status=1 AND c.removed=0 AND  c.currency_id=".$idmoneda." and c.affectable=1 AND c.main_father =co.CuentaClientes"); // hijos
			return $sql;
			
		}
		public function clientesext(){
			$sql=$this->query("select cli.id,cli.nombre from comun_cliente cli,cont_accounts c where ( cli.cuenta=c.account_id || cli.cuenta=0  || cli.cuenta=-1 ) and c.currency_id!=1  group by cli.id order by cli.nombre asc");
			return $sql;
		}
		function tipocambioXfecha($fecha,$moneda){
			$sql = $this->query("select * from cont_tipo_cambio where fecha ='$fecha' and moneda=".$moneda);
			return $sql;
		}
		function InsertMovExt($IdPoliza,$Movto,$segmento,$sucursal,$Cuenta,$TipoMovto,$Importe,$concepto,$persona,$xml,$referencia,$fomapago,$tipocambio)
		{

			$myQuery = "INSERT INTO cont_movimientos(IdPoliza,NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona,FormaPago,tipocambio) VALUES($IdPoliza,$Movto,$segmento,$sucursal,$Cuenta,'$TipoMovto',$Importe,'$referencia','$concepto',1,NOW(),'$xml','".$persona."',$fomapago,$tipocambio)";
			
			if($this->query($myQuery))
			{
				return true;
			}
			else
			{
				return false;
			}

		}
	
}
		
?>