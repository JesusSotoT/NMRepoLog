
/*TABLA EMPLEADOS*/
CREATE TABLE `nomi_empleados` (
  `idEmpleado` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fechaAlta` date DEFAULT NULL,
  `apellidoPaterno` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apellidoMaterno` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombreEmpleado` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `salario` double NOT NULL,
  `idzona` int(11) NOT NULL,
  `idFormapago` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nss` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `idEstadoCivil` int(11) NOT NULL,
  `idsexo` int(11) NOT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `idestado` int(11) NOT NULL,
  `idmunicipio` int(11) NOT NULL,
  `rfc` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `curp` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `poblacion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idestadosat` int(11) DEFAULT NULL,
  `cp` int(11) DEFAULT NULL,
  `telefono` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `idbanco` int(11) NOT NULL,
  `numeroCuenta` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `claveinterbancaria` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nomi_empleado_clasif` int(11) DEFAULT NULL,
  `id_tipo_comision` int(11) DEFAULT NULL,
  `comision` double DEFAULT NULL,
  `id_clasificacion` int(11) DEFAULT NULL,
  `id_area_empleado` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1' COMMENT '1-alta,2,baja,3-reingreso',
  `estatus_tran` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idtipocontrato` int(11) DEFAULT NULL COMMENT 'nomi_tipocontrato',
  `idtipop` int(11) DEFAULT NULL COMMENT 'nomi_tiposperiodos',
  `idbase` int(11) DEFAULT NULL COMMENT 'nomi_base_cotizacion',
  `sbcfija` double(100,2) DEFAULT NULL COMMENT 'SDI',
  `sbcvariable` double(100,2) DEFAULT NULL,
  `sbctopado` double(100,2) DEFAULT NULL,
  `idDep` int(11) DEFAULT NULL COMMENT 'nomi_departamento',
  `idPuesto` int(11) DEFAULT NULL COMMENT 'nomi_puesto',
  `idtipoempleado` int(11) DEFAULT NULL COMMENT 'nomi_tipo_empleado',
  `idbasepago` int(11) DEFAULT NULL COMMENT 'nomi_base_pago',
  `idturno` int(11) DEFAULT NULL COMMENT 'nomi_turno',
  `idregimencontrato` int(11) DEFAULT NULL COMMENT 'nomi_regimencontratacion',
  `fonacot` int(11) DEFAULT NULL,
  `afore` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idregistrop` int(11) DEFAULT NULL COMMENT 'nomi_registropatronal',
  `umf` int(11) DEFAULT NULL,
  `avisosimss` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '1-alta, 2-baja, 3-modif salario',
  `horasext1` double(100,2) DEFAULT NULL,
  `horasext2` double(100,2) DEFAULT NULL,
  `horasext3` double(100,2) DEFAULT NULL,
  `diastrabajados` double(100,2) DEFAULT NULL,
  `diaspagados` double(100,2) DEFAULT NULL,
  `diascotizados` double(100,2) DEFAULT NULL,
  `ausencias` double(100,2) DEFAULT NULL,
  `incapacidades` double(100,2) DEFAULT NULL,
  `vacaciones` double(100,2) DEFAULT NULL,
  `septimosprop` double(100,2) DEFAULT NULL,
  `salariovariable` double(100,2) DEFAULT NULL,
  `fechavariable` date DEFAULT NULL,
  `fechadiario` date DEFAULT NULL,
  `salariopromedio` double(100,2) DEFAULT NULL,
  `fechapromedio` date DEFAULT NULL,
  `fechaintegrado` date DEFAULT NULL,
  `salarioliquidacion` double(100,2) DEFAULT NULL,
  `salarioajusteneto` double(100,2) DEFAULT NULL,
  `imagen` blob,
  `alimento` double DEFAULT NULL,
  PRIMARY KEY (`idEmpleado`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*TABLA PUESTO*/
CREATE TABLE `nomi_puesto` (
  `idPuesto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `descripcion` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `idclaveriesgopuesto` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idPuesto`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;




CREATE TABLE `nomi_base_cotizacion` (
  `idbase` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idbase`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `nomi_base_cotizacion` (`idbase`, `nombre`)
VALUES
  (1, 'Fijo'),
  (2, 'Variable'),
  (3, 'Mixto');


/*CATALOGO ESTRUCTURAS* NOMI_PUESTO*/
INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (413, 'nomi_puesto', 'Puesto', '2017-01-13 10:12:34', '2017-01-13 10:14:19', 'A', 0, '', 0, '');


/* CATALOGO CAMPOS* NOMI_PUESTO*/

INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2380, 413, 'idPuesto', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (2381, 413, 'nombre', 'Nombre', 'Nombre Puesto', 100, 'varchar', 'NA', '', -1, '', 1, 0),
  (2382, 413, 'descripcion', 'Descripción del puesto', 'Descripción del puesto', 500, 'varchar', 'NA', '', 0, '', 2, 0),
  (2578, 413, 'idclaveriesgopuesto', 'Riesgo puesto', 'Riesgo puesto', 11, 'int', 'NA', '', 0, '-1', 3, 0);

/*CATALOGO DEPENDENCIAS* NOMI_PUESTO*/

INSERT  ignore INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (2578, 'S', 'nomi_riesgopuesto', 'idclaveriesgopuesto', ' descripcion ');

INSERT  ignore INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2219, 'Puesto', 2208, '../catalog/gestor.php?idestructura=413&ticket=testing', 1055, 0, 7, 0);
INSERT ignore INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
    (2393, 'S', 'nomi_ajustedias', 'idajuste', ' nombre '),
    (2398, 'S', 'nomi_periodicidad', 'idperiodicidad', ' descripcion , clave '),
    (2402, 'S', 'nomi_jornada', 'idjornada', ' descripcion , clave '),
    (2418, 'S', 'nomi_registropatronal', 'idregistrop', ' registro '),
    (2422, 'S', 'cont_accounts', 'account_id', ' manual_code , descripcion '),
    (2432, 'S', 'nomi_deducciones', 'idAgrupador', ' clave , descripcion '),
    (2433, 'S', 'nomi_tipoconcepto', 'idtipo', ' tipo '),
    (2449, 'S', 'estados', 'idestado', ' estado '),
    (2451, 'S', 'nomi_riesgotrabajo', 'idclaveriesgotrabajo', ' idclaveriesgotrabajo , descripcion '),
    (2452, 'S', 'nomi_fraccionriesgocatalogo', 'idfraccion', ' descripcion , fraccion '),
    (2463, 'S', 'nomi_tipohoras', 'idhora', ' descripcion , clave '),
    (2464, 'S', 'forma_pago', 'idFormapago', ' nombre , claveSat '),
    (2470, 'S', 'nomi_tipo_acumulado', 'idtipoacumulado', ' nombre ');

