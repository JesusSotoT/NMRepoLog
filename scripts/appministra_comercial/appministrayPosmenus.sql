INSERT IGNORE INTO `accelog_categorias` (`idcategoria`, `nombre`, `icono`, `orden`)
VALUES
  (1042, 'Punto de Venta', 0, 1),
  (1043, 'Facturacion', 0, 2),
  (1044, 'Compras', 0, 3),
  (1045, 'Ventas', 0, 4),
  (1046, 'Inventario', 0, 5),
  (1047, 'Administracion', 0, 6),
  (1048, 'Catalogos', 0, 7),
  (1049, 'Reportes', 0, 8),
  (1050, 'Configuracion', 0, 9);


INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (1974, 'Requisiciones', 0, '../../modulos/appministra/index.php?c=compras&f=requisiciones', 1044, 0, 3, 0),
  (1975, 'Recepcion', 0, '../../modulos/appministra/index.php?c=compras&f=recepcion', 1044, 0, 5, 0),
  (1980, 'Ordenes de compra', 0, '../../modulos/appministra/index.php?c=compras&f=ordenes', 1044, 0, 4, 0),
  (2092, 'Envios', 0, '../../modulos/appministra/index.php?c=ventas&f=envios', 1045, 0, 3, 0),
  (2122, 'Facturacion masiva', 0, '../../modulos/appministra/index.php?c=ventas&f=masiva', 1045, 0, 4, 0),
  (1999, 'Cotizaciones', 0, '../../modulos/appministra/index.php?c=ventas&f=requisiciones', 1045, 0, 1, 0),
  (2007, 'Ordenes de venta', 0, '../../modulos/appministra/index.php?c=ventas&f=ordenes', 1045, 0, 2, 0),
  (2010, 'Movimientos de Inventario', 0, '../../modulos/appministra/index.php?c=inventarios&f=entradas', 1046, 0, 1, 0),
  (2080, 'Cuentas por Pagar', 0, '', 1047, 0, 2, 0),
  (2081, 'Aplicar pago', 2080, '../../modulos/appministra/index.php?c=cuentas&f=cuentasxpagar', 1047, 0, 1, 0),
  (2096, 'Cuentas por Cobrar', 0, NULL, 1047, 0, 1, 0),
  (2097, 'Aplicar cobro', 2096, '../../modulos/appministra/index.php?c=cuentas&f=cuentasxcobrar', 1047, 0, 1, 0),
  (2049, 'Clientes AppH', 0, '../../modulos/pos/index.php?c=cliente&f=indexGrid', 1048, 0, 4, 0),
  (2142, 'Importar clientes', 0, '../../modulos/punto_venta/views/clientes/importar_clientes.php', 1048, 0, 1, 0),
  (2143, 'Importar proveedores', 0, '../../modulos/punto_venta/views/proveedores/importar_proveedores.php', 1048, 0, 2, 0),
  (2144, 'Beneficiarios/Proveedores ', 0, '../../modulos/punto_venta/catalogos/proveedor.php', 1048, 0, 5, 0),
  (1993, 'Impuestos', 0, '../../modulos/appministra/index.php?c=configuracion&f=impuestos', 1048, 0, 6, 0),
  (2034, 'Productos', 0, '../../modulos/pos/index.php?c=producto&f=indexGridProductos', 1048, 0, 3, 0),
  (2085, 'Cuentas por Pagar', 0, NULL, 1049, 0, 4, 0),
  (2086, 'Resumen de Saldos por Proveedor', 2085, '../../modulos/appministra/index.php?c=Reportes_Cuentas&f=resumen_saldos', 1049, 0, 1, 0),
  (2087, 'Auxiliar Movimientos Cuentas por Pagar', 2085, '../../modulos/appministra/index.php?c=Reportes_Cuentas&f=aux_mov_cxp', 1049, 0, 2, 0),
  (2088, 'Antigüedad de Saldos Proveedores', 2085, '../../modulos/appministra/index.php?c=Reportes_Cuentas&f=ant_saldos', 1049, 0, 3, 0),
  (2089, 'Pronóstico de Pagos', 2085, '../../modulos/appministra/index.php?c=Reportes_Cuentas&f=pronos_pagos', 1049, 0, 4, 0),
  (2095, 'Cuentas por Cobrar', 0, NULL, 1049, 0, 5, 0),
  (2098, 'Resumen de Saldos por Cliente', 2095, '../../modulos/appministra/index.php?c=Reportes_Cuentas&f=resumen_saldos_cobrar', 1049, 0, 1, 0),
  (2099, 'Auxiliar de Movimientos Cuentas por Cobrar', 2095, '../../modulos/appministra/index.php?c=Reportes_Cuentas&f=aux_mov_cxc', 1049, 0, 2, 0),
  (2100, 'Antigüedad de Saldos Clientes', 2095, '../../modulos/appministra/index.php?c=Reportes_Cuentas&f=ant_saldos_cxc', 1049, 0, 3, 0),
  (2101, 'Pronóstico de Cobros', 2095, '../../modulos/appministra/index.php?c=Reportes_Cuentas&f=pronos_cobros', 1049, 0, 4, 0),
  (2102, 'Inventarios', 0, NULL, 1049, 0, 1, 0),
  (2103, 'Kardex', 2102, '../../modulos/appministra/index.php?c=reportes&f=kardex', 1049, 0, 5, 0),
  (2104, 'Existencias', 2102, '../../modulos/appministra/index.php?c=reportes&f=existencias', 1049, 0, 3, 0),
  (2105, 'Catalogo Productos y Servicios', 2102, '../../modulos/appministra/index.php?c=reportes&f=cataproductos', 1049, 0, 6, 0),
  (2110, 'Reportes compras', 0, '', 1049, 0, 2, 0),
  (2111, 'Grafica mas comprados', 2110, '../../modulos/appministra/index.php?c=reportes_compras&f=grafi_compras', 1049, 0, 1, 0),
  (2112, 'Reportes ventas', 0, NULL, 1049, 0, 3, 0),
  (2113, 'Grafica mas vendidos', 2112, '../../modulos/appministra/index.php?c=reportes_ventas&f=grafi_ventas', 1049, 0, 1, 0),
  (2118, 'Series, Lotes, Pedimentos y Caducos', 2102, '../../modulos/appministra/index.php?c=inventarios&f=reporte_slp', 1049, NULL, 7, NULL),
  (2124, 'Inventario Actual', 2102, '../../modulos/appministra/index.php?c=reportes&f=inventarioactual', 1049, 0, 2, 0),
  (2125, 'Compras por proveedor y por producto', 2110, '../../modulos/appministra/index.php?c=Reportes_Compras&f=prov_prod', 1049, 0, 2, 0),
  (2126, 'Cobranza por vendedor', 2112, '../../modulos/appministra/index.php?c=reportes_ventas&f=cobranza_vendedor', 1049, 0, 3, 0),
  (2127, 'Reporte Facturas', 2112, '../../modulos/appministra/index.php?c=ventas&f=facturas', 1049, 0, 2, 0),
  (2145, 'Contabilidad Electronica', 0, NULL, 1049, 0, 6, 0),
  (2146, 'Almacén Digital', 2145, '../../modulos/cont/index.php?c=Reports&f=almacenXML', 1049, 0, 1, 0),
  (2135, 'Almacenes', 0, '', 1050, 0, 11, 0),
  (2136, 'Arbol de Almacenes', 2135, '../../modulos/appministra/index.php?c=almacenes&f=index', 1050, 0, 2, 0),
  (2137, 'Mi Organización', 2147, '../catalog/gestor.php?idestructura=1&ticket=testing', 1050, 0, 2, 0),
  (2138, 'Bienvenido', 2147, '../../modulos/inicio/index.php', 1050, 0, 1, 0),
  (2139, 'Perfiles de Usuario', 2147, '../../modulos/perfiles/index.php', 1050, 0, 3, 0),
  (2140, 'Administración Usuarios', 2147, '../catalog/gestor.php?idestructura=47&ticket=testing', 1050, 0, 4, 0),
  (2141, 'Color de Interfaz', 2147, '../../modulos/styleselector/index.php', 1050, 0, 5, 0),
  (2147, 'General', 0, '', 1050, 0, 2, 0),
  (2148, 'Facturacion', 0, '', 1050, 0, 3, 0),
  (2149, 'Datos de Facturacion', 2148, '../catalog/gestor.php?idestructura=234&ticket=testing', 1050, 0, 1, 0),
  (2150, 'Serie y Folio', 2148, '../catalog/gestor.php?idestructura=221&ticket=testing', 1050, 0, 2, 0),
  (2151, 'Clasificador Ingresos-Egresos', 0, '../catalog/gestor.php?idestructura=285&ticket=testing', 1050, 0, 5, 0),
  (2152, 'Sucursal', 2135, '../catalog/gestor.php?idestructura=86&ticket=testing', 1050, 0, 1, 0),
  (1960, 'Avanzada', 0, '../../modulos/appministra/index.php?c=configuracion&f=general&p=0', 1050, 0, 1, 0),
  (1961, 'Clasificadores', 0, '../../modulos/appministra/index.php?c=configuracion&f=clasificadores', 1050, 0, 4, 0),
  (1985, 'Clasificadores Productos', 0, '../../modulos/appministra/index.php?c=configuracion&f=clasificadoresProd', 1050, 0, 6, 0),
  (1986, 'Caracteristicas Productos', 0, '../../modulos/appministra/index.php?c=configuracion&f=caracteristicasProd', 1050, 0, 7, 0),
  (1987, 'Tipos de Crédito', 0, '../../modulos/appministra/index.php?c=configuracion&f=credito', 1050, 0, 8, 0),
  (1988, 'Listas de Precios', 0, '../../modulos/appministra/index.php?c=configuracion&f=listas_precio', 1050, 0, 10, 0),
  (1990, 'Unidades de Medida y Peso', 0, '../../modulos/appministra/index.php?c=configuracion&f=medida', 1050, 0, 9, 0),
  (2155, 'Empleados', 0, '../catalog/gestor.php?idestructura=301&ticket=testing', 1048, 0, 5, 0);

  INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
  (2176, 'Area Empleado', 0, '../catalog/gestor.php?idestructura=364&ticket=testing', 1048, 0, 7, 0);

