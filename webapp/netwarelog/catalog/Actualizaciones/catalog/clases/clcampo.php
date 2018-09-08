<?php

class campo{
	var $idcampo;
	var $idestructura;
	var $nombrecampo;
	var $nombrecampousuario;
	var $descripcion;
	var $longitud;
	var $tipo;
	var $valor;
	var $formula;
	var $requerido;
	var $formato;
	var $orden;
	
	function setidcampo($idcampo){ $this->idcampo = $idcampo; }
	function setidestructura($idestructura){ $this->idestructura = $idestructura; }
	function setnombrecampo($nombrecampo){ $this->nombrecampo = $nombrecampo; }
	function setnombrecampousuario($nombrecampousuario){ $this->nombrecampousuario = $nombrecampousuario; }
	function setdescripcion($descripcion){ $this->descripcion = $descripcion; }
	function setlongitud($longitud){ $this->longitud = $longitud; }
	function settipo($tipo){ $this->tipo = $tipo; }
	function setvalor($valor){ $this->valor = $valor; }
	function setformula($formula){ $this->formula = $formula; }
	function setrequerido($requerido){ $this->requerido = $requerido; }
	function setformato($formato){ $this->formato = $formato; }
	function setorden($orden){ $this->orden = $orden; }
	
	function getidcampo(){ return $this->idcampo; }
	function getidestructura(){ return $this->idestructura; }
	function getnombrecampo(){ return $this->nombrecampo; }
	function getnombrecampousuario(){ return $this->nombrecampousuario; }
	function getdescripcion(){ return $this->descripcion; }
	function getlongitud(){ return $this->longitud; }
	function gettipo(){ return $this->tipo; }
	function getvalor(){ return $this->valor; }
	function getformula(){ return $this->formula; }
	function getrequerido(){ return $this->requerido; }
	function getformato(){ return $this->formato; }
	function getorden(){ return $this->orden; }
	
	function guardar($conexion){		
		if($this->idcampo!=-1){ 
			//EDITAR
			$sql = "
				select idcampo
				from catalog_campos
				where nombrecampo='".$this->nombrecampo."'
				      and idcampo<>".$this->idcampo."
					  and idestructura=".$this->idestructura."
			";
			if($conexion->existe($sql)){
				?>
					<script type='text/javascript'> 
						alert("El nombre del campo ya se encuentra seleccionado.");
						window.history.go(-1);
					</script>
				<?php
			}else{						
				$sql = "
				    update catalog_campos set
						nombrecampo='".$this->nombrecampo."',
						nombrecampousuario='".$this->nombrecampousuario."',
						descripcion='".$this->descripcion."',
						longitud='".$this->longitud."',
						tipo='".$this->tipo."',
						valor='".$this->valor."',
						formula='".$this->formula."',
						requerido='".$this->requerido."',
						formato='".$this->formato."',
						orden='".$this->orden."' 					
					where idcampo=".$this->idcampo."
					";
				$conexion->consultar($sql);
				$conexion->cerrar();
				header("Location: campo.php");
			}
		} else {
			//NUEVO
			$sql = "
				select idcampo 
				from catalog_campos
				where nombrecampo='".$this->nombrecampo."' 
					  and idestructura=".$this->idestructura."				
			";
			if($conexion->existe($sql)){
				?>
					<script type='text/javascript'> 
						alert("El nombre del campo ya se encuentra seleccionado.");
						window.history.go(-1);
					</script>
				<?php 
			}else{
				$sql = "
					insert into catalog_campos
					( idestructura,nombrecampo,nombrecampousuario,
					  descripcion,longitud,tipo,
					  valor,formula,requerido,
				 	  formato,orden)
					values
					( 
					  ".$this->idestructura.",'".$this->nombrecampo."','".$this->nombrecampousuario."',
					  '".$this->descripcion."',".$this->longitud.",'".$this->tipo."',
					  '".$this->valor."','".$this->formula."',".$this->requerido.",
					  '".$this->formato."',".$this->orden."
					)
				";
				$conexion->consultar($sql);
				$conexion->cerrar();
				header("Location: campo.php");
			} //if($conexion->existe($sql)){
		} //if($this->idcampo!=-1){ 
	}
	
	function eliminar($conexion){
		$sql = " delete from catalog_campos where idcampo=".$this->idcampo;
		$conexion->consultar($sql);
		$conexion->cerrar();
		header("Location: campo.php");
	}
	
	function marcar_llave($conexion){
		$sql = " update catalog_campos set llaveprimaria=-1 where idcampo=".$this->idcampo;
		$conexion->consultar($sql);
		$conexion->cerrar();
		header("Location: campo.php");
	}
	
	function desmarcar_llave($conexion){
		$sql = " update catalog_campos set llaveprimaria=0 where idcampo=".$this->idcampo;
		$conexion->consultar($sql);
		$conexion->cerrar();
		header("Location: campo.php");		
	}


}

?>