/*TABLA NOMI_REGISTROPATRONAL*/
CREATE TABLE `nomi_registropatronal` (
  `idregistrop` int(11) NOT NULL AUTO_INCREMENT,
  `registro` varchar(13) CHARACTER SET latin1 NOT NULL,
  `localidad` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `idestado` int(11) NOT NULL,
  `cp` varchar(6) CHARACTER SET latin1 NOT NULL,
  `idclaveriesgotrabajo` int(11) DEFAULT NULL,
  `idfraccion` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idregistrop`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


  /*CATALOGO ESTRUCTURAS REGISTRO PATRONAL*/

  INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (427, 'nomi_registropatronal', 'Registros Patronales', '2017-01-17 09:51:41', '2017-01-17 10:46:13', 'A', 0, '', 0, '../../modulos/nominas/js/antesregistropatronal.php');


/*CATALOGO CAMPOS REGISTRO PATRONAL*/

INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2446, 427, 'idregistrop', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (2447, 427, 'registro', 'Registro patronal del IMSS', 'Registro patronal del IMSS', 13, 'varchar', 'NA', '', -1, '', 1, 0),
  (2448, 427, 'localidad', 'Localidad', 'Localidad', 50, 'varchar', 'NA', '', 0, '', 2, 0),
  (2449, 427, 'idestado', 'Entidad Federativa', 'Entidad Federativa', 11, 'int', 'NA', '', -1, '-1', 3, 0),
  (2450, 427, 'cp', 'Código postal', 'Código postal', 6, 'varchar', 'NA', '', -1, '', 4, 0),
  (2451, 427, 'idclaveriesgotrabajo', 'Clase de riesgo de trabajo', 'Clase de riesgo de trabajo', 11, 'int', 'NA', '', 0, '-1', 5, 0),
  (2452, 427, 'idfraccion', 'Fracción de riesgo de trabajo', 'Fracción de riesgo de trabajo', 11, 'int', 'NA', '', 0, '-1', 6, 0),
  (2475, 427, 'activo', 'Activo', 'Activo', 0, 'boolean', '1', '', 0, '', 7, 0);


  /*CATALOGO DEPENDENCIAS REGISTRO PATRONAL*/

INSERT INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (2449, 'S', 'estados', 'idestado', ' estado ');

INSERT INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (2451, 'S', 'nomi_riesgotrabajo', 'idclaveriesgotrabajo', ' descripcion ');

INSERT INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (2452, 'S', 'nomi_fraccionriesgocatalogo', 'idfraccion', ' descripcion , fraccion ');
  
CREATE TABLE `nomi_configuracion` (
  `idconfnomi` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fechainicio` date DEFAULT NULL,
  `idregfiscal` int(11) DEFAULT NULL,
  `factordeduexent` double(100,2) DEFAULT NULL COMMENT 'factor no deducible por ingresos exentos',
  `idregistrop` int(11) DEFAULT NULL,
  `reginfonavit` varchar(50) DEFAULT NULL,
  `centrotrabajofonacot` int(11) DEFAULT NULL,
  `regss` varchar(50) DEFAULT NULL,
  `regcomisionmixta` varchar(50) DEFAULT NULL,
  `periodoanteriores` int(11) DEFAULT NULL COMMENT '1-si,0-no modificar',
  `periodosfuturos` int(11) DEFAULT NULL COMMENT '1-si,0-no modificar',
  `ptu` int(11) DEFAULT NULL,
  `aguinaldo` int(11) DEFAULT NULL,
  `primavac` int(11) DEFAULT NULL,
  `vactiempo` int(11) DEFAULT NULL,
  `calculoinvertido` int(11) DEFAULT NULL,
  `emitirsellos` int(11) DEFAULT NULL COMMENT '1-si,0-no',
  `idzona` int(11) DEFAULT NULL,
  `curp` varchar(18) DEFAULT NULL,
  `idtipop` int(11) NOT NULL,
  PRIMARY KEY (`idconfnomi`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_configuracion` (`idconfnomi`, `fechainicio`, `idregfiscal`, `factordeduexent`, `idregistrop`, `reginfonavit`, `centrotrabajofonacot`, `regss`, `regcomisionmixta`, `periodoanteriores`, `periodosfuturos`, `ptu`, `aguinaldo`, `primavac`, `vactiempo`, `calculoinvertido`, `emitirsellos`, `idzona`, `curp`, `idtipop`)
VALUES
  (1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);


/*TABLA PVT CONFIGURA FACTURACION*/

CREATE TABLE `pvt_configura_facturacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rfc` varchar(15) NOT NULL,
  `regimen` varchar(255) NOT NULL,
  `pais` varchar(45) NOT NULL,
  `razon_social` varchar(200) NOT NULL,
  `calle` varchar(200) NOT NULL,
  `num_ext` varchar(45) NOT NULL,
  `colonia` varchar(45) NOT NULL,
  `ciudad` varchar(45) NOT NULL,
  `municipio` varchar(45) NOT NULL,
  `estado` varchar(45) NOT NULL,
  `cp` varchar(45) NOT NULL,
  `cer` varchar(200) NOT NULL,
  `llave` varchar(200) NOT NULL,
  `clave` varchar(45) NOT NULL,
  `ticket_config` int(11) DEFAULT '1',
  `pac` int(1) DEFAULT '2',
  `fc_user` varchar(45) NOT NULL DEFAULT '',
  `fc_password` varchar(45) NOT NULL DEFAULT '',
  `lugar_exp` varchar(100) DEFAULT 'Mexico',
  `pass_ciec` varchar(32) DEFAULT '----',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


/*INSERT PVT CONFIGURA FACTURACION*/
INSERT INTO `pvt_configura_facturacion` (`id`, `rfc`, `regimen`, `pais`, `razon_social`, `calle`, `num_ext`, `colonia`, `ciudad`, `municipio`, `estado`, `cp`, `cer`, `llave`, `clave`, `ticket_config`, `pac`, `fc_user`, `fc_password`, `lugar_exp`, `pass_ciec`)
VALUES
  (1, 'AAA010101AAA', '1', 'MEXICO', 'Prueba SA DE CV', 'Av. Prueba', '289', 'La Prueba', 'GUADALAJARA', 'GUADALAJARA', 'JALISCO', '44470', 'CSD01_AAA010101AAA.cer', 'CSD01_AAA010101AAA.key', '12345678a', 1, 2, 'pruebasWS', 'pruebasWS', 'Guadalajara, Jalisco Mexico', 'cald3ron');


/*CATALOGO ESTRUCTURAS* PVT CONFIGURA FACTURACION*/
INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (234, 'pvt_configura_facturacion', 'Configuracion de Facturacion', '2014-04-03 18:31:25', '2014-04-03 21:59:44', 'A', 0, '../../modulos/SAT/despues_config.php', 0, '../../modulos/SAT/antes_config.php');

  /*CATALOGO CAMPOS*PVT CONFIGURA FACTURACION */
INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1312, 234, 'id', 'Id', 'Id', 0, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (1313, 234, 'rfc', 'RFC', 'RFC', 15, 'varchar', 'NA', '', -1, '', 0, 0),
  (1314, 234, 'regimen', 'Regimen Fiscal', 'Regimen Fiscal', 45, 'varchar', 'NA', '', -1, '', 0, 0),
  (1315, 234, 'pais', 'Pais', 'Pais', 45, 'varchar', 'NA', '', -1, '', 0, 0),
  (1316, 234, 'razon_social', 'Nombre o razon social', 'Nombre o razon social', 200, 'varchar', 'NA', '', -1, '', 0, 0),
  (1317, 234, 'calle', 'Domicilio', 'Domicilio', 200, 'varchar', 'NA', '', -1, '', 0, 0),
  (1318, 234, 'num_ext', 'Numero exterior', 'Numero exterior', 45, 'varchar', 'NA', '', -1, '', 0, 0),
  (1319, 234, 'colonia', 'Colonia', 'Colonia', 45, 'varchar', 'NA', '', -1, '', 0, 0),
  (1320, 234, 'ciudad', 'Ciudad', 'Ciudad', 45, 'varchar', 'NA', '', -1, '', 0, 0),
  (1321, 234, 'municipio', 'Municipio', 'Municipio', 45, 'varchar', 'NA', '', -1, '', 0, 0),
  (1322, 234, 'estado', 'Estado', 'Estado', 45, 'varchar', 'NA', '', -1, '', 0, 0),
  (1323, 234, 'cp', 'CP', 'CP', 45, 'varchar', 'NA', '', -1, '', 0, 0),
  (1329, 234, 'cer', 'Certificado (.cer)', 'Certificado (.cer)', 200, 'archivo', 'NA', '', 0, '', 0, 0),
  (1330, 234, 'llave', 'Llave (.key)', 'Llave (.key)', 200, 'archivo', 'NA', '', 0, '', 0, 0),
  (1331, 234, 'clave', 'Clave', 'Clave', 45, 'varchar', 'NA', '', -1, '', 0, 0),
  (2265, 234, 'ticket_config', 'Ticket', 'Codigo en Ticket', 11, 'int', 'NA', '', -1, '', 0, 0),
  (2332, 234, 'pac', 'Selecciona PAC', 'Selecciona PAC', 1, 'int', '1', '', -1, '', 1, 0),
  (2334, 234, 'fc_user', 'Usuario Formas continuas', 'Usuario Formas continuas', 45, 'varchar', 'NA', '', 0, '', 2, 0),
  (2335, 234, 'fc_password', 'Password Formas continuas', 'Password Formas continuas', 45, 'varchar', 'NA', '', 0, '', 3, 0),
  (2364, 234, 'lugar_exp', 'Lugar de expedicion', 'Lugar de expedicion', 100, 'varchar', 'Mexico', '', -1, '', 0, 0),
  (2563, 234, 'pass_ciec', 'Password Ciec', 'Password Ciec', 32, 'varchar', '----', '', -1, '#', 4, 0);


 /*TABLA NOMI_PERCEPCIONES*/

CREATE TABLE `nomi_percepciones` (
  `idAgrupador` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `descripcion` varchar(300) DEFAULT NULL,
  `account_id` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idAgrupador`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;


/*INSERT NOMI_PERCEPCIONES*/
INSERT INTO `nomi_percepciones` (`idAgrupador`, `clave`, `descripcion`, `account_id`)
VALUES
  (1, '001', 'Sueldos, Salarios Rayas y Jornales', -1),
  (2, '002', 'Gratificación Anual (Aguinaldo)', -1),
  (3, '003', 'Participación de los Trabajadores en las Utilidades PTU', -1),
  (4, '004', 'Reembolso de Gastos Médicos Dentales y Hospitalarios', -1),
  (5, '005', 'Fondo de Ahorro', -1),
  (6, '006', 'Caja de ahorro', -1),
  (7, '009', 'Contribuciones a Cargo del Trabajador Pagadas por el Patrón', -1),
  (8, '010', 'Premios por puntualidad', -1),
  (9, '011', 'Prima de Seguro de vida', -1),
  (10, '012', 'Seguro de Gastos Médicos Mayores', -1),
  (11, '013', 'Cuotas Sindicales Pagadas por el Patrón', -1),
  (12, '014', 'Subsidios por incapacidad', -1),
  (13, '015', 'Becas para trabajadores y/o hijos', -1),
  (14, '016', 'Otros', -1),
  (15, '017', 'Subsidio para el empleo', 101),
  (16, '019', 'Horas extra', -1),
  (17, '020', 'Prima dominical', -1),
  (18, '021', 'Prima vacacional', -1),
  (19, '022', 'Prima por antigüedad', -1),
  (20, '023', 'Pagos por separación', -1),
  (21, '024', 'Seguro de retiro', -1),
  (22, '025', 'Indemnizaciones', -1),
  (23, '026', 'Reembolso por funeral', -1),
  (24, '027', 'Cuotas de seguridad social pagadas por el patrón', -1),
  (25, '028', 'Comisiones', -1),
  (26, '029', 'Vales de despensa', -1),
  (27, '030', 'Vales de restaurante', -1),
  (28, '031', 'Vales de gasolina', -1),
  (29, '032', 'Vales de ropa', -1),
  (30, '033', 'Ayuda para renta', -1),
  (31, '034', 'Ayuda para artículos escolares', -1),
  (32, '035', 'Ayuda para anteojos', -1),
  (33, '036', 'Ayuda para transporte', -1),
  (34, '037', 'Ayuda para gastos de funeral', -1),
  (35, '038', 'Otros ingresos por salarios', -1),
  (36, '039', 'Jubilaciones, pensiones o haberes de retiro', -1),
  (37, '044', 'Jubilaciones, pensiones o haberes de retiro en parcialidades', -1),
  (38, '045', 'Ingresos en acciones o títulos valor que representan bienes', -1),
  (39, '046', 'Ingresos asimilados a salarios', -1),
  (40, '047', 'Alimentación', -1),
  (41, '048', 'Habitación', -1),
  (42, '049', 'Premios por asistencia', -1),
  (43, '050', 'Viáticos', -1),
  (44, 'OP001', 'Reintegro de ISR pagado en exeso (siempre que no haya sido enterado al SAT)', -1),
  (45, 'OP002', 'Subsidio para el empleado (efectivamente entregado al trabajador)', -1),
  (46, 'OP003', 'Viaticos (entregados al trabajador)', -1),
  (47, 'OP004', 'Aplicacion de saldo a favor por compensacion anual', -1),
  (48, 'OP999', 'Pagos distintos a los listados y que no deben considerarse como ingresos por sueldos, salarios o ingresos asimilados', -1);



  /*TABLA NOMI_DEDUCCIONES*/
  CREATE TABLE `nomi_deducciones` (
  `idAgrupador` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `descripcion` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `account_id` int(11) NOT NULL DEFAULT '-1' COMMENT 'cuenta contable',
  PRIMARY KEY (`idAgrupador`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

  /*INSERT NOMI_DEDUCCIONES*/

  INSERT INTO `nomi_deducciones` (`idAgrupador`, `clave`, `descripcion`, `account_id`)
VALUES
  (1, '001', 'Seguridad social ', -1),
  (2, '002', 'ISR', -1),
  (3, '003', 'Aportaciones a retiro, cesantía en edad avanzada y vejez', -1),
  (4, '004', 'Otros', -1),
  (5, '005', 'Aportaciones a Fondo de vivienda', -1),
  (6, '006', 'Descuento por incapacidad', -1),
  (7, '007', 'Pensión alimenticia', -1),
  (8, '008', 'Renta', -1),
  (9, '009', 'Préstamos provenientes del Fondo Nacional de la Vivienda para los Trabajadores', -1),
  (10, '010', 'Pago por crédito de vivienda', -1),
  (11, '011', 'Pago de abonos INFONACOT', -1),
  (12, '012', 'Anticipo de salarios', -1),
  (13, '013', 'Pagos hechos con exceso al trabajador', -1),
  (14, '014', 'Errores', -1),
  (15, '015', 'Pérdidas', -1),
  (16, '016', 'Averías', -1),
  (17, '017', 'Adquisición de artículos producidos por la empresa o establecimiento', -1),
  (18, '018', 'Cuotas para la constitución y fomento de sociedades cooperativas y de cajas de ahorro', -1),
  (19, '019', 'Cuotas sindicales', -1),
  (20, '020', 'Ausencia (Ausentismo)', -1),
  (21, '021', 'Cuotas obrero patronales', -1),
  (22, '022', 'Impuestos Locales', -1),
  (23, '023', 'Aportaciones voluntarias', -1),
  (24, '024', 'Ajuste en Gratificación Anual (Aguinaldo) Exento', -1),
  (25, '025', 'Ajuste en Gratificación Anual (Aguinaldo) Gravado', -1),
  (26, '026', 'Ajuste en Participación de los Trabajadores en las Utilidades PTU Exento', -1),
  (27, '027', 'Ajuste en Participación de los Trabajadores en las Utilidades PTU Gravado', -1),
  (28, '028', 'Ajuste en Reembolso de Gastos Médicos Dentales y Hospitalarios Exento', -1),
  (29, '029', 'Ajuste en Fondo de ahorro Exento', -1),
  (30, '030', 'Ajuste en Caja de ahorro Exento', -1),
  (31, '031', 'Ajuste en Contribuciones a Cargo del Trabajador Pagadas por el Patrón Exento', -1),
  (32, '032', 'Ajuste en Premios por puntualidad Gravado', -1),
  (33, '033', 'Ajuste en Prima de Seguro de vida Exento', -1),
  (34, '034', 'Ajuste en Seguro de Gastos Médicos Mayores Exento', -1),
  (35, '035', 'Ajuste en Cuotas Sindicales Pagadas por el Patrón Exento', -1),
  (36, '036', 'Ajuste en Subsidios por incapacidad Exento', -1),
  (37, '037', 'Ajuste en Becas para trabajadores y/o hijos Exento', -1),
  (38, '038', 'Ajuste en Horas extra Exento', -1),
  (39, '039', 'Ajuste en Horas extra Gravado', -1),
  (40, '040', 'Ajuste en Prima dominical Exento', -1),
  (41, '041', 'Ajuste en Prima dominical Gravado', -1),
  (42, '042', 'Ajuste en Prima vacacional Exento', -1),
  (43, '043', 'Ajuste en Prima vacacional Gravado', -1),
  (44, '044', 'Ajuste en Prima por antigüedad Exento', -1),
  (45, '045', 'Ajuste en Prima por antigüedad Gravado', -1),
  (46, '046', 'Ajuste en Pagos por separación Exento', -1),
  (47, '047', 'Ajuste en Pagos por separación Gravado', -1),
  (48, '048', 'Ajuste en Seguro de retiro Exento', -1),
  (49, '049', 'Ajuste en Indemnizaciones Exento', -1),
  (50, '050', 'Ajuste en Indemnizaciones Gravado', -1),
  (51, '051', 'Ajuste en Reembolso por funeral Exento', -1),
  (52, '052', 'Ajuste en Cuotas de seguridad social pagadas por el patrón Exento', -1),
  (53, '053', 'Ajuste en Comisiones Gravado', -1),
  (54, '054', 'Ajuste en Vales de despensa Exento', -1),
  (55, '055', 'Ajuste en Vales de restaurante Exento', -1),
  (56, '056', 'Ajuste en Vales de gasolina Exento', -1),
  (57, '057', 'Ajuste en Vales de ropa Exento', -1),
  (58, '058', 'Ajuste en Ayuda para renta Exento', -1),
  (59, '059', 'Ajuste en Ayuda para artículos escolares Exento', -1),
  (60, '060', 'Ajuste en Ayuda para anteojos Exento', -1),
  (61, '061', 'Ajuste en Ayuda para transporte Exento', -1),
  (62, '062', 'Ajuste en Ayuda para gastos de funeral Exento', -1),
  (63, '063', 'Ajuste en Otros ingresos por salarios Exento', -1),
  (64, '064', 'Ajuste en Otros ingresos por salarios Gravado', -1),
  (65, '065', 'Ajuste en Jubilaciones, pensiones o haberes de retiro Exento', -1),
  (66, '066', 'Ajuste en Jubilaciones, pensiones o haberes de retiro Gravado', -1),
  (67, '067', 'Ajuste en Pagos por separación Acumulable', -1),
  (68, '068', 'Ajuste en Pagos por separación No acumulable', -1),
  (69, '069', 'Ajuste en Jubilaciones, pensiones o haberes de retiro Acumulable', -1),
  (70, '070', 'Ajuste en Jubilaciones, pensiones o haberes de retiro No acumulable', -1),
  (71, '071', 'Ajuste en Subsidio para el empleo (efectivamente entregado al trabajador)', -1),
  (72, '072', 'Ajuste en Ingresos en acciones o títulos valor que representan bienes Exento', -1),
  (73, '073', 'Ajuste en Ingresos en acciones o títulos valor que representan bienes Gravado', -1),
  (74, '074', 'Ajuste en Alimentación Exento', -1),
  (75, '075', 'Ajuste en Alimentación Gravado', -1),
  (76, '076', 'Ajuste en Habitación Exento', -1),
  (77, '077', 'Ajuste en Habitación Gravado', -1),
  (78, '078', 'Ajuste en Premios por asistencia', -1),
  (79, '079', 'Ajuste en Pagos distintos a los listados y que no deben considerarse como ingreso por sueldos, salarios o ingresos asimilados', -1),
  (80, '080', 'Ajuste en Viáticos no comprobados', -1),
  (81, '081', 'Ajuste en Viáticos anticipados', -1),
  (82, '082', 'Ajuste en Fondo de ahorro Gravado', -1),
  (83, '083', 'Ajuste en Caja de ahorro Gravado', -1),
  (84, '084', 'Ajuste en Prima de Seguro de vida Gravado', -1),
  (85, '085', 'Ajuste en Seguro de Gastos Médicos Mayores Gravado', -1),
  (86, '086', 'Ajuste en Subsidios por incapacidad Gravado', -1),
  (87, '087', 'Ajuste en Becas para trabajadores y/o hijos Gravado', -1),
  (88, '088', 'Ajuste en Seguro de retiro Gravado', -1),
  (89, '089', 'Ajuste en Vales de despensa Gravado', -1),
  (90, '090', 'Ajuste en Vales de restaurante Gravado', -1),
  (91, '091', 'Ajuste en Vales de gasolina Gravado', -1),
  (92, '092', 'Ajuste en Vales de ropa Gravado', -1),
  (93, '093', 'Ajuste en Ayuda para renta Gravado', -1),
  (94, '094', 'Ajuste en Ayuda para artículos escolares Gravado', -1),
  (95, '095', 'Ajuste en Ayuda para anteojos Gravado', -1),
  (96, '096', 'Ajuste en Ayuda para transporte Gravado', -1),
  (97, '097', 'Ajuste en Ayuda para gastos de funeral Gravado', -1),
  (98, '098', 'Ajuste a ingresos asimilados a salarios gravados', -1),
  (99, '099', 'Ajuste a ingresos por sueldos y salarios gravados', -1),
  (100, '100', 'Ajuste en Viáticos exentos', -1),
  (101, 'OP001', 'Reintegro de ISR pagado en exceso (siempre que no haya sido enterado al SAT)', -1),
  (102, 'OP004', 'Aplicacion de saldo a favor por compensacion anual', -1),
  (103, 'OP002', 'Subsidio para el empleo (efectivamente entregado al trabajador)', -1);


/*TABLA NOMI_OTRO_PAGOS*/
CREATE TABLE `nomi_otros_pagos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(300) DEFAULT NULL,
  `clave` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*INSERT NOMI_OTROS_PAGOS*/
INSERT INTO `nomi_otros_pagos` (`id`, `descripcion`, `clave`)
VALUES
  (1, 'Reintegro de ISR pagado en exceso (siempre que no haya sido enterado al SAT)', '001'),
  (2, 'Subsidio para el empleo (efectivamente entregado al trabajador)', '002'),
  (3, 'Viaticos (entregados al trabajador)', '003'),
  (4, 'Aplicacion de saldo a favor por compensacion anual', '004'),
  (5, 'Pagos distintos a los listados y que no deben considerarse como ingreso por sueldos, salarios o ingresos asimilados.', '999');


CREATE TABLE `nomi_tiponomina` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  `clave` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_tiponomina` (`id`, `descripcion`, `clave`)
VALUES
  (1, 'Nómina ordinaria', 'O'),
  (2, 'Nómina extraordinaria', 'E');

CREATE TABLE `nomi_tipohoras` (
  `idhora` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(10) DEFAULT NULL,
  `clave` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`idhora`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_tipohoras` (`idhora`, `descripcion`, `clave`)
VALUES
  (1, 'Dobles', '01'),
  (2, 'Triples', '02'),
  (3, 'Simples', '03');

CREATE TABLE `nomi_incapacidad` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(11) NOT NULL DEFAULT '' COMMENT 'tabla definida por el sat',
  `descripcion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_incapacidad` (`id`, `clave`, `descripcion`)
VALUES
  (1, '01', 'Riesgo de Trabajo'),
  (2, '02', 'Enfermedad en General'),
  (3, '03', 'Maternidad');

CREATE TABLE `nomi_tipocontrato` (
  `idtipocontrato` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) DEFAULT NULL,
  `clave` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`idtipocontrato`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_tipocontrato` (`idtipocontrato`, `descripcion`, `clave`)
VALUES
  (1, 'Contrato de trabajo por tiempo indeterminado', '01'),
  (2, 'Contrato de trabajo para obra determinada', '02'),
  (3, 'Contrato de trabajo por tiempo determinado', '03'),
  (4, 'Contrato de trabajo por temporada', '04'),
  (5, 'Contrato de trabajo sujeto a prueba', '05'),
  (6, 'Contrato de trabajo con capacidad inicial', '06'),
  (7, 'Modalidad de contratacion por pago de hora laborada', '07'),
  (8, 'Modalidad de trabajo por comision laboral', '08'),
  (9, 'Modalidades de contratacion donde no existe relacion de trabajo', '09'),
  (10, 'Jubilación, pensión, retiro.', '10'),
  (11, 'Otro trabajo', '99');


INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (417, 'nomi_turno', 'Turno', '2017-01-13 16:50:30', '2017-01-13 17:00:29', 'A', 0, '', 0, '');

INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2399, 417, 'idturno', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2400, 417, 'nombre', 'Nombre', 'Nombre', 50, 'varchar', 'NA', '', -1, '', 1, 0),
  (2401, 417, 'horas', 'Horas por turno', 'Horas por turno', 0, 'double', 'NA', '', -1, '', 2, 0),
  (2402, 417, 'idjornada', 'Tipo de jornada', 'Tipo de jornada', 11, 'int', 'NA', '', 0, '-1', 3, 0),
  (2479, 417, 'activo', 'Activo', 'Activo', 0, 'boolean', 'NA', '', 0, '', 4, 0);


CREATE TABLE `nomi_turno` (
  `idturno` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL,
  `horas` double NOT NULL,
  `idjornada` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idturno`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `nomi_jornada` (
  `idjornada` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  `clave` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`idjornada`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_jornada` (`idjornada`, `descripcion`, `clave`)
VALUES
  (0, 'No aplica', ''),
  (1, 'Diurna', '01'),
  (2, 'Nocturna', '02'),
  (3, 'Mixta', '03'),
  (4, 'Por Hora', '04'),
  (5, 'Reducida', '05'),
  (6, 'Continuada', '06'),
  (7, 'Partida', '07'),
  (8, 'Por turnos', '08'),
  (9, 'Por Jornada', '99');

CREATE TABLE `nomi_regimencontratacion` (
  `idregimencontrato` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clave` char(2) DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`idregimencontrato`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_regimencontratacion` (`idregimencontrato`, `clave`, `descripcion`)
VALUES
  (1, '02', 'Sueldos'),
  (2, '03', 'Jubilados'),
  (3, '04', 'Pensionados'),
  (4, '05', 'Asimilados Miembros Sociedades Cooperativas Produccion'),
  (5, '06', 'Asimilados Integrantes Sociedades Asociaciones Civiles'),
  (6, '07', 'Asimilados Miembros consejos'),
  (7, '08', 'Asimilados comisionistas'),
  (8, '09', 'Asimilados Honorarios'),
  (9, '10', 'Asimilados acciones'),
  (10, '11', 'Asimilados otros'),
  (11, '99', 'Otro Regimen');


CREATE TABLE `nomi_departamento` (
  `idDep` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idDep`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (412, 'nomi_departamento', 'Departamento', '2017-01-13 10:02:49', '2017-01-13 10:04:14', 'A', 0, '', 0, '');

INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2378, 412, 'idDep', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (2379, 412, 'nombre', 'Nombre', 'Nombre', 100, 'varchar', 'NA', '', -1, '', 1, 0);


CREATE TABLE `nomi_riesgopuesto` (
  `idclaveriesgopuesto` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idclaveriesgopuesto`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_riesgopuesto` (`idclaveriesgopuesto`, `descripcion`)
VALUES
  (1, 'Clase I'),
  (2, 'Clase II'),
  (3, 'Clase III'),
  (4, 'Clase IV'),
  (5, 'Clase V');

INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (428, 'nomi_riesgopuesto', 'Riesgo puesto', '2017-01-17 10:38:24', '2017-01-17 10:38:24', 'G', 0, '', 0, '');

INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2453, 428, 'idclaveriesgopuesto', 'ID', 'ID', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (2454, 428, 'descripcion', 'Descripcion', 'Descripcion', 50, 'varchar', 'NA', '', -1, '', 1, 0);

CREATE TABLE `nomi_riesgotrabajo` (
  `idclaveriesgotrabajo` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idclaveriesgotrabajo`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_riesgotrabajo` (`idclaveriesgotrabajo`, `descripcion`)
VALUES
  (1, 'I Ordinario'),
  (2, 'II Bajo'),
  (3, 'III Medio'),
  (4, 'IV Alto'),
  (5, 'V Maximo');

INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (452, 'nomi_riesgotrabajo', 'Riesgo trabajo', '2017-06-01 21:37:25', '2017-06-01 21:37:25', 'G', 0, '', 0, '');

INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2576, 452, 'idclaveriesgotrabajo', 'ID', 'ID', 11, 'auto_increment', 'NA', '', 0, '', 0, -1),
  (2577, 452, 'descripcion', 'Descripcion', 'Descripcion', 20, 'varchar', 'NA', '', 0, '', 1, 0);


CREATE TABLE `nomi_fraccionriesgocatalogo` (
  `idfraccion` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(500) DEFAULT NULL,
  `fraccion` int(11) DEFAULT NULL,
  `idclaveriesgotrabajo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idfraccion`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_fraccionriesgocatalogo` (`idfraccion`, `descripcion`, `fraccion`, `idclaveriesgotrabajo`)
VALUES
  (1, 'Beneficio y/o fabricación de productos de tabaco.', 220, 3),
  (2, 'Fabricación, preparación, hilado, tejido y acabado de textiles de fibras blandas.', 231, 4),
  (3, 'Trabajos de blanqueo, teñido, estampado, impermeabilizado y acabado de hilados y tejidos de fibras blandas.', 232, 4),
  (4, 'Fabricación de tejidos y artículos de punto.', 233, 3),
  (5, 'Fabricación, preparación, hilado, tejido y acabado de textiles de fibras duras.', 234, 5),
  (6, 'Fabricación de tejidos de fibras blandas con telares automáticos sin lanzadera.', 236, 4),
  (7, 'Fabricación de hilados con máquinas de turbina.', 237, 4),
  (8, 'Confección de prendas de vestir a la medida.', 241, 1),
  (9, 'Confección de prendas de vestir.', 242, 2),
  (10, 'Otros artículos confeccionados con textiles y materiales diversos.', 243, 2),
  (11, 'Fabricación de calzado, con maquinaria y/o equipo motorizado.', 251, 3),
  (12, 'Fabricación de calzado, sin maquinaria ni equipo motorizado.', 252, 2),
  (13, 'Curtido y acabado de cuero y piel.', 253, 5),
  (14, 'Manufactura de artículos de cuero, piel y sucedáneos, en forma artesanal.', 254, 2),
  (15, 'Fabricación de artículos de cuero, piel y sucedáneos.', 255, 3),
  (16, 'Curtido y acabado de cuero y piel, con uso exclusivo de maquinaria y/o equipo motorizado.', 256, 5),
  (17, 'Fabricación de productos de aserradero.', 261, 5),
  (18, 'Fabricación de artículos y accesorios de madera.', 262, 5),
  (19, 'Manufactura de artículos de corcho, palma, vara, carrizo y mimbre.', 263, 2),
  (20, 'Fabricación de artículos de corcho, palma, vara, carrizo y mimbre.', 264, 3),
  (21, 'Fabricación y/o reparación de muebles de madera y sus partes.', 271, 5),
  (22, 'Fabricación de papel y/o cartón y sus derivados.', 281, 4),
  (23, 'Fabricación de artículos a base de papel y/o cartón.', 282, 4),
  (24, 'Industrias editorial, de impresión, encuadernación y actividades conexas.', 291, 3),
  (25, 'Fabricación de sustancias químicas e industriales; excepto abonos.', 301, 3),
  (26, 'Fabricación de abonos, fertilizantes y plaguicidas.', 302, 4),
  (27, 'Fabricación de resinas sintéticas y plastificantes.', 303, 3),
  (28, 'Industria de las pinturas.', 304, 3),
  (29, 'Industrias químico-farmacéuticas y de medicamentos.', 305, 2),
  (30, 'Fabricación de productos químicos para limpieza y aromatizantes ambientales.', 307, 3),
  (31, 'Fabricación de perfumes y cosméticos.', 308, 2),
  (32, 'Fabricación de aceites y grasas vegetales y animales no comestibles, para usos industriales.', 309, 4),
  (33, 'Fabricación de velas, veladoras y similares.', 3010, 3),
  (34, 'Fabricación de cerillos.', 3012, 4),
  (35, 'Fabricación de explosivos y fuegos artificiales.', 3013, 4),
  (36, 'Otros productos de las industrias químicas conexas.', 3014, 3),
  (37, 'Fabricación de fibras artificiales y sintéticas.', 3016, 2),
  (38, 'Refinación del petróleo crudo y petroquímica básica.', 311, 4),
  (39, 'Fabricación de lubricantes y aditivos.', 312, 3),
  (40, 'Fabricación de productos a base de asfalto y sus mezclas.', 313, 4),
  (41, 'Fabricación de productos de hule.', 321, 5),
  (42, 'Fabricación de productos de plástico.', 322, 4),
  (43, 'Fabricación de productos de látex.', 323, 5),
  (44, 'Manufactura de artículos de alfarería y cerámica.', 331, 3),
  (45, 'Fabricación de muebles sanitarios, loza, porcelana y artículos refractarios.', 332, 5),
  (46, 'Fabricación de vidrio y/o productos de vidrio.', 333, 4),
  (47, 'Fabricación de productos de arcilla para la construcción.', 335, 5),
  (48, 'Fabricación de cal y yeso.', 336, 5),
  (49, 'Fabricación de productos a base de asbesto.', 337, 5),
  (50, 'Fabricación de productos abrasivos.', 338, 3),
  (51, 'Fabricación de granito artificial, productos de mármol y otras piedras.', 339, 5),
  (52, 'Fabricación de productos y partes preconstruidas de concreto.', 3310, 5),
  (53, 'Fabricación de azulejos, con procesos continuos automatizados.', 3312, 3),
  (54, 'Fabricación de vidrio y/o productos de vidrio, con procesos continuos automatizados.', 3313, 2),
  (55, 'Fabricación de productos de asbesto-cemento.', 3315, 5),
  (56, 'Fabricación de cemento.', 3316, 5),
  (57, 'Fabricación de concreto premezclado.', 3317, 4),
  (58, 'Industrias básicas del hierro, acero y metales no ferrosos.', 341, 5),
  (59, 'Industrias básicas del hierro, acero y metales no ferrosos, con procesos automatizados.', 342, 5),
  (60, 'Fabricación de utensilios agrícolas, herramientas y artículos de ferretería y cerrajería.', 351, 5),
  (61, 'Fabricación y/o reparación de puertas, ventanas, cortinas metálicas y otros trabajos de herrería.', 352, 4),
  (62, 'Fabricación, ensamble y/o reparación de muebles metálicos y sus partes.', 353, 4),
  (63, 'Fabricación y/o reparación de estructuras metálicas, tanques, calderas y similares.', 354, 5),
  (64, 'Fabricación de envases metálicos, corcholatas y tapas.', 355, 5),
  (65, 'Fabricación de alambres y otros productos de alambre.', 356, 5),
  (66, 'Trabajos de tratamientos térmicos y galvanoplastia.', 357, 4),
  (67, 'Fabricación de agujas, alfileres, cierres, botones y navajas para rasurar.', 358, 3),
  (68, 'Fabricación de baterías de cocina, cucharas, cuchillos y tenedores.', 359, 5),
  (69, 'Fabricación de otros productos metálicos maquinados.', 3510, 5),
  (70, 'Tratamientos térmicos y galvanoplastia, con procesos continuos automatizados.', 3511, 3),
  (71, 'Fabricación y/o ensamble de maquinaria, equipos e implementos para labores agropecuarias.', 361, 4),
  (72, 'Fabricación y/o ensamble de maquinaria, equipo e implementos para las industrias de alimentos, bebidas, tabacalera, textil, calzado, madera, cuero, impresión, hule, plástico, productos de minerales no metálicos (excepto cemento), metal mecánica y maquinaria y equipo de uso común a varias industrias.', 362, 4),
  (73, 'Fabricación y/o ensamble de maquinaria, equipo e implementos para las industrias de la construcción, extractivas, papel, cemento, petroquímica básica, química; metálicas básicas del hierro, del acero y de metales no ferrosos.', 363, 5),
  (74, 'Fabricación y/o ensamble de máquinas de coser, oficina, cómputo y sus partes.', 364, 2),
  (75, 'Reparación y ensamble de máquinas de coser y de oficina.', 365, 1),
  (76, 'Fabricación de partes y piezas sueltas, para maquinaria y equipo en general.', 366, 5),
  (77, 'Reparación y/o mantenimiento de maquinaria y equipo en general.', 367, 3),
  (78, 'Fabricación y/o ensamble de maquinaria y equipo para generación y transformación de energía eléctrica.', 371, 4),
  (79, 'Fabricación y/o ensamble de equipo y aparatos de radio, televisión y comunicaciones.', 372, 2),
  (80, 'Fabricación y/o grabado de discos y cintas magnéticas para sonidos, imágenes y datos.', 373, 3),
  (81, 'Fabricación y/o ensamble de aparatos eléctricos y sus partes para uso doméstico.', 374, 3),
  (82, 'Fabricación, reconstrucción y/o ensamble de acumuladores eléctricos.', 375, 4),
  (83, 'Fabricación y/o ensamble de pilas (secas), componentes eléctricos y electrónicos diversos.', 376, 3),
  (84, 'Fabricación y/o ensamble de lámparas (focos) y tubos al vacío para alumbrado eléctrico.', 377, 3),
  (85, 'Fabricación de conductores eléctricos.', 378, 3),
  (86, 'Fabricación y/o ensamble de aparatos, accesorios eléctricos o electrónicos, para empalme, corte, protección y conexión.', 379, 2),
  (87, 'Fabricación de luminarias y anuncios luminosos.', 3710, 5),
  (88, 'Fabricación en serie o con procesos continuos de acumuladores eléctricos.', 3711, 3),
  (89, 'Fabricación y/o ensamble de refrigeradores, estufas, lavadoras, secadoras y otros aparatos de línea blanca.', 3712, 4),
  (90, 'Fabricación y/o ensamble de aeronaves.', 381, 3),
  (91, 'Fabricación y/o ensamble de carrocerías para vehículos de transporte.', 382, 4),
  (92, 'Fabricación y/o ensamble de partes y accesorios para automóviles, autobuses, camiones, motocicletas y bicicletas.', 383, 4),
  (93, 'Fabricación y/o ensamble de partes para el sistema eléctrico de vehículos automóviles.', 384, 2),
  (94, 'Fabricación y/o ensamble de bicicletas y otros vehículos de pedal.', 385, 4),
  (95, 'Fabricación, ensamble y/o reparación de carros de ferrocarril, equipo ferroviario y sus partes.', 386, 5),
  (96, 'Fabricación, ensamble y/o reparación de embarcaciones.', 387, 5),
  (97, 'Fabricación y/o ensamble de automóviles, autobuses, camiones y motocicletas.', 388, 3),
  (98, 'Fabricación y/o ensamble de motores para automóviles, autobuses y camiones.', 389, 3),
  (99, 'Fabricación de conjuntos mecánicos y sus partes para automóviles, autobuses, camiones y motocicletas.', 3810, 4),
  (100, 'Fabricación, ensamble y/o reparación de equipos, aparatos científicos y profesionales e instrumentos de medida y control.', 390, 3),
  (101, 'Fabricación, ensamble y/o reparación de aparatos, instrumentos y accesorios de óptica y fotografía.', 391, 2),
  (102, 'Fabricación, montaje y/o ensamble de relojes, joyas, artículos de orfebrería y fantasía.', 392, 2),
  (103, 'Fabricación y/o ensamble de instrumentos musicales, paraguas, juguetes y artículos deportivos, con maquinaria y/o equipo motorizado.', 394, 3),
  (104, 'Fabricación y/o ensamble de instrumentos musicales, paraguas, juguetes y artículos deportivos, sin maquinaria ni equipo motorizado.', 395, 2),
  (105, 'Fabricación de lápices, gomas, plumas y bolígrafos.', 396, 3),
  (106, 'Talleres de mecánica dental.', 397, 2),
  (107, 'Fabricación y/o ensamble de armas de fuego portátiles, cartuchos, municiones y accesorios.', 398, 3),
  (108, 'Fabricación, ensamble y/o reparación de otros artículos manufacturados no clasificados anteriormente, sin maquinaria ni equipo motorizado.', 399, 3),
  (109, 'Fabricación, ensamble y/o reparación de otros artículos manufacturados no clasificados anteriormente, con maquinaria y/o equipo motorizado.', 3910, 4),
  (110, 'Construcción de edificaciones; excepto obra pública.', 411, 5),
  (111, 'Construcciones de obras de infraestructura y edificaciones en obra pública.', 412, 5),
  (112, 'Instalaciones sanitarias, eléctricas, de gas y de aire acondicionado.', 421, 4),
  (113, 'Instalación y reparación de ascensores, escaleras electromecánicas y otros equipos para transportación', 422, 4),
  (114, 'Instalación de ventanería, herrería, cancelería, vidrios y cristales.', 423, 5),
  (115, 'Otros servicios de instalación vinculados al acabado o remodelación de obras de construcción.', 424, 5),
  (116, 'Generación, transmisión y distribución de energía eléctrica.', 500, 4),
  (117, 'Captación y suministro de agua potable y tratada.', 510, 3),
  (118, 'Expendios de ventas al menudeo de alimentos, bebidas y/o productos del tabaco.', 611, 2),
  (119, 'Compraventa de alimentos, bebidas y/o productos del tabaco, sin transporte.', 612, 3),
  (120, 'Compraventa de alimentos, bebidas y/o productos del tabaco, con transporte.', 613, 3),
  (121, 'Compraventa e introducción de animales vivos.', 614, 3),
  (122, 'Expendios de ventas al menudeo de prendas y accesorios de vestir y artículos para su confección.', 621, 1),
  (123, 'Compraventa de prendas y accesorios de vestir y artículos para su confección, sin transporte.', 622, 2),
  (124, 'Compraventa de prendas y accesorios de vestir y artículos para su confección, con transporte.', 623, 1),
  (125, 'Expendios de ventas al menudeo de artículos de uso personal.', 624, 1),
  (126, 'Compraventa de artículos de uso personal, sin transporte.', 625, 2),
  (127, 'Compraventa de artículos de uso personal, con transporte.', 626, 1),
  (128, 'Expendios de ventas al menudeo de medicamentos, productos farmacéuticos, químico-farmacéuticos y de perfumería.', 627, 1),
  (129, 'Compraventa de medicamentos, productos farmacéuticos, químico-farmacéuticos y de perfumería, sin transporte.', 628, 1),
  (130, 'Compraventa de medicamentos, productos farmacéuticos, químico-farmacéuticos y de perfumería, con transporte.', 629, 2),
  (131, 'Expendios de ventas al menudeo de papelería, útiles escolares y de oficina; libros, periódicos y revistas.', 6210, 1),
  (132, 'Compraventa de papelería, útiles escolares y de oficina; libros, periódicos y revistas, sin transporte.', 6211, 3),
  (133, 'Compraventa de papelería, útiles escolares y de oficina; libros, periódicos y revistas, con transporte.', 6212, 2),
  (134, 'Expendios de ventas al menudeo de máquinas, muebles, aparatos e instrumentos para el hogar, sus refacciones y accesorios.', 631, 1),
  (135, 'Compraventa de máquinas, muebles, aparatos e instrumentos para el hogar, sus refacciones y accesorios, sin transporte.', 632, 1),
  (136, 'Compraventa de máquinas, muebles, aparatos e instrumentos para el hogar, sus refacciones y accesorios, con transporte y/o servicios de instalación.', 633, 3),
  (137, 'Expendios de ventas al menudeo de otros artículos para el hogar.', 634, 1),
  (138, 'Compraventa de otros artículos para el hogar, sin transporte.', 635, 1),
  (139, 'Compraventa de otros artículos para el hogar, con transporte y/o servicios de instalación.', 636, 2),
  (140, 'Supermercados, tiendas de autoservicio y de departamentos especializados por línea de mercancías.', 641, 2),
  (141, 'Compraventa, envasado y/o distribución de gases para uso doméstico, industrial y medicinal.', 651, 5),
  (142, 'Compraventa de lubricantes y aditivos, sin transporte.', 652, 2),
  (143, 'Estaciones de venta de gasolina, diesel y compraventa de lubricantes y aditivos, con transporte.', 653, 3),
  (144, 'Compraventa de leña, carbón vegetal y mineral.', 654, 3),
  (145, 'Expendios de ventas al menudeo de materias primas agropecuarias.', 661, 2),
  (146, 'Compraventa de materias primas agropecuarias, sin transporte.', 662, 3),
  (147, 'Compraventa de materias primas agropecuarias, con transporte.', 663, 3),
  (148, 'Compraventa de materiales para construcción, tales como madera, aceros y productos de ferretería, sin transporte, ni preparación de mercancías.', 664, 2),
  (149, 'Compraventa de materiales para construcción tales como: madera, aceros y productos de ferretería, con transporte y/o preparación de mercancías.', 665, 4),
  (150, 'Compraventa de material eléctrico, pinturas y productos de tlapalería, sin transporte.', 666, 2),
  (151, 'Compraventa de material eléctrico, pinturas y productos de tlapalería, con transporte.', 667, 2),
  (152, 'Compraventa de vidrio plano, cristales, espejos y lunas, sin transporte ni servicios de instalación.', 668, 5),
  (153, 'Compraventa de vidrio plano, cristales, espejos y lunas, con transporte y/o servicios de instalación.', 669, 5),
  (154, 'Compraventa de fertilizantes, plaguicidas y productos químicos (no explosivos) en envases cerrados, sin transporte.', 6610, 2),
  (155, 'Compraventa de fertilizantes, plaguicidas y productos químicos (no explosivos) en envases cerrados o a granel, con transporte.', 6611, 3),
  (156, 'Compraventa de pieles, cueros curtidos y otros artículos de peletería, sin transporte.', 6612, 2),
  (157, 'Compraventa de pieles, cueros curtidos y otros artículos de peletería, con transporte', 6613, 1),
  (158, 'Compraventa de papel y cartón nuevos, sin transporte.', 6614, 2),
  (159, 'Compraventa de papel y cartón nuevos, con transporte.', 6615, 3),
  (160, 'Compraventa de chatarra, fierro viejo, partes o mecanismos usados y desperdicios en general.', 6616, 5),
  (161, 'Compraventa de explosivos y productos de pirotecnia.', 6617, 3),
  (162, 'Expendio de ventas al menudeo de refacciones y accesorios para maquinaria y/o equipo para la producción de bienes.', 671, 2),
  (163, 'Compraventa de maquinaria, equipo y sus refacciones y/o accesorios para la producción de bienes, sin transporte.', 672, 2),
  (164, 'Compraventa de maquinaria, equipo y sus refacciones y/o accesorios para la producción de bienes, con transporte y/o servicios de reparación o mantenimiento.', 673, 3),
  (165, 'Compraventa de maquinaria, equipo y sus refacciones y/o accesorios para la producción de bienes, con servicios de instalación.', 674, 4),
  (166, 'Expendios de ventas al menudeo de equipo, mobiliario, sus partes y/o accesorios para la prestación de servicios y el comercio.', 675, 1),
  (167, 'Compraventa de equipo, mobiliario, sus partes y/o accesorios para la prestación de servicios y el comercio, sin transporte.', 676, 1),
  (168, 'Compraventa de equipo, mobiliario, sus partes y/o accesorios para la prestación de servicios y el comercio, con transporte y/o servicios de instalación, reparación y mantenimiento.', 677, 2),
  (169, 'Expendios de ventas al menudeo de aparatos e instrumentos de medición, precisión, cirugía, laboratorio y otros usos científicos.', 678, 1),
  (170, 'Compraventa de aparatos e instrumentos de medición, precisión, cirugía, laboratorio y otros usos científicos, sin transporte.', 679, 1),
  (171, 'Compraventa de aparatos e instrumentos de medición, precisión, cirugía, laboratorio y otros usos científicos, con transporte y/o servicios de instalación, reparación o mantenimiento.', 6710, 2),
  (172, 'Compraventa de equipo de cómputo o de procesamiento electrónico de datos y sus periféricos, con servicios de instalación, reparación y/o mantenimiento.', 6711, 2),
  (173, 'Expendios de ventas al menudeo de refacciones, accesorios y/o partes para equipo de transporte.', 681, 2),
  (174, 'Compraventa de equipo de transporte, sus refacciones, accesorios y/o partes, sin transporte.', 682, 2),
  (175, 'Compraventa de equipo de transporte, sus refacciones, accesorios y/o partes, con transporte y/o servicios de instalación, reparación o mantenimiento.', 683, 3),
  (176, 'Compraventa de bienes inmuebles.', 691, 1),
  (177, 'Expendios de ventas al menudeo de artículos diversos no clasificados.', 692, 1),
  (178, 'Compraventa de artículos diversos no clasificados, sin transporte.', 693, 2),
  (179, 'Compraventa de artículos diversos no clasificados, con transporte y/o servicios de instalación, reparación o mantenimiento.', 694, 2),
  (180, 'Transporte de pasajeros.', 711, 4),
  (181, 'Transporte de carga.', 712, 5),
  (182, 'Transporte ferroviario y eléctrico.', 713, 5),
  (183, 'Transporte marítimo y de navegación interior y servicios diversos a bordo y/o en plataformas marinas.', 721, 4),
  (184, 'Servicios directamente vinculados con el transporte por agua y/o servicios de supervisión y mantenimiento en plataformas marinas.', 722, 5),
  (185, 'Transporte aéreo.', 730, 2),
  (186, 'Administración de vías de comunicación, terminales y servicios auxiliares.', 740, 2),
  (187, 'Servicios de almacenamiento y/o refrigeración.', 751, 4),
  (188, 'Servicios sin transporte de agencias de gestión aduanal, de equipajes, viajes y turísticas.', 752, 1),
  (189, 'Servicios de grúa y emergencia para vehículos.', 753, 4),
  (190, 'Servicios de alquiler de aeronaves, carros de ferrocarril y transportes acuáticos.', 754, 3),
  (191, 'Servicios con transporte de agencias de gestión aduanal, de mensajería y paquetería, de equipajes, viajes, turísticas y otras actividades relacionadas con los transportes en general.', 755, 4),
  (192, 'Comunicaciones.', 760, 2),
  (193, 'Instituciones de crédito, seguros y fianzas.', 810, 1),
  (194, 'Servicios colaterales a las instituciones financieras y de seguros.', 820, 1),
  (195, 'Servicios relacionados con inmuebles.', 830, 1),
  (196, 'Servicios profesionales y técnicos.', 841, 1),
  (197, 'Servicios de instalación de maquinaria y equipo en general.', 843, 5),
  (198, 'Servicios de protección y custodia.', 844, 3),
  (199, 'Servicios de laboratorio para la industria en general.', 845, 2),
  (200, 'Servicios de alquiler de maquinaria y equipo agrícola.', 851, 3),
  (201, 'Servicios de alquiler de maquinaria y equipo para la construcción con operadores.', 852, 5),
  (202, 'Servicios de alquiler de maquinaria y equipo para la construcción sin operadores.', 853, 3),
  (203, 'Servicios de alquiler de equipo y mobiliario a empresas.', 854, 2),
  (204, 'Servicios de alquiler para el público en general.', 855, 1),
  (205, 'Servicios de alquiler o renta de automóviles, bicicletas y motocicletas.', 856, 2),
  (206, 'Servicios de alojamiento temporal.', 860, 2),
  (207, 'Preparación y servicio de alimentos.', 871, 2),
  (208, 'Preparación y servicio de bebidas alcohólicas.', 872, 3),
  (209, 'Servicios recreativos.', 881, 2),
  (210, 'Servicios de esparcimiento.', 882, 1),
  (211, 'Hipódromos, galgódromos, lienzos charros, palenques y promoción y presentación de espectáculos taurinos.', 883, 3),
  (212, 'Servicios de centros nocturnos, salones de baile y casinos.', 884, 2),
  (213, 'Promoción y montaje de exposiciones de pintura y escultura.', 885, 1),
  (214, 'Circos y juegos electromecánicos.', 886, 3),
  (215, 'Servicios de reparación, lavado, engrasado, verificación de emisión de contaminantes y estacionamiento de vehículos con servicios mecánicos y/o de hojalatería.', 891, 3),
  (216, 'Servicios de reparación de artículos de uso doméstico y personal, sin maquinaria ni equipo motorizado.', 892, 2),
  (217, 'Servicios de reparación de artículos de uso doméstico y personal, con maquinaria y/o equipo motorizado.', 893, 4),
  (218, 'Servicios para el aseo personal y sanitarios.', 894, 2),
  (219, 'Servicios de peluquería y salones de belleza.', 895, 1),
  (220, 'Servicios de aseo y limpieza, sin maquinaria ni equipo motorizado.', 896, 2),
  (221, 'Servicios de aseo y limpieza, con maquinaria y/o equipo motorizado.', 897, 3),
  (222, 'Servicios de limpieza de ventanas y fachadas.', 894, 4),
  (223, 'Servicios de fumigación, desinfección y control de plagas.', 899, 3),
  (224, 'Aerotecnia agrícola.', 8910, 5),
  (225, 'Servicios de revelado fotográfico.', 8911, 1),
  (226, 'Inhumaciones y servicios conexos.', 8912, 2),
  (227, 'Servicios domésticos.', 8913, 1),
  (228, 'Servicios de estacionamiento y/o pensión para vehículos.', 8914, 3),
  (229, 'Servicios de enseñanza académica, capacitación, investigación científica y difusión cultural.', 911, 1),
  (230, 'Servicios médicos.', 921, 1),
  (231, 'Servicios médicos, paramédicos y auxiliares.', 922, 2),
  (232, 'Servicios de asistencia social.', 923, 1),
  (233, 'Servicios veterinarios y auxiliares.', 924, 1),
  (234, 'Asociaciones y organizaciones comerciales, profesionales, cívicas, laborales y políticas.', 931, 1),
  (235, 'Organizaciones religiosas.', 933, 1),
  (236, 'Servicios generales de la administración pública.', 941, 2),
  (237, 'Seguridad pública.', 942, 3),
  (238, 'Seguridad social.', 943, 2),
  (239, 'Servicios de organizaciones internacionales y otros organismos extraterritoriales.', 990, 1);


CREATE TABLE `nomi_nominas_timbradas` (
  `idNominatimbre` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idEmpleado` int(11) DEFAULT NULL COMMENT 'nomi_empleados',
  `fechainicial` date DEFAULT NULL,
  `fechafinal` date DEFAULT NULL,
  `fechapago` date DEFAULT NULL,
  `diaspago` int(11) DEFAULT NULL,
  `tiponomina` varchar(1) DEFAULT NULL COMMENT 'nomina O , E',
  `idnomp` int(11) DEFAULT NULL COMMENT 'nomina de pago',
  `subtotal` double DEFAULT NULL,
  `descuento` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `timbrado` tinyint(1) DEFAULT NULL COMMENT '1 si, 0 no',
  `selloSAT` varchar(500) DEFAULT NULL,
  `selloCFD` varchar(500) DEFAULT NULL,
  `fechaTimbrado` datetime DEFAULT NULL,
  `UUID` varchar(100) DEFAULT NULL,
  `nombreXML` varchar(500) DEFAULT NULL,
  `cancelado` tinyint(1) DEFAULT NULL COMMENT '1 si, 0 no',
  `idregfiscal` int(11) DEFAULT NULL,
  `idperiodicidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`idNominatimbre`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `nomi_tipo_empleado` (
  `idtipoempleado` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`idtipoempleado`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `nomi_tipo_empleado` (`idtipoempleado`, `tipo`)
VALUES
  (1, 'Sindicalizado'),
  (2, 'Confianza');

CREATE TABLE `nomi_base_pago` (
  `idbasepago` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `base` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`idbasepago`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `nomi_base_pago` (`idbasepago`, `base`)
VALUES
  (1, 'Sueldo'),
  (2, 'Comision'),
  (3, 'Destajo'),
  (4, 'Sueldo-Comision'),
  (5, 'Sueldo-Destajo');

CREATE TABLE `nomi_periodicidad` (
  `idperiodicidad` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  `clave` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`idperiodicidad`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `nomi_periodicidad` (`idperiodicidad`, `descripcion`, `clave`)
VALUES
  (1, 'Diario', '01'),
  (2, 'Semanal', '02'),
  (3, 'Catorcenal', '03'),
  (4, 'Quincenal', '04'),
  (5, 'Mensual', '05'),
  (6, 'Bimestral', '06'),
  (7, 'Unidad Obra', '07'),
  (8, 'Comisión', '08'),
  (9, 'Precio Alzado', '09'),
  (10, 'Decenal', '10'),
  (11, 'Otra Periodicidad', '99');


/*ACCESS*/
INSERT IGNORE INTO `accelog_categorias` (`idcategoria`, `nombre`, `icono`, `orden`)
VALUES
  (1055, 'Nominas', 0, 12);

INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2208, 'Catalogos', 0, NULL, 1055, 0, 1, 0),
  (2209, 'Empleados Nominas', 2208, '../../modulos/nominas/index.php?c=Catalogos&f=listaEmpleados', 1055, 0, 5, 0),
  (2356, 'Nomina Manual', 0, '../../modulos/nominas/index.php?c=Nominalibre&f=viewNomina', 1055, 0, 7, 0),
  (2218, 'Departamento', 2208, '../catalog/gestor.php?idestructura=412&ticket=testing', 1055, 0, 6, 0),
  (2360, 'Turno', 2208, '../catalog/gestor.php?idestructura=417&ticket=testing', 1055, 0, 3, 0),
  (2227, 'Registros Patronales', 2208, '../catalog/gestor.php?idestructura=427&ticket=testing', 1055, 0, 1, 0),
  (1595, 'Configuracion de Facturacion', 0, '../catalog/gestor.php?idestructura=234&ticket=testing', 19, 0, 20, 0);
INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2378, 'Reportes', 0, '', 1055, 0, 10, 0);
INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2379, 'Reporte de Nominas', 2378, '../../modulos/nominas/index.php?c=Reportes&f=reporteNominas', 1055, 0, 1, 0);

INSERT ignore INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2578, 413, 'idclaveriesgopuesto', 'Riesgo puesto', 'Riesgo puesto', 11, 'int', 'NA', '', 0, '-1', 3, 0);
INSERT ignore INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (2578, 'S', 'nomi_riesgopuesto', 'idclaveriesgopuesto', ' descripcion ');


/*PERFILES*/
INSERT IGNORE INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
  (2, 2208),(2, 2356),(2,2209),(2,2356),(2,2218),(2,2360),(2,2227),(2,1595),(2,2379),(2,2378),(2,2219);

