/*--------------Menus POS-------------------*/
/*Punto de Venta*/
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
  (2051, 'Caja AppH', 0, '../../modulos/pos/index.php?c=caja&f=indexCaja', 1042, 0, 1, 0),
  (2094, 'Retiro de Caja', 0, '../../modulos/pos/index.php?c=retiro&f=imprimeretiro', 1042, 0, 2, 0),
  (2115, 'Cortes de Caja', 0, '../../modulos/pos/index.php?c=caja&f=cortesGrid', 1042, 0, 3, 0);
/*Facturacion */
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
  (2153, 'Listado Facturas', 0, '../repolog/repolog.php?i=10', 1043, 0, 1, 0),
  (2154, 'Ventas No Facturadas', 0, '../repolog/repolog.php?i=43', 1043, 0, 2, 0);
/*Compras*/
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
  (2032, 'Ordenes de Compra POS', 0, '../../modulos/pos/index.php?c=compra&f=indexgrid', 1044, 0, 1, 0),
  (2033, 'Recepcion Orden Compra POS', 0, '../../modulos/pos/index.php?c=compra&f=recepcionGrid', 1044, 0, 2, 0);
/*Ventas*/
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
  (2106, 'Ventas', 0, '../../modulos/pos/index.php?c=caja&f=ventasGrid', 1045, 0, 20, 0);

/*Inventario*/
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
  (2010, 'Movimientos de Inventario', 0, '../../modulos/appministra/index.php?c=inventarios&f=entradas', 1046, 0, 1, 0);

/* Catalogos */
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
  (1993, 'Impuestos', 0, '../../modulos/appministra/index.php?c=configuracion&f=impuestos', 1048, 0, 6, 0),
  (2034, 'Productos', 0, '../../modulos/pos/index.php?c=producto&f=indexGridProductos', 1048, 0, 3, 0),
  (2049, 'Clientes AppH', 0, '../../modulos/pos/index.php?c=cliente&f=indexGrid', 1048, 0, 4, 0),
  (2142, 'Importar clientes', 0, '../../modulos/punto_venta/views/clientes/importar_clientes.php', 1048, 0, 1, 0),
  (2143, 'Importar proveedores', 0, '../../modulos/punto_venta/views/proveedores/importar_proveedores.php', 1048, 0, 2, 0),
  (2144, 'Beneficiarios/Proveedores ', 0, '../../modulos/punto_venta/catalogos/proveedor.php', 1048, 0, 5, 0);
/*Reportes*/  
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
  (2102, 'Inventarios', 0, NULL, 1049, 0, 1, 0),
  (2108, 'Inventario Actual POS', 2102, '../../modulos/pos/index.php?c=inventario&f=inventarioActual', 1049, 0, 1, 0),
  (2036, 'Kardex POS', 2102, '../../modulos/pos/index.php?c=inventario&f=indexGrid', 1049, 0, 4, 0),
  (2128, 'Movimientos Inventario', 2102, '../../modulos/appministra/index.php?c=reportes&f=movinventario', 1049, 0, 6, 0);
