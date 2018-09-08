
INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
	(2272, 'Autorizar cuentas por pagar', 2271, '../../modulos/xtructur/index.php?modulo=aut_cuentaspp', 1060, 0, 0, 0);


INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
	(2, 2272),
	(3, 2272);


UPDATE accelog_menu SET url = '../../modulos/xtructur/index.php?modulo=crear_presu_control'	WHERE idmenu = 1756;

ALTER TABLE constru_pedis ADD fpago int(11);
ALTER TABLE constru_pedis ADD obsgen varchar(600);


INSERT INTO `constru_deptos` (`id`, `clave_nomina`, `departamento`, `borrado`)
VALUES
	(1, 'DEPT-1', 'DEPTO OFICINA CENTRAL', 0),
	(2, 'DEPT-2', 'DEPTO ADMINISTRATIVO OBRA', 0);

ALTER TABLE constru_bit_remesas ADD id_bit_remesa int(11);

DELETE FROM accelog_perfiles_me where idmenu=1791;
DELETE FROM accelog_perfiles_me where idmenu=1790;
DELETE FROM accelog_menu where idmenu=1791;
DELETE FROM accelog_menu where idmenu=1790;


CREATE TABLE `constru_cheques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_remesa` int(11) DEFAULT NULL,
  `no_cheque` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banco` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_expedicion` timestamp NULL DEFAULT NULL,
  `estatus_cheque` int(1) DEFAULT NULL,
  `estatus_factura` int(1) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `remesa` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE constru_pedis ADD condpago VARCHAR(600) DEFAULT "";
ALTER TABLE constru_generales ADD telefono VARCHAR(600) DEFAULT "";




CREATE TABLE `constru_ocCanceladas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_pedi` int(11) DEFAULT NULL,
  `id_requi` int(11) DEFAULT NULL,
  `id_clave` int(11) DEFAULT NULL,
  `precio_compra` decimal(12,2) DEFAULT '0.00',
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



ALTER TABLE constru_info_sp ADD dias_credito decimal(12,2) DEFAULT '0.00';
ALTER TABLE constru_info_sp ADD limite_credito decimal(12,2) DEFAULT '0.00';

ALTER TABLE constru_info_tdo ADD dias_credito decimal(12,2) DEFAULT '0.00';
ALTER TABLE constru_info_tdo ADD limite_credito decimal(12,2) DEFAULT '0.00';

ALTER TABLE constru_ocCanceladas ADD cantidad decimal(12,2) DEFAULT '0.00';

ALTER TABLE constru_estimaciones_destajista ADD id_agru int(11) DEFAULT '0';
ALTER TABLE constru_estimaciones_destajista ADD id_area int(11) DEFAULT '0';
ALTER TABLE constru_estimaciones_destajista ADD id_esp int(11) DEFAULT '0';
ALTER TABLE constru_estimaciones_destajista ADD id_part int(11) DEFAULT '0';



ALTER TABLE constru_estimaciones_subcontratista ADD id_agru int(11) DEFAULT '0';
ALTER TABLE constru_estimaciones_subcontratista ADD id_area int(11) DEFAULT '0';
ALTER TABLE constru_estimaciones_subcontratista ADD id_esp int(11) DEFAULT '0';
ALTER TABLE constru_estimaciones_subcontratista ADD id_part int(11) DEFAULT '0';

DELETE FROM accelog_perfiles_me WHERE idmenu=1754;
DELETE FROM accelog_menu where idmenu=1754 AND nombre="Materiales";

ALTER TABLE constru_info_sp ADD por_fondo_garantia decimal(12,2) DEFAULT '0.00';
ALTER TABLE constru_info_sp ADD por_retencion decimal(12,2) DEFAULT '0.00';
ALTER TABLE constru_requis ADD obs_cancel varchar(600) DEFAULT '';

ALTER TABLE constru_asignaciones ADD po_fecha datetime DEFAULT NULL;
ALTER TABLE constru_asignaciones ADD po_dias decimal(12,6) DEFAULT NULL;
ALTER TABLE constru_asignaciones ADD po_rendimiento decimal(12,6) DEFAULT NULL;



CREATE TABLE `constru_uploadPO` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `presupuestoxls` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `opcion` varchar(1) CHARACTER SET latin1 DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `id_obra` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `constru_poGanttLink` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_link` varchar(200) DEFAULT NULL,
  `source` varchar(200) DEFAULT NULL,
  `target` varchar(200) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `constru_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `autorizaciones` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

	
alter table constru_altas change id_partida id_partida varchar(500);
alter table constru_desgloce ADD por decimal(12,2) DEFAULT '0.00';
alter table constru_config ADD tiempo int(11) DEFAULT '1';
insert into constru_contratista (nombre) values ('...');


ALTER TABLE constru_requisiciones ADD elprov int(11) DEFAULT NULL;


