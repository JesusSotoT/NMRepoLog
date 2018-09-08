<?php
/* 
 * Instalación de la base de datos que controla la ejecución de los reportes.
 */



//-- -----------------------------------------------------
//-- Table `mydb`.`repolog_estilos`
//-- -----------------------------------------------------
$sql = "
   CREATE  TABLE IF NOT EXISTS `repolog_estilos` (
  `idestilo` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL ,
  PRIMARY KEY (`idestilo`) )
ENGINE = InnoDB;";
$conexion->consultar($sql);
$result = $conexion->consultar("select * from repolog_estilos where nombre='Ejecutivo' ");
if(!($rs=$conexion->siguiente($result))){
    $conexion->consultar("insert into repolog_estilos (nombre) values ('Ejecutivo');");
}
$conexion->cerrar_consulta($result);



//-- -----------------------------------------------------
//-- Table `mydb`.`repolog_reportes`
//-- -----------------------------------------------------
$sql = "
    CREATE  TABLE IF NOT EXISTS `repolog_reportes` (
  `idreporte` INT NOT NULL AUTO_INCREMENT ,
  `nombrereporte` VARCHAR(50) NULL ,
  `descripcion` VARCHAR(80) NULL ,
  `fechacreacion` DATETIME NULL ,
  `fechamodificacion` DATETIME NULL ,
  `estatus` CHAR(1) NULL ,
  `idestiloomision` INT NULL ,
  `sql_select` VARCHAR(500) NULL ,
  `sql_from` VARCHAR(500) NULL ,
  `sql_where` VARCHAR(500) NULL ,
  `sql_groupby` VARCHAR(500) NULL ,
  `sql_having` VARCHAR(500) NULL ,
  `sql_orderby` VARCHAR(500) NULL ,
  `url_include` VARCHAR(500) NULL ,
  PRIMARY KEY (`idreporte`) ,
  INDEX `repolog_reportes_estilos` (`idestiloomision` ASC) ,
  CONSTRAINT `repolog_reportes_estilos`
    FOREIGN KEY (`idestiloomision` )
    REFERENCES `repolog_estilos` (`idestilo` )
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB;";
$conexion->consultar($sql);


//-- -----------------------------------------------------
//-- Table `mydb`.`repolog_campos_subtotales`
//-- -----------------------------------------------------
$sql = "
CREATE  TABLE IF NOT EXISTS `repolog_campos_subtotales` (
  `idreporte` INT NOT NULL ,
  `idcampogrupo` INT NOT NULL ,
  `idcamposubtotal` INT NOT NULL ,
  PRIMARY KEY (`idreporte`, `idcampogrupo`, `idcamposubtotal`) ,
  INDEX `repolog_reportes_campos_subtotales` (`idreporte` ASC) ,
  CONSTRAINT `repolog_reportes_campos_subtotales`
    FOREIGN KEY (`idreporte` )
    REFERENCES `repolog_reportes` (`idreporte` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;";
$conexion->consultar($sql);

//-- -----------------------------------------------------
//-- Table `mydb`.`repolog_campos_totales`
//-- -----------------------------------------------------
$sql = "
CREATE  TABLE IF NOT EXISTS `repolog_campos_totales` (
  `idreporte` INT NOT NULL ,
  `idcampo` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`idreporte`, `idcampo`) ,
  INDEX `repolog_reportes_campos_totales` (`idreporte` ASC) ,
  CONSTRAINT `repolog_reportes_campos_totales`
    FOREIGN KEY (`idreporte` )
    REFERENCES `repolog_reportes` (`idreporte` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;";
$conexion->consultar($sql);


//-- -----------------------------------------------------
//-- Table `mydb`.`repolog_filtros_solicitar`
//-- -----------------------------------------------------
$sql = "
CREATE  TABLE IF NOT EXISTS `repolog_filtros_solicitar` (
  `idfiltro` INT NOT NULL AUTO_INCREMENT ,
  `idreporte` INT NOT NULL ,
  `operadorlogico` VARCHAR(10) NULL ,
  `idcampo` VARCHAR(50) NULL ,
  `operadorcomp` VARCHAR(10) NULL ,
  `etiqueta` VARCHAR(50) NULL ,
  PRIMARY KEY (`idfiltro`, `idreporte`) ,
  INDEX `repolog_reportes_filtros_solicitar` (`idreporte` ASC) ,
  CONSTRAINT `repolog_reportes_filtros_solicitar`
    FOREIGN KEY (`idreporte` )
    REFERENCES `repolog_reportes` (`idreporte` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;";
$conexion->consultar($sql);





?>