/*Configuracion*/
/*INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
  (1960, 'Avanzada', 0, '../../modulos/appministra/index.php?c=configuracion&f=general&p=0', 1050, 0, 1, 0),
  (1961, 'Clasificadores', 0, '../../modulos/appministra/index.php?c=configuracion&f=clasificadores', 1050, 0, 4, 0),
  (1985, 'Clasificadores Productos', 0, '../../modulos/appministra/index.php?c=configuracion&f=clasificadoresProd', 1050, 0, 6, 0),
  (1986, 'Caracteristicas Productos', 0, '../../modulos/appministra/index.php?c=configuracion&f=caracteristicasProd', 1050, 0, 7, 0),
  (1990, 'Unidades de Medida y Peso', 0, '../../modulos/appministra/index.php?c=configuracion&f=medida', 1050, 0, 9, 0),
  (2135, 'Almacenes', 0, '', 1050, 0, 2, 0),
  (2136, 'Arbol de Almacenes', 2135, '../../modulos/appministra/index.php?c=almacenes&f=index', 1050, 0, 2, 0),
  (2137, 'Mi Organización', 2147, '../catalog/gestor.php?idestructura=1&ticket=testing', 1050, 0, 2, 0),
  (2138, 'Bienvenido', 2147, '../../modulos/inicio/index.php', 1050, 0, 1, 0),
  (2139, 'Perfiles de Usuario', 2147, '../../modulos/perfiles/index.php', 1050, 0, 3, 0),
  (2140, 'Administración Usuarios', 2147, '../catalog/gestor.php?idestructura=47&ticket=testing', 1050, 0, 4, 0),
  (2141, 'Color de Interfaz', 2147, '../../modulos/styleselector/index.php', 1050, 0, 5, 0),
  (2147, 'General', 0, '', 1050, 0, 2, 0),
  (2148, 'Facturacion', 0, '', 1050, 0, 3, 0),
  (2149, 'Datos de Facturacion', 2148, '../catalog/gestor.php?idestructura=234&ticket=testing', 1050, 0, 1, 0),
  (2150, 'Serie y Folio', 2148, '../catalog/gestor.php?idestructura=221&ticket=testing', 1050, 0, 2, 0),
  (2152, 'Sucursal', 2135, '../catalog/gestor.php?idestructura=86&ticket=testing', 1050, 0, 1, 0); */

INSERT IGNORE INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
(2179, 'Ventas por vendedor', 2112, '../../modulos/appministra/index.php?c=reportes&f=ventasVendedor', 1049, 0, 4, 0);


INSERT IGNORE INTO accelog_perfiles_me (idperfil,idmenu) values (2,2051),
(2,2094),
(2,2115),
(2,2153),
(2,2154),
(2,2032),
(2,2033),
(2,2106),
(2,2010),
(2,1993),
(2,2034),
(2,2049),
(2,2142),
(2,2143),
(2,2144),
(2,2102),
(2,2108),
(2,2036),
(2,1960),
(2,1961),
(2,1985),
(2,1986),
(2,1990),
(2,2135),
(2,2136),
(2,2137),
(2,2138),
(2,2139),
(2,2140),
(2,2141),
(2,2147),
(2,2148),
(2,2149),
(2,2150),
(2,2152),
(2,2179);
/*-------------------------------------------------------------------------------------------*/

  -- Create syntax for TABLE 'app_almacen_tipo'
CREATE TABLE IF NOT EXISTS `app_almacen_tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_almacenes'
CREATE TABLE IF NOT EXISTS `app_almacenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_sistema` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `codigo_manual` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_padre` int(11) DEFAULT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `direccion` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_almacen_tipo` tinyint(1) NOT NULL,
  `id_empleado_encargado` int(11) DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ext` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `es_consignacion` tinyint(1) NOT NULL,
  `id_clasificador` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_area_empleado'
CREATE TABLE IF NOT EXISTS `app_area_empleado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_campos_foodware'
CREATE TABLE IF NOT EXISTS `app_campos_foodware` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `vendible` int(11) DEFAULT '1',
  `h_ini` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `h_fin` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dias` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ganancia` decimal(10,4) DEFAULT NULL,
  `rate` double DEFAULT '0',
  `descripcion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_producto` (`id_producto`) USING BTREE,
  CONSTRAINT `app_campos_foodware_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `app_productos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_caracteristicas_hija'
CREATE TABLE IF NOT EXISTS `app_caracteristicas_hija` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_caracteristica_padre` int(11) NOT NULL,
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_caracteristicas_padre'
CREATE TABLE IF NOT EXISTS `app_caracteristicas_padre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_clasificadores'
CREATE TABLE IF NOT EXISTS `app_clasificadores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `padre` int(5) NOT NULL DEFAULT '0',
  `tipo` int(1) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_configuracion'
CREATE TABLE IF NOT EXISTS `app_configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ejercicio_actual` int(2) NOT NULL,
  `permitir_cerrados` tinyint(1) NOT NULL DEFAULT '0',
  `id_periodo_actual` int(2) NOT NULL,
  `periodos_abiertos` tinyint(1) NOT NULL,
  `id_costeo_general` int(3) NOT NULL,
  `salidas_sin_existencia` tinyint(1) NOT NULL,
  `id_costeo_salida` tinyint(4) NOT NULL,
  `iva` tinyint(4) NOT NULL,
  `ieps` float NOT NULL,
  `ret_iva` float NOT NULL,
  `ret_isr` float NOT NULL,
  `ish` float NOT NULL,
  `pol_aut` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_consignacion'
CREATE TABLE IF NOT EXISTS `app_consignacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_recepcion` int(11) DEFAULT NULL,
  `id_encargado` int(11) DEFAULT NULL,
  `observaciones` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `fecha_compra` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `no_factura` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_factura` datetime DEFAULT NULL,
  `imp_factura` double DEFAULT NULL,
  `xmlfile` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc_concepto` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_consignacion_datos'
CREATE TABLE IF NOT EXISTS `app_consignacion_datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_consignacion` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_recepcion` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `caracteristica` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_costeo'
CREATE TABLE IF NOT EXISTS `app_costeo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(35) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_costos_proveedor'
CREATE TABLE IF NOT EXISTS `app_costos_proveedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_moneda` int(11) DEFAULT NULL,
  `costo` double DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_departamento'
CREATE TABLE IF NOT EXISTS `app_departamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_dfl_nombres'
CREATE TABLE IF NOT EXISTS `app_dfl_nombres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabla` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_ejercicios'
CREATE TABLE IF NOT EXISTS `app_ejercicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` int(4) NOT NULL,
  `cerrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_envios'
CREATE TABLE IF NOT EXISTS `app_envios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oventa` int(11) DEFAULT NULL,
  `id_encargado` int(11) DEFAULT NULL,
  `observaciones` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `no_factura` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_factura` datetime DEFAULT NULL,
  `imp_factura` double DEFAULT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `xmlfile` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc_concepto` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `impuestos` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observacion` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facturo` int(11) DEFAULT '0',
  `forma_pago` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_envios_datos'
CREATE TABLE IF NOT EXISTS `app_envios_datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oventa` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_envio` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `id_lote` int(11) DEFAULT '0',
  `id_pedimento` int(11) DEFAULT '0',
  `estatus` int(11) DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `caracteristica` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_familia'
CREATE TABLE IF NOT EXISTS `app_familia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_departamento` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_impuesto'
CREATE TABLE IF NOT EXISTS `app_impuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `valor` double NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `clave` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_inventario'
CREATE TABLE IF NOT EXISTS `app_inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `cantidad` double NOT NULL,
  `id_almacen` int(11) NOT NULL,
  `apartados` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_inventario_movimientos'
CREATE TABLE IF NOT EXISTS `app_inventario_movimientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `id_producto_caracteristica` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '''0''',
  `id_pedimento` int(11) NOT NULL DEFAULT '0',
  `id_lote` int(11) NOT NULL DEFAULT '0',
  `cantidad` double NOT NULL,
  `importe` double NOT NULL,
  `id_almacen_origen` int(11) DEFAULT '0',
  `id_almacen_destino` int(11) DEFAULT '0',
  `fecha` datetime NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `tipo_traspaso` int(1) NOT NULL,
  `costo` double DEFAULT NULL,
  `referencia` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT '1',
  `origen` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=342 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_linea'
