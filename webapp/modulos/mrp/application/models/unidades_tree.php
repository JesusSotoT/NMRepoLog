<?php

class Unidades_tree extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}

	function unidadesBasicas($type)
	{
		$this->load->database();
		$where = '';
		if($type==1){$where = " where identificadores != '000' ";}
		$queryUnidades = 'Select id,tipo,identificadores from unid_generica '.$where;
		$result = $this->db->query($queryUnidades);
		return $result->result_array();
	}
	
	function unidades($ids)
	{
		$this->load->database();

		if($ids == '000')
		{
			$identificadores = "Select identificadores from unid_generica ";
			$identificadores = $this->db->query($identificadores);
			$ids = '';

			foreach ($identificadores->result_array() as $key => $value) {
				if($value["identificadores"] != '')
				{
					$ids .= $value["identificadores"].",";
				}
			}
			if($ids != '')
			{
				$ids = substr($ids, 0, -1);
			}else
			{
				$ids = '\'\'';
			}

			$queryUnidades = 'Select idUni,compuesto,orden,permiso from mrp_unidades where idUni not in('.$ids.')';
			$type=true;
		}else
		{
			$type=false;
			$queryUnidades = 'Select idUni,compuesto,orden,permiso from mrp_unidades where idUni in('.$ids.')';
		}

		$result = $this->db->query($queryUnidades);
		return array($result->result_array(),$type);

	}

	function quitar($id,$unidad)
	{	
		$this->load->database();
		$identificadores = "Select identificadores from unid_generica where id = ".$unidad." ";
		$identificadores = $this->db->query($identificadores);
		$identificadores = $identificadores->result_array();

		$identificadores = explode(",",$identificadores[0]["identificadores"]);

		$clave = array_search($id, $identificadores);

		unset($identificadores[$clave]);

		$identificadores = implode(',', $identificadores);

		$update = "Update unid_generica set identificadores = '".$identificadores."' where id = ".$unidad;
		$result = $this->db->query($update);

		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}

	}

	function agregar($id,$unidad)
	{
		$this->load->database();
		$identificadores = "Select identificadores from unid_generica where id = ".$unidad." ";
		$identificadores = $this->db->query($identificadores);
		$identificadores = $identificadores->result_array();

		$identificadores =  trim($identificadores[0]["identificadores"]);

		$identificadores = explode(",",$identificadores);

		if($identificadores[0] != '')
		{
			$identificadores[] = $id;
		}else
		{
			$identificadores[0] = $id;
		}

		$identificadores = implode(',', $identificadores);

		$update = "Update unid_generica set identificadores = '".$identificadores."' where id = ".$unidad;
		$result = $this->db->query($update);

		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function nueva($nombre)
	{

		$this->load->database();
		$insert = "INSERT INTO unid_generica (id, tipo, identificadores) VALUES(0, '".$nombre."', '')";

		$result = $this->db->query($insert);

		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}

	}

	function modifica($id,$nombre){

		$this->load->database();
		$update = "Update unid_generica set tipo = '".$nombre."' where id = ".$id." where persmiso != 0 ";

		$result = $this->db->query($update);

		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function eliminar($id,$nombre){

		$this->load->database();
		$update = "delete from unid_generica where id = ".$id." where persmiso != 0 ";

		$result = $this->db->query($update);

		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function uBase($tree,$id)
	{
		$this->load->database();
		$identificadores = "SELECT identificadores from unid_generica where id = $tree";

		$result = $this->db->query($identificadores);

		if ($this->db->affected_rows() > 0)
		{
			$identificadores = $result->result_array();
			$identificadores =  trim($identificadores[0]["identificadores"]);
			
			$updateRestore = "Update mrp_unidades set orden = 2 where idUni in($identificadores)";
			$result = $this->db->query($updateRestore);

			$updateBase = "Update mrp_unidades set orden = 1 where idUni = $id";
			$result = $this->db->query($updateBase);
			return true;
		}
		else
		{
			return FALSE;
		}
	}
}

?>