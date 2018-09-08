<?php

class estructura{
	var $idestructura;
	var $nombreestructura;
	var $descripcion;
	var $fechacreacion;
	var $fechamodificacion;
	var $link;
	
	//SETTES
	function setidestructura($id){ $this->idestructura=$id; }
	function setnombreestructura($nombre){ $this->nombreestructura=$nombre; }
	function setdescripcion($desc){ $this->descripcion=$desc; }
	function setfechacreacion($fecha){ $this->fechacreacion=$fecha; }
	function setfechamodificacion($fecha){ $this->fechamodificacion=$fecha; }
	function setlink($link){ $this->link=$link; }
	
	//GETTES
	function getidestructura(){ return $this->idestructura; }
	function getnombreestructura(){ return $this->nombreestructura; }
	function getdescripcion(){ return $this->descripcion; }
	function getfechacreacion(){ return $this->fechacreacion; }
	function getfechamodificacion(){ return $this->fechamodificacion; }	
	function getlink(){ return $this->link; }	
	


	function guardar($conexion){
		
		$ahora = "now()";
		if($conexion->tipobd()=="mssql") $ahora="getdate()";
		
		
		if($this->idestructura!=-1){ 
			//EDITAR
			$sql = "
				select idestructura 
				from catalog_estructuras
				where nombreestructura='".$this->nombreestructura."'
				      and idestructura<>".$this->idestructura."
			";
			if($conexion->existe($sql)){
				?>
					<script type='text/javascript'> 
						alert("El nombre de la estructura ya se encuentra seleccionado.");
						window.history.go(-1);
					</script>
				<?php
			}else{						
				$sql = "
				    update catalog_estructuras set
						nombreestructura='".$this->nombreestructura."',
						descripcion='".$this->descripcion."',
						fechamodificacion=".$ahora." 
					where idestructura=".$this->idestructura."
					";
				$conexion->consultar($sql);
				$conexion->cerrar();
				header("Location: index.php");
			}
		} else {
			//NUEVO
			$sql = "
				select idestructura 
				from catalog_estructuras
				where nombreestructura='".$this->nombreestructura."'
			";
			if($conexion->existe($sql)){
				?>
					<script type='text/javascript'> 
						alert("El nombre de la estructura ya se encuentra seleccionado.");
						window.history.go(-1);
					</script>
				<?php
			}else{
				$sql = "
					insert into catalog_estructuras
					(nombreestructura,descripcion,fechacreacion,fechamodificacion,estatus)
					values
					('".$this->nombreestructura."','".$this->descripcion."',".$ahora.",".$ahora.",'G')
				";
				//echo $sql;
				$conexion->consultar($sql);
				$conexion->cerrar();
				header("Location: index.php");
				
			} //if($conexion->existe($sql)){
		} //if($this->idestructura!=-1){ 
	}

	function deshabilitar($conexion){
		$ahora = "now()";
		if($conexion->tipobd()=="mssql") $ahora="getdate()";		
		
		$sql = " update catalog_estructuras set estatus='D', fechamodificacion=".$ahora." where idestructura=".$this->idestructura;
		$conexion->consultar($sql);
		$conexion->cerrar();
		header("Location: index.php");
	}

	function habilitar($conexion){
		$ahora = "now()";
		if($conexion->tipobd()=="mssql") $ahora="getdate()";
				
		$sql = " update catalog_estructuras set estatus='G', fechamodificacion=".$ahora." where idestructura=".$this->idestructura;
		$conexion->consultar($sql);
		$conexion->cerrar();
		header("Location: index.php");
	}
	