CREATE TABLE IF NOT EXISTS `app_linea` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_familia` int(3) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_lista_precio'
CREATE TABLE IF NOT EXISTS `app_lista_precio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `porcentaje` double NOT NULL,
  `descuento` tinyint(1) NOT NULL DEFAULT '1',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_lista_precio_prods'
CREATE TABLE IF NOT EXISTS `app_lista_precio_prods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lista` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_merma'
CREATE TABLE IF NOT EXISTS `app_merma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `productos` double DEFAULT NULL,
  `importe` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_merma_datos'
CREATE TABLE IF NOT EXISTS `app_merma_datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_merma` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `precio` double DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `almacen` int(11) DEFAULT NULL,
  `observaciones` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_ocompra'
CREATE TABLE IF NOT EXISTS `app_ocompra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_usrcompra` int(11) DEFAULT NULL,
  `observaciones` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `id_requisicion` int(11) NOT NULL,
  `subtotal` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_ocompra_datos'
CREATE TABLE IF NOT EXISTS `app_ocompra_datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ocompra` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `ses_tmp` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `almacen` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `costo` double DEFAULT NULL,
  `impuestos` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `caracteristica` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_oventa'
CREATE TABLE IF NOT EXISTS `app_oventa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `id_usrcompra` int(11) DEFAULT NULL,
  `observaciones` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `id_requisicion` int(11) NOT NULL,
  `subtotal` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_oventa_datos'
CREATE TABLE IF NOT EXISTS `app_oventa_datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oventa` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `ses_tmp` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `almacen` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `costo` double DEFAULT NULL,
  `impuestos` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_lista` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `caracteristica` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pagos'
CREATE TABLE IF NOT EXISTS `app_pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cobrar_pagar` tinyint(1) NOT NULL,
  `id_prov_cli` int(11) NOT NULL,
  `cargo` double NOT NULL DEFAULT '0',
  `abono` double NOT NULL DEFAULT '0',
  `fecha_pago` datetime NOT NULL,
  `concepto` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_forma_pago` int(11) NOT NULL,
  `id_moneda` int(1) NOT NULL DEFAULT '1',
  `tipo_cambio` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pagos_relacion'
CREATE TABLE IF NOT EXISTS `app_pagos_relacion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pago` int(11) DEFAULT NULL,
  `id_tipo` int(11) DEFAULT NULL,
  `id_documento` int(11) DEFAULT NULL,
  `cargo` double NOT NULL DEFAULT '0',
  `abono` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pendienteFactura'
CREATE TABLE IF NOT EXISTS `app_pendienteFactura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sale` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `facturado` tinyint(1) DEFAULT NULL,
  `factNum` int(10) DEFAULT '0',
  `cadenaOriginal` text,
  `tipoComp` varchar(1) DEFAULT 'F',
  `id_respFact` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'app_periodos'
CREATE TABLE IF NOT EXISTS `app_periodos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ejercicio` int(2) NOT NULL,
  `num_mes` int(2) NOT NULL,
  `cerrado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pos_corte_caja'
CREATE TABLE IF NOT EXISTS `app_pos_corte_caja` (
  `idCortecaja` int(11) NOT NULL AUTO_INCREMENT,
  `fechainicio` datetime DEFAULT NULL,
  `fechafin` datetime DEFAULT NULL,
  `retirocaja` double DEFAULT NULL,
  `abonocaja` double DEFAULT NULL,
  `saldoinicialcaja` double DEFAULT NULL,
  `saldofinalcaja` double DEFAULT NULL,
  `montoventa` double DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCortecaja`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pos_inicio_caja'
CREATE TABLE IF NOT EXISTS `app_pos_inicio_caja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `idCortecaja` int(11) DEFAULT NULL,
  `idSucursal` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pos_retiro_caja'
CREATE TABLE IF NOT EXISTS `app_pos_retiro_caja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cantidad` double DEFAULT NULL,
  `concepto` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idempleado` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `idcorte` int(11) DEFAULT NULL,
  `idSucursal` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pos_venta'
