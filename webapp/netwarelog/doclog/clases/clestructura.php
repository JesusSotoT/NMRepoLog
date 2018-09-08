<?php

class estructura{
	var $idestructura;
	var $nombreestructura;
	var $descripcion;
	var $fechacreacion;
	var $fechamodificacion;
	var $link;
	var $utilizaidorganizacion;
	var $linkproceso;
	var $linkprocesoantes;
	var $columnas=0;
	
	
	//SETTES
	function setidestructura($id){ $this->idestructura=$id; }
	function setnombreestructura($nombre){ $this->nombreestructura=$nombre; }
	function setdescripcion($desc){ $this->descripcion=$desc; }
    function setutilizaidorganizacion($utilizaidorganizacion){ $this->utilizaidorganizacion=$utilizaidorganizacion; }
	function setfechacreacion($fecha){ $this->fechacreacion=$fecha; }
	function setfechamodificacion($fecha){ $this->fechamodificacion=$fecha; }
	function setlink($link){ $this->link=$link; }
    function setlinkproceso($linkproceso){ $this->linkproceso=$linkproceso; }
	function setlinkprocesoantes($linkprocesoantes){ $this->linkprocesoantes=$linkprocesoantes; }
	function setcolumnas($columnas){ $this->columnas=$columnas; }
	
	
	//GETTES
	function getidestructura(){ return $this->idestructura; }
	function getnombreestructura(){ return $this->nombreestructura; }
	function getdescripcion(){ return $this->descripcion; }
    function getutilizaidorganizacion(){ return $this->utilizaidorganizacion; }
	function getfechacreacion(){ return $this->fechacreacion; }
	function getfechamodificacion(){ return $this->fechamodificacion; }	
	function getlinkproceso(){ return $this->linkproceso; }
	function getlinkprocesoantes(){ return $this->linkprocesoantes; }
	function getcolumnas(){ return $this->columnas; }
	
	


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
						alert("El nombre de la estructura ya se encuentra seleccionado, o puede ser que este utilizando parte de los nombres reservados: organizaciones, empleados, accelog, etc.");
						window.history.go(-1);
					</script>
				<?php
			}else{						
				$sql = "
				       update catalog_estructuras set
						nombreestructura='".$this->nombreestructura."',
						descripcion='".$this->descripcion."',
						fechamodificacion=".$ahora.",
                       	utilizaidorganizacion=".$this->utilizaidorganizacion.",
                       	linkproceso='".$this->linkproceso."',
						linkprocesoantes='".$this->linkprocesoantes."',
						columnas='".$this->columnas."'
					where idestructura=".$this->idestructura."
					";
                                //echo $sql;
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
						alert("El nombre de la estructura ya se encuentra seleccionado, o puede ser que este utilizando parte de los nombres reservados: organizaciones, empleados, accelog, etc.");
						window.history.go(-1);
					</script>
				<?php
			}else{
				$sql = "
					insert into catalog_estructuras
					(nombreestructura,descripcion,fechacreacion,fechamodificacion,
					 estatus,utilizaidorganizacion,linkproceso,linkprocesoantes,columnas)
					values
					('".$this->nombreestructura."','".$this->descripcion."',".$ahora.",".$ahora.",'G',
					 ".$this->utilizaidorganizacion.",'".$this->linkproceso."','".$this->linkprocesoantes."',".$this->columnas.")
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

				case 'archivo':
						$longitud="255";
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


            //REGISTRO TRANSACCIONES -- 2010-11-03
            $conexion->transaccion("CATALOG - GENERA ESTRUCTURA - ".$this->getnombreestructura(),$sql);			


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
						
			
			//CONTROL DE ACCESO ---- enlace catalog
			$url_de_la_estructura="../catalog/gestor.php?idestructura=".$this->getidestructura()."&ticket=testing";
            $sql = " select idmenu from accelog_menu where url = '".$url_de_la_estructura."' ";
            $result = $conexion->consultar($sql);
            if(($rs=$conexion->siguiente($result))){
                $sql = "
                    update accelog_menu set
                        nombre='".$this->getdescripcion()."'
                    where url='".$url_de_la_estructura."';
                    ";
            } else {
                $sql = "
                    insert into accelog_menu values
                    (null,'".$this->getdescripcion()."',0,'".$url_de_la_estructura."',1,-1,20,0);
                    ";
            }
            $conexion->consultar($sql);			


			//AÑADIENDO LAS OPCIONES 
			
			//CHECANDO SI YA EXISTEN LOS REGISTROS DE PERMISOS
			$sqlpermisos = " 
					select idopcion 
					from accelog_opciones 
					where idopcion like 'catalog_".$this->getidestructura()."%' 
							";			
			if(!$conexion->existe($sqlpermisos)){
				//AGREGAR
				$sqlpermisos = " insert into accelog_opciones values ('catalog_".$this->getidestructura()."xa','".$this->getnombreestructura()." no agregar');";
				$conexion->consultar($sqlpermisos);
				//ELIMINAR
				$sqlpermisos = " insert into accelog_opciones values ('catalog_".$this->getidestructura()."xe','".$this->getnombreestructura()." no eliminar');";
				$conexion->consultar($sqlpermisos);
				//MODIFICAR
				$sqlpermisos = " insert into accelog_opciones values ('catalog_".$this->getidestructura()."xm','".$this->getnombreestructura()." no modificar');";
				$conexion->consultar($sqlpermisos);				
			}						
			
			$conexion->cerrar_consulta($result);
			$conexion->cerrar();
			
			
			echo "
				  <script type='text/javascript'>
						window.location = 'index.php';
				  </script>
				";			
		}
								
	} // activar estructura




}

?>
