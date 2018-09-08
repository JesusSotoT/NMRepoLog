<?php
	
	class dependencia{
		var $idcampo;
		var $tipodependencia;
		var $dependenciatabla;
		var $dependenciacampovalor;
		var $dependenciacampodescripcion;
		
		var $filtros;
				
		function setidcampo($idcampo){ $this->idcampo=$idcampo; }
		function settipodependencia($tipodependencia){ $this->tipodependencia=$tipodependencia; }
		function setdependenciatabla($dependenciatabla){ $this->dependenciatabla=$dependenciatabla; }
		function setdependenciacampovalor($dependenciacampovalor){ $this->dependenciacampovalor=$dependenciacampovalor; }
		function setdependenciacampodescripcion($dependenciacampodescripcion){ $this->dependenciacampodescripcion=$dependenciacampodescripcion; }

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
			$sql = "delete from catalog_dependencias where idcampo=".$this->idcampo;
			$conexion->consultar($sql);
		
			$dependenciacampodescripcion = "";
			error_log($this->dependenciacampodescripcion);
			foreach($this->dependenciacampodescripcion as $dep){
				$dependenciacampodescripcion.=$conexion->escapalog($dep).",";
			}
			error_log($dependenciacampodescripcion);

			$sql = "
					insert into catalog_dependencias
					(
						idcampo,tipodependencia,dependenciatabla,
					 	dependenciacampovalor,dependenciacampodescripcion
					)
					values
					(
						".$this->idcampo.", '".$this->tipodependencia."', '".$this->dependenciatabla."',
						'".$this->dependenciacampovalor."', '".$dependenciacampodescripcion."'
					)
				   ";
			$conexion->consultar($sql);
			
			
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
			
			$conexion->cerrar();
			header("Location: campo.php");	
		}
		
	}

?>