CREATE TABLE IF NOT EXISTS `app_pos_venta` (
  `idVenta` int(11) NOT NULL AUTO_INCREMENT,
  `idCliente` int(11) DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  `rfc` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `documento` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `cambio` double DEFAULT NULL,
  `montoimpuestos` double DEFAULT NULL,
  `idSucursal` int(11) DEFAULT NULL,
  `observacion` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `envio` int(11) DEFAULT NULL,
  `impuestos` varchar(10000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  PRIMARY KEY (`idVenta`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pos_venta_pagos'
CREATE TABLE IF NOT EXISTS `app_pos_venta_pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idVenta` int(11) DEFAULT NULL,
  `idFormapago` int(11) DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `referencia` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pos_venta_producto'
CREATE TABLE IF NOT EXISTS `app_pos_venta_producto` (
  `idventa_producto` int(11) NOT NULL AUTO_INCREMENT,
  `idProducto` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `preciounitario` double DEFAULT NULL,
  `tipodescuento` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descuento` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `idVenta` int(11) DEFAULT NULL,
  `impuestosproductoventa` double DEFAULT NULL,
  `montodescuento` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `arr_kit` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comentario` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idventa_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pos_venta_producto_impuesto'
CREATE TABLE IF NOT EXISTS `app_pos_venta_producto_impuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idVentaproducto` int(11) DEFAULT NULL,
  `idImpuesto` int(11) DEFAULT NULL,
  `porcentaje` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `totalImpuesto` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_pos_venta_suspendida'
CREATE TABLE IF NOT EXISTS `app_pos_venta_suspendida` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_almacen` int(11) DEFAULT NULL,
  `s_cambio` double DEFAULT NULL,
  `s_cliente` int(11) DEFAULT NULL,
  `s_documento` int(11) DEFAULT NULL,
  `s_empleado` int(11) DEFAULT NULL,
  `s_funcion` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `s_idFact` int(11) DEFAULT NULL,
  `s_impuestos` double DEFAULT NULL,
  `s_monto` double DEFAULT NULL,
  `s_pagoautomatico` int(11) DEFAULT NULL,
  `s_sucursal` int(11) DEFAULT NULL,
  `s_impuestost` double DEFAULT NULL,
  `arreglo1` varchar(10000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `arreglo2` varchar(10000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identi` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `borrado` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_producto_caracteristicas'
CREATE TABLE IF NOT EXISTS `app_producto_caracteristicas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_caracteristica_padre` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_producto_impuesto'
CREATE TABLE IF NOT EXISTS `app_producto_impuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_impuesto` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `formula` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_producto_lotes'
CREATE TABLE IF NOT EXISTS `app_producto_lotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_lote` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_fabricacion` datetime DEFAULT NULL,
  `fecha_caducidad` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_producto_material'
CREATE TABLE IF NOT EXISTS `app_producto_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,4) DEFAULT NULL,
  `id_unidad` int(11) NOT NULL,
  `id_material` int(11) NOT NULL,
  `opcionales` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `app_producto_material_ibfk_1` (`id_producto`),
  KEY `app_producto_material_ibfk_2` (`id_unidad`),
  KEY `app_producto_material_ibfk_3` (`id_material`),
  CONSTRAINT `app_producto_material_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `app_productos` (`id`),
  CONSTRAINT `app_producto_material_ibfk_2` FOREIGN KEY (`id_unidad`) REFERENCES `app_unidades_medida` (`id`),
  CONSTRAINT `app_producto_material_ibfk_3` FOREIGN KEY (`id_material`) REFERENCES `app_productos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_producto_pedimentos'
CREATE TABLE IF NOT EXISTS `app_producto_pedimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_pedimento` int(11) DEFAULT NULL,
  `aduana` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_aduana` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_cambio` double DEFAULT NULL,
  `fecha_pedimento` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_producto_proveedor'
CREATE TABLE IF NOT EXISTS `app_producto_proveedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_unidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_producto_serie'
CREATE TABLE IF NOT EXISTS `app_producto_serie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_ocompra` int(11) DEFAULT NULL,
  `id_recepcion` int(11) DEFAULT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `serie` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `id_pedimento` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_producto_serie_rastro'
CREATE TABLE IF NOT EXISTS `app_producto_serie_rastro` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_serie` int(11) DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `fecha_reg` datetime DEFAULT NULL,
  `id_mov` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_productos'
CREATE TABLE IF NOT EXISTS `app_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `precio` double NOT NULL,
  `descripcion_corta` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `descripcion_larga` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ruta_imagen` varchar(500) COLLATE utf8_unicode_ci DEFAULT 'noimage.jpeg',
  `tipo_producto` int(11) DEFAULT NULL,
  `maximos` int(11) DEFAULT NULL,
  `minimos` int(11) DEFAULT NULL,
  `departamento` int(11) DEFAULT NULL,
  `familia` int(11) DEFAULT NULL,
  `linea` int(11) DEFAULT NULL,
  `id_tipo_costeo` int(11) DEFAULT NULL,
  `id_moneda` int(11) DEFAULT NULL,
  `id_clasificacion` int(11) DEFAULT NULL,
  `inventariable` int(11) DEFAULT NULL,
  `comision` float NOT NULL DEFAULT '0',
  `tipo_comision` int(11) DEFAULT '0',
  `id_unidad_venta` int(11) DEFAULT '0',
  `series` int(11) DEFAULT '0',
  `lotes` int(11) DEFAULT '0',
  `pedimentos` int(11) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `id_unidad_compra` int(11) DEFAULT '0',
  `costo_servicio` double DEFAULT NULL,
  `formulaIeps` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_recepcion'
CREATE TABLE IF NOT EXISTS `app_recepcion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oc` int(11) DEFAULT NULL,
  `id_encargado` int(11) DEFAULT NULL,
  `observaciones` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `fecha_recepcion` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `no_factura` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_factura` datetime DEFAULT NULL,
  `imp_factura` double DEFAULT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `xmlfile` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc_concepto` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `id_consignacion` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_recepcion_datos'
CREATE TABLE IF NOT EXISTS `app_recepcion_datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oc` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_recepcion` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `id_lote` int(11) DEFAULT '0',
  `id_pedimento` int(11) DEFAULT '0',
  `estatus` int(11) DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `caracteristica` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_requisiciones'
CREATE TABLE IF NOT EXISTS `app_requisiciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicito` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `id_tipogasto` int(11) DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `id_moneda` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `urgente` tinyint(1) DEFAULT NULL,
  `inventariable` int(11) DEFAULT NULL,
  `observaciones` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `tipo_cambio` double DEFAULT '0',
  `pr` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_requisiciones_datos'
CREATE TABLE IF NOT EXISTS `app_requisiciones_datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_requisicion` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `ses_tmp` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `almacen` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `caracteristica` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_requisiciones_datos_venta'
CREATE TABLE IF NOT EXISTS `app_requisiciones_datos_venta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_requisicion` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `ses_tmp` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `almacen` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `precio` double DEFAULT NULL,
  `impuestos` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_lista` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `caracteristica` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_requisiciones_venta'
CREATE TABLE IF NOT EXISTS `app_requisiciones_venta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `id_solicito` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `id_tipogasto` int(11) DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `id_moneda` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `urgente` tinyint(1) DEFAULT NULL,
  `inventariable` int(11) DEFAULT NULL,
  `observaciones` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `tipo_cambio` double DEFAULT '0',
  `pr` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_respuestaFacturacion'
CREATE TABLE IF NOT EXISTS `app_respuestaFacturacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idSale` int(11) NOT NULL DEFAULT '0',
  `idOs` int(11) DEFAULT '0',
  `idFact` int(11) NOT NULL DEFAULT '0',
  `folio` varchar(60) NOT NULL DEFAULT '',
  `factNum` int(10) NOT NULL,
  `serieCsdSat` varchar(40) NOT NULL DEFAULT '',
  `serieCsdEmisor` varchar(40) NOT NULL DEFAULT '',
  `selloDigitalSat` varchar(300) NOT NULL DEFAULT '',
  `selloDigitalEmisor` varchar(300) NOT NULL DEFAULT '',
  `fecha` datetime NOT NULL,
  `borrado` int(1) NOT NULL DEFAULT '0',
  `tipoComp` varchar(1) NOT NULL DEFAULT 'F',
  `idComprobante` varchar(45) NOT NULL DEFAULT '0',
  `cadenaOriginal` text,
  `trackid` double DEFAULT '0',
  `xmlfile` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'app_tipo_credito'
CREATE TABLE IF NOT EXISTS `app_tipo_credito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_tipo_producto'
CREATE TABLE IF NOT EXISTS `app_tipo_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_tpl_polizas'
CREATE TABLE IF NOT EXISTS `app_tpl_polizas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_documento` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `id_tipo_poliza` int(11) NOT NULL,
  `nombre_poliza` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `poliza_por_mov` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_tpl_polizas_mov'
CREATE TABLE IF NOT EXISTS `app_tpl_polizas_mov` (
  `id_tpl_poliza` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  `tipo_movto` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'app_unidades_medida'
CREATE TABLE IF NOT EXISTS `app_unidades_medida` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `factor` float NOT NULL,
  `unidad_base` int(4) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'bco_clasificador'
CREATE TABLE IF NOT EXISTS `bco_clasificador` (
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



INSERT IGNORE INTO `app_impuesto` (`id`, `nombre`, `valor`, `activo`, `clave`)
VALUES
  (1, 'IVA 16%', 16, 1, 'IVA'),
  (2, 'IVA 0%', 0, 1, 'IVA'),
  (3, 'IVA EXENTO', 0, 1, 'IVA'),
  (4, 'IVA RETENIDO 10.667%', 10.667, 1, 'IVAR'),
  (5, 'IVA RETENIDO 4%', 4, 1, 'IVAR'),
  (6, 'ISR RETENIDO 10%', 10, 1, 'ISR'),
  (7, 'ISH 2%', 2, 1, 'ISH'),
  (8, 'IEPS 8%', 8, 0, 'IEPS'),
  (9, 'IEPS 160%', 160, 1, 'IEPS'),
  (10, 'IEPS 30%', 30, 1, 'IEPS');

INSERT IGNORE INTO `app_costeo` (`id`, `nombre`)
VALUES
  (1, 'Costo Promedio'),
  (2, 'Costo Promedio por Almacen'),
  (3, 'Último Costo'),
  (4, 'UEPS'),
  (5, 'PEPS'),
  (6, 'Costo Especifico'),
  (7, 'Costo Estandar');

INSERT IGNORE INTO `app_almacen_tipo` (`id`, `nombre`)
VALUES
  (1, 'Almacén'),
  (2, 'Bodega'),
  (3, 'Pasillo'),
  (4, 'Rack'),
  (5, 'Mostrador');

INSERT IGNORE INTO `app_dfl_nombres` (`id`, `tabla`, `nombre`)
VALUES
  (1, 'app_departamento', 'Departamentos'),
  (2, 'app_familia', 'Familias'),
  (3, 'app_linea', 'Líneas');


INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1280, 78, 'PaisdeResidencia', 'Pais de Residencia', 'Pais de Residencia', 11, 'int', 'NA', '', 0, '-1', 32, 0),
  (1281, 78, 'nacionalidad', 'Nacionalidad', 'Nacionalidad', 100, 'varchar', 'Mexicana', '', 0, '-1', 32, 0),
  (1282, 78, 'ivaretenido', 'IVA Retenido %', 'IVA Retenido', 100, 'double', '0.000000000', '', 0, '', 33, 0),
  (1283, 78, 'isretenido', 'ISR Retenido %', 'ISR Retenido', 100, 'double', '0.000000000', '', 0, '', 34, 0),
  (1284, 78, 'idTasaPrvasumir', 'Asumir', 'asumir', 11, 'int', 'NA', '', -1, '', 35, 0),
  (1336, 78, 'idtipoiva', 'Tipo IVA ', 'Tipo IVA ', 11, 'int', 'NA', '', 0, '-1', 36, 0),
  (1352, 78, 'nombrextranjero', 'Nombre del Extranjero', 'Nombre Extranjero', 255, 'varchar', 'NA', '', 0, '', 31, 0),
  (1625, 78, 'idtipo', 'Tipo', 'Tipo', 11, 'int', 'NA', '', 0, '-1', 26, 0),
  (1400, 78, 'idIETU', 'Tipo IETU', 'Tipo IETU', 11, 'int', 'NA', '', 0, '-1', 25, 0),
  (382, 78, 'idPrv', 'ID', 'ID', 0, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (383, 78, 'razon_social', 'Razon Social y/o nombre', 'Razon Social', 255, 'varchar', 'NA', '', -1, '', 2, 0),
  (384, 78, 'rfc', 'RFC (Sin espacios)', 'RFC (Sin espacios)', 13, 'varchar', 'NA', '', -1, '', 1, 0),
  (385, 78, 'domicilio', 'Domicilio', 'Domicilio', 255, 'varchar', 'NA', '', 0, '', 3, 0),
  (386, 78, 'telefono', 'Teléfono', 'Telefono', 20, 'varchar', 'NA', '', 0, '', 6, 0),
  (387, 78, 'email', 'Correo Electronico', 'Correo Electronico', 255, 'varchar', 'NA', '', 0, '', 7, 0),
  (388, 78, 'web', 'Pagina Web', 'Pagina Web', 255, 'varchar', 'NA', '', 0, '', 8, 0),
  (389, 78, 'idestado', 'Estado', 'Estado', 10, 'varchar', 'NA', '', -1, '-1', 4, 0),
  (390, 78, 'idmunicipio', 'Municipio', 'Municipio', 10, 'varchar', 'NA', '', -1, '-1', 5, 0),
  (1689, 78, 'cuentacliente', 'Cuenta Cliente', 'Cuenta Cliente', 5, 'int', 'NA', '', 0, '-1', 29, 0),
  (1690, 78, 'beneficiario_pagador', 'Beneficiario/Pagador', 'Beneficiario/Pagador', 0, 'boolean', 'NA', '', 0, '', 28, 0),
  (1503, 78, 'diascredito', 'Dias de Credito', 'Dias de Credito', 11, 'int', '0', '', 0, '', 9, 0),
  (2287, 78, 'codigo', 'codigo', 'codigo', 45, 'varchar', 'NA', '', 0, '', 0, 0),
  (2288, 78, 'legal', 'legal', 'legal', 10, 'varchar', 'NA', '', 0, '', 10, 0),
  (2289, 78, 'precioycalidad', 'precioycalidad', 'precioycalidad', 1, 'int', 'NA', '', 0, '', 11, 0),
  (2290, 78, 'disponibilidad', 'disponibilidad', 'disponibilidad', 1, 'int', 'NA', '', 0, '', 12, 0),
  (2291, 78, 'nombre_comercial', 'nombre_comercial', 'nombre_comercial', 45, 'varchar', 'NA', '', 0, '', 2, 0),
  (2292, 78, 'moneda', 'moneda', 'moneda', 11, 'int', 'NA', '', 0, '', 14, 0),
  (2293, 78, 'clasificacion', 'clasificacion', 'clasificacion', 45, 'varchar', 'NA', '', 0, '', 15, 0),
  (2294, 78, 'limite_credito', 'limite_credito', 'limite_credito', 100, 'double', 'NA', '', 0, '', 16, 0),
  (2295, 78, 'calle', 'calle', 'calle', 45, 'varchar', 'NA', '', 0, '', 3, 0),
  (2296, 78, 'no_ext', 'no_ext', 'no_ext', 11, 'int', 'NA', '', 0, '', 3, 0),
  (2297, 78, 'no_int', 'no_int', 'no_int', 11, 'int', 'NA', '', 0, '', 3, 0),
  (2298, 78, 'cp', 'cp', 'cp', 11, 'int', 'NA', '', 0, '', 3, 0),
  (1275, 78, 'idtipotercero', 'Tipo Tercero', 'Tipo Tercero', 1, 'int', 'NA', '', -1, '-1', 29, 0),
  (1276, 78, 'idtipoperacion', 'Tipo Operacion', 'Tipo Operacion', 1, 'int', 'NA', '', -1, '-1', 29, 0),
  (1277, 78, 'curp', 'Curp', 'curp', 100, 'varchar', 'NA', '', 0, '', 1, 0),
  (1278, 78, 'cuenta', 'Cuenta', 'cuenta', 100, 'varchar', '000-000-000', '', 0, '-1', 27, 0),
  (1279, 78, 'numidfiscal', 'Numero ID Fiscal', 'Numero ID Fiscal', 100, 'varchar', 'NA', '', 0, '', 30, 0);

INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (2009, 'S', 'app_area_empleado', 'id', ' nombre ');

INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (1632, 301, 'idEmpleado', 'ID', 'ID', 0, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (1633, 301, 'codigo', 'Codigo', 'Codigo', 100, 'varchar', 'NA', '', -1, '', 1, 0),
  (1634, 301, 'fechaAlta', 'Fecha de Alta', 'Fecha de Alta', 0, 'date', 'NA', '', -1, '', 2, 0),
  (1635, 301, 'apellidoPaterno', 'Apellido Paterno', 'Apellido Paterno', 200, 'varchar', 'NA', '', 0, '', 3, 0),
  (1636, 301, 'apellidoMaterno', 'Apellido Materno', 'Apellido Materno', 200, 'varchar', 'NA', '', 0, '', 4, 0),
  (1637, 301, 'nombreEmpleado', 'Nombre', 'Nombre', 200, 'varchar', 'NA', '', -1, '', 5, 0),
  (1638, 301, 'salario', 'Salario Diario', 'Salario Diario', 0, 'double', 'NA', '', -1, '0.00', 6, 0),
  (1639, 301, 'idzona', 'Zona de Salario Minimo', 'Zona de Salario Minimo', 11, 'int', 'NA', '', -1, '', 7, 0),
  (1640, 301, 'idFormapago', 'Forma de Pago', 'Forma de Pago', 11, 'int', 'NA', '', -1, '', 8, 0),
  (1641, 301, 'email', 'Correo electronico', 'Correo electronico', 100, 'varchar', 'NA', '', 0, '', 9, 0),
  (1642, 301, 'nss', 'N.S.S.', 'N.S.S.', 100, 'varchar', 'NA', '', -1, '', 10, 0),
  (1643, 301, 'idEstadoCivil', 'Estado Civil', 'Estado Civil', 11, 'int', 'NA', '', -1, '', 11, 0),
  (1644, 301, 'idsexo', 'Sexo', 'Sexo', 11, 'int', 'NA', '', -1, '', 12, 0),
  (1645, 301, 'fechaNacimiento', 'Fecha de Nacimiento', 'Fecha de Nacimiento', 0, 'date', 'NA', '', -1, '', 13, 0),
  (1646, 301, 'idestado', 'Entidad Federativa', 'Entidad Federativa', 11, 'int', 'NA', '', -1, '', 14, 0),
  (1647, 301, 'idmunicipio', 'Ciudad de Nacimiento', 'Ciudad de Nacimiento', 11, 'int', 'NA', '', -1, '', 15, 0),
  (1648, 301, 'rfc', 'RFC', 'RFC', 30, 'varchar', 'XAXX010101000', '', -1, '', 16, 0),
  (1649, 301, 'curp', 'CURP', 'CURP', 50, 'varchar', 'NA', '', -1, '', 17, 0),
  (1650, 301, 'direccion', 'Direccion', 'Direccion', 100, 'varchar', 'NA', '', 0, '', 18, 0),
  (1651, 301, 'poblacion', 'Poblacion', 'Poblacion', 100, 'varchar', 'NA', '', 0, '', 19, 0),
  (1652, 301, 'idestadosat', 'Estado', 'Estado', 11, 'int', 'NA', '', 0, '', 20, 0),
  (1653, 301, 'cp', 'Codigo Postal', 'Codigo Postal', 11, 'int', 'NA', '', 0, '', 21, 0),
  (1654, 301, 'telefono', 'Telefono', 'Telefono', 15, 'varchar', 'NA', '', -1, '', 22, 0),
  (1655, 301, 'idbanco', 'Banco para pago Electronico', 'Banco para pago Electronico', 11, 'int', 'NA', '', -1, '', 23, 0),
  (1656, 301, 'numeroCuenta', 'Numero de Cuenta para Pago ', 'Numero de Cuenta para Pago ', 100, 'varchar', 'NA', '', -1, '', 24, 0),
  (1657, 301, 'claveinterbancaria', 'Clave Interbancaria', 'Clave Interbancaria', 100, 'varchar', 'NA', '', -1, '', 25, 0),
  (1991, 301, 'nomi_empleado_clasif', 'nomi_empleado_clasif', '', 0, 'int', 'NA', '', 0, '', 26, 0),
  (2004, 301, 'id_tipo_comision', 'id_tipo_comision', 'id_tipo_comision', 11, 'int', 'NA', '', 0, '', 50, 0),
  (2005, 301, 'comision', 'comision', 'comision', 0, 'double', 'NA', '', 0, '', 51, 0),
  (2006, 301, 'id_clasificacion', 'id_clasificacion', 'id_clasificacion', 11, 'int', 'NA', '', 0, '', 52, 0),
  (2009, 301, 'id_area_empleado', 'id_area_empleado', 'id_area_empleado', 11, 'int', 'NA', '', 0, '', 55, 0),
  (2010, 301, 'activo', 'activo', 'activo', 1, 'boolean', '1', '', 0, '', 55, 0);


INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1543, 'S', 'bco_tipoClasificador', 'idtipo', ' tipo ');

INSERT IGNORE INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
  (1676, 'S', 'bco_nivelClasificador', 'idNivel', ' nivel ');

INSERT IGNORE INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (364, 'app_area_empleado', 'app_area_empleado', '2016-04-14 17:13:33', '2016-04-14 17:16:01', 'A', 0, '', 0, '');


INSERT IGNORE INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
  (2007, 364, 'id', 'id', 'id', 11, 'auto_increment', 'NA', '', -1, '', 0, -1),
  (2008, 364, 'nombre', 'nombre', 'nombre', 35, 'varchar', 'NA', '', -1, '', 1, 0);


INSERT IGNORE INTO `app_almacenes` (`id`, `codigo_sistema`, `codigo_manual`, `nombre`, `id_padre`, `id_sucursal`, `id_estado`, `id_municipio`, `direccion`, `id_almacen_tipo`, `id_empleado_encargado`, `telefono`, `ext`, `es_consignacion`, `id_clasificador`, `activo`)
VALUES
  (1, '1', 'al-1', 'Almacen General', 0, 1, 14, 539, '', 1, 0, '', '12', 0, 0, 1);

  ALTER TABLE nomi_empleados
ADD COLUMN nomi_empleado_clasif int(11) DEFAULT NULL AFTER claveinterbancaria,
ADD COLUMN id_tipo_comision int(11) DEFAULT NULL AFTER nomi_empleado_clasif,
ADD COLUMN comision double DEFAULT NULL AFTER id_tipo_comision,
ADD COLUMN id_clasificacion int(11) DEFAULT NULL AFTER comision,
ADD COLUMN id_area_empleado int(11) DEFAULT NULL AFTER id_clasificacion,
ADD COLUMN activo tinyint(1) NOT NULL DEFAULT '-1' AFTER id_area_empleado;

ALTER TABLE mrp_sucursal
ADD COLUMN `clave` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL AFTER idAlmacen,
ADD COLUMN `activo` tinyint(1) NOT NULL DEFAULT '-1' AFTER clave;

ALTER TABLE mrp_proveedor
ADD COLUMN  `codigo` varchar(45) DEFAULT NULL AFTER idPrv,
ADD COLUMN  `beneficiario_pagador` tinyint(1) NOT NULL DEFAULT '0' AFTER idtipo,
ADD COLUMN  `cuentacliente` int(5) NOT NULL DEFAULT 0 AFTER beneficiario_pagador,
ADD COLUMN  `nombre` varchar(45) DEFAULT NULL AFTER cuentacliente,
ADD COLUMN  `nombre_comercial` varchar(45) DEFAULT NULL AFTER nombre,
ADD COLUMN  `moneda` int(11) DEFAULT NULL AFTER nombre_comercial,
ADD COLUMN  `clasificacion` varchar(45) DEFAULT NULL AFTER moneda,
ADD COLUMN  `limite_credito` double(100,9) DEFAULT NULL AFTER clasificacion,
ADD COLUMN  `status` int(11) DEFAULT NULL AFTER limite_credito,
ADD COLUMN  `calle` varchar(45) DEFAULT NULL AFTER status,
ADD COLUMN  `no_ext` int(11) DEFAULT NULL AFTER calle,
ADD COLUMN  `no_int` int(11) DEFAULT NULL AFTER no_ext,
ADD COLUMN  `id_colonia` int(11) DEFAULT NULL AFTER no_int,
ADD COLUMN  `cp` int(11) DEFAULT NULL AFTER id_colonia;

ALTER TABLE comun_cliente
ADD COLUMN  `curp` varchar(30) DEFAULT NULL AFTER rfc,
ADD COLUMN  `id_grupo` int(11) DEFAULT '0' AFTER dias_credito,
ADD COLUMN  `num_ext` int(11) DEFAULT NULL AFTER id_grupo,
ADD COLUMN  `num_int` int(11) DEFAULT NULL AFTER num_ext,
ADD COLUMN  `smsb` int(11) DEFAULT '0' AFTER num_int,
ADD COLUMN  `codigo` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER cuenta,
ADD COLUMN  `id_moneda` int(11) NOT NULL DEFAULT '1' AFTER codigo,
ADD COLUMN  `id_clasificacion` int(11) DEFAULT NULL AFTER id_moneda,
ADD COLUMN  `permitir_vtas_credito` tinyint(1) DEFAULT NULL AFTER id_clasificacion,
ADD COLUMN  `id_tipo_credito` int(11) DEFAULT NULL AFTER permitir_vtas_credito,
ADD COLUMN  `permitir_exceder_limite` tinyint(1) DEFAULT NULL AFTER id_tipo_credito,
ADD COLUMN  `dcto_pronto_pago` double DEFAULT NULL AFTER permitir_exceder_limite,
ADD COLUMN  `intereses_moratorios` double DEFAULT NULL AFTER dcto_pronto_pago,
ADD COLUMN  `domicilio_entrega` varchar(45) DEFAULT NULL AFTER intereses_moratorios,
ADD COLUMN  `id_lista_precios` int(11) DEFAULT NULL AFTER domicilio_entrega,
ADD COLUMN  `envios` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER id_lista_precios,
ADD COLUMN  `comision_vta` double DEFAULT NULL AFTER envios,
ADD COLUMN  `comision_cobranza` double DEFAULT NULL AFTER comision_vta,
ADD COLUMN  `saldo` double DEFAULT NULL AFTER comision_cobranza,
ADD COLUMN  `idBanco` int(11) DEFAULT NULL AFTER saldo,
ADD COLUMN  `numero_cuenta_banco` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER idBanco;

INSERT IGNORE INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
  (285, 'bco_clasificador', 'Clasificador Ingresos-Egresos', '2015-06-12 16:13:42', '2015-12-22 22:20:10', 'A', 0, '../../modulos/bancos/js/despuesclasificador.php', 0, '../../modulos/bancos/js/antesclasificador.php');

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

CREATE TABLE IF NOT EXISTS `bco_nivelClasificador` (
  `idNivel` int(11) NOT NULL AUTO_INCREMENT,
  `nivel` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`idNivel`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT IGNORE INTO `bco_nivelClasificador` (`idNivel`, `nivel`)
VALUES
  (1, 'Subcategoria'),
  (2, 'Categoria');

CREATE TABLE IF NOT EXISTS `bco_tipoClasificador` (
  `idtipo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`idtipo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT IGNORE INTO `bco_tipoClasificador` (`idtipo`, `tipo`)
VALUES
  (1, 'Ingresos'),
  (2, 'Egresos');


CREATE TABLE IF NOT EXISTS `forma_pago_caja` (
  `idFormapago` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) CHARACTER SET latin1 NOT NULL,
  `claveSat` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`idFormapago`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `forma_pago_caja` (`idFormapago`, `nombre`, `claveSat`)
VALUES
  (1, 'Efectivo', '01'),
  (2, 'Cheque Nominativo', '02'),
  (3, 'Transferencia electronica de fondos', '03'),
  (4, 'Tarjeta de Credito', '04'),
  (5, 'Monedero Electronico', '05'),
  (6, 'Dinero Electronico', '06'),
  (7, 'Vales de despensa', '08'),
  (8, 'Tarjeta de Debito', '28'),
  (9, 'Tarjeta de Servicio', '29'),
  (10, 'Otros', '99');

CREATE TABLE IF NOT EXISTS `app_devolucioncli` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ov` int(11) DEFAULT NULL,
  `id_envio` int(11) DEFAULT NULL,
  `id_encargado` int(11) DEFAULT NULL,
  `observaciones` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `xmlfile` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc_concepto` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `id_consignacion` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_devolucioncli_datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ov` int(11) DEFAULT NULL,
  `id_envio` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_devolucion` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `id_lote` int(11) DEFAULT '0',
  `id_pedimento` int(11) DEFAULT '0',
  `estatus` int(11) DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `caracteristica` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_devolucionpro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oc` int(11) DEFAULT NULL,
  `id_rec` int(11) DEFAULT NULL,
  `id_encargado` int(11) DEFAULT NULL,
  `observaciones` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `no_factura` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_factura` datetime DEFAULT NULL,
  `imp_factura` double DEFAULT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `xmlfile` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc_concepto` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `id_consignacion` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_devolucionpro_datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oc` int(11) DEFAULT NULL,
  `id_rec` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_devolucion` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `id_lote` int(11) DEFAULT '0',
  `id_pedimento` int(11) DEFAULT '0',
  `estatus` int(11) DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `caracteristica` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Crea la tabla de los campos extra para foodware*/
CREATE TABLE IF NOT EXISTS app_campos_foodware (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_producto int(11) NOT NULL,
  vendible int(11) DEFAULT 1,
  h_ini varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  h_fin varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  dias varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  ganancia decimal(10, 4) DEFAULT NULL,
  rate double DEFAULT NULL,
  descripcion VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (id),
  KEY id_producto (id_producto) USING BTREE,
  CONSTRAINT app_campos_foodware_ibfk_1 FOREIGN KEY (id_producto) REFERENCES app_productos (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*crea la nueva tabla de materiales*/
CREATE TABLE IF NOT EXISTS app_producto_material(
  id int(11) NOT NULL AUTO_INCREMENT,
  id_producto int(11) NOT NULL,
  cantidad decimal(10, 4) DEFAULT NULL,
  id_unidad int(11) NOT NULL,
  id_material int(11) NOT NULL,
  opcionales varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  status int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT app_producto_material_ibfk_1 FOREIGN KEY (id_producto) REFERENCES app_productos (id),
  CONSTRAINT app_producto_material_ibfk_2 FOREIGN KEY (id_unidad) REFERENCES app_unidades_medida (id),
  CONSTRAINT app_producto_material_ibfk_3 FOREIGN KEY (id_material) REFERENCES app_productos (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE app_requisiciones ADD subtotal DOUBLE;
ALTER TABLE app_requisiciones ADD total DOUBLE;

ALTER TABLE app_requisiciones_venta ADD subtotal DOUBLE;
ALTER TABLE app_requisiciones_venta ADD total DOUBLE;

