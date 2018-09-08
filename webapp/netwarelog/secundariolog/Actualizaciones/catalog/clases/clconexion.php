<?php

class conexion{
	var $cbase;
	var $tipobd;
	
	function tipobd(){
		return $this->tipobd;
	}
	
	function conexion($servidor,$usuariobd,$clavebd,$bd,$instalarbase,$tipobd){
		
		$this->tipobd=$tipobd;
		
		if($tipobd=="mysql"){
			$this->cbase = mysql_connect($servidor,$usuariobd,$clavebd);
			mysql_select_db($bd,$this->cbase);		

			if($instalarbase==1){
				$this->instalacion();
			}			
		} else {
			$this->cbase = mssql_connect($servidor,$usuariobd,$clavebd);
			mssql_select_db($bd,$this->cbase);		

			if($instalarbase==1){
				$this->instalacion();
			}										
		}
						
	}
	
	function instalacion(){

		//Instalación de las tablas del sistemas necesarias para catalog:
		if($this->tipobd=="mysql"){
			$sql = "
			CREATE TABLE IF NOT EXISTS catalog_estructuras(
			  idestructura INT NOT NULL AUTO_INCREMENT ,
			  nombreestructura VARCHAR(50) NULL ,
			  descripcion VARCHAR(80) NULL ,
			  fechacreacion DATETIME NULL ,
			  fechamodificacion DATETIME NULL ,
			  estatus CHAR NULL ,
			  PRIMARY KEY (idestructura) )
			";			
			$sql.="ENGINE = InnoDB;";
			mysql_query($sql, $this->cbase);
		} else {
			$sql = "
			IF NOT EXISTS (SELECT 1 
			    FROM INFORMATION_SCHEMA.TABLES 
			    WHERE TABLE_TYPE='BASE TABLE' 
			    AND TABLE_NAME='catalog_estructuras') 
				CREATE TABLE catalog_estructuras(
				  idestructura  INT IDENTITY(1,1) ,
				  nombreestructura VARCHAR(50) NULL ,
				  descripcion VARCHAR(80) NULL ,
				  fechacreacion DATETIME NULL ,
				  fechamodificacion DATETIME NULL ,
				  estatus CHAR NULL ,
				  PRIMARY KEY (idestructura) )
			";			
			mssql_query($sql, $this->cbase);
		}
		/////////


		//TABLA DE CAMPOS
		if($this->tipobd=="mysql"){
			$sql = "
			CREATE TABLE IF NOT EXISTS catalog_campos (
			  idcampo INT NOT NULL AUTO_INCREMENT ,
			  idestructura INT NULL ,
			  nombrecampo VARCHAR(50) NULL ,
			  nombrecampousuario VARCHAR(80) NULL ,
			  descripcion VARCHAR(255) NULL ,
			  longitud INT NULL ,
			  tipo VARCHAR(45) NULL ,
			  valor VARCHAR(45) NULL ,
			  formula VARCHAR(300) NULL ,
			  requerido TINYINT(1) NULL ,
			  formato VARCHAR(45) NULL ,
			  orden INT NULL ,
			  llaveprimaria TINYINT NULL DEFAULT '0', 
			  PRIMARY KEY (idcampo) ,
			  INDEX eted_estructuraid (idestructura ASC) ,
			  CONSTRAINT eted_estructuraid
			    FOREIGN KEY (idestructura )
			    REFERENCES catalog_estructuras (idestructura )
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";			
			$sql.="ENGINE = InnoDB;";
			mysql_query($sql, $this->cbase);
		} else {
			$sql = "
			IF NOT EXISTS (SELECT 1 
			    FROM INFORMATION_SCHEMA.TABLES 
			    WHERE TABLE_TYPE='BASE TABLE' 
			    AND TABLE_NAME='catalog_campos')
			CREATE TABLE catalog_campos (
			  idcampo INT IDENTITY(1,1) ,
			  idestructura INT NULL ,
			  nombrecampo VARCHAR(50) NULL ,
			  nombrecampousuario VARCHAR(80) NULL ,
			  descripcion VARCHAR(255) NULL ,
			  longitud INT NULL ,
			  tipo VARCHAR(45) NULL ,
			  valor VARCHAR(45) NULL ,
			  formula VARCHAR(300) NULL ,
			  requerido INT NULL ,
			  formato VARCHAR(45) NULL ,
			  orden INT NULL ,
			  llaveprimaria INT NULL , 
			  PRIMARY KEY (idcampo) ,
			  CONSTRAINT eted_estructuraid
			    FOREIGN KEY (idestructura )
			    REFERENCES catalog_estructuras (idestructura )
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";			
			mssql_query($sql, $this->cbase);
		}
		/////////



		//TABLA DE DEPENDENCIAS
		if($this->tipobd=="mysql"){
			$sql = "
			CREATE  TABLE IF NOT EXISTS catalog_dependencias (
			  idcampo INT NOT NULL ,
			  tipodependencia CHAR NULL ,
			  dependenciatabla VARCHAR(50) NULL ,
			  dependenciacampovalor VARCHAR(50) NULL ,
			  dependenciacampodescripcion VARCHAR(80) NULL ,
			  PRIMARY KEY (idcampo) ,
			  INDEX eddds_campoid (idcampo ASC) ,
			  CONSTRAINT eddds_campoid
			    FOREIGN KEY (idcampo)
			    REFERENCES catalog_campos (idcampo)
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";			
			$sql.="ENGINE = InnoDB;";
			mysql_query($sql, $this->cbase);
		} else {
			$sql = "
			IF NOT EXISTS (SELECT 1 
			    FROM INFORMATION_SCHEMA.TABLES 
			    WHERE TABLE_TYPE='BASE TABLE' 
			    AND TABLE_NAME='catalog_dependencias')
			CREATE TABLE catalog_dependencias (
			  idcampo INT NOT NULL ,
			  tipodependencia CHAR NULL ,
			  dependenciatabla VARCHAR(50) NULL ,
			  dependenciacampovalor VARCHAR(50) NULL ,
			  dependenciacampodescripcion VARCHAR(80) NULL ,
			  PRIMARY KEY (idcampo) ,
			  CONSTRAINT eddds_campoid
			    FOREIGN KEY (idcampo)
			    REFERENCES catalog_campos (idcampo)
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";			
			mssql_query($sql, $this->cbase);
		}
		/////////


		//TABLA DE DEPENDENCIASFILTROS
		if($this->tipobd=="mysql"){
			$sql = "
			CREATE  TABLE IF NOT EXISTS catalog_dependenciasfiltros (
			  idcampo INT NOT NULL ,
			  nombrecampo VARCHAR(50) NOT NULL ,
			  INDEX dfd_dependencias (idcampo ASC) ,
			  PRIMARY KEY (idcampo, nombrecampo) ,
			  CONSTRAINT dfd_dependencias
			    FOREIGN KEY (idcampo)
			    REFERENCES catalog_dependencias (idcampo)
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";			
			$sql.="ENGINE = InnoDB;";
			mysql_query($sql, $this->cbase);
		} else {
			$sql = "
			IF NOT EXISTS (SELECT 1 
			    FROM INFORMATION_SCHEMA.TABLES 
			    WHERE TABLE_TYPE='BASE TABLE' 
			    AND TABLE_NAME='catalog_dependenciasfiltros')
			CREATE  TABLE catalog_dependenciasfiltros (
			  idcampo INT NOT NULL ,
			  nombrecampo VARCHAR(50) NOT NULL ,
			  PRIMARY KEY (idcampo, nombrecampo) ,
			  CONSTRAINT dfd_dependencias
			    FOREIGN KEY (idcampo)
			    REFERENCES catalog_dependencias (idcampo)
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			";						
			mssql_query($sql, $this->cbase);
		}
		/////////	
		
	}
	
	function cerrar(){
		if($this->tipobd=="mysql"){
			mysql_close($this->cbase);			
		} else {
			mssql_close($this->cbase);						
		}
		
	}
	
	function consultar($sql){
		if($this->tipobd=="mysql"){
			$result = mysql_query($sql,$this->cbase);
			return $result;			
		} else {
			$result = mssql_query($sql,$this->cbase);
			return $result;						
		}
	}
	
	function siguiente($result){
		if($this->tipobd=="mysql"){
			$reg=mysql_fetch_array($result,MYSQL_ASSOC);
			return $reg;
		} else {
			$reg=mssql_fetch_array($result);
			return $reg;			
		}
	}
	
	function cerrar_consulta($result){
		if($this->tipobd=="mysql"){
			mysql_free_result($result);	
		} else {
			mssql_free_result($result);				
		}
		
	}
	
	function fechamx($dato){
		return date("d/m/Y H:i:s",strtotime($dato));
	}

	function existe($sql){
		$existedato=false;
		$result = $this->consultar($sql);
		if($reg=$this->siguiente($result)){
			$existedato=true;
		}
		$this->cerrar_consulta($result);
		return $existedato;
	}
	
	function existetabla($nombretabla){
		if($this->tipobd=="mysql"){
			$Table = mysql_query("show tables like '" . $nombretabla . "'"); 
			if(mysql_fetch_row($Table) === false){
				return(false); 
			} else {
				return(true); 
			}		
		} else {
			$Table = mssql_query("
					SELECT 1 
					FROM INFORMATION_SCHEMA.TABLES 
					WHERE TABLE_TYPE='BASE TABLE' AND TABLE_NAME='".$nombretabla."' ",$this->cbase);			
			if(mssql_fetch_row($Table) === false){
				return(false); 
			} else {
				return(true); 
			}					
		}
	}
					
}

?>