	function activar($conexion){
				
		if($conexion->tipobd()=="mssql"){
			//CHECA SI EXISTE LA TABLA
			$sql_existe = "
					SELECT 1 
					FROM INFORMATION_SCHEMA.TABLES 
					WHERE TABLE_TYPE='BASE TABLE' AND TABLE_NAME='".$this->nombreestructura."' ";
			if($conexion->existe($sql_existe)){
				//CAMBIA EL NOMBRE DE LA TABLA
				//$sql_exec = " ALTER TABLE ".$this->nombreestructura." rename to ".$this->nombreestructura."_f".date('Y_m_d')."_h".date('H_i_s')."";
				$sql_exec = "sp_RENAME '".$this->nombreestructura."', '".$this->nombreestructura."_f".date('Y_m_d')."_h".date('H_i_s')."'";
				//echo $sql_exec;
				$conexion->consultar($sql_exec);
			}			
		} else {
			//CHECA SI EXISTE LA TABLA
			if($conexion->existe("SHOW TABLES LIKE '".$this->nombreestructura."'")){
				//CAMBIA EL NOMBRE DE LA TABLA
				$sql_exec = "RENAME TABLE ".$this->nombreestructura." to ".$this->nombreestructura."_f".date('Y_m_d')."_h".date('H_i_s')."";
				//echo $sql_exec;
				$conexion->consultar($sql_exec);
			}			
		}	
				
		//CREAR LA TABLA
		$sql_exec = " CREATE TABLE ".$this->nombreestructura."(";
		$llaveprimaria="";
		
		$sql = " select * from catalog_campos where idestructura=".$this->idestructura;
		$result = $conexion->consultar($sql);
		$tot_campos=0;
		
		while($reg=$conexion->siguiente($result)){
			
			$tot_campos+=1;

			//Obtiene el nombre de la llave primaria.
			if($reg{'llaveprimaria'}==-1){
				if(strlen($llaveprimaria)!=0) $llaveprimaria.=", ";
				$llaveprimaria.=$reg{'nombrecampo'};	
			}

			//NOMBRE DEL CAMPO
			$sql_exec.=$reg{'nombrecampo'};
			
			//TIPO DE DATOS
			//varchar
			//double
		 	//auto_increment
			//int
			//bigint
			//datetime
			//boolean
			$tipo="";
			switch($reg{'tipo'}){
				
				case 'bigint':
					if($conexion->tipobd()=="mssql"){
						$tipo=" int, ";						
					} else {
						$tipo=" bigint, ";	
					}				
					break;				
				
				
				case 'boolean':
					if($conexion->tipobd()=="mssql"){
						$tipo=" int, ";						
					} else {
						$tipo=" TINYINT(1), ";	
					}				
					break;
				
				case 'varchar':
					$longitud="50";
					if($reg{'longitud'}!="0") $longitud=$reg{'longitud'};				
					$tipo=" varchar(".$longitud.") ";
					if($reg{'requerido'}==-1){
						$tipo.=" NOT NULL, ";
					} else {
						$tipo.=" NULL, ";
					}
					break;
					
				case 'auto_increment':
					if($conexion->tipobd()=="mssql"){
						$tipo=" int IDENTITY(1,1) , ";						
					} else {
						$tipo=" int not null AUTO_INCREMENT, ";	
					}
					
					
					
					break;				
				default:
					$tipo=" ".$reg{'tipo'}." ";
					if($reg{'requerido'}==-1){
						$tipo.=" not null, ";
					} else {
						$tipo.=" null, ";
					}										
			}
			$sql_exec.=$tipo;			
			
		}
		if($tot_campos==0){
			
			echo " 
				<script type='text/javascript'> 
					alert('Lo siento no pudo crearse la estructura: ".$this->nombreestructura." ya que no tiene campos.');  
					window.history.back();
				</script> 
				";
			$conexion->cerrar_consulta($result);
			$conexion->cerrar();
			
		} else {

			if(strlen($llaveprimaria)==0){
				$sql_exec = substr ($sql_exec, 0, strlen($sql_exec) - 2); //le quita la última coma
				$sql_exec.=" ";								
			} else {
				$sql_exec.=" PRIMARY KEY (".$llaveprimaria.") ";				
			}

			if($conexion->tipobd()=="mssql"){
				$sql_exec.="); ";							
			} else {
				$sql_exec.=") ENGINE = InnoDB; ";				
			}
			
			//echo $sql_exec;			
			//echo $sql_exec;			
			$conexion->consultar($sql_exec);		

			//ACTUALIZA ESTATUS Y FECHAMODIFICACION EN LA TABLA ESTRUCTURAS
			$ahora = "now()";
			if($conexion->tipobd()=="mssql") $ahora="getdate()";			
		
			$sql = " 
					update catalog_estructuras 
					set 
						estatus='A',
						fechamodificacion=".$ahora."
					where 
						idestructura=".$this->idestructura."				
				   ";
			$conexion->consultar($sql);
			
			if($conexion->tipobd()=="mssql"){
				$sql = "EXECUTE g_app '".$this->nombreestructura."','".$this->descripcion."','".$this->link."?idestructura=".$this->idestructura."' ";								
			} else {
				$sql = "CALL g_app('".$this->nombreestructura."','".$this->descripcion."','".$this->link."?idestructura=".$this->idestructura."'); ";				
			}
			//echo "<br><br>".$sql;
			$conexion->consultar($sql);
			
			$conexion->cerrar_consulta($result);
			$conexion->cerrar();
			
			
			echo "
				  <script type='text/javascript'>
						window.location = 'index.php';
				  </script>
				";			
		}
								
	}




}

?>
