-- Create syntax for TABLE 'bco_clasificador'
CREATE TABLE `bco_clasificador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombreclasificador` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `coin_id` int(11) DEFAULT NULL,
  `idtipo` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `idplantilla` int(11) DEFAULT NULL,
  `idNivel` int(11) NOT NULL,
  `cuentapadre` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_complementos'
CREATE TABLE `bco_complementos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clave` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `bco_complementos` (`id`, `nombre`, `clave`)
VALUES
  (1, 'Servicios Profesionales', '01'),
  (2, 'Adquisicion de otros bienes, no consignada en estructura publica', '12'),
  (3, 'Otros retiros de AFORE', '13'),
  (4, 'Dividendos o utilidades distribuidas', '14'),
  (5, 'Remanente distribuible', '15'),
  (6, 'Intereses', '16'),
  (7, 'Arrendamiento en fideicomiso', '17'),
  (8, 'Pagos realizados a favor de residentes en el extranjero', '18'),
  (9, 'Enajenacion de acciones u operaciones en bolsa de valores', '19'),
  (10, 'Obtencion de premios', '20'),
  (11, 'Fideicomisos que no realizan actividades empresariales', '21'),
  (12, 'Planes personales de retiro', '22'),
  (13, 'Intereses reales deducibles por creditos hipotecarios', '23'),
  (14, 'Operaciones Financieras Derivadas de Capital', '24'),
  (15, 'Otro tipo de retenciones', '25'),
  (16, 'Regalias por derechos de autor', '02'),
  (17, 'Autotransporte terrestre de carga', '03'),
  (18, 'Servicios prestados por comisionistas', '04'),
  (19, 'Arrendamiento', '05'),
  (20, 'Enajenacion de acciones', '06'),
  (21, 'Enajenacion de bienes objeto de la LIEPS, a travez de mediadores, agentes, representantes, corredores ', '07'),
  (22, 'Enajenacion de bienes inmuebles consignada en estructura publica', '08'),
  (23, 'Enajenacion de otros bienes, no consignada en escritura publica', '09'),
  (24, 'Adquisicion de desperdicios industriales', '10'),
  (25, 'Adquisicion de bienes consignada en escritura publica', '11');

-- Create syntax for TABLE 'bco_conceptos'
CREATE TABLE `bco_conceptos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `idtipo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_configuracion'
CREATE TABLE `bco_configuracion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RFC` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `PeriodosAbiertos` int(1) DEFAULT NULL COMMENT '1-si, 0-no',
  `PeriodoActual` int(2) DEFAULT NULL,
  `EjercicioActual` int(2) DEFAULT NULL,
  `PolizaAuto` int(2) NOT NULL DEFAULT '0',
  `AcontiaConf` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'bco_controlNumeroCheque'
CREATE TABLE `bco_controlNumeroCheque` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `numeroinicial` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numerofinal` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actualrango` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'num actual del rango controlcheq',
  `numeroactual` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'numeracion automatica apartir de conf',
  `idbancaria` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_cuentas_bancarias'
