<?php
	
	class dependencia{
		var $idcampo;
		var $tipodependencia;
		var $dependenciatabla;
		var $dependenciacampovalor;
		var $dependenciacampodescripcion;
		var $campostitulos=array();
		
		var $filtros;
				
		function setidcampo($idcampo){ $this->idcampo=$idcampo; }
		function settipodependencia($tipodependencia){ $this->tipodependencia=$tipodependencia; }
		function setdependenciatabla($dependenciatabla){ $this->dependenciatabla=$dependenciatabla; }
		function setdependenciacampovalor($dependenciacampovalor){ $this->dependenciacampovalor=$dependenciacampovalor; }
		function setdependenciacampodescripcion($dependenciacampodescripcion){ $this->dependenciacampodescripcion=$dependenciacampodescripcion; }
		function setcampostitulos($campostitulos){ $this->campostitulos = $campostitulos; }

		function getidcampo(){ return $this->idcampo; }
		function gettipodependencia(){ return $this->tipodependencia; }
		function getdependenciatabla(){ return $this->dependenciatabla; }
		function getdependenciacampovalor(){ return $this->dependenciacampovalor; }
		function getdependenciacampodescripcion(){ return $this->dependenciacampodescripcion; }
		
		
		function setfiltros($filtros){
			$this->filtros=$filtros;
		}
		function getfiltros(){
			return $this->filtros;
		}
				
		function guardar($conexion){
			
			//CATALOG_DEPENDENCIAS
			$sql = "delete from catalog_dependencias where idcampo=".$this->idcampo;
			$conexion->consultar($sql);
			
			$sql = "
					insert into catalog_dependencias
					(
						idcampo,tipodependencia,dependenciatabla,
					 	dependenciacampovalor,dependenciacampodescripcion
					)
					values
					(
						".$this->idcampo.", '".$this->tipodependencia."', '".$this->dependenciatabla."',
						'".$this->dependenciacampovalor."', '".$this->dependenciacampodescripcion."'
					)
				   ";
			$conexion->consultar($sql);
			
			//CATALOG_DEPENDENCIASFILTROS
			$sql = " delete from catalog_dependenciasfiltros where idcampo=".$this->idcampo;
			$conexion->consultar($sql);

			if(is_array($this->getfiltros())){
				foreach($this->getfiltros() as $key => $value){
					$sql = "
						insert into catalog_dependenciasfiltros
						(idcampo,nombrecampo)
						values
						('".$this->idcampo."','".$value."')
						";
					//echo $sql;
					$conexion->consultar($sql);
				}
			}
			
			//DOCLOG_DEPENDENCIASFILTROS_DETALLES
			$sql = " delete from doclog_dependenciasfiltros_detalles where idcampo=".$this->idcampo;
			$conexion->consultar($sql);	
			//echo "Elementos:".count($this->campostitulos);		
			foreach ($this->campostitulos as $campo){
				$sql = "
						insert into doclog_dependenciasfiltros_detalles 
						(idcampo,nombrecampotitulo)
						values
						('".$this->idcampo."','".$campo."')
				";
				//echo $sql;
				$conexion->consultar($sql);				
			}
			
			
			$conexion->cerrar();			
			header("Location: campo.php");	
		}
		
	}

?>