ALTER TABLE constru_remesas ADD proviene varchar(45);
ALTER TABLE constru_cheques ADD remesa int(11) DEFAULT 0;


INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2278, 'Autorizacion prenomina campo', 1767, '../../modulos/xtructur/index.php?modulo=prenom_oce', 1060, 0, 8, 0);


INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(2, 2278),
(3, 2278);

INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2276, 'Autorizacion prenomina oficina central', 1767, '../../modulos/xtructur/index.php?modulo=prenom_ocen', 1060, 0, 7, 0);



INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2276),
(2, 2276);

ALTER TABLE constru_estimaciones_bit_destajista ADD id_autorizo2 int(11);
ALTER TABLE constru_estimaciones_bit_prov ADD id_autorizo2 int(11);
ALTER TABLE constru_estimaciones_bit_subcontratista ADD id_autorizo2 int(11);
ALTER TABLE constru_requis ADD autorizo int(11);

INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2279, 'Autorizacion estimacion caja chica', 1779, '../../modulos/xtructur/index.php?modulo=viz_cc', 1060, 0, 11, 0);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2279),
(2, 2279);

INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2281, 'Autorizacion estimacion indirectos', 1779, '../../modulos/xtructur/index.php?modulo=viz_ind', 1060, 0, 12, 0);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2281),
(2, 2281);


INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
	(2266, 'Tablero Solicitudes Pendientes', 0, '../../modulos/xtructur/index.php?modulo=tablero', 1058, 0, 0, 0);

	INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2266),
(2, 2266);


INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2287, 'Historial de movimientos - Nóminas', 2293, '../../modulos/xtructur/index.php?modulo=historialnom', 1057, 0, 9, 0);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2287),
(2, 2287);

ALTER TABLE constru_bit_nominaca ADD fechaaut datetime;
ALTER TABLE constru_bit_nominadest ADD fechaaut datetime;
ALTER TABLE constru_estimaciones_bit_destajista ADD fechaaut datetime;

ALTER TABLE constru_bit_nominaca ADD id_aut2 int(11);
ALTER TABLE constru_bit_nominadest ADD id_aut2 int(11);

ALTER TABLE constru_estimaciones_bit_chica ADD fechaaut datetime;
ALTER TABLE constru_estimaciones_bit_cliente ADD fechaaut datetime;
ALTER TABLE constru_estimaciones_bit_indirectos ADD fechaaut datetime;
ALTER TABLE constru_estimaciones_bit_prov ADD fechaaut datetime;

ALTER TABLE constru_estimaciones_bit_subcontratista ADD fechaaut datetime;

ALTER TABLE constru_estimaciones_bit_chica ADD id_aut2 int(11);
ALTER TABLE constru_estimaciones_bit_chica ADD id_autorizo2 int(11);
ALTER TABLE constru_estimaciones_bit_cliente ADD id_aut2 int(11);
ALTER TABLE constru_estimaciones_bit_cliente ADD id_autorizo2 int(11);
ALTER TABLE constru_estimaciones_bit_indirectos ADD id_aut2 int(11);
ALTER TABLE constru_estimaciones_bit_indirectos ADD id_autorizo2 int(11);

INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2290, 'Historial de movimientos - Estimaciones', 2293, '../../modulos/xtructur/index.php?modulo=historialest', 1057, 0, 12, 0);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2290),
(2, 2290);

ALTER TABLE constru_bit_remesas ADD fechaaut datetime;
ALTER TABLE constru_bit_remesas ADD idaut int(11);

INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2291, 'Historial de movimientos - Pasivos', 2293, '../../modulos/xtructur/index.php?modulo=historialpas', 1057, 0, 4, 0);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2291),
(2, 2291);

ALTER TABLE constru_pedis ADD fechaaut datetime;

ALTER TABLE constru_pedis ADD idaut int(11);

ALTER TABLE constru_estimaciones_chica ADD fechaaut datetime;

ALTER TABLE constru_estimaciones_chica ADD idaut int(11);

ALTER TABLE constru_requis ADD fechaaut datetime;