-- CREATE TABLE `bco_cuentas_bancarias` (
--   `idbancaria` int(11) NOT NULL AUTO_INCREMENT,
--   `cuenta` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
--   `idbanco` int(11) NOT NULL,
--   `idtipoc` int(11) DEFAULT NULL,
--   `coin_id` int(11) NOT NULL,
--   `titular` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
--   `account_id` int(11) NOT NULL,
--   `fechainicial` datetime NOT NULL,
--   `saldoinicial` double(100,2) NOT NULL,
--   `controlNumeroCheq` tinyint(1) DEFAULT NULL,
--   `numInicialCheque` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
--   `numFinalCheque` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
--   `numautomaticacheq` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '-1 si, 0 no',
--   `numeroactual` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
--   PRIMARY KEY (`idbancaria`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
alter table bco_cuentas_bancarias
add `controlNumeroCheq` tinyint(1) DEFAULT NULL,
add `numInicialCheque` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
add `numFinalCheque` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
add  `numautomaticacheq` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '-1 si, 0 no',
add  `numeroactual` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL;

alter table cont_polizas
add idDocumento int(11) NULL after idCuentaBancariaOrigen;

-- Create syntax for TABLE 'bco_devoluciones'
CREATE TABLE `bco_devoluciones` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idDocumento` int(11) DEFAULT NULL,
  `IdPoliza` int(11) DEFAULT NULL,
  `idPolizaInvertida` int(11) DEFAULT NULL,
  `idDocInverso` int(11) DEFAULT NULL,
  `numDevolucion` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'bco_documentos'
CREATE TABLE `bco_documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `fechacreacion` datetime DEFAULT NULL,
  `fechaaplicacion` date DEFAULT NULL,
  `folio` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `importe` double(100,2) NOT NULL,
  `referencia` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `concepto` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `idbancaria` int(11) NOT NULL,
  `beneficiario` int(11) NOT NULL COMMENT '1-prv,3-acreedor,2-empleado,4-empresa,5-cliente',
  `idbeneficiario` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1-activo,2-cancelado,3-borrado,4-devuelto',
  `conciliado` int(11) unsigned DEFAULT '0',
  `impreso` int(11) DEFAULT NULL COMMENT '1-si,2-no',
  `asociado` int(11) DEFAULT NULL,
  `proceso` int(11) DEFAULT NULL COMMENT '1-proyectado,2-autorizado,3-emitido,4-depositado',
  `idclasificador` int(11) DEFAULT NULL COMMENT 'bco_clasificador',
  `idDocumento` int(11) NOT NULL COMMENT '1-cheque,2-ingre,3-ingrenodepo,4-depo,5-egre',
  `posibilidadpago` int(11) DEFAULT NULL,
  `xml` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idmoneda` int(11) DEFAULT NULL,
  `formadeposito` int(11) DEFAULT '0',
  `idTipoDoc` int(11) NOT NULL DEFAULT '0' COMMENT 'bco_tiposDocumentoConcepto',
  `bancoDestino` int(11) DEFAULT NULL,
  `numBancoDes` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipocambio` double(100,4) DEFAULT '0.0000',
  `tipoPoliza` int(11) NOT NULL DEFAULT '0' COMMENT '1-siniva 2-coniva 3-sinprovision',
  `cobrado` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  `traspaso` tinyint(1) NOT NULL DEFAULT '0',
  `idtraspaso` int(11) NOT NULL DEFAULT '0',
  `comision` tinyint(1) NOT NULL DEFAULT '0',
  `interes` tinyint(1) NOT NULL DEFAULT '0',
  `inverso` int(11) NOT NULL DEFAULT '0',
  `reactivadoc` int(11) NOT NULL DEFAULT '0',
  `numdoc` int(11) DEFAULT NULL,
  `anticipo` TINYINT(1) not null default 0,
  `idUser` int(11) not null default 0 ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- Create syntax for TABLE 'bco_documentoSubcategorias'
CREATE TABLE `bco_documentoSubcategorias` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idDocumento` int(11) NOT NULL,
  `idSubcategoria` int(11) NOT NULL,
  `porcentaje` double(100,2) NOT NULL,
  `importe` double(100,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'bco_ingresos_depositos'
CREATE TABLE `bco_ingresos_depositos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idDeposito` int(11) DEFAULT NULL,
  `idIngresoNoDepo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'bco_nivelClasificador'
CREATE TABLE `bco_nivelClasificador` (
  `idNivel` int(11) NOT NULL AUTO_INCREMENT,
  `nivel` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`idNivel`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_saldo_bancario'
-- CREATE TABLE `bco_saldo_bancario` (
--   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
--   `periodo` int(11) DEFAULT NULL,
--   `idejercicio` int(11) DEFAULT NULL,
--   `fecha` date NOT NULL,
--   `saldoinicial` double(100,2) NOT NULL,
--   `abonos` double(100,2) DEFAULT NULL COMMENT 'depositos',
--   `cargos` double(100,2) DEFAULT NULL COMMENT 'retiros',
--   `saldofinal` double(100,2) DEFAULT NULL,
--   `idbancaria` int(11) NOT NULL,
--   `folio` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
--   `concepto` varchar(50) DEFAULT NULL,
--   `conciliado` int(11) NOT NULL DEFAULT '0' COMMENT '1 si 0 no',
--   `idMovimientoPoliza` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Mov d poliza asociado',
--   `idDocumentos` varchar(100) NOT NULL DEFAULT '',
--   `conciliadoBancos` tinyint(1) NOT NULL DEFAULT '0',
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

alter table bco_saldo_bancario
add  `idDocumentos` varchar(100) NOT NULL DEFAULT '',
add `conciliadoBancos` tinyint(1) NOT NULL DEFAULT '0';


-- -- Create syntax for TABLE 'bco_saldos_conciliacion'
-- CREATE TABLE `bco_saldos_conciliacion` (
--   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
--   `periodo` int(11) DEFAULT NULL,
--   `saldo_inicial` double(100,2) NOT NULL DEFAULT '0.00',
--   `saldo_final` double(100,2) NOT NULL DEFAULT '0.00',
--   `idbancaria` int(11) DEFAULT NULL,
--   `ejercicio` int(11) DEFAULT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_saldos_conciliacionBancos'
CREATE TABLE `bco_saldos_conciliacionBancos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `periodo` int(11) DEFAULT NULL,
  `saldo_inicial` double(100,2) NOT NULL DEFAULT '0.00',
  `saldo_final` double(100,2) NOT NULL DEFAULT '0.00',
  `idbancaria` int(11) DEFAULT NULL,
  `ejercicio` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_status'
CREATE TABLE `bco_status` (
  `idstatus` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_sucursalBancaria'
CREATE TABLE `bco_sucursalBancaria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numsucursal` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono` int(11) DEFAULT NULL,
  `ejecutivo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idbancaria` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_tipo_cuenta'
CREATE TABLE `bco_tipo_cuenta` (
  `idtipoc` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`idtipoc`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_tipo_documentos'
CREATE TABLE `bco_tipo_documentos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(30) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_tipoClasificador'
CREATE TABLE `bco_tipoClasificador` (
  `idtipo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`idtipo`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_tiposDocumentoConcepto'
CREATE TABLE `bco_tiposDocumentoConcepto` (
  `idTipoDoc` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` date DEFAULT NULL,
  `id` int(11) NOT NULL COMMENT 'TIPO DOCUMENTO(cheque,egreso,etc)',
  `idstatus` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`idTipoDoc`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT IGNORE INTO `bco_tiposDocumentoConcepto` (`idTipoDoc`, `nombre`, `fecha`, `id`, `idstatus`)
VALUES
  (1, 'Traspaso de Cheque', '2016-01-05', 4, 1),
  (2, 'Devolucion de Cheque', NULL, 2, 1),
  (3, 'Traspaso de Cheque', '2016-01-05', 3, 1);


INSERT IGNORE INTO `bco_clasificador` (`id`, `codigo`, `nombreclasificador`, `coin_id`, `idtipo`, `account_id`, `idplantilla`, `idNivel`, `cuentapadre`, `activo`)
VALUES
  (1, '', 'INGRESOS', 1, 1, -1, 0, 2, 1, -1),
  (2, '', 'Traspaso', 1, 1, -1, 0, 1, 1, -1),
  (3, '', 'EGRESOS', 1, 2, -1, 0, 2, -1, -1),
  (4, '', 'Traspaso', 1, 2, -1, 0, 1, 3, -1),
  (5, NULL, 'Devolucion de Cheque', 1, 1, -1, 0, 1, 1, -1),
  (6, '', 'Ingresos', 1, 1, -1, 0, 1, 1, -1),
  (7, '', 'Egresos', 1, 2, -1, 0, 1, 3, -1);


INSERT IGNORE INTO `bco_tipo_documentos` (`id`, `tipo`)
VALUES
  (1, 'Cheque'),
  (2, 'Ingresos'),
  (3, 'Ingresos No Depositados'),
  (4, 'Depositos'),
  (5, 'Egresos');

INSERT IGNORE INTO `bco_status` (`idstatus`, `status`)
VALUES
  (1, 'Activo'),
  (2, 'Inactivo');


INSERT IGNORE INTO `bco_nivelClasificador` (`idNivel`, `nivel`)
VALUES
  (1, 'Subcategoria'),
  (2, 'Categoria');
INSERT IGNORE INTO `bco_tipoClasificador` (`idtipo`, `tipo`)
VALUES
  (1, 'Ingresos'),
  (2, 'Egresos');


INSERT IGNORE INTO `accelog_categorias` (`idcategoria`, `nombre`, `icono`, `orden`)
VALUES
  (1054, 'Conciliacion Bancaria', 0, 11),
  (1050, 'Configuracion', 0, 12);



INSERT IGNORE INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (282, 'bco_cheques', 'Cheques', '2015-05-04 23:13:15', '2015-05-04 23:42:50', 'A', 0, '', 0, ''),
  (285, 'bco_clasificador', 'Clasificador Ingresos-Egresos', '2015-06-12 16:13:42', '2015-12-22 22:20:10', 'A', 0, '../../modulos/bancos/js/despuesclasificador.php', 0, '../../modulos/bancos/js/antesclasificador.php'),
  (288, 'bco_conceptos', 'Conceptos', '2015-06-15 17:37:51', '2015-06-15 17:43:01', 'A', 0, '', 0, ''),
  (280, 'bco_cuentas_bancarias', 'Cuentas Bancarias', '2015-05-04 22:26:46', '2015-05-08 21:55:11', 'A', 0, '../../modulos/bancos/models/despuescuentas.php', 0, '../../modulos/bancos/js/antescuentasbancarias.php'),
  (308, 'bco_nivelClasificador', 'Nivel Clasificador', '2015-12-22 22:04:50', '2015-12-22 22:07:43', 'A', 0, '', 0, ''),
  (332, 'bco_status', 'Estatus', '2016-03-02 17:30:03', '2016-03-02 17:48:03', 'A', 0, '', 0, ''),
  (283, 'bco_sucursalBancaria', 'Sucursal Bancaria', '2015-05-06 16:45:35', '2015-05-08 22:07:41', 'A', 0, '', 0, '../../modulos/bancos/js/antessucursalbancaria.php'),
  (287, 'bco_tipoClasificador', 'Tipo de Clasificador', '2015-06-12 22:22:52', '2015-06-15 22:59:40', 'A', 0, '', 0, ''),
  (309, 'bco_tiposDocumentoConcepto', 'Tipos de Documento', '2016-01-05 16:28:30', '2016-01-05 16:48:24', 'A', 0, '', 0, '../../modulos/bancos/js/antestipodocumento.php'),
  (281, 'bco_tipo_cuenta', 'Tipo de cuenta', '2015-05-04 22:34:56', '2015-05-04 22:37:20', 'A', 0, '', 0, ''),
  (310, 'bco_tipo_documentos', 'Tipo de Documento', '2016-01-05 16:44:57', '2016-01-05 16:44:57', 'G', 0, '', 0, '');

INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1539, 285, 'id', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (1540, 285, 'codigo', 'Codigo', 'Codigo', 100, 'varchar', 'NA', '', 0, '', 1, 0),
  (1541, 285, 'nombreclasificador', 'Nombre del clasificador', 'Nombre del clasificador', 200, 'varchar', 'NA', '', -1, '', 2, 0),
  (1543, 285, 'idtipo', 'Tipo', 'Tipo', 11, 'int', 'NA', '', 0, '-1', 4, 0),
  (1544, 285, 'account_id', 'Cuenta contable', 'Cuenta contable', 11, 'int', 'NA', '', 0, '-1', 5, 0),
  (1676, 285, 'idNivel', 'Nivel', 'Nivel', 11, 'int', 'NA', '', -1, '-1', 6, 0),
  (1677, 285, 'cuentapadre', 'Dependencia', 'Dependencia', 11, 'int', 'NA', '', 0, '-1', 7, 0),
  (1990, 285, 'activo', 'Activo', 'Activo', 1, 'boolean', 'NA', '', -1, '', 10, 0);

INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1551, 288, 'id', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (1552, 288, 'codigo', 'Codigo', 'Codigo', 100, 'varchar', 'NA', '', 0, '', 1, 0),
  (1553, 288, 'descripcion', 'Descripcion', 'Descripcion', 300, 'varchar', 'NA', '', -1, '', 2, 0),
  (1555, 288, 'idtipo', 'Tipo', 'Tipo', 11, 'int', 'NA', '', 0, '', 3, 0);

INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1504, 280, 'idbancaria', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 1, -1),
  (1505, 280, 'cuenta', 'Cuenta', 'Cuenta', 20, 'varchar', 'NA', '', -1, '', 2, 0),
  (1506, 280, 'idbanco', 'Banco', 'Banco', 11, 'int', 'NA', '', -1, '-1', 3, 0),
  (1507, 280, 'idtipoc', 'Tipo de cuenta', 'Tipo de cuenta', 11, 'int', 'NA', '', 0, '-1', 4, 0),
  (1508, 280, 'coin_id', 'Moneda', 'Moneda', 11, 'int', 'NA', '', -1, '-1', 5, 0),
  (1509, 280, 'titular', 'Titular o titulares de la cuenta', 'Titular o titulares de la cuenta', 255, 'varchar', 'NA', '', -1, '', 6, 0),
  (1510, 280, 'account_id', 'Cuenta contable', 'Cuenta contable', 11, 'int', 'NA', '', -1, '-1', 7, 0),
  (1527, 280, 'fechainicial', 'Fecha Inicial', 'Fecha Inicial', 0, 'datetime', '-1', '', -1, '', 8, 0),
  (1528, 280, 'saldoinicial', 'Saldo de apertura', 'Saldo de apertura', 0, 'double', 'NA', '', -1, '', 9, 0),
  (1535, 280, 'numInicialCheque', 'Numero Inicial', 'Numero Inicial', 100, 'varchar', 'NA', '', 0, '', 11, 0),
  (1536, 280, 'numFinalCheque', 'Numero Final', 'Numero Final', 100, 'varchar', 'NA', '', 0, '', 12, 0),
  (1537, 280, 'numautomaticacheq', 'Llevar numeración automatica de cheques', 'Llevar numeración automatica de cheques', 0, 'boolean', '0', '', 0, '', 14, 0),
  (1538, 280, 'numeroactual', 'Numero Actual', 'Numero Actual', 100, 'varchar', 'NA', '', -1, '', 13, 0),
  (1661, 280, 'controlNumeroCheq', 'Rango de Número de Cheque', 'Rango de Número de Cheque', 0, 'boolean', 'NA', '', 0, '', 10, 0);
INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1678, 308, 'idNivel', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (1679, 308, 'nivel', 'Nivel', 'Nivel', 250, 'varchar', 'NA', '', -1, '', 1, 0);
INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1790, 332, 'idstatus', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 1, -1),
  (1791, 332, 'status', 'status', 'status', 20, 'varchar', 'NA', '', -1, '', 2, 0);
INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1521, 283, 'id', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (1522, 283, 'numsucursal', 'Numero de Sucursal', 'Numero de Sucursal', 11, 'int', 'NA', '', -1, '', 1, 0),
  (1523, 283, 'descripcion', 'Descripcion', 'Descripcion', 255, 'varchar', 'NA', '', 0, '', 2, 0),
  (1524, 283, 'direccion', 'Direccion', 'Direccion', 255, 'varchar', 'NA', '', 0, '', 3, 0),
  (1525, 283, 'telefono', 'Telefono', 'Telefono', 20, 'int', 'NA', '', 0, '', 4, 0),
  (1526, 283, 'ejecutivo', 'Ejecutivo de Cuenta', 'Ejecutivo de Cuenta', 100, 'varchar', 'NA', '', 0, '', 5, 0),
  (1529, 283, 'idbancaria', 'Cuenta bancaria', 'Cuenta bancaria', 11, 'int', 'NA', '', -1, '', 6, 0);
INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1549, 287, 'idtipo', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (1550, 287, 'tipo', 'Tipo', 'Tipo', 100, 'varchar', 'NA', '', -1, '', 1, 0);
INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1680, 309, 'idTipoDoc', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (1681, 309, 'nombre', 'Nombre', 'Nombre', 250, 'varchar', 'NA', '', -1, '', 1, 0),
  (1682, 309, 'fecha', 'Fecha Registro', 'Fecha Registro', 0, 'date', 'NA', '', 0, '', 2, 0),
  (1683, 309, 'id', 'Documentos de', 'Documentos de', 11, 'int', 'NA', '', -1, '-1', 3, 0),
  (1789, 309, 'idstatus', 'Estatus', 'Estatus', 2, 'int', '1', '', 0, '-1', 4, 0);
INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1511, 281, 'idtipoc', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (1512, 281, 'tipo', 'Tipo de cuenta', 'Tipo de cuenta', 40, 'varchar', 'NA', '', -1, '', 0, 0);
INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1684, 310, 'id', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (1685, 310, 'tipo', 'Tipo', 'Tipo', 30, 'varchar', 'NA', '', -1, '', 1, 0);
INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1506, 'S', 'cont_bancos', 'idbanco', ' Clave , nombre ');
  INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1507, 'S', 'bco_tipo_cuenta', 'idtipoc', ' tipo ');
  INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1508, 'S', 'cont_coin', 'coin_id', ' description ');
  INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1510, 'S', 'cont_accounts', 'account_id', ' manual_code , description');

  INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1529, 'S', 'bco_cuentas_bancarias', 'idbancaria', ' cuenta');
INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1543, 'S', 'bco_tipoClasificador', 'idtipo', ' tipo ');

INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1544, 'S', 'cont_accounts', 'account_id', ' manual_code , description ');
INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1676, 'S', 'bco_nivelClasificador', 'idNivel', ' nivel ');
INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1677, 'S', 'bco_clasificador', 'id', ' codigo , nombreclasificador ');
INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1555, 'S', 'bco_tipoClasificador', 'idtipo', ' tipo ');
INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1683, 'S', 'bco_tipo_documentos', 'id', ' tipo ');
INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1789, 'S', 'bco_status', 'idstatus', ' status ');

  update catalog_estructuras set linkproceso='../../modulos/bancos/models/despuescuentas.php' where idestructura=280;

INSERT IGNORE INTO `accelog_opciones` (`idopcion`, `nombre`)
VALUES
  ('catalog_285xa', 'bco_clafisicadorIngresos no agregar'),
  ('catalog_285xe', 'bco_clafisicadorIngresos no eliminar'),
  ('catalog_285xm', 'bco_clafisicadorIngresos no modificar');
  
 
INSERT IGNORE INTO `accelog_opciones` (`idopcion`, `nombre`)
VALUES
  ('catalog_309xa', 'bco_tiposDocumentoConcepto no agregar'),
  ('catalog_309xe', 'bco_tiposDocumentoConcepto no eliminar'),
  ('catalog_309xm', 'bco_tiposDocumentoConcepto no modificar');
INSERT IGNORE INTO `accelog_perfiles_op` (`idperfil`, `idopcion`)
VALUES
  (2, 'catalog_285xe'),(3, 'catalog_285xe'),(2, 'catalog_309xe'),(3, 'catalog_309xe'),(2,'catalog_280xe'),(3,'catalog_280xe');
 
-- new al 14 de julio de 2016
INSERT IGNORE INTO `tipo_proveedor` (`idtipo`, `tipo`)
VALUES
  (4, 'Banco');
INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1689, 78, 'cuentacliente', 'Cuenta Cliente', 'Cuenta Cliente', 5, 'int', 'NA', '', 0, '-1', 29, 0),
  (1690, 78, 'beneficiario_pagador', 'Beneficiario/Pagador', 'Beneficiario/Pagador', 0, 'boolean', 'NA', '', 0, '', 28, 0);
INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1689, 'S', 'cont_accounts', 'account_id', 'manual_code , description');

INSERT  IGNORE INTO `accelog_opciones` (`idopcion`, `nombre`)
VALUES
  ('catalog_280xa', 'bco_cuentas_bancarias no agregar'),
  ('catalog_280xe', 'bco_cuentas_bancarias no eliminar'),
  ('catalog_280xm', 'bco_cuentas_bancarias no modificar');

  INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2320, 280, 'activo', 'Activo', 'Activo', 0, 'boolean', '-1', '', 0, '', 16, 0),
  (2321, 280, 'cancelada', 'Cuenta Cancelada', 'Cancelada/Activa', 0, 'boolean', '0', '', 0, '', 15, 0);



-- retenciones

CREATE TABLE `bco_pendiente_timbrar` (
  `idRetencion` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `claveComplemento` varchar(2) NOT NULL DEFAULT '',
  `fecha` datetime NOT NULL,
  `idPrv` int(11) NOT NULL,
  `referencia` varchar(100) NOT NULL DEFAULT '',
  `mesInicial` int(11) NOT NULL,
  `mesFinal` int(11) NOT NULL,
  `ejercicio` int(11) NOT NULL,
  `totalOperaciones` double(100,6) NOT NULL,
  `totalGravado` double(100,6) NOT NULL,
  `totalExento` double(100,6) NOT NULL,
  `totalRetenciones` double(100,6) NOT NULL,
  `trackID` int(100) NOT NULL DEFAULT '0',
  `timbrado` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-si,0-no',
  `selloSAT` varchar(500) DEFAULT NULL,
  `selloCFD` varchar(500) DEFAULT NULL,
  `fechaTimbrado` varchar(100) DEFAULT NULL,
  `UUID` varchar(100) DEFAULT NULL,
  `nombreXML` varchar(500) DEFAULT NULL,
  `idComprobante` varchar(100) DEFAULT NULL,
  `cancelada` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-si,0-no',
  `idDocumento` int(11) DEFAULT NULL,
  PRIMARY KEY (`idRetencion`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `bco_impuestos_retencion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `importBase` double(100,6) DEFAULT NULL,
  `tipoImpuesto` varchar(2) NOT NULL DEFAULT '' COMMENT '01-ISR,02-IVA,03-IEPS',
  `impuestoRetenido` double(100,6) NOT NULL,
  `tipoPago` varchar(25) NOT NULL DEFAULT '',
  `idRetencion` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `bco_tipo_dividendo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) DEFAULT NULL,
  `clave` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `bco_tipo_dividendo` (`id`, `nombre`, `clave`)
VALUES
  (1, 'Proviene de CUFIN', '01'),
  (2, 'No proviene de CUFIN', '02'),
  (3, 'Reembolso o reducción de capital', '03'),
  (4, 'Liquidación de la persona moral', '04'),
  (5, 'CUFINRE', '05'),
  (6, 'Proviene de CUFIN al 31 de diciembre 2013.', '06');

  CREATE TABLE `bco_tipo_contribuyente` (
  `clave` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`clave`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

  INSERT IGNORE INTO `bco_tipo_contribuyente` (`clave`, `nombre`)
VALUES
  (1, 'Artistas, deportistas y espectáculos públicos '),
  (2, 'Otras personas físicas'),
  (3, 'Persona moral'),
  (4, 'Fideicomiso'),
  (5, 'Asociación en participación'),
  (6, 'Organizaciones Internacionales o de gobierno'),
  (7, 'Organizaciones exentas'),
  (8, 'Agentes pagadores'),
  (9, 'Otros');
/*
  CREATE TABLE `bco_paises` (
  `idpais` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pais` varchar(200) DEFAULT NULL,
  `clave` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`idpais`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

  INSERT IGNORE INTO `bco_paises` (`idpais`, `pais`, `clave`)
VALUES
  (1, 'AFGANISTAN (EMIRATO ISLAMICO DE)', 'AF'),
  (2, 'ALBANIA (REPUBLICA DE)', 'AL'),
  (3, 'ALEMANIA (REPUBLICA FEDERAL DE)', 'DE'),
  (4, 'ANDORRA (PRINCIPADO DE)', 'AD'),
  (5, 'ANGOLA (REPUBLICA DE)', 'AO'),
  (6, 'ANGUILA', 'AI'),
  (7, 'ANTARTIDA', 'AQ'),
  (8, 'ANTIGUA Y BARBUDA (COMUNIDAD BRITANICA DE NACIONES)', 'AG'),
  (9, 'ANTILLAS NEERLANDESAS (TERRITORIO HOLANDES DE ULTRAMAR)', 'AN'),
  (10, 'ARABIA SAUDITA (REINO DE)', 'SA'),
  (11, 'ARGELIA (REPUBLICA DEMOCRATICA Y POPULAR DE)', 'DZ'),
  (12, 'ARGENTINA (REPUBLICA)', 'AR'),
  (13, 'ARMENIA (REPUBLICA DE)', 'AM'),
  (14, 'ARUBA (TERRITORIO HOLANDES DE ULTRAMAR)', 'AW'),
  (15, 'AUSTRALIA (COMUNIDAD DE)', 'AU'),
  (16, 'AUSTRIA (REPUBLICA DE)', 'AT'),
  (17, 'AZERBAIJAN (REPUBLICA AZERBAIJANI)', 'AZ'),
  (18, 'BAHAMAS (COMUNIDAD DE LAS)', 'BS'),
  (19, 'BAHREIN (ESTADO DE)', 'BH'),
  (20, 'BANGLADESH (REPUBLICA POPULAR DE)', 'BD'),
  (21, 'BARBADOS (COMUNIDAD BRITANICA DE NACIONES)', 'BB'),
  (22, 'BELGICA (REINO DE)', 'BE'),
  (23, 'BELICE', 'BZ'),
  (24, 'BENIN (REPUBLICA DE)', 'BJ'),
  (25, 'BERMUDAS', 'BM'),
  (26, 'BIELORRUSIA (REPUBLICA DE)', 'BY'),
  (27, 'BOLIVIA (REPUBLICA DE)', 'BO'),
  (28, 'BOSNIA Y HERZEGOVINA', 'BA'),
  (29, 'BOTSWANA (REPUBLICA DE)', 'BW'),
  (30, 'BRASIL (REPUBLICA FEDERATIVA DE)', 'BR'),
  (31, 'BRUNEI (ESTADO DE) (RESIDENCIA DE PAZ)', 'BN'),
  (32, 'BULGARIA (REPUBLICA DE)', 'BG'),
  (33, 'BURKINA FASO', 'BF'),
  (34, 'BURUNDI (REPUBLICA DE)', 'BI'),
  (35, 'BUTAN (REINO DE)', 'BT'),
  (36, 'CABO VERDE (REPUBLICA DE)', 'CV'),
  (37, 'CHAD (REPUBLICA DEL)', 'TD'),
  (38, 'CAIMAN (ISLAS)', 'KY'),
  (39, 'CAMBOYA (REINO DE)', 'KH'),
  (40, 'CAMERUN (REPUBLICA DEL)', 'CM'),
  (41, 'CANADA', 'CA'),
  (42, 'CHILE (REPUBLICA DE)', 'CL'),
  (43, 'CHINA (REPUBLICA POPULAR)', 'CN'),
  (44, 'CHIPRE (REPUBLICA DE)', 'CY'),
  (45, 'CIUDAD DEL VATICANO (ESTADO DE LA)', 'VA'),
  (46, 'COCOS (KEELING, ISLAS AUSTRALIANAS)', 'CC'),
  (47, 'COLOMBIA (REPUBLICA DE)', 'CO'),
  (48, 'COMORAS (ISLAS)', 'KM'),
  (49, 'CONGO (REPUBLICA DEL)', 'CG'),
  (50, 'COOK (ISLAS)', 'CK'),
  (51, 'COREA (REPUBLICA POPULAR DEMOCRATICA DE) (COREA DEL NORTE)', 'KP'),
  (52, 'COREA (REPUBLICA DE) (COREA DEL SUR)', 'KR'),
  (53, 'COSTA DE MARFIL (REPUBLICA DE LA)', 'CI'),
  (54, 'COSTA RICA (REPUBLICA DE)', 'CR'),
  (55, 'CROACIA (REPUBLICA DE)', 'HR'),
  (56, 'CUBA (REPUBLICA DE)', 'CU'),
  (57, 'DINAMARCA (REINO DE)', 'DK'),
  (58, 'DJIBOUTI (REPUBLICA DE)', 'DJ'),
  (59, 'DOMINICA (COMUNIDAD DE)', 'DM'),
  (60, 'ECUADOR (REPUBLICA DEL)', 'EC'),
  (61, 'EGIPTO (REPUBLICA ARABE DE)', 'EG'),
  (62, 'EL SALVADOR (REPUBLICA DE)', 'SV'),
  (63, 'EMIRATOS ARABES UNIDOS', 'AE'),
  (64, 'ERITREA (ESTADO DE)', 'ER'),
  (65, 'ESLOVENIA (REPUBLICA DE)', 'SI'),
  (66, 'ESPAÑA (REINO DE)', 'ES'),
  (67, 'ESTADO FEDERADO DE MICRONESIA', 'FM'),
  (68, 'ESTADOS UNIDOS DE AMERICA', 'US'),
  (69, 'ESTONIA (REPUBLICA DE)', 'EE'),
  (70, 'ETIOPIA (REPUBLICA DEMOCRATICA FEDERAL)', 'ET'),
  (71, 'FIDJI (REPUBLICA DE)', 'FJ'),
  (72, 'FILIPINAS (REPUBLICA DE LAS)', 'PH'),
  (73, 'FINLANDIA (REPUBLICA DE)', 'FI'),
  (74, 'FRANCIA (REPUBLICA FRANCESA)', 'FR'),
  (75, 'GABONESA (REPUBLICA)', 'GA'),
  (76, 'GAMBIA (REPUBLICA DE LA)', 'GM'),
  (77, 'GEORGIA (REPUBLICA DE)', 'GE'),
  (78, 'GHANA (REPUBLICA DE)', 'GH'),
  (79, 'GIBRALTAR (R.U.)', 'GI'),
  (80, 'GRANADA', 'GD'),
  (81, 'GRECIA (REPUBLICA HELENICA)', 'GR'),
  (82, 'GROENLANDIA (DINAMARCA)', 'GL'),
  (83, 'GUADALUPE (DEPARTAMENTO DE)', 'GP'),
  (84, 'GUAM (E.U.A.)', 'GU'),
  (85, 'GUATEMALA (REPUBLICA DE)', 'GT'),
  (86, 'GUERNSEY', 'GG'),
  (87, 'GUINEA-BISSAU (REPUBLICA DE)', 'GW'),
  (88, 'GUINEA ECUATORIAL (REPUBLICA DE)', 'GQ'),
  (89, 'GUINEA (REPUBLICA DE)', 'GN'),
  (90, 'GUYANA FRANCESA', 'GF'),
  (91, 'GUYANA (REPUBLICA COOPERATIVA DE)', 'GY'),
  (92, 'HAITI (REPUBLICA DE)', 'HT'),
  (93, 'HONDURAS (REPUBLICA DE)', 'HN'),
  (94, 'HONG KONG (REGION ADMINISTRATIVA ESPECIAL DE LA REPUBLICA)', 'HK'),
  (95, 'HUNGRIA (REPUBLICA DE)', 'HU'),
  (96, 'INDIA (REPUBLICA DE)', 'IN'),
  (97, 'INDONESIA (REPUBLICA DE)', 'ID'),
  (98, 'IRAK (REPUBLICA DE)', 'IQ'),
  (99, 'IRAN (REPUBLICA ISLAMICA DEL)', 'IR'),
  (100, 'IRLANDA (REPUBLICA DE)', 'IE'),
  (101, 'ISLANDIA (REPUBLICA DE)', 'IS'),
  (102, 'ISLA BOUVET', 'BV'),
  (103, 'ISLA DE MAN', 'IM'),
  (104, 'ISLAS ALAND', 'AX'),
  (105, 'ISLAS FEROE', 'FO'),
  (106, 'ISLAS GEORGIA Y SANDWICH DEL SUR', 'GS'),
  (107, 'ISLAS HEARD Y MCDONALD', 'HM'),
  (108, 'ISLAS MALVINAS (R.U.)', 'FK'),
  (109, 'ISLAS MARIANAS SEPTENTRIONALES', 'MP'),
  (110, 'ISLAS MARSHALL', 'MH'),
  (111, 'ISLAS MENORES DE ULTRAMAR DE ESTADOS UNIDOS DE AMERICA', 'UM'),
  (112, 'ISLAS SALOMON (COMUNIDAD BRITANICA DE NACIONES)', 'SB'),
  (113, 'ISLAS SVALBARD Y JAN MAYEN (NORUEGA)', 'SJ'),
  (114, 'ISLAS TOKELAU', 'TK'),
  (115, 'ISLAS WALLIS Y FUTUNA', 'WF'),
  (116, 'ISRAEL (ESTADO DE)', 'IL'),
  (117, 'ITALIA (REPUBLICA ITALIANA)', 'IT'),
  (118, 'JAMAICA', 'JM'),
  (119, 'JAPON', 'JP'),
  (120, 'JERSEY', 'JE'),
  (121, 'JORDANIA (REINO HACHEMITA DE)', 'JO'),
  (122, 'KAZAKHSTAN (REPUBLICA DE) ', 'KZ'),
  (123, 'KENYA (REPUBLICA DE)', 'KE'),
  (124, 'KIRIBATI (REPUBLICA DE)', 'KI'),
  (125, 'KUWAIT (ESTADO DE)', 'KW'),
  (126, 'KYRGYZSTAN (REPUBLICA KIRGYZIA)', 'KG'),
  (127, 'LESOTHO (REINO DE)', 'LS'),
  (128, 'LETONIA (REPUBLICA DE)', 'LV'),
  (129, 'LIBANO (REPUBLICA DE)', 'LB'),
  (130, 'LIBERIA (REPUBLICA DE)', 'LR'),
  (131, 'LIBIA (JAMAHIRIYA LIBIA ARABE POPULAR SOCIALISTA)', 'LY'),
  (132, 'LIECHTENSTEIN (PRINCIPADO DE)', 'LI'),
  (133, 'LITUANIA (REPUBLICA DE)', 'LT'),
  (134, 'LUXEMBURGO (GRAN DUCADO DE)', 'LU'),
  (135, 'MACAO', 'MO'),
  (136, 'MACEDONIA (ANTIGUA REPUBLICA YUGOSLAVA DE)', 'MK'),
  (137, 'MADAGASCAR (REPUBLICA DE)', 'MG'),
  (138, 'MALASIA', 'MY'),
  (139, 'MALAWI (REPUBLICA DE)', 'MW'),
  (140, 'MALDIVAS (REPUBLICA DE)', 'MV'),
  (141, 'MALI (REPUBLICA DE)', 'ML'),
  (142, 'MALTA (REPUBLICA DE)', 'MT'),
  (143, 'MARRUECOS (REINO DE)', 'MA'),
  (144, 'MARTINICA (DEPARTAMENTO DE) (FRANCIA)', 'MQ'),
  (145, 'MAURICIO (REPUBLICA DE)', 'MU'),
  (146, 'MAURITANIA (REPUBLICA ISLAMICA DE)', 'MR'),
  (147, 'MAYOTTE', 'YT'),
  (148, 'MEXICO (ESTADOS UNIDOS MEXICANOS)', 'MX'),
  (149, 'MOLDAVIA (REPUBLICA DE)', 'MD'),
  (150, 'MONACO (PRINCIPADO DE)', 'MC'),
  (151, 'MONGOLIA', 'MN'),
  (152, 'MONSERRAT (ISLA)', 'MS'),
  (153, 'MONTENEGRO', 'ME'),
  (154, 'MOZAMBIQUE (REPUBLICA DE)', 'MZ'),
  (155, 'MYANMAR (UNION DE)', 'MM'),
  (156, 'NAMIBIA (REPUBLICA DE)', 'NA'),
  (157, 'NAURU', 'NR'),
  (158, 'NAVIDAD (CHRISTMAS) (ISLAS)', 'CX'),
  (159, 'NEPAL (REINO DE)', 'NP'),
  (160, 'NICARAGUA (REPUBLICA DE)', 'NI'),
  (161, 'NIGER (REPUBLICA DE)', 'NE'),
  (162, 'NIGERIA (REPUBLICA FEDERAL DE)', 'NG'),
  (163, 'NIVE (ISLA)', 'NU'),
  (164, 'NORFOLK (ISLA)', 'NF'),
  (165, 'NORUEGA (REINO DE)', 'NO'),
  (166, 'NUEVA CALEDONIA (TERRITORIO FRANCES DE ULTRAMAR)', 'NC'),
  (167, 'NUEVA ZELANDIA', 'NZ'),
  (168, 'OMAN (SULTANATO DE)', 'OM'),
  (169, 'PACIFICO, ISLAS DEL (ADMON. E.U.A.)', 'PIK'),
  (170, 'PAISES BAJOS (REINO DE LOS) (HOLANDA)', 'NL'),
  (171, 'PAKISTAN (REPUBLICA ISLAMICA DE)', 'PK'),
  (172, 'PALAU (REPUBLICA DE)', 'PW'),
  (173, 'PALESTINA', 'PS'),
  (174, 'PANAMA (REPUBLICA DE)', 'PA'),
  (175, 'PAPUA NUEVA GUINEA (ESTADO INDEPENDIENTE DE)', 'PG'),
  (176, 'PARAGUAY (REPUBLICA DEL)', 'PY'),
  (177, 'PERU (REPUBLICA DEL)', 'PE'),
  (178, 'PITCAIRNS (ISLAS DEPENDENCIA BRITANICA)', 'PN'),
  (179, 'POLINESIA FRANCESA', 'PF'),
  (180, 'POLONIA (REPUBLICA DE)', 'PL'),
  (181, 'PORTUGAL (REPUBLICA PORTUGUESA)', 'PT'),
  (182, 'PUERTO RICO (ESTADO LIBRE ASOCIADO DE LA COMUNIDAD DE)', 'PR'),
  (183, 'QATAR (ESTADO DE)', 'QA'),
  (184, 'REINO UNIDO DE LA GRAN BRETAÑA E IRLANDA DEL NORTE', 'GB'),
  (185, 'REPUBLICA CHECA', 'CZ'),
  (186, 'REPUBLICA CENTROAFRICANA', 'CF'),
  (187, 'REPUBLICA DEMOCRATICA POPULAR LAOS', 'LA'),
  (188, 'REPUBLICA DE SERBIA', 'RS'),
  (189, 'REPUBLICA DOMINICANA', 'DO'),
  (190, 'REPUBLICA ESLOVACA', 'SK'),
  (191, 'REPUBLICA POPULAR DEL CONGO', 'CD'),
  (192, 'REPUBLICA RUANDESA', 'RW'),
  (193, 'REUNION (DEPARTAMENTO DE LA) (FRANCIA)', 'RE'),
  (194, 'RUMANIA', 'RO'),
  (195, 'RUSIA (FEDERACION RUSA)', 'RU'),
  (196, 'SAHARA OCCIDENTAL (REPUBLICA ARABE SAHARAVI DEMOCRATICA)', 'EH'),
  (197, 'SAMOA (ESTADO INDEPENDIENTE DE)', 'WS'),
  (198, 'SAMOA AMERICANA', 'AS'),
  (199, 'SAN BARTOLOME', 'BL'),
  (200, 'SAN CRISTOBAL Y NIEVES (FEDERACION DE) (SAN KITTS-NEVIS)', 'KN'),
  (201, 'SAN MARINO (SERENISIMA REPUBLICA DE)', 'SM'),
  (202, 'SAN MARTIN', 'MF'),
  (203, 'SAN PEDRO Y MIQUELON', 'PM'),
  (204, 'SAN VICENTE Y LAS GRANADINAS', 'VC'),
  (205, 'SANTA ELENA', 'SH'),
  (206, 'SANTA LUCIA', 'LC'),
  (207, 'SANTO TOME Y PRINCIPE (REPUBLICA DEMOCRATICA DE)', 'ST'),
  (208, 'SENEGAL (REPUBLICA DEL)', 'SN'),
  (209, 'SEYCHELLES (REPUBLICA DE LAS)', 'SC'),
  (210, 'SIERRA LEONA (REPUBLICA DE)', 'SL'),
  (211, 'SINGAPUR (REPUBLICA DE)', 'SG'),
  (212, 'SIRIA (REPUBLICA ARABE)', 'SY'),
  (213, 'SOMALIA', 'SO'),
  (214, 'SRI LANKA (REPUBLICA DEMOCRATICA SOCIALISTA DE)', 'LK'),
  (215, 'SUDAFRICA (REPUBLICA DE)', 'ZA'),
  (216, 'SUDAN (REPUBLICA DEL)', 'SD'),
  (217, 'SUECIA (REINO DE)', 'SE'),
  (218, 'SUIZA (CONFEDERACION)', 'CH'),
  (219, 'SURINAME (REPUBLICA DE)', 'SR'),
  (220, 'SWAZILANDIA (REINO DE)', 'SZ'),
  (221, 'TADJIKISTAN (REPUBLICA DE)', 'TJ'),
  (222, 'TAILANDIA (REINO DE)', 'TH'),
  (223, 'TAIWAN (REPUBLICA DE CHINA)', 'TW'),
  (224, 'TANZANIA (REPUBLICA UNIDA DE)', 'TZ'),
  (225, 'TERRITORIOS BRITANICOS DEL OCEANO INDICO', 'IO'),
  (226, 'TERRITORIOS FRANCESES, AUSTRALES Y ANTARTICOS', 'TF'),
  (227, 'TIMOR ORIENTAL', 'TL'),
  (228, 'TOGO (REPUBLICA TOGOLESA)', 'TG'),
  (229, 'TONGA (REINO DE)', 'TO'),
  (230, 'TRINIDAD Y TOBAGO (REPUBLICA DE)', 'TT'),
  (231, 'TUNEZ (REPUBLICA DE)', 'TN'),
  (232, 'TURCAS Y CAICOS (ISLAS)', 'TC'),
  (233, 'TURKMENISTAN (REPUBLICA DE)', 'TM'),
  (234, 'TURQUIA (REPUBLICA DE)', 'TR'),
  (235, 'TUVALU (COMUNIDAD BRITANICA DE NACIONES)', 'TV'),
  (236, 'UCRANIA', 'UA'),
  (237, 'UGANDA (REPUBLICA DE)', 'UG'),
  (238, 'URUGUAY (REPUBLICA ORIENTAL DEL)', 'UY'),
  (239, 'UZBEJISTAN (REPUBLICA DE)', 'UZ'),
  (240, 'VANUATU', 'VU'),
  (241, 'VENEZUELA (REPUBLICA DE)', 'VE'),
  (242, 'VIETNAM (REPUBLICA SOCIALISTA DE)', 'VN'),
  (243, 'VIRGENES. ISLAS (BRITANICAS)', 'VG'),
  (244, 'VIRGENES. ISLAS (NORTEAMERICANAS)', 'VI'),
  (245, 'YEMEN (REPUBLICA DE)', 'YE'),
  (246, 'ZAMBIA (REPUBLICA DE)', 'ZM'),
  (247, 'ZIMBABWE (REPUBLICA DE)', 'ZW');
*/
  


alter table nomi_empleados
add activo tinyint(1) DEFAULT -1;

alter table mrp_proveedor
add beneficiario_pagador tinyint(1) NOT NULL DEFAULT 0 after idtipo,
add cuentacliente INT(5) NOT NULL DEFAULT 0 after beneficiario_pagador;

alter table app_pagos
add ref_bancos varchar(30) NULL after origen;




-- Como bancos incluye administra si no tiene estas tablas truena -_-
CREATE TABLE `app_pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cobrar_pagar` tinyint(1) NOT NULL,
  `id_prov_cli` int(11) NOT NULL,
  `cargo` double NOT NULL DEFAULT '0',
  `abono` double NOT NULL DEFAULT '0',
  `fecha_pago` datetime NOT NULL,
  `concepto` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_forma_pago` int(11) NOT NULL,
  `id_moneda` int(1) NOT NULL DEFAULT '1',
  `tipo_cambio` double NOT NULL DEFAULT '1',
  `origen` int(1) NOT NULL DEFAULT '0',
  `ref_bancos` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pagos_relacion'
CREATE TABLE `app_pagos_relacion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pago` int(11) DEFAULT NULL,
  `id_tipo` int(11) DEFAULT NULL,
  `id_documento` int(11) DEFAULT NULL,
  `cargo` double NOT NULL DEFAULT '0',
  `abono` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



/*

INSERT IGNORE INTO `accelog_categorias` (`idcategoria`, `nombre`, `icono`, `orden`)
VALUES
  (1054, 'Conciliacion Bancaria', 0, 11);
  
  
update accelog_menu set idmenupadre=0, idcategoria=1054 where idmenu=1907;
update accelog_menu set idmenupadre=0, idcategoria=1054 where idmenu=1808;
update accelog_menu set idmenupadre=0, idcategoria=1054 where idmenu=1911;
update accelog_menu set idmenupadre=0, idcategoria=1054 where idmenu=1915;
-- elimina el menu de conciliacion de bancos y acontia
delete from accelog_perfiles_me where idmenu=1807;
delete from accelog_perfiles_me where idmenu=1908;



-- menus de configuracion aplica cuando no se tiene ninguno de los modulos 
-- con menu nuevo de configuracion
-- INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
-- VALUES
--   (2, 2137),(2, 2138),(2, 2139),(2, 2140),(2, 2141),(2, 2147),
--  (3, 2137),(3, 2138),(3, 2139),(3, 2140),(3, 2141),(3, 2147);

*/




