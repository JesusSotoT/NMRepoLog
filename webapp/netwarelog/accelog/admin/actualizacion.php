<?php

	if(!$conexion->existetabla("netwarelog_version")){
		$sql = "create table netwarelog_version (version double);";				
		$conexion->consultar($sql);
	}
	
	$sql = " select version from netwarelog_version ";
	$result = $conexion->consultar($sql);

	$version_sistema = 0;
	if($rs=$conexion->siguiente($result)){
		$version_sistema = $rs{"version"};
	}

	//ACTUALIZACION A VERSION 1
	
	if($version_sistema==0){
		/*
			Cambio a ver 1.000
			Se añade la capacidad al accelog para que los menús
			se abran por omisión.
		*/	
		$sql = "
				ALTER TABLE accelog_menu ADD omision TINYINT(1);
			   ";
		$conexion->consultar($sql);
		
		$sql = "select idestructura from catalog_estructuras where nombreestructura='accelog_menu' ";
		$result = $conexion->consultar($sql);
		$idestructura_catest=0;
		if($rs=$conexion->siguiente($result)){
			$idestructura_catest=$rs{"idestructura"};			
		}
		
		// Agrega campo en catalog
        $sql = " 
                    insert into catalog_campos
                    values
                    (null, ".$idestructura_catest.",'omision',
					'Abrir por Omisión','Abrir menú por omisión o default al abrir el sistema',
					'0','boolean','NA','',0,'',8,0) ";
        $conexion->consultar($sql);

		// Iguala el campo a 0 
		$conexion->consultar(" update accelog_menu set omision=0 ");
		
		// Establece ahora el número de versión
		$version_sistema=1.000;
	}
	
	////////////////////////////////////
	
	
	
	
	
	
	
	//ACTUALIZACION A VERSION 1.001
	
	if($version_sistema==1){
		
		//Aumentando el campo de nombre en la tabla de opciones;
		$sql = "  ALTER TABLE accelog_opciones MODIFY nombre varchar(100) ";
		$conexion->consultar($sql);
		
		//Haciendo cambio en el catalog
		
			//obteniendo el id estructura
			$sql = "select idestructura from catalog_estructuras where nombreestructura='accelog_opciones' ";
			$result=$conexion->consultar($sql);
			if($rs=$conexion->siguiente($result)){
				$idestructura_accelog_opciones=$rs{"idestructura"};
			}
			$conexion->cerrar_consulta($result);
			
			//obteniendo el idcampo
			$sql = " select idcampo from catalog_campos where 
						idestructura=".$idestructura_accelog_opciones." and nombrecampo='nombre' ";
			$result=$conexion->consultar($sql);
			if($rs=$conexion->siguiente($result)){
				$idcampo_ao_nombre=$rs{"idcampo"};
			}
			$conexion->cerrar_consulta($result);
			
			//Ahora si a cerrar la consulta
			$sql = " update catalog_campos set longitud=100 where idcampo=".$idcampo_ao_nombre;
			$conexion->consultar($sql);
															
		////
		
		$sql = " 
				 select idestructura, nombreestructura 
				 from catalog_estructuras 
				 order by idestructura
			   ";
		$result = $conexion->consultar($sql);
		while($rs=$conexion->siguiente($result)){

			//BORRANDO EN CASO DE QUE YA HUBIERA REGISTROS
			$sqlpermisos = " delete from accelog_opciones where idopcion like 'catalog_".$rs{"idestructura"}."%' ";
			$conexion->consultar($sqlpermisos);


			//AGREGAR
			$sqlpermisos = " insert into accelog_opciones values ('catalog_".$rs{"idestructura"}."xa','".$rs{'nombreestructura'}." no agregar');";
			$conexion->consultar($sqlpermisos);
			//ELIMINAR
			$sqlpermisos = " insert into accelog_opciones values ('catalog_".$rs{"idestructura"}."xe','".$rs{'nombreestructura'}." no eliminar');";
			$conexion->consultar($sqlpermisos);
			//MODIFICAR
			$sqlpermisos = " insert into accelog_opciones values ('catalog_".$rs{"idestructura"}."xm','".$rs{'nombreestructura'}." no modificar');";
			$conexion->consultar($sqlpermisos);			
		}
		
		$version_sistema=1.001;
	}
	
	////////////////////////////////////
	
	
	
	//ACTUALIZACION A VERSION 1.002
	
	if($version_sistema==1.001){
		//Aumentando el campo de nombre en la tabla de opciones;
		$sql = "  ALTER TABLE catalog_campos MODIFY formula varchar(10000) ";
		$conexion->consultar($sql);	
		$version_sistema=1.002;	
	}	
	
	
	//ACTUALIZACION A VERSION 1.003
	if($version_sistema==1.002){
		
		//Capacidad para columnas en el catalog
		$sql = "  ALTER TABLE catalog_estructuras ADD columnas int ";
		$conexion->consultar($sql);
		
		//Añadir link de proceso antes del botón guardar
		$sql = "  ALTER TABLE catalog_estructuras ADD linkprocesoantes varchar(200) ";
		$conexion->consultar($sql);
		
		$version_sistema=1.003;		
	}
	///////
	
	
	
	//ACTUALIZACION A VERSION 1.004
	if($version_sistema==1.003){
		
		
		
		//Tabla de documentos
		$sql = "
			-- -----------------------------------------------------
			-- Table `doclog_titulos`
			-- -----------------------------------------------------
			CREATE  TABLE IF NOT EXISTS `doclog_titulos` (
			  `iddocumento` INT NOT NULL AUTO_INCREMENT ,
			  `nombredocumento` VARCHAR(100) NULL ,
			  `observaciones` VARCHAR(500) NULL ,
			  `fechacreacion` DATETIME NULL ,
			  `fechamodificacion` DATETIME NULL ,
			  `estatus` CHAR(1) NULL ,
			  `utilizaidorganizacion` TINYINT(4) NULL ,
			  `linkantes` VARCHAR(500) NULL ,
			  `linkdespues` VARCHAR(500) NULL ,
			  `idestructuratitulo` INT NULL ,
			  `columnas` INT(11)  NULL ,
			  PRIMARY KEY (`iddocumento`) )
			ENGINE = InnoDB;
			";
		$conexion->consultar($sql);
		////
		
		
		
		//Tabla de documentos detalles
		$sql = "
			-- -----------------------------------------------------
			-- Table `doclog_detalles`
			-- -----------------------------------------------------
			CREATE  TABLE IF NOT EXISTS `doclog_detalles` (
			  `iddocumento` INT NOT NULL ,
			  `idestructuradetalle` INT NULL ,
			  PRIMARY KEY (`iddocumento`,`idestructuradetalle`) ,
			  INDEX `doclog_detalles_documentos` (`iddocumento` ASC) ,
			  CONSTRAINT `doclog_detalles_documentos`
			    FOREIGN KEY (`iddocumento` )
			    REFERENCES `doclog_titulos` (`iddocumento` )
			    ON DELETE CASCADE
			    ON UPDATE CASCADE)
			ENGINE = InnoDB;
			";
		$conexion->consultar($sql);
		////
		
		
		
		//Añadiendo la opción en el accelog para el administrador
			
			
			//Obtiene la categoría de la configuración...
	        $sql = " select idcategoria from accelog_categorias where nombre='Configuración' ";
			$result_ac = $conexion->consultar($sql);
			$id_categoria_configuracion=0;
			if($rs_ac = $conexion->siguiente($result_ac)){
				$id_categoria_configuracion = $rs_ac{"idcategoria"};
			}
			$conexion->cerrar_consulta($result_ac);
			////
        	
			
			//Dando de alta la opción en el menú...
	        $sql = "
	            insert into accelog_menu values
	            (null,'DocLog',0,'".$url_doclog_para_accelog."admin/', ".$id_categoria_configuracion.", -1, 2, 0)
	            ";
	        $conexion->consultar($sql);
	        $idmenudoclog=mysql_insert_id();
			////
				
				
			//Obtiene el id del perfil
	        $sql = "select idperfil from accelog_perfiles where nombre='".$super_perfil."' ";
			$result_ap = $conexion->consultar($sql);
			$idsuperperfil=0;
			if($rs_ap = $conexion->siguiente($result_ap)){
				$idsuperperfil=$rs_ap{"idperfil"};
			}
			$conexion->cerrar_consulta($result_ap);
			////
			
			
			
			//Agregando la opción al perfil...
            $sql = "
                insert into accelog_perfiles_me
                values (".$idsuperperfil.",".$idmenudoclog.")
                ";
			//echo $sql."<br><br>";
            $conexion->consultar($sql);
			////
			
			
		//////
		

		$version_sistema=1.004;		
	}
	///////
	
	

	//ACTUALIZACION A VERSION 1.005
	if($version_sistema==1.004){
	
		//Tabla de dependencias con títulos
		$sql = "
				CREATE  TABLE IF NOT EXISTS `doclog_dependenciasfiltros_detalles` (
					  `idcampo` int(11) NOT NULL,
					  `nombrecampotitulo` varchar(50) NOT NULL,
					   PRIMARY KEY (`idcampo`,`nombrecampotitulo`),
					   KEY `dfd_dependencias` (`idcampo`),
					   CONSTRAINT `dfd_dependencias_detalles` 
					   		FOREIGN KEY (`idcampo`) 
					   		REFERENCES `catalog_dependencias` (`idcampo`) 
							ON DELETE CASCADE 
							ON UPDATE CASCADE
				) ENGINE=InnoDB; 
			";
		$conexion->consultar($sql);	
	
	
		$version_sistema=1.005;		
	}
	///////
	
	


	//ACTUALIZACION A VERSION 1.006
	if($version_sistema==1.005){
	
		//Tabla de dependencias con títulos
		$sql = "

			CREATE TABLE IF NOT EXISTS `configuracion_manejadorestatus` (
			  `idmanejador` int(11) NOT NULL AUTO_INCREMENT,
			  `idestructura` int(11) DEFAULT NULL,
			  `idcampo` int(11) DEFAULT NULL,
			  `valordefault` int(11) DEFAULT NULL,
			  PRIMARY KEY (`idmanejador`)
			) ENGINE=InnoDB;


			";
		$conexion->consultar($sql);	
		//Modifica tabla de Repolog Agrega el campo url_include_despues
		$sql = "

			ALTER TABLE `domaincontrol`.`repolog_reportes` ADD COLUMN `url_include_despues` VARCHAR(5000) 
			DEFAULT NULL AFTER `url_include`;



			";
		$conexion->consultar($sql);	
	
		$version_sistema=1.006;		
	}
	///////

	
	//ACTUALIZACION A VERSION 1.007
	//Políticas de niveles de usuarios
	if($version_sistema==1.006){
	
		//Tabla de niveles para usuarios
		$sql = "

			CREATE TABLE IF NOT EXISTS `accelog_niveles` (
			  `idestructura` int(11) NOT NULL,
			  `nombrecampo_empleados` varchar(50) NOT NULL,
			  `nombreestructura` varchar(50) NOT NULL,
			  PRIMARY KEY (`idestructura`,`nombrecampo_empleados`,`nombreestructura`)
			) ENGINE=InnoDB;


			";
			//echo $sql;
		$conexion->consultar($sql);	
		
		//Añadiendo la estructura al catalog...
        	
			$sql = " insert into catalog_estructuras values(null,'accelog_niveles','Niveles de Usuarios',now(),now(),'A',0,'',1,'') ";
        	$conexion->consultar($sql);
        	$idestructura_accelog_niveles=mysql_insert_id();
		
		/////
		
		
		//Añadiendo los campos en el catalog...
        
			$setup->agregacampocatalog($idestructura_accelog_niveles, "idestructura", "Estructura", "0", "int", "-1", "1", "-1",$conexion);
			$setup->agregadependencia(mysql_insert_id(), "catalog_estructuras", "idestructura", "nombreestructura", $conexion);
				
			$setup->agregacampocatalog($idestructura_accelog_niveles, "nombrecampo_empleados", "Campo llave (Empleados)", "50", "varchar", "-1", "2", "-1",$conexion);
			$setup->agregacampocatalog($idestructura_accelog_niveles, "nombreestructura", "Tabla", "50", "varchar", "-1", "3", "-1",$conexion);
		
		//////
		
		//Permisos para el super perfil
		
			//AGREGAR
			$sqlpermisos = " insert into accelog_opciones values ('catalog_".$idestructura_accelog_niveles."xa','"."accelog_niveles no agregar');";
			$conexion->consultar($sqlpermisos);
			//ELIMINAR
			$sqlpermisos = " insert into accelog_opciones values ('catalog_".$idestructura_accelog_niveles."xe','"."accelog_niveles no eliminar');";
			$conexion->consultar($sqlpermisos);
			//MODIFICAR
			$sqlpermisos = " insert into accelog_opciones values ('catalog_".$idestructura_accelog_niveles."xm','"."accelog_niveles no modificar');";
			$conexion->consultar($sqlpermisos);			
				
		//////
		
		
		//Añadiendo la captura dentro del menú de configuración para el super perfil
		
			//Se añade al menu Usuarios Niveles
			$sql = "
				insert into accelog_menu values
				(null,'Usuarios Niveles',0,'".$url_catalog."gestor.php?idestructura=".$idestructura_accelog_niveles."&ticket=testing', 1, -1, 12, 0)
				";
			$conexion->consultar($sql);
			
			//Se agrega al superperfil
			$sql = "
				insert into accelog_perfiles_me
				values (1,".mysql_insert_id().")
				";
			$conexion->consultar($sql);				
						
		//////
		        				
		
		$version_sistema=1.007;		
	}
	///////	
	
	
	
	
	
	//ACTUALIZANDO VERSION ///////////////////////
		
		$sql = " delete from netwarelog_version ";
		$conexion->consultar($sql);
		
		$sql = " insert into netwarelog_version values (".$version_sistema.") ";
		$conexion->consultar($sql);
		
	//////////////////////////////////////////////
	
	

?>