CREATE TABLE `constru_notilog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `modulo` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2295, 'Notificaciones', 0, '../../modulos/xtructur/index.php?modulo=notilog', 1058, 0, 6, 0);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2291),
(2, 2291);

update accelog_menu set url='../../modulos/xtructur/index.php?modulo=adic_control' where idmenu=‘1756’;

update accelog_menu set url='../../modulos/xtructur/index.php?modulo=extra_control' where idmenu=‘1755’;

update accelog_menu set url='../../modulos/xtructur/index.php?modulo=nocob_control' where idmenu=‘1757’;


INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2302, 'Autorizacion de Extraordinarios', 2270, '../../modulos/xtructur/index.php?modulo=extra_aut', 1060, 0, 4, 0);


INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2302),
(2, 2302);



INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2305, 'Autorizacion de Adicionales', 2270, '../../modulos/xtructur/index.php?modulo=adic_aut', 1060, 0, 5, 0);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2305),
(2, 2305);


INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2304, 'Autorizacion de No cobrables', 2270, '../../modulos/xtructur/index.php?modulo=nocob_aut', 1060, 0, 6, 0);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2304),
(2, 2304);

CREATE TABLE `constru_bit_solicitudes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_solicito` int(11) DEFAULT '0',
  `fecha` datetime DEFAULT NULL,
  `total` decimal(12,2) DEFAULT '0.00',
  `borrado` tinyint(1) DEFAULT '0',
  `id_aut` int(11) DEFAULT '0',
  `estatus` tinyint(1) DEFAULT '0',
  `fechaaut` datetime DEFAULT NULL,
  `naturaleza` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE constru_recurso ADD autorizado int(11) DEFAULT 1 ;
ALTER TABLE constru_recurso ADD id_bit_solicitud int(11);
ALTER TABLE constru_recurso ADD justificacion varchar(500);
ALTER TABLE constru_recurso ADD sestemp int(11);


ALTER TABLE constru_config ADD puaut int(11) DEFAULT 1;


CREATE TABLE `constru_bit_traspasos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra_salida` int(11) DEFAULT NULL,
  `id_obra_entrada` int(11) DEFAULT NULL,
  `id_solicito` int(11) DEFAULT '0',
  `fecha_solicito` datetime DEFAULT NULL,
  `total` decimal(12,2) DEFAULT '0.00',
  `borrado` tinyint(1) DEFAULT '0',
  `id_aut_os` int(11) DEFAULT '0',
  `id_aut_oe` int(11) DEFAULT '0',
  `id_quien_os` int(11) DEFAULT '0',
  `id_quien_oe` int(11) DEFAULT '0',
  `estatus` tinyint(1) DEFAULT '0',
  `fecha_aut_os` datetime DEFAULT NULL,
  `fecha_aut_oe` datetime DEFAULT NULL,
  `justificacion_os` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `justificacion_oe` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `constru_traspasos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra_sal` int(11) DEFAULT NULL,
  `id_obra_ent` int(11) DEFAULT NULL,
  `id_clave` int(11) DEFAULT NULL,
  `id_bit_traspaso` int(11) DEFAULT NULL,
  `cantidad` decimal(12,2) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `sestmp` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_id_clave` (`id_clave`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
	(2320, 'Traspaso almacenes', 0, '../../modulos/xtructur/index.php?modulo=traspaso', 1058, 0, 6, 0);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(3, 2320),
(2, 2320);

ALTER TABLE constru_estimaciones_bit_cliente ADD factura varchar(100);

CREATE TABLE IF NOT EXISTS `constru_cheques2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_cobro` int(11) DEFAULT NULL,
  `no_cheque` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banco` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_expedicion` timestamp NULL DEFAULT NULL,
  `estatus_cheque` int(1) DEFAULT NULL,
  `estatus_factura` int(1) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `cobro` int(11) DEFAULT '0',
  `mpago` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `constru_cobros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_bit_cobros` int(11) DEFAULT NULL,
  `id_esti` int(11) DEFAULT NULL,
  `imp_sem` decimal(12,2) DEFAULT '0.00',
  `semana` int(11) DEFAULT '0',
  `borrado` tinyint(1) DEFAULT '0',
  `sestmp` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  `cheque` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banco` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_exp` timestamp NULL DEFAULT NULL,
  `estatus_cheque` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus_factura` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `proviene` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS  `constru_bit_cobros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `tot_pasiv` decimal(12,2) DEFAULT '0.00',
  `cob_aut` decimal(12,2) DEFAULT '0.00',
  `semana` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `id_solicito` int(11) DEFAULT '0',
  `fechaaut` datetime DEFAULT NULL,
  `idaut` int(11) DEFAULT NULL,
  `id_aut` int(11) DEFAULT NULL,
  `estatus` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2323, 'Cuentas por Cobrar', 0, NULL, 1060, 0, 7, 0);

INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2324, 'Cuentas por Cobrar', 2323, '../../modulos/xtructur/index.php?modulo=cobros', 1060, 0, 1, 0);

INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2325, 'Cuentas Cobradas', 2323, '../../modulos/xtructur/index.php?modulo=cobrado', 1060, 0, 2, 0);

INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
	(2326, 'Importar proveedores', 1749, '../../modulos/xtructur/index.php?modulo=imp_proveedores', 1059, 0, 8, 0),
	(2327, 'Importar subcontratistas', 1749, '../../modulos/xtructur/index.php?modulo=imp_subcontratistas', 1059, 0, 9, 0);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(2, 2325),
(3, 2325);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(2, 2323),
(3, 2323);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(2, 2324),
(3, 2324);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(2, 2326),
(3, 2326);

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
(2, 2327),
(3, 2327);

ALTER TABLE constru_generales ADD residente varchar(200);







