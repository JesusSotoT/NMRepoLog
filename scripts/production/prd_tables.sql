CREATE TABLE `prd_lab_conceptos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_tipo` int(11) DEFAULT NULL,
  `parametro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_numeric` tinyint(1) DEFAULT NULL,
  `unidad` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `prd_lab_conceptos_productos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_lab_concepto` int(11) DEFAULT NULL,
  `lim_inf` float DEFAULT NULL,
  `lim_sup` float DEFAULT NULL,
  `referencia` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `prd_lab_tipos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `prd_lab_tipos` (`id`, `descripcion`)
VALUES
	(1, 'General'),
	(2, 'Distribución de partícula en malla'),
	(3, 'Parámetros organolépticos'),
	(4, 'Análisis Microbiológicos');

CREATE TABLE `prd_lab_unidades` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `prd_lab_unidades` (`id`, `descripcion`)
VALUES
	(1, 'N/A'),
	(2, 'g/ml'),
	(3, '%'),
	(4, 'ml'),
	(5, 'cm2');

CREATE TABLE `prd_procesos` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `nombre` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `tiempo_hrs` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `prd_procesos` (`id`, `nombre`, `tiempo_hrs`)
VALUES
	(1, 'Orden de Producción', 1),
	(2, 'Calidad', 2),
	(3, 'Envasado', 3),
	(4, 'Loteo', 4),
	(5, 'Envasado Master', 5),
	(6, 'Almacén', 6);

  CREATE TABLE `prd_procesos_pasos` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `id_proceso` int(11) DEFAULT NULL,
    `descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `secuencia` int(11) DEFAULT NULL,
    `id_menu_registro` int(11) DEFAULT NULL,
    `tiempo_hrs` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

  INSERT INTO `prd_procesos_pasos` (`id`, `id_proceso`, `descripcion`, `secuencia`, `id_menu_registro`, `tiempo_hrs`)
VALUES
	(1, 1, 'Formulación de insumos', 1, NULL, 0),
	(2, 1, 'Pesado de producto', 2, NULL, 0),
	(3, 1, 'Registro de proceso', 3, NULL, 0),
	(4, 2, 'Análisis laboratorio producto', 1, NULL, 0),
	(5, 2, 'Registro de batch', 2, NULL, 0),
	(6, 2, 'Registro de merma proceso', 3, NULL, 0),
	(7, 3, 'Registro de proceso', 1, NULL, 0),
	(8, 3, 'Registro de calidad', 2, NULL, 0),
	(9, 4, 'Tiempos y movimientos', 1, NULL, 0),
	(10, 5, 'Tiempos y movimientos', 1, NULL, 0),
	(11, 5, 'Envase caja master', 2, NULL, 0),
	(12, 5, 'Registro de merma', 3, NULL, 0),
	(13, 6, 'Entrada automática', 1, NULL, 0),
	(14, 6, 'Almacén producto terminado', 2, NULL, 0);

  CREATE TABLE `prd_productos_procesos` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `id_producto` int(11) DEFAULT NULL,
    `id_proceso` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
