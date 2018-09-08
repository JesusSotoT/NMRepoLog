CREATE TABLE `cont_accounts` (
  `account_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `account_code` varchar(100) DEFAULT NULL COMMENT 'Codigo',
  `manual_code` varchar(100) DEFAULT NULL COMMENT 'Codigo Manual',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT 'Nombre',
  `sec_desc` varchar(100) NOT NULL DEFAULT '' COMMENT 'NomIdioma',
  `account_type` int(11) NOT NULL COMMENT 'Tipo',
  `status` tinyint(1) DEFAULT NULL COMMENT 'EsBaja',
  `main_account` int(11) DEFAULT NULL COMMENT 'CtaMayor',
  `cash_flow` tinyint(1) DEFAULT NULL COMMENT 'CtaEfectivo',
  `reg_date` date DEFAULT NULL COMMENT 'FechaRegistro',
  `currency_id` tinyint(1) DEFAULT NULL COMMENT 'IdMoneda',
  `group_dig` int(11) NOT NULL DEFAULT '0' COMMENT 'DigAgrupador',
  `id_sucursal` int(11) DEFAULT NULL COMMENT 'IdSegNeg',
  `seg_neg_mov` int(11) DEFAULT NULL COMMENT 'SegNegMovtos',
  `affectable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Afectable',
  `mod_date` date DEFAULT NULL COMMENT 'TimeStamp',
  `father_account_id` int(11) NOT NULL COMMENT 'ID de Cuenta Padre',
  `removable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Definir Como Removible',
  `account_nature` int(11) NOT NULL,
  `removed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;

CREATE TABLE `cont_coin` (
  `coin_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`coin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE TABLE `cont_nature` (
  `nature_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`nature_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

CREATE TABLE `cont_account_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

CREATE TABLE `cont_classification` (
  `classification_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`classification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

CREATE TABLE `cont_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;


CREATE TABLE `cont_config` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `IdOrganizacion` int(3) NOT NULL,
  `IdEjercicio` int(2) NOT NULL,
  `TipoCatalogo` int(3) NOT NULL,
  `Estructura` varchar(100) NOT NULL,
  `TipoValores` varchar(1) NOT NULL,
  `TipoNiveles` varchar(1) NOT NULL,
  `RFC` varchar(30) NOT NULL,
  `InicioEjercicio` date NOT NULL,
  `FinEjercicio` date NOT NULL,
  `TipoPeriodo` varchar(1) NOT NULL,
  `NumPeriodos` int(2) NOT NULL,
  `PeriodoActual` int(2) NOT NULL,
  `PeriodosAbiertos` int(1) NOT NULL,
  `EjercicioActual` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


CREATE TABLE `cont_ejercicios` (
  `Id` int(2) NOT NULL AUTO_INCREMENT,
  `NombreEjercicio` varchar(15) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


CREATE TABLE `cont_movimientos` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `IdPoliza` int(11) NOT NULL,
  `NumMovto` int(3) NOT NULL,
  `IdSucursal` int(2) NOT NULL,
  `Cuenta` int(4) NOT NULL,
  `TipoMovto` varchar(5) NOT NULL,
  `Importe` float NOT NULL DEFAULT '0',
  `Referencia` varchar(30) NOT NULL,
  `Concepto` varchar(30) NOT NULL,
  `Activo` int(1) NOT NULL,
  `FechaCreacion` datetime NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;


CREATE TABLE `cont_polizas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idorganizacion` int(11) DEFAULT NULL,
  `idejercicio` int(11) DEFAULT NULL,
  `idperiodo` int(11) DEFAULT NULL,
  `idtipopoliza` int(11) DEFAULT NULL,
  `referencia` varchar(30) DEFAULT NULL,
  `concepto` varchar(40) DEFAULT NULL,
  `cargos` double DEFAULT NULL,
  `abonos` double DEFAULT NULL,
  `ajuste` int(1) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `eliminado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;


CREATE TABLE `cont_tipos_poliza` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE `cont_sucursales` (
  `idSuc` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) DEFAULT NULL,
  `idOrg` int(11) NOT NULL,
  PRIMARY KEY (`idSuc`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`idSuc`, `nombre`, `idOrg`) VALUES
(1, 'Almacen', 1),
(2, 'OFICINA', 1);
