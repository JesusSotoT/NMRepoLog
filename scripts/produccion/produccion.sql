

CREATE TABLE `prd_lab_conceptos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_tipo` int(11) DEFAULT NULL,
  `parametro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_numeric` tinyint(1) DEFAULT NULL,
  `unidad` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `prd_lab_conceptos_productos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_lab_concepto` int(11) DEFAULT NULL,
  `lim_inf` float DEFAULT NULL,
  `lim_sup` float DEFAULT NULL,
  `referencia` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `prd_lab_plantilla` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `prd_lab_plantilla_detalle` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_plantilla` int(11) DEFAULT NULL,
  `id_concepto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `prd_lab_registro` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_orden_produccion` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `num_mezclas` int(11) DEFAULT NULL,
  `fecha_elaboracion` date DEFAULT NULL,
  `fecha_recepcion` date DEFAULT NULL,
  `fecha_liberacion` date DEFAULT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `fecha_analisis` date DEFAULT NULL,
  `lote_analisis` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lote_fabricacion` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lote_produccion` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `prd_lab_registro_detalle` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_reporte` int(11) DEFAULT NULL,
  `is_numeric` tinyint(1) DEFAULT NULL,
  `valor_num` int(11) DEFAULT '-1',
  `valor_alfa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `prd_lab_tipos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `prd_lab_unidades` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `prd_procesos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tiempo_hrs` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `prd_procesos_pasos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_proceso` int(11) DEFAULT NULL,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secuencia` int(11) DEFAULT NULL,
  `id_menu_registro` int(11) DEFAULT NULL,
  `tiempo_hrs` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `prd_productos_insumos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_insumo` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `u_medida` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `prd_productos_procesos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_proceso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
