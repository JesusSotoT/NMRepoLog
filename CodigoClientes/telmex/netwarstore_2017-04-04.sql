# ************************************************************
# Sequel Pro SQL dump
# Versión 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com (MySQL 5.6.23-log)
# Base de datos: netwarstore
# Tiempo de Generación: 2017-04-04 16:33:32 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Volcado de tabla app_compatibles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_compatibles`;

CREATE TABLE `app_compatibles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `instalado` varchar(10) DEFAULT NULL,
  `instalable` varchar(10) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `sustituye` varchar(50) DEFAULT NULL,
  `incompatible` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;



# Volcado de tabla app_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_config`;

CREATE TABLE `app_config` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla appaddon
# ------------------------------------------------------------

DROP TABLE IF EXISTS `appaddon`;

CREATE TABLE `appaddon` (
  `id` int(11) DEFAULT NULL,
  `idapp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla appclient
# ------------------------------------------------------------

DROP TABLE IF EXISTS `appclient`;

CREATE TABLE `appclient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idclient` int(11) NOT NULL,
  `idapp` int(11) NOT NULL,
  `idcustomer` int(11) NOT NULL,
  `installkey` char(19) DEFAULT NULL,
  `initdate` date DEFAULT NULL,
  `limitdate` date DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `agreement` int(11) DEFAULT NULL,
  `idstatus` int(11) NOT NULL,
  `activ_pend` int(1) unsigned DEFAULT '0',
  `version` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20104 DEFAULT CHARSET=utf8;



# Volcado de tabla appdescrip
# ------------------------------------------------------------

DROP TABLE IF EXISTS `appdescrip`;

CREATE TABLE `appdescrip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idapp` int(11) NOT NULL,
  `type_inst` varchar(100) NOT NULL DEFAULT '',
  `appname` varchar(200) NOT NULL,
  `photo` varchar(50) NOT NULL,
  `category` varchar(100) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `score` decimal(10,1) NOT NULL DEFAULT '0.0',
  `destacado` tinyint(1) NOT NULL,
  `description` text NOT NULL,
  `escencial` tinyint(1) NOT NULL,
  `fecha_in` date NOT NULL,
  `descr_short` varchar(50) DEFAULT '',
  `seller` varchar(200) NOT NULL DEFAULT '',
  `descr` varchar(200) DEFAULT NULL,
  `tag` varchar(250) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;



# Volcado de tabla bco_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bco_status`;

CREATE TABLE `bco_status` (
  `idstatus` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla codigos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `codigos`;

CREATE TABLE `codigos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` char(19) NOT NULL DEFAULT '',
  `estatus` int(10) NOT NULL,
  `salesman` char(6) DEFAULT NULL,
  `instalations` int(11) DEFAULT '1',
  `version` varchar(25) DEFAULT NULL,
  `fecha_asignacion` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65537 DEFAULT CHARSET=utf8;



# Volcado de tabla customer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idclient` int(11) NOT NULL,
  `razon` varchar(100) NOT NULL,
  `nombre` varchar(100) DEFAULT '',
  `rfc` varchar(14) NOT NULL DEFAULT '',
  `pais` varchar(100) NOT NULL DEFAULT '',
  `estado` varchar(50) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `colonia` varchar(100) NOT NULL DEFAULT '',
  `direccion` varchar(100) NOT NULL DEFAULT '',
  `cp` varchar(6) NOT NULL,
  `id_direccion` int(11) NOT NULL,
  `telefono` varchar(35) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `giro` varchar(100) NOT NULL,
  `instancia` varchar(255) NOT NULL DEFAULT '',
  `nombre_db` varchar(255) NOT NULL,
  `usuario_db` varchar(255) NOT NULL DEFAULT '',
  `pwd_db` varchar(255) NOT NULL DEFAULT '',
  `usuario_master` varchar(255) NOT NULL DEFAULT '',
  `pwd_master` varchar(255) NOT NULL DEFAULT '',
  `fecha` varchar(255) NOT NULL DEFAULT '',
  `registro` longtext,
  `fechaultimoacceso` datetime DEFAULT NULL,
  `status_instancia` int(11) DEFAULT '1',
  `productos` int(11) DEFAULT NULL,
  `ventas` int(11) DEFAULT NULL,
  `facturas` int(11) DEFAULT NULL,
  `polizas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12275 DEFAULT CHARSET=utf8 COMMENT='NULL o 0 - Regular\n4 - Cancelada\n6 - Deshabilitada (verificación por trial expirado)';



# Volcado de tabla imgslider
# ------------------------------------------------------------

DROP TABLE IF EXISTS `imgslider`;

CREATE TABLE `imgslider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idapp` int(11) NOT NULL,
  `imgslider` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;



# Volcado de tabla inovekia_calendario_consultor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_calendario_consultor`;

CREATE TABLE `inovekia_calendario_consultor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_consultor` int(11) NOT NULL,
  `id_empresario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `latitud` float NOT NULL,
  `longitud` float NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `creado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;



# Volcado de tabla inovekia_consultor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_consultor`;

CREATE TABLE `inovekia_consultor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `contrasena` text NOT NULL,
  `creado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`),
  UNIQUE KEY `usuario_UNIQUE` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Volcado de tabla inovekia_curso
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_curso`;

CREATE TABLE `inovekia_curso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  `creado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `url` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;



# Volcado de tabla inovekia_empresario
# ------------------------------------------------------------

DROP VIEW IF EXISTS `inovekia_empresario`;

CREATE TABLE `inovekia_empresario` (
   `id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
   `razon` VARCHAR(100) NOT NULL,
   `nombre` VARCHAR(100) NULL DEFAULT '',
   `activo` INT(1) NOT NULL DEFAULT '0',
   `creado` VARCHAR(255) NOT NULL DEFAULT '',
   `modificado` VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM;



# Volcado de tabla inovekia_empresario_consultor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_empresario_consultor`;

CREATE TABLE `inovekia_empresario_consultor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresario` int(11) NOT NULL,
  `id_consultor` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `creado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=latin1;



# Volcado de tabla inovekia_empresario_folio
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_empresario_folio`;

CREATE TABLE `inovekia_empresario_folio` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_empresario` bigint(20) NOT NULL,
  `folio` varchar(50) NOT NULL DEFAULT '',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `creado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;



# Volcado de tabla inovekia_evaluacion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_evaluacion`;

CREATE TABLE `inovekia_evaluacion` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_consultor` int(11) NOT NULL,
  `id_empresario` int(11) NOT NULL,
  `calificacion` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT '1',
  `creado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;



# Volcado de tabla inovekia_formulario_cinco
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_formulario_cinco`;

CREATE TABLE `inovekia_formulario_cinco` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `f5p1a` varchar(500) DEFAULT NULL,
  `f5p2a` varchar(500) DEFAULT NULL,
  `f5p3a` varchar(500) DEFAULT NULL,
  `f5p4a` varchar(500) DEFAULT NULL,
  `f5p5a` varchar(500) DEFAULT NULL,
  `f5p6a` varchar(500) DEFAULT NULL,
  `f5p7a` varchar(500) DEFAULT NULL,
  `f5p8a` varchar(500) DEFAULT NULL,
  `id_empresario` varchar(11) DEFAULT NULL,
  `id_consultor` varchar(11) DEFAULT NULL,
  `visita` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla inovekia_formulario_cuatro
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_formulario_cuatro`;

CREATE TABLE `inovekia_formulario_cuatro` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `f4p1a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p2a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p3a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p4a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p5a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p6a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p7a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p8a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p9a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p10a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p11a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p12a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p13a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p14a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p15a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p16a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p17a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p18a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p19a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p20a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p21a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p22a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p23a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p24a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p24r1a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p24r2a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p24r3a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p24r4a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p24r5a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p24r6a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p24r7a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p24r8a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p25a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p25b` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p26a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p27a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p28a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p29a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p30a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p31a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p32a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p33a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p34r1a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p34r1b` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p34r1c` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p34r1d` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p34r1e` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p34r1f` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p34r1g` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p34r1h` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p35a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p36a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p37a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p38a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p39a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p40a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p41a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p42a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p43a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p44a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p45a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p46a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p47a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p48a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p49a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `f4p50a` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `id_empresario` varchar(11) CHARACTER SET latin1 DEFAULT NULL,
  `id_consultor` varchar(11) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPRESSED KEY_BLOCK_SIZE=8;



# Volcado de tabla inovekia_formulario_dos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_formulario_dos`;

CREATE TABLE `inovekia_formulario_dos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `f2p1a` varchar(500) DEFAULT NULL,
  `f2p2a` varchar(500) DEFAULT NULL,
  `f2p2b` varchar(500) DEFAULT NULL,
  `f2p3a` varchar(500) DEFAULT NULL,
  `f2p4a` varchar(500) DEFAULT NULL,
  `f2p4b` varchar(500) DEFAULT NULL,
  `f2p5a` varchar(500) DEFAULT NULL,
  `f2p5b` varchar(500) DEFAULT NULL,
  `f2p6a` varchar(500) DEFAULT NULL,
  `f2p6b` varchar(500) DEFAULT NULL,
  `f2p6c` varchar(500) DEFAULT NULL,
  `f2p6d` varchar(500) DEFAULT NULL,
  `f2p7a` varchar(500) DEFAULT NULL,
  `f2p8a` varchar(500) DEFAULT NULL,
  `f2p9a` varchar(500) DEFAULT NULL,
  `f2p10a` varchar(500) DEFAULT NULL,
  `id_empresario` varchar(11) DEFAULT NULL,
  `id_consultor` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla inovekia_formulario_seis
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_formulario_seis`;

CREATE TABLE `inovekia_formulario_seis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `f6p1a` varchar(500) DEFAULT NULL,
  `f6p2a` varchar(500) DEFAULT NULL,
  `f6p3a` varchar(500) DEFAULT NULL,
  `f6p4a` varchar(500) DEFAULT NULL,
  `f6p5a` varchar(500) DEFAULT NULL,
  `f6p6a` varchar(500) DEFAULT NULL,
  `f6p7a` varchar(500) DEFAULT NULL,
  `f6p8a` varchar(500) DEFAULT NULL,
  `f6p9a` varchar(500) DEFAULT NULL,
  `f6p10a` varchar(500) DEFAULT NULL,
  `f6p11a` varchar(500) DEFAULT NULL,
  `f6p12a` varchar(500) DEFAULT NULL,
  `f6p13a` varchar(500) DEFAULT NULL,
  `f6p14a` varchar(500) DEFAULT NULL,
  `f6p15a` varchar(500) DEFAULT NULL,
  `f6p16a` varchar(500) DEFAULT NULL,
  `f6p17a` varchar(500) DEFAULT NULL,
  `f6p18a` varchar(500) DEFAULT NULL,
  `f6p19a` varchar(500) DEFAULT NULL,
  `f6p20a` varchar(500) DEFAULT NULL,
  `f6p21a` varchar(500) DEFAULT NULL,
  `f6p22a` varchar(500) DEFAULT NULL,
  `f6p23a` varchar(500) DEFAULT NULL,
  `f6p24a` varchar(500) DEFAULT NULL,
  `id_empresario` varchar(11) DEFAULT NULL,
  `id_consultor` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;



# Volcado de tabla inovekia_formulario_siete
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_formulario_siete`;

CREATE TABLE `inovekia_formulario_siete` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `f7p1a` varchar(500) DEFAULT NULL,
  `f7p2a` varchar(500) DEFAULT NULL,
  `f7p3a` varchar(500) DEFAULT NULL,
  `f7p4a` varchar(500) DEFAULT NULL,
  `f7p5a` varchar(500) DEFAULT NULL,
  `f7p6a` varchar(500) DEFAULT NULL,
  `f7p7a` varchar(500) DEFAULT NULL,
  `id_empresario` varchar(11) DEFAULT NULL,
  `id_consultor` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;



# Volcado de tabla inovekia_formulario_tres
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_formulario_tres`;

CREATE TABLE `inovekia_formulario_tres` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `f3p1a` varchar(500) DEFAULT NULL,
  `f3p2a` varchar(500) DEFAULT NULL,
  `f3p2b` varchar(500) DEFAULT NULL,
  `f3p3a` varchar(500) DEFAULT NULL,
  `f3p4a` varchar(500) DEFAULT NULL,
  `f3p4b` varchar(500) DEFAULT NULL,
  `f3p5a` varchar(500) DEFAULT NULL,
  `f3p5b` varchar(500) DEFAULT NULL,
  `f3p6a` varchar(500) DEFAULT NULL,
  `f3p6b` varchar(500) DEFAULT NULL,
  `f3p6c` varchar(500) DEFAULT NULL,
  `f3p6d` varchar(500) DEFAULT NULL,
  `f3p7a` varchar(500) DEFAULT NULL,
  `f3p8a` varchar(500) DEFAULT NULL,
  `f3p9a` varchar(500) DEFAULT NULL,
  `f3p10a` varchar(500) DEFAULT NULL,
  `f3p11a` varchar(500) DEFAULT NULL,
  `id_empresario` varchar(11) DEFAULT NULL,
  `id_consultor` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla inovekia_formulario_uno
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_formulario_uno`;

CREATE TABLE `inovekia_formulario_uno` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `f1p1a` varchar(500) DEFAULT NULL,
  `f1p2a` varchar(500) DEFAULT NULL,
  `f1p3a` varchar(500) DEFAULT NULL,
  `f1p4a` varchar(500) DEFAULT NULL,
  `f1p5a` varchar(500) DEFAULT NULL,
  `f1p5b` varchar(500) DEFAULT NULL,
  `f1p5c` varchar(500) DEFAULT NULL,
  `f1p6a` varchar(500) DEFAULT NULL,
  `f1p7a` varchar(500) DEFAULT NULL,
  `f1p8a` varchar(500) DEFAULT NULL,
  `f1p9a` varchar(500) DEFAULT NULL,
  `f1p10a` varchar(500) DEFAULT NULL,
  `f1p11a` varchar(500) DEFAULT NULL,
  `f1p12a` varchar(500) DEFAULT NULL,
  `f1p13a` varchar(500) DEFAULT NULL,
  `f1p14a` varchar(500) DEFAULT NULL,
  `f1p15a` varchar(500) DEFAULT NULL,
  `f1p16a` varchar(500) DEFAULT NULL,
  `f1p17a` varchar(500) DEFAULT NULL,
  `f1p18a` varchar(500) DEFAULT NULL,
  `f1p19a` varchar(500) DEFAULT NULL,
  `f1p20a` varchar(500) DEFAULT NULL,
  `f1p21a` varchar(500) DEFAULT NULL,
  `f1p22a` varchar(500) DEFAULT NULL,
  `f1p23a` varchar(500) DEFAULT NULL,
  `f1p24a` varchar(500) DEFAULT NULL,
  `f1p24b` varchar(500) DEFAULT NULL,
  `f1p25a` varchar(500) DEFAULT NULL,
  `f1p26a` varchar(500) DEFAULT NULL,
  `f1p26b` varchar(500) DEFAULT NULL,
  `f1p27a` varchar(500) DEFAULT NULL,
  `f1p28a` varchar(500) DEFAULT NULL,
  `f1p28b` varchar(500) DEFAULT NULL,
  `f1p29a` varchar(500) DEFAULT NULL,
  `f1p29b` varchar(500) DEFAULT NULL,
  `f1p30a` varchar(500) DEFAULT NULL,
  `f1p30b` varchar(500) DEFAULT NULL,
  `f1p30c` varchar(500) DEFAULT NULL,
  `f1p30d` varchar(500) DEFAULT NULL,
  `f1p31a` varchar(500) DEFAULT NULL,
  `f1p32a` varchar(500) DEFAULT NULL,
  `f1p33a` varchar(500) DEFAULT NULL,
  `f1p34a` varchar(500) DEFAULT NULL,
  `id_empresario` varchar(11) DEFAULT NULL,
  `id_consultor` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla inovekia_organismo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_organismo`;

CREATE TABLE `inovekia_organismo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL DEFAULT '',
  `activo` tinyint(5) NOT NULL DEFAULT '1',
  `creado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;



# Volcado de tabla inovekia_organismo_consultor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_organismo_consultor`;

CREATE TABLE `inovekia_organismo_consultor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_consultor` varchar(100) NOT NULL DEFAULT '',
  `id_organismo` varchar(100) NOT NULL DEFAULT '',
  `activo` tinyint(5) NOT NULL DEFAULT '1',
  `creado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;



# Volcado de tabla inovekia_seguimiento
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_seguimiento`;

CREATE TABLE `inovekia_seguimiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_consultor` int(11) NOT NULL,
  `id_empresario` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `ultimo_slide` int(11) NOT NULL,
  `seguimiento` text NOT NULL,
  `latitud` float NOT NULL,
  `longitud` float NOT NULL,
  `creado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12426 DEFAULT CHARSET=latin1;



# Volcado de tabla inovekia_visita_consultor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inovekia_visita_consultor`;

CREATE TABLE `inovekia_visita_consultor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_consultor` int(11) NOT NULL,
  `id_empresario` int(11) NOT NULL,
  `visita` tinyint(2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `creado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;



# Volcado de tabla installfile
# ------------------------------------------------------------

DROP TABLE IF EXISTS `installfile`;

CREATE TABLE `installfile` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idapp` int(11) DEFAULT NULL,
  `url` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;



# Volcado de tabla instancias
# ------------------------------------------------------------

DROP TABLE IF EXISTS `instancias`;

CREATE TABLE `instancias` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `base` varchar(255) DEFAULT NULL,
  `instancia` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12111 DEFAULT CHARSET=utf8;



# Volcado de tabla mass_upload
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mass_upload`;

CREATE TABLE `mass_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rc_name` varchar(255) NOT NULL DEFAULT '',
  `rc_phone` varchar(20) DEFAULT NULL,
  `rc_email` varchar(255) NOT NULL DEFAULT '',
  `rc_password` varchar(255) NOT NULL DEFAULT '',
  `rc_img` varchar(100) DEFAULT 'default.png',
  `c_razon` varchar(100) DEFAULT NULL,
  `c_rfc` varchar(14) DEFAULT NULL,
  `c_pais` varchar(100) DEFAULT NULL,
  `c_estado` varchar(50) DEFAULT NULL,
  `c_ciudad` varchar(50) DEFAULT NULL,
  `c_colonia` varchar(100) DEFAULT NULL,
  `c_direccion` varchar(100) DEFAULT NULL,
  `c_cp` varchar(6) DEFAULT NULL,
  `c_giro` varchar(100) DEFAULT NULL,
  `c_instancia` varchar(100) DEFAULT NULL,
  `apd_appname` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13794 DEFAULT CHARSET=utf8;



# Volcado de tabla parametros_licencias
# ------------------------------------------------------------

DROP TABLE IF EXISTS `parametros_licencias`;

CREATE TABLE `parametros_licencias` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `idclient` int(15) NOT NULL,
  `idcustomer` int(15) NOT NULL,
  `idapp` int(7) NOT NULL,
  `parametro` varchar(70) NOT NULL DEFAULT '',
  `valor` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=428 DEFAULT CHARSET=utf8;



# Volcado de tabla prontipagos_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `prontipagos_products`;

CREATE TABLE `prontipagos_products` (
  `intId` int(11) NOT NULL AUTO_INCREMENT,
  `strDescription` varchar(255) NOT NULL,
  `blnFixedFee` tinyint(4) NOT NULL DEFAULT '0',
  `decPrice` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `strProductName` varchar(255) NOT NULL,
  `strReference` varchar(255) NOT NULL,
  `strRegex` varchar(45) NOT NULL,
  `strType` varchar(45) NOT NULL,
  `strSku` varchar(255) NOT NULL,
  `strMethod` varchar(45) NOT NULL,
  PRIMARY KEY (`intId`),
  KEY `ix_01` (`strDescription`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=466 DEFAULT CHARSET=utf8;



# Volcado de tabla regcustomer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `regcustomer`;

CREATE TABLE `regcustomer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idfacebook` varchar(255) DEFAULT NULL,
  `idgoogle` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(100) DEFAULT NULL,
  `bloq_tiempo` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10240 DEFAULT CHARSET=utf8;



# Volcado de tabla relacion_profesores_alumnos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `relacion_profesores_alumnos`;

CREATE TABLE `relacion_profesores_alumnos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iduniversidad` int(11) DEFAULT NULL,
  `idprofesor` int(11) DEFAULT NULL,
  `idalumno` int(11) DEFAULT NULL,
  `idgrupo` varchar(20) DEFAULT NULL,
  `fsolicitud` datetime DEFAULT NULL,
  `fautorizada` datetime DEFAULT NULL,
  `ultima_revision` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=latin1;



# Volcado de tabla rubro
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rubro`;

CREATE TABLE `rubro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubro` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;



# Volcado de tabla salesforce
# ------------------------------------------------------------

DROP TABLE IF EXISTS `salesforce`;

CREATE TABLE `salesforce` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` char(6) DEFAULT NULL,
  `percent` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;



# Volcado de tabla sellers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sellers`;

CREATE TABLE `sellers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idseller` int(11) NOT NULL,
  `nameseller` varchar(250) NOT NULL DEFAULT '',
  `pic` varchar(250) NOT NULL DEFAULT '',
  `slogan` varchar(255) NOT NULL DEFAULT '',
  `descrip` varchar(255) NOT NULL DEFAULT '',
  `wep` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;



# Volcado de tabla smail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smail`;

CREATE TABLE `smail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `thtml` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;



# Volcado de tabla sqlfile
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sqlfile`;

CREATE TABLE `sqlfile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idapp` int(11) NOT NULL,
  `type_inst` varchar(100) NOT NULL,
  `file` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;



# Volcado de tabla ugrupo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ugrupo`;

CREATE TABLE `ugrupo` (
  `idgrupo` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL DEFAULT '',
  `idcu` int(11) DEFAULT NULL,
  PRIMARY KEY (`idgrupo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;



# Volcado de tabla uservalue
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uservalue`;

CREATE TABLE `uservalue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idclient` int(11) NOT NULL,
  `vdate` date DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `review` varchar(255) NOT NULL,
  `idapp` int(11) DEFAULT NULL,
  `score` decimal(11,1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;



# Volcado de tabla usuario_srpago
# ------------------------------------------------------------

DROP TABLE IF EXISTS `usuario_srpago`;

CREATE TABLE `usuario_srpago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(500) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `telefono_UNIQUE` (`telefono`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;





# Replace placeholder table for inovekia_empresario with correct view syntax
# ------------------------------------------------------------

DROP TABLE `inovekia_empresario`;

CREATE ALGORITHM=UNDEFINED DEFINER=`nmdevel`@`%` SQL SECURITY DEFINER VIEW `inovekia_empresario`
AS SELECT
   `customer`.`id` AS `id`,
   `customer`.`razon` AS `razon`,
   `customer`.`nombre` AS `nombre`,if(isnull(`customer`.`status_instancia`),0,1) AS `activo`,
   `customer`.`fecha` AS `creado`,
   `customer`.`fecha` AS `modificado`
FROM `customer`;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
