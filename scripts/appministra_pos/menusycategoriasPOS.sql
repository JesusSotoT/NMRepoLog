INSERT IGNORE INTO accelog_categorias (idcategoria, nombre, icono, orden)
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

/*Punto de Venta*/
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
	(2051, 'Caja', 0, '../../modulos/pos/index.php?c=caja&f=indexCaja', 1042, 0, 1, 0),
	(2094, 'Retiro de Caja', 0, '../../modulos/pos/index.php?c=retiro&f=imprimeretiro', 1042, 0, 2, 0),
	(2115, 'Cortes de Caja', 0, '../../modulos/pos/index.php?c=caja&f=cortesGrid', 1042, 0, 3, 0);
/*Facturacion */
INSERT INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
	(2180, 'Tickets no Facturados', 0, 'modulos/pos/index.php?c=caja&f=pendienteFacturar', 1043, 0, 3, 0),
	(2181, 'Lista de Facturas', 0, '../../modulos/pos/index.php?c=caja&f=gridFacturas', 1043, 0, 4, 0);

/*Compras*/
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
	(2032, 'Ordenes de Compra', 0, '../../modulos/pos/index.php?c=compra&f=indexgrid', 1044, 0, 1, 0),
	(2033, 'Recepcion Orden Compra', 0, '../../modulos/pos/index.php?c=compra&f=recepcionGrid', 1044, 0, 2, 0);
/*Ventas*/
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
	(2106, 'Ventas', 0, '../../modulos/pos/index.php?c=caja&f=ventasGrid', 1045, 0, 20, 0);

/*Inventario*/
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
	(2010, 'Movimientos de Inventario', 0, '../../modulos/appministra/index.php?c=inventarios&f=entradas', 1046, 0, 1, 0);
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
	(2039, 'Mermas', 0, '../../modulos/pos/index.php?c=inventario&f=indexGridMermas', 1046, 0, 2, 0);
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
	(2119, 'Etiquetas', 0, '../../modulos/pos/index.php?c=inventario&f=indexEtiquetado', 1046, 0, 3, 0);



/* Catalogos */
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
	(1993, 'Impuestos', 0, '../../modulos/appministra/index.php?c=configuracion&f=impuestos', 1048, 0, 6, 0),
	(2034, 'Productos', 0, '../../modulos/pos/index.php?c=producto&f=indexGridProductos', 1048, 0, 3, 0),
	(2049, 'Clientes', 0, '../../modulos/pos/index.php?c=cliente&f=indexGrid', 1048, 0, 4, 0),
	(2142, 'Importar clientes', 0, '../../modulos/punto_venta/views/clientes/importar_clientes.php', 1048, 0, 1, 0),
	(2143, 'Importar proveedores', 0, '../../modulos/punto_venta/views/proveedores/importar_proveedores.php', 1048, 0, 2, 0),
	(2144, 'Beneficiarios/Proveedores ', 0, '../../modulos/punto_venta/catalogos/proveedor.php', 1048, 0, 5, 0);
/*Reportes*/
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
	(2102, 'Inventarios', 0, NULL, 1049, 0, 1, 0),
	(2108, 'Inventario Actual', 2102, '../../modulos/pos/index.php?c=inventario&f=inventarioActual', 1049, 0, 1, 0),
	(2036, 'Kardex', 2102, '../../modulos/pos/index.php?c=inventario&f=indexGrid', 1049, 0, 4, 0);
/*Configuracion*/
INSERT IGNORE INTO accelog_menu (idmenu, nombre, idmenupadre, url, idcategoria, icono, orden, omision)
VALUES
	(1960, 'Avanzada', 0, '../../modulos/appministra/index.php?c=configuracion&f=general&p=0', 1050, 0, 1, 0),
	(1961, 'Clasificadores', 0, '../../modulos/appministra/index.php?c=configuracion&f=clasificadores', 1050, 0, 4, 0),
	(1985, 'Clasificadores Productos', 0, '../../modulos/appministra/index.php?c=configuracion&f=clasificadoresProd', 1050, 0, 6, 0),
	(1986, 'Caracteristicas Productos', 0, '../../modulos/appministra/index.php?c=configuracion&f=caracteristicasProd', 1050, 0, 7, 0),
	(1990, 'Unidades de Medida', 0, '../../modulos/appministra/index.php?c=configuracion&f=medida', 1050, 0, 9, 0),
	(2135, 'Almacenes', 0, '', 1050, 0, 15, 0),
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
	(2152, 'Sucursal', 2135, '../catalog/gestor.php?idestructura=86&ticket=testing', 1050, 0, 1, 0);

	DELETE FROM
	 	accelog_perfiles_me
	 WHERE
	 	idperfil>1;
		
INSERT IGNORE INTO accelog_perfiles_me (idperfil,idmenu) values (2,2051),
(2,2094),
(2,2115),
(2,2181),
(2,2181),
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
(2,2119),
(2,2039),
(2,2174),
(2,2175);
