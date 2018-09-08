# ************************************************************
# Sequel Pro SQL dump
# Versión 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com (MySQL 5.6.23-log)
# Base de datos: _clonbase
# Tiempo de Generación: 2017-04-04 16:34:04 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Volcado de tabla accelog_categorias
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accelog_categorias`;

CREATE TABLE `accelog_categorias` (
  `idcategoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `icono` tinyint(1) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=1061 DEFAULT CHARSET=utf8;

LOCK TABLES `accelog_categorias` WRITE;
/*!40000 ALTER TABLE `accelog_categorias` DISABLE KEYS */;

INSERT INTO `accelog_categorias` (`idcategoria`, `nombre`, `icono`, `orden`)
VALUES
	(1,'Configuración',0,16),
	(2,'Catalogos Generales',0,10003),
	(3,'Inventarios',0,4),
	(4,'Ventas',0,10003),
	(5,'Reportes',0,10003),
	(6,'Administración de Ventas',0,10003),
	(7,'Gestión de Ventas',0,10003),
	(10,'Operaciones',0,10003),
	(11,'Compras',0,10003),
	(12,'Administración viejo',0,10003),
	(13,'Administración',0,9),
	(14,'Reportes (PDV)',0,10003),
	(15,'Agenda',0,7),
	(16,'MRP',0,10003),
	(18,'Acontia',0,11),
	(19,'Facturación',0,8),
	(22,'Producción',0,10),
	(1018,'SMS',0,13),
	(1023,'Catalogos',0,3),
	(1024,'Punto de Venta',0,5),
	(1025,'Reportes Graficos Ventas',0,10003),
	(1026,'Foodware',0,6),
	(1027,'Puerto Serial',0,3),
	(1028,'Hazbizne',0,12),
	(1032,'Xtructur',0,3),
	(1033,'Bancos',0,11),
	(1042,'Punto de Venta',0,4),
	(1043,'Facturacion',0,5),
	(1044,'Compras',0,6),
	(1045,'Ventas',0,7),
	(1046,'Inventario',0,8),
	(1047,'Administracion',0,9),
	(1048,'Catalogos',0,10),
	(1049,'Reportes',0,11),
	(1050,'Configuracion',0,12),
	(1051,'Servicios',0,1),
	(1052,'Reservaciones',0,2),
	(1053,'Recetas',0,3),
	(1054,'Conciliacion Bancaria',0,1),
	(1055,'Nominas',0,2),
	(1056,'Inovekia',0,3),
	(1057,'Reportes',0,4),
	(1058,'ConfiguraciÃ³n de Empresa',0,1),
	(1059,'Carga/ConfiguraciÃ³n de Obra',0,2),
	(1060,'AdministraciÃ³n de Obra',0,3);

/*!40000 ALTER TABLE `accelog_categorias` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla accelog_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accelog_menu`;

CREATE TABLE `accelog_menu` (
  `idmenu` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `idmenupadre` int(11) DEFAULT NULL,
  `url` varchar(300) DEFAULT NULL,
  `idcategoria` int(11) NOT NULL,
  `icono` tinyint(1) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `omision` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idmenu`,`idcategoria`),
  KEY `menu_categorias` (`idcategoria`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2300 DEFAULT CHARSET=utf8;

LOCK TABLES `accelog_menu` WRITE;
/*!40000 ALTER TABLE `accelog_menu` DISABLE KEYS */;

INSERT INTO `accelog_menu` (`idmenu`, `nombre`, `idmenupadre`, `url`, `idcategoria`, `icono`, `orden`, `omision`)
VALUES
	(1,'CataLog',0,'../catalog/admin/index.php',1,0,20,0),
	(2,'Mi OrganizaciÃ³n',0,'../catalog/gestor.php?idestructura=1&ticket=testing',1,0,1,0),
	(3,'Catálogo de Empleados',0,'../catalog/gestor.php?idestructura=2&ticket=testing',1,0,20,0),
	(4,'Categorías',0,'../catalog/gestor.php?idestructura=3&ticket=testing',1,0,20,0),
	(5,'Menús',0,'../catalog/gestor.php?idestructura=4&ticket=testing',1,0,20,0),
	(6,'Opciones',0,'../catalog/gestor.php?idestructura=7&ticket=testing',1,0,20,0),
	(7,'Perfiles',0,'../catalog/gestor.php?idestructura=5&ticket=testing',1,0,20,0),
	(8,'Perfiles - Menús',0,'../catalog/gestor.php?idestructura=6&ticket=testing',1,0,20,0),
	(9,'Perfiles - Opciones',0,'../catalog/gestor.php?idestructura=8&ticket=testing',1,0,20,0),
	(11,'Lista de perfiles por usuario',0,'../catalog/gestor.php?idestructura=9&ticket=testing',1,0,20,0),
	(12,'RepoLog',0,'../repolog/admin/index.php',1,0,20,0),
	(13,'DocLog',0,'../doclog/admin/index.php',1,0,20,0),
	(14,'Usuarios Niveles',0,'../catalog/gestor.php?idestructura=11&ticket=testing',1,0,20,0),
	(30,'Ventas',0,'../doclog/abrir.php?iddocumento=1&ticket=testing',7,0,1,0),
	(34,'Movimientos',0,'../catalog/gestor.php?idestructura=38&ticket=testing',1,0,20,0),
	(40,'Administracion de Usuarios',0,'../catalog/gestor.php?idestructura=47&ticket=testing',1,0,3,0),
	(85,'Tarjetas de Regalo',162,'../../modulos/posclasico/index.php/giftcards',2,0,6,0),
	(113,'Expediente',0,'../../modulos/agenda/expediente.php',15,0,2,0),
	(115,'Departamento',1276,'../../modulos/punto_venta/catalogos/departamento.php',1023,0,2,0),
	(116,'Familia',1276,'../../modulos/punto_venta/catalogos/familia.php',1023,0,4,0),
	(118,'Línea',1276,'../../modulos/punto_venta/catalogos/linea.php',1023,0,6,0),
	(119,'Producto e Insumos',0,'../../modulos/punto_venta/catalogos/producto.php',1023,0,1,0),
	(120,'Producto proveedor',1276,'../catalog/gestor.php?idestructura=80&ticket=testing',1023,0,16,0),
	(121,'Color',1276,'../../modulos/punto_venta/catalogos/color.php',1023,0,8,0),
	(122,'Talla',1276,'../../modulos/punto_venta/catalogos/talla.php',1023,0,10,0),
	(123,'Beneficiarios/Proveedores ',0,'../../modulos/punto_venta/catalogos/proveedor.php',1023,0,2,0),
	(124,'Alta orden de compra',1903,'../../modulos/mrp/index.php/buy_order',3,0,1,0),
	(127,'Unidades de medida',0,'../catalog/gestor.php?idestructura=85&ticket=testing',1023,0,0,0),
	(128,'Recepcion orden de compra',1903,'../../modulos/mrp/index.php/buy',3,0,2,0),
	(129,'Sucursal',1277,'../../modulos/punto_venta/catalogos/sucursal.php',1023,0,4,0),
	(131,'Existencias',1275,'../../modulos/mrp/index.php/inventary/stock',3,0,3,0),
	(132,'Entradas proveedores',1275,'../../modulos/mrp/index.php/inventary',3,0,2,0),
	(133,'Orden de produccion',0,'../../modulos/mrp/index.php/production_order',22,0,0,0),
	(139,'Configuración',0,'',18,0,0,0),
	(140,'Tipos de Pólizas',0,'../catalog/gestor.php?idestructura=105&ticket=testing',18,0,1000,0),
	(141,'Nuevo Ejercicio',0,'../catalog/gestor.php?idestructura=108&ticket=testing',18,0,1000,0),
	(142,'Ejercicios',139,'../../modulos/cont/index.php?c=Config&f=mainPage',18,0,0,0),
	(143,'Captura',1664,'../../modulos/cont/index.php?c=CaptPolizas&f=Ver',18,0,0,0),
	(145,'Árbol de Cuentas',139,'../../modulos/cont/index.php?c=arbol&f=index',18,0,4,0),
	(146,'Listado Facturas',0,'../repolog/repolog.php?i=10',19,0,20,0),
	(151,'Historico existencias',1275,'../../modulos/mrp/index.php/inventary/historic',3,0,4,0),
	(1072,'Cuentas Por Cobrar (REST)',0,'../doclog/abrir.php?iddocumento=32&ticket=testing',12,0,5,0),
	(1073,'Cuentas Por Pagar (REST).',0,'../doclog/abrir.php?iddocumento=33&ticket=testing',12,0,8,0),
	(1074,'Reporte Cuentas Por Cobrar(REST)',0,'../repolog/repolog.php?i=33',12,0,6,0),
	(1075,'Reporte Cuentas Por Pagar(REST)',0,'../repolog/repolog.php?i=17',12,0,9,0),
	(1079,'Clientes (REST)',163,'../../modulos/poscomandas/index.php/customers',2,0,1,0),
	(1080,'Productos (REST)',163,'../../modulos/poscomandas/index.php/items',2,0,2,0),
	(1081,'Platillos (REST)',163,'../../modulos/poscomandas/index.php/item_kits',2,0,3,0),
	(1082,'Proveedores (REST)',163,'../../modulos/poscomandas/index.php/suppliers',2,0,4,0),
	(1083,'Recepción Productos(REST)',0,'../../modulos/poscomandas/index.php/receivings',3,0,5,0),
	(1085,'Tarjetas de Regalo(REST)',163,'../../modulos/poscomandas/index.php/giftcards',2,0,6,0),
	(1086,'Configuración (REST)',0,'../../modulos/poscomandas/index.php/config',1,0,20,0),
	(1087,'Resumen de Ventas (Grafico)',1095,'../../modulos/poscomandas/index.php/reports/graphical_summary_sales',1014,0,1,0),
	(1088,'Ventas por Categoria (Grafico)',1095,'../../modulos/poscomandas/index.php/reports/graphical_summary_categories',1014,0,2,0),
	(1089,'Ventas por Cliente (Grafico)',1095,'../../modulos/poscomandas/index.php/reports/graphical_summary_customers',1014,0,3,0),
	(1090,'Resumen Proveedores (Grafico)',1095,'../../modulos/poscomandas/index.php/reports/graphical_summary_suppliers',1014,0,4,0),
	(1091,'Ventas por Producto (Grafico)',1095,'../../modulos/poscomandas/index.php/reports/graphical_summary_items',1014,0,5,0),
	(1092,'Ventas por Empleado (Grafico)',1095,'../../modulos/poscomandas/index.php/reports/graphical_summary_employees',1014,0,6,0),
	(1093,'Resumen Impuestos (Grafico)',1095,'../../modulos/poscomandas/index.php/reports/graphical_summary_taxes',1014,0,7,0),
	(1094,'Distribución Formas Pagos (Grafico)',1095,'../../modulos/poscomandas/index.php/reports/graphical_summary_payments',1014,0,8,0),
	(1095,'Reportes Graficos',0,'',1014,0,1,0),
	(1096,'Reportes Resumen',0,'',1014,0,9,0),
	(1097,'Resumen de Ventas',1096,'../../modulos/poscomandas/index.php/reports/summary_sales',1014,0,11,0),
	(1098,'Resumen Ventas/Categoria',1096,'../../modulos/poscomandas/index.php/reports/summary_categories',1014,0,12,0),
	(1099,'Resumen Ventas/Clientes',1096,'../../modulos/poscomandas/index.php/reports/summary_customers',1014,0,13,0),
	(1100,'Resumen Proveedores',1096,'../../modulos/poscomandas/index.php/reports/summary_suppliers',1014,0,14,0),
	(1101,'Resumen Ventas/Productos',1096,'../../modulos/poscomandas/index.php/reports/summary_items',1014,0,15,0),
	(1102,'Resumen Ventas/Cajeros',1096,'../../modulos/poscomandas/index.php/reports/summary_employees',1014,0,16,0),
	(1103,'Resumen de Pagos',1096,'../../modulos/poscomandas/index.php/reports/summary_payments',1014,0,17,0),
	(1104,'Reportes Detallados',0,'',1014,0,18,0),
	(1105,'Detallado de Ventas',1104,'../../modulos/poscomandas/index.php/reports/detailed_sales',1014,0,19,0),
	(1106,'Detallado de Entradas (REST)',0,'../../modulos/poscomandas/index.php/reports/detailed_receivings',3,0,1,0),
	(1107,'Detallado Clientes',1104,'../../modulos/poscomandas/index.php/reports/specific_customer',1014,0,20,0),
	(1108,'Detallado Cajero',1104,'../../modulos/poscomandas/index.php/reports/specific_employee',1014,0,21,0),
	(1109,'Punto de Re-Orden (REST)',0,'../../modulos/poscomandas/index.php/reports/inventory_low',3,0,3,0),
	(1110,'Inventario Actual(REST)',0,'../../modulos/poscomandas/index.php/reports/inventory_summary',3,0,3,0),
	(1114,'Cortes de Caja',0,'../../modulos/poscomandas/index.php/reports/reportcash/',1014,0,30,0),
	(1117,'Listado Facturas (REST)',0,'../repolog/repolog.php?i=16',1017,0,20,0),
	(1118,'Estado de Resultados',1639,'../../modulos/cont/index.php?c=reports&f=balanceGeneral&tipo=0',18,0,1,0),
	(1119,'Impresión',1664,'../../modulos/cont/index.php?c=polizasImpresion&f=Inicial&tipo=1',18,0,1,0),
	(1120,'Reportes Financieros',0,'',18,0,6,0),
	(1121,'Balance de Sumas y Saldos',0,'../repolog/repolog.php?i=12',18,0,1000,0),
	(1122,'Libro de Mayor',1639,'../../modulos/cont/index.php?c=Reports&f=libro_mayor',18,0,3,0),
	(1124,'Inactivas',1664,'../../modulos/cont/index.php?c=CaptPolizas&f=ListaPolizasEliminadas',18,0,2,0),
	(1126,'ParcialLog',0,'../doclog/abrir.php?iddocumento=12',1,0,20,0),
	(1133,'Ofertas',0,'../../modulos/sms/views/offer/listado_ofertas.php',1018,0,20,0),
	(1136,'TRT Destinatarios',0,'../catalog/gestor.php?idestructura=130&ticket=testing',1019,0,20,0),
	(1137,'Clientes',0,'../../modulos/punto_venta/catalogos/cliente.php',1023,0,3,0),
	(1138,'Giro',1161,'../catalog/gestor.php?idestructura=132&ticket=testing',2,0,20,0),
	(1139,'Promotor',1161,'../catalog/gestor.php?idestructura=133&ticket=testing',2,0,20,0),
	(1140,'Rubro',1161,'../catalog/gestor.php?idestructura=131&ticket=testing',2,0,20,0),
	(1141,'Ruta(SMS)',1161,'../catalog/gestor.php?idestructura=134&ticket=testing',2,0,20,0),
	(1147,'Rutas (Entrega de Ofertas)',0,'',1018,0,22,0),
	(1149,'Configuración rutas',1147,'../../modulos/sms/views/configrutas/listado_ofertas.php',1018,0,2,0),
	(1162,'Catálogo de Cuentas',1640,'../../modulos/cont/index.php?c=reports&f=catalogoCuentas',18,0,0,0),
	(1182,'Balance General',1639,'../../modulos/cont/index.php?c=reports&f=balanceGeneral&tipo=1',18,0,0,0),
	(1189,'Liquidación de rutas',1147,'../../modulos/sms/views/configrutas/liquidacion_rutas.php',1018,0,0,0),
	(1210,'Por ruta',1211,'../repolog/repolog.php?i=38',1018,0,20,0),
	(1211,'Reportes ventas',0,'',1018,0,0,0),
	(1212,'Por oferta',1211,'../repolog/repolog.php?i=39',1018,0,20,0),
	(1213,'Por cliente',1211,'../repolog/repolog.php?i=40',1018,0,20,0),
	(1214,'Por transporte',1211,'../repolog/repolog.php?i=41',1018,0,20,0),
	(1217,'Trazar Ruta',1147,'../../modulos/sms/rutamap/inicio.php',1018,0,3,0),
	(1218,'Reporte Ventas No Facturadas',0,'../repolog/repolog.php?i=43',19,0,20,0),
	(1234,'Almacen',1277,'../../modulos/punto_venta/catalogos/almacen.php',1023,0,2,0),
	(1235,'Tarjeta de regalo',0,'../catalog/gestor.php?idestructura=196&ticket=testing',1024,0,20,0),
	(1238,'Caja',0,'../../modulos/punto_venta_nuevo/index.php?c=caja&f=imprimecaja',1024,0,0,0),
	(1239,'Cuenta por pagar',0,'../../modulos/punto_venta/views/cxp/listado_cxp.php',13,0,20,0),
	(1246,'Sucursal-Almacen',1277,'../../modulos/punto_venta/listadosucuralma.php',1023,0,6,0),
	(1259,'Cuenta por cobrar',0,'../../modulos/punto_venta/views/cxc/listado_cxc.php',13,0,20,0),
	(1260,'Salidas almacen',1904,'../../modulos/punto_venta/movimientos/listadomovimientos.php',3,0,20,0),
	(1261,'Corte de caja',0,'../../modulos/punto_venta/boxCut/views/index.php',1024,0,20,0),
	(1263,'Configuracion Serie y Folio',0,'../catalog/gestor.php?idestructura=221&ticket=testing',19,0,20,0),
	(1265,'Reportes',0,'',13,0,20,0),
	(1266,'Reporte Cuentas por Cobrar',1265,'../../modulos/punto_venta/reportes/rcxc.php',13,0,2,0),
	(1268,'Reporte Cuentas Por Pagar',1265,'../../modulos/punto_venta/reportes/rcxp.php',13,0,4,0),
	(1269,'Reporte de ventas',1265,'../../modulos/punto_venta/views/reportes/reporte_ventas.php',13,0,20,0),
	(1271,'Movimientos productos',1275,'../../modulos/punto_venta/entradasalidas.php',3,0,5,0),
	(1272,'Pólizas sin Autorizar (PDV)',0,'../../modulos/cont/index.php?c=CaptPolizas&f=ListaPolizasPDV',18,0,1000,0),
	(1273,'Ventas',0,'../../modulos/punto_venta/ventas.php',13,0,0,0),
	(1274,'Reporte de ventas canceladas',1265,'../../modulos/punto_venta/views/reportes/reporte_ventas_canceladas.php',13,0,20,0),
	(1275,'Reportes',0,'',3,0,20,0),
	(1276,'Clasificación',0,'',1023,0,7,0),
	(1277,'Inventario',0,'',1023,0,5,0),
	(1278,'Impuestos',0,'../catalog/gestor.php?idestructura=224&ticket=testing',1023,0,20,0),
	(1280,'Recepcion de mercancia',1903,'../../modulos/punto_venta/ingreso_mercancia.php',3,0,3,0),
	(1281,'Importar Productos',0,'../../modulos/punto_venta/views/productos/importar_productos.php',1023,0,0,0),
	(1282,'Importar Clientes',0,'../../modulos/punto_venta/views/clientes/importar_clientes.php',1023,0,0,0),
	(1283,'Importar Proveedores',0,'../../modulos/punto_venta/views/proveedores/importar_proveedores.php',1023,0,0,0),
	(1284,'Datos facturacion',0,'../catalog/gestor.php?idestructura=249&ticket=testing',1023,0,4,0),
	(1567,'Inventario Actual',1275,'../../modulos/punto_venta/reportes/stock.php',3,0,0,0),
	(1568,'Punto de Reorden (Minimo)',1275,'../../modulos/punto_venta/reportes/reorden.php',3,0,1,0),
	(1569,'Reportes - Resumen de Ventas',0,'../../modulos/punto_venta/graphicReports/views/index.php',1025,0,0,0),
	(1570,'Reportes - Ventas por Familia',0,'../../modulos/punto_venta/graphicReports/views/family.php',1025,0,1,0),
	(1571,'Reportes - Ventas por Cliente',0,'../../modulos/punto_venta/graphicReports/views/client.php',1025,0,2,0),
	(1572,'Reportes - Ventas por Producto',0,'../../modulos/punto_venta/graphicReports/views/product.php',1025,0,3,0),
	(1573,'Reportes - Ventas por Empleado',0,'../../modulos/punto_venta/graphicReports/views/employee.php',1025,0,4,0),
	(1574,'Reportes - Ventas por Tipo de Pago',0,'../../modulos/punto_venta/graphicReports/views/payment.php',1025,0,5,0),
	(1575,'Listado devoluciones',1579,'../repolog/repolog.php?i=55',3,0,20,0),
	(1576,'Ventas (Linea, Familia, Departamento)',1265,'../../modulos/punto_venta/reportes/rventasview.php',13,0,20,0),
	(1577,'Reporte de ventas(Proveedor)',1265,'../../modulos/punto_venta/reportes/rventasproveedor.php',13,0,20,0),
	(1578,'Reporte de ventas(Pagos)',1265,'../../modulos/punto_venta/reportes/rventapagos.php',13,0,20,0),
	(1579,'Devoluciones a Proveedores',0,'',3,0,1,0),
	(1580,'Realizar devoluciones',1579,'../../modulos/devoluciones/devoluciones.php',3,0,0,0),
	(1581,'cosas',0,'../catalog/gestor.php?idestructura=225&ticket=testing',1,0,20,0),
	(1582,'prueba1_1',0,'../catalog/gestor.php?idestructura=225&ticket=testing',1023,0,0,0),
	(1583,'pruebas_salvador',0,'../../modulos/pruebas_salvador/index.php',1,0,20,0),
	(1584,'Tipo Operacion',0,'../catalog/gestor.php?idestructura=227&ticket=testing',1,0,20,0),
	(1585,'Tipo tercero',0,'../catalog/gestor.php?idestructura=228&ticket=testing',1,0,20,0),
	(1588,'Reportes Fiscales',0,'',18,0,9,0),
	(1589,'Reportes DIOT',1588,'',18,0,0,0),
	(1590,'Egresos sin Control de IVA',1589,'../../modulos/cont/index.php?c=EgresosSinIva&f=Inicial',18,0,0,0),
	(1591,'Localizacion de las mesas',0,'../catalog/gestor.php?idestructura=233&ticket=testing',1026,0,20,0),
	(1592,'Mesas',1895,'../catalog/gestor.php?idestructura=232&ticket=testing',1026,0,1,0),
	(1593,'Pedidos',1896,'../../modulos/restaurantes/ajax.php?c=pedidosActivos&f=zona',1026,0,2,0),
	(1594,'Comandas',1896,'../../modulos/restaurantes/ajax.php?c=comandas&f=menuMesas',1026,0,1,0),
	(1595,'Configuracion de Facturacion',0,'../catalog/gestor.php?idestructura=234&ticket=testing',19,0,20,0),
	(1598,'Movimientos con Proveedores',0,'../../modulos/cont/index.php?c=RepPeriodoAcreditamiento&f=ver',18,0,1000,0),
	(1599,'Concentrado de IVA por Proveedor',1589,'../../modulos/cont/index.php?c=ConcentradoIVAProveedor&f=verconcentrado',18,0,1,0),
	(1601,'Propina',1895,'../../modulos/restaurantes/ajax.php?c=pedidosActivos&f=configuraprodpropina',1026,0,2,0),
	(1603,'Auxiliar de Impuestos',1606,'../../modulos/cont/index.php?c=Auxiliar_Impuestos&f=filtro',18,0,0,0),
	(1604,'Declaración R-21 Impuesto al Valor Agregado',1606,'../../modulos/cont/index.php?c=declaracionR21&f=filtro',18,0,1,0),
	(1605,'Generación del IVA Acreditable',0,'../../modulos/cont/index.php?c=generacionIVA&f=filtro',18,0,1000,0),
	(1606,'Declaraciones IVA - IETU',1588,'',18,0,1,0),
	(1607,'Resumen General R-21',1606,'../../modulos/cont/index.php?c=resumenGeneralR21&f=filtro',18,0,2,0),
	(1608,'Anexos IVA Causado y Acreditable',1609,'../../modulos/cont/index.php?c=anexosIVACausadoAcreditable&f=filtro',18,0,0,0),
	(1609,'Pago Provisional de IVA',1588,'',18,0,2,0),
	(1610,'Conciliación de IVA Contable y Fiscal',1609,'../../modulos/cont/index.php?c=conciliacion_IVA_contable_fiscal&f=filtro',18,0,1,0),
	(1611,'Movimientos Auxiliares por Base Gravable',0,'../../modulos/cont/index.php?c=Movimientos_aux_base_gravable&f=filtro',18,0,1000,0),
	(1612,'Conciliación de Efectivo y Pago Provisional d',0,'../../modulos/cont/index.php?c=ConcFlujoEfec_Pago_provisional_IVA&f=filtro',18,0,1000,0),
	(1613,'Bienvenido',0,'../../modulos/inicio/index.php',1,0,0,-1),
	(1614,'Auxiliar de Formato A-29',1589,'../../modulos/cont/index.php?c=auxiliar_a29&f=Inicial',18,0,2,0),
	(1617,'Auxiliar de Movimientos de Control de IVA',1589,'../../modulos/cont/index.php?c=auxiliar_controlIva&f=Inicial',18,0,3,0),
	(1618,'Hazbizne',0,'../../modulos/hazbizne/index.php',1,0,20,-1),
	(1621,'Conciliación de Flujo de Efectivo e IVA',1589,'../../modulos/cont/index.php?c=flujoEfectivoIva&f=Inicial',18,0,4,0),
	(1622,'Reporte de Movimientos por Cuentas',0,'../repolog/repolog.php?i=57',18,0,1000,0),
	(1623,'Tipo de Unidad de Medida',0,'../../modulos/mrp/index.php/unidadesTree',1023,0,0,0),
	(1624,'SMS Grupos',0,'../catalog/gestor.php?idestructura=244&ticket=testing',1018,0,20,0),
	(1625,'SMS Clientes',0,'../../modulos/sms/clientes.php',1018,0,0,0),
	(1626,'Reporte Costo y Precio',0,'../repolog/repolog.php?i=58',13,0,20,0),
	(1627,'Hazbizne: Ofertas',0,'../../modulos/hazbizne/ofertas.php',1028,0,10,0),
	(1628,'SMS Origen rutas',0,'../catalog/gestor.php?idestructura=245&ticket=testing',1018,0,20,0),
	(1629,'sms transporte',0,'../catalog/gestor.php?idestructura=246&ticket=testing',1018,0,20,0),
	(1631,'sms capacidades',0,'../catalog/gestor.php?idestructura=248&ticket=testing',1018,0,20,0),
	(1632,'Movimientos por Cuentas',1641,'../../modulos/cont/index.php?c=Reports&f=movcuentas',18,0,0,0),
	(1633,'Balanza de Comprobación',1639,'../../modulos/cont/index.php?c=reports&f=balanzaComprobacion',18,0,2,0),
	(1635,'Comunidad y contactos',0,'../../modulos/hazbizne/comunidad/index.php',1028,0,20,0),
	(1636,'Interactúa en foros',0,'../../modulos/hazbizne/foros/index.php',1028,0,30,0),
	(1637,'Artículos de proveedores',0,'../../modulos/hazbizne/articulos/index.php',1028,0,15,0),
	(1639,'Estados Financieros',1120,'',18,0,0,0),
	(1640,'Reportes de Catálogos',0,'',18,0,5,0),
	(1641,'Auxiliares',1120,'',18,0,1,0),
	(1642,'Anexos de Catálogo',1641,'../repolog/repolog.php?i=59',18,0,1,0),
	(1643,'Contabilidad Electrónica',0,'',18,0,7,0),
	(1645,'XMLs Catalogo de Cuentas',1643,'../../modulos/cont/index.php?c=Reports&f=catalogoXML',18,0,0,0),
	(1646,'XMLs Balanza de Comprobación',1643,'../../modulos/cont/index.php?c=Reports&f=balanzaComprobacionXML',18,0,1,0),
	(1647,'Asignación de Cuentas',139,'../../modulos/cont/index.php?c=Config&f=configAccounts',18,0,2,0),
	(1648,'Cobros a Clientes',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=filtroAutomaticas&t=cobro',18,0,1,0),
	(1649,'Pagos a Proveedores',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=filtroAutomaticas&t=pago',18,0,2,0),
	(1652,'Pólizas de Provisión',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=verprovision',18,0,0,0),
	(1653,'Validador de XMLs',1643,'../../modulos/cont/xmls/valida_xmls/validacion.php',18,0,4,0),
	(1654,'Almacén Digital',1643,'../../modulos/cont/index.php?c=Reports&f=almacenXML',18,0,3,0),
	(1656,'Segmentos de Negocio',139,'../catalog/gestor.php?idestructura=251&ticket=testing',18,0,1,0),
	(1663,'Asignación Periodo Acreditamiento',1666,'../../modulos/cont/index.php?c=reports&f=listaAcreditamientoProveedores',18,0,0,0),
	(1664,'Pólizas',0,'',18,0,1,0),
	(1665,'Pólizas Automáticas',0,'',18,0,2,0),
	(1666,'Control de IVA',0,'',18,0,8,0),
	(1667,'A-29 Proveedores TXT',1666,'../../modulos/cont/index.php?c=Reports&f=a29Txt',18,0,0,0),
	(1668,'Resumen de IVAs retenidos ',1606,'../catalog/gestor.php?idestructura=252&ticket=testing',18,0,1,0),
	(1671,'Punto de Venta',139,'../../modulos/cont/index.php?c=Config&f=configPDV',18,0,3,0),
	(1675,'Tipos de Cambio',139,'../catalog/gestor.php?idestructura=257&ticket=testing',18,0,7,0),
	(1677,'Póliza de Ajuste por Diferencia Cambiaria',1665,'../../modulos/cont/index.php?c=Ajustecambiario&f=verfiltro',18,0,5,0),
	(1694,'Estado de Origen y Aplicación de Recursos',1639,'../../modulos/cont/index.php?c=reports&f=balanceGeneral&tipo=2',18,0,5,0),
	(1695,'Clasificación NIF de Cuentas',139,'../../modulos/cont/index.php?c=AccountsTree&f=cuentasNIF',18,0,6,0),
	(1696,'NIF B-6 Estado de Situación Financiera',1702,'../../modulos/cont/index.php?c=reports&f=balanceGeneral&tipo=3',18,0,5,0),
	(1699,'Declaración R-54 Impuesto Empresarial a T.U.',1606,'../../modulos/cont/index.php?c=Declaracionr54&f=viewdeclaracion',18,0,1000,0),
	(1702,'Estados Financieros NIF',1120,'',18,0,1,0),
	(1703,'Libro de Diario',1639,'../repolog/repolog.php?i=61',18,0,4,0),
	(1704,'NIF B-3 Estado de resultado Integral',1702,'../../modulos/cont/index.php?c=reports&f=balanceGeneral&tipo=4',18,0,6,0),
	(1706,'Bancos de Beneficiarios/Proveedores ',0,'../catalog/gestor.php?idestructura=275&ticket=testing',1048,0,2,0),
	(1727,'Datos generales constructores',0,'../../modulos/xtructur/index.php?modulo=datos_generales',1058,0,1,0),
	(1728,'Alta de obra',0,'../../modulos/xtructur/index.php?modulo=alta_obra',1058,0,2,0),
	(1729,'Presupuesto ',0,'',1059,0,1,0),
	(1730,'Presupuesto contractual',1729,'../../modulos/xtructur/index.php?modulo=crear_presu_control',1059,0,1,0),
	(1732,'Proforma',1729,'../../modulos/xtructur/index.php?modulo=proforma',1059,0,2,0),
	(1733,'Explosion de insumos',1729,'../../modulos/xtructur/index.php?modulo=explosion_insumos',1059,0,4,0),
	(1734,'Desgloce de indirectos',1729,'../../modulos/xtructur/index.php?modulo=desgloce_indirectos',1059,0,3,0),
	(1735,'PlaneaciÃ³n',0,'',1059,0,2,0),
	(1736,'Definir planeacion',1735,'../../modulos/xtructur/index.php?modulo=planeacion',1059,0,3,0),
	(1737,'Visualizar arbol de planeacion',1735,'../../modulos/xtructur/index.php?modulo=arbol',1059,0,8,0),
	(1738,'Definir familia de materiales',1735,'../../modulos/xtructur/index.php?modulo=materiales',1059,0,10,0),
	(1741,'Asignar planeacion a presupuesto',1735,'../../modulos/xtructur/index.php?modulo=asignar_planeacion',1059,0,4,0),
	(1742,'Asignar PU a destajo',1735,'../../modulos/xtructur/index.php?modulo=pu_destajos',1059,0,6,0),
	(1743,'Asignar PU a subcontrato',1735,'../../modulos/xtructur/index.php?modulo=pu_subcontratos',1059,0,7,0),
	(1745,'Asignar familias a explosion de insumos',1735,'../../modulos/xtructur/index.php?modulo=asignar_familias',1059,0,11,0),
	(1746,'Perfil de empleados',0,NULL,1059,0,3,0),
	(1747,'Tabulador tecnico administrativo',1746,'../../modulos/xtructur/index.php?modulo=tab_tecnicos',1059,0,3,0),
	(1748,'Tabulador de obreros',1746,'../../modulos/xtructur/index.php?modulo=tab_obreros',1059,0,4,0),
	(1749,'Altas de sistema',0,NULL,1059,0,4,0),
	(1750,'Tecnicos',1749,'../../modulos/xtructur/index.php?modulo=alta_tecnicos',1059,0,1,0),
	(1751,'Administrativos',1749,'../../modulos/xtructur/index.php?modulo=alta_administrativos',1059,0,2,0),
	(1752,'Subcontratistas',1749,'../../modulos/xtructur/index.php?modulo=alta_subcontratistas',1059,0,5,0),
	(1753,'Proveedores',1749,'../../modulos/xtructur/index.php?modulo=alta_proveedores',1059,0,7,0),
	(1755,'Solicitud de Extraordinarios',2270,'../../modulos/xtructur/index.php?modulo=crear_presu_control',1060,0,1,0),
	(1756,'Solicitud de Adicionales',2270,'../../modulos/xtructur/index.php?modulo=crear_presu_control',1060,0,3,0),
	(1757,'Solicitud de No Cobrables',2270,'../../modulos/xtructur/index.php?modulo=crear_presu_control',1060,0,2,0),
	(1758,'Visualizar Cuentas de Costo',0,NULL,1060,0,1,0),
	(1759,'Costo acumulado',1758,'../../modulos/xtructur/index.php?modulo=costo_acumulado',1060,0,3,0),
	(1760,'Costo directo',1758,'../../modulos/xtructur/index.php?modulo=costo_directo',1060,0,1,0),
	(1761,'Costo indirecto',1758,'../../modulos/xtructur/index.php?modulo=costo_indirecto',1060,0,2,0),
	(1762,'Compras',0,NULL,1060,0,2,0),
	(1763,'Elaboracion de Requisiciones',1762,'../../modulos/xtructur/index.php?modulo=requisiciones',1060,0,1,0),
	(1764,'Elaboracion de Ordenes de compra',1762,'../../modulos/xtructur/index.php?modulo=pedidos',1060,0,3,0),
	(1765,'Entradas de almacen',1762,'../../modulos/xtructur/index.php?modulo=entradas',1060,0,5,0),
	(1766,'Salidas de almacen',1762,'../../modulos/xtructur/index.php?modulo=salidas',1060,0,6,0),
	(1767,'Personal',0,NULL,1060,0,3,0),
	(1768,'Maestros',1749,'../../modulos/xtructur/index.php?modulo=alta_destajista',1059,0,3,0),
	(1769,'Obreros',1749,'../../modulos/xtructur/index.php?modulo=alta_obreros',1059,0,4,0),
	(1770,'Personal de subcontratos',1749,'../../modulos/xtructur/index.php?modulo=alta_ps',1059,0,6,0),
	(1771,'Control de Asistencia Obreros',1767,'../../modulos/xtructur/index.php?modulo=tomaduria',1060,0,1,0),
	(1772,'Elaboracion Nomina Obreros',1767,'../../modulos/xtructur/index.php?modulo=prenomina',1060,0,2,0),
	(1774,'Alta familia obreros',1746,'../../modulos/xtructur/index.php?modulo=alta_fam_obreros',1059,0,2,0),
	(1775,'Alta familia Tecnicos-Administradores',1746,'../../modulos/xtructur/index.php?modulo=alta_fam_tecnicos',1059,0,1,0),
	(1776,'Autorizacion de RequisiciÃ³n',1762,'../../modulos/xtructur/index.php?modulo=visualizar_requi',1060,0,2,0),
	(1777,'Autorizacion de Ordenes de compra',1762,'../../modulos/xtructur/index.php?modulo=visualizar_pedi',1060,0,4,0),
	(1778,'Seleccionar concepto a Destajo o Subcontrato',1735,'../../modulos/xtructur/index.php?modulo=indicarpu',1059,0,5,0),
	(1779,'Control de Estimaciones',0,NULL,1060,0,4,0),
	(1780,'Estado de resultados',1779,'../../modulos/xtructur/index.php?modulo=estado_resultados',1032,0,11,0),
	(1781,'Elaboracion Estimacion Maestros',1779,'../../modulos/xtructur/index.php?modulo=est_destajistas',1060,0,1,0),
	(1782,'Elaboracion Estimacion Subcontratistas',1779,'../../modulos/xtructur/index.php?modulo=est_subcontratistas',1060,0,3,0),
	(1783,'Elaboracion Estimacion Proveedores',1779,'../../modulos/xtructur/index.php?modulo=est_proveedores',1060,0,5,0),
	(1786,'Elaboracion Estimacion Indirectos',1779,'../../modulos/xtructur/index.php?modulo=est_indirectos',1060,0,9,0),
	(1787,'Elaboracion Estimacion Caja Chica',1779,'../../modulos/xtructur/index.php?modulo=est_cc',1060,0,10,0),
	(1793,'Cuentas por Pagar',2271,'../../modulos/xtructur/index.php?modulo=remesas',1060,0,1,0),
	(1794,'Cuentas Pagadas',2271,'../../modulos/xtructur/index.php?modulo=cheques',1060,0,2,0),
	(1798,'Elaboracion Estimacion Clientes',1779,'../../modulos/xtructur/index.php?modulo=est_cliente',1060,0,7,0),
	(1800,'Reportes',0,NULL,1057,0,7,0),
	(1801,'Visualizar entradas de almacen',1762,'../../modulos/xtructur/index.php?modulo=visualizar_entradas',1060,0,7,0),
	(1802,'Visualizar salidas de almacen',1762,'../../modulos/xtructur/index.php?modulo=visualizar_salidas',1060,0,8,0),
	(1804,'Cuentas Bancarias',0,'../catalog/gestor.php?idestructura=280&ticket=testing',1033,0,2,0),
	(1807,'Conciliacion Bancaria',0,NULL,1033,0,5,0),
	(1808,'Importar estado de cuenta bancario',1807,'../../modulos/bancos/index.php?c=importarEstadoCuenta&f=verImport',1033,0,1,0),
	(1831,'Control de Asistencia Tec-Admon',1767,'../../modulos/xtructur/index.php?modulo=nom_tom_oce',1060,0,4,0),
	(1832,'Elaboracion Nomina Tec-Admon Oficina Central',1767,'../../modulos/xtructur/index.php?modulo=nom_ocen',1060,0,5,0),
	(1834,'Elaboracion Nomina Tec-Admon Oficina Campo',1767,'../../modulos/xtructur/index.php?modulo=nom_oce',1060,0,6,0),
	(1835,'Avance de obra',1800,'../../modulos/xtructur/index.php?modulo=unovsuno',1057,0,3,0),
	(1836,'XMLs Polizas',1643,'../../modulos/cont/index.php?c=Reports&f=polizasXML',18,0,2,0),
	(1837,'Perfiles Usuarios',0,'../../modulos/perfiles/index.php',1,0,2,0),
	(1840,'Ingresos vs Egresos',1800,'../../modulos/xtructur/index.php?modulo=ingresos_egresos',1057,0,4,0),
	(1842,'Color de Interfaz',0,'../../modulos/styleselector/index.php',1,0,4,0),
	(1846,'Control de indirectos',1800,'../../modulos/xtructur/index.php?modulo=control_indirectos',1057,NULL,1,NULL),
	(1847,'Prontipagos',0,NULL,1024,0,4,0),
	(1848,'Configuracion prontipagos',1847,'../../modulos/prontipagos/setsettings.php',1024,0,0,0),
	(1849,'Consulta de Saldo',1847,'../../modulos/prontipagos/getbalance.php',1024,0,1,0),
	(1859,'Pago de Servicios',1847,'../../modulos/prontipagos/sell.php',1024,0,2,0),
	(1866,'Anticipo de Gastos',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=anticipo',18,0,3,0),
	(1867,'Comprobacion Anticipo Gastos',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=comprobacion',18,0,4,0),
	(1874,'Pólizas de Provisión multiple',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=filtroAutomaticas&t=provision',18,0,0,0),
	(1875,'Estatus comandas',1893,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_estatus_comandas',1026,0,1,0),
	(1878,'Entradas almacen',1904,'../../modulos/punto_venta/movimientos/recepcionmovimientos.php',3,0,21,0),
	(1879,'XMLs Folios',1643,'../../modulos/cont/index.php?c=Reports&f=foliosXML',18,0,2,0),
	(1881,'Subir Comprobantes',0,'../../modulos/cont/index.php?c=CaptPolizas&f=subecomprobantes',18,0,20,0),
	(1882,'Retiro de Caja',0,'../../modulos/punto_venta_nuevo/index.php?c=retiro&f=imprimeretiro',1024,0,3,0),
	(1883,'Costo acumulado a detalle',1800,'../../modulos/xtructur/index.php?modulo=acumulado_detalle',1057,0,5,0),
	(1884,'XMLs Auxiliar de Cuentas',1643,'../../modulos/cont/index.php?c=Reports&f=auxCuentasXML',18,0,2,0),
	(1886,'Catalogo partidas',1735,'../../modulos/xtructur/index.php?modulo=cat_partidas',1059,0,2,0),
	(1888,'Provisión Detallada',1665,'../../modulos/cont/index.php?c=CaptPolizas&f=filtroAutomaticas&t=provisiond&detalle=1',18,0,0,0),
	(1891,'Empleados',0,'../catalog/gestor.php?idestructura=301&ticket=testing',1023,0,3,0),
	(1892,'Promedio por comensal',1893,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_promedio_comensal',1026,0,2,0),
	(1893,'Reportes',0,'',1026,0,22,0),
	(1895,'Ajustes',0,'',1026,0,1,0),
	(1896,'Servicios',0,'',1026,0,2,0),
	(1897,'Provision Recibo Nomina',1902,'../../modulos/cont/index.php?c=Nomina&f=viewNomina',18,0,1,0),
	(1899,'Catalogo especialidades',1735,'../../modulos/xtructur/index.php?modulo=cat_especialidades',1059,0,1,0),
	(1900,'Pago Recibo Nomina',1902,'../../modulos/cont/index.php?c=Nomina&f=viewPagoNomina',18,0,2,0),
	(1902,'Nominas',0,NULL,18,0,3,0),
	(1903,'Departamento de Compras',0,'',3,0,0,0),
	(1904,'Movimiento Entre Almacences',0,'',3,0,2,0),
	(1905,'Retenciones y fondos de garantia',1800,'../../modulos/xtructur/index.php?modulo=retenciones',1057,NULL,2,NULL),
	(1907,'Realizar Conciliacion',1908,'../../modulos/cont/index.php?c=conciliacionAcontia&f=verCaratulaConciliacion',18,0,1,0),
	(1908,'Conciliacion Bancaria',0,NULL,18,0,3,0),
	(1911,'Reporte de Estado de Cuenta',1807,'../../modulos/cont/index.php?c=conciliacionAcontia&f=estadocuentafiltro',1033,0,2,0),
	(1912,'Reservaciones',1893,'../../modulos/restaurantes/ajax.php?c=reservaciones&f= vista_reservaciones',1026,0,3,0),
	(1915,'Reporte Conciliacion',1908,'../../modulos/cont/index.php?c=conciliacionAcontia&f=verReporteConciliacion',18,0,2,0),
	(1920,'Seguridad',1895,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_seguridad',1026,0,3,0),
	(1922,'Presupuestos',0,'',18,0,8,0),
	(1924,'Crear/Ver Presupuesto',1922,'../../modulos/cont/index.php?c=Presupuesto&f=creaPresupuesto',18,0,1,0),
	(1927,'Usuarios obras',0,'../../modulos/xtructur/index.php?modulo=usuarios_obras',1058,0,3,0),
	(1930,'Empleados',1895,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_empleados',1026,0,4,0),
	(1960,'Avanzada',0,'../../modulos/appministra/index.php?c=configuracion&f=general&p=0',1050,0,1,0),
	(1961,'Clasificadores',0,'../../modulos/appministra/index.php?c=configuracion&f=clasificadores',1050,0,4,0),
	(1974,'Requisiciones',0,'../../modulos/appministra/index.php?c=compras&f=requisiciones',1044,0,3,0),
	(1975,'Recepcion',0,'../../modulos/appministra/index.php?c=compras&f=recepcion',1044,0,5,0),
	(1980,'Ordenes de compra',0,'../../modulos/appministra/index.php?c=compras&f=ordenes',1044,0,4,0),
	(1985,'Clasificadores Productos',0,'../../modulos/appministra/index.php?c=configuracion&f=clasificadoresProd',1050,0,6,0),
	(1986,'Caracteristicas Productos',0,'../../modulos/appministra/index.php?c=configuracion&f=caracteristicasProd',1050,0,7,0),
	(1987,'Tipos de Crédito',0,'../../modulos/appministra/index.php?c=configuracion&f=credito',1050,0,8,0),
	(1988,'Listas de Precios',0,'../../modulos/appministra/index.php?c=configuracion&f=listas_precio',1050,0,10,0),
	(1990,'Unidades de Medida y Peso',0,'../../modulos/appministra/index.php?c=configuracion&f=medida',1050,0,9,0),
	(1993,'Impuestos',0,'../../modulos/appministra/index.php?c=configuracion&f=impuestos',1048,0,6,0),
	(1999,'Cotizaciones',0,'../../modulos/appministra/index.php?c=ventas&f=requisiciones',1045,0,1,0),
	(2007,'Ordenes de venta',0,'../../modulos/appministra/index.php?c=ventas&f=ordenes',1045,0,2,0),
	(2010,'Movimientos de Inventario',0,'../../modulos/appministra/index.php?c=inventarios&f=entradas',1046,0,1,0),
	(2032,'Ordenes de Compra',0,'../../modulos/pos/index.php?c=compra&f=indexgrid',1044,0,1,0),
	(2033,'Recepcion Orden Compra',0,'../../modulos/pos/index.php?c=compra&f=recepcionGrid',1044,0,2,0),
	(2034,'Productos',0,'../../modulos/pos/index.php?c=producto&f=indexGridProductos',1048,0,3,0),
	(2036,'Kardex',2102,'../../modulos/pos/index.php?c=inventario&f=indexGrid',1049,0,4,0),
	(2039,'Mermas',0,'../../modulos/pos/index.php?c=inventario&f=indexGridMermas',1046,0,2,0),
	(2049,'Clientes',0,'../../modulos/pos/index.php?c=cliente&f=indexGrid',1048,0,4,0),
	(2051,'Caja',0,'../../modulos/pos/index.php?c=caja&f=indexCaja',1042,0,1,0),
	(2080,'Cuentas por Pagar',0,'',1047,0,2,0),
	(2081,'Aplicar pago',2080,'../../modulos/appministra/index.php?c=cuentas&f=cuentasxpagar',1047,0,1,0),
	(2085,'Cuentas por Pagar',0,NULL,1049,0,4,0),
	(2086,'Resumen de Saldos por Proveedor',2085,'../../modulos/appministra/index.php?c=Reportes_Cuentas&f=resumen_saldos',1049,0,1,0),
	(2087,'Auxiliar Movimientos Cuentas por Pagar',2085,'../../modulos/appministra/index.php?c=Reportes_Cuentas&f=aux_mov_cxp',1049,0,2,0),
	(2088,'Antigüedad de Saldos Proveedores',2085,'../../modulos/appministra/index.php?c=Reportes_Cuentas&f=ant_saldos',1049,0,3,0),
	(2089,'Pronóstico de Pagos',2085,'../../modulos/appministra/index.php?c=Reportes_Cuentas&f=pronos_pagos',1049,0,4,0),
	(2092,'Envios',0,'../../modulos/appministra/index.php?c=ventas&f=envios',1045,0,3,0),
	(2094,'Retiro de Caja',0,'../../modulos/pos/index.php?c=retiro&f=imprimeretiro',1042,0,2,0),
	(2095,'Cuentas por Cobrar',0,NULL,1049,0,5,0),
	(2096,'Cuentas por Cobrar',0,NULL,1047,0,1,0),
	(2097,'Aplicar cobro',2096,'../../modulos/appministra/index.php?c=cuentas&f=cuentasxcobrar',1047,0,1,0),
	(2098,'Resumen de Saldos por Cliente',2095,'../../modulos/appministra/index.php?c=Reportes_Cuentas&f=resumen_saldos_cobrar',1049,0,1,0),
	(2099,'Auxiliar de Movimientos Cuentas por Cobrar',2095,'../../modulos/appministra/index.php?c=Reportes_Cuentas&f=aux_mov_cxc',1049,0,2,0),
	(2100,'Antigüedad de Saldos Clientes',2095,'../../modulos/appministra/index.php?c=Reportes_Cuentas&f=ant_saldos_cxc',1049,0,3,0),
	(2101,'Pronóstico de Cobros',2095,'../../modulos/appministra/index.php?c=Reportes_Cuentas&f=pronos_cobros',1049,0,4,0),
	(2102,'Inventarios',0,NULL,1049,0,1,0),
	(2103,'Kardex',2102,'../../modulos/appministra/index.php?c=reportes&f=kardex',1049,0,5,0),
	(2104,'Existencias',2102,'../../modulos/appministra/index.php?c=reportes&f=existencias',1049,0,3,0),
	(2105,'Catalogo Productos y Servicios',2102,'../../modulos/appministra/index.php?c=reportes&f=cataproductos',1049,0,6,0),
	(2106,'Venta Global',0,'../../modulos/pos/index.php?c=caja&f=ventasGrid',1045,0,20,0),
	(2108,'Inventario Actual',2102,'../../modulos/pos/index.php?c=inventario&f=inventarioActual',1049,0,1,0),
	(2110,'Reportes compras',0,'',1049,0,2,0),
	(2111,'Grafica mas comprados',2110,'../../modulos/appministra/index.php?c=reportes_compras&f=grafi_compras',1049,0,1,0),
	(2112,'Reportes ventas',0,NULL,1049,0,3,0),
	(2113,'Grafica mas vendidos',2112,'../../modulos/appministra/index.php?c=reportes_ventas&f=grafi_ventas',1049,0,1,0),
	(2115,'Cortes de Caja',0,'../../modulos/pos/index.php?c=caja&f=cortesGrid',1042,0,3,0),
	(2118,'Series, Lotes, Pedimentos y Caducos',2102,'../../modulos/appministra/index.php?c=inventarios&f=reporte_slp',1049,NULL,7,NULL),
	(2119,'Etiquetas',0,'../../modulos/pos/index.php?c=inventario&f=indexEtiquetado',1046,0,3,0),
	(2122,'Facturacion masiva',0,'../../modulos/appministra/index.php?c=ventas&f=masiva',1045,0,4,0),
	(2124,'Inventario Actual',2102,'../../modulos/appministra/index.php?c=reportes&f=inventarioactual',1049,0,2,0),
	(2125,'Compras por proveedor y por producto',2110,'../../modulos/appministra/index.php?c=Reportes_Compras&f=prov_prod',1049,0,2,0),
	(2126,'Cobranza por vendedor',2112,'../../modulos/appministra/index.php?c=reportes_ventas&f=cobranza_vendedor',1049,0,3,0),
	(2127,'Reporte Facturas',2112,'../../modulos/appministra/index.php?c=ventas&f=facturas',1049,0,2,0),
	(2135,'Almacenes',0,'',1050,0,11,0),
	(2136,'Arbol de Almacenes',2135,'../../modulos/appministra/index.php?c=almacenes&f=index',1050,0,2,0),
	(2137,'Mi Organización',2147,'../catalog/gestor.php?idestructura=1&ticket=testing',1050,0,2,0),
	(2138,'Bienvenido',2147,'../../modulos/inicio/index.php',1050,0,1,0),
	(2139,'Perfiles de Usuario',2147,'../../modulos/perfiles/index.php',1050,0,3,0),
	(2140,'Administración Usuarios',2147,'../catalog/gestor.php?idestructura=47&ticket=testing',1050,0,4,0),
	(2141,'Color de Interfaz',2147,'../../modulos/styleselector/index.php',1050,0,5,0),
	(2142,'Importar clientes',0,'../../modulos/punto_venta/views/clientes/importar_clientes.php',1048,0,1,0),
	(2143,'Importar proveedores',0,'../../modulos/punto_venta/views/proveedores/importar_proveedores.php',1048,0,2,0),
	(2144,'Beneficiarios/Proveedores ',0,'../../modulos/punto_venta/catalogos/proveedor.php',1048,0,5,0),
	(2145,'Contabilidad Electronica',0,NULL,1049,0,6,0),
	(2146,'Almacén Digital',2145,'../../modulos/cont/index.php?c=Reports&f=almacenXML',1049,0,1,0),
	(2147,'General',0,'',1050,0,2,0),
	(2148,'Facturacion',0,'',1050,0,3,0),
	(2149,'Datos de Facturacion',2148,'../catalog/gestor.php?idestructura=234&ticket=testing',1050,0,1,0),
	(2150,'Serie y Folio',2148,'../catalog/gestor.php?idestructura=221&ticket=testing',1050,0,2,0),
	(2151,'Clasificador Ingresos-Egresos',0,'../catalog/gestor.php?idestructura=285&ticket=testing',1050,0,5,0),
	(2152,'Sucursal',2135,'../catalog/gestor.php?idestructura=86&ticket=testing',1050,0,1,0),
	(2153,'Listado Facturas',0,'../repolog/repolog.php?i=10',1043,0,1,0),
	(2154,'Ventas No Facturadas',0,'../repolog/repolog.php?i=43',1043,0,2,0),
	(2155,'Empleados',0,'../catalog/gestor.php?idestructura=301&ticket=testing',1048,0,5,0),
	(2156,'Comandera',0,'../../modulos/restaurantes/ajax.php?c=comandas&f=menuMesas',1051,0,1,0),
	(2157,'Pedidos',0,'../../modulos/restaurantes/ajax.php?c=pedidosActivos&f=zona',1051,0,2,0),
	(2158,'Reservaciones',0,'../../modulos/restaurantes/ajax.php?c=reservaciones&f=mapa_reservaciones',1052,0,1,0),
	(2159,'Recetas',0,'../../modulos/restaurantes/ajax.php?c=recetas&f=vista_recetas',1053,0,1,0),
	(2160,'Foodware',0,NULL,1049,0,1,0),
	(2161,'Estatus comanda',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_estatus_comandas',1049,0,1,0),
	(2162,'Actividad empleado',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_actividad',1049,0,2,0),
	(2163,'Promedio por comensal',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_promedio_comensal',1049,0,3,0),
	(2164,'Comensales por mesa',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_comensales',1049,0,4,0),
	(2165,'Zonas de mayor afluencia',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_zonas',1049,0,5,0),
	(2166,'Ocupacion',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_ocupacion',1049,0,6,0),
	(2167,'Reservaciones',2160,'../../modulos/restaurantes/ajax.php?c=reservaciones&f=vista_reservaciones',1049,0,7,0),
	(2168,'Seguridad',2177,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_seguridad',1050,0,1,0),
	(2169,'Ajustes',2177,'../../modulos/restaurantes/ajax.php?c=pedidosActivos&f=configuraprodpropina',1050,0,2,0),
	(2170,'Platillos',2177,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_productos',1050,0,3,0),
	(2171,'Mesas',2177,'../catalog/gestor.php?idestructura=232&ticket=testing',1050,0,4,0),
	(2172,'Empleados',2177,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_empleados',1050,0,5,0),
	(2173,'Asignaciones',2177,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_asignaciones',1050,0,6,0),
	(2174,'Pedidos Movil',0,'../../modulos/pos_pedidos/index.php?c=caja&f=indexCaja&cliente=1268',1042,0,10,-1),
	(2175,'Pedidos y Cotizaciones',0,'../../modulos/pos_pedidos/index.php?c=pedido&f=imprimeGridP',1042,0,11,0),
	(2176,'Area Empleado',0,'../catalog/gestor.php?idestructura=364&ticket=testing',1048,0,7,0),
	(2177,'Foodware',0,NULL,1050,0,2,0),
	(2180,'Tickets no Facturados',0,'../../modulos/pos/index.php?c=caja&f=pendienteFacturar',1043,0,3,0),
	(2181,'Lista de Facturas',0,'../../modulos/pos/index.php?c=caja&f=gridFacturas',1043,0,4,0),
	(2188,'Devoluciones de Compras',2110,'../../modulos/appministra/index.php?c=reportes&f=devolucionespro',1049,0,3,0),
	(2190,'Utilidad Bruta',0,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_utilidad',1045,0,2,0),
	(2194,'Promociones',0,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_promociones',1050,0,1,0),
	(2195,'Análisis de Ventas',0,'../../modulos/pos/index.php?c=reporte&f=indexReportes',1045,0,21,0),
	(2197,'IngenierÃ­a de menÃº',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_producto_detalle',1049,0,6,0),
	(2198,'Menu Digital',2177,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_menu',1050,0,7,0),
	(2199,'Lista de Compra Generales',0,'../../modulos/pos_pedidos/index.php?c=caja&f=listaCompraA',1042,0,14,0),
	(2200,'Traspasos de Inventario',0,'../../modulos/appministra/index.php?c=inventarios&f=sol_traspasos',1046,0,2,0),
	(2201,'RecepciÃ³n de Traspasos',0,'../../modulos/appministra/index.php?c=inventarios&f=recepciones',1046,0,3,0),
	(2202,'Preparacion',0,'../../modulos/restaurantes/ajax.php?c=recetas&f=vista_preparacion',1053,0,2,0),
	(2203,'kits',0,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_kits',1050,0,1,0),
	(2205,'Repartidores',0,'../../modulos/restaurantes/ajax.php?c=repartidores&f=pedidosRep',1051,0,3,0),
	(2206,'Repartidores',2160,'../../modulos/restaurantes/ajax.php?c=repartidores&f=reporteRep',1049,0,8,0),
	(2207,'Autorizacion Estimacion Clientes',1779,'../../modulos/xtructur/index.php?modulo=est_cliente_bit',1060,0,8,0),
	(2214,'Autorizacion Estimacion Maestros',1779,'../../modulos/xtructur/index.php?modulo=est_destajistas_bit',1060,0,2,0),
	(2215,'Autorizacion Estimacion Subcontratistas',1779,'../../modulos/xtructur/index.php?modulo=est_subcontratistas_bit',1060,0,4,0),
	(2216,'Autorizacion Estimacion Proveedores',1779,'../../modulos/xtructur/index.php?modulo=est_proveedores_bit',1060,0,6,0),
	(2222,'Combos',0,'../../modulos/restaurantes/ajax.php?c=configuracion&f=vista_combos',1050,0,1,0),
	(2229,'TPL Polizas',0,'../../modulos/appministra/index.php?c=configuracion&f=polizas&p=0',1050,0,1,0),
	(2238,'Inventarios',1762,'../../modulos/xtructur/index.php?modulo=inventarios',1060,0,0,0),
	(2240,'Tarjetas de Regalo',0,'../../modulos/pos/index.php?c=caja&f=gridTarjetasRegalo',1042,0,4,0),
	(2242,'Cargar programa de obra',2264,'../../modulos/xtructur/index.php?modulo=programa_obra',1060,0,1,0),
	(2247,'Polizas Manuales',0,'../../modulos/appministra/index.php?c=configuracion&f=polizas_manuales',1050,0,1,0),
	(2253,'Organismos Intermedios',0,'../../modulos/inovekia_dashboard/index.php?c=organismo&f=index',1056,0,0,0),
	(2254,'Supervisores',0,'../../modulos/inovekia_dashboard/index.php?c=supervisor&f=index',1056,0,0,0),
	(2259,'Panel Clientes',0,'../../modulos/cont/index.php?c=edu&f=index',0,0,0,0),
	(2263,'Configuracion inicial',0,'../../modulos/xtructur/index.php?modulo=config',1058,0,0,0),
	(2264,'Control de obra',NULL,NULL,1060,0,0,0),
	(2265,'Gantt',2264,'../../modulos/xtructur/index.php?modulo=gantt',1060,0,2,0),
	(2266,'Tablero Solicitudes Pendientes',0,'../../modulos/xtructur/index.php?modulo=tablero',1058,0,0,0),
	(2268,'Autorizacion Nomina Obreros',1767,'../../modulos/xtructur/index.php?modulo=prenomina_auth',1060,0,3,0),
	(2269,'Propinas',2160,'../../modulos/restaurantes/ajax.php?c=comandas&f=vista_propinas',1049,0,9,0),
	(2270,'Presupuesto Actualizado',0,NULL,1060,0,5,0),
	(2271,'Pasivos',0,NULL,1060,0,6,0),
	(2272,'Autorizar cuentas por pagar',2271,'../../modulos/xtructur/index.php?modulo=aut_cuentaspp',1060,0,0,0),
	(2276,'Autorizacion prenomina oficina central',1767,'../../modulos/xtructur/index.php?modulo=prenom_ocen',1060,0,7,0),
	(2278,'Autorizacion prenomina campo',1767,'../../modulos/xtructur/index.php?modulo=prenom_oce',1060,0,8,0),
	(2279,'Autorizacion estimacion caja chica',1779,'../../modulos/xtructur/index.php?modulo=viz_cc',1060,0,11,0),
	(2281,'Autorizacion estimacion indirectos',1779,'../../modulos/xtructur/index.php?modulo=viz_ind',1060,0,12,0),
	(2284,'Cursos',0,'../../modulos/inovekia_dashboard/index.php?c=lms&f=index',1056,0,0,0),
	(2287,'Historial de movimientos - NÃ³minas',2293,'../../modulos/xtructur/index.php?modulo=historialnom',1057,0,9,0),
	(2290,'Historial de movimientos - Estimaciones',2293,'../../modulos/xtructur/index.php?modulo=historialest',1057,0,12,0),
	(2291,'Historial de movimientos - Pasivos',2293,'../../modulos/xtructur/index.php?modulo=historialpas',1057,0,4,0),
	(2292,'Movimiento de Polizas y Facturas',1664,'../../modulos/cont/index.php?c=Reports&f=movpolizas',18,0,2,0),
	(2293,'Historial',0,NULL,1057,0,7,0),
	(2295,'Notificaciones',0,'../../modulos/xtructur/index.php?modulo=notilog',1058,0,6,0),
	(2299,'Asignar PU a Conceptos',0,'../../modulos/xtructur/index.php?modulo=recetas',1059,0,5,0);

/*!40000 ALTER TABLE `accelog_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla accelog_niveles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accelog_niveles`;

CREATE TABLE `accelog_niveles` (
  `idestructura` int(11) NOT NULL,
  `nombrecampo_empleados` varchar(50) NOT NULL,
  `nombreestructura` varchar(50) NOT NULL,
  PRIMARY KEY (`idestructura`,`nombrecampo_empleados`,`nombreestructura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla accelog_opciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accelog_opciones`;

CREATE TABLE `accelog_opciones` (
  `idopcion` varchar(45) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idopcion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `accelog_opciones` WRITE;
/*!40000 ALTER TABLE `accelog_opciones` DISABLE KEYS */;

INSERT INTO `accelog_opciones` (`idopcion`, `nombre`)
VALUES
	('catalog_10xa','accelog_usuarios no agregar'),
	('catalog_10xe','accelog_usuarios no eliminar'),
	('catalog_10xm','accelog_usuarios no modificar'),
	('catalog_110xa','configuracionFactura no agregar'),
	('catalog_110xe','configuracionFactura no eliminar'),
	('catalog_110xm','configuracionFactura no modificar'),
	('catalog_111xa','pvt_clientes no agregar'),
	('catalog_111xe','pvt_clientes no eliminar'),
	('catalog_111xm','pvt_clientes no modificar'),
	('catalog_112xa','pvt_facturacion no agregar'),
	('catalog_112xe','pvt_facturacion no eliminar'),
	('catalog_112xm','pvt_facturacion no modificar'),
	('catalog_114xa','pvt_targetasRegalo no agregar'),
	('catalog_114xe','pvt_targetasRegalo no eliminar'),
	('catalog_114xm','pvt_targetasRegalo no modificar'),
	('catalog_115xa','agendaLog no agregar'),
	('catalog_115xe','agendaLog no eliminar'),
	('catalog_115xm','agendaLog no modificar'),
	('catalog_116xa','agendalog_citas no agregar'),
	('catalog_116xe','agendalog_citas no eliminar'),
	('catalog_116xm','agendalog_citas no modificar'),
	('catalog_117xa','accelog_usuarios no agregar'),
	('catalog_117xe','accelog_usuarios no eliminar'),
	('catalog_117xm','accelog_usuarios no modificar'),
	('catalog_118xa','rest_cxc no agregar'),
	('catalog_118xe','rest_cxc no eliminar'),
	('catalog_11xa','accelog_niveles no agregar'),
	('catalog_11xe','accelog_niveles no eliminar'),
	('catalog_11xm','accelog_niveles no modificar'),
	('catalog_120xa','rest_cxp no agregar'),
	('catalog_120xe','rest_cxp no eliminar'),
	('catalog_12xa','productos no agregar'),
	('catalog_12xe','productos no eliminar'),
	('catalog_12xm','productos no modificar'),
	('catalog_13xa','accelog_niveles no agregar'),
	('catalog_13xe','accelog_niveles no eliminar'),
	('catalog_13xm','accelog_niveles no modificar'),
	('catalog_14xa','marcas no agregar'),
	('catalog_14xe','marcas no eliminar'),
	('catalog_14xm','marcas no modificar'),
	('catalog_15xa','proveedores no agregar'),
	('catalog_15xe','proveedores no eliminar'),
	('catalog_15xm','proveedores no modificar'),
	('catalog_169xa','rest_admin_cxc no agregar'),
	('catalog_169xe','rest_admin_cxc no eliminar'),
	('catalog_16xa','estados no agregar'),
	('catalog_16xe','estados no eliminar'),
	('catalog_16xm','estados no modificar'),
	('catalog_171xa','rest_cxp no agregar'),
	('catalog_171xe','rest_cxp no eliminar'),
	('catalog_17xa','paises no agregar'),
	('catalog_17xe','paises no eliminar'),
	('catalog_17xm','paises no modificar'),
	('catalog_18xa','municipios no agregar'),
	('catalog_18xe','municipios no eliminar'),
	('catalog_18xm','municipios no modificar'),
	('catalog_19xa','clientes no agregar'),
	('catalog_19xe','clientes no eliminar'),
	('catalog_19xm','clientes no modificar'),
	('catalog_1xa','organizaciones no agregar'),
	('catalog_1xe','organizaciones no eliminar'),
	('catalog_1xm','organizaciones no modificar'),
	('catalog_20xa','configuracion_recibos no agregar'),
	('catalog_20xe','configuracion_recibos no eliminar'),
	('catalog_20xm','configuracion_recibos no modificar'),
	('catalog_21xa','productos_maximosminimos no agregar'),
	('catalog_21xe','productos_maximosminimos no eliminar'),
	('catalog_21xm','productos_maximosminimos no modificar'),
	('catalog_22xa','almacenes no agregar'),
	('catalog_22xe','almacenes no eliminar'),
	('catalog_22xm','almacenes no modificar'),
	('catalog_234xa','pvt_config_facturacion no agregar'),
	('catalog_234xe','pvt_config_facturacion no eliminar'),
	('catalog_234xm','pvt_config_facturacion no modificar'),
	('catalog_23xa','ventas_titulo no agregar'),
	('catalog_23xe','ventas_titulo no eliminar'),
	('catalog_23xm','ventas_titulo no modificar'),
	('catalog_242xa','Datos Bascula no agregar'),
	('catalog_242xe','Datos Bascula no eliminar'),
	('catalog_242xm','Datos Bascula no modificar'),
	('catalog_24xa','ventas_detalle no agregar'),
	('catalog_24xe','ventas_detalle no eliminar'),
	('catalog_24xm','ventas_detalle no modificar'),
	('catalog_251xa','SegmentoNegocio no agregar'),
	('catalog_251xe','SegmentoNegocio no eliminar'),
	('catalog_251xm','SegmentoNegocio no modificar'),
	('catalog_25xa','sucursales no agregar'),
	('catalog_25xe','sucursales no eliminar'),
	('catalog_25xm','sucursales no modificar'),
	('catalog_26xa','vista_productos no agregar'),
	('catalog_26xe','vista_productos no eliminar'),
	('catalog_26xm','vista_productos no modificar'),
	('catalog_27xa','vista_empleados no agregar'),
	('catalog_27xe','vista_empleados no eliminar'),
	('catalog_27xm','vista_empleados no modificar'),
	('catalog_280xa','bco_cuentas_bancarias no agregar'),
	('catalog_280xe','bco_cuentas_bancarias no eliminar'),
	('catalog_280xm','bco_cuentas_bancarias no modificar'),
	('catalog_28xa','accelog_niveles no agregar'),
	('catalog_28xe','accelog_niveles no eliminar'),
	('catalog_28xm','accelog_niveles no modificar'),
	('catalog_29xa','accelog_niveles no agregar'),
	('catalog_29xe','accelog_niveles no eliminar'),
	('catalog_29xm','accelog_niveles no modificar'),
	('catalog_2xa','empleados no agregar'),
	('catalog_2xe','empleados no eliminar'),
	('catalog_2xm','empleados no modificar'),
	('catalog_30xa','accelog_niveles no agregar'),
	('catalog_30xe','accelog_niveles no eliminar'),
	('catalog_30xm','accelog_niveles no modificar'),
	('catalog_31xa','accelog_niveles no agregar'),
	('catalog_31xe','accelog_niveles no eliminar'),
	('catalog_31xm','accelog_niveles no modificar'),
	('catalog_32xa','accelog_niveles no agregar'),
	('catalog_32xe','accelog_niveles no eliminar'),
	('catalog_32xm','accelog_niveles no modificar'),
	('catalog_33xa','accelog_niveles no agregar'),
	('catalog_33xe','accelog_niveles no eliminar'),
	('catalog_33xm','accelog_niveles no modificar'),
	('catalog_34xa','accelog_niveles no agregar'),
	('catalog_34xe','accelog_niveles no eliminar'),
	('catalog_34xm','accelog_niveles no modificar'),
	('catalog_35xa','accelog_niveles no agregar'),
	('catalog_35xe','accelog_niveles no eliminar'),
	('catalog_35xm','accelog_niveles no modificar'),
	('catalog_36xa','accelog_niveles no agregar'),
	('catalog_36xe','accelog_niveles no eliminar'),
	('catalog_36xm','accelog_niveles no modificar'),
	('catalog_38xa','inventarios_movimientos no agregar'),
	('catalog_38xe','inventarios_movimientos no eliminar'),
	('catalog_38xm','inventarios_movimientos no modificar'),
	('catalog_39xa','inventarios_saldos no agregar'),
	('catalog_39xe','inventarios_saldos no eliminar'),
	('catalog_39xm','inventarios_saldos no modificar'),
	('catalog_3xa','accelog_categorias no agregar'),
	('catalog_3xe','accelog_categorias no eliminar'),
	('catalog_3xm','accelog_categorias no modificar'),
	('catalog_40xa','inventarios_lotes no agregar'),
	('catalog_40xe','inventarios_lotes no eliminar'),
	('catalog_40xm','inventarios_lotes no modificar'),
	('catalog_41xa','inventarios_tiposmovimiento no agregar'),
	('catalog_41xe','inventarios_tiposmovimiento no eliminar'),
	('catalog_41xm','inventarios_tiposmovimiento no modificar'),
	('catalog_42xa','inventarios_estadosproducto no agregar'),
	('catalog_42xe','inventarios_estadosproducto no eliminar'),
	('catalog_42xm','inventarios_estadosproducto no modificar'),
	('catalog_43xa','inventarios_movimientostitulo no agregar'),
	('catalog_43xe','inventarios_movimientostitulo no eliminar'),
	('catalog_43xm','inventarios_movimientostitulo no modificar'),
	('catalog_44xa','inventarios_movimientosdetalle no agregar'),
	('catalog_44xe','inventarios_movimientosdetalle no eliminar'),
	('catalog_44xm','inventarios_movimientosdetalle no modificar'),
	('catalog_45xa','lonas no agregar'),
	('catalog_45xe','lonas no eliminar'),
	('catalog_45xm','lonas no modificar'),
	('catalog_47xa','administracion_usuarios no agregar'),
	('catalog_47xe','administracion_usuarios no eliminar'),
	('catalog_47xm','administracion_usuarios no modificar'),
	('catalog_49xa','crm_campanias no agregar'),
	('catalog_49xe','crm_campanias no eliminar'),
	('catalog_49xm','crm_campanias no modificar'),
	('catalog_4xa','accelog_menu no agregar'),
	('catalog_4xe','accelog_menu no eliminar'),
	('catalog_4xm','accelog_menu no modificar'),
	('catalog_50xa','crm_metasventas no agregar'),
	('catalog_50xe','crm_metasventas no eliminar'),
	('catalog_50xm','crm_metasventas no modificar'),
	('catalog_51xa','crm_ciclos no agregar'),
	('catalog_51xe','crm_ciclos no eliminar'),
	('catalog_51xm','crm_ciclos no modificar'),
	('catalog_52xa','puestos no agregar'),
	('catalog_52xe','puestos no eliminar'),
	('catalog_52xm','puestos no modificar'),
	('catalog_53xa','crm_actividades no agregar'),
	('catalog_53xe','crm_actividades no eliminar'),
	('catalog_53xm','crm_actividades no modificar'),
	('catalog_54xa','crm_registroactividadescomerciales no agregar'),
	('catalog_54xe','crm_registroactividadescomerciales no eliminar'),
	('catalog_54xm','crm_registroactividadescomerciales no modificar'),
	('catalog_55xa','crm_cuentas no agregar'),
	('catalog_55xe','crm_cuentas no eliminar'),
	('catalog_55xm','crm_cuentas no modificar'),
	('catalog_56xa','crm_procesosventa no agregar'),
	('catalog_56xe','crm_procesosventa no eliminar'),
	('catalog_56xm','crm_procesosventa no modificar'),
	('catalog_57xa','crm_estadosventa no agregar'),
	('catalog_57xe','crm_estadosventa no eliminar'),
	('catalog_57xm','crm_estadosventa no modificar'),
	('catalog_58xa','productos_precios no agregar'),
	('catalog_58xe','productos_precios no eliminar'),
	('catalog_58xm','productos_precios no modificar'),
	('catalog_59xa','compras_titulo no agregar'),
	('catalog_59xe','compras_titulo no eliminar'),
	('catalog_59xm','compras_titulo no modificar'),
	('catalog_5xa','accelog_perfiles no agregar'),
	('catalog_5xe','accelog_perfiles no eliminar'),
	('catalog_5xm','accelog_perfiles no modificar'),
	('catalog_60xa','compras_detalle no agregar'),
	('catalog_60xe','compras_detalle no eliminar'),
	('catalog_60xm','compras_detalle no modificar'),
	('catalog_61xa','crm_tiposcliente no agregar'),
	('catalog_61xe','crm_tiposcliente no eliminar'),
	('catalog_61xm','crm_tiposcliente no modificar'),
	('catalog_62xa','crm_tipoprecio no agregar'),
	('catalog_62xe','crm_tipoprecio no eliminar'),
	('catalog_62xm','crm_tipoprecio no modificar'),
	('catalog_63xa','crm_campanias_detalle no agregar'),
	('catalog_63xe','crm_campanias_detalle no eliminar'),
	('catalog_63xm','crm_campanias_detalle no modificar'),
	('catalog_64xa','admin_cxc no agregar'),
	('catalog_64xe','admin_cxc no eliminar'),
	('catalog_64xm','admin_cxc no modificar'),
	('catalog_65xa','admin_cxp no agregar'),
	('catalog_65xe','admin_cxp no eliminar'),
	('catalog_65xm','admin_cxp no modificar'),
	('catalog_67xa','admin_clasdeudores no agregar'),
	('catalog_67xe','admin_clasdeudores no eliminar'),
	('catalog_67xm','admin_clasdeudores no modificar'),
	('catalog_68xa','admin_acreedores no agregar'),
	('catalog_68xe','admin_acreedores no eliminar'),
	('catalog_68xm','admin_acreedores no modificar'),
	('catalog_69xa','admin_conceptos no agregar'),
	('catalog_69xe','admin_conceptos no eliminar'),
	('catalog_69xm','admin_conceptos no modificar'),
	('catalog_6xa','accelog_perfiles_me no agregar'),
	('catalog_6xe','accelog_perfiles_me no eliminar'),
	('catalog_6xm','accelog_perfiles_me no modificar'),
	('catalog_70xa','admin_cxcpagos no agregar'),
	('catalog_70xe','admin_cxcpagos no eliminar'),
	('catalog_70xm','admin_cxcpagos no modificar'),
	('catalog_71xa','admin_formaspago no agregar'),
	('catalog_71xe','admin_formaspago no eliminar'),
	('catalog_71xm','admin_formaspago no modificar'),
	('catalog_72xa','amin_cuentasbancarias no agregar'),
	('catalog_72xe','amin_cuentasbancarias no eliminar'),
	('catalog_72xm','amin_cuentasbancarias no modificar'),
	('catalog_73xa','admin_bancos no agregar'),
	('catalog_73xe','admin_bancos no eliminar'),
	('catalog_73xm','admin_bancos no modificar'),
	('catalog_74xa','admin_cxppagos no agregar'),
	('catalog_74xe','admin_cxppagos no eliminar'),
	('catalog_74xm','admin_cxppagos no modificar'),
	('catalog_7xa','accelog_opciones no agregar'),
	('catalog_7xe','accelog_opciones no eliminar'),
	('catalog_7xm','accelog_opciones no modificar'),
	('catalog_87xa','mrp_unidad_equivalencias no agregar'),
	('catalog_87xe','mrp_unidad_equivalencias no eliminar'),
	('catalog_87xm','mrp_unidad_equivalencias no modificar'),
	('catalog_89xa','mrp_vista_unidades no agregar'),
	('catalog_89xe','mrp_vista_unidades no eliminar'),
	('catalog_89xm','mrp_vista_unidades no modificar'),
	('catalog_8xa','accelog_perfiles_op no agregar'),
	('catalog_8xe','accelog_perfiles_op no eliminar'),
	('catalog_8xm','accelog_perfiles_op no modificar'),
	('catalog_9xa','accelog_usuarios_per no agregar'),
	('catalog_9xe','accelog_usuarios_per no eliminar'),
	('catalog_9xm','accelog_usuarios_per no modificar'),
	('doclog_18xa','Facturacion (PVT) no agregar'),
	('doclog_18xe','Facturacion (PVT) no eliminar'),
	('doclog_18xm','Facturacion (PVT) no modificar'),
	('doclog_19xa','agendaLog_Citas no agregar'),
	('doclog_19xe','agendaLog_Citas no eliminar'),
	('doclog_19xm','agendaLog_Citas no modificar'),
	('doclog_20xa','Cuentas por Cobrar Rest no agregar'),
	('doclog_20xe','Cuentas por Cobrar Rest no eliminar'),
	('doclog_20xm','Cuentas por Cobrar Rest no modificar'),
	('doclog_21xa','Cuentas por Pagar Rest no agregar'),
	('doclog_21xe','Cuentas por Pagar Rest no eliminar'),
	('doclog_21xm','Cuentas por Pagar Rest no modificar'),
	('NMOTRAS_ORG','Permitir el acceso a otras organizaciones.');

/*!40000 ALTER TABLE `accelog_opciones` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla accelog_perfiles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accelog_perfiles`;

CREATE TABLE `accelog_perfiles` (
  `idperfil` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `visible` tinyint(11) DEFAULT NULL,
  PRIMARY KEY (`idperfil`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

LOCK TABLES `accelog_perfiles` WRITE;
/*!40000 ALTER TABLE `accelog_perfiles` DISABLE KEYS */;

INSERT INTO `accelog_perfiles` (`idperfil`, `nombre`, `visible`)
VALUES
	(1,'NMPerfil',0),
	(2,'Administrador',-1),
	(3,'Cajero',-1),
	(32,'Deudores',-1);

/*!40000 ALTER TABLE `accelog_perfiles` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla accelog_perfiles_me
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accelog_perfiles_me`;

CREATE TABLE `accelog_perfiles_me` (
  `idperfil` int(11) NOT NULL,
  `idmenu` int(11) NOT NULL,
  PRIMARY KEY (`idperfil`,`idmenu`),
  KEY `perfiles_menu` (`idmenu`) USING BTREE,
  KEY `perfiles_perfiles_me` (`idperfil`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `accelog_perfiles_me` WRITE;
/*!40000 ALTER TABLE `accelog_perfiles_me` DISABLE KEYS */;

INSERT INTO `accelog_perfiles_me` (`idperfil`, `idmenu`)
VALUES
	(1,0),
	(1,1),
	(1,2),
	(1,3),
	(1,4),
	(1,5),
	(1,6),
	(1,7),
	(1,8),
	(1,9),
	(1,11),
	(1,12),
	(1,13),
	(1,14),
	(1,40),
	(1,1126),
	(2,2155),
	(2,2188),
	(3,2188),
	(2,2194),
	(2,2197),
	(2,2200),
	(2,2201),
	(2,2203),
	(2,2206),
	(5,2206),
	(2,2207),
	(3,2207),
	(2,2214),
	(3,2214),
	(2,2215),
	(3,2215),
	(2,2216),
	(3,2216),
	(2,2229),
	(3,2229),
	(2,2238),
	(3,2238),
	(2,2240),
	(2,2242),
	(3,2242),
	(2,2247),
	(5,2247),
	(2,2269),
	(2,2278),
	(3,2278);

/*!40000 ALTER TABLE `accelog_perfiles_me` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla accelog_perfiles_op
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accelog_perfiles_op`;

CREATE TABLE `accelog_perfiles_op` (
  `idperfil` int(11) NOT NULL,
  `idopcion` varchar(45) NOT NULL,
  PRIMARY KEY (`idperfil`,`idopcion`),
  KEY `perfiles_opciones` (`idopcion`) USING BTREE,
  KEY `opciones_perfiles_op` (`idperfil`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `accelog_perfiles_op` WRITE;
/*!40000 ALTER TABLE `accelog_perfiles_op` DISABLE KEYS */;

INSERT INTO `accelog_perfiles_op` (`idperfil`, `idopcion`)
VALUES
	(1,'NMOTRAS_ORG'),
	(2,'catalog_118xa'),
	(2,'catalog_118xe'),
	(2,'catalog_120xa'),
	(2,'catalog_120xe'),
	(2,'catalog_135xe'),
	(2,'catalog_169xa'),
	(2,'catalog_169xe'),
	(2,'catalog_171xa'),
	(2,'catalog_171xe'),
	(2,'catalog_1xa'),
	(2,'catalog_1xe'),
	(2,'catalog_221xa'),
	(2,'catalog_221xe'),
	(2,'catalog_234xa'),
	(2,'catalog_234xe'),
	(2,'catalog_242xa'),
	(2,'catalog_242xe'),
	(2,'catalog_251xe'),
	(2,'catalog_280xe'),
	(2,'catalog_285xe'),
	(2,'catalog_309xe'),
	(2,'catalog_3xa'),
	(2,'catalog_64xa'),
	(2,'catalog_64xe'),
	(2,'catalog_65xa'),
	(2,'catalog_65xe'),
	(2,'doclog_20xa'),
	(2,'doclog_20xe'),
	(2,'doclog_21xe'),
	(3,'catalog_118xa'),
	(3,'catalog_118xe'),
	(3,'catalog_120xa'),
	(3,'catalog_120xe'),
	(3,'catalog_135xe'),
	(3,'catalog_169xa'),
	(3,'catalog_169xe'),
	(3,'catalog_171xa'),
	(3,'catalog_171xe'),
	(3,'catalog_221xa'),
	(3,'catalog_221xe'),
	(3,'catalog_234xa'),
	(3,'catalog_234xe'),
	(3,'catalog_242xa'),
	(3,'catalog_242xe'),
	(3,'catalog_251xe'),
	(3,'catalog_280xe'),
	(3,'catalog_285xe'),
	(3,'catalog_309xe'),
	(3,'catalog_64xa'),
	(3,'catalog_64xe'),
	(3,'catalog_65xa'),
	(3,'catalog_65xe');

/*!40000 ALTER TABLE `accelog_perfiles_op` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla accelog_politicas_acceso
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accelog_politicas_acceso`;

CREATE TABLE `accelog_politicas_acceso` (
  `idpacceso` int(11) NOT NULL AUTO_INCREMENT,
  `nombrepolitica` varchar(100) DEFAULT NULL,
  `numerodias` int(11) DEFAULT NULL,
  `ultimocambio` datetime DEFAULT NULL,
  `restringehorario` tinyint(1) DEFAULT NULL,
  `hinicial` time DEFAULT NULL,
  `hfinal` time DEFAULT NULL,
  PRIMARY KEY (`idpacceso`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `accelog_politicas_acceso` WRITE;
/*!40000 ALTER TABLE `accelog_politicas_acceso` DISABLE KEYS */;

INSERT INTO `accelog_politicas_acceso` (`idpacceso`, `nombrepolitica`, `numerodias`, `ultimocambio`, `restringehorario`, `hinicial`, `hfinal`)
VALUES
	(1,NULL,NULL,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `accelog_politicas_acceso` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla accelog_usuarios
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accelog_usuarios`;

CREATE TABLE `accelog_usuarios` (
  `idempleado` int(11) DEFAULT NULL,
  `usuario` varchar(100) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `css` varchar(45) NOT NULL DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `accelog_usuarios` WRITE;
/*!40000 ALTER TABLE `accelog_usuarios` DISABLE KEYS */;

INSERT INTO `accelog_usuarios` (`idempleado`, `usuario`, `clave`, `css`)
VALUES
	(1,'yoda','0','default');

/*!40000 ALTER TABLE `accelog_usuarios` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla accelog_usuarios_per
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accelog_usuarios_per`;

CREATE TABLE `accelog_usuarios_per` (
  `idperfil` int(11) NOT NULL DEFAULT '0',
  `idempleado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idperfil`,`idempleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `accelog_usuarios_per` WRITE;
/*!40000 ALTER TABLE `accelog_usuarios_per` DISABLE KEYS */;

INSERT INTO `accelog_usuarios_per` (`idperfil`, `idempleado`)
VALUES
	(1,1),
	(2,2);

/*!40000 ALTER TABLE `accelog_usuarios_per` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla administracion_usuarios
# ------------------------------------------------------------

DROP TABLE IF EXISTS `administracion_usuarios`;

CREATE TABLE `administracion_usuarios` (
  `idadmin` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `nombreusuario` varchar(100) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `confirmaclave` varchar(100) DEFAULT NULL,
  `correoelectronico` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `idperfil` int(11) DEFAULT NULL,
  `idempleado` int(11) DEFAULT NULL,
  `idorganizacion` int(11) DEFAULT NULL,
  `idpuesto` int(11) DEFAULT NULL,
  `tipo` varchar(2) DEFAULT NULL,
  `idSuc` int(11) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  PRIMARY KEY (`idadmin`),
  KEY `idSuc` (`idSuc`),
  CONSTRAINT `administracion_usuarios_ibfk_1` FOREIGN KEY (`idSuc`) REFERENCES `mrp_sucursal` (`idSuc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla agenda
# ------------------------------------------------------------

DROP TABLE IF EXISTS `agenda`;

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `inicio` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `todoeldia` tinyint(4) NOT NULL,
  `descripcion` longtext NOT NULL,
  `color` varchar(16) NOT NULL,
  `idGrupo` int(11) DEFAULT NULL,
  `idCliente` int(11) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idGrupo` (`idGrupo`) USING BTREE,
  KEY `idCliente` (`idCliente`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla agenda_expediente
# ------------------------------------------------------------

DROP TABLE IF EXISTS `agenda_expediente`;

CREATE TABLE `agenda_expediente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAgenda` int(11) NOT NULL,
  `idExpediente` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idAgenda` (`idAgenda`) USING BTREE,
  KEY `idExpediente` (`idExpediente`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla agenda_grupo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `agenda_grupo`;

CREATE TABLE `agenda_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT '1',
  `idCliente` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idCliente` (`idCliente`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla agendaLog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `agendaLog`;

CREATE TABLE `agendaLog` (
  `idcontacto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(16) NOT NULL,
  `campo1` double DEFAULT NULL,
  `campo2` double DEFAULT NULL,
  `camporesultado` double DEFAULT NULL,
  `idestado` int(11) NOT NULL,
  `idmunicipio` int(11) NOT NULL,
  PRIMARY KEY (`idcontacto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla agendalog_citas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `agendalog_citas`;

CREATE TABLE `agendalog_citas` (
  `idlinea` int(11) NOT NULL AUTO_INCREMENT,
  `idcontacto` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `asunto` varchar(200) NOT NULL,
  `campoX` double DEFAULT NULL,
  PRIMARY KEY (`idlinea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla almacen
# ------------------------------------------------------------

DROP TABLE IF EXISTS `almacen`;

CREATE TABLE `almacen` (
  `idAlmacen` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `idEstado` int(11) NOT NULL,
  `idmunicipio` int(11) NOT NULL,
  `cp` varchar(50) DEFAULT NULL,
  `tel_contacto` varchar(15) DEFAULT NULL,
  `contacto` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`idAlmacen`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `almacen` WRITE;
/*!40000 ALTER TABLE `almacen` DISABLE KEYS */;

INSERT INTO `almacen` (`idAlmacen`, `nombre`, `direccion`, `idEstado`, `idmunicipio`, `cp`, `tel_contacto`, `contacto`)
VALUES
	(1,'Almacen1','',1,1,'','','');

/*!40000 ALTER TABLE `almacen` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla almacen_sucursal
# ------------------------------------------------------------

DROP TABLE IF EXISTS `almacen_sucursal`;

CREATE TABLE `almacen_sucursal` (
  `idAlmacenSucursal` int(11) NOT NULL AUTO_INCREMENT,
  `idAlmacen` int(45) NOT NULL,
  `idSucursal` int(45) NOT NULL,
  PRIMARY KEY (`idAlmacenSucursal`),
  KEY `almacen_sucursal_ibfk_1` (`idAlmacen`),
  KEY `almacen_sucursal_ibfk_2` (`idSucursal`),
  CONSTRAINT `almacen_sucursal_ibfk_1` FOREIGN KEY (`idAlmacen`) REFERENCES `almacen` (`idAlmacen`),
  CONSTRAINT `almacen_sucursal_ibfk_2` FOREIGN KEY (`idSucursal`) REFERENCES `mrp_sucursal` (`idSuc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla almacenes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `almacenes`;

CREATE TABLE `almacenes` (
  `idalmacen` int(11) NOT NULL AUTO_INCREMENT,
  `almacen` varchar(100) DEFAULT NULL,
  `domicilio` varchar(255) DEFAULT NULL,
  `idestado` int(11) DEFAULT NULL,
  `idmunicipio` int(11) DEFAULT NULL,
  `telefonos` varchar(100) DEFAULT NULL,
  `idempleado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idalmacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla api_token
# ------------------------------------------------------------

DROP TABLE IF EXISTS `api_token`;

CREATE TABLE `api_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `dispositivo` text COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `inicio` datetime DEFAULT NULL,
  `fin` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla api_token_inovekia
# ------------------------------------------------------------

DROP TABLE IF EXISTS `api_token_inovekia`;

CREATE TABLE `api_token_inovekia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `dispositivo` text NOT NULL,
  `token` varchar(100) NOT NULL,
  `inicio` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Volcado de tabla app_almacen_tipo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_almacen_tipo`;

CREATE TABLE `app_almacen_tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `app_almacen_tipo` WRITE;
/*!40000 ALTER TABLE `app_almacen_tipo` DISABLE KEYS */;

INSERT INTO `app_almacen_tipo` (`id`, `nombre`)
VALUES
	(1,'Almacén'),
	(2,'Bodega'),
	(3,'Pasillo'),
	(4,'Rack'),
	(5,'Mostrador');

/*!40000 ALTER TABLE `app_almacen_tipo` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla app_almacenes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_almacenes`;

CREATE TABLE `app_almacenes` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `app_almacenes` WRITE;
/*!40000 ALTER TABLE `app_almacenes` DISABLE KEYS */;

INSERT INTO `app_almacenes` (`id`, `codigo_sistema`, `codigo_manual`, `nombre`, `id_padre`, `id_sucursal`, `id_estado`, `id_municipio`, `direccion`, `id_almacen_tipo`, `id_empleado_encargado`, `telefono`, `ext`, `es_consignacion`, `id_clasificador`, `activo`)
VALUES
	(1,'1','al-1','Almacen General',0,16,14,539,'',1,5,'','12',0,6,1);

/*!40000 ALTER TABLE `app_almacenes` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla app_area_empleado
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_area_empleado`;

CREATE TABLE `app_area_empleado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_campos_foodware
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_campos_foodware`;

CREATE TABLE `app_campos_foodware` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `vendible` int(11) DEFAULT '1',
  `h_ini` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `h_fin` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dias` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ganancia` decimal(10,4) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_producto_2` (`id_producto`),
  UNIQUE KEY `id_producto_3` (`id_producto`),
  UNIQUE KEY `id_producto_4` (`id_producto`),
  KEY `id_producto` (`id_producto`) USING BTREE,
  CONSTRAINT `app_campos_foodware_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `app_productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_caracteristicas_hija
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_caracteristicas_hija`;

CREATE TABLE `app_caracteristicas_hija` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_caracteristica_padre` int(11) NOT NULL,
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_caracteristicas_padre
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_caracteristicas_padre`;

CREATE TABLE `app_caracteristicas_padre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_clasificadores
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_clasificadores`;

CREATE TABLE `app_clasificadores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `padre` int(5) NOT NULL DEFAULT '0',
  `tipo` int(1) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_config`;

CREATE TABLE `app_config` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `app_config` WRITE;
/*!40000 ALTER TABLE `app_config` DISABLE KEYS */;

INSERT INTO `app_config` (`key`, `value`)
VALUES
	('address',''),
	('company',''),
	('currency_symbol','$'),
	('default_tax_1_name','IVA'),
	('default_tax_1_rate','16'),
	('default_tax_2_name',''),
	('default_tax_2_rate',''),
	('default_tax_rate','16'),
	('email',''),
	('fax',''),
	('language','spanish'),
	('phone',''),
	('print_after_sale','print_after_sale'),
	('return_policy','Politica de devoluciones'),
	('timezone','America/Cancun'),
	('website','');

/*!40000 ALTER TABLE `app_config` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla app_configuracion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_configuracion`;

CREATE TABLE `app_configuracion` (
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
  `not_ventas` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `not_compras` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `factura_cancelacion` int(2) NOT NULL DEFAULT '0',
  `factura_emision` int(2) NOT NULL DEFAULT '0',
  `not_cortes` varchar(500) COLLATE utf8_unicode_ci DEFAULT '',
  `mod_costo_compras` tinyint(1) NOT NULL DEFAULT '1',
  `conectar_acontia` tinyint(1) NOT NULL DEFAULT '0',
  `conectar_bancos` tinyint(1) NOT NULL DEFAULT '0',
  `pol_autorizacion` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_consignacion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_consignacion`;

CREATE TABLE `app_consignacion` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_consignacion_datos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_consignacion_datos`;

CREATE TABLE `app_consignacion_datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_consignacion` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_recepcion` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `caracteristica` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_costeo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_costeo`;

CREATE TABLE `app_costeo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(35) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `app_costeo` WRITE;
/*!40000 ALTER TABLE `app_costeo` DISABLE KEYS */;

INSERT INTO `app_costeo` (`id`, `nombre`)
VALUES
	(1,'Costo Promedio'),
	(2,'Costo Promedio por Almacen'),
	(3,'Último Costo'),
	(4,'UEPS'),
	(5,'PEPS'),
	(6,'Costo Especifico'),
	(7,'Costo Estandar');

/*!40000 ALTER TABLE `app_costeo` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla app_costos_proveedor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_costos_proveedor`;

CREATE TABLE `app_costos_proveedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_moneda` int(11) DEFAULT NULL,
  `costo` double DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_departamento
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_departamento`;

CREATE TABLE `app_departamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_devolucioncli
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_devolucioncli`;

CREATE TABLE `app_devolucioncli` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_devolucioncli_datos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_devolucioncli_datos`;

CREATE TABLE `app_devolucioncli_datos` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_devolucionpro
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_devolucionpro`;

CREATE TABLE `app_devolucionpro` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_devolucionpro_datos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_devolucionpro_datos`;

CREATE TABLE `app_devolucionpro_datos` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_dfl_nombres
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_dfl_nombres`;

CREATE TABLE `app_dfl_nombres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabla` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `app_dfl_nombres` WRITE;
/*!40000 ALTER TABLE `app_dfl_nombres` DISABLE KEYS */;

INSERT INTO `app_dfl_nombres` (`id`, `tabla`, `nombre`)
VALUES
	(1,'app_departamento','Departamentos'),
	(2,'app_familia','Familias'),
	(3,'app_linea','Líneas');

/*!40000 ALTER TABLE `app_dfl_nombres` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla app_ejercicios
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_ejercicios`;

CREATE TABLE `app_ejercicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` int(4) NOT NULL,
  `cerrado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_envios
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_envios`;

CREATE TABLE `app_envios` (
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
  `numpago` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_envios_datos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_envios_datos`;

CREATE TABLE `app_envios_datos` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_familia
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_familia`;

CREATE TABLE `app_familia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_departamento` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_impuesto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_impuesto`;

CREATE TABLE `app_impuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `valor` double NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `clave` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `app_impuesto` WRITE;
/*!40000 ALTER TABLE `app_impuesto` DISABLE KEYS */;

INSERT INTO `app_impuesto` (`id`, `nombre`, `valor`, `activo`, `clave`)
VALUES
	(1,'IVA 16%',16,1,'IVA'),
	(2,'IVA 0%',0,1,'IVA'),
	(3,'IVA EXENTO',0,1,'IVA'),
	(4,'IVA RETENIDO 10.667%',10.667,1,'IVAR'),
	(5,'IVA RETENIDO 4%',4,1,'IVAR'),
	(6,'ISR RETENIDO 10%',10,1,'ISR'),
	(7,'ISH 2%',2,1,'ISH'),
	(8,'IEPS 8%',8,0,'IEPS'),
	(9,'IEPS 160%',160,1,'IEPS'),
	(10,'IEPS 30%',30,1,'IEPS');

/*!40000 ALTER TABLE `app_impuesto` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla app_inventario
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_inventario`;

CREATE TABLE `app_inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `cantidad` double NOT NULL,
  `id_almacen` int(11) NOT NULL,
  `apartados` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_inventario_movimientos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_inventario_movimientos`;

CREATE TABLE `app_inventario_movimientos` (
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
  `id_poliza_mov` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_producto_idx` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_inventario_movimientos_respaldo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_inventario_movimientos_respaldo`;

CREATE TABLE `app_inventario_movimientos_respaldo` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_inventario_traslados
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_inventario_traslados`;

CREATE TABLE `app_inventario_traslados` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clave` int(11) DEFAULT NULL,
  `id_almacen_origen` int(4) DEFAULT NULL,
  `id_almacen_destino` int(4) DEFAULT NULL,
  `id_solicitante` int(5) NOT NULL DEFAULT '0',
  `fecha` date DEFAULT NULL,
  `fecha_recepcion` date DEFAULT NULL,
  `referencia` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cerrado` tinyint(1) DEFAULT NULL,
  `comentario_rec` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N/A',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_inventario_traslados_movimientos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_inventario_traslados_movimientos`;

CREATE TABLE `app_inventario_traslados_movimientos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_traslado` int(11) NOT NULL,
  `id_movimiento` int(11) NOT NULL,
  `id_movimiento_rec` int(11) DEFAULT NULL,
  `recibidos` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_linea
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_linea`;

CREATE TABLE `app_linea` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_familia` int(3) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_lista_precio
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_lista_precio`;

CREATE TABLE `app_lista_precio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `porcentaje` double NOT NULL,
  `descuento` tinyint(1) NOT NULL DEFAULT '1',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_lista_precio_prods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_lista_precio_prods`;

CREATE TABLE `app_lista_precio_prods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lista` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_merma
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_merma`;

CREATE TABLE `app_merma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `productos` double DEFAULT NULL,
  `importe` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_merma_datos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_merma_datos`;

CREATE TABLE `app_merma_datos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_merma` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `precio` double DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `almacen` int(11) DEFAULT NULL,
  `observaciones` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `caracteristicas` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_ocompra
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_ocompra`;

CREATE TABLE `app_ocompra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_usrcompra` int(11) DEFAULT NULL,
  `observaciones` mediumtext CHARACTER SET utf8,
  `fecha` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `id_requisicion` int(11) NOT NULL,
  `subtotal` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_ocompra_datos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_ocompra_datos`;

CREATE TABLE `app_ocompra_datos` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_oventa
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_oventa`;

CREATE TABLE `app_oventa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `id_usrcompra` int(11) DEFAULT NULL,
  `observaciones` mediumtext CHARACTER SET utf8,
  `fecha` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `id_requisicion` int(11) NOT NULL,
  `subtotal` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_oventa_datos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_oventa_datos`;

CREATE TABLE `app_oventa_datos` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_pagos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_pagos`;

CREATE TABLE `app_pagos` (
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
  `origen` int(1) DEFAULT '0',
  `ref_bancos` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_pagos_relacion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_pagos_relacion`;

CREATE TABLE `app_pagos_relacion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pago` int(11) DEFAULT NULL,
  `id_tipo` int(11) DEFAULT NULL,
  `id_documento` int(11) DEFAULT NULL,
  `cargo` double NOT NULL DEFAULT '0',
  `abono` double NOT NULL DEFAULT '0',
  `id_poliza_mov` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_pendienteFactura
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_pendienteFactura`;

CREATE TABLE `app_pendienteFactura` (
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
  `origen` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Volcado de tabla app_periodos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_periodos`;

CREATE TABLE `app_periodos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ejercicio` int(2) NOT NULL,
  `num_mes` int(2) NOT NULL,
  `cerrado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_pos_corte_caja
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_pos_corte_caja`;

CREATE TABLE `app_pos_corte_caja` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_pos_inicio_caja
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_pos_inicio_caja`;

CREATE TABLE `app_pos_inicio_caja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `idCortecaja` int(11) DEFAULT NULL,
  `idSucursal` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_pos_retiro_caja
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_pos_retiro_caja`;

CREATE TABLE `app_pos_retiro_caja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cantidad` double DEFAULT NULL,
  `concepto` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idempleado` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `idcorte` int(11) DEFAULT NULL,
  `idSucursal` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_pos_venta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_pos_venta`;

CREATE TABLE `app_pos_venta` (
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
  `observacion` mediumtext CHARACTER SET utf8,
  `envio` int(11) DEFAULT NULL,
  `impuestos` varchar(10000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `moneda` int(11) DEFAULT NULL,
  `tipo_cambio` double DEFAULT NULL,
  `descuentoGeneral` double DEFAULT NULL,
  `token_venta` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idVenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_pos_venta_pagos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_pos_venta_pagos`;

CREATE TABLE `app_pos_venta_pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idVenta` int(11) DEFAULT NULL,
  `idFormapago` int(11) DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `referencia` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tarjeta` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_pos_venta_producto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_pos_venta_producto`;

CREATE TABLE `app_pos_venta_producto` (
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
  `caracteristicas` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  `formulaIeps` int(11) DEFAULT NULL,
  PRIMARY KEY (`idventa_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_pos_venta_producto_impuesto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_pos_venta_producto_impuesto`;

CREATE TABLE `app_pos_venta_producto_impuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idVentaproducto` int(11) DEFAULT NULL,
  `idImpuesto` int(11) DEFAULT NULL,
  `porcentaje` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `totalImpuesto` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_pos_venta_suspendida
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_pos_venta_suspendida`;

CREATE TABLE `app_pos_venta_suspendida` (
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



# Volcado de tabla app_producto_caracteristicas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_producto_caracteristicas`;

CREATE TABLE `app_producto_caracteristicas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_caracteristica_padre` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_producto_impuesto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_producto_impuesto`;

CREATE TABLE `app_producto_impuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_impuesto` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `formula` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_producto_lotes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_producto_lotes`;

CREATE TABLE `app_producto_lotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_lote` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_fabricacion` datetime DEFAULT NULL,
  `fecha_caducidad` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_producto_material
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_producto_material`;

CREATE TABLE `app_producto_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,4) DEFAULT NULL,
  `id_unidad` int(11) NOT NULL,
  `id_material` int(11) NOT NULL,
  `opcionales` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_producto_material_ibfk_1` (`id_producto`),
  KEY `app_producto_material_ibfk_2` (`id_unidad`),
  KEY `app_producto_material_ibfk_3` (`id_material`),
  CONSTRAINT `app_producto_material_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `app_productos` (`id`),
  CONSTRAINT `app_producto_material_ibfk_2` FOREIGN KEY (`id_unidad`) REFERENCES `app_unidades_medida` (`id`),
  CONSTRAINT `app_producto_material_ibfk_3` FOREIGN KEY (`id_material`) REFERENCES `app_productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_producto_pedimentos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_producto_pedimentos`;

CREATE TABLE `app_producto_pedimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_pedimento` int(11) DEFAULT NULL,
  `aduana` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_aduana` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_cambio` double DEFAULT NULL,
  `fecha_pedimento` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_producto_proveedor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_producto_proveedor`;

CREATE TABLE `app_producto_proveedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_unidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_producto_serie
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_producto_serie`;

CREATE TABLE `app_producto_serie` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_producto_serie_rastro
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_producto_serie_rastro`;

CREATE TABLE `app_producto_serie_rastro` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_serie` int(11) DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `fecha_reg` datetime DEFAULT NULL,
  `id_mov` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_productos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_productos`;

CREATE TABLE `app_productos` (
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
  `idempledo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_recepcion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_recepcion`;

CREATE TABLE `app_recepcion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oc` int(11) DEFAULT NULL,
  `id_encargado` int(11) DEFAULT NULL,
  `observaciones` mediumtext CHARACTER SET utf8,
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
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_recepcion_datos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_recepcion_datos`;

CREATE TABLE `app_recepcion_datos` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_recepcion_xml
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_recepcion_xml`;

CREATE TABLE `app_recepcion_xml` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_oc` int(11) DEFAULT NULL,
  `fecha_factura` datetime DEFAULT NULL,
  `imp_factura` double DEFAULT NULL,
  `xmlfile` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `concepto` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_subida` datetime DEFAULT NULL,
  `id_poliza_mov` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_requisiciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_requisiciones`;

CREATE TABLE `app_requisiciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicito` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `id_tipogasto` int(11) DEFAULT NULL,
  `id_almacen` int(11) DEFAULT NULL,
  `id_moneda` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `urgente` tinyint(1) DEFAULT NULL,
  `inventariable` int(11) DEFAULT NULL,
  `observaciones` mediumtext CHARACTER SET utf8,
  `fecha` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `tipo_cambio` double DEFAULT '0',
  `pr` int(11) DEFAULT '0',
  `subtotal` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_requisiciones_datos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_requisiciones_datos`;

CREATE TABLE `app_requisiciones_datos` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_requisiciones_datos_venta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_requisiciones_datos_venta`;

CREATE TABLE `app_requisiciones_datos_venta` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_requisiciones_venta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_requisiciones_venta`;

CREATE TABLE `app_requisiciones_venta` (
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
  `observaciones` mediumtext CHARACTER SET utf8,
  `fecha` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `tipo_cambio` double DEFAULT '0',
  `pr` int(11) DEFAULT '0',
  `subtotal` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_respuestaFacturacion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_respuestaFacturacion`;

CREATE TABLE `app_respuestaFacturacion` (
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
  `origen` int(11) DEFAULT '1',
  `proviene` int(11) DEFAULT '0',
  `fecha_cancelacion` datetime DEFAULT NULL,
  `cancelre` int(11) DEFAULT '0',
  `id_poliza_mov` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla app_tarjetas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_tarjetas`;

CREATE TABLE `app_tarjetas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tarjeta` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

LOCK TABLES `app_tarjetas` WRITE;
/*!40000 ALTER TABLE `app_tarjetas` DISABLE KEYS */;

INSERT INTO `app_tarjetas` (`id`, `tarjeta`)
VALUES
	(1,'Visa'),
	(2,'Master Card'),
	(3,'American Express');

/*!40000 ALTER TABLE `app_tarjetas` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla app_tipo_credito
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_tipo_credito`;

CREATE TABLE `app_tipo_credito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_tipo_producto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_tipo_producto`;

CREATE TABLE `app_tipo_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_tpl_datos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_tpl_datos`;

CREATE TABLE `app_tpl_datos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

LOCK TABLES `app_tpl_datos` WRITE;
/*!40000 ALTER TABLE `app_tpl_datos` DISABLE KEYS */;

INSERT INTO `app_tpl_datos` (`id`, `nombre`)
VALUES
	(1,'Total'),
	(2,'Sub Total'),
	(3,'Impuesto'),
	(4,'Cliente'),
	(5,'Proveedor');

/*!40000 ALTER TABLE `app_tpl_datos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla app_tpl_polizas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_tpl_polizas`;

CREATE TABLE `app_tpl_polizas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_documento` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `id_tipo_poliza` int(11) NOT NULL,
  `id_gasto` int(11) NOT NULL,
  `nombre_poliza` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `automatica` tinyint(1) NOT NULL DEFAULT '1',
  `poliza_por_mov` tinyint(1) NOT NULL DEFAULT '1',
  `dias` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `app_tpl_polizas` WRITE;
/*!40000 ALTER TABLE `app_tpl_polizas` DISABLE KEYS */;

INSERT INTO `app_tpl_polizas` (`id`, `nombre_documento`, `id_tipo_poliza`, `id_gasto`, `nombre_poliza`, `automatica`, `poliza_por_mov`, `dias`)
VALUES
	(1,'Ventas',1,1,'Poliza de Ventas Appministra',0,1,1),
	(2,'Compras',2,0,'Poliza de Compras',0,1,1),
	(3,'Cuentas Por Pagar',3,0,'Poliza de CXP',0,1,1),
	(4,'Cuentas Por Cobrar',3,0,'Poliza de CXC',0,1,1),
	(5,'Ingreso de Mercancia',3,1,'Poliza de Ingreso',0,1,1),
	(6,'Salida de Mercancia',1,3,'Poliza de Salida',0,1,1),
	(7,'Traspaso',3,0,'Poliza de Traspaso',0,1,1),
	(8,'Cancelacion',3,0,'Poliza de Cancelacion X',0,1,1),
	(9,'Devolucion',3,3,'Poliza de Devolucion G',0,1,1),
	(10,'Ventas POS',1,0,'Poliza de VENTAS POS',0,1,1);

/*!40000 ALTER TABLE `app_tpl_polizas` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla app_tpl_polizas_mov
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_tpl_polizas_mov`;

CREATE TABLE `app_tpl_polizas_mov` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_tpl_poliza` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  `tipo_movto` int(11) NOT NULL,
  `id_dato` int(11) NOT NULL,
  `nombre_impuesto` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla app_unidades_medida
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_unidades_medida`;

CREATE TABLE `app_unidades_medida` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `factor` float NOT NULL,
  `unidad_base` int(4) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla bco_clasificador
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bco_clasificador`;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `bco_clasificador` WRITE;
/*!40000 ALTER TABLE `bco_clasificador` DISABLE KEYS */;

INSERT INTO `bco_clasificador` (`id`, `codigo`, `nombreclasificador`, `coin_id`, `idtipo`, `account_id`, `idplantilla`, `idNivel`, `cuentapadre`, `activo`)
VALUES
	(1,'','INGRESOS',1,1,-1,0,2,1,-1),
	(2,'','Traspaso',1,1,-1,0,1,1,-1),
	(3,'','EGRESOS',1,2,-1,0,2,-1,-1),
	(4,'','Traspaso',1,2,-1,0,1,3,-1),
	(5,NULL,'Devolucion de Cheque',1,1,-1,0,1,1,-1),
	(6,'','Ingresos',1,1,-1,0,1,1,-1),
	(7,'','Egresos',1,2,-1,0,1,3,-1);

/*!40000 ALTER TABLE `bco_clasificador` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla bco_cuentas_bancarias
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bco_cuentas_bancarias`;

CREATE TABLE `bco_cuentas_bancarias` (
  `idbancaria` int(11) NOT NULL AUTO_INCREMENT,
  `cuenta` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `idbanco` int(11) NOT NULL,
  `idtipoc` int(11) DEFAULT NULL,
  `coin_id` int(11) NOT NULL,
  `titular` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_id` int(11) NOT NULL,
  `fechainicial` datetime NOT NULL,
  `saldoinicial` double(100,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  `cancelada` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idbancaria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla bco_nivelClasificador
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bco_nivelClasificador`;

CREATE TABLE `bco_nivelClasificador` (
  `idNivel` int(11) NOT NULL AUTO_INCREMENT,
  `nivel` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`idNivel`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `bco_nivelClasificador` WRITE;
/*!40000 ALTER TABLE `bco_nivelClasificador` DISABLE KEYS */;

INSERT INTO `bco_nivelClasificador` (`idNivel`, `nivel`)
VALUES
	(1,'Subcategoria'),
	(2,'Categoria');

/*!40000 ALTER TABLE `bco_nivelClasificador` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla bco_saldo_bancario
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bco_saldo_bancario`;

CREATE TABLE `bco_saldo_bancario` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `periodo` int(11) DEFAULT NULL,
  `idejercicio` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `saldoinicial` double(100,2) NOT NULL,
  `abonos` double(100,2) DEFAULT NULL COMMENT 'depositos',
  `cargos` double(100,2) DEFAULT NULL COMMENT 'retiros',
  `saldofinal` double(100,2) DEFAULT NULL,
  `idbancaria` int(11) NOT NULL,
  `folio` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `concepto` varchar(50) DEFAULT NULL,
  `conciliado` int(11) NOT NULL DEFAULT '0' COMMENT '1 si 0 no',
  `idMovimientoPoliza` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Mov d poliza asociado',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla bco_saldos_conciliacion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bco_saldos_conciliacion`;

CREATE TABLE `bco_saldos_conciliacion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `periodo` int(11) DEFAULT NULL,
  `saldo_inicial` double(100,2) NOT NULL DEFAULT '0.00',
  `saldo_final` double(100,2) NOT NULL DEFAULT '0.00',
  `idbancaria` int(11) DEFAULT NULL,
  `ejercicio` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla bco_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bco_status`;

CREATE TABLE `bco_status` (
  `idstatus` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `bco_status` WRITE;
/*!40000 ALTER TABLE `bco_status` DISABLE KEYS */;

INSERT INTO `bco_status` (`idstatus`, `status`)
VALUES
	(1,'Activo'),
	(2,'Inactivo');

/*!40000 ALTER TABLE `bco_status` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla bco_tipo_cuenta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bco_tipo_cuenta`;

CREATE TABLE `bco_tipo_cuenta` (
  `idtipoc` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`idtipoc`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `bco_tipo_cuenta` WRITE;
/*!40000 ALTER TABLE `bco_tipo_cuenta` DISABLE KEYS */;

INSERT INTO `bco_tipo_cuenta` (`idtipoc`, `tipo`)
VALUES
	(1,'Linea Credito'),
	(2,'Ahorro/Inversion'),
	(3,'Cheques');

/*!40000 ALTER TABLE `bco_tipo_cuenta` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla bco_tipoClasificador
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bco_tipoClasificador`;

CREATE TABLE `bco_tipoClasificador` (
  `idtipo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`idtipo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `bco_tipoClasificador` WRITE;
/*!40000 ALTER TABLE `bco_tipoClasificador` DISABLE KEYS */;

INSERT INTO `bco_tipoClasificador` (`idtipo`, `tipo`)
VALUES
	(1,'Ingresos'),
	(2,'Egresos');

/*!40000 ALTER TABLE `bco_tipoClasificador` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla catalog_campos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `catalog_campos`;

CREATE TABLE `catalog_campos` (
  `idcampo` int(11) NOT NULL AUTO_INCREMENT,
  `idestructura` int(11) DEFAULT NULL,
  `nombrecampo` varchar(50) DEFAULT NULL,
  `nombrecampousuario` varchar(80) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `longitud` int(11) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `valor` varchar(45) DEFAULT NULL,
  `formula` varchar(10000) DEFAULT NULL,
  `requerido` tinyint(1) DEFAULT NULL,
  `formato` varchar(45) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `llaveprimaria` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`idcampo`),
  KEY `eted_estructuraid` (`idestructura`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2564 DEFAULT CHARSET=utf8;

LOCK TABLES `catalog_campos` WRITE;
/*!40000 ALTER TABLE `catalog_campos` DISABLE KEYS */;

INSERT INTO `catalog_campos` (`idcampo`, `idestructura`, `nombrecampo`, `nombrecampousuario`, `descripcion`, `longitud`, `tipo`, `valor`, `formula`, `requerido`, `formato`, `orden`, `llaveprimaria`)
VALUES
	(1,1,'idorganizacion','Id. Organización','Id. Organización',0,'auto_increment','NA','',0,'',1,-1),
	(2,1,'nombreorganizacion','Razón Social','Razón Social',45,'varchar','NA','',-1,'',2,0),
	(3,2,'idempleado','Id. Empleado','Id. Empleado',0,'auto_increment','NA','',0,'',1,-1),
	(4,2,'nombre','Nombre','Nombre',45,'varchar','NA','',-1,'',2,0),
	(5,2,'apellido1','Apellido Paterno','Apellido Paterno',45,'varchar','NA','',-1,'',3,0),
	(6,2,'apellido2','Apellido Materno','Apellido Materno',45,'varchar','NA','',-1,'',4,0),
	(7,2,'idorganizacion','Organización','Organización',45,'varchar','NA','',-1,'',7,0),
	(8,3,'idcategoria','Id. Categoria','Id. Categoria',0,'auto_increment','NA','',0,'',1,-1),
	(9,3,'nombre','Nombre','Nombre',45,'varchar','NA','',-1,'',2,0),
	(10,3,'icono','Usa icono','Usa icono',0,'boolean','NA','',0,'',3,0),
	(11,3,'orden','Orden','Orden',0,'int','NA','',0,'',4,0),
	(12,4,'idmenu','Id. Menú','Id. Menú',0,'auto_increment','NA','',0,'',1,-1),
	(13,4,'nombre','Nombre','Nombre',45,'varchar','NA','',-1,'',2,0),
	(14,4,'idmenupadre','Id. Menu Padre','Id. Menu Padre',5,'int','NA','',-1,'',3,0),
	(15,4,'url','URL o vínculo','URL o vínculo',300,'varchar','NA','',0,'',4,0),
	(16,4,'idcategoria','Categoría','Categoría',5,'int','NA','',-1,'',5,0),
	(17,4,'icono','Icono','Icono',0,'boolean','NA','',0,'',6,0),
	(18,4,'orden','Orden','Orden',5,'int','NA','',0,'',7,0),
	(19,5,'idperfil','Id. Perfil','Id. Perfil',0,'auto_increment','NA','',0,'',1,-1),
	(20,5,'nombre','Nombre','Nombre',50,'varchar','NA','',-1,'',2,0),
	(21,6,'idperfil','Perfil','Perfil',0,'int','NA','',0,'',1,-1),
	(22,6,'idmenu','Menú','Menú',0,'int','NA','',0,'',2,-1),
	(23,7,'idopcion','Id. Opción','Id. Opción',45,'varchar','NA','',0,'',1,-1),
	(24,7,'nombre','Nombre','Nombre',100,'varchar','NA','',1,'',2,0),
	(25,8,'idperfil','Perfil','Perfil',0,'int','NA','',0,'',1,-1),
	(26,8,'idopcion','Opción','Opción',45,'varchar','NA','',0,'',2,-1),
	(27,9,'idperfil','Perfil','Perfil',0,'int','NA','',0,'',0,-1),
	(28,9,'idempleado','Empleado','Empleado',0,'int','NA','',0,'',0,-1),
	(29,10,'idempleado','Empleado','Empleado',0,'int','NA','',0,'',1,-1),
	(30,10,'usuario','Usuario','Usuario',100,'varchar','NA','',0,'',2,-1),
	(31,10,'clave','Clave','Clave',100,'varchar','NA','',0,'',3,0),
	(32,4,'omision','Abrir por Omisión','Abrir menú por omisión o default al abrir el sistema',0,'boolean','NA','',0,'',8,0),
	(33,11,'idestructura','Estructura','Estructura',0,'int','NA','',-1,'',1,-1),
	(34,11,'nombrecampo_empleados','Campo llave (Empleados)','Campo llave (Empleados)',50,'varchar','NA','',-1,'',2,-1),
	(35,11,'nombreestructura','Tabla','Tabla',50,'varchar','NA','',-1,'',3,-1),
	(36,12,'idproducto','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(37,12,'nombreproducto','Nombre Producto','Nombre Producto',100,'varchar','NA','',0,'',2,0),
	(38,12,'codigobarras','Codigo Barras','Codigo Barras',0,'int','NA','',0,'',3,0),
	(39,12,'clave','Clave','Clave',50,'varchar','NA','',0,'',4,0),
	(40,12,'descripcion','Descripcion','Descripcion',255,'varchar','NA','',0,'',5,0),
	(41,12,'idmarca','Marca','Marca',0,'int','NA','',0,'',6,0),
	(46,14,'idmarca','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(47,14,'descripcion','Descripcion','Descripcion',100,'varchar','NA','',0,'',2,0),
	(48,12,'precio','Precio','Precio',0,'double','NA','',0,'O',7,0),
	(49,12,'impuestos','Impuestos','Impuestos',0,'double','NA','',0,'O',8,0),
	(50,12,'preciofinal','Precio Final','Precio Final',0,'double','NA','',0,'O',9,0),
	(51,12,'costo','Costo','Costo',0,'double','NA','',0,'O',10,0),
	(52,15,'idproveedor','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(53,15,'rfc','RFC (SIN ESPACIOS)','RFC (SIN ESPACIOS)',30,'varchar','NA','',0,'',2,0),
	(54,15,'razonsocial','Razon Social','Razon Social',150,'varchar','NA','',-1,'',3,0),
	(55,15,'domicilio','Domicilio (Calle-No,Colonia,CP.)','Domicilio (Calle-No,Colonia,CP.)',255,'varchar','NA','',-1,'',4,0),
	(56,15,'idestado','Estado','Estado',0,'int','NA','',0,'',5,0),
	(57,15,'idmunicipio','Municipio','Municipio',0,'int','NA','',0,'',7,0),
	(58,16,'idestado','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(59,16,'estado','Estado','Estado',0,'varchar','NA','',0,'',2,0),
	(60,16,'idpais','Pais','Pais',0,'int','NA','',0,'',3,0),
	(61,17,'idpais','id','Id',0,'auto_increment','NA','',0,'',1,-1),
	(62,17,'pais','Pais','Pais',100,'varchar','NA','',0,'',2,0),
	(63,18,'idmunicipio','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(64,18,'municipio','Municipio','Municipio',100,'varchar','NA','',0,'',2,0),
	(65,18,'idestado','Estado','Estado',0,'int','NA','',0,'',3,0),
	(66,15,'telefonos','Telefonos (LADA) XXXX-XXXX','Telefonos (LADA) XXXX-XXXX',100,'varchar','NA','',-1,'',8,0),
	(67,15,'diascredito','Dias credito','Dias Credito',0,'int','NA','',0,'',9,0),
	(68,15,'limitecredito','Limite Credito','Limite Credito',0,'double','NA','',0,'$.00',10,0),
	(69,15,'correoelectronico','Correo Electronico','Correo Electronico',0,'varchar','@','',0,'',11,0),
	(70,15,'paginaweb','Pagina Web','Pagina Web',0,'varchar','http://','',0,'',12,0),
	(71,19,'idcliente','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(72,19,'razonsocial','Cliente','Cliente',100,'varchar','NA','',-1,'',3,0),
	(73,19,'rfc','RFC (SIN ESPACIOS)','RFC (SIN ESPACIOS)',50,'varchar','NA','',0,'',2,0),
	(74,19,'domicilio','Domicilio (Calle-No,Colonia,CP.)','Domicilio',255,'varchar','NA','',-1,'',4,0),
	(75,19,'idestado','Estado','Estado',0,'int','NA','',0,'',5,0),
	(76,19,'idmunicipio','Municipio','Municipio',0,'int','NA','',0,'',6,0),
	(77,19,'telefonos','Telefonos (LADA) XXXX-XXXX','Telefonos',100,'varchar','NA','',-1,'',7,0),
	(78,19,'diascredito','Dias Credito','Dias Credito',0,'int','NA','',0,'',8,0),
	(79,19,'limitecredito','Limite Credito','Limite Credito',0,'double','NA','',0,'$.00',9,0),
	(80,19,'correoelectronico','Correo Electronico','Correo Electronico',100,'varchar','NA','',0,'',10,0),
	(81,19,'paginaweb','Pagina Web','Pagina Web',100,'varchar','http://','',0,'',11,0),
	(82,20,'idrecibo','Recibo','Recibo',0,'auto_increment','NA','',0,'',1,-1),
	(83,20,'recibo','recibo','Recibo',5000,'varchar','NA','',0,'',2,0),
	(84,21,'idmm','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(85,21,'idproducto','Producto','Producto',0,'int','NA','',0,'',2,0),
	(86,21,'idalmacen','Almacen','Almacen',0,'int','NA','',0,'',3,0),
	(87,22,'idalmacen','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(88,22,'almacen','Nombre Almacen','Nombre Almacen',100,'varchar','NA','',0,'',2,0),
	(89,22,'domicilio','Domicilio','Domicilio',255,'varchar','NA','',0,'',3,0),
	(90,22,'idestado','Estado','Estado',0,'int','NA','',0,'',4,0),
	(91,22,'idmunicipio','Municipio','Municipio',0,'int','NA','',0,'',5,0),
	(92,22,'telefonos','Telefonos','Telefonos',100,'varchar','NA','',0,'',6,0),
	(93,22,'idempleado','Responsable','Responsable',0,'int','NA','',0,'',7,0),
	(94,12,'foto1','Foto 1 [jpg, png,gif]:[50x50]','Foto 1 [jpg, png,gif]:[50x50]',0,'archivo','NA','',0,'O',11,0),
	(95,21,'maximo','Cantidad Maxima','Cantidad Maxima',0,'double','NA','',0,'0.00',4,0),
	(96,21,'minimo','Minimo','Minimo',0,'double','NA','',0,'0.00',5,0),
	(97,12,'unidadmedida','Unidad de Medida','Unidad de Medida',20,'varchar','NA','',0,'',12,0),
	(98,23,'idventa','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(99,23,'fecha','Fecha Venta','Fecha Venta',0,'date','NA','',0,'',2,0),
	(100,25,'idSuc','idSuc','id',0,'auto_increment','NA','',0,'',1,-1),
	(101,25,'nombre','Sucursal','Sucursal',30,'varchar','NA','',0,'',2,0),
	(102,23,'idsucursal','Sucursal','Sucursal',0,'int','NA','',0,'',3,0),
	(103,23,'idcliente','Cliente','Cliente',0,'int','NA','',0,'',4,0),
	(104,23,'subtotal','Sub-Total','Sub-Total',0,'double','NA','[subtotal]',0,'$.00',5,0),
	(105,23,'impuestos','Impuestos','Impuestos',0,'double','NA','',0,'$.00',6,0),
	(106,23,'total','Total','Total',0,'double','NA','{subtotal}',0,'$.00',7,0),
	(107,26,'idproducto','id','id',0,'int','NA','',0,'',0,-1),
	(108,26,'Descripcion','Descripcion','Descripcion',0,'varchar','NA','',0,'',2,0),
	(109,24,'idventa','id','id',0,'int','NA','',0,'',1,0),
	(110,24,'idlinea','Linea','Linea',0,'auto_increment','NA','',0,'',2,-1),
	(111,24,'idproducto','Producto','Producto',0,'int','NA','',0,'',3,0),
	(112,24,'cantidad','Cantidad','Cantidad',0,'double','1','',0,'',6,0),
	(113,24,'preciounitario','Precio Unitario','Precio Unitario',0,'double','0','',0,'$.00',7,0),
	(114,24,'subtotal','Subtotal','Subtotal',0,'double','NA','({cantidad}*{preciounitario})',0,'$.00',9,0),
	(117,23,'idempleado','Vendedor','Vendedor',0,'int','NA','',0,'-1',9,0),
	(118,27,'idempleado','id','id',0,'int','NA','',0,'',0,-1),
	(119,27,'nombre','Nombre','Nombre',0,'varchar','NA','',0,'',0,0),
	(133,40,'idlote','Lote','Lote',0,'auto_increment','NA','',0,'',1,-1),
	(134,40,'descripcionlote','Lote','Lote',100,'varchar','NA','',0,'',2,0),
	(135,40,'fabricacion','Fabricacion','Fabricacion',0,'datetime','NA','',0,'',3,0),
	(136,40,'caducidad','Caducidad','Caducidad',0,'datetime','NA','',0,'',4,0),
	(137,41,'idtipomovimiento','Tipo Movimiento','Tipo Movimiento',0,'auto_increment','NA','',0,'',1,-1),
	(138,41,'descripcion','Descripcion','Descripcion',100,'varchar','NA','',0,'',2,0),
	(139,41,'efectoinventario','Efecto Inventario','Efecto Inventario (Entrada=1, Salida=-1)',0,'double','NA','',0,'',3,0),
	(140,38,'idmovimiento','Id','Id',0,'auto_increment','NA','',0,'',1,-1),
	(141,38,'idalmacen','Almacen','Almacen',0,'int','NA','',0,'',2,0),
	(142,38,'idproducto','Producto','Producto',0,'int','NA','',0,'',3,0),
	(143,38,'idlote','Lote','Lote',0,'int','NA','',0,'',4,0),
	(144,38,'cantidad','Cantidad','Cantidad',0,'double','NA','',0,'',6,0),
	(145,38,'idtipomovimiento','Movimiento','Movimiento',0,'int','NA','',0,'',7,0),
	(146,38,'doctoorigen','Documento Origen','Documento Origen',0,'int','NA','',0,'',8,0),
	(147,38,'folioorigen','Folio Origen','Folio Origen',0,'int','NA','',0,'',9,0),
	(148,39,'idmovimiento','Id','Id',0,'auto_increment','NA','',0,'',1,-1),
	(149,39,'idalmacen','Almacen','Almacen',0,'int','NA','',0,'',2,0),
	(150,39,'idproducto','Producto','Producto',0,'int','NA','',0,'',3,0),
	(151,39,'idlote','Lote','Lote',0,'int','NA','',0,'',4,0),
	(152,39,'idestadoproducto','Estado Producto','Estado Producto',0,'int','NA','',0,'',5,0),
	(153,42,'idestadoproducto','Id','Id',0,'auto_increment','NA','',0,'',1,-1),
	(154,42,'descripcion','Descripcion','Descripcion',100,'varchar','NA','',0,'',1,0),
	(155,38,'fecha','Fecha','Fecha',0,'datetime','NA','',0,'',10,0),
	(156,38,'idestadoproducto','Estado','Estado',0,'int','NA','',0,'',5,0),
	(157,39,'cantidad','Cantidad','Cantidad',0,'double','NA','',0,'',6,0),
	(158,43,'idmovimiento','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(159,43,'fecha','Fecha','Fecha',0,'date','NA','',0,'',2,0),
	(161,43,'referencia','Referencia','Referencia',50,'varchar','NA','',0,'',4,0),
	(162,43,'observaciones','Observaciones','Observaciones',255,'varchar','NA','',0,'',5,0),
	(163,44,'idmovimiento','id','id',0,'int','NA','',0,'',1,0),
	(164,44,'idlinea','Linea','Linea',0,'auto_increment','NA','',0,'',2,-1),
	(165,44,'idproducto','Producto','Producto',0,'int','NA','',0,'',3,0),
	(166,44,'idlote','Lote','Lote',0,'int','NA','',0,'',4,0),
	(167,44,'idestadoproducto','Estado Producto','Estado Producto',0,'int','NA','',0,'',5,0),
	(168,44,'cantidad','Cantidad','Cantidad',0,'double','NA','',0,'0.00',6,0),
	(169,44,'idtipomovimiento','Tipo Movimiento','Tipo Movimiento',0,'int','NA','',0,'',2,0),
	(170,43,'idestadodocumento','Estado','Estado',0,'int','0','',0,'',6,0),
	(171,43,'idalmacen','Almacen','Almacen',0,'int','NA','',0,'',3,0),
	(172,24,'idlote','Lote','Lote',0,'int','NA','',0,'O',4,0),
	(173,24,'idestadoproducto','Estado Producto','Estado Producto',0,'int','NA','',0,'O',5,0),
	(174,23,'idalmacen','Almacen','Almacen',0,'int','NA','',0,'',8,0),
	(175,45,'idlona','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(176,45,'nombrelona','Nombre Lona','Nombre Lona',100,'varchar','NA','',0,'',2,0),
	(177,45,'lonajpg','Imagen','Imagen',0,'archivo','NA','',0,'',3,0),
	(178,45,'mostrar','¿Mostrar?','¿Mostrar?',0,'boolean','NA','',0,'',4,0),
	(182,46,'idpinicial','id','id',0,'auto_increment','NA','',0,'',1,0),
	(183,1,'RFC','RFC','RFC',40,'varchar','NA','',-1,'',3,0),
	(184,1,'regimen','Regimen','Regimen',0,'varchar','NA','',0,'',4,0),
	(185,1,'domicilio','Domicilio','Domicilio',200,'varchar','NA','',0,'',5,0),
	(186,1,'idestado','Estado','Estado',0,'int','NA','',0,'',7,0),
	(187,1,'idmunicipio','Municipios','Municipios',0,'varchar','NA','',0,'',8,0),
	(188,1,'cp','Código Postal','Código Postal',50,'varchar','NA','',0,'',9,0),
	(189,1,'colonia','Colonia','Colonia',120,'varchar','NA','',0,'',6,0),
	(190,1,'paginaweb','PaginaWeb','PaginaWeb',100,'varchar','NA','',0,'',10,0),
	(192,1,'logoempresa','Logo Empresa (180x100 pixeles.)','Logo Empresa (180x100 pixeles.)',0,'archivo','NA','',0,'',11,0),
	(193,2,'visible','Visible','Visible',0,'boolean','NA','',0,'',6,0),
	(194,5,'visible','Visible','Visible',0,'boolean','NA','',0,'',3,0),
	(195,47,'idadmin','Id','Id',0,'auto_increment','NA','',0,'',1,-1),
	(196,47,'nombre','Nombre','Nombre',100,'varchar','NA','',-1,'',2,0),
	(197,47,'apellidos','Apellidos','Apellidos',100,'varchar','NA','',-1,'',3,0),
	(198,47,'nombreusuario','Nombre de Usuario','Nombre de Usuario',100,'varchar','NA','',-1,'',4,0),
	(199,47,'clave','Contraseña','Contraseña',50,'varchar','NA','',-1,'#',5,0),
	(200,47,'confirmaclave','Confirma tu Contraseña','Confirma tu Contraseña',50,'varchar','NA','',-1,'#',6,0),
	(201,47,'correoelectronico','Correo Electronico','Correo Electronico',100,'varchar','@','',0,'',7,0),
	(202,47,'foto','Fotografia Perfil','Fotografia Perfil',0,'archivo','NA','',0,'',8,0),
	(203,47,'idperfil','Perfil Aplicaciones','Perfil Aplicaciones',0,'int','NA','',0,'-1',9,0),
	(204,47,'idempleado','idempleado','idempleado',0,'int','NA','',0,'O',11,0),
	(205,48,'idperfil','Perfil','Perfil',0,'int','NA','',0,'',1,-1),
	(206,48,'nombre','Nombre','Nombre',100,'varchar','NA','',0,'',2,0),
	(207,47,'idorganizacion','idorganizacion','idorganizacion',0,'int','NA','',0,'',12,0),
	(208,2,'administrador','¿Administrador?','¿Administrador?',0,'boolean','NA','',0,'',5,0),
	(209,47,'idpuesto','Puesto','Puesto',0,'int','NA','',0,'',13,0),
	(210,49,'idcampania','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(211,49,'nombrecampania','Nombre Campaña','Nombre Campaña',100,'varchar','NA','',0,'',2,0),
	(212,49,'estrategia','Estrategia Campaña','Estrategia Campaña',2000,'varchar','NA','',0,'',3,0),
	(213,49,'archivoscampania','Archivos Campaña','Archivos Campaña',0,'archivo','NA','',0,'',4,0),
	(214,49,'fechainicial','Fecha Inicial','Fecha Inicial',0,'datetime_seg_hr','NA','',0,'',5,0),
	(215,49,'fechafinal','Fecha Final','Fecha Final',0,'datetime_seg_hr','NA','',0,'',6,0),
	(216,49,'responsable','Responsable','Responsable',100,'varchar','NA','',0,'',7,0),
	(217,49,'activa','¿Campaña Activa?','¿Campaña Activa?',0,'boolean','NA','',0,'',8,0),
	(218,50,'idmeta','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(219,49,'meta','Meta (Toneladas)','Meta (Toneladas)',0,'double','NA','[cantidad]',0,'0.00',9,0),
	(220,50,'nombre','Nombre Meta','Nombre Meta',100,'varchar','NA','',0,'',2,0),
	(221,50,'montometa','Monto Meta','Monto Meta',0,'double','NA','',0,'$.00',3,0),
	(222,50,'idciclo','Ciclo Evaluación','Ciclo Evaluación',0,'int','NA','',0,'',4,0),
	(223,51,'idciclo','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(224,51,'nombre','Ciclo','Ciclo',50,'varchar','NA','',0,'',2,0),
	(225,52,'idpuesto','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(226,52,'puesto','Puesto','Puesto',100,'varchar','NA','',0,'',2,0),
	(227,19,'clipro','¿Aun es prospecto?','¿Aun es prospecto?',0,'boolean','NA','',0,'',13,0),
	(228,53,'idactividad','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(229,53,'nombre','Nombre Actividad Comercial','Nombre Actividad Comercial',100,'varchar','NA','',0,'',2,0),
	(230,53,'minutos','Minutos','Minutos',0,'double','NA','',0,'0.00',3,0),
	(231,54,'idreg','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(232,54,'fecha','Fecha','Fecha',0,'datetime','NA','',0,'',2,0),
	(233,54,'idactividad','Actividad','Actividad',0,'int','NA','',0,'',3,0),
	(234,54,'descripcion','Descripcion','Descripcion',1000,'varchar','NA','',0,'',4,0),
	(235,54,'documento','Documento','Documento',0,'archivo','NA','',0,'',5,0),
	(236,54,'idempleado','Empleado','Empleado',0,'int','NA','',0,'O',6,0),
	(237,54,'idcliente','Cliente','Cliente',0,'int','NA','',0,'',7,0),
	(238,55,'idcuenta','Cuenta','Cuenta',0,'auto_increment','NA','',0,'',1,-1),
	(239,55,'nombrecuenta','Nombre Cuenta','Nombre Cuenta',100,'varchar','NA','',0,'',2,0),
	(240,55,'idcliente','Cliente','Cliente',0,'int','NA','',0,'',3,0),
	(241,55,'fecha','Fecha Alta','Fecha Alta',0,'datetime_seg_hr','NA','',0,'',4,0),
	(242,55,'ventaestimada','Venta Estimada (Toneladas)','Venta Estimada (Toneladas)',0,'double','NA','',0,'0.00',5,0),
	(243,55,'idprocesoventa','Proceso Venta','Proceso Venta',0,'int','NA','',0,'',7,0),
	(244,55,'activa','¿Activa?','¿Activa?',0,'boolean','NA','',0,'',8,0),
	(245,56,'idprocesoventa','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(246,56,'nombre','Nombre','Nombre',100,'varchar','NA','',0,'',2,0),
	(247,56,'porcentaje','Porcentaje (%)','Porcentaje (%)',0,'varchar','NA','',0,'0.00',3,0),
	(248,57,'idestadoventa','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(249,57,'nombre','Nombre','Nombre',100,'varchar','NA','',0,'',2,0),
	(250,23,'idestadoventa','Estado Venta','Estado Venta',0,'int','NA','',0,'',10,0),
	(251,23,'idcampania','Campaña','Campaña',0,'int','NA','',0,'',11,0),
	(252,23,'idcuenta','Cuenta','Cuenta',0,'int','NA','',0,'',12,0),
	(253,23,'idproducto','(+/-) Productos','(+/-) Productos',0,'int','NA','',0,'',14,0),
	(254,58,'idprecio','Precio','Precio',0,'auto_increment','NA','',0,'',1,-1),
	(255,58,'idproducto','Producto','Producto',0,'int','NA','',0,'',2,0),
	(256,58,'preciocompra','Precio Compra','Precio Compra',0,'double','NA','',0,'$.00',3,0),
	(257,58,'precioventa','Precio Venta','Precio Venta',0,'double','NA','',0,'$.00',4,0),
	(258,58,'fechainicial','Fecha Inicial','Fecha Inicial',0,'datetime_seg_hr','NA','',0,'',5,0),
	(260,59,'idcompra','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(261,59,'fecha','Fecha Compra','Fecha Compra',0,'datetime_seg_hr','NA','',0,'',2,0),
	(262,59,'idproveedor','Proveedor','Proveedor',0,'int','NA','',0,'',3,0),
	(263,59,'idalmacen','Almacen','Almacen',0,'int','NA','',0,'',4,0),
	(264,59,'refcompra','Ref. SA','Ref. SA',50,'varchar','NA','',0,'',5,0),
	(265,59,'subtotal','SubTotal','SubTotal',0,'double','NA','[subtotal]',0,'$.00',6,0),
	(266,59,'impuestos','Impuestos','Impuestos',0,'double','NA','{subtotal}*0.16',0,'$.00',7,0),
	(267,59,'total','Total','Total',0,'double','NA','{subtotal}+{impuestos}',0,'$.00',8,0),
	(268,59,'observaciones','Observaciones','Observaciones',1000,'varchar','NA','',0,'',9,0),
	(269,59,'activa','¿Activa?','¿Activa?',0,'boolean','NA','',0,'',10,0),
	(270,59,'idproducto','Alta Producto','Alta Producto',0,'int','NA','',0,'',11,0),
	(271,61,'idtipocliente','Tipo Cliente','Tipo Cliente',0,'auto_increment','NA','',0,'',1,-1),
	(272,61,'nombre','Nombre','Nombre',100,'varchar','NA','',0,'',2,0),
	(273,19,'idtipocliente','Tipo Cliente','Tipo Cliente',0,'int','NA','',0,'',18,0),
	(274,58,'idtipoprecio','Tipo Precio','Tipo Precio',0,'int','NA','',0,'',10,0),
	(275,62,'idtipoprecio','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(276,62,'nombre','Nombre','Nombre',0,'varchar','NA','',0,'',2,0),
	(278,63,'idcampania','id','id',0,'int','NA','',0,'',1,0),
	(279,63,'idlinea','Linea','Linea',0,'auto_increment','NA','',0,'',2,-1),
	(280,63,'idproducto','Producto','Producto',0,'int','NA','',0,'',3,0),
	(281,63,'cantidad','Toneladas','Toneladas',0,'double','NA','',0,'0.00',5,0),
	(282,23,'fechaentrega','Fecha Entrega','Fecha Entrega',0,'datetime_seg_hr','NA','',0,'',3,0),
	(283,60,'idcompra','id','id',0,'int','NA','',0,'',1,0),
	(284,60,'idlinea','id','id',0,'auto_increment','NA','',0,'',2,-1),
	(285,60,'idproducto','Producto','Producto',0,'int','NA','',0,'',3,0),
	(286,60,'cantidad','Cantidad','Cantidad',0,'double','NA','',0,'0.00',4,0),
	(287,60,'precio','Precio Compra','Precio',0,'double','NA','',0,'$.00',5,0),
	(288,60,'subtotal','Subtotal','Subtotal',0,'double','NA','{cantidad}*{precio}',0,'$.00',6,0),
	(289,66,'iddeudor','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(290,66,'nombre','Nombre','Nombre',100,'varchar','NA','',0,'',2,0),
	(291,66,'tipo','Tipo','Tipo',0,'varchar','NA','',0,'',3,0),
	(292,67,'iddeudor','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(293,67,'nombredeudor','Nombre Deudor','Nombre Deudor',100,'varchar','NA','',0,'',2,0),
	(294,19,'tipo','Tipo','Tipo',2,'varchar','C','',0,'O',19,0),
	(295,47,'tipo','Tipo','Tipo',2,'varchar','NA','',0,'O',14,0),
	(296,64,'idcxc','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(297,64,'fechacargo','Fecha','Fecha',0,'datetime_seg_hr','NA','',0,'',2,0),
	(298,65,'idcxp','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(299,65,'fechavencimiento','Fecha Vencimiento','Fecha Vencimiento',0,'datetime_seg_hr','NA','',0,'',3,0),
	(300,65,'fechacargo','Fecha Cargo','Fecha Cargo',0,'datetime_seg_hr','NA','',0,'',2,0),
	(301,23,'idcompra','Ref. SA','Ref. SA',0,'int','NA','',0,'',15,0),
	(302,23,'refdeposito','Referencia Deposito','Referencia Deposito',20,'varchar','NA','',0,'',12,0),
	(303,67,'idclaveorigen','Clave Origen','Clave origen',0,'int','NA','',0,'',3,0),
	(304,67,'idestructura','Estructura','Estructura',0,'int','NA','',0,'',4,0),
	(305,68,'idacreedor','Id ','Id',0,'auto_increment','NA','',0,'',1,-1),
	(306,68,'nombreacreedor','Acreedor','Acreedor',100,'varchar','NA','',0,'',2,0),
	(307,68,'idclaveorigen','Clave Origen','Clave Origen',0,'int','NA','',0,'',3,0),
	(308,68,'idestructura','Estructura','Estructura',0,'int','NA','',0,'',4,0),
	(309,64,'fechavencimiento','Fecha Vencimiento','Fecha Vencimiento',0,'datetime_seg_hr','NA','',0,'',3,0),
	(310,64,'iddeudor','Deudor','Deudor',0,'int','NA','',0,'',4,0),
	(311,64,'idconcepto','Concepto','Concepto',0,'int','NA','',0,'',5,0),
	(312,69,'idconcepto','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(313,69,'concepto','Concepto','Concepto',100,'varchar','NA','',0,'',2,0),
	(314,64,'descripcion','Descripción','Descripción',150,'varchar','NA','',0,'',6,0),
	(315,64,'saldoinicial','Saldo Inicial','Saldo Inicial',0,'double','NA','',0,'$.00',7,0),
	(316,64,'abonos','Abonos','Abonos',0,'double','NA','[pago]',0,'$.00',8,0),
	(317,64,'saldoactual','Saldo Actual','Saldo Actual',0,'double','NA','{saldoinicial}-{abonos}',0,'$.00',9,0),
	(318,64,'iddocumento','Documento','Documento',0,'int','NA','',0,'',10,0),
	(319,64,'foliodocumento','Folio Documento','Folio Documento',0,'int','NA','',0,'',11,0),
	(320,64,'activo','¿Activo?','¿Activo?',0,'boolean','NA','',0,'',12,0),
	(321,70,'idcxc','idcxc','idcxc',0,'int','NA','',0,'',1,0),
	(322,70,'idpago','id','id',0,'auto_increment','NA','',0,'',2,-1),
	(323,70,'fechapago','Fecha Pago','Fecha Pago',0,'datetime_seg_hr','NA','',0,'',3,0),
	(324,70,'pago','Pago','Pago',0,'double','NA','',0,'$.00',4,0),
	(325,70,'idformapago','Forma de Pago','Forma de Pago',0,'int','NA','',0,'',5,0),
	(326,71,'idformapago','id','id',0,'auto_increment','NA','',0,'',1,-1),
	(327,71,'formapago','Forma de Pago','Forma de Pago',100,'varchar','NA','',0,'',2,0),
	(328,70,'referencia','Referencia','Referencia',100,'varchar','NA','',0,'',6,0),
	(329,70,'idcuenta','Cuenta','Cuenta',0,'int','NA','',0,'',7,0),
	(330,72,'idcuenta','Cuenta Bancaria','Cuenta Bancaria',0,'auto_increment','NA','',0,'',1,-1),
	(331,72,'idbanco','Banco','Banco',0,'int','NA','',0,'',2,0),
	(332,73,'idbanco','Banco','Banco',0,'auto_increment','NA','',0,'',1,-1),
	(333,73,'banco','Banco','Banco',100,'varchar','NA','',0,'',2,0),
	(334,72,'nombrecuenta','Nombre Cuenta','Nombre Cuenta',100,'varchar','NA','',0,'',3,0),
	(335,72,'numerocuenta','Numero Cuenta','Numero Cuenta',100,'varchar','NA','',0,'',4,0),
	(336,72,'clabe','CLABE','CLABE',100,'varchar','NA','',0,'',5,0),
	(337,72,'beneficiario','Beneficiario','Beneficiario',100,'varchar','NA','',0,'',5,0),
	(338,70,'observaciones','Observaciones','Observaciones',255,'varchar','NA','',0,'',8,0),
	(339,65,'idacreedor','Acreedor','Acreedor',0,'int','NA','',0,'',4,0),
	(340,65,'idconcepto','Concepto','Concepto',0,'int','NA','',0,'',5,0),
	(341,65,'descripcion','Descripción','Descripción',150,'varchar','NA','',0,'',6,0),
	(342,65,'saldoinicial','Saldo Inicial','Saldo Inicial',0,'double','NA','',0,'$.00',7,0),
	(343,65,'abonos','Abonos','Abonos',0,'double','NA','[pago]',0,'$.00',8,0),
	(344,65,'saldoactual','Saldo Actual','Saldo Actual',0,'double','NA','{saldoinicial}-{abonos}',0,'$.00',9,0),
	(345,65,'iddocumento','Documento Origen','Documento Origen',0,'int','NA','',0,'',10,0),
	(346,65,'foliodocumento','Folio Documento','Folio Documento',0,'int','NA','',0,'',11,0),
	(347,65,'activo','¿Activo?','¿Activo?',0,'boolean','NA','',0,'',12,0),
	(348,74,'idcxp','Idcxp','Idcxp',0,'int','NA','',0,'',1,0),
	(349,74,'idpago','id','id',0,'auto_increment','NA','',0,'',2,-1),
	(350,74,'fechapago','Fecha Pago','Fecha Pago',0,'date','NA','',0,'',3,0),
	(351,74,'pago','Pago','Pago',0,'double','NA','',0,'$.00',4,0),
	(352,74,'idformapago','Forma Pago','Forma Pago',0,'int','NA','',0,'',5,0),
	(353,74,'referencia','Referencia','Referencia',100,'varchar','NA','',0,'',6,0),
	(354,74,'idcuenta','Cuenta','Cuenta',0,'varchar','NA','',0,'',7,0),
	(355,74,'observaciones','Observaciones','Observaciones',255,'varchar','NA','',0,'',8,0),
	(356,75,'idDep','ID','ID Departamento',0,'auto_increment','NA','',-1,'',0,-1),
	(357,75,'nombre','Nombre','Nombre',100,'varchar','NA','',-1,'',1,0),
	(358,76,'idFam','ID','ID Familia',0,'auto_increment','NA','',-1,'',0,-1),
	(359,76,'nombre','Nombre','Nombre',255,'varchar','NA','',-1,'',0,0),
	(360,76,'idDep','Departamento','Departamento',0,'int','NA','',-1,'',0,0),
	(361,77,'idLin','ID','ID Linea',0,'auto_increment','NA','',-1,'',0,-1),
	(362,77,'nombre','Nombre','Nombre',255,'varchar','NA','',-1,'',1,0),
	(363,77,'idFam','Familia','Familia',0,'int','NA','',-1,'',2,0),
	(364,79,'idProducto','id','',0,'auto_increment','NA','',0,'',0,-1),
	(365,79,'nombre','Nombre','',100,'varchar','NA','',-1,'',1,0),
	(366,79,'deslarga','Descripción larga','',500,'varchar','NA','',0,'',4,0),
	(367,79,'descorta','Descripción corta','',200,'varchar','NA','',-1,'',3,0),
	(368,79,'consumo','Consumo','',0,'boolean','NA','',-1,'',6,0),
	(369,79,'vendible','Vendible','',0,'boolean','NA','',-1,'',7,0),
	(370,79,'descenefa','Descripción Cenefa','',200,'varchar','NA','',0,'',5,0),
	(371,80,'id','ID','',0,'auto_increment','NA','',0,'',0,-1),
	(372,80,'idProducto','Producto','',0,'int','NA','',-1,'',1,0),
	(373,80,'idPrv','Proveedor','',0,'int','NA','',-1,'',2,0),
	(374,80,'costo','Costo','',20,'double','NA','',0,'',2,0),
	(375,79,'idLinea','Linea','Linea',0,'int','NA','',0,'',2,0),
	(376,79,'talla','Talla','Talla',255,'varchar','NA','',0,'',9,0),
	(377,79,'color','Color','Color',255,'varchar','NA','',0,'',8,0),
	(378,81,'idCol','ID Color','ID Color',0,'auto_increment','NA','',-1,'',0,-1),
	(379,81,'color','Color','Color',11,'varchar','NA','',-1,'',0,0),
	(380,82,'idTal','id','id',0,'auto_increment','NA','',-1,'',0,-1),
	(381,82,'talla','Talla','Talla',11,'varchar','NA','',-1,'',0,0),
	(382,78,'idPrv','ID','ID',0,'auto_increment','NA','',-1,'',0,-1),
	(383,78,'razon_social','Razon Social y/o nombre','Razon Social',255,'varchar','NA','',-1,'',2,0),
	(384,78,'rfc','RFC (Sin espacios)','RFC (Sin espacios)',13,'varchar','NA','',-1,'',1,0),
	(385,78,'domicilio','Domicilio','Domicilio',255,'varchar','NA','',0,'',3,0),
	(386,78,'telefono','Teléfono','Telefono',20,'varchar','NA','',0,'',6,0),
	(387,78,'email','Correo Electronico','Correo Electronico',255,'varchar','NA','',0,'',7,0),
	(388,78,'web','Pagina Web','Pagina Web',255,'varchar','NA','',0,'',8,0),
	(389,78,'idestado','Estado','Estado',10,'varchar','NA','',-1,'-1',4,0),
	(390,78,'idmunicipio','Municipio','Municipio',10,'varchar','NA','',-1,'-1',5,0),
	(391,83,'idOrd','ID Orden','ID Orden',0,'auto_increment','NA','',-1,'',0,-1),
	(392,83,'idDep','Departamento','Departamento',0,'varchar','NA','',-1,'',1,0),
	(393,79,'maximo','Máximo','',0,'int','NA','',0,'',10,0),
	(394,79,'minimo','Minimo','',0,'int','NA','',0,'',11,0),
	(395,84,'idPrOr','ID Producto-Orden','ID Producto-Orden',0,'auto_increment','NA','',-1,'',0,-1),
	(396,84,'idOrd','ID Orden','ID Orden',0,'int','NA','',0,'',0,0),
	(397,84,'cantidad','Cantidad','Cantidad',0,'int','NA','',0,'',0,0),
	(398,84,'unidad','Unidad','Unidad (Kg, M, etc.)',15,'varchar','NA','',0,'',0,0),
	(399,84,'producto','Producto','Producto',0,'varchar','255','',0,'',0,0),
	(400,83,'fecha_pedido','Fecha de Pedido','Fecha de Pedido',0,'date','NA','',-1,'',2,0),
	(401,83,'fecha_entrega','Fecha de Entrega','Fecha de Entrega',0,'date','NA','',-1,'',3,0),
	(402,83,'elaborado_por','Elaborado por:','Elaborado por:',255,'varchar','NA','',-1,'',4,0),
	(403,83,'autorizado_por','Autorizado por:','Autorizado por:',255,'varchar','NA','',-1,'',5,0),
	(404,83,'recibido_por','Recibido por:','Recibido por:',255,'varchar','NA','',0,'',6,0),
	(405,83,'idPrv','Proveedor','Proveedor',7,'varchar','NA','',0,'',0,0),
	(406,85,'idUni','ID Unidad','ID Unidad',0,'auto_increment','NA','',-1,'',0,-1),
	(407,85,'compuesto','Nombre unidad','Nombre unidad',26,'varchar','NA','',-1,'',1,0),
	(408,86,'idSuc','ID Sucursal','ID Sucursal',0,'auto_increment','NA','',0,'',0,-1),
	(409,86,'nombre','Nombre Sucursal','Nombre Sucursal',255,'varchar','NA','',0,'',1,0),
	(410,83,'idSuc','Sucursal emisora','Sucursal emisora',0,'varchar','NA','',0,'',0,0),
	(412,87,'idUnidadEquivalencia','ID Unidad Equivalencia','ID Unidad Equivalencia',0,'auto_increment','NA','',0,'',0,-1),
	(413,87,'idUni','ID Unidad Base','ID Unidad Base',0,'int','NA','',0,'',1,0),
	(414,87,'idCompuesto','ID Unidad Compuesta','ID Unidad Compuesta',0,'int','NA','',0,'',3,0),
	(415,87,'conversion','Conversion','Conversion',0,'varchar','NA','',0,'',2,0),
	(416,85,'conversion','Conversion','Conversion',0,'double','NA','',0,'',2,0),
	(417,88,'idPatron','ID Patron','ID Patron de medida',0,'auto_increment','NA','',0,'',0,-1),
	(418,88,'patron','Patron de medida','Patron de medida',20,'varchar','NA','',0,'',0,0),
	(419,80,'idUni','Unidad','Unidad',0,'varchar','NA','',0,'',5,0),
	(420,80,'utilidad','Utilidad (Porcentaje)','Utilidad (Porcentaje)',0,'double','NA','',0,'',4,0),
	(421,89,'idUnidad','ID Unidad V','ID Unidad V',0,'int','NA','',0,'',0,0),
	(422,89,'compuesto_descripcion','Compuesto Desc','Compuesto Desc',0,'int','NA','',0,'',1,0),
	(423,89,'conversion','Conversion','Conversion',0,'int','NA','',0,'',2,0),
	(424,85,'unidad','Unidad','Unidad',0,'int','NA','',0,'',3,0),
	(425,89,'unidad','Unidad','Unidad',0,'int','NA','',0,'',3,0),
	(496,105,'id','Id','Id',3,'auto_increment','NA','',-1,'',0,-1),
	(497,105,'titulo','Titulo','Titulo del Tipo de Poliza',30,'varchar','NA','',-1,'',1,0),
	(498,106,'id','Id','id',11,'auto_increment','NA','',-1,'',0,-1),
	(499,106,'idorganizacion','Id Organizacion','Id Organizacion',3,'int','NA','',0,'',1,0),
	(500,106,'idejercicio','idejercicio','idejercicio',2,'int','NA','',0,'',2,0),
	(501,106,'idperiodo','idperiodo','idperiodo',2,'int','NA','',0,'',3,0),
	(502,106,'idtipopoliza','idtipopoliza','idtipopoliza',5,'int','NA','',0,'',4,0),
	(503,106,'referencia','referencia','referencia',30,'varchar','NA','',0,'',5,0),
	(504,106,'concepto','concepto','concepto',30,'varchar','NA','',0,'',6,0),
	(505,106,'fecha','fecha','fecha',0,'date','NA','',0,'',7,0),
	(506,106,'fecha_creacion','fecha_creacion','fecha_creacion',0,'datetime','NA','',0,'',8,0),
	(507,106,'activo','activo','activo',1,'int','NA','',0,'',9,0),
	(508,106,'eliminado','eliminado','eliminado',1,'int','NA','',0,'',10,0),
	(509,107,'Id','Id','Id',11,'auto_increment','NA','',-1,'',0,-1),
	(510,107,'IdPoliza','IdPoliza','IdPoliza',11,'int','NA','',-1,'',1,0),
	(511,107,'NumMovto','NumMovto','NumMovto',3,'int','NA','',-1,'',2,0),
	(512,107,'IdSucursal','IdSucursal','IdSucursal',2,'int','NA','',-1,'',3,0),
	(513,107,'Cuenta','Cuenta','Cuenta',4,'int','NA','',-1,'',4,0),
	(514,107,'TipoMovto','TipoMovto','TipoMovto',5,'varchar','NA','',-1,'',5,0),
	(515,107,'Importe','Importe','Importe',0,'double','NA','',-1,'',6,0),
	(516,107,'Referencia','Referencia','Referencia',30,'varchar','NA','',-1,'',7,0),
	(517,107,'Concepto','Concepto','Concepto',30,'varchar','NA','',-1,'',8,0),
	(518,107,'Activo','Activo','Activo',1,'int','NA','',-1,'',9,0),
	(519,107,'FechaCreacion','FechaCreacion','FechaCreacion',0,'datetime','NA','',-1,'',10,0),
	(520,108,'Id','Id','Id',2,'auto_increment','NA','',-1,'',0,-1),
	(521,108,'NombreEjercicio','NombreEjercicio','NombreEjercicio',15,'varchar','NA','',-1,'',1,0),
	(522,109,'id','id','id',2,'auto_increment','NA','',-1,'',0,-1),
	(523,109,'IdOrganizacion','IdOrganizacion','IdOrganizacion',2,'int','NA','',-1,'',1,0),
	(524,109,'IdEjercicio','IdEjercicio','IdEjercicio',2,'int','NA','',-1,'',2,0),
	(525,109,'TipoCatalogo','TipoCatalogo','TipoCatalogo',3,'int','NA','',-1,'',3,0),
	(526,109,'Estructura','Estructura','Estructura',50,'varchar','NA','',-1,'',4,0),
	(527,109,'TipoValores','TipoValores','TipoValores',1,'varchar','NA','',-1,'',5,0),
	(528,109,'TipoNiveles','TipoNiveles','TipoNiveles',1,'varchar','NA','',-1,'',6,0),
	(529,109,'RFC','RFC','RFC',30,'varchar','NA','',-1,'',7,0),
	(530,109,'InicioEjercicio','InicioEjercicio','InicioEjercicio',0,'date','NA','',-1,'',8,0),
	(531,109,'FinEjercicio','FinEjercicio','FinEjercicio',0,'date','NA','',-1,'',9,0),
	(532,109,'TipoPeriodo','TipoPeriodo','TipoPeriodo',1,'varchar','NA','',-1,'',10,0),
	(533,109,'NumPeriodos','NumPeriodos','NumPeriodos',2,'int','NA','',-1,'',11,0),
	(534,109,'PeriodoActual','PeriodoActual','PeriodoActual',2,'int','NA','',-1,'',12,0),
	(535,109,'PeriodosAbiertos','PeriodosAbiertos','PeriodosAbiertos',1,'int','NA','',-1,'',13,0),
	(536,109,'EjercicioActual','EjercicioActual?','EjercicioActual?',1,'int','NA','',-1,'',14,0),
	(537,25,'idOrg','idOrg','idOrg',11,'int','NA','',-1,'',3,0),
	(538,110,'id','id','id',1,'auto_increment','NA','',0,'',0,-1),
	(539,110,'razonsocial','Razon social','Razon social',60,'varchar','NA','',0,'',0,0),
	(540,110,'rfc','RFC','RFC',20,'varchar','NA','',0,'',0,0),
	(541,111,'idCliente','idCliente','idCliente',0,'auto_increment','NA','',0,'',0,-1),
	(542,111,'nombre','Nombre','Nombre',100,'varchar','NA','',-1,'',0,0),
	(543,111,'apellido','Apellido','Apellido',100,'varchar','NA','',-1,'',0,0),
	(544,111,'email','Email','Email',100,'varchar','NA','',0,'',0,0),
	(545,111,'direccion','Direccion','Direccion',200,'varchar','NA','',0,'',0,0),
	(546,111,'direccion2','Direccion alterna','Direccion alterna',200,'varchar','NA','',0,'',0,0),
	(547,111,'telefono','Telefono','Telefono',10,'varchar','NA','',0,'',0,0),
	(548,111,'ciudad','Ciudad','Ciudad',100,'varchar','NA','',0,'',0,0),
	(549,111,'estado','Estado','Estado',100,'varchar','NA','',0,'',0,0),
	(550,111,'cp','CP','CP',10,'varchar','NA','',0,'',0,0),
	(551,111,'pais','Pais','Pais',100,'varchar','NA','',0,'',0,0),
	(552,112,'idCliente','idCliente','idCliente',0,'int','NA','',0,'',0,0),
	(553,112,'idFactura','idFactura','idFactura',0,'auto_increment','NA','',0,'',0,-1),
	(554,112,'rfc','RFC','RFC',15,'varchar','NA','',-1,'',0,0),
	(556,112,'razonSocial','Razon social','Razon social',100,'varchar','NA','',-1,'',0,0),
	(557,112,'regimeFiscal','Regimen fiscal','Regimen fiscal',100,'varchar','NA','',0,'',0,0),
	(558,112,'calle','Calle','Calle',200,'varchar','NA','',0,'',0,0),
	(559,112,'correos','Correos','Correos',400,'varchar','NA','',0,'',0,0),
	(560,112,'numExt','Numext','Numext',5,'varchar','NA','',0,'',0,0),
	(561,112,'colonia','Colonia','Colonia',45,'varchar','NA','',0,'',0,0),
	(562,112,'municipio','Municipio','Municipio',45,'varchar','NA','',0,'',0,0),
	(563,112,'ciudad','Ciudad','Ciudad',45,'varchar','NA','',0,'',0,0),
	(564,112,'cp','CP','CP',11,'varchar','NA','',0,'',0,0),
	(565,112,'estado','Estado','Estado',45,'varchar','NA','',0,'',0,0),
	(566,112,'pais','Pais','Pais',45,'varchar','NA','',-1,'',0,0),
	(567,112,'utilizada','Utilizada','Utilizada',1,'int','0','',0,'',0,0),
	(568,112,'borrado','Borrado','Borrado',1,'int','0','',0,'',0,0),
	(569,114,'id','idTargeta','idTargeta',0,'auto_increment','NA','',0,'',0,-1),
	(570,114,'numTargeta','Numero de targeta de regalo','Numero de targeta de regalo',25,'varchar','NA','',-1,'',0,0),
	(571,114,'value','Valor','Valor',15,'double','NA','',-1,'',0,0),
	(572,114,'deleted','Deleted','Deleted',1,'int','NA','',0,'',0,0),
	(573,115,'idcontacto','Id. Contacto','Identificador númerico del contacto',0,'auto_increment','NA','',-1,'',0,-1),
	(574,115,'nombre','Nombre','Nombre del contacto',100,'varchar','NA','',-1,'',10,0),
	(575,115,'telefono','Teléfono','Teléfono del contacto',16,'varchar','NA','',-1,'(999) 999-999-99 ',20,0),
	(576,115,'campo1','Campo 1','Campo para sumar',0,'double','NA','',0,'$.0000',40,0),
	(577,115,'campo2','Campo 2','Campo para sumar',0,'double','NA','',0,'$.0000',50,0),
	(578,115,'camporesultado','Campo Resultado','Campo Resultado',0,'double','NA','({campo1}+5) + ({campo2}+5)',0,'$.0000',60,0),
	(579,115,'idestado','Estado','Estado donde vive el contacto.',0,'int','NA','',-1,'',80,0),
	(580,115,'idmunicipio','Municipio','Municipio donde vive el contacto',0,'int','NA','',-1,'',90,0),
	(581,116,'idlinea','Id. Linea','Linea de control de filas',0,'auto_increment','NA','',-1,'',0,-1),
	(582,116,'idcontacto','Id. Contacto','Campo de conexión con títulos',0,'int','NA','',0,'',10,0),
	(583,116,'fecha','Fecha','Fecha para la cita',0,'datetime','NA','',-1,'',20,0),
	(584,116,'asunto','Asunto','Asunto a tratar',200,'varchar','NA','',-1,'',30,0),
	(585,116,'campoX','Campo X','Campo X',0,'double','NA','',0,'$.0000',40,0),
	(586,117,'idempleado','idempleado','idempleado',11,'int','NA','',0,'',0,0),
	(587,117,'usuario','usuario','usuario',100,'varchar','NA','',-1,'',0,0),
	(588,117,'clave','clave','clave',100,'varchar','NA','',-1,'',0,0),
	(666,135,'id','Id','Id',0,'auto_increment','NA','',-1,'',0,-1),
	(667,135,'nombre','Nombre del cliente','Nombre del cliente',100,'varchar','NA','',-1,'',1,0),
	(669,135,'direccion','Dirección','Dirección',200,'varchar','NA','',0,'',2,0),
	(670,135,'colonia','Colonia','Colonia',100,'varchar','NA','',0,'',3,0),
	(676,135,'email','Email','Email',100,'varchar','NA','',0,'',7,0),
	(677,135,'celular','Celular','Celular',15,'varchar','NA','',0,'',8,0),
	(679,135,'cp','Código postal','Código postal',5,'int','NA','',0,'99999',4,0),
	(680,135,'idEstado','Estado','Estado',0,'int','NA','',0,'',5,0),
	(681,135,'idMunicipio','Municipio','Municipio',0,'int','NA','',0,'',6,0),
	(901,169,'idcxc','id','id',11,'auto_increment','NA','',0,'',0,-1),
	(902,169,'fechacargo','Fecha','Fecha',0,'datetime_seg_hr','NA','',0,'',0,0),
	(903,169,'fechavencimiento','fecha Vencimiento','fecha Vencimiento',0,'datetime_seg_hr','NA','',0,'',0,0),
	(904,169,'iddeudor','Deudor','Deudor',11,'int','NA','',0,'',0,0),
	(905,169,'idconcepto','Concepto','idconcepto',11,'int','NA','',0,'',0,0),
	(906,169,'descripcion','Descripcion','Descripcion',150,'varchar','NA','',0,'',0,0),
	(907,169,'saldoinicial','Saldo Inicial','Saldo Inicial',0,'double','NA','',0,'',0,0),
	(908,169,'abonos','Abonos','Abonos',0,'double','NA','[pago]',0,'$.00',0,0),
	(909,169,'saldoactual','Saldo Actual','Saldo Actual',0,'double','NA','{saldoinicial}-{abonos}',0,'$.00',0,0),
	(910,169,'iddocumento','iddocumento','iddocumento',11,'int','NA','',0,'',0,0),
	(911,169,'foliodocumento','Folio Documento','Folio Documento',11,'int','NA','',0,'',0,0),
	(912,169,'activo','activo','activo',1,'boolean','NA','',0,'',0,0),
	(913,170,'idcxc','id','id',11,'int','NA','',0,'',0,0),
	(914,170,'idpago','idpago','idpago',0,'auto_increment','NA','',0,'',0,-1),
	(915,170,'fechapago','Fecha Pago','Fecha Pago',0,'datetime','NA','',0,'',0,0),
	(916,170,'pago','pago','pago',0,'double','NA','',0,'$.00',0,0),
	(917,170,'idformapago','Forma Pago','idformapago',11,'int','NA','',0,'',0,0),
	(918,170,'referencia','Referencia','Referencia',100,'varchar','NA','',0,'',0,0),
	(919,170,'idcuenta','idcuenta','idcuenta',11,'int','NA','',0,'',0,0),
	(920,170,'observaciones','Observaciones','Observaciones',255,'varchar','NA','',0,'',0,0),
	(921,171,'idcxp','idcxp','idcxp',0,'auto_increment','NA','',0,'',0,-1),
	(922,171,'fechacargo','Fecha Cargo','Fecha Cargo',0,'datetime_seg_hr','NA','',0,'',0,0),
	(923,171,'fechavencimiento','Fecha Vencimiento','Fecha Vencimiento',0,'datetime_seg_hr','NA','',0,'',0,0),
	(924,171,'idacreedor','Acreedor','Acreedor',0,'int','NA','',0,'',0,0),
	(925,171,'idconcepto','Concepto','Concepto',0,'int','NA','',0,'',0,0),
	(926,171,'descripcion','Descripcion','Descripcion',150,'varchar','NA','',0,'',0,0),
	(927,171,'saldoinicial','Saldo Inicial','Saldo Inicial',0,'double','NA','',0,'',0,0),
	(928,171,'abonos','Abonos','Abonos',0,'double','NA','[pago]',0,'$.00',0,0),
	(929,171,'saldoactual','Saldo Actual','Saldo Actual',0,'double','NA','{saldoinicial}-{abonos}',0,'$.00',0,0),
	(930,171,'iddocumento','Documento','Documento',11,'int','NA','',0,'',0,0),
	(931,171,'foliodocumento','Folio Documento','Folio Documento',11,'int','NA','',0,'',0,0),
	(932,171,'activo','activo','activo',1,'boolean','NA','',0,'',0,0),
	(933,172,'idcxp','idcxp','idcxp',11,'int','NA','',0,'',0,0),
	(934,172,'idpago','id','idpago',0,'auto_increment','NA','',0,'',0,-1),
	(935,172,'fechapago','Fecha Pago','Fecha Pago',0,'datetime','NA','',0,'',0,0),
	(936,172,'referencia','Referencia','referencia',100,'varchar','NA','',0,'',0,0),
	(937,172,'pago','pago','pago',0,'double','NA','',0,'',0,0),
	(938,172,'idformapago','Forma P ago','Forma Pago',11,'int','NA','',0,'',0,0),
	(939,172,'idcuenta','Cuenta','Cuenta',50,'varchar','NA','',0,'',0,0),
	(940,172,'observaciones','Observaciones','Observaciones',255,'varchar','NA','',0,'',0,0),
	(941,173,'iddeudor','iddeudor','iddeudor',0,'auto_increment','NA','',0,'',0,-1),
	(942,173,'nombredeudor','Nombre Deudor','Nombre Deudor',100,'varchar','NA','',0,'',0,0),
	(943,173,'idclaveorigen','Clave Origen','idclaveorigen',11,'int','NA','',0,'',0,0),
	(944,173,'idestructura','Estructura','idestructura',11,'int','NA','',0,'',0,0),
	(946,174,'idacreedor','id','idacreedor',0,'auto_increment','NA','',0,'',0,-1),
	(947,174,'nombreacreedor','Acreedor','Acreedor',100,'varchar','NA','',0,'',0,0),
	(948,174,'idclaveorigen','Clave Origen','Clave Origen',11,'int','NA','',0,'',0,0),
	(950,174,'idestructura','Estructura','Estructura',11,'int','NA','',0,'',0,0),
	(951,175,'transport','Chofer','Chofer',0,'int','NA','',-1,'',0,0),
	(952,175,'monto','Monto a pagar','Monto a pagar',0,'double','NA','',-1,'',0,0),
	(953,175,'efectuado','Pago efectuado','Pago efectuado',0,'boolean','0','',-1,'',0,0),
	(954,175,'fecha_pago','Fecha del pago','Fecha del pago',0,'datetime','NA','',0,'',0,0),
	(955,176,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(956,176,'contacto','Contacto','Contacto',0,'int','NA','',0,'',0,0),
	(957,177,'id','id','id',0,'auto_increment','NA','',0,'',0,-1),
	(958,176,'liquidacion','Liquidacion','Liquidacion',0,'double','NA','',0,'',0,0),
	(959,177,'tipo_combustible','Tipo de combustible','Tipo de combustible',50,'varchar','NA','',-1,'',0,0),
	(960,177,'litros','Litros','Litros',0,'int','NA','',-1,'',0,0),
	(961,177,'monto','Monto total','Monto total',0,'double','NA','',-1,'',0,0),
	(962,177,'fecha_caducidad','Fecha de caducidad','Fecha de caducidad',0,'datetime','NA','',-1,'',0,0),
	(963,178,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(964,178,'id_sale','Venta','Venta',0,'int','NA','',0,'',0,0),
	(965,178,'fecha','Fecha','Fecha',0,'datetime','NA','',0,'',0,0),
	(966,178,'id_cliente','Cliente','Cliente',0,'int','NA','',0,'',0,0),
	(967,178,'monto','Monto','Monto',0,'double','NA','',0,'',0,0),
	(968,178,'facturado','Facturado','Facturado',0,'boolean','0','',0,'',0,0),
	(969,130,'telefono2','Telefono 2','telefono 2',30,'varchar','NA','',0,'',0,0),
	(970,130,'tipo_persona','Tipo Persona','',2,'int','4','',0,'',0,0),
	(971,181,'id','id','',0,'auto_increment','NA','',0,'',0,0),
	(972,181,'tipo','Tipo Persona','',50,'varchar','NA','',0,'',0,0),
	(973,130,'idMunicipio','Municipio','',11,'int','NA','',0,'',0,0),
	(974,128,'idMunicipio','Municipio','municipio',100,'varchar','NA','',0,'',71,0),
	(975,128,'tipo_persona','Tipo Persona','Tipo Persona',2,'int','5','',0,'',199,0),
	(976,128,'borrado','borrado','borrado',1,'boolean','0','',0,'',200,0),
	(977,180,'vientosfunciona2','vientos funciona2','',10,'varchar','NA','',0,'',0,0),
	(978,113,'id','idregistro','ID. Registro',0,'auto_increment','NA','',-1,'',0,-1),
	(979,113,'dato','Dato','Dato',100,'varchar','NA','',0,'',1,0),
	(980,113,'idempleado','Empleado','Empleado',0,'int','NA','',0,'',10,0),
	(981,183,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(982,183,'estado','Estado','Estado',0,'int','0','',0,'',0,0),
	(983,183,'poblacion','Poblacion','Poblacion',150,'varchar','NA','',0,'',0,0),
	(984,184,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(985,184,'iva','IVA','IVA',0,'int','NA','',0,'',0,0),
	(986,184,'retencion','Retencion','Retencion',0,'int','NA','',0,'',0,0),
	(987,185,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(988,185,'combustible','Combustible','Combustible',0,'int','NA','',0,'',0,0),
	(989,185,'precio','Precio','Precio',0,'int','NA','',0,'',0,0),
	(990,185,'fechaInicio','Fecha Inicio','Fecha Inicio',0,'datetime','NA','',0,'',0,0),
	(991,185,'fechaFin','Fecha Fin','Fecha Fin',0,'datetime','NA','',0,'',0,0),
	(992,186,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(993,186,'producto','Producto','Producto',150,'varchar','NA','',0,'',0,0),
	(994,187,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(995,187,'nEconomico','Numero Economico','Numero Economico',0,'int','NA','',0,'',0,0),
	(996,187,'placas','Placas','Placas',12,'varchar','NA','',0,'',0,0),
	(997,187,'marca','Marca','Marca',50,'varchar','NA','',0,'',0,0),
	(998,187,'unidad','Unidad','Unidad',0,'int','NA','',0,'',0,0),
	(999,187,'modelo','Modelo','Modelo',4,'int','NA','',0,'',0,0),
	(1000,187,'color','Color','Color',30,'varchar','NA','',0,'',0,0),
	(1001,187,'combustible','Combustible','Combustible',0,'int','NA','',0,'',0,0),
	(1002,187,'ttTanque','Tamaño De Tanque Tanque','Tamaño De Tanque Tanque',0,'int','NA','',0,'',0,0),
	(1003,187,'rolTanque','Rendimiento Optimo Foraneo Tanque','Rendimiento Optimo Foraneo Tanque',0,'int','NA','',0,'',0,0),
	(1004,187,'rofTanque','Rendimiento Optimo Local Tanque','Rendimiento Optimo Local Tanque',0,'int','NA','',0,'',0,0),
	(1005,187,'ttThermo','Tamaño De Tanque Thermo','Tamaño De Tanque Thermo',0,'int','NA','',0,'',0,0),
	(1006,187,'rofThermo','Rendimiento Optimo Foraneo Thermo','Rendimiento Optimo Foraneo Thermo',0,'int','NA','',0,'',0,0),
	(1007,187,'rolThermo','Rendimiento Optimo Local Thermo','Rendimiento Optimo Local Thermo',0,'int','NA','',0,'',0,0),
	(1008,187,'capacidadU','Capacidad Unidad','Capacidad Unidad',0,'int','NA','',0,'',0,0),
	(1009,187,'tUnidad','Tipo De Unidad','Tipo De Unidad',0,'int','NA','',0,'',0,0),
	(1010,187,'uPropiedad','Unidad Propiedad','Unidad Propiedad',0,'int','NA','',0,'',0,0),
	(1011,187,'fAdquisicion','Fecha Adquisicion','Fecha Adquisicion',0,'datetime','NA','',0,'',0,0),
	(1012,187,'observaciones','Observaciones','Observaciones',250,'varchar','NA','',0,'',0,0),
	(1013,188,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1014,188,'combustible','Combustible','Combustible',100,'varchar','NA','',0,'',0,0),
	(1015,189,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1016,189,'marca','Marca','Marca',100,'varchar','NA','',0,'',0,0),
	(1017,190,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1018,190,'unidad','Unidad','Unidad',100,'varchar','NA','',0,'',0,0),
	(1019,191,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1020,191,'capacidadU','Capacidad Unidad','Capacidad Unidad',150,'varchar','NA','',0,'',0,0),
	(1021,192,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1022,192,'del','Del','Del',0,'datetime','NA','',0,'',1,0),
	(1023,192,'al','Al','Al',0,'datetime','NA','',0,'',2,0),
	(1024,192,'nombre','Cliente','Nombre',0,'int','NA','',0,'',3,0),
	(1025,192,'toperador','Tipo Operador','Tipo Operador',0,'int','NA','',0,'',4,0),
	(1026,192,'operador','Operador','Operador',0,'int','NA','',0,'',5,0),
	(1027,192,'nEconomico','Unidad','Numero Economico',0,'int','NA','',0,'',6,0),
	(1028,193,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1029,193,'tOperador','Tipo Operador','Tipo Operador',100,'varchar','NA','',0,'',0,0),
	(1030,194,'idAlmacen','ID','ID',0,'auto_increment','NA','',-1,'',0,-1),
	(1031,194,'nombre','Nombre','Nombre',0,'varchar','NA','',0,'',1,0),
	(1032,194,'direccion','Direccion','Direccion',255,'varchar','NA','',0,'',2,0),
	(1033,194,'idEstado','Estado','Estado',0,'int','NA','',-1,'',3,0),
	(1034,194,'idmunicipio','Municipio','Municipio',0,'int','NA','',-1,'',4,0),
	(1035,194,'cp','C.P.','Código postal',0,'varchar','NA','',0,'',5,0),
	(1036,194,'tel_contacto','Telefono contacto','Telefono de contacto',15,'varchar','NA','',0,'',6,0),
	(1037,194,'contacto','Contacto','Contacto',128,'varchar','NA','',0,'',7,0),
	(1038,195,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1039,195,'tUnidad','Tipo Unidad','Tipo Unidad',50,'varchar','NA','',0,'',0,0),
	(1047,196,'id','ID Tarjeta regalo','ID Tarjeta regalo',0,'auto_increment','NA','',-1,'',0,-1),
	(1048,196,'numero','Numero','Numero',0,'int','NA','',0,'',1,0),
	(1049,196,'valor','Valor','Valor',0,'double','NA','',0,'',2,0),
	(1050,197,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1052,197,'uPropiedad','Unidad Propiedad','Unidad Propiedad',50,'varchar','NA','',0,'',0,0),
	(1053,198,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1054,198,'operador','Operador','Operdor',100,'varchar','NA','',0,'',0,0),
	(1055,86,'direccion','Direccion','Direccion',255,'varchar','NA','',0,'',2,0),
	(1056,86,'idEstado','Estado','Estado',0,'int','NA','',-1,'',3,0),
	(1057,86,'idMunicipio','Municipio','Municipio',0,'int','NA','',0,'',4,0),
	(1058,86,'cp','Codigo postal','Codigo postal',0,'int','NA','',0,'',5,0),
	(1059,86,'tel_contacto','Telefono contacto','Telefono contacto',15,'varchar','NA','',0,'',6,0),
	(1060,86,'contacto','Contacto','Contacto',255,'varchar','NA','',0,'',7,0),
	(1061,86,'idOrganizacion','Organizacion','Organizacion',0,'int','NA','',0,'',8,0),
	(1062,86,'idAlmacen','Almacen primario','Almacenr primario',0,'int','NA','',0,'',9,0),
	(1063,199,'idCxp','Cuenta por pagar','Cuenta por pagar',0,'auto_increment','NA','',-1,'',0,-1),
	(1064,199,'fechacargo','Fecha de cargo','Fecha de cargo',0,'date','NA','',0,'',1,0),
	(1065,199,'fechavencimiento','Fecha vencimiento','Fecha vencimiento',0,'date','NA','',0,'',2,0),
	(1066,199,'concepto','Concepto','Concepto',45,'varchar','NA','',0,'',0,0),
	(1067,199,'monto','Monto','Monto',0,'int','NA','',0,'',4,0),
	(1068,199,'saldoabonado','Saldo abonado','Saldo abonado',0,'int','NA','',0,'',5,0),
	(1069,199,'saldoactual','Saldo actual','Saldo actual',0,'int','NA','',0,'',6,0),
	(1070,199,'estatus','Estatus','Estatus',0,'boolean','NA','',0,'',7,0),
	(1071,200,'idCxppagos','ID pago','ID Pago',0,'auto_increment','NA','',-1,'',1,-1),
	(1072,200,'fecha','Fecha','Fecha',0,'date','NA','',0,'',2,0),
	(1073,200,'monto','Monto','Monto',0,'int','NA','',0,'',3,0),
	(1074,200,'saldoinicial','Saldo inicial','Saldo inicial',0,'int','NA','',0,'',4,0),
	(1075,200,'saldofinal','Saldo final','Saldo final',0,'int','NA','',0,'',5,0),
	(1076,200,'idCxp','ID Cuenta por pagar','ID Cuenta por pagar',0,'int','NA','',0,'',0,0),
	(1077,200,'idFormapago','Forma de pago','Forma de pago',0,'int','NA','',0,'',6,0),
	(1078,201,'idEstado','Estado','Estado',0,'int','NA','',0,'',50,0),
	(1079,201,'idMunicipio','Municipio','Municipio',0,'int','NA','',0,'',60,0),
	(1080,202,'idFormapago','ID','ID',0,'auto_increment','NA','',-1,'',0,-1),
	(1081,202,'nombre','Forma de pago','Forma de pago',45,'varchar','NA','',-1,'',1,0),
	(1082,201,'nombre','nombre','nombre',0,'varchar','NA','',0,'',10,0),
	(1083,201,'direccion','direccion','direccion',200,'varchar','NA','',0,'',20,0),
	(1084,201,'colonia','colonia','colonia',100,'varchar','NA','',0,'',30,0),
	(1087,201,'cp','cp','cp',50,'varchar','NA','',0,'',40,0),
	(1088,201,'contacto','contacto','contacto',200,'varchar','NA','',0,'',12,0),
	(1089,201,'id_tipo_fiscal','tipo fiscal','id_tipo_fiscal',2,'int','NA','',0,'',14,0),
	(1090,201,'rfc','rfc','rfc',30,'varchar','NA','',0,'',16,0),
	(1091,201,'telefono1','telefono1','telefono1',15,'varchar','NA','',0,'',70,0),
	(1092,201,'telefono2','telefono2','telefono2',15,'varchar','NA','',0,'',80,0),
	(1093,201,'tipo_persona','tipo_persona','tipo_persona',2,'int','2','',0,'',998,0),
	(1094,201,'borrado','borrado','borrado',1,'boolean','0','',0,'',999,0),
	(1095,201,'web','web','web',200,'varchar','NA','',0,'',90,0),
	(1096,201,'condiciones','condiciones','condiciones',300,'varchar','NA','',0,'',110,0),
	(1097,201,'revision_dias','revision_dias','revision_dias',11,'int','NA','',0,'',120,0),
	(1098,201,'horario','horario','horario',100,'varchar','NA','',0,'',130,0),
	(1099,201,'revision_lugar','revision_lugar','revision_lugar',200,'varchar','NA','',0,'',140,0),
	(1105,201,'dias_pago','dias_pago','dias_pago',11,'int','NA','',0,'',150,0),
	(1106,201,'horario_pago','horario_pago','horario_pago',100,'varchar','NA','',0,'',160,0),
	(1107,201,'forma_pago','forma_pago','forma_pago',100,'varchar','NA','',0,'',170,0),
	(1108,201,'convenio_pago','convenio_pago','convenio_pago',100,'varchar','NA','',0,'',180,0),
	(1109,201,'lugar_pago','lugar_pago','lugar_pago',100,'varchar','NA','',0,'',190,0),
	(1110,201,'contacto_pago','contacto_pago','contacto_pago',200,'varchar','NA','',0,'',200,0),
	(1111,201,'puesto_pago','puesto_pago','puesto_pago',100,'varchar','NA','',0,'',210,0),
	(1112,201,'telefono_pago','telefono_pago','telefono_pago',30,'varchar','NA','',0,'',220,0),
	(1113,201,'email_pago','email_pago','email_pago',100,'varchar','NA','',0,'',230,0),
	(1114,201,'docu_anticipo','docu_anticipo','docu_anticipo',1,'boolean','NA','',0,'',250,0),
	(1115,201,'nombre_corto','nombre_corto','nombre_corto',200,'varchar','NA','',0,'',260,0),
	(1116,201,'mercancia_tipo','mercancia_tipo','mercancia_tipo',100,'varchar','NA','',0,'',240,0),
	(1117,203,'id_tipo_fiscal','id','id',0,'auto_increment','NA','',0,'',0,0),
	(1118,203,'tipo','tipo','tipo',50,'varchar','NA','',0,'',0,0),
	(1119,204,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1120,204,'noliq','No. Liq','No. Liq',0,'int','NA','',0,'',0,0),
	(1121,204,'numeroOs','Numero OS','Numero OS',0,'int','NA','',0,'',0,0),
	(1122,204,'operador','Operador','Operador',0,'int','NA','',0,'',0,0),
	(1123,204,'unidad','Unidad','Unidad',0,'int','NA','',0,'',0,0),
	(1124,130,'id_tipo_fiscal','id_tipo_fiscal','id_tipo_fiscal',2,'int','NA','',0,'',90,0),
	(1125,128,'id_tipo_fiscal','tipo fiscal','id_tipo_fiscal',0,'int','NA','',0,'',30,0),
	(1127,128,'num_lic','numero de licencia','numero de licencia',11,'int','NA','',0,'',120,0),
	(1128,128,'nom_corto','nombre corto','nombre corto',250,'varchar','NA','',0,'',140,0),
	(1130,205,'id','id','id',0,'auto_increment','NA','',0,'',0,-1),
	(1131,205,'nombre','Nombre/Razón Social','Nombre / Razon Social',255,'varchar','NA','',-1,'',0,0),
	(1132,205,'contacto','contacto','contacto',255,'varchar','NA','',0,'',0,0),
	(1133,205,'rfc','rfc','rfc',14,'varchar','NA','',0,'',0,0),
	(1134,205,'idEstado','Estado','Estado',100,'varchar','NA','',0,'',0,0),
	(1135,205,'direccion','direccion','direccion',200,'varchar','NA','',0,'',0,0),
	(1136,205,'colonia','Colonia/Zona','Colonia / Zona',200,'varchar','NA','',0,'',0,0),
	(1137,205,'cp','CP','Codigo Postal',0,'int','NA','',0,'9999999',0,0),
	(1138,205,'telefono1','Telefono1','Telefono 1',20,'varchar','NA','',0,'99-99-99-99-99-99',0,0),
	(1139,205,'telefono2','Telefono2','Telefono 2',20,'varchar','NA','',0,'99-99-99-99-99-99',0,0),
	(1140,205,'idMunicipio','Municipio','municipio',100,'varchar','NA','',0,'',0,0),
	(1141,205,'tipo_persona','Tipo Persona','Tipo Persona',2,'int','3','',0,'',0,0),
	(1142,205,'borrado','borrado','borrado',1,'boolean','0','',0,'',0,0),
	(1143,205,'id_tipo_fiscal','tipo fiscal','id_tipo_fiscal',0,'int','NA','',0,'',0,0),
	(1144,206,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1145,206,'nombre','Nombre','Nombre',45,'varchar','NA','',0,'',0,0),
	(1146,207,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1147,207,'noliq','No. Liq','No. Liq',0,'int','NA','',0,'',0,0),
	(1227,221,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1228,221,'serie','Serie','Serie',20,'varchar','A','',-1,'',0,0),
	(1229,221,'folio','Folio','Folio',0,'int','1','',-1,'',0,0),
	(1234,223,'id','Id','Id',11,'auto_increment','NA','',0,'',0,-1),
	(1235,223,'nombre','Cliente','Cliente',11,'int','NA','',-1,'',0,0),
	(1236,223,'rfc','RFC','RFC',15,'varchar','NA','',-1,'',0,0),
	(1237,223,'razon_social','Razon social','Razon social',100,'varchar','NA','',-1,'',0,0),
	(1238,223,'correo','Correo electronico','Correo electronico',100,'varchar','NA','',0,'',0,0),
	(1239,223,'pais','Pais','Pais',100,'varchar','NA','',-1,'',0,0),
	(1240,223,'regimen_fiscal','Regimen fiscal','Regimen fiscal',100,'varchar','NA','',-1,'',0,0),
	(1241,223,'domicilio','Domicilio','Domicilio',200,'varchar','NA','',-1,'',0,0),
	(1242,223,'num_ext','Numero exterior','Numero exterior',6,'varchar','NA','',-1,'',0,0),
	(1243,223,'cp','Codigo postal','Codigo postal',10,'varchar','NA','',-1,'',0,0),
	(1244,223,'colonia','Colonia','Colonia',100,'varchar','NA','',-1,'',0,0),
	(1245,223,'estado','Estado','Estado',11,'int','NA','',-1,'',0,0),
	(1246,223,'ciudad','Ciudad','Ciudad',100,'varchar','NA','',-1,'',0,0),
	(1247,223,'municipio','Municipio','Municipio',100,'varchar','NA','',-1,'',0,0),
	(1248,224,'id','id ','',0,'auto_increment','NA','',-1,'',0,-1),
	(1249,224,'nombre','Nombre de impuesto','Nombre de impuesto',100,'varchar','NA','',-1,'',1,0),
	(1250,224,'valor','valor','valor del impuesto',0,'double','0.00','',-1,'0.00',2,0),
	(1251,135,'limite_credito','Límite de crédito','Límite de crédito',10,'double','NA','',0,'',9,0),
	(1252,135,'dias_credito','Días de crédito','Días de crédito',3,'int','NA','',0,'',10,0),
	(1253,47,'idSuc','Sucursal','Sucursal',0,'int','NA','',0,'',15,0),
	(1271,227,'id','ID','',11,'auto_increment','NA','',-1,'',0,-1),
	(1272,227,'tipoOperacion','Tipo Operacion','',255,'varchar','NA','',0,'',0,0),
	(1273,228,'id','ID','',11,'auto_increment','NA','',0,'',0,-1),
	(1274,228,'tipotercero','Tipo de Tercero','Tipo de Tercero',255,'varchar','NA','',0,'',0,0),
	(1275,78,'idtipotercero','Tipo Tercero','Tipo Tercero',1,'int','NA','',-1,'-1',28,0),
	(1276,78,'idtipoperacion','Tipo Operacion','Tipo Operacion',1,'int','NA','',-1,'-1',29,0),
	(1277,78,'curp','Curp','curp',100,'varchar','NA','',0,'',1,0),
	(1278,78,'cuenta','Cuenta','cuenta',100,'varchar','000-000-000','',0,'-1',27,0),
	(1279,78,'numidfiscal','Numero ID Fiscal','Numero ID Fiscal',100,'varchar','NA','',0,'',30,0),
	(1280,78,'PaisdeResidencia','Pais de Residencia','Pais de Residencia',11,'int','NA','',0,'-1',32,0),
	(1281,78,'nacionalidad','Nacionalidad','Nacionalidad',100,'varchar','Mexicana','',0,'-1',32,0),
	(1282,78,'ivaretenido','IVA Retenido %','IVA Retenido',100,'double','0.000000000','',0,'',33,0),
	(1283,78,'isretenido','ISR Retenido %','ISR Retenido',100,'double','0.000000000','',0,'',34,0),
	(1284,78,'idTasaPrvasumir','Asumir','asumir',11,'int','NA','',-1,'',35,0),
	(1285,230,'account_id','ID','',11,'int','NA','',-1,'',0,-1),
	(1286,230,'account_code','account_code','',100,'varchar','NA','',0,'',1,0),
	(1287,230,'manual_code','manual_code','',100,'varchar','NA','',0,'',2,0),
	(1288,230,'description','description','',100,'varchar','NA','',-1,'',3,0),
	(1289,230,'sec_desc','sec_desc','',100,'varchar','NA','',-1,'',4,0),
	(1290,230,'account_type','account_type','',11,'int','NA','',-1,'',5,0),
	(1291,230,'status','status','',1,'int','NA','',0,'',6,0),
	(1292,230,'main_account','main_account','',11,'int','NA','',0,'',7,0),
	(1293,230,'cash_flow','cash_flow','',1,'int','NA','',0,'',8,0),
	(1294,230,'reg_date','reg_date','',0,'date','NA','',0,'',9,0),
	(1295,230,'currency_id','currency_id','',1,'int','NA','',0,'',10,0),
	(1296,230,'group_dig','group_dig','',11,'int','NA','',-1,'',11,0),
	(1297,230,'id_sucursal','id_sucursal','',11,'int','NA','',0,'',12,0),
	(1298,230,'seg_neg_mov','seg_neg_mov','',11,'int','NA','',0,'',13,0),
	(1299,230,'affectable','affectable','',1,'int','NA','',-1,'',14,0),
	(1300,230,'mod_date','mod_date','',0,'date','NA','',0,'',15,0),
	(1301,230,'father_account_id','father_account_id','',11,'int','NA','',-1,'',16,0),
	(1302,230,'removable','removable','',1,'int','NA','',-1,'',17,0),
	(1303,230,'account_nature','account_nature','',11,'int','NA','',-1,'',18,0),
	(1304,230,'removed','removed','',1,'int','NA','',0,'',19,0),
	(1305,230,'main_father','main_father','',11,'int','NA','',0,'',20,0),
	(1307,232,'id_mesa','id_mesa','',0,'auto_increment','NA','',-1,'',0,-1),
	(1308,232,'idDep','localizacion de la mesa','idDep',0,'int','NA','',-1,'',0,0),
	(1309,232,'personas','numero de personas','personas',0,'int','NA','',0,'',0,0),
	(1312,234,'id','Id','Id',0,'auto_increment','NA','',-1,'',0,-1),
	(1313,234,'rfc','RFC','RFC',15,'varchar','NA','',-1,'',0,0),
	(1314,234,'regimen','Regimen Fiscal','Regimen Fiscal',45,'varchar','NA','',-1,'',0,0),
	(1315,234,'pais','Pais','Pais',45,'varchar','NA','',-1,'',0,0),
	(1316,234,'razon_social','Nombre o razon social','Nombre o razon social',200,'varchar','NA','',-1,'',0,0),
	(1317,234,'calle','Domicilio','Domicilio',200,'varchar','NA','',-1,'',0,0),
	(1318,234,'num_ext','Numero exterior','Numero exterior',45,'varchar','NA','',-1,'',0,0),
	(1319,234,'colonia','Colonia','Colonia',45,'varchar','NA','',-1,'',0,0),
	(1320,234,'ciudad','Ciudad','Ciudad',45,'varchar','NA','',-1,'',0,0),
	(1321,234,'municipio','Municipio','Municipio',45,'varchar','NA','',-1,'',0,0),
	(1322,234,'estado','Estado','Estado',45,'varchar','NA','',-1,'',0,0),
	(1323,234,'cp','CP','CP',45,'varchar','NA','',-1,'',0,0),
	(1329,234,'cer','Certificado (.cer)','Certificado (.cer)',200,'archivo','NA','',0,'',0,0),
	(1330,234,'llave','Llave (.key)','Llave (.key)',200,'archivo','NA','',0,'',0,0),
	(1331,234,'clave','Clave','Clave',45,'varchar','NA','',-1,'',0,0),
	(1332,236,'id','Id','Id',0,'auto_increment','NA','',-1,'',0,-1),
	(1333,236,'regimen','Regimen Fiscal','Regimen Fiscal',45,'varchar','NA','',-1,'',0,0),
	(1336,78,'idtipoiva','Tipo IVA ','Tipo IVA ',11,'int','NA','',0,'-1',36,0),
	(1342,242,'id','Id','Id',0,'auto_increment','NA','',0,'',0,-1),
	(1343,242,'puerto','Puerto','Puerto',5,'varchar','NA','',-1,'',0,0),
	(1344,242,'baudios','Baudios','Baudios',0,'int','0','',-1,'',0,0),
	(1345,242,'paridad','Paridad','Paridad',5,'varchar','0','',-1,'',0,0),
	(1346,242,'bstop','Bstop','Bstop',0,'int','0','',-1,'',0,0),
	(1347,242,'bits','Bits','Bits',0,'int','0','',-1,'',0,0),
	(1350,85,'orden','orden','orden',0,'int','NA','',0,'',4,0),
	(1351,242,'bascula','Bascula','Bascula',0,'boolean','NA','',0,'',0,0),
	(1352,78,'nombrextranjero','Nombre del Extranjero','Nombre Extranjero',255,'varchar','NA','',0,'',31,0),
	(1353,244,'id','ID','ID',0,'auto_increment','NA','',0,'',0,-1),
	(1354,244,'grupo','Nombre del grupo','Nombre del grupo',150,'varchar','NA','',-1,'',0,0),
	(1357,245,'id','ID','ID',0,'auto_increment','NA','',0,'',0,-1),
	(1358,245,'calle','Calle','Calle',200,'varchar','NA','',-1,'',0,0),
	(1359,245,'num_ext','Numero exterior','Numero exterior',10,'varchar','NA','',-1,'',0,0),
	(1360,245,'idEstado','Estado','Estado',0,'int','NA','',-1,'',0,0),
	(1361,245,'municipio','Municipio','Municipio',0,'int','NA','',-1,'',0,0),
	(1362,245,'cp','Codigo Postal','Codigo Postal',10,'varchar','NA','',-1,'',0,0),
	(1363,246,'id','id','id',0,'auto_increment','NA','',0,'',0,-1),
	(1364,246,'transporte','transporte','transporte',50,'varchar','NA','',-1,'',0,0),
	(1365,246,'placas','placas','placas',10,'varchar','NA','',-1,'',0,0),
	(1366,247,'idtipo','idtipo','idtipo',0,'auto_increment','NA','',0,'',0,-1),
	(1367,247,'tipo','tipo','tipo',20,'varchar','NA','',-1,'',0,0),
	(1368,246,'tipo','tipo','tipo',0,'int','NA','',0,'',0,0),
	(1369,248,'idcapacidad','idcapacidad','idcapacidad',0,'auto_increment','NA','',0,'',0,-1),
	(1370,248,'capacidad','capacidad','capacidad',30,'varchar','NA','',-1,'',0,0),
	(1371,246,'idcapacidad','Capacidad','Capacidad',0,'int','NA','',0,'',0,0),
	(1372,249,'idfacturacion','idfacturacion','idfacturacion',11,'auto_increment','NA','',0,'',0,-1),
	(1373,249,'id','Cliente','Cliente',11,'int','NA','',-1,'',0,0),
	(1374,249,'rfc','RFC','RFC',15,'varchar','NA','',-1,'',0,0),
	(1375,249,'razon_social','Razon social','Razon social',100,'varchar','NA','',-1,'',0,0),
	(1376,249,'correo','Correo','Correo',100,'varchar','NA','',-1,'',0,0),
	(1377,249,'pais','Pais','Pais',100,'varchar','NA','',-1,'',0,0),
	(1378,249,'regimen_fiscal','Regimen fiscal','Regimen fiscal',100,'varchar','NA','',0,'',0,0),
	(1379,249,'domicilio','Domicilio','Domcilio',200,'varchar','NA','',-1,'',0,0),
	(1380,249,'num_ext','Numero exterior  interior','Numero exterior  interior',20,'varchar','NA','',-1,'',0,0),
	(1381,249,'cp','Codigo postal','Codigo postal',10,'varchar','NA','',-1,'',0,0),
	(1382,249,'colonia','Colonia','Colonia',100,'varchar','NA','',-1,'',0,0),
	(1383,249,'idestado','Estado','Estado',11,'int','NA','',0,'',0,0),
	(1384,249,'ciudad','Ciudad','Ciudad',100,'varchar','NA','',-1,'',0,0),
	(1385,249,'municipio','Municipio','Municipio',100,'varchar','NA','',-1,'',0,0),
	(1388,251,'idSuc','Id Segmento','ID del Segmento de Negocio',4,'auto_increment','NA','',-1,'',1,-1),
	(1389,251,'nombre','Nombre del Segmento','Nombre del Segmento de Negocio',30,'varchar','NA','',-1,'',2,0),
	(1390,252,'id','ID','id',11,'auto_increment','NA','',-1,'',0,-1),
	(1391,252,'mes','Mes','mes',100,'varchar','NA','',-1,'',0,0),
	(1392,252,'cargo','Cargo','Cargo del mes',100,'double','NA','',-1,'0.00',0,0),
	(1394,252,'idejercicio','Ejercicio','Ejercicio',11,'int','NA','',-1,'',0,0),
	(1395,253,'id','ID','ID',11,'auto_increment','NA','',-1,'',0,-1),
	(1396,253,'mes','mes','mes',100,'varchar','NA','',-1,'',0,0),
	(1397,252,'derivada_ajuste','Monto acreditable derivado del ajuste','Monto acreditable derivado del ajuste',100,'double','NA','',0,'0.00',0,0),
	(1398,252,'cantidadreintegrarse','Cantidad actualizada a reintegrarse','Cantidad actualizada a reintegrarse',100,'double','NA','',0,'0.00',0,0),
	(1399,252,'ivaretenido','IVA retenido al contribuyente','IVA retenido al contribuyente',100,'double','NA','',0,'0.00',0,0),
	(1400,78,'idIETU','Tipo IETU','Tipo IETU',11,'int','NA','',0,'-1',37,0),
	(1401,254,'id','ID','ID',11,'auto_increment','NA','',0,'',0,-1),
	(1402,254,'nombre','Nombre','Nombre',100,'varchar','NA','',0,'',0,0),
	(1409,256,'coin_id','ID','ID',11,'auto_increment','NA','',-1,'',0,-1),
	(1410,256,'description','Descripcion','Descripcion',100,'varchar','NA','',0,'',0,0),
	(1411,257,'id','ID','ID',11,'auto_increment','NA','',-1,'',0,-1),
	(1412,257,'moneda','Moneda','Moneda',11,'int','NA','',-1,'',1,0),
	(1413,257,'fecha','Fecha','Fecha',0,'date','NA','',-1,'',2,0),
	(1414,257,'tipo','Tipo','Tipo',50,'varchar','Contable','',-1,'',3,0),
	(1415,257,'tipo_cambio','Tipo de Cambio','Tipo de Cambio',100,'double','NA','',-1,'',4,0),
	(1464,135,'cuenta','Cuenta','Cuenta',5,'int','0','',0,'',14,0),
	(1479,274,'id','ID','ID',11,'auto_increment','NA','',0,'',0,-1),
	(1480,274,'Clave','Clave','Clave',50,'varchar','NA','',-1,'',1,0),
	(1481,274,'nombre','Nombre Corto','Nombre Corto',100,'varchar','NA','',-1,'',2,0),
	(1482,275,'id','ID','ID',11,'auto_increment','NA','',0,'',0,-1),
	(1483,275,'idbanco','Banco','Banco',11,'int','NA','',-1,'',2,0),
	(1484,275,'idPrv','Proveedor','Proveedor',11,'int','NA','',-1,'',1,0),
	(1485,275,'numCT','No. Cuenta Bancariatarjeta','No. Cuenta Bancariatarjeta',100,'varchar','NA','',-1,'',3,0),
	(1503,78,'diascredito','Dias de Credito','Dias de Credito',11,'int','0','',0,'',9,0),
	(1504,280,'idbancaria','ID','ID',11,'auto_increment','NA','',-1,'',1,-1),
	(1505,280,'cuenta','Cuenta','Cuenta',20,'varchar','NA','',-1,'',2,0),
	(1506,280,'idbanco','Banco','Banco',11,'int','NA','',-1,'-1',3,0),
	(1507,280,'idtipoc','Tipo de cuenta','Tipo de cuenta',11,'int','NA','',0,'-1',4,0),
	(1508,280,'coin_id','Moneda','Moneda',11,'int','NA','',-1,'-1',5,0),
	(1509,280,'titular','Titular o titulares de la cuenta','Titular o titulares de la cuenta',255,'varchar','NA','',-1,'',6,0),
	(1510,280,'account_id','Cuenta contable','Cuenta contable',11,'int','NA','',-1,'-1',7,0),
	(1511,281,'idtipoc','ID','ID',11,'auto_increment','NA','',0,'',0,-1),
	(1512,281,'tipo','Tipo de cuenta','Tipo de cuenta',40,'varchar','NA','',-1,'',0,0),
	(1527,280,'fechainicial','Fecha Inicial','Fecha Inicial',0,'datetime','NA','',-1,'',8,0),
	(1528,280,'saldoinicial','Saldo de apertura','Saldo de apertura',0,'double','NA','',-1,'',9,0),
	(1533,135,'rfc','RFC','RFC',20,'varchar','NA','',-1,'',0,0),
	(1534,251,'clave','Clave del Segmento','Clave del Segmento de Negocio',10,'varchar','NA','',-1,'',3,0),
	(1539,285,'id','ID','ID',11,'auto_increment','NA','',0,'',0,-1),
	(1540,285,'codigo','Codigo','Codigo',100,'varchar','NA','',0,'',1,0),
	(1541,285,'nombreclasificador','Nombre del clasificador','Nombre del clasificador',200,'varchar','NA','',-1,'',2,0),
	(1543,285,'idtipo','Tipo','Tipo',11,'int','NA','',0,'-1',4,0),
	(1544,285,'account_id','Cuenta contable','Cuenta contable',11,'int','NA','',0,'-1',5,0),
	(1623,298,'idtipo','ID','ID',11,'auto_increment','NA','',-1,'',0,-1),
	(1624,298,'tipo','Tipo','Tipo',100,'varchar','NA','',0,'',1,0),
	(1625,78,'idtipo','Tipo','Tipo',11,'int','NA','',0,'-1',26,0),
	(1627,232,'nombre','Nombre','Ubicacion, lugar, area de la mesa',50,'varchar','NA','',0,'',0,0),
	(1628,299,'idEstadoCivil','ID','ID',0,'auto_increment','NA','',-1,'',0,-1),
	(1629,299,'estadoCivil','Estado Civil','Estado Civil',200,'varchar','NA','',-1,'',1,0),
	(1630,300,'idsexo','ID','ID',0,'auto_increment','NA','',-1,'',0,-1),
	(1631,300,'sexo','Sexo','Sexo',100,'varchar','NA','',-1,'',1,0),
	(1632,301,'idEmpleado','ID','ID',0,'auto_increment','NA','',-1,'',0,-1),
	(1633,301,'codigo','Codigo','Codigo',100,'varchar','NA','',-1,'',1,0),
	(1634,301,'fechaAlta','Fecha de Alta','Fecha de Alta',0,'date','NA','',-1,'',2,0),
	(1635,301,'apellidoPaterno','Apellido Paterno','Apellido Paterno',200,'varchar','NA','',0,'',3,0),
	(1636,301,'apellidoMaterno','Apellido Materno','Apellido Materno',200,'varchar','NA','',0,'',4,0),
	(1637,301,'nombreEmpleado','Nombre','Nombre',200,'varchar','NA','',-1,'',5,0),
	(1638,301,'salario','Salario Diario','Salario Diario',0,'double','NA','',-1,'0.00',6,0),
	(1639,301,'idzona','Zona de Salario Minimo','Zona de Salario Minimo',11,'int','NA','',-1,'',7,0),
	(1640,301,'idFormapago','Forma de Pago','Forma de Pago',11,'int','NA','',-1,'',8,0),
	(1641,301,'email','Correo electronico','Correo electronico',100,'varchar','NA','',0,'',9,0),
	(1642,301,'nss','N.S.S.','N.S.S.',100,'varchar','NA','',-1,'',10,0),
	(1643,301,'idEstadoCivil','Estado Civil','Estado Civil',11,'int','NA','',-1,'',11,0),
	(1644,301,'idsexo','Sexo','Sexo',11,'int','NA','',-1,'',12,0),
	(1645,301,'fechaNacimiento','Fecha de Nacimiento','Fecha de Nacimiento',0,'date','NA','',-1,'',13,0),
	(1646,301,'idestado','Entidad Federativa','Entidad Federativa',11,'int','NA','',-1,'',14,0),
	(1647,301,'idmunicipio','Ciudad de Nacimiento','Ciudad de Nacimiento',11,'int','NA','',-1,'',15,0),
	(1648,301,'rfc','RFC','RFC',30,'varchar','XAXX010101000','',-1,'',16,0),
	(1649,301,'curp','CURP','CURP',50,'varchar','NA','',-1,'',17,0),
	(1650,301,'direccion','Direccion','Direccion',100,'varchar','NA','',0,'',18,0),
	(1651,301,'poblacion','Poblacion','Poblacion',100,'varchar','NA','',0,'',19,0),
	(1652,301,'idestadosat','Estado','Estado',11,'int','NA','',0,'',20,0),
	(1653,301,'cp','Codigo Postal','Codigo Postal',11,'int','NA','',0,'',21,0),
	(1654,301,'telefono','Telefono','Telefono',15,'varchar','NA','',-1,'',22,0),
	(1655,301,'idbanco','Banco para pago Electronico','Banco para pago Electronico',11,'int','NA','',-1,'',23,0),
	(1656,301,'numeroCuenta','Numero de Cuenta para Pago ','Numero de Cuenta para Pago ',100,'varchar','NA','',-1,'',24,0),
	(1657,301,'claveinterbancaria','Clave Interbancaria','Clave Interbancaria',100,'varchar','NA','',-1,'',25,0),
	(1658,302,'idzona','ID','ID',11,'auto_increment','NA','',-1,'',0,-1),
	(1659,302,'zonasalario','Zona de Salario Minimo','Zona de Salario Minimo',2,'varchar','NA','',-1,'',1,0),
	(1660,232,'idempleado','Mesero','ID del mesero o empleado',12,'int','NA','',0,'',0,0),
	(1676,285,'idNivel','Nivel','Nivel',11,'int','NA','',-1,'-1',6,0),
	(1677,285,'cuentapadre','Dependencia','Dependencia',11,'int','NA','',0,'-1',7,0),
	(1686,251,'activo','activo','activar y desactivar',1,'boolean','1','',-1,'',4,0),
	(1689,78,'cuentacliente','Cuenta Cliente','Cuenta Cliente',5,'int','NA','',0,'-1',29,0),
	(1690,78,'beneficiario_pagador','Beneficiario/Pagador','Beneficiario/Pagador',0,'boolean','NA','',0,'',28,0),
	(1790,332,'idstatus','ID','ID',11,'auto_increment','NA','',-1,'',1,-1),
	(1791,332,'status','status','status',20,'varchar','NA','',-1,'',2,0),
	(1990,285,'activo','Activo','Activo',1,'boolean','NA','',-1,'',10,0),
	(1991,301,'nomi_empleado_clasif','nomi_empleado_clasif','',0,'int','NA','',0,'',26,0),
	(2004,301,'id_tipo_comision','id_tipo_comision','id_tipo_comision',11,'int','NA','',0,'',50,0),
	(2005,301,'comision','comision','comision',0,'double','NA','',0,'',51,0),
	(2006,301,'id_clasificacion','id_clasificacion','id_clasificacion',11,'int','NA','',0,'',52,0),
	(2007,364,'id','id','id',11,'auto_increment','NA','',-1,'',0,-1),
	(2008,364,'nombre','nombre','nombre',35,'varchar','NA','',-1,'',1,0),
	(2009,301,'id_area_empleado','id_area_empleado','id_area_empleado',11,'int','NA','',0,'',55,0),
	(2010,301,'activo','activo','activo',1,'boolean','1','',0,'',55,0),
	(2265,234,'ticket_config','Kiosco Facturacion','Codigo en Ticket',11,'int','NA','',-1,'',0,0),
	(2287,78,'codigo','codigo','codigo',45,'varchar','NA','',0,'',0,0),
	(2288,78,'legal','legal','legal',10,'varchar','NA','',0,'',10,0),
	(2289,78,'precioycalidad','precioycalidad','precioycalidad',1,'int','NA','',0,'',11,0),
	(2290,78,'disponibilidad','disponibilidad','disponibilidad',1,'int','NA','',0,'',12,0),
	(2291,78,'nombre_comercial','nombre_comercial','nombre_comercial',45,'varchar','NA','',0,'',2,0),
	(2292,78,'moneda','moneda','moneda',11,'int','NA','',0,'',14,0),
	(2293,78,'clasificacion','clasificacion','clasificacion',45,'varchar','NA','',0,'',15,0),
	(2294,78,'limite_credito','limite_credito','limite_credito',100,'double','NA','',0,'',16,0),
	(2295,78,'calle','calle','calle',45,'varchar','NA','',0,'',3,0),
	(2296,78,'no_ext','no_ext','no_ext',11,'int','NA','',0,'',3,0),
	(2297,78,'no_int','no_int','no_int',11,'int','NA','',0,'',3,0),
	(2298,78,'cp','cp','cp',11,'int','NA','',0,'',3,0),
	(2320,280,'activo','Activo','Activo',0,'boolean','-1','',0,'',16,0),
	(2321,280,'cancelada','Cuenta Cancelada','Cancelada/Activa',0,'boolean','0','',0,'',15,0),
	(2322,406,'idestadosat','idestadosat','idestadosat',11,'int','NA','',0,'',0,0),
	(2323,406,'estado','estado','estado',0,'varchar','50','',0,'',1,0),
	(2324,406,'idpais','pais','pais',0,'int','11','',0,'',3,0),
	(2326,47,'id','Cliente','Cliente',100,'int','NA','',0,'-1',16,0),
	(2332,234,'pac','Selecciona PAC','Selecciona PAC',1,'int','1','',-1,'',1,0),
	(2334,234,'fc_user','Usuario Formas continuas','Usuario Formas continuas',45,'varchar','NA','',0,'',2,0),
	(2335,234,'fc_password','Password Formas continuas','Password Formas continuas',45,'varchar','NA','',0,'',3,0),
	(2336,232,'status','Estatus','Estatus de la mesa',1,'int','NA','',0,'-1',0,0),
	(2362,232,'idSuc','ID sucursal','ID de la sucursal',0,'int','NA','',-1,'',0,0),
	(2364,234,'lugar_exp','Lugar de expedicion','Lugar de expedicion',100,'varchar','Mexico','',-1,'',0,0),
	(2494,1,'idpais','Pais','PaÃ­s',0,'int','NA','',0,'',6,0),
	(2563,234,'pass_ciec','Password Ciec','Password Ciec',32,'varchar','----','',-1,'',4,0);

/*!40000 ALTER TABLE `catalog_campos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla catalog_dependencias
# ------------------------------------------------------------

DROP TABLE IF EXISTS `catalog_dependencias`;

CREATE TABLE `catalog_dependencias` (
  `idcampo` int(11) NOT NULL AUTO_INCREMENT,
  `tipodependencia` char(1) DEFAULT NULL,
  `dependenciatabla` varchar(50) DEFAULT NULL,
  `dependenciacampovalor` varchar(50) DEFAULT NULL,
  `dependenciacampodescripcion` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`idcampo`),
  KEY `eddds_campoid` (`idcampo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2495 DEFAULT CHARSET=utf8;

LOCK TABLES `catalog_dependencias` WRITE;
/*!40000 ALTER TABLE `catalog_dependencias` DISABLE KEYS */;

INSERT INTO `catalog_dependencias` (`idcampo`, `tipodependencia`, `dependenciatabla`, `dependenciacampovalor`, `dependenciacampodescripcion`)
VALUES
	(7,'S','organizaciones','idorganizacion','nombreorganizacion'),
	(16,'S','accelog_categorias','idcategoria','nombre'),
	(21,'S','accelog_perfiles','idperfil','nombre'),
	(22,'S','accelog_menu','idmenu','nombre'),
	(25,'S','accelog_perfiles','idperfil','nombre'),
	(26,'S','accelog_opciones','idopcion','nombre'),
	(27,'S','accelog_perfiles','idperfil','nombre'),
	(28,'S','empleados','idempleado','nombre'),
	(29,'S','empleados','idempleado','nombre'),
	(33,'S','catalog_estructuras','idestructura','nombreestructura'),
	(41,'S','marcas','idmarca','descripcion'),
	(56,'S','estados','idestado','estado'),
	(57,'C','municipios','idmunicipio','municipio'),
	(60,'S','paises','idpais','pais'),
	(65,'S','estados','idestado','estado'),
	(75,'S','estados','idestado','estado'),
	(76,'C','municipios','idmunicipio','municipio'),
	(86,'S','almacenes','idalmacen','almacen'),
	(90,'S','estados','idestado','estado'),
	(91,'C','municipios','idmunicipio','municipio'),
	(93,'S','empleados','idempleado','nombre'),
	(102,'S','sucursales','idsucursal','sucursal'),
	(103,'S','clientes','idcliente','razonsocial'),
	(111,'S','productos','idproducto','nombreproducto'),
	(117,'S','vista_empleados','idempleado','nombre'),
	(141,'S','almacenes','idalmacen','almacen'),
	(142,'S','productos','idproducto','nombreproducto'),
	(143,'S','inventarios_lotes','idlote','descripcionlote'),
	(145,'S','inventarios_tiposmovimiento','idtipomovimiento','descripcion'),
	(149,'S','almacenes','idalmacen','almacen'),
	(156,'S','inventarios_estadosproducto','idestadoproducto','descripcion'),
	(165,'S','vista_estados','idestadosat','nombreproducto'),
	(166,'S','inventarios_lotes','idlote','descripcionlote'),
	(167,'S','inventarios_estadosproducto','idestadoproducto','descripcion'),
	(169,'S','inventarios_tiposmovimiento','idtipomovimiento','descripcion'),
	(171,'S','almacenes','idalmacen','almacen'),
	(172,'S','inventarios_lotes','idlote','descripcionlote'),
	(173,'S','inventarios_estadosproducto','idestadoproducto','descripcion'),
	(174,'S','almacenes','idalmacen','almacen'),
	(184,'S','nomi_regimenfiscal','idregfiscal','descripcion'),
	(186,'S','estados','idestado','estado'),
	(187,'C','municipios','idmunicipio','municipio'),
	(203,'S','vista_perfiles','idperfil','nombre'),
	(209,'S','puestos','idpuesto','puesto'),
	(222,'S','crm_ciclos','idciclo','nombre'),
	(233,'S','crm_actividades','idactividad','nombre'),
	(237,'S','clientes','idcliente','razonsocial'),
	(240,'S','clientes','idcliente','razonsocial'),
	(243,'S','crm_procesosventa','idprocesoventa','nombre'),
	(250,'S','crm_estadosventa','idestadoventa','nombre'),
	(251,'S','crm_campanias','idcampania','nombrecampania'),
	(252,'S','crm_cuentas','idcuenta','nombrecuenta'),
	(253,'S','productos','idproducto','nombreproducto'),
	(255,'S','productos','idproducto','nombreproducto'),
	(262,'S','proveedores','idproveedor','razonsocial'),
	(263,'S','almacenes','idalmacen','almacen'),
	(270,'S','productos','idproducto','nombreproducto'),
	(273,'S','crm_tiposcliente','idtipocliente','nombre'),
	(274,'S','crm_tipoprecio','idtipoprecio','nombre'),
	(280,'S','productos','idproducto','nombreproducto'),
	(285,'S','productos','idproducto','nombreproducto'),
	(301,'S','compras_titulo','idcompra','refcompra'),
	(310,'S','admin_deudores','iddeudor','nombredeudor'),
	(311,'S','admin_conceptos','idconcepto','concepto'),
	(325,'S','admin_formaspago','idformapago','formapago'),
	(329,'S','amin_cuentasbancarias','idcuenta','nombrecuenta'),
	(331,'S','admin_bancos','idbanco','banco'),
	(339,'S','admin_acreedores','idacreedor','nombreacreedor'),
	(340,'S','admin_conceptos','idconcepto','concepto'),
	(352,'S','admin_formaspago','idformapago','formapago'),
	(354,'S','amin_cuentasbancarias','idcuenta','nombrecuenta'),
	(355,'N','mrp_familia','idDep','idDep'),
	(356,'N','mrp_departamento','nombre','nombre'),
	(357,'N','ninguna','',''),
	(358,'N','mrp_familia','idFam','nombre'),
	(359,'N','crm_tipoprecio','idtipoprecio','idtipoprecio'),
	(360,'S','mrp_departamento','idDep','nombre'),
	(361,'N','paises','idpais','pais'),
	(362,'N','mrp_producto','idProducto','nombre'),
	(363,'S','mrp_familia','idFam','nombre'),
	(364,'N','estados','',''),
	(365,'N','municipios','idmunicipio','municipio'),
	(366,'N','mrp_departamento','idDep','nombre'),
	(367,'N','organizaciones','idorganizacion','nombreorganizacion'),
	(368,'N','mrp_unidades','',''),
	(369,'N','mrp_vista_unidades','idUnidad','unidad'),
	(370,'N','mrp_unidades','',''),
	(371,'N','mrp_unidades','idUni','compuesto'),
	(372,'S','mrp_producto','idProducto','nombre'),
	(373,'S','mrp_proveedor','idPrv','razon_social'),
	(375,'S','mrp_linea','idLin','nombre'),
	(376,'S','mrp_talla','idTal','talla'),
	(377,'S','mrp_color','idCol','color'),
	(389,'S','estados','idestado','estado'),
	(390,'C','municipios','idmunicipio','municipio'),
	(393,'N','ninguna','',''),
	(394,'N','ninguna','',''),
	(411,'S','organizaciones','idorganizacion','nombreorganizacion'),
	(419,'S','mrp_unidades','idUni','compuesto'),
	(424,'S','mrp_vista_unidades','idUnidad','compuesto_descripcion'),
	(552,'S','pvt_clientes','idCliente','nombre'),
	(553,'N','pvt_clientes','idCliente','nombre'),
	(579,'S','estados','idestado','estado'),
	(580,'C','municipios','idmunicipio','municipio'),
	(592,'S','rest_admin_deudores','iddeudor','nombredeudor'),
	(604,'S','admin_formaspago','idformapago','formapago'),
	(606,'S','amin_cuentasbancarias','idcuenta','nombrecuenta'),
	(611,'S','rest_admin_acreedores','idacreedor','nombreacreedor'),
	(612,'S','admin_conceptos','idconcepto','concepto'),
	(624,'S','admin_formaspago','idformapago','formapago'),
	(626,'S','amin_cuentasbancarias','idcuenta','nombrecuenta'),
	(632,'S','admin_conceptos','idconcepto','concepto'),
	(680,'S','estados','idestado','estado'),
	(681,'C','municipios','idmunicipio','municipio'),
	(904,'S','rest_admin_deudores','iddeudor','nombredeudor'),
	(905,'S','admin_conceptos','idconcepto','concepto'),
	(917,'S','admin_formaspago','idformapago','formapago'),
	(919,'S','amin_cuentasbancarias','idcuenta','nombrecuenta'),
	(924,'S','rest_admin_acreedores','idacreedor','nombreacreedor'),
	(925,'S','admin_conceptos','idconcepto','concepto'),
	(938,'S','admin_formaspago','idformapago','formapago'),
	(939,'S','amin_cuentasbancarias','idcuenta','nombrecuenta'),
	(951,'S','trt_transportistas','id','transport'),
	(959,'S','trt_tipo_combustible','id','tipo_combustible'),
	(970,'N','trt_tipo_persona','id','tipo'),
	(973,'C','municipios','idmunicipio','municipio'),
	(974,'C','municipios','idmunicipio','municipio'),
	(977,'S','accelog_perfiles_me','idperfil','idmenu'),
	(980,'C','empleados','idempleado','nombre,apellido1,apellido2,'),
	(982,'S','estados','idestado','estado'),
	(988,'S','tra_tipo_combustible','id','combustible'),
	(997,'S','tra_marca','id','marca'),
	(998,'S','tra_unidad','id','unidad'),
	(1001,'S','tra_tipo_combustible','id','combustible'),
	(1008,'S','tra_capacidad_unidad','id','capacidadU'),
	(1009,'S','tra_tipo_unidad','id','tUnidad'),
	(1010,'S','tra_propiedad_unidad','id','uPropiedad'),
	(1024,'S','comun_cliente','id','nombre'),
	(1025,'S','tra_tipo_operador','id','tOperador'),
	(1026,'S','tra_operadores','id','operador'),
	(1027,'S','tra_unidades','id','nEconomico'),
	(1033,'S','estados','idestado','estado'),
	(1034,'C','municipios','idmunicipio','municipio'),
	(1041,'S','estados','idestado','estado'),
	(1042,'C','municipios','idmunicipio','municipio'),
	(1046,'S','almacen','id',' nombre '),
	(1056,'S','estados','idestado',' estado '),
	(1057,'C','municipios','idmunicipio',' municipio '),
	(1061,'S','organizaciones','idorganizacion',' nombreorganizacion '),
	(1062,'S','almacen','idAlmacen',' nombre '),
	(1077,'S','forma_pago','idFormapago',' nombre '),
	(1078,'S','estados','idestado','estado'),
	(1079,'C','municipios','idmunicipio','municipio'),
	(1089,'S','trt_persona_fiscal','id_tipo_fiscal','tipo'),
	(1122,'S','tra_operadores','id','operador'),
	(1123,'S','tra_unidades','id',' nEconomico '),
	(1124,'S','trt_persona_fiscal','id_tipo_fiscal','tipo'),
	(1125,'S','trt_persona_fiscal','id_tipo_fiscal',' tipo '),
	(1134,'S','estados','idestado','estado'),
	(1140,'C','municipios','idmunicipio','municipio'),
	(1153,'S','tra_operadores','id',' operador '),
	(1157,'S','tra_proveedores','id',' proveedor '),
	(1158,'S','tra_conceptos','id',' concepto '),
	(1159,'S','tra_forma_pago','id',' formaPago '),
	(1161,'S','tra_cuentas_bancarias','id',' cuenta '),
	(1177,'S','tra_proveedores','id',' proveedor '),
	(1178,'S','tra_conceptos','id',' concepto '),
	(1199,'S','trt_persona_fiscal','id_tipo_fiscal',' tipo '),
	(1204,'S','estados','idestado',' estado '),
	(1211,'C','municipios','idmunicipio',' municipio '),
	(1235,'S','comun_cliente','id','nombre'),
	(1245,'S','estados','idestado','estado'),
	(1253,'S','mrp_sucursal','idsuc','nombre'),
	(1275,'S','cont_tipo_tercero','id',' tipotercero '),
	(1276,'S','cont_tipo_operacion','id',' tipoOperacion '),
	(1278,'S','cont_accounts','account_id',' manual_code , description '),
	(1280,'S','paises','idpais',' pais '),
	(1308,'S','mrp_departamento','idDep',' nombre '),
	(1314,'S','pvt_catalogo_regimen','id',' regimen '),
	(1336,'S','cont_tipo_iva','id',' tipoiva '),
	(1360,'S','estados','idestado',' estado '),
	(1361,'C','municipios','idmunicipio',' municipio '),
	(1368,'S','sms_tipo_unidad','idtipo',' tipo '),
	(1371,'S','sms_capacidades','idcapacidad',' capacidad '),
	(1373,'S','comun_cliente','id',' nombre '),
	(1383,'S','estados','idestado',' estado '),
	(1391,'S','meses','id',' mes '),
	(1394,'S','cont_ejercicios','Id',' NombreEjercicio '),
	(1400,'S','cont_IETU','id',' nombre '),
	(1412,'S','cont_coin','coin_id','description'),
	(1464,'S','cont_accounts','account_id','manual_code , description'),
	(1483,'S','cont_bancos','idbanco','Clave, nombre'),
	(1484,'S','mrp_proveedor','idPrv','razon_social'),
	(1506,'S','cont_bancos','idbanco',' Clave , nombre '),
	(1507,'S','bco_tipo_cuenta','idtipoc',' tipo '),
	(1508,'S','cont_coin','coin_id',' description '),
	(1510,'S','cont_accounts','account_id',' manual_code , description'),
	(1543,'S','bco_tipoClasificador','idtipo',' tipo '),
	(1625,'S','tipo_proveedor','idtipo',' tipo'),
	(1639,'S','nomi_zona','idzona',' zonasalario '),
	(1640,'S','forma_pago','idFormapago',' nombre '),
	(1643,'S','nomi_estado_civil','idEstadoCivil',' estadoCivil '),
	(1644,'S','nomi_sexo','idsexo',' sexo '),
	(1646,'S','estados','idestado',' estado '),
	(1647,'S','municipios','idmunicipio',' municipio '),
	(1652,'S','vista_estados','idestadosat',' estado '),
	(1655,'S','cont_bancos','idbanco',' nombre '),
	(1660,'S','administracion_usuarios','idempleado','nombreusuario'),
	(1676,'S','bco_nivelClasificador','idNivel',' nivel '),
	(1789,'S','bco_status','idstatus',' status '),
	(2009,'S','app_area_empleado','id',' nombre '),
	(2326,'S','comun_cliente','id','nombre'),
	(2336,'S','bco_status','idstatus',' status '),
	(2362,'S','mrp_sucursal','idSuc',' nombre '),
	(2494,'S','paises','idpais','pais');

/*!40000 ALTER TABLE `catalog_dependencias` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla catalog_dependenciasfiltros
# ------------------------------------------------------------

DROP TABLE IF EXISTS `catalog_dependenciasfiltros`;

CREATE TABLE `catalog_dependenciasfiltros` (
  `idcampo` int(11) NOT NULL,
  `nombrecampo` varchar(50) NOT NULL,
  PRIMARY KEY (`idcampo`,`nombrecampo`),
  KEY `dfd_dependencias` (`idcampo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `catalog_dependenciasfiltros` WRITE;
/*!40000 ALTER TABLE `catalog_dependenciasfiltros` DISABLE KEYS */;

INSERT INTO `catalog_dependenciasfiltros` (`idcampo`, `nombrecampo`)
VALUES
	(57,'idestado'),
	(76,'idestado'),
	(91,'idestado'),
	(187,'idestado'),
	(390,'idestado'),
	(580,'idestado'),
	(681,'idEstado'),
	(785,'id_nombre'),
	(786,'id_nombre'),
	(812,'idestado'),
	(820,'idproyecto'),
	(831,'idpais'),
	(832,'idestado'),
	(842,'idpais'),
	(844,'idestado'),
	(973,'idEstado'),
	(974,'idEstado'),
	(980,'dato'),
	(980,'id'),
	(1034,'idEstado'),
	(1042,'idEstado'),
	(1057,'idEstado'),
	(1079,'idEstado'),
	(1140,'idEstado'),
	(1211,'idEstado'),
	(1361,'idEstado');

/*!40000 ALTER TABLE `catalog_dependenciasfiltros` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla catalog_estructuras
# ------------------------------------------------------------

DROP TABLE IF EXISTS `catalog_estructuras`;

CREATE TABLE `catalog_estructuras` (
  `idestructura` int(11) NOT NULL AUTO_INCREMENT,
  `nombreestructura` varchar(50) DEFAULT NULL,
  `descripcion` varchar(80) DEFAULT NULL,
  `fechacreacion` datetime DEFAULT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `estatus` char(1) DEFAULT NULL,
  `utilizaidorganizacion` tinyint(4) DEFAULT '0',
  `linkproceso` varchar(200) DEFAULT NULL,
  `columnas` int(11) DEFAULT NULL,
  `linkprocesoantes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idestructura`)
) ENGINE=InnoDB AUTO_INCREMENT=407 DEFAULT CHARSET=utf8;

LOCK TABLES `catalog_estructuras` WRITE;
/*!40000 ALTER TABLE `catalog_estructuras` DISABLE KEYS */;

INSERT INTO `catalog_estructuras` (`idestructura`, `nombreestructura`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkproceso`, `columnas`, `linkprocesoantes`)
VALUES
	(1,'organizaciones','Catalogo de Organizaciones','2012-11-23 19:57:26','2013-10-26 16:05:41','A',0,'../../modulos/organizaciones/despues.php',1,''),
	(2,'empleados','Catálogo de Empleados','2012-11-23 19:57:26','2013-10-26 16:05:09','A',1,'',0,''),
	(3,'accelog_categorias','Categorías del accelog','2012-11-23 19:57:26','2013-10-26 16:03:45','A',0,'',0,''),
	(4,'accelog_menu','Lista de menús para accelog','2012-11-23 19:57:27','2013-10-26 16:03:56','A',0,'',0,''),
	(5,'accelog_perfiles','Lista de perfiles de usuarios de accelog','2012-11-23 19:57:27','2012-11-23 19:57:27','A',0,'',NULL,NULL),
	(6,'accelog_perfiles_me','Lista de menús por perfil','2012-11-23 19:57:27','2013-10-26 16:04:12','A',0,'',0,''),
	(7,'accelog_opciones','Lista de opciones','2012-11-23 19:57:27','2012-11-23 19:57:27','A',0,'',NULL,NULL),
	(8,'accelog_perfiles_op','Lista de opciones por perfil','2012-11-23 19:57:27','2012-11-23 19:57:27','A',0,'',NULL,NULL),
	(9,'accelog_usuarios_per','Lista de perfiles por usuario','2012-11-23 19:57:27','2013-05-26 19:43:32','A',0,'',NULL,NULL),
	(11,'accelog_niveles','Niveles de Usuarios','2012-11-23 19:57:30','2012-11-23 19:57:30','A',0,'',1,''),
	(12,'productos','Productos','2012-11-23 20:50:03','2013-04-23 01:36:33','A',0,'',1,''),
	(14,'marcas','Marcas','2012-11-23 20:56:47','2012-11-23 20:57:34','A',0,'',0,''),
	(15,'proveedores','Proveedores','2012-11-23 21:02:53','2012-11-23 21:25:19','A',0,'',1,''),
	(16,'estados','Estados','2012-11-23 21:06:39','2013-05-06 09:21:57','A',0,'',0,''),
	(17,'paises','paises','2012-11-23 21:09:16','2012-11-23 21:10:03','A',0,'',0,''),
	(18,'municipios','Municipios','2012-11-23 21:11:18','2013-05-06 09:22:03','A',0,'',0,''),
	(19,'clientes','Clientes','2012-11-23 21:29:30','2013-05-06 10:50:12','A',0,'',1,''),
	(20,'configuracion_recibos','Configuracion Recibos','2012-11-23 21:41:50','2012-11-23 21:43:03','A',0,'',0,''),
	(21,'productos_maximosminimos','Maximos y Minimos','2012-11-23 21:46:23','2012-11-23 22:05:30','A',0,'',0,''),
	(22,'almacenes','Almacenes','2012-11-23 21:47:58','2012-11-23 21:52:15','A',0,'',0,''),
	(23,'ventas_titulo','Ventas Titulo','2012-11-23 22:10:05','2013-05-16 10:26:43','A',0,'../../modulos/ventas/afecta.php',2,''),
	(24,'ventas_detalle','Ventas Detalle','2012-11-23 22:10:15','2013-05-16 10:26:36','A',0,'',0,''),
	(25,'sucursales','Sucursales','2012-11-23 22:11:46','2013-10-07 14:36:26','A',0,'',0,''),
	(26,'vista_productos','vista_productos','2012-11-23 22:30:01','2012-11-23 22:30:01','G',0,'',0,''),
	(27,'vista_empleados','Empleado','2012-11-23 23:00:59','2012-11-23 23:00:59','G',0,'',0,''),
	(38,'inventarios_movimientos','Movimientos','2012-12-16 13:04:07','2013-04-23 01:36:41','A',0,'',0,''),
	(39,'inventarios_saldos','Saldos','2012-12-16 13:04:16','2013-04-23 01:37:25','A',0,'',0,''),
	(40,'inventarios_lotes','Lotes','2012-12-16 13:45:55','2013-04-23 01:37:03','A',0,'',0,''),
	(41,'inventarios_tiposmovimiento','Tipos Movimientos','2012-12-16 13:48:49','2012-12-16 13:50:15','A',0,'',0,''),
	(42,'inventarios_estadosproducto','Estados Producto','2012-12-16 14:04:17','2013-04-23 01:36:56','A',0,'',0,''),
	(43,'inventarios_movimientostitulo','Movimientos Titulo','2012-12-16 15:32:33','2013-04-23 01:37:16','A',0,'../../modulos/inventarios/movimientosdirectos.php',0,''),
	(44,'inventarios_movimientosdetalle','Movimientos Detalle','2012-12-16 15:32:55','2013-04-23 01:37:11','A',0,'',0,''),
	(45,'lonas','Lonas','2012-12-22 14:01:38','2012-12-22 14:04:15','A',0,'',0,''),
	(46,'pinicial','Pagina Inicial','2013-01-14 14:53:51','2013-01-14 14:53:51','G',0,'',0,''),
	(47,'administracion_usuarios','Administración Usuarios','2013-04-21 21:03:43','2013-10-26 16:04:25','A',1,'../../modulos/usuarios/despues.php',0,'../../modulos/usuarios/antes.php'),
	(48,'vista_perfiles','vista_perfiles','2013-04-21 21:20:43','2013-04-21 21:20:43','G',0,'',0,''),
	(49,'crm_campanias','Campañas','2013-04-22 19:38:38','2013-10-26 16:04:46','A',0,'',1,''),
	(50,'crm_metasventas','Metas Ventas','2013-04-22 19:47:29','2013-04-23 01:04:47','A',0,'',0,''),
	(51,'crm_ciclos','Ciclos Evaluacion','2013-04-22 23:45:18','2013-04-22 23:46:11','A',0,'',0,''),
	(52,'puestos','Puestos','2013-04-22 23:57:35','2013-04-22 23:58:16','A',0,'',0,''),
	(53,'crm_actividades','Actividades Comerciales','2013-04-23 00:30:35','2013-04-23 00:34:35','A',0,'',0,''),
	(54,'crm_registroactividadescomerciales','Registro Actividades Comerciales','2013-04-23 00:36:15','2013-04-23 00:43:57','A',0,'',0,''),
	(55,'crm_cuentas','Cuentas','2013-04-23 00:48:32','2013-04-23 00:54:53','A',0,'',0,''),
	(56,'crm_procesosventa','Procesos de Venta','2013-04-23 00:52:52','2013-04-23 00:54:22','A',0,'',0,''),
	(57,'crm_estadosventa','Estados de Venta','2013-04-23 01:02:12','2013-04-23 01:06:06','A',0,'',0,''),
	(58,'productos_precios','Lista de Precios','2013-05-06 09:11:28','2013-05-06 11:27:00','A',0,'',0,''),
	(59,'compras_titulo','Compras Titulo','2013-05-06 10:25:44','2013-05-16 11:39:08','A',0,'../../modulos/compras/afecta.php',1,''),
	(60,'compras_detalle','Compras Detalle','2013-05-06 10:25:56','2013-05-09 09:46:12','A',0,'',0,''),
	(61,'crm_tiposcliente','Tipos Cliente','2013-05-06 10:47:13','2013-05-06 10:49:00','A',0,'',0,''),
	(62,'crm_tipoprecio','Tipo Precio','2013-05-06 11:25:00','2013-05-06 11:25:47','A',0,'',0,''),
	(63,'crm_campanias_detalle','Campañas Detalle','2013-05-06 11:53:44','2013-10-26 16:04:57','A',0,'',0,''),
	(64,'admin_cxc','Cuentas por Cobrar','2013-05-09 10:01:44','2013-08-09 16:11:42','A',0,'../../modulos/cxc/despues_cxcpagos.php',2,'../../modulos/cxc/antes.php'),
	(65,'admin_cxp','Cuentas por Pagar','2013-05-09 10:01:56','2013-05-16 15:20:58','A',0,'',2,'../../modulos/cxp/antes.php'),
	(66,'vista_deudores','Deudores','2013-05-09 10:02:58','2013-05-09 10:02:58','G',0,'',0,''),
	(67,'admin_deudores','Deudores','2013-05-09 10:08:11','2013-05-16 15:21:04','A',0,'',0,''),
	(68,'admin_acreedores','Acreedores','2013-05-15 21:44:27','2013-05-16 15:21:09','A',0,'',0,''),
	(69,'admin_conceptos','Conceptos CXC, CXP','2013-05-15 21:50:51','2013-05-15 21:52:09','A',0,'',0,''),
	(70,'admin_cxcpagos','Pagos CXC','2013-05-15 22:00:55','2013-08-09 14:02:35','A',0,'',0,''),
	(71,'admin_formaspago','Formas de Pago','2013-05-15 22:04:17','2013-05-15 22:05:29','A',0,'',0,''),
	(72,'amin_cuentasbancarias','Cuentas Bancarias','2013-05-15 22:08:56','2013-05-15 22:15:00','A',0,'',0,''),
	(73,'admin_bancos','Bancos','2013-05-15 22:09:59','2013-05-15 22:10:46','A',0,'',0,''),
	(74,'admin_cxppagos','Pagos CXP','2013-05-15 22:27:37','2013-05-16 15:20:51','A',0,'',0,''),
	(75,'mrp_departamento','Departamento','2013-09-10 18:05:00','2013-09-11 10:57:33','A',0,'',1,''),
	(76,'mrp_familia','Familia','2013-09-10 18:11:49','2013-09-11 16:16:40','A',0,'',3,''),
	(77,'mrp_linea','Línea','2013-09-11 11:13:02','2013-10-26 16:05:27','A',0,'',3,'../../modulos/mrp/preload_filtro_dep_fam.php'),
	(78,'mrp_proveedor','Proveedor','2013-09-11 11:27:07','2013-10-24 23:45:02','A',0,'../../modulos/mrp/despuesprove.php',0,'../../modulos/mrp/antesprove.php'),
	(79,'mrp_producto','Producto','2013-09-11 11:36:46','2013-09-11 16:18:04','A',0,'',4,''),
	(80,'mrp_producto_proveedor','Producto proveedor','2013-09-11 12:41:27','2013-10-03 09:39:09','A',0,'',4,''),
	(81,'mrp_color','Color','2013-09-12 13:23:36','2013-09-12 14:55:26','A',0,'',2,''),
	(82,'mrp_talla','Talla','2013-09-12 13:27:24','2013-09-12 14:55:33','A',0,'',0,''),
	(83,'mrp_orden_compra','Orden de compra','2013-09-17 11:53:50','2013-09-18 17:14:02','A',0,'',0,''),
	(84,'mrp_producto_orden_compra','Producto de orden de compra','2013-09-18 15:12:27','2013-09-18 17:14:08','A',0,'',0,''),
	(85,'mrp_unidades','Unidades de medida','2013-09-20 13:09:48','2013-10-25 15:10:21','A',0,'../../modulos/mrp/unidad_falsa_despues.php',0,'../../modulos/mrp/unidad_falsa.php'),
	(86,'mrp_sucursal','Sucursal','2013-09-24 09:22:22','2013-09-24 09:30:37','A',0,'',0,''),
	(87,'mrp_unidad_equivalencias','Equivalencias de unidades','2013-10-02 13:57:46','2013-10-25 23:55:45','A',0,'',0,''),
	(88,'mrp_patrones_medida','Patrones de medida','2013-10-02 16:25:18','2013-10-03 13:04:09','D',0,'',0,''),
	(89,'mrp_vista_unidades','Vista de Unidades','2013-10-03 10:44:40','2013-10-14 15:39:11','D',0,'',0,''),
	(105,'cont_tipos_poliza','Tipos de Polizas','2013-10-04 12:21:35','2013-10-04 12:24:33','A',0,'',0,''),
	(106,'cont_polizas','Polizas','2013-10-04 12:25:06','2013-10-04 12:40:41','A',0,'',0,''),
	(107,'cont_movimientos','cont_movimientos','2013-10-04 12:41:31','2013-10-04 12:51:47','A',0,'',0,''),
	(108,'cont_ejercicios','Ejercicios','2013-10-04 12:52:24','2013-10-04 15:08:09','A',0,'',0,''),
	(109,'cont_config','cont_config','2013-10-04 12:55:51','2013-10-04 13:19:49','A',0,'',0,''),
	(110,'configuracionFactura','Configuracion Factura','2013-10-18 14:26:21','2013-10-18 14:29:23','A',0,'',1,'../../modulos/facturacion/configuracionFactura.php'),
	(111,'pvt_clientes','Clientes (PDV)','2013-10-21 22:49:42','2013-10-24 15:35:27','A',0,'',0,'../../modulos/puntoVenta/cat_clientesAntes.php'),
	(112,'pvt_facturacion','Facturacion (PDV)','2013-10-22 15:24:54','2013-10-24 15:35:33','A',0,'',0,'../../modulos/puntoVenta/cat_facturacionAntes.php'),
	(113,'prueba','asdfasdfasdfasdf','2013-10-23 14:50:52','2013-10-23 14:50:52','G',0,'',0,''),
	(114,'pvt_targetasRegalo','Tarjetas de regalo (PDV)','2013-10-23 15:36:29','2013-10-24 15:35:41','A',0,'',0,''),
	(115,'agendaLog','Agenda','2013-10-25 16:39:28','2013-10-25 17:42:34','A',0,'',0,''),
	(116,'agendalog_citas','Citas con el contacto','2013-10-25 18:13:16','2013-10-25 18:16:58','A',0,'',0,''),
	(117,'accelog_usuarios','accelog_usuarios','2013-10-26 00:19:58','2013-10-26 00:25:11','A',0,'',3,''),
	(135,'comun_cliente','Clientes','2013-11-06 20:00:04','2013-11-07 16:05:54','A',0,'',0,'../../modulos/cont_repolog/catalog_clientes.php'),
	(169,'rest_admin_cxc','cuentas x cobrar REST','2013-11-20 13:48:11','2013-11-20 16:21:12','G',0,'../../modulos/restcxc/despues_cxcpagos.php',2,'../../modulos/restcxc/antes.php'),
	(170,'rest_admin_cxcpagos','pagos cxc rest','2013-11-20 15:50:14','2013-11-20 15:50:14','G',0,'',0,''),
	(171,'rest_admin_cxp','Cuentas por Pagar rest','2013-11-20 16:08:02','2013-11-20 16:24:29','G',0,'',2,'../../modulos/restcxp/antes.php'),
	(172,'rest_admin_cxppagos','admin cxp pagos rest','2013-11-20 16:10:52','2013-11-20 16:10:52','G',0,'',1,''),
	(173,'rest_admin_deudores','admin deudores rest','2013-11-20 16:26:09','2013-11-20 16:26:09','G',0,'',0,''),
	(174,'rest_admin_acreedores','rest_admin_acreedores','2013-11-21 16:08:19','2013-11-21 16:08:19','G',0,'',2,''),
	(175,'trt_pagos_transportistas','TRT pagos transportistas','2013-11-21 18:11:19','2013-11-21 19:12:07','A',0,'',0,''),
	(176,'trt_liquidaciones','TRT Liquidaciones','2013-11-26 19:20:24','2013-11-26 19:29:15','G',0,'../../modulos/transportes/despuesLiqui.php',0,''),
	(177,'trt_vales_combustible','TRT Vales combustible','2013-11-26 19:21:21','2013-11-26 19:29:34','A',0,'',0,''),
	(178,'pvt_pendienteFactura','Pendiente Facturas','2013-11-26 23:34:52','2013-11-26 23:47:41','A',0,'',0,''),
	(179,'pruebatabla','','2013-12-09 17:15:59','2013-12-09 17:16:30','G',0,'',0,''),
	(180,'Prueba-11-17',' Cadena con  quotes  4','2013-12-09 17:18:08','2013-12-10 20:08:25','G',0,'',0,''),
	(181,'trt_tipo_persona','trt_tipo_persona','2013-12-09 17:52:18','2013-12-09 17:52:18','G',0,'',0,''),
	(182,'prueba-11-55','','2013-12-09 17:55:16','2013-12-09 18:18:51','G',0,'',0,''),
	(183,'tra_ciudades','TRA Ciudades','2013-12-12 15:27:51','2013-12-12 19:03:14','A',0,'',0,''),
	(184,'tra_configuracion','TRA Configuracion','2013-12-12 15:35:58','2013-12-12 19:03:50','A',0,'',0,''),
	(185,'tra_costo_combustible','TRA Costo Combustible','2013-12-12 16:05:48','2013-12-12 19:04:27','A',0,'',0,''),
	(186,'tra_productos','TRA Productos','2013-12-12 16:32:13','2013-12-12 19:13:55','A',0,'',0,''),
	(187,'tra_unidades','TRA Unidades','2013-12-12 16:40:15','2013-12-12 23:02:02','A',0,'',0,''),
	(188,'tra_tipo_combustible','TRA Combustible','2013-12-12 17:17:58','2013-12-12 19:19:28','A',0,'',0,''),
	(189,'tra_marca','TRA Marca','2013-12-12 17:28:14','2013-12-12 19:13:35','A',0,'',0,''),
	(190,'tra_unidad','TRA Unidad','2013-12-12 17:32:03','2013-12-12 23:06:00','A',0,'',0,''),
	(191,'tra_capacidad_unidad','TRA Capacidad Unidad','2013-12-12 17:34:08','2013-12-12 22:46:44','A',0,'',0,''),
	(192,'tra_liquidacion_os','TRA Liquidacion OS','2013-12-12 17:51:36','2013-12-16 19:12:31','A',0,'',0,''),
	(193,'tra_tipo_operador','TRA Tipo Operador','2013-12-12 18:22:07','2013-12-12 19:20:40','A',0,'',0,''),
	(194,'almacen','Almacen','2013-12-12 22:04:42','2013-12-13 17:59:44','A',0,'',0,''),
	(195,'tra_tipo_unidad','TRA Tipo Unidad','2013-12-12 22:21:59','2013-12-12 22:37:43','A',0,'',0,''),
	(196,'tarjeta_regalo','Tarjeta de regalo','2013-12-12 23:48:44','2013-12-12 23:52:07','A',0,'',0,'../../modulos/punto_venta/js/tarjetaregalo.php'),
	(197,'tra_propiedad_unidad','TRA Propiedad Unidad','2013-12-12 23:50:36','2013-12-12 23:54:54','A',0,'',0,''),
	(198,'tra_operadores','TRA Operadores','2013-12-13 00:03:30','2013-12-16 17:27:56','A',0,'',0,''),
	(199,'cxp','Cuenta por pagar','2013-12-13 21:24:29','2013-12-16 16:29:21','A',0,'',0,'../../modulos/punto_venta/cxp.php'),
	(200,'cxp_pagos','Pagos cuenta por pagar','2013-12-13 21:34:01','2013-12-16 16:38:18','A',0,'',0,'../../modulos/punto_venta/cxp.php'),
	(201,'vista_trt_clientes','vista_trt_clientes','2013-12-13 22:15:41','2013-12-23 23:35:43','G',0,'',2,'../../modulos/trt/clientes_antes.php'),
	(202,'forma_pago','Formas de pago','2013-12-13 22:31:12','2013-12-13 22:34:53','A',0,'',0,''),
	(203,'trt_persona_fiscal','Persona Fisica/Fiscal','2013-12-16 16:44:42','2013-12-16 16:44:42','G',0,'',0,''),
	(204,'tra_modificacion_liquidacion_os','TRA Modificacion Liquidacion OS','2013-12-16 17:37:25','2013-12-18 00:26:20','A',0,'',0,''),
	(205,'vista_trt_proveedores','TRT Proveedores','2013-12-17 19:15:33','2013-12-17 19:15:33','G',0,'',3,''),
	(206,'prueba_catx','Prueba Cax','2013-12-18 15:43:08','2013-12-18 15:43:08','G',0,'',0,''),
	(207,'tra_liquidacion_cancelacion','TRA Liquidacion Cancelacion','2013-12-18 16:18:06','2013-12-18 16:33:58','A',0,'',0,''),
	(208,'tra_pago_liquidacion','TRA Pago Liquidacion','2013-12-18 16:38:24','2013-12-18 16:44:33','A',0,'',0,''),
	(209,'tra_poliza_egreso','TRA Poliza Egreso','2013-12-18 16:51:45','2013-12-18 17:17:52','A',0,'',0,''),
	(210,'tra_proveedores','TRA Proveedores','2013-12-18 17:19:50','2013-12-18 21:45:18','A',0,'',0,''),
	(211,'tra_conceptos','TRA Conceptos','2013-12-18 18:09:47','2013-12-18 18:45:11','A',0,'',0,''),
	(212,'tra_forma_pago','TRA Forma Pago','2013-12-18 18:59:16','2013-12-18 19:17:12','A',0,'',0,''),
	(213,'tra_cuentas_bancarias','TRA Cuentas Bancarias','2013-12-18 21:51:08','2013-12-18 21:59:42','A',0,'',0,''),
	(214,'tra_gasto_OS','TRA Gasto OS','2013-12-18 22:39:15','2013-12-18 22:39:15','G',0,'',0,''),
	(215,'tra_solicitud_recursos','TRA Solicitud Recursos','2013-12-18 22:47:23','2013-12-18 22:58:05','A',0,'',0,''),
	(216,'vista_trt_transportista','vista_trt_transportista','2013-12-19 19:36:23','2013-12-19 19:58:27','G',0,'../../modulos/transportes/transportista.php',2,'../../modulos/trt/transportista_antes.php'),
	(218,'trt_contacto_cliente','trt_contacto_cliente','2013-12-26 18:53:42','2013-12-26 18:54:16','G',0,'',0,''),
	(219,'tra_transportistas','TRA Transportistas','2013-12-27 22:49:57','2013-12-27 22:54:06','A',0,'',0,''),
	(220,'tra_destino','TRA Destino','2013-12-28 00:43:52','2013-12-28 00:43:52','G',0,'',0,''),
	(221,'pvt_serie_folio','Configuracion Serie y Folio','2013-12-31 18:32:11','2013-12-31 18:40:34','A',0,'',0,''),
	(222,'tra_prueba','Tra Prueba','2014-01-07 16:44:34','2014-01-07 16:44:34','G',0,'',0,''),
	(223,'comun_facturacion','Comun facturacion','2014-01-08 11:42:30','2014-01-08 11:42:30','G',0,'',0,'../../modulos/facturacion/antesfactura.php'),
	(224,'impuesto','Impuestos','2014-01-13 17:59:16','2014-01-13 18:05:41','A',0,'',0,'../../modulos/punto_venta/combo_impuesto.php'),
	(227,'cont_tipo_operacion','Tipo Operacion','2014-03-24 23:30:10','2014-03-24 23:33:49','A',0,'',0,''),
	(228,'cont_tipo_tercero','Tipo tercero','2014-03-24 23:50:02','2014-03-24 23:52:58','A',0,'',0,''),
	(230,'cont_accounts','cont_accounts','2014-03-25 17:51:04','2014-03-25 17:51:04','G',0,'',0,''),
	(232,'com_mesas','mesas','2014-03-28 17:04:32','2014-03-28 17:12:56','A',0,'',0,''),
	(234,'pvt_configura_facturacion','Configuracion de Facturacion','2014-04-03 18:31:25','2014-04-03 21:59:44','A',0,'../../modulos/SAT/despues_config.php',0,'../../modulos/SAT/antes_config.php'),
	(236,'pvt_catalogo_regimen','Catalogo regimen fiscal','2014-04-03 21:50:53','2014-04-03 21:53:39','A',0,'',0,''),
	(242,'datos_bascula','Datos Bascula','2014-06-12 22:45:52','2014-06-17 23:16:53','A',0,'',0,'../../modulos/serial/serial.php'),
	(244,'sms_grupos','SMS Grupos','2014-06-25 17:49:23','2014-06-25 18:16:50','A',0,'',0,''),
	(245,'sms_origen_rutas','SMS Origen rutas','2014-07-10 16:53:03','2014-07-10 17:32:47','A',0,'',0,''),
	(246,'sms_transporte','SMS transporte','2014-07-10 23:37:41','2014-07-11 18:40:30','A',0,'',0,'../../modulos/sms/antes_sms_transporte.php'),
	(247,'sms_tipo_unidad','SMS tipo unidad','2014-07-10 23:48:23','2014-07-11 00:12:20','A',0,'',0,''),
	(248,'sms_capacidades','SMS capacidades','2014-07-11 18:19:18','2014-07-11 18:43:10','A',0,'',0,''),
	(249,'vista_comun_facturacion','Datos Facturacion.','2014-07-17 16:34:57','2014-07-17 16:34:57','G',0,'',0,''),
	(251,'cont_segmentos','Segmentos de Negocio','2014-10-16 19:12:46','2014-10-16 19:29:49','A',0,'../../modulos/cont_repolog/eliminar_segmentos_despues.php',1,'../../modulos/cont_repolog/eliminar_segmentos.php'),
	(252,'cont_resumen_ivas_retenidos','Resumen de ivas retenidos ','2014-11-11 21:51:26','2014-11-11 21:57:05','A',0,'',0,''),
	(253,'meses','Meses de ejercicio','2014-11-13 15:34:26','2014-11-13 15:39:54','A',0,'',0,''),
	(254,'cont_IETU','IETU','2014-11-19 18:04:23','2014-11-19 18:06:22','A',0,'',0,''),
	(256,'cont_coin','Tipo de moneda','2014-11-20 18:15:43','2014-11-20 18:51:55','A',0,'',0,''),
	(257,'cont_tipo_cambio','Tipos de Cambio','2014-11-21 17:29:56','2014-11-21 17:44:21','A',0,'',0,'../../modulos/cont/controllers/antestipocambio.php'),
	(274,'cont_bancos','Bancos','2015-01-22 17:39:43','2015-01-22 17:52:23','A',0,'',0,''),
	(275,'cont_bancosPrv','Bancos  de Proveedor','2015-01-26 17:45:11','2015-01-26 18:11:53','A',0,'',0,'../../modulos/cont/js/antesbancosprv.php'),
	(280,'bco_cuentas_bancarias','Cuentas Bancarias','2015-05-04 22:26:46','2015-05-08 21:55:11','A',0,'',0,'../../modulos/bancos/js/antescuentasbancarias.php'),
	(281,'bco_tipo_cuenta','Tipo de cuenta','2015-05-04 22:34:56','2015-05-04 22:37:20','A',0,'',0,''),
	(285,'bco_clasificador','Clasificador Ingresos-Egresos','2015-06-12 16:13:42','2015-12-22 22:20:10','A',0,'../../modulos/bancos/js/despuesclasificador.php',0,'../../modulos/bancos/js/antesclasificador.php'),
	(299,'nomi_estado_civil','Estado Civil','2015-09-21 01:18:58','2015-09-21 01:20:58','A',0,'',0,''),
	(300,'nomi_sexo','Sexo','2015-09-21 01:22:56','2015-09-21 01:24:32','A',0,'',0,''),
	(301,'nomi_empleados','Empleados','2015-09-21 01:25:37','2015-09-22 18:34:42','A',0,'',0,'../../modulos/cont/js/antesempleados.php'),
	(302,'nomi_zona','Zona de Salario','2015-09-22 18:29:31','2015-09-22 18:31:37','A',0,'',0,''),
	(332,'bco_status','Estatus','2016-03-02 17:30:03','2016-03-02 17:48:03','A',0,'',0,''),
	(364,'app_area_empleado','app_area_empleado','2016-04-14 17:13:33','2016-04-14 17:16:01','A',0,'',0,''),
	(406,'vista_estados','vista estados','2016-07-29 18:51:15','2016-07-29 18:51:15','G',0,'',0,'');

/*!40000 ALTER TABLE `catalog_estructuras` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla com_actividades
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_actividades`;

CREATE TABLE `com_actividades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empleado` int(11) DEFAULT NULL,
  `accion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8_unicode_ci,
  `id_sucursal` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla com_areas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_areas`;

CREATE TABLE `com_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla com_bit_repartidores
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_bit_repartidores`;

CREATE TABLE `com_bit_repartidores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_repartidor` int(11) NOT NULL,
  `id_comanda` int(11) NOT NULL,
  `fecha_pedido_asignado` datetime DEFAULT NULL,
  `fecha_pedido_entregado` datetime DEFAULT NULL,
  `fecha_pedido_pagado` datetime DEFAULT NULL,
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla com_comandas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_comandas`;

CREATE TABLE `com_comandas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idmesa` int(11) DEFAULT '0',
  `personas` int(11) DEFAULT '0',
  `tipo` int(11) DEFAULT '0',
  `abierta` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `codigo` varchar(30) DEFAULT '',
  `individual` int(11) DEFAULT '0',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `idempleado` int(12) DEFAULT '1',
  `promedioComensal` decimal(12,2) DEFAULT '0.00',
  `fin` timestamp NULL DEFAULT NULL,
  `comensales` int(15) DEFAULT '0',
  `total` decimal(12,2) DEFAULT '0.00',
  `id_venta` int(11) DEFAULT NULL,
  `tickets` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_combos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_combos`;

CREATE TABLE `com_combos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) DEFAULT NULL,
  `dias` varchar(50) DEFAULT NULL,
  `inicio` varchar(10) DEFAULT NULL,
  `fin` varchar(10) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_combosXproductos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_combosXproductos`;

CREATE TABLE `com_combosXproductos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_combo` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `grupo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_configuracion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_configuracion`;

CREATE TABLE `com_configuracion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `propina` int(11) DEFAULT '1',
  `consumo` int(11) DEFAULT '0',
  `reservaciones` int(11) DEFAULT '1',
  `seguridad` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_operacion` int(1) DEFAULT '1',
  `pedir_pass` int(11) DEFAULT '1',
  `mostrar_dolares` int(11) DEFAULT '1',
  `mostrar_info_comanda` int(11) DEFAULT '1',
  `aplicar_a` int(11) DEFAULT '1',
  `calculo_automatico` int(11) DEFAULT '0',
  `mostrar_sd` int(11) DEFAULT '1',
  `switch_propina` int(11) DEFAULT '1',
  `facturar_propina` int(11) DEFAULT '1',
  `idioma` int(11) DEFAULT '1',
  `mostrar_nombre` int(11) DEFAULT '1',
  `mostrar_domicilio` int(11) DEFAULT '1',
  `mostrar_tel` int(11) DEFAULT '1',
  `switch_info_ticket` int(11) DEFAULT '1',
  `mostrar_info_empresa` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `com_configuracion` WRITE;
/*!40000 ALTER TABLE `com_configuracion` DISABLE KEYS */;

INSERT INTO `com_configuracion` (`id`, `propina`, `consumo`, `reservaciones`, `seguridad`, `tipo_operacion`, `pedir_pass`, `mostrar_dolares`, `mostrar_info_comanda`, `aplicar_a`, `calculo_automatico`, `mostrar_sd`, `switch_propina`, `facturar_propina`, `idioma`, `mostrar_nombre`, `mostrar_domicilio`, `mostrar_tel`, `switch_info_ticket`, `mostrar_info_empresa`)
VALUES
	(1,1,0,1,NULL,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1);

/*!40000 ALTER TABLE `com_configuracion` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla com_kits
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_kits`;

CREATE TABLE `com_kits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) DEFAULT NULL,
  `dias` varchar(50) DEFAULT NULL,
  `inicio` varchar(10) DEFAULT NULL,
  `fin` varchar(10) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_kitsXproductos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_kitsXproductos`;

CREATE TABLE `com_kitsXproductos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_kit` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_lugar
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_lugar`;

CREATE TABLE `com_lugar` (
  `id_lugar` int(11) NOT NULL AUTO_INCREMENT,
  `lugar` varchar(50) NOT NULL,
  PRIMARY KEY (`id_lugar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_menus`;

CREATE TABLE `com_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) DEFAULT NULL,
  `nombre_restaurante` varchar(30) DEFAULT NULL,
  `estilo` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_mesas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_mesas`;

CREATE TABLE `com_mesas` (
  `id_mesa` int(11) NOT NULL AUTO_INCREMENT,
  `idDep` int(11) NOT NULL DEFAULT '0',
  `personas` int(11) DEFAULT '0',
  `tipo` int(11) DEFAULT '0',
  `nombre` varchar(60) DEFAULT '',
  `area` varchar(60) DEFAULT NULL,
  `domicilio` varchar(150) DEFAULT '',
  `idempleado` int(11) DEFAULT NULL,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `idSuc` int(11) NOT NULL,
  `id_via_contacto` int(11) DEFAULT NULL,
  `notificacion` int(11) DEFAULT '0',
  `id_zona_reparto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_mesa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_meseros
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_meseros`;

CREATE TABLE `com_meseros` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_mesero` int(11) DEFAULT NULL,
  `permisos` text COLLATE utf8_unicode_ci,
  `asignacion` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla com_pedidos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_pedidos`;

CREATE TABLE `com_pedidos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idcomanda` int(11) DEFAULT '0',
  `idproducto` int(11) DEFAULT '0',
  `cantidad` int(11) DEFAULT '0',
  `npersona` int(11) DEFAULT '0',
  `tipo` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `opcionales` text,
  `adicionales` text,
  `sin` text,
  `nota` varchar(100) DEFAULT NULL,
  `nota_opcional` varchar(100) DEFAULT NULL,
  `nota_extra` varchar(100) DEFAULT NULL,
  `nota_sin` varchar(100) DEFAULT NULL,
  `id_sub_comanda` int(11) DEFAULT '0',
  `inicio` datetime DEFAULT NULL,
  `fin` datetime DEFAULT NULL,
  `tiempo` int(11) DEFAULT '0',
  `origen` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_pedidos_combo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_pedidos_combo`;

CREATE TABLE `com_pedidos_combo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_comanda` int(11) NOT NULL DEFAULT '0',
  `id_producto` int(11) DEFAULT '0',
  `persona` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `opcionales` text,
  `extras` text,
  `sin` text,
  `nota_opcional` varchar(100) DEFAULT NULL,
  `nota_extra` varchar(100) DEFAULT NULL,
  `nota_sin` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_pedidos_kit
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_pedidos_kit`;

CREATE TABLE `com_pedidos_kit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_comanda` int(11) NOT NULL DEFAULT '0',
  `id_producto` int(11) DEFAULT '0',
  `persona` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `opcionales` text,
  `extras` text,
  `sin` text,
  `nota_opcional` varchar(100) DEFAULT NULL,
  `nota_extra` varchar(100) DEFAULT NULL,
  `nota_sin` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_preparaciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_preparaciones`;

CREATE TABLE `com_preparaciones` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `f_ini` datetime DEFAULT NULL,
  `f_fin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_productos_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_productos_menu`;

CREATE TABLE `com_productos_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` varchar(5) NOT NULL DEFAULT '',
  `id_padre` varchar(5) DEFAULT NULL,
  `id_menu` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_productos_propina
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_productos_propina`;

CREATE TABLE `com_productos_propina` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idproducto` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_promociones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_promociones`;

CREATE TABLE `com_promociones` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `tipo` int(1) NOT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `cantidad_descuento` decimal(10,2) DEFAULT NULL,
  `descuento` decimal(5,2) DEFAULT NULL,
  `dias` varchar(50) DEFAULT NULL,
  `inicio` varchar(10) DEFAULT NULL,
  `fin` varchar(10) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_promocionesXproductos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_promocionesXproductos`;

CREATE TABLE `com_promocionesXproductos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_promocion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla com_propinas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_propinas`;

CREATE TABLE `com_propinas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_venta` int(11) DEFAULT NULL,
  `metodo_pago` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_recetas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_recetas`;

CREATE TABLE `com_recetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `ganancia` decimal(10,2) DEFAULT NULL,
  `ids_insumos` text COLLATE utf8_unicode_ci NOT NULL,
  `ids_insumos_preparados` text COLLATE utf8_unicode_ci,
  `preparacion` text COLLATE utf8_unicode_ci,
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla com_reservaciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_reservaciones`;

CREATE TABLE `com_reservaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `inicio` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `todoeldia` tinyint(4) NOT NULL,
  `descripcion` longtext NOT NULL,
  `color` varchar(16) NOT NULL,
  `tel` varchar(10) DEFAULT NULL,
  `idCliente` int(11) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT '1',
  `mesa` int(12) DEFAULT NULL,
  `num_personas` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idGrupo` (`tel`) USING BTREE,
  KEY `idCliente` (`idCliente`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_reservaciones_expediente
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_reservaciones_expediente`;

CREATE TABLE `com_reservaciones_expediente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAgenda` int(11) NOT NULL,
  `idExpediente` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idAgenda` (`idAgenda`) USING BTREE,
  KEY `idExpediente` (`idExpediente`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_reservaciones_grupo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_reservaciones_grupo`;

CREATE TABLE `com_reservaciones_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT '1',
  `idCliente` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idCliente` (`idCliente`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_sub_comandas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_sub_comandas`;

CREATE TABLE `com_sub_comandas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idpadre` int(11) NOT NULL,
  `mesa` int(11) DEFAULT NULL,
  `persona` int(11) DEFAULT NULL,
  `pedidos` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `empleado` int(11) DEFAULT NULL,
  `codigo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL,
  `id_venta` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla com_union
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_union`;

CREATE TABLE `com_union` (
  `idprincipal` int(11) DEFAULT '0',
  `idmesa` int(11) DEFAULT '0',
  `idcomanda` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_vias_contacto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_vias_contacto`;

CREATE TABLE `com_vias_contacto` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla com_zonas_reparto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `com_zonas_reparto`;

CREATE TABLE `com_zonas_reparto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Volcado de tabla comun_cliente
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comun_cliente`;

CREATE TABLE `comun_cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL DEFAULT '',
  `nombretienda` varchar(100) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `colonia` varchar(100) DEFAULT NULL,
  `idTipotienda` int(11) DEFAULT NULL,
  `idRubro` int(11) DEFAULT NULL,
  `idGiro` int(11) DEFAULT NULL,
  `idPromotor` int(11) DEFAULT NULL,
  `idRuta` int(11) DEFAULT NULL,
  `email` varchar(50) NOT NULL DEFAULT '',
  `celular` varchar(50) DEFAULT NULL,
  `cp` varchar(50) DEFAULT NULL,
  `idPais` int(11) DEFAULT '1',
  `idEstado` int(11) DEFAULT NULL,
  `idMunicipio` int(11) DEFAULT NULL,
  `escliente` int(11) DEFAULT NULL,
  `contacto` varchar(200) DEFAULT NULL,
  `id_tipo_fiscal` int(2) DEFAULT NULL,
  `rfc` varchar(30) DEFAULT NULL,
  `curp` varchar(30) DEFAULT NULL,
  `telefono1` varchar(15) DEFAULT NULL,
  `telefono2` varchar(15) DEFAULT NULL,
  `tipo_persona` int(2) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `limite_credito` float(10,2) DEFAULT '0.00',
  `dias_credito` int(11) DEFAULT '0',
  `id_grupo` int(11) DEFAULT '0',
  `num_ext` int(11) DEFAULT NULL,
  `num_int` int(11) DEFAULT NULL,
  `smsb` int(11) DEFAULT '0',
  `cuenta` int(5) NOT NULL DEFAULT '0',
  `codigo` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_moneda` int(11) NOT NULL DEFAULT '1',
  `id_clasificacion` int(11) DEFAULT NULL,
  `permitir_vtas_credito` tinyint(1) DEFAULT NULL,
  `id_tipo_credito` int(11) DEFAULT NULL,
  `permitir_exceder_limite` tinyint(1) DEFAULT NULL,
  `dcto_pronto_pago` double DEFAULT NULL,
  `intereses_moratorios` double DEFAULT NULL,
  `domicilio_entrega` varchar(45) DEFAULT NULL,
  `id_lista_precios` int(11) DEFAULT NULL,
  `envios` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `comision_vta` double DEFAULT NULL,
  `comision_cobranza` double DEFAULT NULL,
  `saldo` double DEFAULT NULL,
  `idBanco` int(11) DEFAULT NULL,
  `numero_cuenta_banco` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `idVendedor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla comun_facturacion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comun_facturacion`;

CREATE TABLE `comun_facturacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `nombre` int(11) DEFAULT NULL COMMENT 'Cliente>comun_cliente',
  `rfc` varchar(15) DEFAULT NULL COMMENT 'RFC',
  `razon_social` varchar(100) DEFAULT NULL COMMENT 'Razon social',
  `correo` varchar(100) DEFAULT NULL COMMENT 'Correo electronico',
  `pais` varchar(100) DEFAULT NULL COMMENT 'Pais',
  `regimen_fiscal` varchar(100) DEFAULT NULL COMMENT 'Regimen fiscal',
  `domicilio` varchar(200) DEFAULT NULL COMMENT 'Domicilio',
  `num_ext` varchar(20) DEFAULT NULL COMMENT 'Numero exterior',
  `cp` varchar(10) DEFAULT NULL COMMENT 'Codigo postal',
  `colonia` varchar(100) DEFAULT NULL COMMENT 'Colonia',
  `idPais` int(11) DEFAULT '1',
  `estado` int(11) DEFAULT NULL COMMENT 'Estado>estados',
  `ciudad` varchar(100) DEFAULT NULL COMMENT 'Ciudad',
  `municipio` varchar(100) DEFAULT NULL COMMENT 'Municipio',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla configuracion_manejadorestatus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `configuracion_manejadorestatus`;

CREATE TABLE `configuracion_manejadorestatus` (
  `idmanejador` int(11) NOT NULL AUTO_INCREMENT,
  `idestructura` int(11) DEFAULT NULL,
  `idcampo` int(11) DEFAULT NULL,
  `valordefault` int(11) DEFAULT NULL,
  PRIMARY KEY (`idmanejador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla configuracion_srpago
# ------------------------------------------------------------

DROP TABLE IF EXISTS `configuracion_srpago`;

CREATE TABLE `configuracion_srpago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `primer_apellido` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `segundo_apellido` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `calle` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `colonia` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ciudad` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `codigo_postal` int(11) NOT NULL,
  `cumpleanos` date DEFAULT NULL,
  `tarjeta` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ife_delantera` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ife_posterior` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `comprobante_domicilio` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contrato_pagina_1` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contrato_pagina_2` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contrato_pagina_3` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `compania` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ventas_mensuales` double NOT NULL,
  `ventas_promedio` double NOT NULL,
  `tipo_compania` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `firma` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `activo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_agrupador
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_agrupador`;

CREATE TABLE `constru_agrupador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_presupuesto` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_obra` int(11) DEFAULT NULL,
  `id_cat_agrupador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_altas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_altas`;

CREATE TABLE `constru_altas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_tipo_alta` int(11) DEFAULT NULL,
  `estatus` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `f_captura` timestamp NULL DEFAULT NULL,
  `f_ingreso` timestamp NULL DEFAULT NULL,
  `f_alta_i` timestamp NULL DEFAULT NULL,
  `f_baja_i` timestamp NULL DEFAULT NULL,
  `id_responsable` int(11) DEFAULT NULL,
  `id_agrupador` int(11) DEFAULT NULL,
  `id_especialidad` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `id_partida` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_depto` int(11) DEFAULT NULL,
  `tipo_alta` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oc_inst` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_familia` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_cc` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_area
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_area`;

CREATE TABLE `constru_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_especialidad` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_cat_especialidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_asignaciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_asignaciones`;

CREATE TABLE `constru_asignaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_recurso` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT '0',
  `id_partida` int(11) DEFAULT NULL,
  `fecha_asignacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `po_fecha` datetime DEFAULT NULL,
  `po_dias` decimal(12,6) DEFAULT NULL,
  `po_rendimiento` decimal(12,6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_id_partida` (`id_partida`),
  KEY `idx_id_recurso` (`id_recurso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_bit_entradas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_bit_entradas`;

CREATE TABLE `constru_bit_entradas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_oc` int(11) DEFAULT NULL,
  `id_almacenista` int(11) DEFAULT NULL,
  `observaciones` varchar(5000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_bit_nominaca
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_bit_nominaca`;

CREATE TABLE `constru_bit_nominaca` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_tecnico` int(11) DEFAULT NULL,
  `per_ini` timestamp NULL DEFAULT NULL,
  `per_fin` timestamp NULL DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(12,2) DEFAULT '0.00',
  `borrado` tinyint(1) DEFAULT '0',
  `id_aut` int(11) DEFAULT '0',
  `id_cc` int(11) DEFAULT '0',
  `estatus` tinyint(1) DEFAULT '0',
  `fechaaut` datetime DEFAULT NULL,
  `id_aut2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_bit_nominadest
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_bit_nominadest`;

CREATE TABLE `constru_bit_nominadest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_dest` int(11) DEFAULT NULL,
  `id_edif` int(11) DEFAULT NULL,
  `id_aut` int(11) DEFAULT NULL,
  `semana` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(12,2) DEFAULT '0.00',
  `total_est` decimal(12,2) DEFAULT '0.00',
  `borrado` tinyint(1) DEFAULT '0',
  `id_cc` int(11) DEFAULT '0',
  `estatus` tinyint(1) DEFAULT '0',
  `per_ini` timestamp NULL DEFAULT NULL,
  `per_fin` timestamp NULL DEFAULT NULL,
  `xxano` varchar(7) COLLATE utf8_unicode_ci DEFAULT '0',
  `fechaaut` datetime DEFAULT NULL,
  `id_aut2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_bit_pubasico
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_bit_pubasico`;

CREATE TABLE `constru_bit_pubasico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `pub_nombre` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unidtext` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` decimal(12,4) DEFAULT '0.0000',
  `precio` decimal(12,4) DEFAULT '0.0000',
  `fecha_creacion` datetime DEFAULT NULL,
  `preparacion` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_bit_puconcepto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_bit_puconcepto`;

CREATE TABLE `constru_bit_puconcepto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_concepto` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `preparacion` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_bit_remesa
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_bit_remesa`;

CREATE TABLE `constru_bit_remesa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `semana` int(11) DEFAULT NULL,
  `remesa` decimal(12,2) DEFAULT NULL,
  `estatus` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_bit_remesas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_bit_remesas`;

CREATE TABLE `constru_bit_remesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `tot_pasiv` decimal(12,2) DEFAULT '0.00',
  `rem_aut` decimal(12,2) DEFAULT '0.00',
  `semana` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `id_solicito` int(11) DEFAULT '0',
  `id_bit_remesa` int(11) DEFAULT NULL,
  `fechaaut` datetime DEFAULT NULL,
  `id_aut` int(11) DEFAULT NULL,
  `idaut` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_bit_salidas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_bit_salidas`;

CREATE TABLE `constru_bit_salidas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_oc` int(11) DEFAULT NULL,
  `id_bit_entrada` int(11) DEFAULT NULL,
  `id_almacenista` int(11) DEFAULT NULL,
  `observaciones` varchar(5000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `id_recibio` int(11) DEFAULT NULL,
  `id_autorizo` int(11) DEFAULT NULL,
  `id_entrego` int(11) DEFAULT NULL,
  `id_agru` int(11) DEFAULT '0',
  `id_area` int(11) DEFAULT '0',
  `id_esp` int(11) DEFAULT '0',
  `id_part` int(11) DEFAULT '0',
  `id_cc` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_cat_agrupador
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_cat_agrupador`;

CREATE TABLE `constru_cat_agrupador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agrupador` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_cat_area
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_cat_area`;

CREATE TABLE `constru_cat_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_cat_especialidad
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_cat_especialidad`;

CREATE TABLE `constru_cat_especialidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `especialidad` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_obra` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `constru_cat_especialidad` WRITE;
/*!40000 ALTER TABLE `constru_cat_especialidad` DISABLE KEYS */;

INSERT INTO `constru_cat_especialidad` (`id`, `especialidad`, `borrado`, `id_obra`)
VALUES
	(1,'Obra civil',0,0),
	(2,'Instalaciones',0,0);

/*!40000 ALTER TABLE `constru_cat_especialidad` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla constru_cat_partidas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_cat_partidas`;

CREATE TABLE `constru_cat_partidas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partida` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_obra` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_cat_proveedores
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_cat_proveedores`;

CREATE TABLE `constru_cat_proveedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `razon_social` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rfc` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `calle` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `colonia` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cp` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `municipio` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel_empresa` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paterno` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `materno` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_categoria
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_categoria`;

CREATE TABLE `constru_categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_familia` int(11) DEFAULT '0',
  `clave_cat` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `categoria` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sal_semanal` decimal(12,2) DEFAULT '0.00',
  `sal_mensual` decimal(12,2) DEFAULT '0.00',
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_cheques
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_cheques`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_config`;

CREATE TABLE `constru_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `autorizaciones` int(11) DEFAULT '0',
  `tiempo` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_contratista
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_contratista`;

CREATE TABLE `constru_contratista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) DEFAULT NULL,
  `domicilio` varchar(400) DEFAULT NULL,
  `colonia` varchar(200) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `rfc` varchar(45) DEFAULT NULL,
  `padron` varchar(45) DEFAULT NULL,
  `ciudad` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `cnic` varchar(45) DEFAULT NULL,
  `imss` varchar(45) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_obra` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Volcado de tabla constru_cuentas_cargo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_cuentas_cargo`;

CREATE TABLE `constru_cuentas_cargo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_costo` int(11) DEFAULT NULL,
  `cargo` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `constru_cuentas_cargo` WRITE;
/*!40000 ALTER TABLE `constru_cuentas_cargo` DISABLE KEYS */;

INSERT INTO `constru_cuentas_cargo` (`id`, `id_costo`, `cargo`, `borrado`)
VALUES
	(1,21,'MATERIALES','0'),
	(2,21,'ACARREOS','0'),
	(3,22,'MANO OBRA A DESTAJO','0'),
	(4,22,'MANO OBRA POR ADMINISTRACION','0'),
	(5,22,'PRESTACIONES  MANO OBRA','0'),
	(6,23,'RENTAS PROPIAS','0'),
	(7,23,'RENTAS TERCEROS','0'),
	(8,23,'FLETE DE EQUIPO','0'),
	(9,23,'REFACCIONES Y REPARACIONES','0'),
	(10,23,'LUBRICANTES','0'),
	(11,23,'COMBUSTIBLES','0'),
	(12,23,'HERRAMIENTA','0'),
	(13,24,'SUBCONTRATISTAS','0'),
	(14,25,'PERSONAL TECNICO','0'),
	(15,25,'PERSONAL  ADMINISTRATIVO','0'),
	(16,25,'PERSONAL COMPRAS','0'),
	(17,25,'PERSONAL COSTOS','0'),
	(18,25,'PERSONAL PROGRAMACION','0'),
	(19,25,'PERSONAL ALMACEN','0'),
	(20,25,'PERSONAL VIGILANCIA','0'),
	(21,25,'PERSONAL OPERACIÃ“N Y MTO EQUIPO','0'),
	(22,25,'VIATICOS Y HOSPEDAJE','0'),
	(23,25,'IMPUESTO ESTATAL 2.5%','0'),
	(24,25,'GRATIFICACIONES','0'),
	(25,25,'LIMPIEZA Y MANTENIMIENTO','0'),
	(26,25,'PLANOS Y PLOTEO','0'),
	(27,25,'RENTA DE INMUEBLES','0'),
	(28,25,'INSTALACIONES PROVISIONALES ','0'),
	(29,25,'RENTA DE EQUIPO Y MAQUINARIA','0'),
	(30,25,'RENTA DE EQUIPO DE OFICINA','0'),
	(31,25,'REPARACIONES MENORES Y REFACCIONES','0'),
	(32,25,'REPARACIONES MAYORES Y REFACCIONES','0'),
	(33,25,'LUZ, AGUA Y ENERGIA','0'),
	(34,25,'PAPELERIA Y ARTICULOS DE ESCRITORIO','0'),
	(35,25,'COMUNICACIONES','0'),
	(36,25,'PASAJES EN TRANSITO','0'),
	(37,25,'SEGURIDAD E HIGIENE','0'),
	(38,25,'CUOTAS SINDICALES','0'),
	(39,25,'FLETES Y MUDANZAS','0'),
	(40,25,'RELACIONES PUBLICAS Y ATENCIONES','0'),
	(41,25,'CONTROL DE CALIDAD Y PRUEBAS','0'),
	(42,25,'COMBUSTIBLE Y LUBRICANTES','0'),
	(43,25,'SEGUROS','0'),
	(44,25,'FIANZAS','0'),
	(45,25,'TRANSPORTES LOCALES','0'),
	(46,25,'FOTOCOPIADO','0'),
	(47,25,'FOTOGRAFIA, REVELADO Y FILMACIONES','0'),
	(48,25,'MANTENIMIENTO DE EQUIPO DE OFICINA Y COMPUTO','0'),
	(49,25,'GASTOS LEGALES','0'),
	(50,25,'PAQUETERIA Y GASTOS DE ENVIO','0'),
	(51,25,'TRAMITES ADUANALES','0'),
	(52,25,'OBRA COMISIONES BANCARIAS','0'),
	(53,25,'SERVICIO VIGILANCIA EXTERNA','0'),
	(54,25,'GASTOS DIVERSOS DEDUCIBLES','0'),
	(55,25,'GASTOS DIVERSOS NO DEDUCIBLES','0'),
	(56,25,'IVA VIVIENDA 16%','0'),
	(57,25,'PROVISION DE COSTO','0'),
	(58,25,'HONORARIOS CON RETENCION','0'),
	(59,25,'TRASLADO DE VALORES','0'),
	(60,25,'MATERIALES ALMACENABLES','0'),
	(61,25,'MATERIALES SIN CARGO AL CLIENTE','0');

/*!40000 ALTER TABLE `constru_cuentas_cargo` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla constru_cuentas_cc
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_cuentas_cc`;

CREATE TABLE `constru_cuentas_cc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cp` int(11) DEFAULT NULL,
  `cc` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `constru_cuentas_cc` WRITE;
/*!40000 ALTER TABLE `constru_cuentas_cc` DISABLE KEYS */;

INSERT INTO `constru_cuentas_cc` (`id`, `id_cp`, `cc`, `borrado`)
VALUES
	(1,1,'TERRENOS','0'),
	(2,2,'TRAMITES','0'),
	(3,3,'INGENIERIAS','0'),
	(4,4,'COSTO DIRECTO','0'),
	(5,4,'COSTO INDIRECTO','0');

/*!40000 ALTER TABLE `constru_cuentas_cc` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla constru_cuentas_costo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_cuentas_costo`;

CREATE TABLE `constru_cuentas_costo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cc` int(11) DEFAULT NULL,
  `costo` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `constru_cuentas_costo` WRITE;
/*!40000 ALTER TABLE `constru_cuentas_costo` DISABLE KEYS */;

INSERT INTO `constru_cuentas_costo` (`id`, `id_cc`, `costo`, `borrado`)
VALUES
	(1,1,'RESERVA 1','0'),
	(2,1,'OTRO','0'),
	(3,2,'AGUA','0'),
	(4,2,'LUZ','0'),
	(5,2,'DRENAJE','0'),
	(6,2,'IMPACTO AMBIENTAL','0'),
	(7,2,'SEDUVI','0'),
	(8,2,'SETRAVI','0'),
	(9,2,'PROTECCION CIVIL','0'),
	(10,2,'OTRO','0'),
	(11,3,'PROY. ARQUITECTONICO','0'),
	(12,3,'PROY. ESTRUCTURAL','0'),
	(13,3,'PROY. HIDRAULICO','0'),
	(14,3,'PROY. SANITARIO','0'),
	(15,3,'PROY. ELECTRICO','0'),
	(16,3,'PROY. ELEVADORES','0'),
	(17,3,'PROY. CCTV','0'),
	(18,3,'PROY. SONIDO','0'),
	(19,3,'PROY. VOZ Y DATOS','0'),
	(20,3,'OTROS','0'),
	(21,4,'MATERIALES','0'),
	(22,4,'MANO DE OBRA','0'),
	(23,4,'HERR. Y EQUIPO','0'),
	(24,4,'SUBCONTRATOS','0'),
	(25,5,'INDIRECTO CAMPO','0');

/*!40000 ALTER TABLE `constru_cuentas_costo` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla constru_cuentas_cp
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_cuentas_cp`;

CREATE TABLE `constru_cuentas_cp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cp` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `constru_cuentas_cp` WRITE;
/*!40000 ALTER TABLE `constru_cuentas_cp` DISABLE KEYS */;

INSERT INTO `constru_cuentas_cp` (`id`, `cp`, `borrado`)
VALUES
	(1,'RESERVA TERRITORIAL','0'),
	(2,'TRAMITES Y PERMISOS','0'),
	(3,'PROYECTO E INGENIERIAS','0'),
	(4,'CONSTRUCCION','0'),
	(5,'CONTROL ANTICIPOS','0');

/*!40000 ALTER TABLE `constru_cuentas_cp` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla constru_deptos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_deptos`;

CREATE TABLE `constru_deptos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave_nomina` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `departamento` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `constru_deptos` WRITE;
/*!40000 ALTER TABLE `constru_deptos` DISABLE KEYS */;

INSERT INTO `constru_deptos` (`id`, `clave_nomina`, `departamento`, `borrado`)
VALUES
	(1,'DEPT-1','DEPTO OFICINA CENTRAL',0),
	(2,'DEPT-2','DEPTO ADMINISTRATIVO OBRA',0);

/*!40000 ALTER TABLE `constru_deptos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla constru_desgloce
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_desgloce`;

CREATE TABLE `constru_desgloce` (
  `id` int(1) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `id_obra` int(1) DEFAULT NULL,
  `clave` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `importe` decimal(12,2) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_cc` int(11) DEFAULT '0',
  `por` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_docs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_docs`;

CREATE TABLE `constru_docs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alta` int(11) DEFAULT NULL,
  `acta` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ife` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `curp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imss` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `infonavit` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `carta_penales` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domicilio` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_entrada_almacen
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_entrada_almacen`;

CREATE TABLE `constru_entrada_almacen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_oc` int(11) DEFAULT NULL,
  `id_req` int(11) DEFAULT NULL,
  `id_almacenista` int(11) DEFAULT NULL,
  `fecha_entrada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estatus` tinyint(1) DEFAULT '0',
  `llego` decimal(12,2) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_insumo` int(11) DEFAULT NULL,
  `id_bit_entrada` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_entris
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_entris`;

CREATE TABLE `constru_entris` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` tinyint(1) DEFAULT NULL,
  `almacenista` int(11) DEFAULT NULL,
  `estatus` tinyint(1) DEFAULT '0',
  `fecha_entrada` timestamp NULL DEFAULT NULL,
  `fecha_captura` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `observaciones` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_escaneo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_escaneo`;

CREATE TABLE `constru_escaneo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alta` int(11) DEFAULT NULL,
  `contrato_e` tinyint(1) DEFAULT '0',
  `foto_e` tinyint(1) DEFAULT '0',
  `acta_e` tinyint(1) DEFAULT '0',
  `ife_e` tinyint(1) DEFAULT '0',
  `curp_e` tinyint(1) DEFAULT '0',
  `imss_e` tinyint(1) DEFAULT '0',
  `infonavit_e` tinyint(1) DEFAULT '0',
  `carta_penales_e` tinyint(1) DEFAULT '0',
  `domicilio_e` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_especialidad
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_especialidad`;

CREATE TABLE `constru_especialidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_agrupador` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_id_agrupador` (`id_agrupador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_estatus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estatus`;

CREATE TABLE `constru_estatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_estimaciones_bit_chica
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_bit_chica`;

CREATE TABLE `constru_estimaciones_bit_chica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `id_autorizo` int(11) DEFAULT '0',
  `imp_estimacion` decimal(12,2) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `iva` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `id_cc` int(11) DEFAULT NULL,
  `semana` int(11) DEFAULT '0',
  `estatus` tinyint(1) DEFAULT '0',
  `xxano` varchar(7) COLLATE utf8_unicode_ci DEFAULT '0',
  `fechaaut` datetime DEFAULT NULL,
  `id_aut2` int(11) DEFAULT NULL,
  `id_autorizo2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_estimaciones_bit_cliente
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_bit_cliente`;

CREATE TABLE `constru_estimaciones_bit_cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `id_agru` int(11) DEFAULT '0',
  `id_area` int(11) DEFAULT '0',
  `id_esp` int(11) DEFAULT '0',
  `id_part` int(11) DEFAULT '0',
  `id_autorizo` int(11) DEFAULT '0',
  `imp_contrato` decimal(12,2) DEFAULT NULL,
  `ade1` int(11) DEFAULT '0',
  `ade2` int(11) DEFAULT '0',
  `ade3` int(11) DEFAULT '0',
  `imp_tot_contrato` decimal(12,2) DEFAULT NULL,
  `anticipo` decimal(12,2) DEFAULT NULL,
  `amortizado_anterior` decimal(12,2) DEFAULT NULL,
  `amortizado_estimacion` decimal(12,2) DEFAULT NULL,
  `tot_amortizado` decimal(12,2) DEFAULT NULL,
  `por_amortizar` decimal(12,2) DEFAULT NULL,
  `imp_estimacion` decimal(12,2) DEFAULT NULL,
  `amortizacion` decimal(12,2) DEFAULT NULL,
  `subtotal1` decimal(12,2) DEFAULT NULL,
  `fondo_garantia` decimal(12,2) DEFAULT NULL,
  `retencion` decimal(12,2) DEFAULT NULL,
  `cargos` decimal(12,2) DEFAULT NULL,
  `subtotal2` decimal(12,2) DEFAULT NULL,
  `iva` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `fgp` decimal(12,2) DEFAULT '0.00',
  `rep` decimal(12,2) DEFAULT '0.00',
  `semana` int(11) DEFAULT '0',
  `estatus` tinyint(1) DEFAULT '0',
  `xxano` varchar(7) COLLATE utf8_unicode_ci DEFAULT '0',
  `fechaaut` datetime DEFAULT NULL,
  `id_aut2` int(11) DEFAULT NULL,
  `id_autorizo2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_estimaciones_bit_destajista
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_bit_destajista`;

CREATE TABLE `constru_estimaciones_bit_destajista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_destajista` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `subtotal1` decimal(12,2) DEFAULT NULL,
  `retencion` decimal(12,2) DEFAULT NULL,
  `cargos` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_agru` int(11) DEFAULT '0',
  `id_area` int(11) DEFAULT '0',
  `id_esp` int(11) DEFAULT '0',
  `id_part` int(11) DEFAULT '0',
  `id_autorizo` int(11) DEFAULT '0',
  `pret` decimal(12,2) DEFAULT '0.00',
  `semana` int(11) DEFAULT '0',
  `id_cc` int(11) DEFAULT '0',
  `estatus` tinyint(1) DEFAULT '0',
  `rep` decimal(12,2) DEFAULT '0.00',
  `xxano` varchar(7) COLLATE utf8_unicode_ci DEFAULT '0',
  `id_autorizo2` int(11) DEFAULT NULL,
  `fechaaut` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_estimaciones_bit_indirectos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_bit_indirectos`;

CREATE TABLE `constru_estimaciones_bit_indirectos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `id_autorizo` int(11) DEFAULT '0',
  `imp_estimacion` decimal(12,2) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `iva` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `id_cc` int(11) DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `semana` int(11) DEFAULT '0',
  `id_prov` int(11) DEFAULT '0',
  `estatus` tinyint(1) DEFAULT '0',
  `xxano` varchar(7) COLLATE utf8_unicode_ci DEFAULT '0',
  `fechaaut` datetime DEFAULT NULL,
  `id_aut2` int(11) DEFAULT NULL,
  `id_autorizo2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_estimaciones_bit_prov
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_bit_prov`;

CREATE TABLE `constru_estimaciones_bit_prov` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `id_autorizo` int(11) DEFAULT '0',
  `imp_estimacion` decimal(12,2) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `iva` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `semana` int(11) DEFAULT '0',
  `id_oc` int(11) DEFAULT '0',
  `id_prov` int(11) DEFAULT '0',
  `estatus` tinyint(1) DEFAULT '0',
  `xxano` varchar(7) COLLATE utf8_unicode_ci DEFAULT '0',
  `id_autorizo2` int(11) DEFAULT NULL,
  `fechaaut` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_estimaciones_bit_subcontratista
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_bit_subcontratista`;

CREATE TABLE `constru_estimaciones_bit_subcontratista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_subcontratista` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `id_agru` int(11) DEFAULT '0',
  `id_area` int(11) DEFAULT '0',
  `id_esp` int(11) DEFAULT '0',
  `id_part` int(11) DEFAULT '0',
  `id_autorizo` int(11) DEFAULT '0',
  `imp_contrato` decimal(12,2) DEFAULT NULL,
  `ade1` int(11) DEFAULT '0',
  `ade2` int(11) DEFAULT '0',
  `ade3` int(11) DEFAULT '0',
  `imp_tot_contrato` decimal(12,2) DEFAULT NULL,
  `anticipo` decimal(12,2) DEFAULT NULL,
  `amortizado_anterior` decimal(12,2) DEFAULT NULL,
  `amortizado_estimacion` decimal(12,2) DEFAULT NULL,
  `tot_amortizado` decimal(12,2) DEFAULT NULL,
  `por_amortizar` decimal(12,2) DEFAULT NULL,
  `imp_estimacion` decimal(12,2) DEFAULT NULL,
  `amortizacion` decimal(12,2) DEFAULT NULL,
  `subtotal1` decimal(12,2) DEFAULT NULL,
  `fondo_garantia` decimal(12,2) DEFAULT NULL,
  `retencion` decimal(12,2) DEFAULT NULL,
  `cargos` decimal(12,2) DEFAULT NULL,
  `subtotal2` decimal(12,2) DEFAULT NULL,
  `iva` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `id_cc` int(11) DEFAULT NULL,
  `fgp` decimal(12,2) DEFAULT '0.00',
  `rep` decimal(12,2) DEFAULT '0.00',
  `factura` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estatus` tinyint(1) DEFAULT '0',
  `semana` int(11) DEFAULT '0',
  `xxano` varchar(7) COLLATE utf8_unicode_ci DEFAULT '0',
  `id_autorizo2` int(11) DEFAULT NULL,
  `fechaaut` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_estimaciones_chica
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_chica`;

CREATE TABLE `constru_estimaciones_chica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `concepto` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unidad` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cantidad` decimal(12,2) DEFAULT NULL,
  `val_fact` decimal(12,2) DEFAULT NULL,
  `sestmp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_bit_chica` int(11) DEFAULT '0',
  `id_cc` int(11) DEFAULT '0',
  `factura` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iva` decimal(12,2) DEFAULT '0.00',
  `otro` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaaut` datetime DEFAULT NULL,
  `idaut` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_estimaciones_cliente
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_cliente`;

CREATE TABLE `constru_estimaciones_cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_insumo` int(11) DEFAULT NULL,
  `vol_tope` decimal(12,2) DEFAULT '0.00',
  `importe` decimal(12,2) DEFAULT '0.00',
  `vol_estimacion` decimal(12,2) DEFAULT '0.00',
  `imp_estimacion` decimal(12,2) DEFAULT '0.00',
  `id_bit_cliente` int(11) DEFAULT NULL,
  `sestmp` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  `borrado` tinyint(1) DEFAULT '0',
  `vol_anterior` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_estimaciones_destajista
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_destajista`;

CREATE TABLE `constru_estimaciones_destajista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_clave` int(11) DEFAULT NULL,
  `vol_tope` decimal(12,2) DEFAULT '0.00',
  `vol_anterior` decimal(12,2) DEFAULT '0.00',
  `vol_est` decimal(12,2) DEFAULT '0.00',
  `vol_acu` decimal(12,2) DEFAULT '0.00',
  `vol_eje` decimal(12,2) DEFAULT '0.00',
  `imp_est` decimal(12,2) DEFAULT '0.00',
  `id_bit_destajista` int(11) DEFAULT NULL,
  `sestmp` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  `borrado` tinyint(1) DEFAULT '0',
  `id_agru` int(11) DEFAULT '0',
  `id_area` int(11) DEFAULT '0',
  `id_esp` int(11) DEFAULT '0',
  `id_part` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_estimaciones_indirectos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_indirectos`;

CREATE TABLE `constru_estimaciones_indirectos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `clave` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `concepto` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unidtext` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cantidad` decimal(12,2) DEFAULT NULL,
  `pu_indirecto` decimal(12,2) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `sestmp` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  `id_bit_indirectos` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_estimaciones_prov
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_prov`;

CREATE TABLE `constru_estimaciones_prov` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_clave` int(11) DEFAULT NULL,
  `id_oc` int(11) DEFAULT NULL,
  `id_bit_prov` int(11) DEFAULT '0',
  `sestmp` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  `borrado` tinyint(1) DEFAULT '0',
  `vol_anterior` decimal(12,2) DEFAULT '0.00',
  `vol_gris` decimal(12,2) DEFAULT '0.00',
  `vol_acu` decimal(12,2) DEFAULT '0.00',
  `vol_eje` decimal(12,2) DEFAULT '0.00',
  `imp_est` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_estimaciones_subcontratista
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_estimaciones_subcontratista`;

CREATE TABLE `constru_estimaciones_subcontratista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_insumo` int(11) DEFAULT NULL,
  `vol_tope` decimal(12,2) DEFAULT '0.00',
  `importe` decimal(12,2) DEFAULT '0.00',
  `vol_estimacion` decimal(12,2) DEFAULT '0.00',
  `imp_estimacion` decimal(12,2) DEFAULT '0.00',
  `id_bit_subcontratista` int(11) DEFAULT NULL,
  `sestmp` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  `borrado` tinyint(1) DEFAULT '0',
  `vol_anterior` decimal(12,2) DEFAULT '0.00',
  `id_agru` int(11) DEFAULT '0',
  `id_area` int(11) DEFAULT '0',
  `id_esp` int(11) DEFAULT '0',
  `id_part` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_famat
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_famat`;

CREATE TABLE `constru_famat` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `id_obra` int(1) DEFAULT NULL,
  `nomfam` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_familias
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_familias`;

CREATE TABLE `constru_familias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_categoria_familia` int(11) DEFAULT NULL,
  `clave_familia` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `familia` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_fondo_retencion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_fondo_retencion`;

CREATE TABLE `constru_fondo_retencion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_ds` int(11) DEFAULT NULL,
  `fondo` decimal(12,2) DEFAULT '0.00',
  `retencion` decimal(12,2) DEFAULT '0.00',
  `fecha_act` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Volcado de tabla constru_gen_nomdest
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_gen_nomdest`;

CREATE TABLE `constru_gen_nomdest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `semana` int(11) DEFAULT NULL,
  `id_dest` int(11) DEFAULT NULL,
  `id_emp` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT NULL,
  `dt` int(11) DEFAULT NULL,
  `id_bit_nom` int(11) DEFAULT NULL,
  `he` int(11) DEFAULT '0',
  `df` int(11) DEFAULT '0',
  `idt` decimal(12,2) DEFAULT '0.00',
  `ihe` decimal(12,2) DEFAULT '0.00',
  `idf` decimal(12,2) DEFAULT '0.00',
  `desci` decimal(12,2) DEFAULT '0.00',
  `finis` decimal(12,2) DEFAULT '0.00',
  `subt` decimal(12,2) DEFAULT '0.00',
  `difd` decimal(12,2) DEFAULT '0.00',
  `total` decimal(12,2) DEFAULT '0.00',
  `xxano` varchar(7) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_gen_nomtec
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_gen_nomtec`;

CREATE TABLE `constru_gen_nomtec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `semana` int(11) DEFAULT NULL,
  `id_tecnico` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT NULL,
  `dt` int(11) DEFAULT NULL,
  `id_bit_nom` int(11) DEFAULT NULL,
  `he` int(11) DEFAULT '0',
  `idt` decimal(12,2) DEFAULT '0.00',
  `ihe` decimal(12,2) DEFAULT '0.00',
  `desci` decimal(12,2) DEFAULT '0.00',
  `finis` decimal(12,2) DEFAULT '0.00',
  `subt` decimal(12,2) DEFAULT '0.00',
  `total` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_generales
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_generales`;

CREATE TABLE `constru_generales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `licitacion` varchar(45) DEFAULT NULL,
  `contrato` varchar(45) DEFAULT NULL,
  `cliente` varchar(200) DEFAULT NULL,
  `construye` int(11) DEFAULT NULL,
  `obra` varchar(200) DEFAULT NULL,
  `localizacion` varchar(200) DEFAULT NULL,
  `clave` varchar(45) DEFAULT NULL,
  `inicio` timestamp NULL DEFAULT NULL,
  `termino` timestamp NULL DEFAULT NULL,
  `hon` decimal(12,2) DEFAULT '0.00',
  `iva` decimal(12,2) DEFAULT '0.00',
  `fsr` decimal(12,2) DEFAULT '0.00',
  `presupuesto` decimal(12,2) DEFAULT '0.00',
  `anticipo` decimal(12,2) DEFAULT '0.00',
  `director` varchar(200) DEFAULT NULL,
  `superintendencia` varchar(200) DEFAULT NULL,
  `control` varchar(200) DEFAULT NULL,
  `sup_imss` varchar(200) DEFAULT NULL,
  `elaboro` varchar(200) DEFAULT NULL,
  `revision` varchar(200) DEFAULT NULL,
  `autorizacion` varchar(200) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_presupuesto` int(11) DEFAULT '0',
  `fecha_contrato` timestamp NULL DEFAULT NULL,
  `no_compromiso` varchar(45) DEFAULT NULL,
  `fecha_compromiso` timestamp NULL DEFAULT NULL,
  `no_obra` varchar(45) DEFAULT NULL,
  `ade1` decimal(12,2) DEFAULT '0.00',
  `ade2` decimal(12,2) DEFAULT '0.00',
  `ade3` decimal(12,2) DEFAULT '0.00',
  `ade4` decimal(12,2) DEFAULT '0.00',
  `ade5` decimal(12,2) DEFAULT '0.00',
  `telefono` varchar(600) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Volcado de tabla constru_info_sp
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_info_sp`;

CREATE TABLE `constru_info_sp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alta` int(11) DEFAULT NULL,
  `razon_social_sp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rfc_sp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `calle_sp` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `colonia_sp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cp_sp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `municipio_sp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado_sp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel_emp_sp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paterno_sp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `materno_sp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombres_sp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel_personal_sp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correo_sp` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imp_cont` decimal(12,2) DEFAULT '0.00',
  `ade1` decimal(12,2) DEFAULT '0.00',
  `ade2` decimal(12,2) DEFAULT '0.00',
  `ade3` decimal(12,2) DEFAULT '0.00',
  `anticipo` decimal(12,2) DEFAULT '0.00',
  `dias_credito` decimal(12,2) DEFAULT '0.00',
  `limite_credito` decimal(12,2) DEFAULT '0.00',
  `por_fondo_garantia` decimal(12,2) DEFAULT '0.00',
  `por_retencion` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_info_tdo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_info_tdo`;

CREATE TABLE `constru_info_tdo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alta` int(11) DEFAULT NULL,
  `nombre` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paterno` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `materno` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domicilio` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `colonia` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cp` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `municipio` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `civil` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel_personal` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `casado_con` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contacto_con` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono_con` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` timestamp NULL DEFAULT NULL,
  `correo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrado` tinyint(4) DEFAULT NULL,
  `imp_cont` decimal(12,2) DEFAULT '0.00',
  `ade1` decimal(12,2) DEFAULT '0.00',
  `ade2` decimal(12,2) DEFAULT '0.00',
  `ade3` decimal(12,2) DEFAULT '0.00',
  `dias_credito` decimal(12,2) DEFAULT '0.00',
  `limite_credito` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_insumos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_insumos`;

CREATE TABLE `constru_insumos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_presupuesto` int(11) DEFAULT NULL,
  `id_obra` int(11) DEFAULT NULL,
  `naturaleza` varchar(45) DEFAULT NULL,
  `clave` varchar(45) DEFAULT NULL,
  `materiales` varchar(45) DEFAULT NULL,
  `descripcion` varchar(5000) DEFAULT NULL,
  `id_um` int(11) DEFAULT NULL,
  `unidad` decimal(12,3) DEFAULT NULL,
  `precio` decimal(12,2) DEFAULT NULL,
  `importe` decimal(12,2) DEFAULT NULL,
  `unidtext` varchar(45) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_familia` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Volcado de tabla constru_lista15
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_lista15`;

CREATE TABLE `constru_lista15` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_tecnico` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT NULL,
  `asistio` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_notilog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_notilog`;

CREATE TABLE `constru_notilog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `observaciones` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `modulo` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_obrasusuario
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_obrasusuario`;

CREATE TABLE `constru_obrasusuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) DEFAULT NULL,
  `idobra` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_ocCanceladas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_ocCanceladas`;

CREATE TABLE `constru_ocCanceladas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_pedi` int(11) DEFAULT NULL,
  `id_requi` int(11) DEFAULT NULL,
  `id_clave` int(11) DEFAULT NULL,
  `precio_compra` decimal(12,2) DEFAULT '0.00',
  `fecha` datetime DEFAULT NULL,
  `cantidad` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_partida
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_partida`;

CREATE TABLE `constru_partida` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_area` int(11) DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `codigo` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_cat_partida` int(11) DEFAULT NULL,
  `id_obra` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_id_area` (`id_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_pedidos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_pedidos`;

CREATE TABLE `constru_pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_pedid` int(11) DEFAULT '0',
  `id_requis` int(11) DEFAULT NULL,
  `fecha_entrega` timestamp NULL DEFAULT NULL,
  `autorizo` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_captura` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `sestmp` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_pedis
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_pedis`;

CREATE TABLE `constru_pedis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` tinyint(1) DEFAULT NULL,
  `nombre` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `solicito` int(11) DEFAULT NULL,
  `estatus` tinyint(1) DEFAULT '0',
  `fecha_entrega` timestamp NULL DEFAULT NULL,
  `fecha_captura` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `entrada_alm` tinyint(1) DEFAULT '0',
  `id_prov` int(11) DEFAULT '0',
  `salida_alm` tinyint(1) DEFAULT '0',
  `xxano` varchar(7) COLLATE utf8_unicode_ci DEFAULT '0',
  `fpago` int(11) DEFAULT NULL,
  `obsgen` varchar(600) COLLATE utf8_unicode_ci DEFAULT NULL,
  `condpago` varchar(600) COLLATE utf8_unicode_ci DEFAULT '',
  `fechaaut` datetime DEFAULT NULL,
  `idaut` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_poGanttLink
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_poGanttLink`;

CREATE TABLE `constru_poGanttLink` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_link` varchar(200) DEFAULT NULL,
  `source` varchar(200) DEFAULT NULL,
  `target` varchar(200) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_prenomina
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_prenomina`;

CREATE TABLE `constru_prenomina` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `ano` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `semana` int(11) DEFAULT NULL,
  `id_dest` int(11) DEFAULT NULL,
  `id_emp` int(11) DEFAULT NULL,
  `hre` int(11) DEFAULT '0',
  `diasf` int(11) DEFAULT '0',
  `impdf` decimal(12,2) DEFAULT '0.00',
  `descinf` decimal(12,2) DEFAULT '0.00',
  `fini` decimal(12,2) DEFAULT '0.00',
  `difd` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_presupuesto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_presupuesto`;

CREATE TABLE `constru_presupuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_proyecto` int(11) DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `codigo` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_obra` int(11) DEFAULT '0',
  `archivo` varchar(500) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_proforma
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_proforma`;

CREATE TABLE `constru_proforma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `por_utilidad` decimal(12,2) DEFAULT '10.00',
  `de_utilidad` decimal(12,2) DEFAULT NULL,
  `por_indirecto` decimal(12,2) DEFAULT '8.00',
  `de_indirecto` decimal(12,2) DEFAULT NULL,
  `factor_salario` decimal(12,2) DEFAULT '1.56',
  `indirecto` decimal(12,2) DEFAULT '0.00',
  `borrado` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_proforma2
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_proforma2`;

CREATE TABLE `constru_proforma2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `costo_directo` decimal(12,2) DEFAULT NULL,
  `costo_directo_p` decimal(12,2) DEFAULT NULL,
  `indirecto_campo` decimal(12,2) DEFAULT NULL,
  `indirecto_campo_p` decimal(12,2) DEFAULT NULL,
  `indirecto_oc` decimal(12,2) DEFAULT NULL,
  `indirecto_oc_p` decimal(12,2) DEFAULT NULL,
  `utilidad` decimal(12,2) DEFAULT NULL,
  `utilidad_p` decimal(12,2) DEFAULT NULL,
  `importe_pres` decimal(12,2) DEFAULT NULL,
  `importe_presu_p` decimal(12,2) DEFAULT NULL,
  `factor_salario` decimal(12,2) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_proyecto_presupuesto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_proyecto_presupuesto`;

CREATE TABLE `constru_proyecto_presupuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `codigo` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_pubasico
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_pubasico`;

CREATE TABLE `constru_pubasico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_insumo` int(11) DEFAULT NULL,
  `id_bit_pubasico` int(11) DEFAULT NULL,
  `cantidad` decimal(12,4) DEFAULT '0.0000',
  `id_pubasico` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_puconcepto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_puconcepto`;

CREATE TABLE `constru_puconcepto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_insumo` int(11) DEFAULT NULL,
  `id_pubasico` int(11) DEFAULT NULL,
  `id_bit_puconcepto` int(11) DEFAULT NULL,
  `cantidad` decimal(12,4) DEFAULT '0.0000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_recurso
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_recurso`;

CREATE TABLE `constru_recurso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_partida` int(11) DEFAULT NULL,
  `id_naturaleza` int(11) DEFAULT NULL,
  `id_um` int(11) DEFAULT NULL,
  `naturaleza` varchar(45) DEFAULT 'Catalogo',
  `codigo` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `unidad` decimal(12,3) DEFAULT NULL,
  `descripcion` varchar(5000) DEFAULT NULL,
  `precio_costo` decimal(12,2) DEFAULT NULL,
  `precio_venta` decimal(12,2) DEFAULT NULL,
  `corto` varchar(100) DEFAULT NULL,
  `id_presupuesto` int(11) DEFAULT '0',
  `borrado` tinyint(1) DEFAULT '0',
  `unidtext` varchar(20) DEFAULT NULL,
  `pu_destajo` decimal(12,2) DEFAULT '0.00',
  `pu_subcontrato` decimal(12,2) DEFAULT '0.00',
  `esdestajo` tinyint(1) DEFAULT '0',
  `essubcontrato` tinyint(1) DEFAULT '0',
  `id_obra` int(11) DEFAULT '0',
  `autorizado` int(11) DEFAULT '1',
  `id_bit_solicitud` int(11) DEFAULT NULL,
  `justificación` varchar(500) DEFAULT NULL,
  `sestemp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_recurso_naturaleza
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_recurso_naturaleza`;

CREATE TABLE `constru_recurso_naturaleza` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_remesas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_remesas`;

CREATE TABLE `constru_remesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_bit_remesas` int(11) DEFAULT NULL,
  `id_prov` int(11) DEFAULT NULL,
  `id_subc` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_requis
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_requis`;

CREATE TABLE `constru_requis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `nombre` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_contratista` int(11) DEFAULT NULL,
  `fecha_entrega` timestamp NULL DEFAULT NULL,
  `solicito` int(11) DEFAULT NULL,
  `fecha_captura` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estatus` tinyint(1) DEFAULT '0',
  `borrado` tinyint(1) DEFAULT '0',
  `id_agru` int(11) DEFAULT '0',
  `id_area` int(11) DEFAULT '0',
  `id_esp` int(11) DEFAULT '0',
  `id_part` int(11) DEFAULT '0',
  `xxano` varchar(7) COLLATE utf8_unicode_ci DEFAULT '0',
  `obs_cancel` varchar(600) COLLATE utf8_unicode_ci DEFAULT '',
  `autorizo` int(11) DEFAULT NULL,
  `fechaaut` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_requisiciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_requisiciones`;

CREATE TABLE `constru_requisiciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_clave` int(11) DEFAULT NULL,
  `id_requi` int(11) DEFAULT NULL,
  `id_alta` int(11) DEFAULT NULL,
  `unidad` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cantidad` decimal(12,2) DEFAULT NULL,
  `descripcion` varchar(5000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_entrega` timestamp NULL DEFAULT NULL,
  `precio` decimal(12,2) DEFAULT NULL,
  `fecha_captura` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` tinyint(1) DEFAULT '0',
  `sestmp` varchar(45) COLLATE utf8_unicode_ci DEFAULT '0',
  `precio_compra` decimal(12,2) DEFAULT '0.00',
  `elprov` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_id_clave` (`id_clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_salida_almacen
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_salida_almacen`;

CREATE TABLE `constru_salida_almacen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_oc` int(11) DEFAULT NULL,
  `id_req` int(11) DEFAULT NULL,
  `id_almacenista` int(11) DEFAULT NULL,
  `fecha_entrada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estatus` tinyint(1) DEFAULT '0',
  `salio` decimal(12,2) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  `id_insumo` int(11) DEFAULT NULL,
  `id_bit_salida` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_tarea
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_tarea`;

CREATE TABLE `constru_tarea` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nivel` int(11) DEFAULT NULL,
  `id_recurso` int(11) DEFAULT NULL,
  `id_um` int(11) DEFAULT NULL,
  `unidad` decimal(12,3) DEFAULT NULL,
  `descripcion` varchar(5000) CHARACTER SET latin1 DEFAULT NULL,
  `precio_coste` decimal(12,2) DEFAULT NULL,
  `precio_venta` decimal(12,2) DEFAULT NULL,
  `id_estatus` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_tipo_altas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_tipo_altas`;

CREATE TABLE `constru_tipo_altas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_tomaduria
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_tomaduria`;

CREATE TABLE `constru_tomaduria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_dest` int(11) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `ano` varchar(4) COLLATE utf8_unicode_ci DEFAULT '2015',
  `semana` int(11) DEFAULT '0',
  `lun` tinyint(1) DEFAULT '0',
  `mar` tinyint(1) DEFAULT '0',
  `mie` tinyint(1) DEFAULT '0',
  `jue` tinyint(1) DEFAULT '0',
  `vie` tinyint(1) DEFAULT '0',
  `sab` tinyint(1) DEFAULT '0',
  `dom` tinyint(1) DEFAULT '0',
  `hre` int(11) DEFAULT '0',
  `diasf` int(11) DEFAULT '0',
  `impdf` decimal(12,2) DEFAULT '0.00',
  `descinf` decimal(12,2) DEFAULT '0.00',
  `fini` decimal(12,2) DEFAULT '0.00',
  `difd` decimal(12,2) DEFAULT '0.00',
  `per_ini` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `per_fin` timestamp NULL DEFAULT NULL,
  `imphe` decimal(12,2) DEFAULT '0.00',
  `id_bit` int(11) DEFAULT '0',
  `xxano` varchar(7) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_tomaduria_tec1
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_tomaduria_tec1`;

CREATE TABLE `constru_tomaduria_tec1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_tecnico` int(11) DEFAULT NULL,
  `ano` varchar(4) COLLATE utf8_unicode_ci DEFAULT '2015',
  `semana` int(11) DEFAULT '0',
  `lun` tinyint(1) DEFAULT '0',
  `mar` tinyint(1) DEFAULT '0',
  `mie` tinyint(1) DEFAULT '0',
  `jue` tinyint(1) DEFAULT '0',
  `vie` tinyint(1) DEFAULT '0',
  `sab` tinyint(1) DEFAULT '0',
  `hre` int(11) DEFAULT '0',
  `impdf` decimal(12,2) DEFAULT '0.00',
  `descinf` decimal(12,2) DEFAULT '0.00',
  `fini` decimal(12,2) DEFAULT '0.00',
  `impviati` decimal(12,2) DEFAULT '0.00',
  `per_ini` timestamp NULL DEFAULT NULL,
  `per_fin` timestamp NULL DEFAULT NULL,
  `imphe` decimal(12,2) DEFAULT '0.00',
  `id_bit` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla constru_um
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_um`;

CREATE TABLE `constru_um` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_uploadPO
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_uploadPO`;

CREATE TABLE `constru_uploadPO` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `presupuestoxls` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `opcion` varchar(1) CHARACTER SET latin1 DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `id_obra` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla constru_vol_tope
# ------------------------------------------------------------

DROP TABLE IF EXISTS `constru_vol_tope`;

CREATE TABLE `constru_vol_tope` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obra` int(11) DEFAULT NULL,
  `id_clave` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `id_partida` int(11) DEFAULT '0',
  `vol_tope` decimal(12,2) DEFAULT NULL,
  `borrado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_id_area` (`id_area`),
  KEY `idx_id_clave` (`id_clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla cont_account_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_account_status`;

CREATE TABLE `cont_account_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_account_status` WRITE;
/*!40000 ALTER TABLE `cont_account_status` DISABLE KEYS */;

INSERT INTO `cont_account_status` (`status_id`, `description`)
VALUES
	(1,'ACTIVO'),
	(2,'INACTIVO');

/*!40000 ALTER TABLE `cont_account_status` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_accounts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_accounts`;

CREATE TABLE `cont_accounts` (
  `account_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `account_code` varchar(100) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Codigo',
  `manual_code` varchar(100) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Codigo Manual',
  `description` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT 'Nombre',
  `sec_desc` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT 'NomIdioma',
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
  `main_father` int(11) DEFAULT '0' COMMENT 'Ancestro de Mayor',
  `cuentaoficial` varchar(10) NOT NULL DEFAULT '0',
  `nif` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_id`),
  KEY `accounts_main_father_idx` (`main_father`),
  KEY `accounts_manual_code_idx` (`manual_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_bancos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_bancos`;

CREATE TABLE `cont_bancos` (
  `idbanco` int(11) NOT NULL AUTO_INCREMENT,
  `Clave` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idbanco`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `cont_bancos` WRITE;
/*!40000 ALTER TABLE `cont_bancos` DISABLE KEYS */;

INSERT INTO `cont_bancos` (`idbanco`, `Clave`, `nombre`)
VALUES
	(1,'2','BANAMEX'),
	(2,'6','BANCOMEXT'),
	(3,'9','BANOBRAS'),
	(4,'12','BBVA BANCOMER'),
	(5,'14','SANTANDER'),
	(6,'19','BANJERCITO'),
	(7,'21','HSBC'),
	(8,'30','BAJIO'),
	(9,'32','IXE'),
	(10,'36','INBURSA'),
	(11,'37','INTERACCIONES'),
	(12,'42','MIFEL'),
	(13,'44','SCOTIABANK'),
	(14,'58','BANREGIO'),
	(15,'59','INVEX'),
	(16,'60','BANSI'),
	(17,'62','AFIRME'),
	(18,'72','BANORTE'),
	(19,'102','THE ROYAL BANK'),
	(20,'103','AMERICAN EXPRESS'),
	(21,'106','BAMSA'),
	(22,'108','TOKYO'),
	(23,'110','JP MORGAN'),
	(24,'112','BMONEX'),
	(25,'113','VE POR MAS'),
	(26,'116','ING'),
	(27,'124','DEUTSCHE'),
	(28,'126','CREDIT SUISSE'),
	(29,'127','AZTECA'),
	(30,'128','AUTOFIN'),
	(31,'129','BARCLAYS'),
	(32,'130','COMPARTAMOS'),
	(33,'131','BANCO FAMSA'),
	(34,'132','BMULTIVA'),
	(35,'133','ACTINVER'),
	(36,'134','WAL-MART'),
	(37,'135','NAFIN'),
	(38,'136','INTERBANCO'),
	(39,'137','BANCOPPEL'),
	(40,'138','ABC CAPITAL'),
	(41,'139','UBS BANK'),
	(42,'140','CONSUBANCO'),
	(43,'141','VOLKSWAGEN'),
	(44,'143','CIBANCO'),
	(45,'145','BBASE'),
	(46,'166','BANSEFI'),
	(47,'168','HIPOTECARIA FEDERAL'),
	(48,'600','MONEXCB'),
	(49,'601','GBM'),
	(50,'602','MASARI'),
	(51,'605','VALUE'),
	(52,'606','ESTRUCTURADORES'),
	(53,'607','TIBER'),
	(54,'608','VECTOR'),
	(55,'610','B&B'),
	(56,'614','ACCIVAL'),
	(57,'615','MERRILL LYNCH'),
	(58,'616','FINAMEX'),
	(59,'617','VALMEX'),
	(60,'618','UNICA'),
	(61,'619','MAPFRE'),
	(62,'620','PROFUTURO'),
	(63,'621','CB ACTINVER'),
	(64,'622','OACTIN'),
	(65,'623','SKANDIA'),
	(66,'626','CBDEUTSCHE'),
	(67,'627','ZURICH'),
	(68,'628','ZURICHVI'),
	(69,'629','SU CASITA'),
	(70,'630','CB INTERCAM'),
	(71,'631','CI BOLSA'),
	(72,'632','BULLTICK CB'),
	(73,'633','STERLING'),
	(74,'634','FINCOMUN'),
	(75,'636','HDI SEGUROS'),
	(76,'637','ORDER'),
	(77,'638','AKALA'),
	(78,'640','CB JPMORGAN'),
	(79,'642','REFORMA'),
	(80,'646','STP'),
	(81,'647','TELECOMM'),
	(82,'648','EVERCORE'),
	(83,'649','SKANDIA'),
	(84,'651','SEGMTY'),
	(85,'652','ASEA'),
	(86,'653','KUSPIT'),
	(87,'655','SOFIEXPRESS'),
	(88,'656','UNAGRA'),
	(89,'659','OPCIONES EMPRESARIALES DEL NOROESTE'),
	(90,'901','CLS'),
	(91,'902','INDEVAL'),
	(92,'670','LIBERTAD'),
	(93,'999','NA');

/*!40000 ALTER TABLE `cont_bancos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_bancosPrv
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_bancosPrv`;

CREATE TABLE `cont_bancosPrv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idbanco` int(11) NOT NULL,
  `idPrv` int(11) NOT NULL,
  `numCT` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ImOtSis` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla cont_clasificacion_nif
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_clasificacion_nif`;

CREATE TABLE `cont_clasificacion_nif` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clasificacion` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `nivel` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_clasificacion_nif` WRITE;
/*!40000 ALTER TABLE `cont_clasificacion_nif` DISABLE KEYS */;

INSERT INTO `cont_clasificacion_nif` (`id`, `clasificacion`, `nivel`)
VALUES
	(1,'Efectivo','Activo a corto plazo'),
	(2,'Bancos e inversiones','Activo a corto plazo'),
	(3,'Cuentas y doctos por cobrar a clientes','Activo a corto plazo'),
	(4,'Préstamos a empleados','Activo a corto plazo'),
	(5,'Préstamos personales','Activo a corto plazo'),
	(6,'Almacén','Activo a corto plazo'),
	(7,'Anticipos a proveedores','Activo a corto plazo'),
	(8,'Activos por instrumentos derivados y de ','Activo a corto plazo'),
	(9,'Obra ejecutada por aprobar','Activo a corto plazo'),
	(10,'IVA acreditable pendiente','Activo a corto plazo'),
	(11,'IVA acreditable efectivo','Activo a corto plazo'),
	(12,'IVA a favor','Activo a corto plazo'),
	(13,'Otros impuestos anticipados o a favor por recuperar','Activo a corto plazo'),
	(14,'Pago anticipado de impuestos o impuestos a favor','Activo a corto plazo'),
	(15,'Partes relacionadas','Activo a corto plazo'),
	(16,'Inversión neta para el arrendador en arrendamientos capitalizables','Activo a corto plazo'),
	(17,'Otros activos a corto plazo','Activo a corto plazo'),
	(18,'Inventarios a largo plazo, neto','Activo a largo plazo'),
	(19,'Inversiones a largo plazo','Activo a largo plazo'),
	(20,'Inversiones reconocidas bajo el método de participación','Activo a largo plazo'),
	(21,'Propiedades, planta y equipo','Activo a largo plazo'),
	(22,'Propiedades de inversión','Activo a largo plazo'),
	(23,'Crédito mercantil','Activo a largo plazo'),
	(24,'Activos intangibles, excluyendo el crédito mercantil','Activo a largo plazo'),
	(25,'Activo neto proyectado de planes de beneficios a empleados','Activo a largo plazo'),
	(26,'Activos por instrumentos derivados y de cobertura no circulantes. Activos de larga duracion mantenid','Activo a largo plazo'),
	(27,'Efectivo restringido a largo plazo','Activo a largo plazo'),
	(28,'Pagos anticipados','Activo a largo plazo'),
	(29,'Grupos de activos a ser dispuestos, incluyendo operaciones descontinua','Activo a largo plazo'),
	(30,'Partes relacionadas','Activo a largo plazo'),
	(31,'Activo por impuesto a la utilidad diferido','Activo a largo plazo'),
	(32,'Activo por participación de los trabajadores en la utilidad diferida','Activo a largo plazo'),
	(33,'Otros activos a largo plazo','Activo a largo plazo'),
	(34,'Proveedores','Pasivo a corto plazo'),
	(35,'Otras cuentas por pagar a proveedores','Pasivo a corto plazo'),
	(36,'Préstamos con el sistema financiero','Pasivo a corto plazo'),
	(37,'Préstamos fuera del sistema financiero','Pasivo a corto plazo'),
	(38,'Pasivo por emisión de obligaciones y de otros instrumentos de deuda y porcion circulante a largo pl ','Pasivo a corto plazo'),
	(39,'Impuestos retenidos','Pasivo a corto plazo'),
	(40,'Anticipos de clientes','Pasivo a corto plazo'),
	(41,'Provisiones','Pasivo a corto plazo'),
	(42,'IVA trasladado pendiente','Pasivo a corto plazo'),
	(43,'IVA trasladado efectivo','Pasivo a corto plazo'),
	(44,'IVA trasladado a pagar','Pasivo a corto plazo'),
	(45,'Pasivo por impuesto a la utilidad causado','Pasivo a corto plazo'),
	(46,'Pasivo por otros impuestos causados','Pasivo a corto plazo'),
	(47,'Pasivo por participación de los trabajadores en la utilidad causada','Pasivo a corto plazo'),
	(48,'Obra cobrada por ejecutar','Pasivo a corto plazo'),
	(49,'Pasivos por instrumentos derivados y de cobertura a corto plazo','Pasivo a corto plazo'),
	(50,'Provisión de pérdidas sobre contratos de construcción','Pasivo a corto plazo'),
	(51,'Partes relacionadas','Pasivo a corto plazo'),
	(52,'Pasivos incluidos en grupo','Pasivo a corto plazo'),
	(53,'Otros pasivos a corto plazo','Pasivo a corto plazo'),
	(54,'Deuda a largo plazo','Pasivo a largo plazo'),
	(55,'Provisión de beneficios posteriores al empleo','Pasivo a largo plazo'),
	(56,'Obligaciones asociadas con el retiro de componentes de propiedades, planta y equipo','Pasivo a largo plazo'),
	(57,'Provisión por impuesto a la utilidad diferido','Pasivo a largo plazo'),
	(58,'Provisión por participación de los trabajadores en la utilidad diferida','Pasivo a largo plazo'),
	(59,'Pasivos incluidos en grupos de activos a ser dispuestos, incluyendo operaciones descontinuadas a lar','Pasivo a largo plazo'),
	(60,'Pasivos por instrumentos derivados y de cobertura a largo plazo','Pasivo a largo plazo'),
	(61,'Partes relacionadas','Pasivo a largo plazo'),
	(62,'Otros pasivos a largo plazo','Pasivo a largo plazo'),
	(63,'Capital social común','Capital participación controladora'),
	(64,'Capital social preferente','Capital participación controladora'),
	(65,'Prima en emisión o venta de acciones o capital adicional pagado','Capital participación controladora'),
	(66,'Acciones en tesorería','Capital participación controladora'),
	(67,'Capital aportado por planes de participación a empleados','Capital participación controladora'),
	(68,'Otros resultados integrales, netos de impuestos','Capital participación controladora'),
	(69,'Reserva para recompra de acciones','Capital participación controladora'),
	(70,'Otras reservas atribuibles a los propietarios de la controladora','Capital participación controladora'),
	(71,'Participación controladora en el capital','Capital participación controladora'),
	(72,'Utilidad o perdida del ejercicio','Capital participación controladora'),
	(73,'Utilidades o perdidas acumuladas','Utilidades acumuladas'),
	(74,'Participación no controladora en el capital','Capital participación no controladora'),
	(75,'Ventas o ingresos netos','Ventas o ingresos netos'),
	(76,'Costo de ventas o de servicios','Costos y gastos'),
	(77,'Gastos de venta y distribución','Gastos generales'),
	(78,'Gastos de administración','Gastos generales'),
	(79,'Sueldos y salarios de administración','Gastos generales'),
	(80,'Gastos no deducibles','Gastos generales'),
	(81,'Materia prima','Gastos generales'),
	(82,'Mano de obra','Gastos generales'),
	(83,'Gastos indirectos de fabricación','Gastos generales'),
	(84,'Gastos de investigación','Gastos generales'),
	(85,'Gastos por depreciación contable','Gastos generales'),
	(86,'Otros gastos de operación','Gastos generales'),
	(87,'Resultado integral de financiamiento','Resultado integral de financiamiento (RIF)'),
	(88,'Participación en asociadas','Participación en el RIF de otras entidades'),
	(89,'Impuestos a la utilidad','Impuestos a la utilidad'),
	(90,'Operaciones descontinuadas','Operaciones discontinuadas'),
	(91,'Resultado por conversión de operaciones extranjeras','Otros resultados integrales (ORI)'),
	(92,'Valuación de cobertura de flujo de efectivo','Otros resultados integrales (ORI)'),
	(93,'Participación en las ORI de asociadas','Otros resultados integrales (ORI)'),
	(94,'Impuestos a la utilidad de los ORI','Otros resultados integrales (ORI)'),
	(95,'Utilidad atribuible a la participación controladora','Utilidad neta atribuible a'),
	(96,'Utilidad atribuible a la participación no controladora','Utilidad neta atribuible a'),
	(97,'Participación controladora en el resultado integral','Resultados integral atribuible a'),
	(98,'Participación no controladora en el resultado integral','Resultados integral atribuible a');

/*!40000 ALTER TABLE `cont_clasificacion_nif` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_classification
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_classification`;

CREATE TABLE `cont_classification` (
  `classification_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`classification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_classification` WRITE;
/*!40000 ALTER TABLE `cont_classification` DISABLE KEYS */;

INSERT INTO `cont_classification` (`classification_id`, `description`)
VALUES
	(1,'ACTIVO'),
	(2,'PASIVO'),
	(3,'CAPITAL'),
	(4,'RESULTADOS'),
	(5,'DE ORDEN');

/*!40000 ALTER TABLE `cont_classification` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_coin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_coin`;

CREATE TABLE `cont_coin` (
  `coin_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `codigo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`coin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_coin` WRITE;
/*!40000 ALTER TABLE `cont_coin` DISABLE KEYS */;

INSERT INTO `cont_coin` (`coin_id`, `description`, `codigo`)
VALUES
	(1,'Peso Mexicano','MXN'),
	(2,'Dólar estadounidense','USD'),
	(3,'Euro','EUR'),
	(4,'Dirham de los Emiratos Árabes Unidos','AED'),
	(5,'Afgani afgano','AFN'),
	(6,'Lek albanés','ALL'),
	(7,'Dram armenio','AMD'),
	(8,'Florín antillano neerlandés','ANG'),
	(9,'Kwanza angoleño','AOA'),
	(10,'Peso argentino','ARS'),
	(11,'Dólar australiano','AUD'),
	(12,'Florín arubeño','AWG'),
	(13,'Manat azerbaiyano','AZN'),
	(14,'Marco convertible de Bosnia-Herzegovina','BAM'),
	(15,'Dólar de Barbados','BBD'),
	(16,'Taka de Bangladés','BDT'),
	(17,'Lev búlgaro','BGN'),
	(18,'Dinar bahreiní','BHD'),
	(19,'Franco burundés','BIF'),
	(20,'Dólar de Bermuda','BMD'),
	(21,'Dólar de Brunéi','BND'),
	(22,'Boliviano','BOB'),
	(23,'Mvdol boliviano (código de fondos)','BOV'),
	(24,'Real brasileño','BRL'),
	(25,'Dólar bahameño','BSD'),
	(26,'Ngultrum de Bután','BTN'),
	(27,'Pula de Botsuana','BWP'),
	(28,'Rublo bielorruso','BYR'),
	(29,'Dólar de Belice','BZD'),
	(30,'Dólar canadiense','CAD'),
	(31,'Franco congoleño, o congolés','CDF'),
	(32,'Franco suizo','CHF'),
	(33,'Unidades de fomento chilenas (código de fondos)','CLF'),
	(34,'Peso chileno','CLP'),
	(35,'Yuan chino','CNY'),
	(36,'Peso colombiano','COP'),
	(37,'Unidad de valor real colombiana (añadida al COP)','COU'),
	(38,'Colón costarricense','CRC'),
	(39,'Dinar serbio (Reemplazado por RSD el 25 de octubre de 2006)','CSD'),
	(40,'Peso cubano','CUP'),
	(41,'Escudo caboverdiano','CVE'),
	(42,'Koruna checa','CZK'),
	(43,'Franco yibutiano','DJF'),
	(44,'Corona danesa','DKK'),
	(45,'Peso dominicano','DOP'),
	(46,'Dinar argelino','DZD'),
	(47,'Libra egipcia','EGP'),
	(48,'Nakfa eritreo','ERN'),
	(49,'Birr etíope','ETB'),
	(51,'Dólar fiyiano','FJD'),
	(52,'Libra malvinense','FKP'),
	(53,'Libra esterlina (libra de Gran Bretaña)','GBP'),
	(54,'Lari georgiano','GEL'),
	(55,'Cedi ghanés','GHS'),
	(56,'Libra de Gibraltar','GIP'),
	(57,'Dalasi gambiano','GMD'),
	(58,'Franco guineano','GNF'),
	(59,'Quetzal guatemalteco','GTQ'),
	(60,'Dólar guyanés','GYD'),
	(61,'Dólar de Hong Kong','HKD'),
	(62,'Lempira hondureño','HNL'),
	(63,'Kuna croata','HRK'),
	(64,'Gourde haitiano','HTG'),
	(65,'Forint húngaro','HUF'),
	(66,'Rupiah indonesia','IDR'),
	(67,'Nuevo shéquel israelí','ILS'),
	(68,'Rupia india','INR'),
	(69,'Dinar iraquí','IQD'),
	(70,'Rial iraní','IRR'),
	(71,'Króna islandesa','ISK'),
	(72,'Dólar jamaicano','JMD'),
	(73,'Dinar jordano','JOD'),
	(74,'Yen japonés','JPY'),
	(75,'Chelín keniata','KES'),
	(76,'Som kirguís (de Kirguistán)','KGS'),
	(77,'Riel camboyano','KHR'),
	(78,'Franco comoriano (de Comoras)','KMF'),
	(79,'Won norcoreano','KPW'),
	(80,'Won surcoreano','KRW'),
	(81,'Dinar kuwaití','KWD'),
	(82,'Dólar caimano (de Islas Caimán)','KYD'),
	(83,'Tenge kazajo','KZT'),
	(84,'Kip lao','LAK'),
	(85,'Libra libanesa','LBP'),
	(86,'Rupia de Sri Lanka','LKR'),
	(88,'Peso cubano convertible','CUC'),
	(89,'Loti lesotense','LSL'),
	(90,'Litas lituano','LTL'),
	(91,'Lat letón','LVL'),
	(92,'Dinar libio','LYD'),
	(93,'Dirham marroquí','MAD'),
	(94,'Leu moldavo','MDL'),
	(95,'Ariary malgache','MGA'),
	(96,'Denar macedonio','MKD'),
	(97,'Kyat birmano','MMK'),
	(98,'Tughrik mongol','MNT'),
	(99,'Pataca de Macao','MOP'),
	(100,'Ouguiya mauritana','MRO'),
	(101,'Rupia mauricia','MUR'),
	(102,'Rufiyaa maldiva','MVR'),
	(103,'Kwacha malauí','MWK'),
	(105,'Unidad de Inversión (UDI) mexicana (código de fondos)','MXV'),
	(106,'Ringgit malayo','MYR'),
	(107,'Metical mozambiqueño','MZN'),
	(108,'Dólar namibio','NAD'),
	(109,'Naira nigeriana','NGN'),
	(110,'Corona noruega','NOK'),
	(111,'Rupia nepalesa','NPR'),
	(112,'Dólar neozelandés','NZD'),
	(113,'Rial omaní','OMR'),
	(115,'Nuevo sol peruano','PEN'),
	(116,'Kina de Papúa Nueva Guinea','PGK'),
	(117,'Peso filipino','PHP'),
	(118,'Rupia pakistaní','PKR'),
	(119,'zloty polaco','PLN'),
	(120,'Guaraní paraguayo','PYG'),
	(121,'Rial qatarí','QAR'),
	(122,'Leu rumano','RON'),
	(123,'Rublo ruso','RUB'),
	(124,'Franco ruandés','RWF'),
	(125,'Riyal saudí','SAR'),
	(126,'Dólar de las Islas Salomón','SBD'),
	(127,'Rupia de Seychelles','SCR'),
	(128,'Dinar sudanés','SDG'),
	(129,'Corona sueca','SEK'),
	(130,'Dólar de Singapur','SGD'),
	(131,'Libra de Santa Helena','SHP'),
	(132,'Leone de Sierra Leona','SLL'),
	(133,'Chelín somalí','SOS'),
	(134,'Dólar surinamés','SRD'),
	(135,'Dobra de Santo Tomé y Príncipe','STD'),
	(136,'Libra siria','SYP'),
	(137,'Lilangeni suazi','SZL'),
	(138,'Baht tailandés','THB'),
	(139,'Somoni tayik (de Tayikistán)','TJS'),
	(140,'Manat turcomano','TMT'),
	(142,'Pa anga tongano','TOP'),
	(143,'Lira turca','TRY'),
	(144,'Dólar de Trinidad y Tobago','TTD'),
	(145,'Dólar taiwanés','TWD'),
	(146,'Chelín tanzano','TZS'),
	(147,'Grivna ucraniana','UAH'),
	(148,'Chelín ugandés','UGX'),
	(149,'Dinar tunecino','TND'),
	(150,'Dólar estadounidense (Siguiente día) (código de fondos)','USN'),
	(151,'Dólar estadounidense (Mismo día) (código de fondos)','USS'),
	(153,'Peso uruguayo','UYU'),
	(154,'Som uzbeko','UZS'),
	(155,'Bolívar fuerte venezolano','VEF'),
	(156,'Dong vietnamita','VND'),
	(157,'Vatu vanuatense','VUV'),
	(158,'Tala samoana','WST'),
	(159,'Franco CFA de África Central','XAF'),
	(160,'Onza de plata','XAG'),
	(161,'Onza de oro','XAU'),
	(162,'European Composite Unit (EURCO) (unidad del mercado de bonos)','XBA'),
	(163,'European Monetary Unit (E.M.U.-6) (unidad del mercado de bonos)','XBB'),
	(164,'European Unit of Account 9 (E.U.A.-9) (unidad del mercado de bonos)','XBC'),
	(165,'European Unit of Account 17 (E.U.A.-17) (unidad del mercado de bonos)','XBD'),
	(166,'Dólar del Caribe Oriental','XCD'),
	(167,'Derechos Especiales de Giro (FMI)','XDR'),
	(168,'Franco de oro (Special settlement currency)','XFO'),
	(169,'Franco UIC (Special settlement currency)','XFU'),
	(170,'Franco CFA de África Occidental','XOF'),
	(171,'Onza de paladio','XPD'),
	(172,'Franco CFP','XPF'),
	(175,'Sin divisa','XXX'),
	(180,'Onza de platino','XPT'),
	(181,'Reservado para pruebas','XTS'),
	(183,'Rial yemení (de Yemen)','YER'),
	(184,'Rand sudafricano','ZAR'),
	(185,'Kwacha zambiano','ZMW'),
	(186,'Dólar zimbabuense','ZWL');

/*!40000 ALTER TABLE `cont_coin` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_config`;

CREATE TABLE `cont_config` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `IdOrganizacion` int(3) NOT NULL,
  `TipoCatalogo` int(3) NOT NULL,
  `Estructura` varchar(100) NOT NULL,
  `TipoValores` varchar(1) NOT NULL,
  `TipoNiveles` varchar(1) NOT NULL,
  `NumPol` int(1) DEFAULT NULL,
  `ClaveOrg` varchar(25) DEFAULT NULL,
  `RFC` varchar(30) NOT NULL,
  `InicioEjercicio` date NOT NULL,
  `FinEjercicio` date NOT NULL,
  `TipoPeriodo` varchar(1) NOT NULL,
  `NumPeriodos` int(2) NOT NULL,
  `PeriodoActual` int(2) NOT NULL,
  `PeriodosAbiertos` int(1) NOT NULL,
  `EjercicioActual` int(4) NOT NULL,
  `PrimeraVez` int(1) NOT NULL,
  `CuentaCompras` int(4) NOT NULL DEFAULT '-1',
  `CuentaVentas` int(4) NOT NULL DEFAULT '-1',
  `CuentaDev` int(4) NOT NULL DEFAULT '-1',
  `CuentaClientes` int(4) NOT NULL DEFAULT '-1',
  `CuentaIVA` int(4) NOT NULL DEFAULT '-1',
  `CuentaCaja` int(4) NOT NULL DEFAULT '-1',
  `CuentaTR` int(4) NOT NULL DEFAULT '-1',
  `CuentaBancos` int(4) NOT NULL DEFAULT '-1',
  `CuentaSaldos` int(4) NOT NULL DEFAULT '-1',
  `CuentaFlujoEfectivo` int(4) NOT NULL DEFAULT '-1',
  `CuentaProveedores` int(4) NOT NULL DEFAULT '-1',
  `CuentaUtilidad` int(4) NOT NULL DEFAULT '-1',
  `CuentaPerdida` int(4) NOT NULL DEFAULT '-1',
  `CuentaIVAPendientePago` int(4) NOT NULL DEFAULT '-1',
  `CuentaIVApagado` int(4) NOT NULL DEFAULT '-1',
  `CuentaIVAPendienteCobro` int(4) NOT NULL DEFAULT '-1',
  `CuentaIVAcobrado` int(4) NOT NULL DEFAULT '-1',
  `CuentaIEPSPendientePago` int(4) NOT NULL DEFAULT '-1',
  `CuentaIEPSpagado` int(4) NOT NULL DEFAULT '-1',
  `CuentaIEPSPendienteCobro` int(4) NOT NULL DEFAULT '-1',
  `CuentaIEPScobrado` int(4) NOT NULL DEFAULT '-1',
  `ISH` int(4) NOT NULL DEFAULT '-1',
  `ISRretenido` int(4) NOT NULL DEFAULT '-1',
  `IVAretenido` int(4) NOT NULL DEFAULT '-1',
  `CuentaDeudores` int(4) NOT NULL DEFAULT '-1',
  `CuentaIEPSgasto` int(4) NOT NULL DEFAULT '-1',
  `CuentaIVAgasto` int(4) NOT NULL DEFAULT '-1',
  `CuentaSueldoxPagar` int(4) NOT NULL DEFAULT '-1',
  `statusIVAIEPS` int(4) NOT NULL DEFAULT '0',
  `statusRetencionISH` int(4) NOT NULL DEFAULT '0',
  `statusIEPS` int(4) NOT NULL DEFAULT '0',
  `statusIVA` int(4) NOT NULL DEFAULT '0',
  `statuSueldoxPagar` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_config_pdv
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_config_pdv`;

CREATE TABLE `cont_config_pdv` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `historial` int(1) NOT NULL DEFAULT '0',
  `conectar` int(1) NOT NULL DEFAULT '0',
  `polizas_por` int(1) NOT NULL DEFAULT '0',
  `bancos` int(6) NOT NULL DEFAULT '-1',
  `clientes` int(6) NOT NULL DEFAULT '-1',
  `iva` int(6) NOT NULL DEFAULT '-1',
  `ventas` int(6) NOT NULL DEFAULT '-1',
  `caja` int(6) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_diarioficial
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_diarioficial`;

CREATE TABLE `cont_diarioficial` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo_agrupador` varchar(10) NOT NULL DEFAULT '',
  `descripcion` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1079 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_diarioficial` WRITE;
/*!40000 ALTER TABLE `cont_diarioficial` DISABLE KEYS */;

INSERT INTO `cont_diarioficial` (`id`, `codigo_agrupador`, `descripcion`)
VALUES
	(1,'100','Activo'),
	(2,'100.01','Activo a corto plazo'),
	(3,'101',' Caja'),
	(4,'101.01','Caja y efectivo'),
	(5,'102','Bancos'),
	(6,'102.01','Bancos nacionales'),
	(7,'102.02','Bancos extranjeros'),
	(8,'103',' Inversiones'),
	(9,'103.01','Inversiones temporales'),
	(10,'103.02','Inversiones en fideicomisos'),
	(11,'103.03','Otras inversiones'),
	(12,'104','Otros instrumentos financieros'),
	(13,'104.01','Otros instrumentos financieros'),
	(14,'105','Clientes'),
	(15,'105.01','Clientes nacionales'),
	(16,'105.02','Clientes extranjeros'),
	(17,'105.03','Clientes nacionales parte relacionada'),
	(18,'105.04','Clientes extranjeros parte relacionada'),
	(19,'106','Cuentas y documentos por cobrar a corto plazo'),
	(20,'106.01','Cuentas y documentos por cobrar a corto plazo nacional'),
	(21,'106.02','Cuentas y documentos por cobrar a corto plazo extranjero'),
	(22,'106.03','Cuentas y documentos por cobrar a corto plazo nacional parte relacionada'),
	(23,'106.04','Cuentas y documentos por cobrar a corto plazo extranjero parte relacionada'),
	(24,'106.05','Intereses por cobrar a corto plazo nacional'),
	(25,'106.06','Intereses por cobrar a corto plazo extranjero'),
	(26,'106.07','Intereses por cobrar a corto plazo nacional parte relacionada'),
	(27,'106.08','Intereses por cobrar a corto plazo extranjero parte relacionada'),
	(28,'106.09','Otras cuentas y documentos por cobrar a corto plazo'),
	(29,'106.10','Otras cuentas y documentos por cobrar a corto plazo parte relacionada'),
	(30,'107','Deudores diversos'),
	(31,'107.01','Funcionarios y empleados'),
	(32,'107.02','Socios y accionistas'),
	(33,'107.03','Partes relacionadas nacionales'),
	(34,'107.04','Partes relacionadas extranjeros'),
	(35,'107.05','Otros deudores diversos'),
	(36,'108',' Estimación de cuentas incobrables'),
	(37,'108.01','Estimación de cuentas incobrables nacional'),
	(38,'108.02','Estimación de cuentas incobrables extranjero'),
	(39,'108.03','Estimación de cuentas incobrables nacional parte relacionada'),
	(40,'108.04','Estimación de cuentas incobrables extranjero parte relacionada'),
	(41,'109','Pagos anticipados'),
	(42,'109.01','Seguros y fianzas pagados por anticipado nacional'),
	(43,'109.02','Seguros y fianzas pagados por anticipado extranjero'),
	(44,'109.03','Seguros y fianzas pagados por anticipado nacional parte relacionada'),
	(45,'109.04','Seguros y fianzas pagados por anticipado extranjero parte relacionada'),
	(46,'109.05','Rentas pagados por anticipado nacional'),
	(47,'109.06','Rentas pagados por anticipado extranjero'),
	(48,'109.07','Rentas pagados por anticipado nacional parte relacionada'),
	(49,'109.08','Rentas pagados por anticipado extranjero parte relacionada'),
	(50,'109.09','Intereses pagados por anticipado nacional'),
	(51,'109.10','Intereses pagados por anticipado extranjero'),
	(52,'109.11','Intereses pagados por anticipado nacional parte relacionada'),
	(53,'109.12','Intereses pagados por anticipado extranjero parte relacionada'),
	(54,'109.13','Factoraje financiero pagados por anticipado nacional'),
	(55,'109.14','Factoraje financiero pagados por anticipado extranjero'),
	(56,'109.15','Factoraje financiero pagados por anticipado nacional parte relacionada'),
	(57,'109.16','Factoraje financiero pagados por anticipado extranjero parte relacionada'),
	(58,'109.17','Arrendamiento financiero pagados por anticipado nacional'),
	(59,'109.18','Arrendamiento financiero pagados por anticipado extranjero'),
	(60,'109.19','Arrendamiento financiero pagados por anticipado nacional parte relacionada'),
	(61,'109.20','Arrendamiento financiero pagados por anticipado extranjero parte relacionada'),
	(62,'109.21','Pérdida por deterioro de pagos anticipados'),
	(63,'109.22','Derechos fiduciarios'),
	(64,'109.23','Otros pagos anticipados'),
	(65,'110','Subsidio al empleo por aplicar'),
	(66,'110.01','Subsidio al empleo por aplicar'),
	(67,'111','Crédito al diesel por acreditar'),
	(68,'111.01','Crédito al diesel por acreditar'),
	(69,'112','Otros estímulos'),
	(70,'112.01','Otros estímulos'),
	(71,'113','Impuestos a favor'),
	(72,'113.01','IVA a favor'),
	(73,'113.02','ISR a favor'),
	(74,'113.03','IETU a favor'),
	(75,'113.04','IDE a favor'),
	(76,'113.05','IA a favor'),
	(77,'113.06','Subsidio al empleo'),
	(78,'113.07','Pago de lo indebido'),
	(79,'113.08','Otros impuestos a favor'),
	(80,'114','Pagos provisionales'),
	(81,'114.01','Pagos provisionales de ISR'),
	(82,'115','Inventario'),
	(83,'115.01','Inventario'),
	(84,'115.02','Materia prima y materiales'),
	(85,'115.03','Producción en proceso'),
	(86,'115.04','Productos terminados'),
	(87,'115.05','Mercancías en tránsito'),
	(88,'115.06','Mercancías en poder de terceros'),
	(89,'115.07','Otros'),
	(90,'116','Estimación de inventarios obsoletos y de lento movimiento'),
	(91,'116.01','Estimación de inventarios obsoletos y de lento movimiento'),
	(92,'117','Obras en proceso de inmuebles'),
	(93,'117.01','Obras en proceso de inmuebles'),
	(94,'118','Impuestos acreditables pagados'),
	(95,'118.01','IVA acreditable pagado'),
	(96,'118.02','IVA acreditable de importación pagado'),
	(97,'118.03','IEPS acreditable pagado'),
	(98,'118.04','IEPS pagado en importación'),
	(99,'119','Impuestos acreditables por pagar'),
	(100,'119.01','IVA pendiente de pago'),
	(101,'119.02','IVA de importación pendiente de pago'),
	(102,'119.03','IEPS pendiente de pago'),
	(103,'119.04','IEPS pendiente de pago en importación'),
	(104,'120','Anticipo a proveedores'),
	(105,'120.01','Anticipo a proveedores nacional'),
	(106,'120.02','Anticipo a proveedores extranjero'),
	(107,'120.03','Anticipo a proveedores nacional parte relacionada'),
	(108,'120.04','Anticipo a proveedores extranjero parte relacionada'),
	(109,'121','Otros activos a corto plazo'),
	(110,'121.01','Otros activos a corto plazo'),
	(111,'151','Terrenos'),
	(112,'151.01','Terrenos'),
	(113,'152','Edificios'),
	(114,'152.01','Edificios'),
	(115,'153','Maquinaria y equipo'),
	(116,'153.01','Maquinaria y equipo'),
	(117,'154','Automóviles, autobuses, camiones de carga, tractocamiones, montacargas y remolques'),
	(118,'154.01','Automóviles, autobuses, camiones de carga, tractocamiones, montacargas y remolques'),
	(119,'155','Mobiliario y equipo de oficina'),
	(120,'155.01','Mobiliario y equipo de oficina'),
	(121,'156','Equipo de cómputo'),
	(122,'156.01','Equipo de cómputo'),
	(123,'157','Equipo de comunicación'),
	(124,'157.01','Equipo de comunicación'),
	(125,'158','Activos biológicos, vegetales y semovientes'),
	(126,'158.01','Activos biológicos, vegetales y semovientes'),
	(127,'159','Obras en proceso de activos fijos'),
	(128,'159.01','Obras en proceso de activos fijos'),
	(129,'160','Otros activos fijos'),
	(130,'160.01','Otros activos fijos'),
	(131,'161','Ferrocarriles'),
	(132,'161.01','Ferrocarriles'),
	(133,'162','Embarcaciones'),
	(134,'162.01','Embarcaciones'),
	(135,'163','Aviones'),
	(136,'163.01','Aviones'),
	(137,'164','Troqueles, moldes, matrices y herramental'),
	(138,'164.01','Troqueles, moldes, matrices y herramental'),
	(139,'165','Equipo de comunicaciones telefónicas'),
	(140,'165.01','Equipo de comunicaciones telefónicas'),
	(141,'166','Equipo de comunicación satelital'),
	(142,'166.01','Equipo de comunicación satelital'),
	(143,'167','Equipo de adaptaciones para personas con capacidades diferentes'),
	(144,'167.01','Equipo de adaptaciones para personas con capacidades diferentes'),
	(145,'168','Maquinaria y equipo de generación de energía de fuentes renovables o de sistemas de cogeneración de '),
	(146,'168.01','Maquinaria y equipo de generación de energía de fuentes renovables o de sistemas de cogeneración de '),
	(147,'169','Otra maquinaria y equipo'),
	(148,'169.01','Otra maquinaria y equipo'),
	(149,'170','Adaptaciones y mejoras'),
	(150,'170.01','Adaptaciones y mejoras'),
	(151,'171','Depreciación acumulada de activos fijos'),
	(152,'171.01','Depreciación acumulada de edificios'),
	(153,'171.02','Depreciación acumulada de maquinaria y equipo'),
	(154,'171.03','Depreciación acumulada de automóviles, autobuses, camiones de carga, tractocamiones, montacargas y r'),
	(155,'171.04','Depreciación acumulada de mobiliario y equipo de oficina'),
	(156,'171.05','Depreciación acumulada de equipo de cómputo'),
	(157,'171.06','Depreciación acumulada de equipo de comunicación'),
	(158,'171.07','Depreciación acumulada de activos biológicos, vegetales y semovientes'),
	(159,'171.08','Depreciación acumulada de otros activos fijos'),
	(160,'171.09','Depreciación acumulada de ferrocarriles'),
	(161,'171.1','Depreciación acumulada de embarcaciones'),
	(162,'171.11','Depreciación acumulada de aviones'),
	(163,'171.12','Depreciación acumulada de troqueles, moldes, matrices y herramental'),
	(164,'171.13','Depreciación acumulada de equipo de comunicaciones telefónicas'),
	(165,'171.14','Depreciación acumulada de equipo de comunicación satelital'),
	(166,'171.15','Depreciación acumulada de equipo de adaptaciones para personas con capacidades diferentes'),
	(167,'171.16','Depreciación acumulada de maquinaria y equipo de generación de energía de fuentes renovables o de si'),
	(168,'171.17','Depreciación acumulada de adaptaciones y mejoras'),
	(169,'171.18','Depreciación acumulada de otra maquinaria y equipo'),
	(170,'172','Pérdida por deterioro acumulado de activos fijos'),
	(171,'172.01','Pérdida por deterioro acumulado de edificios'),
	(172,'172.02','Pérdida por deterioro acumulado de maquinaria y equipo'),
	(173,'172.03','Pérdida por deterioro acumulado de automóviles, autobuses, camiones de carga, tractocamiones, montac'),
	(174,'172.04','Pérdida por deterioro acumulado de mobiliario y equipo de oficina'),
	(175,'172.05','Pérdida por deterioro acumulado de equipo de cómputo'),
	(176,'172.06','Pérdida por deterioro acumulado de equipo de comunicación'),
	(177,'172.07','Pérdida por deterioro acumulado de activos biológicos, vegetales y semovientes'),
	(178,'172.08','Pérdida por deterioro acumulado de otros activos fijos'),
	(179,'172.09','Pérdida por deterioro acumulado de ferrocarriles'),
	(180,'172.10','Pérdida por deterioro acumulado de embarcaciones'),
	(181,'172.11','Pérdida por deterioro acumulado de aviones'),
	(182,'172.12','Pérdida por deterioro acumulado de troqueles, moldes, matrices y herramental'),
	(183,'172.13','Pérdida por deterioro acumulado de equipo de comunicaciones telefónicas'),
	(184,'172.14','Pérdida por deterioro acumulado de equipo de comunicación satelital'),
	(185,'172.15','Pérdida por deterioro acumulado de equipo de adaptaciones para personas con capacidades diferentes'),
	(186,'172.16','Pérdida por deterioro acumulado de maquinaria y equipo de generación de energía de fuentes renovable'),
	(187,'172.17','Pérdida por deterioro acumulado de adaptaciones y mejoras'),
	(188,'172.18','Pérdida por deterioro acumulado de otra maquinaria y equipo'),
	(189,'173','Gastos diferidos'),
	(190,'173.01','Gastos diferidos'),
	(191,'174','Gastos pre operativos'),
	(192,'174.01','Gastos pre operativos'),
	(193,'175','Regalías, asistencia técnica y otros gastos diferidos'),
	(194,'175.01','Regalías, asistencia técnica y otros gastos diferidos'),
	(195,'176','Activos intangibles'),
	(196,'176.01','Activos intangibles'),
	(197,'177','Gastos de organización'),
	(198,'177.01','Gastos de organización'),
	(199,'178','Investigación y desarrollo de mercado'),
	(200,'178.01','Investigación y desarrollo de mercado'),
	(201,'179','Marcas y patentes'),
	(202,'179.01','Marcas y patentes'),
	(203,'180','Crédito mercantil'),
	(204,'180.01','Crédito mercantil'),
	(205,'181','Gastos de instalación'),
	(206,'181.01','Gastos de instalación'),
	(207,'182','Otros activos diferidos'),
	(208,'182.01','Otros activos diferidos'),
	(209,'183','Amortización acumulada de activos diferidos'),
	(210,'183.01','Amortización acumulada de gastos diferidos'),
	(211,'183.02','Amortización acumulada de gastos pre operativos'),
	(212,'183.03','Amortización acumulada de regalías, asistencia técnica y otros gastos diferidos'),
	(213,'183.04','Amortización acumulada de activos intangibles'),
	(214,'183.05','Amortización acumulada de gastos de organización'),
	(215,'183.06','Amortización acumulada de investigación y desarrollo de mercado'),
	(216,'183.07','Amortización acumulada de marcas y patentes'),
	(217,'183.08','Amortización acumulada de crédito mercantil'),
	(218,'183.09','Amortización acumulada de gastos de instalación'),
	(219,'183.10','Amortización acumulada de otros activos diferidos'),
	(220,'184','Depósitos en garantía'),
	(221,'184.01','Depósitos de fianzas'),
	(222,'184.02','Depósitos de arrendamiento de bienes inmuebles'),
	(223,'184.03','Otros depósitos en garantía'),
	(224,'185','Impuestos diferidos'),
	(225,'185.01','Impuestos diferidos ISR'),
	(226,'186','Cuentas y documentos por cobrar a largo plazo'),
	(227,'186.01','Cuentas y documentos por cobrar a largo plazo nacional'),
	(228,'186.02','Cuentas y documentos por cobrar a largo plazo extranjero'),
	(229,'186.03','Cuentas y documentos por cobrar a largo plazo nacional parte relacionada'),
	(230,'186.04','Cuentas y documentos por cobrar a largo plazo extranjero parte relacionada'),
	(231,'186.05','Intereses por cobrar a largo plazo nacional'),
	(232,'186.06','Intereses por cobrar a largo plazo extranjero'),
	(233,'186.07','Intereses por cobrar a largo plazo nacional parte relacionada'),
	(234,'186.08','Intereses por cobrar a largo plazo extranjero parte relacionada'),
	(235,'186.09','Otras cuentas y documentos por cobrar a largo plazo'),
	(236,'186.10','Otras cuentas y documentos por cobrar a largo plazo parte relacionada'),
	(237,'187','Participación de los trabajadores en las utilidades diferidas'),
	(238,'187.01','Participación de los trabajadores en las utilidades diferidas'),
	(239,'188','Inversiones permanentes en acciones'),
	(240,'188.01','Inversiones a largo plazo en subsidiarias'),
	(241,'188.02','Inversiones a largo plazo en asociadas'),
	(242,'188.03','Otras inversiones permanentes en acciones'),
	(243,'189','Estimación por deterioro de inversiones permanentes en acciones'),
	(244,'189.01','Estimación por deterioro de inversiones permanentes en acciones'),
	(245,'190','Otros instrumentos financieros'),
	(246,'190.01','Otros instrumentos financieros'),
	(247,'191','Otros activos a largo plazo'),
	(248,'191.01','Otros activos a largo plazo'),
	(249,'201','Proveedores'),
	(250,'201.01','Proveedores nacionales'),
	(251,'201.02','Proveedores extranjeros'),
	(252,'201.03','Proveedores nacionales parte relacionada'),
	(253,'201.04','Proveedores extranjeros parte relacionada'),
	(254,'202','Cuentas por pagar a corto plazo'),
	(255,'202.01','Documentos por pagar bancario y financiero nacional'),
	(256,'202.02','Documentos por pagar bancario y financiero extranjero'),
	(257,'202.03','Documentos y cuentas por pagar a corto plazo nacional'),
	(258,'202.04','Documentos y cuentas por pagar a corto plazo extranjero'),
	(259,'202.05','Documentos y cuentas por pagar a corto plazo nacional parte relacionada'),
	(260,'202.06','Documentos y cuentas por pagar a corto plazo extranjero parte relacionada'),
	(261,'202.07','Intereses por pagar a corto plazo nacional'),
	(262,'202.08','Intereses por pagar a corto plazo extranjero'),
	(263,'202.09','Intereses por pagar a corto plazo nacional parte relacionada'),
	(264,'202.10','Intereses por pagar a corto plazo extranjero parte relacionada'),
	(265,'202.11','Dividendo por pagar nacional'),
	(266,'202.12','Dividendo por pagar extranjero'),
	(267,'203','Cobros anticipados a corto plazo'),
	(268,'203.01','Rentas cobradas por anticipado a corto plazo nacional'),
	(269,'203.02','Rentas cobradas por anticipado a corto plazo extranjero'),
	(270,'203.03','Rentas cobradas por anticipado a corto plazo nacional parte relacionada'),
	(271,'203.04','Rentas cobradas por anticipado a corto plazo extranjero parte relacionada'),
	(272,'203.05','Intereses cobrados por anticipado a corto plazo nacional'),
	(273,'203.06','Intereses cobrados por anticipado a corto plazo extranjero'),
	(274,'203.07','Intereses cobrados por anticipado a corto plazo nacional parte relacionada'),
	(275,'203.08','Intereses cobrados por anticipado a corto plazo extranjero parte relacionada'),
	(276,'203.09','Factoraje financiero cobrados por anticipado a corto plazo nacional'),
	(277,'203.10','Factoraje financiero cobrados por anticipado a corto plazo extranjero'),
	(278,'203.11','Factoraje financiero cobrados por anticipado a corto plazo nacional parte relacionada'),
	(279,'203.12','Factoraje financiero cobrados por anticipado a corto plazo extranjero parte relacionada'),
	(280,'203.13','Arrendamiento financiero cobrados por anticipado a corto plazo nacional'),
	(281,'203.14','Arrendamiento financiero cobrados por anticipado a corto plazo extranjero'),
	(282,'203.15','Arrendamiento financiero cobrados por anticipado a corto plazo nacional parte relacionada'),
	(283,'203.16','Arrendamiento financiero cobrados por anticipado a corto plazo extranjero parte relacionada'),
	(284,'203.17','Derechos fiduciarios'),
	(285,'203.18','Otros cobros anticipados'),
	(286,'204','Instrumentos financieros a corto plazo'),
	(287,'204.01','Instrumentos financieros a corto plazo'),
	(288,'205','Acreedores diversos a corto plazo'),
	(289,'205.01','Socios, accionistas o representante legal'),
	(290,'205.02','Acreedores diversos a corto plazo nacional'),
	(291,'205.03','Acreedores diversos a corto plazo extranjero'),
	(292,'205.04','Acreedores diversos a corto plazo nacional parte relacionada'),
	(293,'205.05','Acreedores diversos a corto plazo extranjero parte relacionada'),
	(294,'205.06','Otros acreedores diversos a corto plazo'),
	(295,'206','Anticipo de cliente'),
	(296,'206.01','Anticipo de cliente nacional'),
	(297,'206.02','Anticipo de cliente extranjero'),
	(298,'206.03','Anticipo de cliente nacional parte relacionada'),
	(299,'206.04','Anticipo de cliente extranjero parte relacionada'),
	(300,'206.05','Otros anticipos de clientes'),
	(301,'207','Impuestos trasladados'),
	(302,'207.01','IVA trasladado'),
	(303,'207.02','IEPS trasladado'),
	(304,'208','Impuestos trasladados cobrados'),
	(305,'208.01','IVA trasladado cobrado'),
	(306,'208.02','IEPS trasladado cobrado'),
	(307,'209','Impuestos trasladados no cobrados'),
	(308,'209.01','IVA trasladado no cobrado'),
	(309,'209.02','IEPS trasladado no cobrado'),
	(310,'210','Provisión de sueldos y salarios por pagar'),
	(311,'210.01','Provisión de sueldos y salarios por pagar'),
	(312,'210.02','Provisión de vacaciones por pagar'),
	(313,'210.03','Provisión de aguinaldo por pagar'),
	(314,'210.04','Provisión de fondo de ahorro por pagar'),
	(315,'210.05','Provisión de asimilados a salarios por pagar'),
	(316,'210.06','Provisión de anticipos o remanentes por distribuir'),
	(317,'210.07','Provisión de otros sueldos y salarios por pagar'),
	(318,'211','Provisión de contribuciones de seguridad social por pagar'),
	(319,'211.01','Provisión de IMSS patronal por pagar'),
	(320,'211.02','Provisión de SAR por pagar'),
	(321,'211.03','Provisión de infonavit por pagar'),
	(322,'212','Provisión de impuesto estatal sobre nómina por pagar'),
	(323,'212.01','Provisión de impuesto estatal sobre nómina por pagar'),
	(324,'213','Impuestos y derechos por pagar'),
	(325,'213.01','IVA por pagar'),
	(326,'213.02','IEPS por pagar'),
	(327,'213.03','ISR por pagar'),
	(328,'213.04','Impuesto estatal sobre nómina por pagar'),
	(329,'213.05','Impuesto estatal y municipal por pagar'),
	(330,'213.06','Derechos por pagar'),
	(331,'213.07','Otros impuestos por pagar'),
	(332,'214','Dividendos por pagar'),
	(333,'214.01','Dividendos por pagar'),
	(334,'215','PTU por pagar'),
	(335,'215.01','PTU por pagar'),
	(336,'215.02','PTU por pagar de ejercicios anteriores'),
	(337,'215.03','Provisión de PTU por pagar'),
	(338,'216','Impuestos retenidos'),
	(339,'216.01','Impuestos retenidos de ISR por sueldos y salarios'),
	(340,'216.02','Impuestos retenidos de ISR por asimilados a salarios'),
	(341,'216.03','Impuestos retenidos de ISR por arrendamiento'),
	(342,'216.04','Impuestos retenidos de ISR por servicios profesionales'),
	(343,'216.05','Impuestos retenidos de ISR por dividendos'),
	(344,'216.06','Impuestos retenidos de ISR por intereses'),
	(345,'216.07','Impuestos retenidos de ISR por pagos al extranjero'),
	(346,'216.08','Impuestos retenidos de ISR por venta de acciones'),
	(347,'216.09','Impuestos retenidos de ISR por venta de partes sociales'),
	(348,'216.10','Impuestos retenidos de IVA'),
	(349,'216.11','Retenciones de IMSS a los trabajadores'),
	(350,'216.12','Otras impuestos retenidos'),
	(351,'217','Pagos realizados por cuenta de terceros'),
	(352,'217.01','Pagos realizados por cuenta de terceros'),
	(353,'218','Otros pasivos a corto plazo'),
	(354,'218.01','Otros pasivos a corto plazo'),
	(355,'251','Acreedores diversos a largo plazo'),
	(356,'251.01','Socios, accionistas o representante legal'),
	(357,'251.02','Acreedores diversos a largo plazo nacional'),
	(358,'251.03','Acreedores diversos a largo plazo extranjero'),
	(359,'251.04','Acreedores diversos a largo plazo nacional parte relacionada'),
	(360,'251.05','Acreedores diversos a largo plazo extranjero parte relacionada'),
	(361,'251.06','Otros acreedores diversos a largo plazo'),
	(362,'252','Cuentas por pagar a largo plazo'),
	(363,'252.01','Documentos bancarios y financieros por pagar a largo plazo nacional'),
	(364,'252.02','Documentos bancarios y financieros por pagar a largo plazo extranjero'),
	(365,'252.03','Documentos y cuentas por pagar a largo plazo nacional'),
	(366,'252.04','Documentos y cuentas por pagar a largo plazo extranjero'),
	(367,'252.05','Documentos y cuentas por pagar a largo plazo nacional parte relacionada'),
	(368,'252.06','Documentos y cuentas por pagar a largo plazo extranjero parte relacionada'),
	(369,'252.07','Hipotecas por pagar a largo plazo nacional'),
	(370,'252.08','Hipotecas por pagar a largo plazo extranjero'),
	(371,'252.09','Hipotecas por pagar a largo plazo nacional parte relacionada'),
	(372,'252.10','Hipotecas por pagar a largo plazo extranjero parte relacionada'),
	(373,'252.11','Intereses por pagar a largo plazo nacional'),
	(374,'252.12','Intereses por pagar a largo plazo extranjero'),
	(375,'252.13','Intereses por pagar a largo plazo nacional parte relacionada'),
	(376,'252.14','Intereses por pagar a largo plazo extranjero parte relacionada'),
	(377,'252.15','Dividendos por pagar nacionales'),
	(378,'252.16','Dividendos por pagar extranjeros'),
	(379,'252.17','Otras cuentas y documentos por pagar a largo plazo'),
	(380,'253','Cobros anticipados a largo plazo'),
	(381,'253.01','Rentas cobradas por anticipado a largo plazo nacional'),
	(382,'253.02','Rentas cobradas por anticipado a largo plazo extranjero'),
	(383,'253.03','Rentas cobradas por anticipado a largo plazo nacional parte relacionada'),
	(384,'253.04','Rentas cobradas por anticipado a largo plazo extranjero parte relacionada'),
	(385,'253.05','Intereses cobrados por anticipado a largo plazo nacional'),
	(386,'253.06','Intereses cobrados por anticipado a largo plazo extranjero'),
	(387,'253.07','Intereses cobrados por anticipado a largo plazo nacional parte relacionada'),
	(388,'253.08','Intereses cobrados por anticipado a largo plazo extranjero parte relacionada'),
	(389,'253.09','Factoraje financiero cobrados por anticipado a largo plazo nacional'),
	(390,'253.10','Factoraje financiero cobrados por anticipado a largo plazo extranjero'),
	(391,'253.11','Factoraje financiero cobrados por anticipado a largo plazo nacional parte relacionada'),
	(392,'253.12','Factoraje financiero cobrados por anticipado a largo plazo extranjero parte relacionada'),
	(393,'253.13','Arrendamiento financiero cobrados por anticipado a largo plazo nacional'),
	(394,'253.14','Arrendamiento financiero cobrados por anticipado a largo plazo extranjero'),
	(395,'253.15','Arrendamiento financiero cobrados por anticipado a largo plazo nacional parte relacionada'),
	(396,'253.16','Arrendamiento financiero cobrados por anticipado a largo plazo extranjero parte relacionada'),
	(397,'253.17','Derechos fiduciarios'),
	(398,'253.18','Otros cobros anticipados'),
	(399,'254','Instrumentos financieros a largo plazo'),
	(400,'254.01','Instrumentos financieros a largo plazo'),
	(401,'255','Pasivos por beneficios a los empleados a largo plazo'),
	(402,'255.01','Pasivos por beneficios a los empleados a largo plazo'),
	(403,'256','Otros pasivos a largo plazo'),
	(404,'256.01','Otros pasivos a largo plazo'),
	(405,'257','Participación de los trabajadores en las utilidades diferida'),
	(406,'257.01','Participación de los trabajadores en las utilidades diferida'),
	(407,'258','Obligaciones contraídas de fideicomisos'),
	(408,'258.01','Obligaciones contraídas de fideicomisos'),
	(409,'259','Impuestos diferidos'),
	(410,'259.01','ISR diferido'),
	(411,'259.02','ISR por dividendo diferido'),
	(412,'259.03','Otros impuestos diferidos'),
	(413,'260','Pasivos diferidos'),
	(414,'260.01','Pasivos diferidos'),
	(415,'301','Capital social'),
	(416,'301.01','Capital fijo'),
	(417,'301.02','Capital variable'),
	(418,'301.03','Aportaciones para futuros aumentos de capital'),
	(419,'301.04','Prima en suscripción de acciones'),
	(420,'301.05','Prima en suscripción de partes sociales'),
	(421,'302','Patrimonio'),
	(422,'302.01','Patrimonio'),
	(423,'302.02','Aportación patrimonial'),
	(424,'302.03','Déficit o remanente del ejercicio'),
	(425,'303','Reserva legal'),
	(426,'303.01','Reserva legal'),
	(427,'304','Resultado de ejercicios anteriores'),
	(428,'304.01','Utilidad de ejercicios anteriores'),
	(429,'304.02','Pérdida de ejercicios anteriores'),
	(430,'304.03','Resultado integral de ejercicios anteriores'),
	(431,'304.04','Déficit o remanente de ejercicio anteriores'),
	(432,'305','Resultado del ejercicio'),
	(433,'305.01','Utilidad del ejercicio'),
	(434,'305.02','Pérdida del ejercicio'),
	(435,'305.03','Resultado integral'),
	(436,'306','Otras cuentas de capital'),
	(437,'306.01','Otras cuentas de capital'),
	(438,'401','Ingresos'),
	(440,'401.01','Ventas yo servicios gravados a la tasa general'),
	(441,'401.02','Ventas yo servicios gravados a la tasa general de contado'),
	(442,'401.03','Ventas yo servicios gravados a la tasa general a crédito'),
	(443,'401.04','Ventas yo servicios gravados al 0%'),
	(444,'401.05','Ventas yo servicios gravados al 0% de contado'),
	(445,'401.06','Ventas yo servicios gravados al 0% a crédito'),
	(446,'401.07','Ventas yo servicios exentos'),
	(447,'401.08','Ventas yo servicios exentos de contado'),
	(448,'401.09','Ventas yo servicios exentos a crédito'),
	(449,'401.10','Ventas yo servicios gravados a la tasa general nacionales partes relacionadas'),
	(450,'401.11','Ventas yo servicios gravados a la tasa general extranjeros partes relacionadas'),
	(451,'401.12','Ventas yo servicios gravados al 0% nacionales partes relacionadas'),
	(452,'401.13','Ventas yo servicios gravados al 0% extranjeros partes relacionadas'),
	(453,'401.14','Ventas yo servicios exentos nacionales partes relacionadas'),
	(454,'401.15','Ventas yo servicios exentos extranjeros partes relacionadas'),
	(455,'401.16','Ingresos por servicios administrativos'),
	(456,'401.17','Ingresos por servicios administrativos nacionales partes relacionadas'),
	(457,'401.18','Ingresos por servicios administrativos extranjeros partes relacionadas'),
	(458,'401.19','Ingresos por servicios profesionales'),
	(459,'401.20','Ingresos por servicios profesionales nacionales partes relacionadas'),
	(460,'401.21','Ingresos por servicios profesionales extranjeros partes relacionadas'),
	(461,'401.22','Ingresos por arrendamiento'),
	(462,'401.23','Ingresos por arrendamiento nacionales partes relacionadas'),
	(463,'401.24','Ingresos por arrendamiento extranjeros partes relacionadas'),
	(464,'401.25','Ingresos por exportación'),
	(465,'401.26','Ingresos por comisiones'),
	(466,'401.27','Ingresos por maquila'),
	(467,'401.28','Ingresos por coordinados'),
	(468,'401.29','Ingresos por regalías'),
	(469,'401.30','Ingresos por asistencia técnica'),
	(470,'401.31','Ingresos por donativos'),
	(471,'401.32','Ingresos por intereses (actividad propia)'),
	(472,'401.33','Ingresos de copropiedad'),
	(473,'401.34','Ingresos por fideicomisos'),
	(474,'401.35','Ingresos por factoraje financiero'),
	(475,'401.36','Ingresos por arrendamiento financiero'),
	(476,'401.37','Ingresos de extranjeros con establecimiento en el país'),
	(477,'401.38','Otros ingresos propios'),
	(479,'402','Devoluciones, descuentos o bonificaciones sobre ingresos'),
	(480,'402.01','Devoluciones, descuentos o bonificaciones sobre ventas yo servicios a la tasa general'),
	(481,'402.02','Devoluciones, descuentos o bonificaciones sobre ventas yo servicios al 0%'),
	(482,'402.03','Devoluciones, descuentos o bonificaciones sobre ventas yo servicios exentos'),
	(483,'402.04','Devoluciones, descuentos o bonificaciones de otros ingresos'),
	(484,'403','Otros ingresos'),
	(485,'403.01','Otros Ingresos'),
	(486,'403.02','Otros ingresos nacionales parte relacionada'),
	(487,'403.03','Otros ingresos extranjeros parte relacionada'),
	(488,'403.04','Ingresos por operaciones discontinuas'),
	(489,'403.05','Ingresos por condonación de adeudo'),
	(490,'501','Costo de venta yo servicio'),
	(491,'501.01','Costo de venta'),
	(492,'501.02','Costo de servicios (Mano de obra)'),
	(493,'501.03','Materia prima directa utilizada para la producción'),
	(494,'501.04','Materia prima consumida en el proceso productivo'),
	(495,'501.05','Mano de obra directa consumida'),
	(496,'501.06','Mano de obra directa'),
	(497,'501.07','Cargos indirectos de producción'),
	(498,'501.08','Otros conceptos de costo'),
	(499,'502','Compras'),
	(500,'502.01','Compras nacionales'),
	(501,'502.02','Compras nacionales parte relacionada'),
	(502,'502.03','Compras de Importación'),
	(503,'502.04','Compras de Importación partes relacionadas'),
	(504,'503','Devoluciones, descuentos o bonificaciones sobre compras'),
	(505,'503.01','Devoluciones, descuentos o bonificaciones sobre compras'),
	(506,'504','Otras cuentas de costos'),
	(507,'504.01','Gastos indirectos de fabricación'),
	(508,'504.02','Gastos indirectos de fabricación de partes relacionadas nacionales'),
	(509,'504.03','Gastos indirectos de fabricación de partes relacionadas extranjeras'),
	(510,'504.04','Otras cuentas de costos incurridos'),
	(511,'504.05','Otras cuentas de costos incurridos con partes relacionadas nacionales'),
	(512,'504.06','Otras cuentas de costos incurridos con partes relacionadas extranjeras'),
	(513,'504.07','Depreciación de edificios'),
	(514,'504.08','Depreciación de maquinaria y equipo'),
	(515,'504.09','Depreciación de automóviles, autobuses, camiones de carga, tractocamiones, montacargas y remolques'),
	(516,'504.10','Depreciación de mobiliario y equipo de oficina'),
	(517,'504.11','Depreciación de equipo de cómputo'),
	(518,'504.12','Depreciación de equipo de comunicación'),
	(519,'504.13','Depreciación de activos biológicos, vegetales y semovientes'),
	(520,'504.14','Depreciación de otros activos fijos'),
	(521,'504.15','Depreciación de ferrocarriles'),
	(522,'504.16','Depreciación de embarcaciones'),
	(523,'504.17','Depreciación de aviones'),
	(524,'504.18','Depreciación de troqueles, moldes, matrices y herramental'),
	(525,'504.19','Depreciación de equipo de comunicaciones telefónicas'),
	(526,'504.20','Depreciación de equipo de comunicación satelital'),
	(527,'504.21','Depreciación de equipo de adaptaciones para personas con capacidades diferentes'),
	(528,'504.22','Depreciación de maquinaria y equipo de generación de energía de fuentes renovables o de sistemas de '),
	(529,'504.23','Depreciación de adaptaciones y mejoras'),
	(530,'504.24','Depreciación de otra maquinaria y equipo'),
	(531,'504.25','Otras cuentas de costos'),
	(532,'505','Costo de activo fijo'),
	(533,'505.01','Costo por venta de activo fijo'),
	(534,'505.02','Costo por baja de activo fijo'),
	(535,'601','Gastos generales'),
	(536,'601.01','Sueldos y salarios'),
	(537,'601.02','Compensaciones'),
	(538,'601.03','Tiempos extras'),
	(539,'601.04','Premios de asistencia'),
	(540,'601.05','Premios de puntualidad'),
	(541,'601.06','Vacaciones'),
	(542,'601.07','Prima vacacional'),
	(543,'601.08','Prima dominical'),
	(544,'601.09','Días festivos'),
	(545,'601.10','Gratificaciones'),
	(546,'601.11','Primas de antigüedad'),
	(547,'601.12','Aguinaldo'),
	(548,'601.13','Indemnizaciones'),
	(549,'601.14','Destajo'),
	(550,'601.15','Despensa'),
	(551,'601.16','Transporte'),
	(552,'601.17','Servicio médico'),
	(553,'601.18','Ayuda en gastos funerarios'),
	(554,'601.19','Fondo de ahorro'),
	(555,'601.20','Cuotas sindicales'),
	(556,'601.21','PTU'),
	(557,'601.22','Estímulo al personal'),
	(558,'601.23','Previsión social'),
	(559,'601.24','Aportaciones para el plan de jubilación'),
	(560,'601.25','Otras prestaciones al personal'),
	(561,'601.26','Cuotas al IMSS'),
	(562,'601.27','Aportaciones al infonavit'),
	(563,'601.28','Aportaciones al SAR'),
	(564,'601.29','Impuesto estatal sobre nóminas'),
	(565,'601.30','Otras aportaciones'),
	(566,'601.31','Asimilados a salarios'),
	(567,'601.32','Servicios administrativos'),
	(568,'601.33','Servicios administrativos partes relacionadas'),
	(569,'601.34','Honorarios a personas físicas residentes nacionales'),
	(570,'601.35','Honorarios a personas físicas residentes nacionales partes relacionadas'),
	(571,'601.36','Honorarios a personas físicas residentes del extranjero'),
	(572,'601.37','Honorarios a personas físicas residentes del extranjero partes relacionadas'),
	(573,'601.38','Honorarios a personas morales residentes nacionales'),
	(574,'601.39','Honorarios a personas morales residentes nacionales partes relacionadas'),
	(575,'601.40','Honorarios a personas morales residentes del extranjero'),
	(576,'601.41','Honorarios a personas morales residentes del extranjero partes relacionadas'),
	(577,'601.42','Honorarios aduanales personas físicas'),
	(578,'601.43','Honorarios aduanales personas morales'),
	(579,'601.44','Honorarios al consejo de administración'),
	(580,'601.45','Arrendamiento a personas físicas residentes nacionales'),
	(581,'601.46','Arrendamiento a personas morales residentes nacionales'),
	(582,'601.47','Arrendamiento a residentes del extranjero'),
	(583,'601.48','Combustibles y lubricantes'),
	(584,'601.49','Viáticos y gastos de viaje'),
	(585,'601.50','Teléfono, internet'),
	(586,'601.51','Agua'),
	(587,'601.52','Energía eléctrica'),
	(588,'601.53','Vigilancia y seguridad'),
	(589,'601.54','Limpieza'),
	(590,'601.55','Papelería y artículos de oficina'),
	(591,'601.56','Mantenimiento y conservación'),
	(592,'601.57','Seguros y fianzas'),
	(593,'601.58','Otros impuestos y derechos'),
	(594,'601.59','Recargos fiscales'),
	(595,'601.60','Cuotas y suscripciones'),
	(596,'601.61','Propaganda y publicidad'),
	(597,'601.62','Capacitación al personal'),
	(598,'601.63','Donativos y ayudas'),
	(599,'601.64','Asistencia técnica'),
	(600,'601.65','Regalías sujetas a otros porcentajes'),
	(602,'601.66','Regalías sujetas al 5%'),
	(603,'601.67','Regalías sujetas al 10%'),
	(604,'601.68','Regalías sujetas al 15%'),
	(605,'601.69','Regalías sujetas al 25%'),
	(606,'601.70','Regalías sujetas al 30%'),
	(607,'601.71','Regalías sin retención'),
	(608,'601.72','Fletes y acarreos'),
	(609,'601.73','Gastos de importación'),
	(610,'601.74','Comisiones sobre ventas'),
	(611,'601.75','Comisiones por tarjetas de crédito'),
	(612,'601.76','Patentes y marcas'),
	(613,'601.77','Uniformes'),
	(614,'601.78','Prediales'),
	(615,'601.79','Gastos generales de urbanización'),
	(616,'601.80','Gastos generales de construcción'),
	(617,'601.81','Fletes del extranjero'),
	(618,'601.82','Recolección de bienes del sector agropecuario yo ganadero'),
	(619,'601.83','Gastos no deducibles (sin requisitos fiscales)'),
	(620,'601.84','Otros gastos generales'),
	(621,'602','Gastos de venta'),
	(622,'602.01','Sueldos y salarios'),
	(623,'602.02','Compensaciones'),
	(624,'602.03','Tiempos extras'),
	(625,'602.04','Premios de asistencia'),
	(626,'602.05','Premios de puntualidad'),
	(627,'602.06','Vacaciones'),
	(628,'602.07','Prima vacacional'),
	(629,'602.08','Prima dominical'),
	(630,'602.09','Días festivos'),
	(631,'602.10','Gratificaciones'),
	(632,'602.11','Primas de antigüedad'),
	(633,'602.12','Aguinaldo'),
	(634,'602.13','Indemnizaciones'),
	(635,'602.14','Destajo'),
	(636,'602.15','Despensa'),
	(637,'602.16','Transporte'),
	(638,'602.17','Servicio médico'),
	(639,'602.18','Ayuda en gastos funerarios'),
	(640,'602.19','Fondo de ahorro'),
	(641,'602.20','Cuotas sindicales'),
	(642,'602.21','PTU'),
	(643,'602.22','Estímulo al personal'),
	(644,'602.23','Previsión social'),
	(645,'602.24','Aportaciones para el plan de jubilación'),
	(646,'602.25','Otras prestaciones al personal'),
	(647,'602.26','Cuotas al IMSS'),
	(648,'602.27','Aportaciones al infonavit'),
	(649,'602.28','Aportaciones al SAR'),
	(650,'602.29','Impuesto estatal sobre nóminas'),
	(651,'602.30','Otras aportaciones'),
	(652,'602.31','Asimilados a salarios'),
	(653,'602.32','Servicios administrativos'),
	(654,'602.33','Servicios administrativos partes relacionadas'),
	(655,'602.34','Honorarios a personas físicas residentes nacionales'),
	(656,'602.35','Honorarios a personas físicas residentes nacionales partes relacionadas'),
	(657,'602.36','Honorarios a personas físicas residentes del extranjero'),
	(658,'602.37','Honorarios a personas físicas residentes del extranjero partes relacionadas'),
	(659,'602.38','Honorarios a personas morales residentes nacionales'),
	(660,'602.39','Honorarios a personas morales residentes nacionales partes relacionadas'),
	(661,'602.40','Honorarios a personas morales residentes del extranjero'),
	(662,'602.41','Honorarios a personas morales residentes del extranjero partes relacionadas'),
	(663,'602.42','Honorarios aduanales personas físicas'),
	(664,'602.43','Honorarios aduanales personas morales'),
	(665,'602.44','Honorarios al consejo de administración'),
	(666,'602.45','Arrendamiento a personas físicas residentes nacionales'),
	(667,'602.46','Arrendamiento a personas morales residentes nacionales'),
	(668,'602.47','Arrendamiento a residentes del extranjero'),
	(669,'602.48','Combustibles y lubricantes'),
	(670,'602.49','Viáticos y gastos de viaje'),
	(671,'602.50','Teléfono, internet'),
	(672,'602.51','Agua'),
	(673,'602.52','Energía eléctrica'),
	(674,'602.53','Vigilancia y seguridad'),
	(675,'602.54','Limpieza'),
	(676,'602.55','Papelería y artículos de oficina'),
	(677,'602.56','Mantenimiento y conservación'),
	(678,'602.57','Seguros y fianzas'),
	(679,'602.58','Otros impuestos y derechos'),
	(680,'602.59','Recargos fiscales'),
	(681,'602.60','Cuotas y suscripciones'),
	(682,'602.61','Propaganda y publicidad'),
	(683,'602.62','Capacitación al personal'),
	(684,'602.63','Donativos y ayudas'),
	(685,'602.64','Asistencia técnica'),
	(686,'602.65','Regalías sujetas a otros porcentajes'),
	(687,'602.66','Regalías sujetas al 5%'),
	(688,'602.67','Regalías sujetas al 10%'),
	(689,'602.68','Regalías sujetas al 15%'),
	(690,'602.69','Regalías sujetas al 25%'),
	(691,'602.70','Regalías sujetas al 30%'),
	(692,'602.71','Regalías sin retención'),
	(693,'602.72','Fletes y acarreos'),
	(694,'602.73','Gastos de importación'),
	(695,'602.74','Comisiones sobre ventas'),
	(696,'602.75','Comisiones por tarjetas de crédito'),
	(697,'602.76','Patentes y marcas'),
	(698,'602.77','Uniformes'),
	(699,'602.78','Prediales'),
	(700,'602.79','Gastos de venta de urbanización'),
	(701,'602.80','Gastos de venta de construcción'),
	(702,'602.81','Fletes del extranjero'),
	(703,'602.82','Recolección de bienes del sector agropecuario yo ganadero'),
	(704,'602.83','Gastos no deducibles (sin requisitos fiscales)'),
	(705,'602.84','Otros gastos de venta'),
	(706,'603','Gastos de administración'),
	(707,'603.01','Sueldos y salarios'),
	(708,'603.02','Compensaciones'),
	(709,'603.03','Tiempos extras'),
	(710,'603.04','Premios de asistencia'),
	(711,'603.05','Premios de puntualidad'),
	(712,'603.06','Vacaciones'),
	(713,'603.07','Prima vacacional'),
	(714,'603.08','Prima dominical'),
	(715,'603.09','Días festivos'),
	(716,'603.10','Gratificaciones'),
	(717,'603.11','Primas de antigüedad'),
	(718,'603.12','Aguinaldo'),
	(719,'603.13','Indemnizaciones'),
	(720,'603.14','Destajo'),
	(721,'603.15','Despensa'),
	(722,'603.16','Transporte'),
	(723,'603.17','Servicio médico'),
	(724,'603.18','Ayuda en gastos funerarios'),
	(725,'603.19','Fondo de ahorro'),
	(726,'603.20','Cuotas sindicales'),
	(727,'603.21','PTU'),
	(728,'603.22','Estímulo al personal'),
	(729,'603.23','Previsión social'),
	(730,'603.24','Aportaciones para el plan de jubilación'),
	(731,'603.25','Otras prestaciones al personal'),
	(732,'603.26','Cuotas al IMSS'),
	(733,'603.27','Aportaciones al infonavit'),
	(734,'603.28','Aportaciones al SAR'),
	(735,'603.29','Impuesto estatal sobre nóminas'),
	(736,'603.30','Otras aportaciones'),
	(737,'603.31','Asimilados a salarios'),
	(738,'603.32','Servicios administrativos'),
	(739,'603.33','Servicios administrativos partes relacionadas'),
	(740,'603.34','Honorarios a personas físicas residentes nacionales'),
	(741,'603.35','Honorarios a personas físicas residentes nacionales partes relacionadas'),
	(742,'603.36','Honorarios a personas físicas residentes del extranjero'),
	(743,'603.37','Honorarios a personas físicas residentes del extranjero partes relacionadas'),
	(744,'603.38','Honorarios a personas morales residentes nacionales'),
	(745,'603.39','Honorarios a personas morales residentes nacionales partes relacionadas'),
	(746,'603.40','Honorarios a personas morales residentes del extranjero'),
	(747,'603.41','Honorarios a personas morales residentes del extranjero partes relacionadas'),
	(748,'603.42','Honorarios aduanales personas físicas'),
	(749,'603.43','Honorarios aduanales personas morales'),
	(750,'603.44','Honorarios al consejo de administración'),
	(751,'603.45','Arrendamiento a personas físicas residentes nacionales'),
	(752,'603.46','Arrendamiento a personas morales residentes nacionales'),
	(753,'603.47','Arrendamiento a residentes del extranjero'),
	(754,'603.48','Combustibles y lubricantes'),
	(755,'603.49','Viáticos y gastos de viaje'),
	(756,'603.50','Teléfono, internet'),
	(757,'603.51','Agua'),
	(758,'603.52','Energía eléctrica'),
	(759,'603.53','Vigilancia y seguridad'),
	(760,'603.54','Limpieza'),
	(761,'603.55','Papelería y artículos de oficina'),
	(762,'603.56','Mantenimiento y conservación'),
	(763,'603.57','Seguros y fianzas'),
	(764,'603.58','Otros impuestos y derechos'),
	(765,'603.59','Recargos fiscales'),
	(766,'603.60','Cuotas y suscripciones'),
	(767,'603.61','Propaganda y publicidad'),
	(768,'603.62','Capacitación al personal'),
	(769,'603.63','Donativos y ayudas'),
	(770,'603.64','Asistencia técnica'),
	(771,'603.65','Regalías sujetas a otros porcentajes'),
	(772,'603.66','Regalías sujetas al 5%'),
	(773,'603.67','Regalías sujetas al 10%'),
	(774,'603.68','Regalías sujetas al 15%'),
	(775,'603.69','Regalías sujetas al 25%'),
	(776,'603.70','Regalías sujetas al 30%'),
	(777,'603.71','Regalías sin retención'),
	(778,'603.72','Fletes y acarreos'),
	(779,'603.73','Gastos de importación'),
	(780,'603.74','Patentes y marcas'),
	(781,'603.75','Uniformes'),
	(782,'603.76','Prediales'),
	(783,'603.77','Gastos de administración de urbanización'),
	(784,'603.78','Gastos de administración de construcción'),
	(785,'603.79','Fletes del extranjero'),
	(786,'603.80','Recolección de bienes del sector agropecuario yo ganadero'),
	(787,'603.81','Gastos no deducibles (sin requisitos fiscales)'),
	(788,'603.82','Otros gastos de administración'),
	(789,'604','Gastos de fabricación'),
	(790,'604.01','Sueldos y salarios'),
	(791,'604.02','Compensaciones'),
	(792,'604.03','Tiempos extras'),
	(793,'604.04','Premios de asistencia'),
	(794,'604.05','Premios de puntualidad'),
	(795,'604.06','Vacaciones'),
	(796,'604.07','Prima vacacional'),
	(797,'604.08','Prima dominical'),
	(798,'604.09','Días festivos'),
	(799,'604.10','Gratificaciones'),
	(800,'604.11','Primas de antigüedad'),
	(801,'604.12','Aguinaldo'),
	(802,'604.13','Indemnizaciones'),
	(803,'604.14','Destajo'),
	(804,'604.15','Despensa'),
	(805,'604.16','Transporte'),
	(806,'604.17','Servicio médico'),
	(807,'604.18','Ayuda en gastos funerarios'),
	(808,'604.19','Fondo de ahorro'),
	(809,'604.20','Cuotas sindicales'),
	(810,'604.21','PTU'),
	(811,'604.22','Estímulo al personal'),
	(812,'604.23','Previsión social'),
	(813,'604.24','Aportaciones para el plan de jubilación'),
	(814,'604.25','Otras prestaciones al personal'),
	(815,'604.26','Cuotas al IMSS'),
	(816,'604.27','Aportaciones al infonavit'),
	(817,'604.28','Aportaciones al SAR'),
	(818,'604.29','Impuesto estatal sobre nóminas'),
	(819,'604.30','Otras aportaciones'),
	(820,'604.31','Asimilados a salarios'),
	(821,'604.32','Servicios administrativos'),
	(822,'604.33','Servicios administrativos partes relacionadas'),
	(823,'604.34','Honorarios a personas físicas residentes nacionales'),
	(824,'604.35','Honorarios a personas físicas residentes nacionales partes relacionadas'),
	(825,'604.36','Honorarios a personas físicas residentes del extranjero'),
	(826,'604.37','Honorarios a personas físicas residentes del extranjero partes relacionadas'),
	(827,'604.38','Honorarios a personas morales residentes nacionales'),
	(828,'604.39','Honorarios a personas morales residentes nacionales partes relacionadas'),
	(829,'604.40','Honorarios a personas morales residentes del extranjero'),
	(830,'604.41','Honorarios a personas morales residentes del extranjero partes relacionadas'),
	(831,'604.42','Honorarios aduanales personas físicas'),
	(832,'604.43','Honorarios aduanales personas morales'),
	(833,'604.44','Honorarios al consejo de administración'),
	(834,'604.45','Arrendamiento a personas físicas residentes nacionales'),
	(835,'604.46','Arrendamiento a personas morales residentes nacionales'),
	(836,'604.47','Arrendamiento a residentes del extranjero'),
	(837,'604.48','Combustibles y lubricantes'),
	(838,'604.49','Viáticos y gastos de viaje'),
	(839,'604.50','Teléfono, internet'),
	(840,'604.51','Agua'),
	(841,'604.52','Energía eléctrica'),
	(842,'604.53','Vigilancia y seguridad'),
	(843,'604.54','Limpieza'),
	(844,'604.55','Papelería y artículos de oficina'),
	(845,'604.56','Mantenimiento y conservación'),
	(846,'604.57','Seguros y fianzas'),
	(847,'604.58','Otros impuestos y derechos'),
	(848,'604.59','Recargos fiscales'),
	(849,'604.60','Cuotas y suscripciones'),
	(850,'604.61','Propaganda y publicidad'),
	(851,'604.62','Capacitación al personal'),
	(852,'604.63','Donativos y ayudas'),
	(853,'604.64','Asistencia técnica'),
	(854,'604.65','Regalías sujetas a otros porcentajes'),
	(855,'604.66','Regalías sujetas al 5%'),
	(856,'604.67','Regalías sujetas al 10%'),
	(857,'604.68','Regalías sujetas al 15%'),
	(858,'604.69','Regalías sujetas al 25%'),
	(859,'604.70','Regalías sujetas al 30%'),
	(860,'604.71','Regalías sin retención'),
	(861,'604.72','Fletes y acarreos'),
	(862,'604.73','Gastos de importación'),
	(863,'604.74','Patentes y marcas'),
	(864,'604.75','Uniformes'),
	(865,'604.76','Prediales'),
	(866,'604.77','Gastos de fabricación de urbanización'),
	(867,'604.78','Gastos de fabricación de construcción'),
	(868,'604.79','Fletes del extranjero'),
	(869,'604.80','Recolección de bienes del sector agropecuario yo ganadero'),
	(870,'604.81','Gastos no deducibles (sin requisitos fiscales)'),
	(871,'604.82','Otros gastos de fabricación'),
	(872,'605','Mano de obra directa'),
	(873,'605.01','Mano de obra'),
	(874,'605.02','Sueldos y Salarios'),
	(875,'605.03','Compensaciones'),
	(876,'605.04','Tiempos extras'),
	(877,'605.05','Premios de asistencia'),
	(878,'605.06','Premios de puntualidad'),
	(879,'605.07','Vacaciones'),
	(880,'605.08','Prima vacacional'),
	(881,'605.09','Prima dominical'),
	(882,'605.10','Días festivos'),
	(883,'605.11','Gratificaciones'),
	(884,'605.12','Primas de antigüedad'),
	(885,'605.13','Aguinaldo'),
	(886,'605.14','Indemnizaciones'),
	(887,'605.15','Destajo'),
	(888,'605.16','Despensa'),
	(889,'605.17','Transporte'),
	(890,'605.18','Servicio médico'),
	(891,'605.19','Ayuda en gastos funerarios'),
	(892,'605.20','Fondo de ahorro'),
	(893,'605.21','Cuotas sindicales'),
	(894,'605.22','PTU'),
	(895,'605.23','Estímulo al personal'),
	(896,'605.24','Previsión social'),
	(897,'605.25','Aportaciones para el plan de jubilación'),
	(898,'605.26','Otras prestaciones al personal'),
	(899,'605.27','Asimilados a salarios'),
	(900,'605.28','Cuotas al IMSS'),
	(901,'605.29','Aportaciones al infonavit'),
	(903,'605.30','Aportaciones al SAR'),
	(904,'605.31','Otros costos de mano de obra directa'),
	(905,'606','Facilidades administrativas fiscales'),
	(906,'606.01','Facilidades administrativas fiscales'),
	(907,'607','Participación de los trabajadores en las utilidades'),
	(908,'607.01','Participación de los trabajadores en las utilidades'),
	(909,'608','Participación en resultados de subsidiarias'),
	(910,'608.01','Participación en resultados de subsidiarias'),
	(911,'609','Participación en resultados de asociadas'),
	(912,'609.01','Participación en resultados de asociadas'),
	(913,'610','Participación de los trabajadores en las utilidades diferida'),
	(914,'610.01','Participación de los trabajadores en las utilidades diferida'),
	(915,'611','Impuesto Sobre la renta'),
	(916,'611.01','Impuesto Sobre la renta'),
	(917,'611.02','Impuesto Sobre la renta por remanente distribuible'),
	(918,'612','Gastos no deducibles para CUFIN'),
	(919,'612.01','Gastos no deducibles para CUFIN'),
	(920,'613','Depreciación contable'),
	(921,'613.01','Depreciación de edificios'),
	(922,'613.02','Depreciación de maquinaria y equipo'),
	(923,'613.03','Depreciación de automóviles, autobuses, camiones de carga, tractocamiones, montacargas y remolques'),
	(924,'613.04','Depreciación de mobiliario y equipo de oficina'),
	(925,'613.05','Depreciación de equipo de cómputo'),
	(926,'613.06','Depreciación de equipo de comunicación'),
	(927,'613.07','Depreciación de activos biológicos, vegetales y semovientes'),
	(928,'613.08','Depreciación de otros activos fijos'),
	(929,'613.09','Depreciación de ferrocarriles'),
	(930,'613.10','Depreciación de embarcaciones'),
	(931,'613.11','Depreciación de aviones'),
	(932,'613.12','Depreciación de troqueles, moldes, matrices y herramental'),
	(933,'613.13','Depreciación de equipo de comunicaciones telefónicas'),
	(934,'613.14','Depreciación de equipo de comunicación satelital'),
	(935,'613.15','Depreciación de equipo de adaptaciones para personas con capacidades diferentes'),
	(936,'613.16','Depreciación de maquinaria y equipo de generación de energía de fuentes renovables o de sistemas de '),
	(937,'613.17','Depreciación de adaptaciones y mejoras'),
	(938,'613.18','Depreciación de otra maquinaria y equipo'),
	(939,'614','Amortización contable'),
	(940,'614.01','Amortización de gastos diferidos'),
	(941,'614.02','Amortización de gastos pre operativos'),
	(942,'614.03','Amortización de regalías, asistencia técnica y otros gastos diferidos'),
	(943,'614.04','Amortización de activos intangibles'),
	(944,'614.05','Amortización de gastos de organización'),
	(945,'614.06','Amortización de investigación y desarrollo de mercado'),
	(946,'614.07','Amortización de marcas y patentes'),
	(947,'614.08','Amortización de crédito mercantil'),
	(948,'614.09','Amortización de gastos de instalación'),
	(949,'614.10','Amortización de otros activos diferidos'),
	(950,'701','Gastos financieros'),
	(951,'701.01','Pérdida cambiaria'),
	(952,'701.02','Pérdida cambiaria nacional parte relacionada'),
	(953,'701.03','Pérdida cambiaria extranjero parte relacionada'),
	(954,'701.04','Intereses a cargo bancario nacional'),
	(955,'701.05','Intereses a cargo bancario extranjero'),
	(956,'701.06','Intereses a cargo de personas físicas nacional'),
	(957,'701.07','Intereses a cargo de personas físicas extranjero'),
	(958,'701.08','Intereses a cargo de personas morales nacional'),
	(959,'701.09','Intereses a cargo de personas morales extranjero'),
	(960,'701.10','Comisiones bancarias'),
	(961,'701.11','Otros gastos financieros'),
	(962,'702','Productos financieros'),
	(963,'702.01','Utilidad cambiaria'),
	(964,'702.02','Utilidad cambiaria nacional parte relacionada'),
	(965,'702.03','Utilidad cambiaria extranjero parte relacionada'),
	(966,'702.04','Intereses a favor bancarios nacional'),
	(967,'702.05','Intereses a favor bancarios extranjero'),
	(968,'702.06','Intereses a favor de personas físicas nacional'),
	(969,'702.07','Intereses a favor de personas físicas extranjero'),
	(970,'702.08','Intereses a favor de personas morales nacional'),
	(971,'702.09','Intereses a favor de personas morales extranjero'),
	(972,'702.10','Otros productos financieros'),
	(974,'703','Otros gastos'),
	(975,'703.01','Pérdida en venta yo baja de terrenos'),
	(976,'703.02','Pérdida en venta yo baja de edificios'),
	(977,'703.03','Pérdida en venta yo baja de maquinaria y equipo'),
	(978,'703.04','Pérdida en venta yo baja de automóviles, autobuses, camiones de carga, tractocamiones, montacargas y'),
	(979,'703.05','Pérdida en venta yo baja de mobiliario y equipo de oficina'),
	(980,'703.06','Pérdida en venta yo baja de equipo de cómputo'),
	(981,'703.07','Pérdida en venta yo baja de equipo de comunicación'),
	(982,'703.08','Pérdida en venta yo baja de activos biológicos, vegetales y semovientes'),
	(983,'703.09','Pérdida en venta yo baja de otros activos fijos'),
	(984,'703.10','Pérdida en venta yo baja de ferrocarriles'),
	(985,'703.11','Pérdida en venta yo baja de embarcaciones'),
	(986,'703.12','Pérdida en venta yo baja de aviones'),
	(987,'703.13','Pérdida en venta yo baja de troqueles, moldes, matrices y herramental'),
	(988,'703.14','Pérdida en venta yo baja de equipo de comunicaciones telefónicas'),
	(989,'703.15','Pérdida en venta yo baja de equipo de comunicación satelital'),
	(990,'703.16','Pérdida en venta yo baja de equipo de adaptaciones para personas con capacidades diferentes'),
	(991,'703.17','Pérdida en venta yo baja de maquinaria y equipo de generación de enregía de fuentes renovables o de '),
	(992,'703.18','Pérdida en venta yo baja de otra maquinaria y equipo'),
	(993,'703.19','Pérdida por enajenación de acciones'),
	(994,'703.20','Pérdida por enajenación de partes sociales'),
	(995,'703.21','Otros gastos'),
	(996,'704','Otros productos'),
	(997,'704.01','Ganancia en venta yo baja de terrenos'),
	(998,'704.02','Ganancia en venta yo baja de edificios'),
	(999,'704.03','Ganancia en venta yo baja de maquinaria y equipo'),
	(1000,'704.04','Ganancia en venta yo baja de automóviles, autobuses, camiones de carga, tractocamiones, montacargas '),
	(1001,'704.05','Ganancia en venta yo baja de mobiliario y equipo de oficina'),
	(1002,'704.06','Ganancia en venta yo baja de equipo de cómputo'),
	(1003,'704.07','Ganancia en venta yo baja de equipo de comunicación'),
	(1004,'704.08','Ganancia en venta yo baja de activos biológicos, vegetales y semovientes'),
	(1005,'704.09','Ganancia en venta yo baja de otros activos fijos'),
	(1006,'704.10','Ganancia en venta yo baja de ferrocarriles'),
	(1007,'704.11','Ganancia en venta yo baja de embarcaciones'),
	(1008,'704.12','Ganancia en venta yo baja de aviones'),
	(1009,'704.13','Ganancia en venta yo baja de troqueles, moldes, matrices y herramental'),
	(1010,'704.14','Ganancia en venta yo baja de equipo de comunicaciones telefónicas'),
	(1011,'704.15','Ganancia en venta yo baja de equipo de comunicación satelital'),
	(1012,'704.16','Ganancia en venta yo baja de equipo de adaptaciones para personas con capacidades diferentes'),
	(1013,'704.17','Ganancia en venta de maquinaria y equipo de generación de energía de fuentes renovables o de sistema'),
	(1014,'704.18','Ganancia en venta yo baja de otra maquinaria y equipo'),
	(1015,'704.19','Ganancia por enajenación de acciones'),
	(1016,'704.20','Ganancia por enajenación de partes sociales'),
	(1017,'704.21','Ingresos por estímulos fiscales'),
	(1018,'704.22','Ingresos por condonación de adeudo'),
	(1019,'704.23','Otros productos'),
	(1020,'801','UFIN del ejercicio'),
	(1021,'801.01','UFIN'),
	(1022,'801.02','Contra cuenta UFIN'),
	(1023,'802','CUFIN del ejercicio'),
	(1024,'802.01','CUFIN'),
	(1025,'802.02','Contra cuenta CUFIN'),
	(1026,'803','CUFIN de ejercicios anteriores'),
	(1027,'803.01','CUFIN de ejercicios anteriores'),
	(1028,'803.02','Contra cuenta CUFIN de ejercicios anteriores'),
	(1029,'804','CUFINRE del ejercicio'),
	(1030,'804.01','CUFINRE'),
	(1031,'804.02','Contra cuenta CUFINRE '),
	(1032,'805','CUFINRE de ejercicios anteriores'),
	(1033,'805.01','CUFINRE de ejercicios anteriores'),
	(1034,'805.02','Contra cuenta CUFINRE de ejercicios anteriores'),
	(1035,'806','CUCA del ejercicio'),
	(1036,'806.01','CUCA'),
	(1037,'806.02','Contra cuenta CUCA'),
	(1038,'807','CUCA de ejercicios anteriores'),
	(1039,'807.01','CUCA de ejercicios anteriores'),
	(1040,'807.02','Contra cuenta CUCA de ejercicios anteriores'),
	(1041,'808','Ajuste anual por inflación acumulable'),
	(1042,'808.01','Ajuste anual por inflación acumulable'),
	(1043,'808.02','Acumulación del ajuste anual inflacionario'),
	(1044,'809','Ajuste anual por inflación deducible'),
	(1045,'809.01','Ajuste anual por inflación deducible'),
	(1046,'809.02','Deducción del ajuste anual inflacionario'),
	(1047,'810','Deducción de inversión'),
	(1048,'810.01','Deducción de inversión'),
	(1049,'810.02','Contra cuenta deducción de inversiones'),
	(1050,'811','Utilidad o pérdida fiscal en venta yo baja de activo fijo'),
	(1051,'811.01','Utilidad o pérdida fiscal en venta yo baja de activo fijo'),
	(1052,'811.02','Contra cuenta utilidad o pérdida fiscal en venta yo baja de activo fijo'),
	(1053,'812','Utilidad o pérdida fiscal en venta acciones o partes sociales'),
	(1054,'812.01','Utilidad o pérdida fiscal en venta acciones o partes sociales'),
	(1055,'812.02','Contra cuenta utilidad o pérdida fiscal en venta acciones o partes sociales'),
	(1056,'813','Pérdidas fiscales pendientes de amortizar actualizadas de ejercicios anteriores'),
	(1057,'813.01','Pérdidas fiscales pendientes de amortizar actualizadas de ejercicios anteriores'),
	(1058,'813.02','Actualización de pérdidas fiscales pendientes de amortizar de ejercicios anteriores'),
	(1059,'814','Mercancías recibidas en consignación'),
	(1060,'814.01','Mercancías recibidas en consignación'),
	(1061,'814.02','Consignación de mercancías recibidas'),
	(1062,'815','Crédito fiscal de IVA e IEPS por la importación de mercancías para empresas certificadas'),
	(1063,'815.01','Crédito fiscal de IVA e IEPS por la importación de mercancías'),
	(1064,'815.02','Importación de mercancías con aplicación de crédito fiscal de IVA e IEPS'),
	(1065,'816','Crédito fiscal de IVA e IEPS por la importación de activos fijos para empresas certificadas'),
	(1066,'816.01','Crédito fiscal de IVA e IEPS por la importación de activo fijo'),
	(1067,'816.02','Importación de activo fijo con aplicación de crédito fiscal de IVA e IEPS'),
	(1068,'899','Otras cuentas de orden'),
	(1069,'899.01','Otras cuentas de orden'),
	(1070,'899.02','Contra cuenta otras cuentas de orden'),
	(1071,'200','Pasivo'),
	(1072,'200.01','Pasivo a corto plazo'),
	(1073,'200.02','Pasivo a largo plazo'),
	(1074,'300','Capital contable'),
	(1075,'400','Ingresos'),
	(1076,'600','Gastos'),
	(1077,'700','Resultado integral de financiamiento'),
	(1078,'800','Cuentas de orden');

/*!40000 ALTER TABLE `cont_diarioficial` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_ejercicios
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_ejercicios`;

CREATE TABLE `cont_ejercicios` (
  `Id` int(2) NOT NULL AUTO_INCREMENT,
  `NombreEjercicio` int(4) NOT NULL,
  `Cerrado` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_grupo_facturas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_grupo_facturas`;

CREATE TABLE `cont_grupo_facturas` (
  `IdPoliza` int(11) NOT NULL,
  `NumMovimiento` int(11) NOT NULL,
  `Factura` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `UUID` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla cont_IETU
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_IETU`;

CREATE TABLE `cont_IETU` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_IETU` WRITE;
/*!40000 ALTER TABLE `cont_IETU` DISABLE KEYS */;

INSERT INTO `cont_IETU` (`id`, `nombre`)
VALUES
	(1,'Deduccion por Adquisicion de Bienes'),
	(2,'Ingresos Gravados'),
	(3,'Ingresos Exentos Agropecuarios'),
	(4,'Ingresos Exentos del Inciso a) Fraccion VI, Articulo 4,LIETU'),
	(5,'Ingresos Exentos de Fondos de Pensiones y Jubilaciones del Extrangero'),
	(6,'Ingresos Exentos de Cajas de Ahorro'),
	(7,'Otros Ingresos Exentos'),
	(8,'Deduccion por Adquisicion de Bienes'),
	(9,'Deduccion por Servicios Independientes'),
	(10,'Deduccion por el Uso o Goce Temporal de Bienes'),
	(11,'Deduccion por Contribuciones a Cargo'),
	(12,'Deduccion de Erogaciones por Aprovechamientos'),
	(13,'Deduccion de Indemnizaciones por Daños y Perjuicios y Penas Convencionales'),
	(14,'Deduccion por Premios Pagados en Efectivo '),
	(15,'Deduccion por Donativos'),
	(16,'Deduccion por Perdidas por Creditos Incobrables y Caso de Fortuito o Fuerza Mayor'),
	(17,'Deduccion de Inversiones'),
	(18,'Deduccion de Reservas Preventivas Globales para las Instituciones de Credito'),
	(19,'Deduccion de Creditos Incobrables'),
	(20,'Deduccion Adicional por Inversiones'),
	(21,'Otras Deducciones Autorizadas'),
	(22,'Otros Gastos No Deducibles'),
	(23,'Acreditamientos para IETU Sueldos y Salarios'),
	(24,'Acreditamientos para IETU Seguridad Social');

/*!40000 ALTER TABLE `cont_IETU` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_main_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_main_type`;

CREATE TABLE `cont_main_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_main_type` WRITE;
/*!40000 ALTER TABLE `cont_main_type` DISABLE KEYS */;

INSERT INTO `cont_main_type` (`type_id`, `description`)
VALUES
	(1,'DE MAYOR'),
	(2,'AGRUPADORA'),
	(3,'SUB-CUENTA');

/*!40000 ALTER TABLE `cont_main_type` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_movimientos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_movimientos`;

CREATE TABLE `cont_movimientos` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `IdPoliza` int(11) NOT NULL,
  `NumMovto` int(3) NOT NULL,
  `IdSegmento` int(2) NOT NULL,
  `IdSucursal` int(2) NOT NULL,
  `Cuenta` int(4) NOT NULL,
  `TipoMovto` varchar(20) DEFAULT NULL,
  `Importe` double NOT NULL DEFAULT '0',
  `Referencia` varchar(40) NOT NULL DEFAULT '',
  `Concepto` varchar(50) NOT NULL,
  `Activo` int(1) NOT NULL,
  `FechaCreacion` datetime NOT NULL,
  `Factura` varchar(200) DEFAULT NULL,
  `MultipleFacturas` int(1) NOT NULL DEFAULT '0',
  `Persona` varchar(20) DEFAULT NULL,
  `FormaPago` int(1) NOT NULL DEFAULT '0',
  `conciliado` int(11) NOT NULL DEFAULT '0' COMMENT '0-no 1-si',
  `tipocambio` double(100,4) DEFAULT '0.0000',
  PRIMARY KEY (`Id`),
  KEY `movimientos_Cuentasx` (`Cuenta`),
  KEY `movimientos_IdPolizax` (`IdPoliza`),
  KEY `movimientos_IdPoliza_idx` (`IdPoliza`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_nature
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_nature`;

CREATE TABLE `cont_nature` (
  `nature_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT ' ',
  PRIMARY KEY (`nature_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_nature` WRITE;
/*!40000 ALTER TABLE `cont_nature` DISABLE KEYS */;

INSERT INTO `cont_nature` (`nature_id`, `description`)
VALUES
	(1,'ACREEDORA'),
	(2,'DEUDORA');

/*!40000 ALTER TABLE `cont_nature` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_nodeducible
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_nodeducible`;

CREATE TABLE `cont_nodeducible` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `concepto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `importe` double DEFAULT NULL,
  `idAnticipo` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1' COMMENT '1-activo, 0-en poliza inactivo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla cont_polizas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_polizas`;

CREATE TABLE `cont_polizas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idorganizacion` int(11) DEFAULT NULL,
  `idejercicio` int(11) DEFAULT NULL,
  `idperiodo` int(11) DEFAULT NULL,
  `numpol` int(11) NOT NULL,
  `idtipopoliza` int(11) DEFAULT NULL,
  `referencia` varchar(40) DEFAULT NULL,
  `concepto` varchar(50) DEFAULT NULL,
  `cargos` double DEFAULT NULL,
  `abonos` double DEFAULT NULL,
  `ajuste` int(1) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `eliminado` tinyint(1) NOT NULL,
  `pdv_aut` int(1) DEFAULT '0',
  `relacionExt` int(5) NOT NULL DEFAULT '0',
  `beneficiario` int(11) NOT NULL DEFAULT '0',
  `numero` varchar(30) DEFAULT '0',
  `rfc` varchar(15) DEFAULT NULL,
  `idbanco` int(11) NOT NULL DEFAULT '0',
  `numtarjcuent` varchar(100) NOT NULL DEFAULT '0',
  `saldado` int(11) NOT NULL DEFAULT '0',
  `idCuentaBancariaOrigen` int(11) DEFAULT '0',
  `Anticipo` int(11) DEFAULT '0' COMMENT '1-si, 0- no',
  `idAnticipo` int(11) DEFAULT '0',
  `idUser` int(11) NOT NULL DEFAULT '0',
  `tipoBeneficiario` int(11) DEFAULT NULL COMMENT '1-prv 2-empleado',
  `usuario_creacion` int(11) NOT NULL,
  `usuario_modificacion` int(11) NOT NULL,
  `fecha_modificacion` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_presupuestos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_presupuestos`;

CREATE TABLE `cont_presupuestos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ejercicio` int(2) NOT NULL,
  `cuenta` int(11) NOT NULL,
  `segmento` int(3) NOT NULL,
  `sucursal` int(2) NOT NULL,
  `anual` double(16,2) NOT NULL,
  `meses` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `activo` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla cont_rel_desglose_iva
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_rel_desglose_iva`;

CREATE TABLE `cont_rel_desglose_iva` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idPoliza` int(11) NOT NULL,
  `tasa16` varchar(50) NOT NULL DEFAULT '0',
  `tasa11` varchar(50) NOT NULL DEFAULT '0',
  `tasa0` varchar(35) NOT NULL DEFAULT '0',
  `tasaExenta` varchar(35) NOT NULL DEFAULT '0',
  `tasa15` varchar(50) NOT NULL DEFAULT '0',
  `tasa10` varchar(50) NOT NULL DEFAULT '0',
  `otrasTasas` varchar(50) NOT NULL DEFAULT '0',
  `ivaRetenido` float NOT NULL DEFAULT '0',
  `isrRetenido` float NOT NULL DEFAULT '0',
  `otros` float NOT NULL DEFAULT '0',
  `aplica` tinyint(1) NOT NULL,
  `periodoAcreditamiento` int(2) NOT NULL,
  `ejercicioAcreditamiento` int(2) NOT NULL,
  `acreditableIETU` double DEFAULT NULL,
  `conceptoIETU` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_rel_pol_prov
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_rel_pol_prov`;

CREATE TABLE `cont_rel_pol_prov` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idPoliza` int(11) NOT NULL,
  `idProveedor` int(11) NOT NULL,
  `referencia` varchar(25) DEFAULT '',
  `tasa` float NOT NULL,
  `importe` double(10,2) NOT NULL,
  `importeBase` double(10,2) NOT NULL,
  `otrasErogaciones` double(10,2) NOT NULL DEFAULT '0.00',
  `ivaRetenido` double(10,2) NOT NULL DEFAULT '0.00',
  `isrRetenido` double(10,2) NOT NULL DEFAULT '0.00',
  `ivaPagadoNoAcreditable` double(10,2) NOT NULL DEFAULT '0.00',
  `aplica` tinyint(1) NOT NULL,
  `activo` int(1) NOT NULL,
  `periodoAcreditamiento` int(2) NOT NULL,
  `ejercicioAcreditamiento` int(2) NOT NULL,
  `idietu` int(11) DEFAULT '0',
  `acreditableIETU` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_relacion_ter_oper
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_relacion_ter_oper`;

CREATE TABLE `cont_relacion_ter_oper` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idtipotercero` int(11) unsigned NOT NULL,
  `idtipoperacion` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_relacion_ter_oper` WRITE;
/*!40000 ALTER TABLE `cont_relacion_ter_oper` DISABLE KEYS */;

INSERT INTO `cont_relacion_ter_oper` (`id`, `idtipotercero`, `idtipoperacion`)
VALUES
	(1,1,1),
	(2,1,2),
	(3,1,3),
	(4,2,4),
	(5,2,1),
	(6,2,3),
	(7,3,5),
	(8,3,6),
	(9,3,17),
	(10,3,7),
	(11,3,5),
	(12,3,10),
	(13,4,11),
	(14,4,12),
	(15,4,1),
	(16,4,2),
	(17,4,13),
	(18,4,3),
	(19,5,1),
	(20,5,2),
	(21,5,4),
	(22,5,3),
	(23,6,1),
	(24,6,4),
	(25,6,3),
	(26,7,14),
	(27,7,18),
	(28,7,16),
	(29,7,19),
	(30,7,20);

/*!40000 ALTER TABLE `cont_relacion_ter_oper` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_resumen_ivas_retenidos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_resumen_ivas_retenidos`;

CREATE TABLE `cont_resumen_ivas_retenidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mes` varchar(100) NOT NULL DEFAULT '',
  `cargo` double(100,2) NOT NULL DEFAULT '0.00',
  `favor` double(100,2) NOT NULL DEFAULT '0.00',
  `derivada_ajuste` double(100,2) NOT NULL DEFAULT '0.00',
  `cantidadreintegrarse` double(100,2) NOT NULL DEFAULT '0.00',
  `ivaretenido` double(100,2) NOT NULL DEFAULT '0.00',
  `idejercicio` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_segmentos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_segmentos`;

CREATE TABLE `cont_segmentos` (
  `idSuc` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `clave` varchar(10) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idSuc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_sucursales
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_sucursales`;

CREATE TABLE `cont_sucursales` (
  `idSuc` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) DEFAULT NULL,
  `idOrg` int(11) NOT NULL,
  PRIMARY KEY (`idSuc`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_sucursales` WRITE;
/*!40000 ALTER TABLE `cont_sucursales` DISABLE KEYS */;

INSERT INTO `cont_sucursales` (`idSuc`, `nombre`, `idOrg`)
VALUES
	(1,'Almacen',1),
	(2,'OFICINA',1);

/*!40000 ALTER TABLE `cont_sucursales` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_tasaPrv
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_tasaPrv`;

CREATE TABLE `cont_tasaPrv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPrv` int(11) NOT NULL,
  `tasa` varchar(255) NOT NULL,
  `valor` float NOT NULL,
  `visible` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_tasas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_tasas`;

CREATE TABLE `cont_tasas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tasa` varchar(100) NOT NULL DEFAULT '',
  `valor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_tasas` WRITE;
/*!40000 ALTER TABLE `cont_tasas` DISABLE KEYS */;

INSERT INTO `cont_tasas` (`id`, `tasa`, `valor`)
VALUES
	(1,'16%',16),
	(2,'11%',11),
	(3,'0%',0),
	(4,'Exenta',0),
	(5,'15%',15),
	(6,'10%',10);

/*!40000 ALTER TABLE `cont_tasas` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_tipo_cambio
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_tipo_cambio`;

CREATE TABLE `cont_tipo_cambio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `tipo` varchar(50) NOT NULL DEFAULT '',
  `tipo_cambio` double(100,4) DEFAULT NULL,
  `moneda` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cont_tipo_iva
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_tipo_iva`;

CREATE TABLE `cont_tipo_iva` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipoiva` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_tipo_iva` WRITE;
/*!40000 ALTER TABLE `cont_tipo_iva` DISABLE KEYS */;

INSERT INTO `cont_tipo_iva` (`id`, `tipoiva`)
VALUES
	(1,'Gastos para generar ingresos gravados para IVA'),
	(2,'Inversiones para generar ingresos gravados para IVA'),
	(3,'Otros egresos'),
	(4,'Ingresos nacionales'),
	(5,'Ingresos por exportaciones'),
	(6,'Gastos para generar ingresos NO identificados para IVA'),
	(7,'Gastos para generar ingresos exentos de IVA'),
	(8,'Inversiones para generar ingresos NO identificados para IVA'),
	(9,'Inversiones para generar ingresos exentos de IVA');

/*!40000 ALTER TABLE `cont_tipo_iva` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_tipo_operacion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_tipo_operacion`;

CREATE TABLE `cont_tipo_operacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipoOperacion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_tipo_operacion` WRITE;
/*!40000 ALTER TABLE `cont_tipo_operacion` DISABLE KEYS */;

INSERT INTO `cont_tipo_operacion` (`id`, `tipoOperacion`)
VALUES
	(1,'03- Prestación de Servicios Profesionales'),
	(2,'06- Arrendamiento de Inmuebles'),
	(3,'85- Otros'),
	(4,'Adquisicion de otro bienes intangibles'),
	(5,'Dividendos provenientes de CUFIN'),
	(6,'Dividendos ni provenientes de CUFIN'),
	(7,'Utilidades por reembolsos o reduccion de capital'),
	(8,'Proveedores nacionales'),
	(9,'Dividendos provenientes de CUFINRE'),
	(10,'Remanente distribuido por personas morales no lucrativas'),
	(11,'Premios'),
	(12,'Intereses'),
	(13,'IEPS retenido a contribuyentes'),
	(14,'No calcula IVA'),
	(15,'Salarios Gravados'),
	(16,'Gastos de seguridad social'),
	(17,'Utilidades por liquidacion de la persona moral'),
	(18,'Sueldos y salarios gravados'),
	(19,'No aplica para IETU'),
	(20,'Otros Acreditamientos para IETU');

/*!40000 ALTER TABLE `cont_tipo_operacion` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_tipo_tercero
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_tipo_tercero`;

CREATE TABLE `cont_tipo_tercero` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipotercero` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_tipo_tercero` WRITE;
/*!40000 ALTER TABLE `cont_tipo_tercero` DISABLE KEYS */;

INSERT INTO `cont_tipo_tercero` (`id`, `tipotercero`)
VALUES
	(1,'04-Proveedor Nacional'),
	(2,'05-Poveedor Extranjero'),
	(3,'Accionistas, Socios o Integrantes'),
	(4,'Retenciones (Excepto Proveedores)'),
	(5,'15- Proveedor Nacional Global'),
	(6,'15- Proveedor Extranjero Global'),
	(7,'Egresos Varios');

/*!40000 ALTER TABLE `cont_tipo_tercero` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_tipos_poliza
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cont_tipos_poliza`;

CREATE TABLE `cont_tipos_poliza` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `cont_tipos_poliza` WRITE;
/*!40000 ALTER TABLE `cont_tipos_poliza` DISABLE KEYS */;

INSERT INTO `cont_tipos_poliza` (`id`, `titulo`)
VALUES
	(1,'Ingresos'),
	(2,'Egresos'),
	(3,'Diario'),
	(4,'Orden');

/*!40000 ALTER TABLE `cont_tipos_poliza` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla cont_view_init_balance
# ------------------------------------------------------------

DROP VIEW IF EXISTS `cont_view_init_balance`;

CREATE TABLE `cont_view_init_balance` (
   `Clasificacion` VARCHAR(10) NULL DEFAULT NULL,
   `Cuenta_de_Mayor` VARCHAR(100) NULL DEFAULT NULL,
   `Code` VARCHAR(100) NULL DEFAULT NULL,
   `Naturaleza` VARCHAR(100) NOT NULL DEFAULT '',
   `Fecha` DATE NULL DEFAULT NULL,
   `Poliza` VARCHAR(50) NULL DEFAULT NULL,
   `Cuenta` VARCHAR(100) NOT NULL DEFAULT '',
   `Cargos` DOUBLE NOT NULL DEFAULT '0',
   `Abonos` DOUBLE NOT NULL DEFAULT '0',
   `Flag` VARCHAR(16) NOT NULL DEFAULT ''
) ENGINE=MyISAM;



# Volcado de tabla cont_view_init_balance2
# ------------------------------------------------------------

DROP VIEW IF EXISTS `cont_view_init_balance2`;

CREATE TABLE `cont_view_init_balance2` (
   `Clasificacion` VARCHAR(10) NULL DEFAULT NULL,
   `Clasificacion_Alt` VARCHAR(100) NULL DEFAULT NULL,
   `Cuenta_de_Mayor` VARCHAR(203) NULL DEFAULT NULL,
   `Cuenta_de_Mayor_Alt` VARCHAR(203) NULL DEFAULT NULL,
   `Code` VARCHAR(100) NULL DEFAULT NULL,
   `Naturaleza` VARCHAR(100) NOT NULL DEFAULT '',
   `Fecha` DATE NULL DEFAULT NULL,
   `Poliza` VARCHAR(50) NULL DEFAULT NULL,
   `Cuenta` VARCHAR(100) NOT NULL DEFAULT '',
   `Cuenta_Alt` VARCHAR(100) NOT NULL DEFAULT '',
   `Cargos` DOUBLE NOT NULL DEFAULT '0',
   `Abonos` DOUBLE NOT NULL DEFAULT '0',
   `Flag` VARCHAR(16) NOT NULL DEFAULT '',
   `idperiodo` INT(11) NULL DEFAULT NULL,
   `idsegmento` INT(2) NOT NULL,
   `idsucursal` INT(2) NOT NULL
) ENGINE=MyISAM;



# Volcado de tabla cont_view_libro_mayor
# ------------------------------------------------------------

DROP VIEW IF EXISTS `cont_view_libro_mayor`;

CREATE TABLE `cont_view_libro_mayor` (
   `Mov_Id` INT(11) NOT NULL DEFAULT '0',
   `Clasificacion` VARCHAR(10) NULL DEFAULT NULL,
   `Num_Clasif` INT(0) NULL DEFAULT NULL,
   `Cuenta_de_Mayor` VARCHAR(203) NULL DEFAULT NULL,
   `Code` VARCHAR(100) NULL DEFAULT NULL,
   `Manual_Code` VARCHAR(100) NULL DEFAULT NULL,
   `Naturaleza` VARCHAR(100) NOT NULL DEFAULT '',
   `Fecha` DATE NULL DEFAULT NULL,
   `Poliza` VARCHAR(50) NULL DEFAULT NULL,
   `Cuenta` VARCHAR(203) NULL DEFAULT NULL,
   `Cargos` DOUBLE NOT NULL DEFAULT '0',
   `Abonos` DOUBLE NOT NULL DEFAULT '0',
   `Flag` VARCHAR(16) NOT NULL DEFAULT '',
   `idperiodo` INT(11) NULL DEFAULT NULL,
   `Codigo_Cuenta_de_Mayor` VARCHAR(100) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Volcado de tabla corte_caja
# ------------------------------------------------------------

DROP TABLE IF EXISTS `corte_caja`;

CREATE TABLE `corte_caja` (
  `idCortecaja` int(11) NOT NULL AUTO_INCREMENT,
  `fechainicio` datetime DEFAULT NULL,
  `fechafin` datetime DEFAULT NULL,
  `retirocaja` float(10,2) DEFAULT NULL,
  `abonocaja` float(10,2) DEFAULT NULL,
  `saldoinicialcaja` float(10,2) DEFAULT NULL,
  `saldofinalcaja` float(10,2) DEFAULT NULL,
  `montoventa` float(10,2) DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCortecaja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cotpe_pedido
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cotpe_pedido`;

CREATE TABLE `cotpe_pedido` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idCliente` int(11) DEFAULT NULL,
  `total` decimal(20,6) DEFAULT NULL,
  `idempleado` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `idCotizacion` int(11) DEFAULT NULL,
  `observaciones` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `idVenta` int(11) DEFAULT NULL,
  `idMoneda` int(11) DEFAULT NULL,
  `tipo_cambio` double DEFAULT NULL,
  `impuestosJson` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descCant` double DEFAULT '0',
  `descuentoGeneral` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla cotpe_pedido_producto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cotpe_pedido_producto`;

CREATE TABLE `cotpe_pedido_producto` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idPedido` int(11) DEFAULT NULL,
  `idProducto` int(11) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  `idunidad` int(11) DEFAULT NULL,
  `precio` decimal(11,2) DEFAULT NULL,
  `importe` decimal(11,2) DEFAULT NULL,
  `caracteristicas` varchar(500) COLLATE utf8_unicode_ci DEFAULT '0',
  `descuentoCantidad` double DEFAULT '0',
  `tipoDes` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `descuento` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla cxc
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cxc`;

CREATE TABLE `cxc` (
  `idCxc` int(11) NOT NULL AUTO_INCREMENT,
  `fechacargo` date DEFAULT NULL,
  `fechavencimiento` date DEFAULT NULL,
  `idVenta` int(45) DEFAULT NULL,
  `monto` float(10,2) DEFAULT NULL,
  `saldoabonado` float(10,2) DEFAULT NULL,
  `saldoactual` float(10,2) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT '0',
  `idCliente` int(11) DEFAULT NULL,
  `concepto` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idCxc`),
  KEY `cxc_ibfk_1` (`idVenta`),
  KEY `cxc_ibfk_2` (`idCliente`),
  CONSTRAINT `cxc_ibfk_1` FOREIGN KEY (`idVenta`) REFERENCES `venta` (`idVenta`),
  CONSTRAINT `cxc_ibfk_2` FOREIGN KEY (`idCliente`) REFERENCES `comun_cliente` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cxc_pagos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cxc_pagos`;

CREATE TABLE `cxc_pagos` (
  `idCxcpagos` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `monto` float(10,2) DEFAULT NULL,
  `saldoinicial` float(10,2) DEFAULT NULL,
  `saldofinal` float(10,2) DEFAULT NULL,
  `idCxc` int(11) DEFAULT NULL,
  `idFormapago` int(11) DEFAULT NULL,
  `referencia` varchar(45) DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCxcpagos`),
  KEY `cxc_pagos_ibfk_1` (`idCxc`),
  KEY `cxc_pagos_ibfk_2` (`idFormapago`),
  CONSTRAINT `cxc_pagos_ibfk_1` FOREIGN KEY (`idCxc`) REFERENCES `cxc` (`idCxc`),
  CONSTRAINT `cxc_pagos_ibfk_2` FOREIGN KEY (`idFormapago`) REFERENCES `forma_pago` (`idFormapago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cxp
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cxp`;

CREATE TABLE `cxp` (
  `idCxp` int(11) NOT NULL AUTO_INCREMENT,
  `fechacargo` date DEFAULT NULL,
  `fechavencimiento` date DEFAULT NULL,
  `concepto` varchar(45) DEFAULT NULL,
  `monto` float(10,2) DEFAULT NULL,
  `saldoabonado` float(10,2) DEFAULT NULL,
  `saldoactual` float(10,2) DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT '0',
  `idProveedor` int(11) DEFAULT NULL,
  `idOrCom` int(11) DEFAULT '0',
  PRIMARY KEY (`idCxp`),
  KEY `cxp_ibfk_1` (`idProveedor`),
  CONSTRAINT `cxp_ibfk_1` FOREIGN KEY (`idProveedor`) REFERENCES `mrp_proveedor` (`idPrv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla cxp_pagos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cxp_pagos`;

CREATE TABLE `cxp_pagos` (
  `idCxppagos` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `monto` float(10,2) DEFAULT NULL,
  `saldoinicial` float(10,2) DEFAULT NULL,
  `saldofinal` float(10,2) DEFAULT NULL,
  `idCxp` int(11) DEFAULT NULL,
  `idFormapago` int(11) DEFAULT NULL,
  `referencia` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idCxppagos`),
  KEY `cxp_pagos_ibfk_1` (`idCxp`),
  KEY `cxp_pagos_ibfk_2` (`idFormapago`),
  CONSTRAINT `cxp_pagos_ibfk_1` FOREIGN KEY (`idCxp`) REFERENCES `cxp` (`idCxp`),
  CONSTRAINT `cxp_pagos_ibfk_2` FOREIGN KEY (`idFormapago`) REFERENCES `forma_pago` (`idFormapago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla dashboard_comunica
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dashboard_comunica`;

CREATE TABLE `dashboard_comunica` (
  `idms` int(11) NOT NULL AUTO_INCREMENT,
  `instancia` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idempleado` int(11) DEFAULT NULL,
  `msg` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechainicio` datetime DEFAULT NULL,
  `fechafin` datetime DEFAULT NULL,
  PRIMARY KEY (`idms`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla dashboard_contenido
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dashboard_contenido`;

CREATE TABLE `dashboard_contenido` (
  `iddc` int(11) NOT NULL AUTO_INCREMENT,
  `idtipo` int(11) DEFAULT NULL,
  `idmenu` int(11) DEFAULT NULL,
  `idempleado` int(11) DEFAULT NULL,
  PRIMARY KEY (`iddc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla datos_bascula
# ------------------------------------------------------------

DROP TABLE IF EXISTS `datos_bascula`;

CREATE TABLE `datos_bascula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `puerto` varchar(5) CHARACTER SET utf8 NOT NULL,
  `baudios` int(11) NOT NULL,
  `paridad` varchar(5) CHARACTER SET utf8 NOT NULL,
  `bstop` int(11) NOT NULL,
  `bits` int(11) NOT NULL,
  `bascula` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `datos_bascula` WRITE;
/*!40000 ALTER TABLE `datos_bascula` DISABLE KEYS */;

INSERT INTO `datos_bascula` (`id`, `puerto`, `baudios`, `paridad`, `bstop`, `bits`, `bascula`)
VALUES
	(1,'COM3',9600,'0',0,8,0);

/*!40000 ALTER TABLE `datos_bascula` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla doclog_dependenciasfiltros_detalles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `doclog_dependenciasfiltros_detalles`;

CREATE TABLE `doclog_dependenciasfiltros_detalles` (
  `idcampo` int(11) NOT NULL,
  `nombrecampotitulo` varchar(50) NOT NULL,
  PRIMARY KEY (`idcampo`,`nombrecampotitulo`),
  KEY `dfd_dependencias` (`idcampo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla doclog_detalles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `doclog_detalles`;

CREATE TABLE `doclog_detalles` (
  `iddocumento` int(11) NOT NULL DEFAULT '0',
  `idestructuradetalle` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iddocumento`,`idestructuradetalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `doclog_detalles` WRITE;
/*!40000 ALTER TABLE `doclog_detalles` DISABLE KEYS */;

INSERT INTO `doclog_detalles` (`iddocumento`, `idestructuradetalle`)
VALUES
	(0,121),
	(1,24),
	(12,121),
	(13,44),
	(14,63),
	(15,60),
	(16,70),
	(17,74),
	(18,112),
	(32,170),
	(33,172);

/*!40000 ALTER TABLE `doclog_detalles` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla doclog_titulos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `doclog_titulos`;

CREATE TABLE `doclog_titulos` (
  `iddocumento` int(11) NOT NULL AUTO_INCREMENT,
  `nombredocumento` varchar(100) DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `fechacreacion` datetime DEFAULT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `estatus` char(1) DEFAULT NULL,
  `utilizaidorganizacion` tinyint(4) DEFAULT NULL,
  `linkantes` varchar(500) DEFAULT NULL,
  `linkdespues` varchar(500) DEFAULT NULL,
  `idestructuratitulo` int(11) DEFAULT NULL,
  `columnas` int(11) DEFAULT NULL,
  PRIMARY KEY (`iddocumento`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

LOCK TABLES `doclog_titulos` WRITE;
/*!40000 ALTER TABLE `doclog_titulos` DISABLE KEYS */;

INSERT INTO `doclog_titulos` (`iddocumento`, `nombredocumento`, `observaciones`, `fechacreacion`, `fechamodificacion`, `estatus`, `utilizaidorganizacion`, `linkantes`, `linkdespues`, `idestructuratitulo`, `columnas`)
VALUES
	(1,'Ventas','Ventas','2012-11-23 22:51:50','2012-11-23 22:51:50','G',0,'','',23,0),
	(12,'ParcialLog','Registro de permisos especiales sobre campos','2012-02-09 15:23:29','2012-02-09 15:23:29','G',0,'','',120,0),
	(13,'Movimientos Inventario','Movimientos Inventarios Directos','2012-12-16 15:41:10','2012-12-16 15:41:10','G',0,'','',43,0),
	(14,'Campañas','Campañas','2013-05-06 12:02:43','2013-10-26 16:13:50','G',0,'','',49,0),
	(15,'Compras','Compras','2013-05-09 09:48:40','2013-05-09 09:48:40','G',0,'','',59,0),
	(16,'Cuentas Por Cobrar','Cuentas Por Cobrar','2013-05-15 22:17:10','2013-08-09 17:31:47','G',0,'../../modulos/cxc/pagos.php','',64,0),
	(17,'Cuentas por Pagar','Cuentas Por Pagar','2013-05-15 22:37:38','2013-05-16 12:22:21','G',0,'','',65,0),
	(18,'Facturacion (PVT)','Facturacion (PVT)','2013-10-22 15:32:54','2013-10-22 16:33:11','A',0,'','',111,0),
	(19,'agendaLog_Citas','AgendaLog Citas','2013-10-25 18:17:51','2013-10-25 18:18:01','A',0,'','',115,0),
	(32,'Cuentas por Cobrar Rest','Cuentas por Cobrar Rest','2013-11-21 16:13:08','2013-11-21 22:12:38','A',0,'','',169,0),
	(33,'Cuentas por Pagar Rest','Cuentas por Pagar Rest','2013-11-21 16:13:43','2013-11-21 22:12:47','A',0,'','',171,0);

/*!40000 ALTER TABLE `doclog_titulos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla empleados
# ------------------------------------------------------------

DROP TABLE IF EXISTS `empleados`;

CREATE TABLE `empleados` (
  `idempleado` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `apellido1` varchar(45) NOT NULL,
  `apellido2` varchar(45) NOT NULL,
  `idorganizacion` varchar(45) NOT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `administrador` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idempleado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;

INSERT INTO `empleados` (`idempleado`, `nombre`, `apellido1`, `apellido2`, `idorganizacion`, `visible`, `administrador`)
VALUES
	(1,'Soporte','-','-','1',0,-1);

/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla estados
# ------------------------------------------------------------

DROP TABLE IF EXISTS `estados`;

CREATE TABLE `estados` (
  `idestado` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) DEFAULT NULL,
  `idpais` int(11) DEFAULT NULL,
  `clave` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`idestado`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;

LOCK TABLES `estados` WRITE;
/*!40000 ALTER TABLE `estados` DISABLE KEYS */;

INSERT INTO `estados` (`idestado`, `estado`, `idpais`, `clave`)
VALUES
	(1,'Aguascalientes',1,'AGU'),
	(2,'Baja California',1,'BCN'),
	(3,'Baja California Sur',1,'BCS'),
	(4,'Campeche',1,'CAM'),
	(5,'Coahuila de Zaragoza',1,'COA'),
	(6,'Colima',1,'COL'),
	(7,'Chiapas',1,'CHP'),
	(8,'Chihuahua',1,'CHH'),
	(9,'Ciudad de Mexico',1,'DIF'),
	(10,'Durango',1,'DUR'),
	(11,'Guanajuato',1,'GUA'),
	(12,'Guerrero',1,'GRO'),
	(13,'Hidalgo',1,'HID'),
	(14,'Jalisco',1,'JAL'),
	(15,'Estado de MÃ©xico',1,'MEX'),
	(16,'MichoacÃ¡n de Ocampo',1,'MIC'),
	(17,'Morelos',1,'MOR'),
	(18,'Nayarit',1,'NAY'),
	(19,'Nuevo LeÃ³n',1,'NLE'),
	(20,'Oaxaca',1,'OAX'),
	(21,'Puebla',1,'PUE'),
	(22,'QuerÃ©taro',1,'QUE'),
	(23,'Quintana Roo',1,'ROO'),
	(24,'San Luis PotosÃ­',1,'SLP'),
	(25,'Sinaloa',1,'SIN'),
	(26,'Sonora',1,'SON'),
	(27,'Tabasco',1,'TAB'),
	(28,'Tamaulipas',1,'TAM'),
	(29,'Tlaxcala',1,'TLA'),
	(30,'Veracruz de Ignacio de la Llave',1,'VER'),
	(31,'YucatÃ¡n',1,'YUC'),
	(32,'Zacatecas',1,'ZAC'),
	(33,'California',2,'CA'),
	(34,'Alabama',2,'AL'),
	(35,'Alaska',2,'AK'),
	(36,'Arizona',2,'AZ'),
	(37,'Arkansas',2,'AR'),
	(38,'Carolina del Norte',2,'NC'),
	(39,'Carolina del Sur',2,'SC'),
	(40,'Colorado',2,'CO'),
	(41,'Connecticut',2,'CT'),
	(42,'Dakota del Norte',2,'ND'),
	(43,'Dakota del Sur',2,'SD'),
	(44,'Delaware',2,'DE'),
	(45,'Florida',2,'FL'),
	(46,'Georgia',2,'GA'),
	(47,'HawÃ¡i',2,'HI'),
	(48,'Idaho',2,'ID'),
	(49,'Illinois',2,'IL'),
	(50,'Indiana',2,'IN'),
	(51,'Iowa',2,'IA'),
	(52,'Kansas',2,'KS'),
	(53,'Kentucky',2,'KY'),
	(54,'Luisiana',2,'LA'),
	(55,'Maine',2,'ME'),
	(56,'Maryland',2,'MD'),
	(57,'Massachusetts',2,'MA'),
	(58,'MÃ­chigan',2,'MI'),
	(59,'Minnesota',2,'MN'),
	(60,'Misisipi',2,'MS'),
	(61,'Misuri',2,'MO'),
	(62,'Montana',2,'MT'),
	(63,'Nebraska',2,'NE'),
	(64,'Nevada',2,'NV'),
	(65,'Nueva Jersey',2,'NJ'),
	(66,'Nueva York',2,'NY'),
	(67,'Nuevo Hampshire',2,'NH'),
	(68,'Nuevo MÃ©xico',2,'NM'),
	(69,'Ohio',2,'OH'),
	(70,'Oklahoma',2,'OK'),
	(71,'OregÃ³n',2,'OR'),
	(72,'Pensilvania',2,'PA'),
	(73,'Rhode Island',2,'RI'),
	(74,'Tennessee',2,'TN'),
	(75,'Texas',2,'TX'),
	(76,'Utah',2,'UT'),
	(77,'Vermont',2,'VT'),
	(78,'Virginia',2,'VA'),
	(79,'Virginia Occidental',2,'WV'),
	(80,'Washington',2,'WA'),
	(81,'Wisconsin',2,'WI'),
	(82,'Wyoming',2,'WY'),
	(83,'Otro',0,'OTR');

/*!40000 ALTER TABLE `estados` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla forma_pago
# ------------------------------------------------------------

DROP TABLE IF EXISTS `forma_pago`;

CREATE TABLE `forma_pago` (
  `idFormapago` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `claveSat` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`idFormapago`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

LOCK TABLES `forma_pago` WRITE;
/*!40000 ALTER TABLE `forma_pago` DISABLE KEYS */;

INSERT INTO `forma_pago` (`idFormapago`, `nombre`, `claveSat`)
VALUES
	(1,'Efectivo','01'),
	(2,'Cheque','02'),
	(3,'Tarjeta de regalo','07'),
	(4,'Tarjeta de crédito','04'),
	(5,'Tarjeta de debito','28'),
	(6,'Crédito','99'),
	(7,'Transferencia','03'),
	(8,'Spei',NULL),
	(9,'-No Identificado-','98'),
	(10,'Monedero Electronico','05'),
	(11,'Dinero electrónico','06'),
	(12,'Vales de despensa','08'),
	(13,'Bienes','09'),
	(14,'Servicio','10'),
	(15,'Por cuenta de tercero','11'),
	(16,'Dación en pago','12'),
	(17,'Pago por subrogación','13'),
	(18,'Pago por consignación','14'),
	(19,'Condonación','15'),
	(20,'Cancelación','16'),
	(21,'Otros','99'),
	(22,'Compensación','17'),
	(24,'NA','NA');

/*!40000 ALTER TABLE `forma_pago` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla forma_pago_caja
# ------------------------------------------------------------

DROP TABLE IF EXISTS `forma_pago_caja`;

CREATE TABLE `forma_pago_caja` (
  `idFormapago` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) CHARACTER SET latin1 NOT NULL,
  `claveSat` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`idFormapago`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

LOCK TABLES `forma_pago_caja` WRITE;
/*!40000 ALTER TABLE `forma_pago_caja` DISABLE KEYS */;

INSERT INTO `forma_pago_caja` (`idFormapago`, `nombre`, `claveSat`)
VALUES
	(1,'Efectivo','01'),
	(2,'Cheque Nominativo','02'),
	(3,'Transferencia electronica de fondos','03'),
	(4,'Tarjeta de Credito','04'),
	(5,'Monedero Electronico','05'),
	(6,'Credito','99'),
	(7,'Vales de despensa','08'),
	(8,'Tarjeta de Debito','28'),
	(9,'Tarjeta de Servicio','29'),
	(10,'Otros','99'),
	(11,'Dinero Electronico','06'),
	(12,'NA','98');

/*!40000 ALTER TABLE `forma_pago_caja` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla impuesto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `impuesto`;

CREATE TABLE `impuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `valor` double NOT NULL,
  `retenido` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

LOCK TABLES `impuesto` WRITE;
/*!40000 ALTER TABLE `impuesto` DISABLE KEYS */;

INSERT INTO `impuesto` (`id`, `nombre`, `valor`, `retenido`)
VALUES
	(1,'IVA',16,0),
	(10,'ISR',10,1),
	(17,'IVAR',10.6666775,1);

/*!40000 ALTER TABLE `impuesto` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla ingreso_mercancia
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ingreso_mercancia`;

CREATE TABLE `ingreso_mercancia` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `idProducto` int(11) DEFAULT NULL,
  `idProveedor` int(11) DEFAULT NULL,
  `cantidad` float(10,2) DEFAULT NULL,
  `idSuc` int(11) DEFAULT NULL,
  `costo` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idProducto` (`idProducto`),
  KEY `idSuc` (`idSuc`),
  KEY `idProveedor` (`idProveedor`),
  CONSTRAINT `ingreso_mercancia_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `mrp_producto` (`idProducto`),
  CONSTRAINT `ingreso_mercancia_ibfk_2` FOREIGN KEY (`idSuc`) REFERENCES `mrp_sucursal` (`idSuc`),
  CONSTRAINT `ingreso_mercancia_ibfk_3` FOREIGN KEY (`idProveedor`) REFERENCES `mrp_proveedor` (`idPrv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla inicio_caja
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inicio_caja`;

CREATE TABLE `inicio_caja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `monto` float(10,2) DEFAULT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `idCortecaja` int(11) DEFAULT NULL,
  `idSucursal` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inicio_caja_ibfk_1` (`idCortecaja`),
  KEY `inicio_caja_ibfk_2` (`idSucursal`),
  KEY `inicio_caja_ibfk_3` (`idUsuario`),
  CONSTRAINT `inicio_caja_ibfk_1` FOREIGN KEY (`idCortecaja`) REFERENCES `corte_caja` (`idCortecaja`),
  CONSTRAINT `inicio_caja_ibfk_2` FOREIGN KEY (`idSucursal`) REFERENCES `mrp_sucursal` (`idSuc`),
  CONSTRAINT `inicio_caja_ibfk_3` FOREIGN KEY (`idUsuario`) REFERENCES `empleados` (`idempleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla meses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `meses`;

CREATE TABLE `meses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mes` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

LOCK TABLES `meses` WRITE;
/*!40000 ALTER TABLE `meses` DISABLE KEYS */;

INSERT INTO `meses` (`id`, `mes`)
VALUES
	(1,'Enero'),
	(2,'Febrero'),
	(3,'Marzo'),
	(4,'Abril'),
	(5,'Mayo'),
	(6,'Junio'),
	(7,'Julio'),
	(8,'Agosto'),
	(9,'Septiembre'),
	(10,'Octubre'),
	(11,'Noviembre'),
	(12,'Diciembre');

/*!40000 ALTER TABLE `meses` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla movimientos_mercancia
# ------------------------------------------------------------

DROP TABLE IF EXISTS `movimientos_mercancia`;

CREATE TABLE `movimientos_mercancia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAlmacenOrigen` int(11) NOT NULL,
  `cantidadtotalOrigen` float(10,2) DEFAULT NULL,
  `cantidadmovimiento` float(10,2) NOT NULL,
  `idUnidad` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idAlmacenDestino` int(11) NOT NULL,
  `cantidadtotalDestino` float(10,2) DEFAULT NULL,
  `fechamovimiento` datetime DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `idEmpleadoRec` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_mercancia_ibfk_1` (`idAlmacenOrigen`),
  KEY `movimientos_mercancia_ibfk_2` (`idUnidad`),
  KEY `movimientos_mercancia_ibfk_3` (`idProducto`),
  KEY `movimientos_mercancia_ibfk_4` (`idAlmacenDestino`),
  CONSTRAINT `movimientos_mercancia_ibfk_1` FOREIGN KEY (`idAlmacenOrigen`) REFERENCES `almacen` (`idAlmacen`),
  CONSTRAINT `movimientos_mercancia_ibfk_2` FOREIGN KEY (`idUnidad`) REFERENCES `mrp_unidades` (`idUni`),
  CONSTRAINT `movimientos_mercancia_ibfk_3` FOREIGN KEY (`idProducto`) REFERENCES `mrp_producto` (`idProducto`),
  CONSTRAINT `movimientos_mercancia_ibfk_4` FOREIGN KEY (`idAlmacenDestino`) REFERENCES `almacen` (`idAlmacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla mrp_color
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_color`;

CREATE TABLE `mrp_color` (
  `idCol` int(11) NOT NULL AUTO_INCREMENT,
  `color` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`idCol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla mrp_compra
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_compra`;

CREATE TABLE `mrp_compra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` varchar(15) CHARACTER SET latin1 NOT NULL,
  `archivo` varchar(100) CHARACTER SET latin1 NOT NULL,
  `idOrden` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fact` varchar(100) DEFAULT NULL,
  `xml` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idOrden` (`idOrden`) USING BTREE,
  CONSTRAINT `mrp_compra_ibfk_1` FOREIGN KEY (`idOrden`) REFERENCES `mrp_orden_compra` (`idOrd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 57344 kB; (`idOrden`) REFER `_dbmlog0000000000/';



# Volcado de tabla mrp_departamento
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_departamento`;

CREATE TABLE `mrp_departamento` (
  `idDep` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`idDep`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `mrp_departamento` WRITE;
/*!40000 ALTER TABLE `mrp_departamento` DISABLE KEYS */;

INSERT INTO `mrp_departamento` (`idDep`, `nombre`)
VALUES
	(1,'Departamento');

/*!40000 ALTER TABLE `mrp_departamento` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla mrp_detalle_compra
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_detalle_compra`;

CREATE TABLE `mrp_detalle_compra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCompra` int(11) NOT NULL,
  `idProductoOrdenCompra` int(11) NOT NULL,
  `cantidad` float(10,2) NOT NULL,
  `costo` float(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idCompra` (`idCompra`) USING BTREE,
  KEY `idProductoOrdenCompra` (`idProductoOrdenCompra`) USING BTREE,
  CONSTRAINT `mrp_detalle_compra_ibfk_1` FOREIGN KEY (`idCompra`) REFERENCES `mrp_compra` (`id`),
  CONSTRAINT `mrp_detalle_compra_ibfk_2` FOREIGN KEY (`idProductoOrdenCompra`) REFERENCES `mrp_producto_orden_compra` (`idPrOr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 57344 kB; (`idCompra`) REFER `_dbmlog0000000000';



# Volcado de tabla mrp_detalle_orden_produccion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_detalle_orden_produccion`;

CREATE TABLE `mrp_detalle_orden_produccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProducto` int(11) NOT NULL,
  `idUnidad` int(11) NOT NULL,
  `idOrdPro` int(11) NOT NULL,
  `cantidad` float(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idProducto` (`idProducto`) USING BTREE,
  KEY `idUnidad` (`idUnidad`) USING BTREE,
  KEY `idOrdPro` (`idOrdPro`) USING BTREE,
  CONSTRAINT `mrp_detalle_orden_produccion_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `mrp_producto` (`idProducto`),
  CONSTRAINT `mrp_detalle_orden_produccion_ibfk_2` FOREIGN KEY (`idUnidad`) REFERENCES `mrp_unidades` (`idUni`),
  CONSTRAINT `mrp_detalle_orden_produccion_ibfk_3` FOREIGN KEY (`idOrdPro`) REFERENCES `mrp_orden_produccion` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 57344 kB; (`idProducto`) REFER `_dbmlog00000000';



# Volcado de tabla mrp_devoluciones_reporte
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_devoluciones_reporte`;

CREATE TABLE `mrp_devoluciones_reporte` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nDevoluciones` double NOT NULL DEFAULT '0',
  `idProducto` int(11) DEFAULT NULL,
  `idProveedor` int(11) DEFAULT '0',
  `idAlmacen` int(11) DEFAULT '0',
  `fechaDevolucion` datetime DEFAULT '0000-00-00 00:00:00',
  `estatus` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla mrp_etapas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_etapas`;

CREATE TABLE `mrp_etapas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `etapa` varchar(45) DEFAULT NULL,
  `duracion_eta` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla mrp_familia
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_familia`;

CREATE TABLE `mrp_familia` (
  `idFam` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idDep` int(11) DEFAULT NULL,
  PRIMARY KEY (`idFam`),
  KEY `idDep` (`idDep`),
  CONSTRAINT `mrp_familia_ibfk_1` FOREIGN KEY (`idDep`) REFERENCES `mrp_departamento` (`idDep`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `mrp_familia` WRITE;
/*!40000 ALTER TABLE `mrp_familia` DISABLE KEYS */;

INSERT INTO `mrp_familia` (`idFam`, `nombre`, `idDep`)
VALUES
	(1,'Familia',1);

/*!40000 ALTER TABLE `mrp_familia` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla mrp_linea
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_linea`;

CREATE TABLE `mrp_linea` (
  `idLin` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `idFam` int(11) DEFAULT NULL,
  PRIMARY KEY (`idLin`),
  KEY `idFam` (`idFam`),
  CONSTRAINT `mrp_linea_ibfk_1` FOREIGN KEY (`idFam`) REFERENCES `mrp_familia` (`idFam`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `mrp_linea` WRITE;
/*!40000 ALTER TABLE `mrp_linea` DISABLE KEYS */;

INSERT INTO `mrp_linea` (`idLin`, `nombre`, `idFam`)
VALUES
	(1,'Linea',1);

/*!40000 ALTER TABLE `mrp_linea` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla mrp_lista_precios
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_lista_precios`;

CREATE TABLE `mrp_lista_precios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idProducto` int(11) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `precio` float(10,2) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla mrp_movimientos_inv
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_movimientos_inv`;

CREATE TABLE `mrp_movimientos_inv` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` int(11) NOT NULL,
  `movimiento` varchar(30) NOT NULL DEFAULT '',
  `tipo` varchar(20) NOT NULL DEFAULT '',
  `cantidad` float NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idAlmacen` int(11) NOT NULL,
  `comentario` varchar(50) NOT NULL DEFAULT '',
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla mrp_orden_compra
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_orden_compra`;

CREATE TABLE `mrp_orden_compra` (
  `idOrd` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_pedido` datetime DEFAULT NULL,
  `elaborado_por` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `idSuc` int(11) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `autorizado_por` varchar(100) CHARACTER SET latin1 NOT NULL,
  `idProveedor` int(11) NOT NULL,
  `estatus` varchar(30) CHARACTER SET latin1 NOT NULL DEFAULT 'Registrada',
  `idOrdPro` int(11) DEFAULT NULL,
  `idAlmacen` int(11) DEFAULT NULL,
  `comentario` varchar(100) DEFAULT NULL,
  `enviada` int(11) DEFAULT '0',
  PRIMARY KEY (`idOrd`),
  KEY `idSuc` (`idSuc`) USING BTREE,
  KEY `idProveedor` (`idProveedor`) USING BTREE,
  KEY `idOrdPro` (`idOrdPro`) USING BTREE,
  KEY `mrp_orden_compra_ibfk_4` (`idAlmacen`),
  CONSTRAINT `mrp_orden_compra_ibfk_1` FOREIGN KEY (`idSuc`) REFERENCES `mrp_sucursal` (`idSuc`),
  CONSTRAINT `mrp_orden_compra_ibfk_2` FOREIGN KEY (`idProveedor`) REFERENCES `mrp_proveedor` (`idPrv`),
  CONSTRAINT `mrp_orden_compra_ibfk_3` FOREIGN KEY (`idOrdPro`) REFERENCES `mrp_orden_produccion` (`id`),
  CONSTRAINT `mrp_orden_compra_ibfk_4` FOREIGN KEY (`idAlmacen`) REFERENCES `almacen` (`idAlmacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 57344 kB; (`idSuc`) REFER `_dbmlog0000000000/mr';



# Volcado de tabla mrp_orden_produccion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_orden_produccion`;

CREATE TABLE `mrp_orden_produccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `generada_por` varchar(100) CHARACTER SET latin1 NOT NULL,
  `idSuc` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT '0',
  `idAlmacen` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idSuc` (`idSuc`) USING BTREE,
  KEY `mrp_orden_produccion_ibfk_2` (`idAlmacen`),
  CONSTRAINT `mrp_orden_produccion_ibfk_1` FOREIGN KEY (`idSuc`) REFERENCES `mrp_sucursal` (`idSuc`),
  CONSTRAINT `mrp_orden_produccion_ibfk_2` FOREIGN KEY (`idAlmacen`) REFERENCES `almacen` (`idAlmacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 57344 kB; (`idSuc`) REFER `_dbmlog0000000000/mr';



# Volcado de tabla mrp_procesos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_procesos`;

CREATE TABLE `mrp_procesos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_etapa` int(11) NOT NULL,
  `proceso` varchar(160) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `duracion` varchar(45) DEFAULT NULL,
  `orden` int(11) DEFAULT '1',
  `duracion_pro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla mrp_producto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_producto`;

CREATE TABLE `mrp_producto` (
  `idProducto` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `deslarga` text,
  `descorta` text,
  `descenefa` text,
  `color` int(11) DEFAULT NULL,
  `talla` int(11) DEFAULT NULL,
  `vendible` tinyint(4) NOT NULL,
  `consumo` tinyint(4) NOT NULL,
  `idLinea` int(11) NOT NULL,
  `maximo` float(10,2) NOT NULL,
  `minimo` float(10,2) NOT NULL,
  `imagen` varchar(100) CHARACTER SET latin1 NOT NULL,
  `barcode` varchar(15) CHARACTER SET latin1 NOT NULL,
  `esreceta` tinyint(1) DEFAULT '0',
  `precioventa` decimal(40,30) DEFAULT NULL,
  `preciomayoreo` float(10,2) DEFAULT '0.00',
  `precioliquidacion` float(10,2) DEFAULT '0.00',
  `idProveedor` int(11) DEFAULT '0',
  `costo` float(10,2) DEFAULT '0.00',
  `stock_inicial` float(10,2) DEFAULT NULL,
  `eskit` tinyint(1) DEFAULT '0',
  `idunidad` int(11) NOT NULL,
  `tipo_producto` int(11) NOT NULL DEFAULT '1',
  `idunidadCompra` int(11) NOT NULL DEFAULT '1',
  `estatus` int(11) DEFAULT '1',
  `costo_produccion` decimal(20,6) DEFAULT NULL,
  `margen_ganancia` decimal(20,6) DEFAULT NULL,
  `descu` int(11) DEFAULT '0',
  PRIMARY KEY (`idProducto`),
  UNIQUE KEY `nombre` (`nombre`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `idLinea` (`idLinea`),
  KEY `mrp_producto_ibfk_2` (`color`),
  KEY `mrp_producto_ibfk_3` (`talla`),
  CONSTRAINT `mrp_producto_ibfk_1` FOREIGN KEY (`idLinea`) REFERENCES `mrp_linea` (`idLin`),
  CONSTRAINT `mrp_producto_ibfk_2` FOREIGN KEY (`color`) REFERENCES `mrp_color` (`idCol`),
  CONSTRAINT `mrp_producto_ibfk_3` FOREIGN KEY (`talla`) REFERENCES `mrp_talla` (`idTal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla mrp_producto_material
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_producto_material`;

CREATE TABLE `mrp_producto_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProducto` int(11) NOT NULL,
  `cantidad` decimal(10,4) NOT NULL,
  `idUnidad` int(11) NOT NULL,
  `idMaterial` int(11) NOT NULL,
  `opcional` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idProducto` (`idProducto`) USING BTREE,
  KEY `idMaterial` (`idMaterial`) USING BTREE,
  KEY `mrp_producto_material_ibfk_3` (`idUnidad`) USING BTREE,
  CONSTRAINT `mrp_producto_material_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `mrp_producto` (`idProducto`),
  CONSTRAINT `mrp_producto_material_ibfk_2` FOREIGN KEY (`idMaterial`) REFERENCES `mrp_producto` (`idProducto`),
  CONSTRAINT `mrp_producto_material_ibfk_3` FOREIGN KEY (`idUnidad`) REFERENCES `mrp_unidades` (`idUni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 57344 kB; (`idProducto`) REFER `_dbmlog00000000';



# Volcado de tabla mrp_producto_orden_compra
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_producto_orden_compra`;

CREATE TABLE `mrp_producto_orden_compra` (
  `idPrOr` int(11) NOT NULL AUTO_INCREMENT,
  `idOrden` int(11) DEFAULT NULL,
  `cantidad` float(10,2) DEFAULT NULL,
  `idUnidad` int(11) DEFAULT NULL,
  `idProducto` int(11) DEFAULT NULL,
  `ultCosto` float(10,2) NOT NULL,
  PRIMARY KEY (`idPrOr`),
  KEY `idProducto` (`idProducto`) USING BTREE,
  KEY `idOrden` (`idOrden`) USING BTREE,
  KEY `mrp_producto_orden_compra_ibfk_3` (`idUnidad`) USING BTREE,
  CONSTRAINT `mrp_producto_orden_compra_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `mrp_producto` (`idProducto`),
  CONSTRAINT `mrp_producto_orden_compra_ibfk_2` FOREIGN KEY (`idOrden`) REFERENCES `mrp_orden_compra` (`idOrd`),
  CONSTRAINT `mrp_producto_orden_compra_ibfk_3` FOREIGN KEY (`idUnidad`) REFERENCES `mrp_unidades` (`idUni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 57344 kB; (`idProducto`) REFER `_dbmlog00000000';



# Volcado de tabla mrp_producto_proveedor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_producto_proveedor`;

CREATE TABLE `mrp_producto_proveedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProducto` int(11) NOT NULL,
  `idPrv` int(11) NOT NULL,
  `costo` double DEFAULT NULL,
  `idUni` int(50) DEFAULT NULL,
  `utilidad` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mrp_producto_proveedor_ibfk_3` (`idUni`),
  KEY `mrp_producto_proveedor_ibfk_1` (`idProducto`),
  KEY `mrp_producto_proveedor_ibfk_2` (`idPrv`),
  CONSTRAINT `mrp_producto_proveedor_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `mrp_producto` (`idProducto`),
  CONSTRAINT `mrp_producto_proveedor_ibfk_2` FOREIGN KEY (`idPrv`) REFERENCES `mrp_proveedor` (`idPrv`),
  CONSTRAINT `mrp_producto_proveedor_ibfk_3` FOREIGN KEY (`idUni`) REFERENCES `mrp_unidades` (`idUni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla mrp_proveedor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_proveedor`;

CREATE TABLE `mrp_proveedor` (
  `idPrv` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) DEFAULT NULL,
  `razon_social` varchar(255) NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `domicilio` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `web` varchar(255) DEFAULT NULL,
  `diascredito` int(11) DEFAULT '1',
  `idpais` int(10) DEFAULT '1',
  `idestado` varchar(10) NOT NULL,
  `idmunicipio` varchar(10) NOT NULL,
  `legal` tinyint(1) DEFAULT NULL,
  `precioycalidad` tinyint(1) DEFAULT NULL,
  `disponibilidad` tinyint(1) DEFAULT NULL,
  `idtipotercero` tinyint(1) NOT NULL,
  `idtipoperacion` tinyint(1) NOT NULL,
  `curp` varchar(100) DEFAULT NULL,
  `cuenta` int(11) DEFAULT NULL,
  `numidfiscal` varchar(100) DEFAULT NULL,
  `nombrextranjero` varchar(255) DEFAULT NULL,
  `PaisdeResidencia` int(11) DEFAULT NULL,
  `nacionalidad` varchar(100) DEFAULT NULL,
  `ivaretenido` double(100,9) DEFAULT NULL,
  `isretenido` double(100,9) DEFAULT NULL,
  `idTasaPrvasumir` int(11) NOT NULL,
  `idtipoiva` int(11) DEFAULT NULL,
  `idIETU` int(11) DEFAULT '0',
  `ImOtSis` int(8) DEFAULT '0',
  `idtipo` int(11) NOT NULL DEFAULT '1',
  `beneficiario_pagador` tinyint(1) NOT NULL DEFAULT '0',
  `cuentacliente` int(5) NOT NULL DEFAULT '0',
  `nombre` varchar(45) DEFAULT NULL,
  `nombre_comercial` varchar(45) DEFAULT NULL,
  `moneda` int(11) DEFAULT NULL,
  `clasificacion` varchar(45) DEFAULT NULL,
  `limite_credito` double(100,9) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `calle` varchar(45) DEFAULT NULL,
  `no_ext` int(11) DEFAULT NULL,
  `no_int` int(11) DEFAULT NULL,
  `id_colonia` int(11) DEFAULT NULL,
  `cp` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPrv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla mrp_stock
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_stock`;

CREATE TABLE `mrp_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProducto` int(11) NOT NULL,
  `cantidad` float(10,2) NOT NULL,
  `idAlmacen` int(11) NOT NULL,
  `idUnidad` int(11) DEFAULT NULL,
  `ocupados` float(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idProducto` (`idProducto`) USING BTREE,
  KEY `mrp_stock_ibfk_2` (`idAlmacen`),
  KEY `mrp_stock_ibfk_3` (`idUnidad`),
  CONSTRAINT `mrp_stock_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `mrp_producto` (`idProducto`),
  CONSTRAINT `mrp_stock_ibfk_2` FOREIGN KEY (`idAlmacen`) REFERENCES `almacen` (`idAlmacen`),
  CONSTRAINT `mrp_stock_ibfk_3` FOREIGN KEY (`idUnidad`) REFERENCES `mrp_unidades` (`idUni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 57344 kB; (`idProducto`) REFER `_dbmlog00000000';



# Volcado de tabla mrp_sucursal
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_sucursal`;

CREATE TABLE `mrp_sucursal` (
  `idSuc` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `idEstado` int(11) NOT NULL DEFAULT '1',
  `idMunicipio` int(11) NOT NULL DEFAULT '1',
  `cp` int(11) DEFAULT NULL,
  `tel_contacto` varchar(15) DEFAULT NULL,
  `contacto` varchar(255) DEFAULT NULL,
  `idorganizacion` int(11) DEFAULT NULL,
  `idAlmacen` int(11) DEFAULT NULL,
  `clave` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idSuc`),
  KEY `idAlmacen` (`idAlmacen`),
  CONSTRAINT `mrp_sucursal_ibfk_1` FOREIGN KEY (`idAlmacen`) REFERENCES `almacen` (`idAlmacen`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `mrp_sucursal` WRITE;
/*!40000 ALTER TABLE `mrp_sucursal` DISABLE KEYS */;

INSERT INTO `mrp_sucursal` (`idSuc`, `nombre`, `direccion`, `idEstado`, `idMunicipio`, `cp`, `tel_contacto`, `contacto`, `idorganizacion`, `idAlmacen`, `clave`, `activo`)
VALUES
	(1,'Sucursal','',1,1,0,'','',1,1,NULL,-1);

/*!40000 ALTER TABLE `mrp_sucursal` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla mrp_talla
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_talla`;

CREATE TABLE `mrp_talla` (
  `idTal` int(11) NOT NULL AUTO_INCREMENT,
  `talla` varchar(11) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`idTal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla mrp_unidades
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mrp_unidades`;

CREATE TABLE `mrp_unidades` (
  `idUni` int(11) NOT NULL AUTO_INCREMENT,
  `compuesto` varchar(26) NOT NULL,
  `conversion` double DEFAULT NULL,
  `unidad` int(11) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT '0',
  `permiso` int(11) DEFAULT '1',
  PRIMARY KEY (`idUni`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

LOCK TABLES `mrp_unidades` WRITE;
/*!40000 ALTER TABLE `mrp_unidades` DISABLE KEYS */;

INSERT INTO `mrp_unidades` (`idUni`, `compuesto`, `conversion`, `unidad`, `orden`, `permiso`)
VALUES
	(1,'Unidad',1,1,1,0),
	(2,'Miligramo',1,2,1,0),
	(3,'Gramo',1000,2,2,0),
	(4,'Kilo',1000000,2,3,0),
	(5,'Tonelada',1000000000,2,4,0),
	(6,'Milimetro Cuadrado',1,6,1,0),
	(7,'Centimetro Cuadrado',100,6,2,0),
	(8,'Metro Cuadrado',1000,6,3,0),
	(9,'Kilometro Cuadrado',1000000000000,6,4,0),
	(10,'Milimetro',1,10,1,0),
	(11,'Centimetro',10,10,2,0),
	(12,'Metro',1000,10,3,0),
	(13,'Carrete',58000,10,4,0),
	(14,'Kilometro',1000000,10,5,0),
	(15,'Milisegundo',1,15,1,0),
	(16,'Segundo',1000,15,2,0),
	(17,'Minuto',60000,15,3,0),
	(18,'Hora',3600000,15,4,0),
	(19,'Dia',86400000,15,5,0),
	(20,'Mililitro',1,20,1,0),
	(21,'Litro',1000,20,2,0);

/*!40000 ALTER TABLE `mrp_unidades` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla mrp_vista_unidades
# ------------------------------------------------------------

DROP VIEW IF EXISTS `mrp_vista_unidades`;

CREATE TABLE `mrp_vista_unidades` (
   `idUnidad` INT(11) NOT NULL DEFAULT '0',
   `compuesto_descripcion` VARCHAR(26) NOT NULL,
   `conversion` DOUBLE NULL DEFAULT NULL,
   `unidad` INT(11) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Volcado de tabla municipios
# ------------------------------------------------------------

DROP TABLE IF EXISTS `municipios`;

CREATE TABLE `municipios` (
  `idmunicipio` int(11) NOT NULL AUTO_INCREMENT,
  `municipio` varchar(100) DEFAULT NULL,
  `idestado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idmunicipio`)
) ENGINE=InnoDB AUTO_INCREMENT=2458 DEFAULT CHARSET=utf8;

LOCK TABLES `municipios` WRITE;
/*!40000 ALTER TABLE `municipios` DISABLE KEYS */;

INSERT INTO `municipios` (`idmunicipio`, `municipio`, `idestado`)
VALUES
	(1,'Aguascalientes',1),
	(2,'Asientos',1),
	(3,'Calvillo',1),
	(4,'Cosio',1),
	(5,'El Llano',1),
	(6,'Jesus Maria',1),
	(7,'Pabellon de Arteaga',1),
	(8,'Rincon de Romos',1),
	(9,'San Francisco de los Romo',1),
	(10,'San Jose de Gracia',1),
	(11,'Tepezala',1),
	(12,'Mexicali',2),
	(13,'Tecate',2),
	(14,'Tijuana',2),
	(15,'Playas de Rosarito',2),
	(16,'Ensenada',2),
	(17,'La Paz',3),
	(18,'Los Cabos',3),
	(19,'Comondu',3),
	(20,'Loreto',3),
	(21,'Mulege',3),
	(22,'Campeche',4),
	(23,'Carmen',4),
	(24,'Palizada',4),
	(25,'Candelaria',4),
	(26,'Escarcega',4),
	(27,'Champoton',4),
	(28,'Hopelchen',4),
	(29,'Calakmul',4),
	(30,'Tenabo',4),
	(31,'Hecelchakan',4),
	(32,'Calkini',4),
	(33,'Saltillo',5),
	(34,'Arteaga',5),
	(35,'Juarez',5),
	(36,'Progreso',5),
	(37,'Escobedo',5),
	(38,'San Buenaventura',5),
	(39,'Abasolo',5),
	(40,'Candela',5),
	(41,'Frontera',5),
	(42,'Monclova',5),
	(43,'Castanos',5),
	(44,'Ramos Arizpe',5),
	(45,'General Cepeda',5),
	(46,'Piedras Negras',5),
	(47,'Nava',5),
	(48,'Acuna',5),
	(49,'Muzquiz',5),
	(50,'Jimenez',5),
	(51,'Zaragoza',5),
	(52,'Morelos',5),
	(53,'Allende',5),
	(54,'Villa Union',5),
	(55,'Guerrero',5),
	(56,'Hidalgo',5),
	(57,'Sabinas',5),
	(58,'San Juan de Sabinas',5),
	(59,'Torreon',5),
	(60,'Matamoros',5),
	(61,'Viesca',5),
	(62,'Ocampo',5),
	(63,'Nadadores',5),
	(64,'Sierra Mojada',5),
	(65,'Cuatro Cienegas',5),
	(66,'Lamadrid',5),
	(67,'Sacramento',5),
	(68,'San Pedro',5),
	(69,'Francisco I. Madero',5),
	(70,'Parras',5),
	(71,'Colima',6),
	(72,'Tecoman',6),
	(73,'Manzanillo',6),
	(74,'Armeria',6),
	(75,'Coquimatlan',6),
	(76,'Comala',6),
	(77,'Cuauhtemoc',6),
	(78,'Ixtlahuacan',6),
	(79,'Minatitlan',6),
	(80,'Villa de Alvarez',6),
	(81,'Juarez',7),
	(82,'Tuxtla Gutierrez',7),
	(83,'San Fernando',7),
	(84,'Berriozabal',7),
	(85,'Ocozocoautla de Espinosa',7),
	(86,'Suchiapa',7),
	(87,'Chiapa de Corzo',7),
	(88,'Osumacinta',7),
	(89,'San Cristobal de las Casas',7),
	(90,'Chamula',7),
	(91,'Ixtapa',7),
	(92,'Zinacantan',7),
	(93,'Acala',7),
	(94,'Chiapilla',7),
	(95,'San Lucas',7),
	(96,'Teopisca',7),
	(97,'Amatenango del Valle',7),
	(98,'Chanal',7),
	(99,'Oxchuc',7),
	(100,'Huixtan',7),
	(101,'Tenejapa',7),
	(102,'Mitontic',7),
	(103,'Reforma',7),
	(104,'Pichucalco',7),
	(105,'Sunuapa',7),
	(106,'Ostuacan',7),
	(107,'Francisco Leon',7),
	(108,'Ixtacomitan',7),
	(109,'Solosuchiapa',7),
	(110,'Ixtapangajoya',7),
	(111,'Tecpatan',7),
	(112,'Copainala',7),
	(113,'Chicoasen',7),
	(114,'Coapilla',7),
	(115,'Pantepec',7),
	(116,'Tapalapa',7),
	(117,'Ocotepec',7),
	(118,'Chapultenango',7),
	(119,'Amatan',7),
	(120,'Huitiupan',7),
	(121,'Ixhuatan',7),
	(122,'Tapilula',7),
	(123,'Rayon',7),
	(124,'Pueblo Nuevo Solistahuacan',7),
	(125,'Jitotol',7),
	(126,'Bochil',7),
	(127,'Soyalo',7),
	(128,'San Juan Cancuc',7),
	(129,'Sabanilla',7),
	(130,'Simojovel',7),
	(131,'San Andres Duraznal',7),
	(132,'El Bosque',7),
	(133,'Chalchihuitan',7),
	(134,'Larrainzar',7),
	(135,'Santiago el Pinar',7),
	(136,'Chenalho',7),
	(137,'Aldama',7),
	(138,'Pantelho',7),
	(139,'Sitala',7),
	(140,'Salto de Agua',7),
	(141,'Tila',7),
	(142,'Tumbala',7),
	(143,'Yajalon',7),
	(144,'Chilon',7),
	(145,'Ocosingo',7),
	(146,'Benemerito de las Americas',7),
	(147,'Marques de Comillas',7),
	(148,'Palenque',7),
	(149,'La Libertad',7),
	(150,'Catazaja',7),
	(151,'Comitan de Dominguez',7),
	(152,'Tzimol',7),
	(153,'Chicomuselo',7),
	(154,'Bella Vista',7),
	(155,'Frontera Comalapa',7),
	(156,'La Trinitaria',7),
	(157,'La Independencia',7),
	(158,'Maravilla Tenejapa',7),
	(159,'Las Margaritas',7),
	(160,'Altamirano',7),
	(161,'Venustiano Carranza',7),
	(162,'Totolapa',7),
	(163,'Nicolas Ruiz',7),
	(164,'Las Rosas',7),
	(165,'La Concordia',7),
	(166,'Angel Albino Corzo',7),
	(167,'Montecristo de Guerrero',7),
	(168,'Socoltenango',7),
	(169,'Cintalapa',7),
	(170,'Jiquipilas',7),
	(171,'Arriaga',7),
	(172,'Villaflores',7),
	(173,'Tonala',7),
	(174,'Villa Corzo',7),
	(175,'Pijijiapan',7),
	(176,'Mapastepec',7),
	(177,'Acapetahua',7),
	(178,'Acacoyagua',7),
	(179,'Escuintla',7),
	(180,'Villa Comaltitlan',7),
	(181,'Huixtla',7),
	(182,'Mazatan',7),
	(183,'Huehuetan',7),
	(184,'Tuzantan',7),
	(185,'Tapachula',7),
	(186,'Suchiate',7),
	(187,'Frontera Hidalgo',7),
	(188,'Metapa',7),
	(189,'Tuxtla Chico',7),
	(190,'Union Juarez',7),
	(191,'Cacahoatan',7),
	(192,'Motozintla',7),
	(193,'Mazapa de Madero',7),
	(194,'Amatenango de la Frontera',7),
	(195,'Bejucal de Ocampo',7),
	(196,'La Grandeza',7),
	(197,'El Porvenir',7),
	(198,'Siltepec',7),
	(199,'Juarez',8),
	(200,'Jimenez',8),
	(201,'Morelos',8),
	(202,'Allende',8),
	(203,'Guerrero',8),
	(204,'Matamoros',8),
	(205,'Ocampo',8),
	(206,'Cuauhtemoc',8),
	(207,'Aldama',8),
	(208,'Chihuahua',8),
	(209,'Riva Palacio',8),
	(210,'Aquiles Serdan',8),
	(211,'Bachiniva',8),
	(212,'Nuevo Casas Grandes',8),
	(213,'Ascension',8),
	(214,'Janos',8),
	(215,'Casas Grandes',8),
	(216,'Galeana',8),
	(217,'Buenaventura',8),
	(218,'Gomez Farias',8),
	(219,'Ignacio Zaragoza',8),
	(220,'Madera',8),
	(221,'Namiquipa',8),
	(222,'Temosachic',8),
	(223,'Matachi',8),
	(224,'Guadalupe',8),
	(225,'Praxedis G. Guerrero',8),
	(226,'Ahumada',8),
	(227,'Coyame del Sotol',8),
	(228,'Ojinaga',8),
	(229,'Julimes',8),
	(230,'Manuel Benavides',8),
	(231,'Delicias',8),
	(232,'Rosales',8),
	(233,'Meoqui',8),
	(234,'Dr. Belisario Dominguez',8),
	(235,'Satevo',8),
	(236,'San Francisco de Borja',8),
	(237,'Nonoava',8),
	(238,'Guachochi',8),
	(239,'Bocoyna',8),
	(240,'Cusihuiriachi',8),
	(241,'Gran Morelos',8),
	(242,'Santa Isabel',8),
	(243,'Carichi',8),
	(244,'Uruachi',8),
	(245,'Moris',8),
	(246,'Chinipas',8),
	(247,'Maguarichi',8),
	(248,'Guazapares',8),
	(249,'Batopilas',8),
	(250,'Urique',8),
	(251,'Guadalupe y Calvo',8),
	(252,'San Francisco del Oro',8),
	(253,'Rosario',8),
	(254,'Huejotitan',8),
	(255,'El Tule',8),
	(256,'Balleza',8),
	(257,'Santa Barbara',8),
	(258,'Camargo',8),
	(259,'Saucillo',8),
	(260,'Valle de Zaragoza',8),
	(261,'La Cruz',8),
	(262,'San Francisco de Conchos',8),
	(263,'Hidalgo del Parral',8),
	(264,'Lopez',8),
	(265,'Coronado',8),
	(266,'Cuauhtemoc',9),
	(267,'Venustiano Carranza',9),
	(268,'Alvaro Obregon',9),
	(269,'Azcapotzalco',9),
	(270,'Benito Juarez',9),
	(271,'Coyoacan',9),
	(272,'Cuajimalpa de Morelos',9),
	(273,'Gustavo A. Madero',9),
	(274,'Iztacalco',9),
	(275,'Iztapalapa',9),
	(276,'La Magdalena Contreras',9),
	(277,'Miguel Hidalgo',9),
	(278,'Milpa Alta',9),
	(279,'Tlahuac',9),
	(280,'Tlalpan',9),
	(281,'Xochimilco',9),
	(282,'Hidalgo',10),
	(283,'Ocampo',10),
	(284,'Durango',10),
	(285,'Canatlan',10),
	(286,'Nuevo Ideal',10),
	(287,'Coneto de Comonfort',10),
	(288,'San Juan del Rio',10),
	(289,'Canelas',10),
	(290,'Topia',10),
	(291,'Tamazula',10),
	(292,'Santiago Papasquiaro',10),
	(293,'Otaez',10),
	(294,'San Dimas',10),
	(295,'Guadalupe Victoria',10),
	(296,'Penon Blanco',10),
	(297,'Panuco de Coronado',10),
	(298,'Poanas',10),
	(299,'Nombre de Dios',10),
	(300,'Vicente Guerrero',10),
	(301,'Suchil',10),
	(302,'Pueblo Nuevo',10),
	(303,'Mezquital',10),
	(304,'Gomez Palacio',10),
	(305,'Lerdo',10),
	(306,'Mapimi',10),
	(307,'Tlahualilo',10),
	(308,'Guanacevi',10),
	(309,'San Bernardo',10),
	(310,'Inde',10),
	(311,'San Pedro del Gallo',10),
	(312,'Tepehuanes',10),
	(313,'El Oro',10),
	(314,'Nazas',10),
	(315,'San Luis del Cordero',10),
	(316,'Rodeo',10),
	(317,'Cuencame',10),
	(318,'Santa Clara',10),
	(319,'San Juan de Guadalupe',10),
	(320,'General Simon Bolivar',10),
	(321,'Abasolo',11),
	(322,'Ocampo',11),
	(323,'Pueblo Nuevo',11),
	(324,'Guanajuato',11),
	(325,'Silao',11),
	(326,'Romita',11),
	(327,'San Francisco del Rincon',11),
	(328,'Purisima del Rincon',11),
	(329,'Manuel Doblado',11),
	(330,'Irapuato',11),
	(331,'Salamanca',11),
	(332,'Penjamo',11),
	(333,'Cueramaro',11),
	(334,'Huanimaro',11),
	(335,'Leon',11),
	(336,'San Felipe',11),
	(337,'San Miguel de Allende',11),
	(338,'Dolores Hidalgo Cuna de la Independencia Nacional',11),
	(339,'San Diego de la Union',11),
	(340,'San Luis de la Paz',11),
	(341,'Victoria',11),
	(342,'Xichu',11),
	(343,'Atarjea',11),
	(344,'Santa Catarina',11),
	(345,'Doctor Mora',11),
	(346,'Tierra Blanca',11),
	(347,'San Jose Iturbide',11),
	(348,'Celaya',11),
	(349,'Apaseo el Grande',11),
	(350,'Comonfort',11),
	(351,'Santa Cruz de Juventino Rosas',11),
	(352,'Villagran',11),
	(353,'Cortazar',11),
	(354,'Valle de Santiago',11),
	(355,'Jaral del Progreso',11),
	(356,'Apaseo el Alto',11),
	(357,'Jerecuaro',11),
	(358,'Coroneo',11),
	(359,'Acambaro',11),
	(360,'Tarimoro',11),
	(361,'Tarandacuao',11),
	(362,'Moroleon',11),
	(363,'Salvatierra',11),
	(364,'Yuriria',11),
	(365,'Santiago Maravatio',11),
	(366,'Uriangato',11),
	(367,'Benito Juarez',12),
	(368,'Chilpancingo de los Bravo',12),
	(369,'General Heliodoro Castillo',12),
	(370,'Leonardo Bravo',12),
	(371,'Tixtla de Guerrero',12),
	(372,'Ayutla de los Libres',12),
	(373,'Mochitlan',12),
	(374,'Quechultenango',12),
	(375,'Tecoanapa',12),
	(376,'Acapulco de Juarez',12),
	(377,'Juan R. Escudero',12),
	(378,'San Marcos',12),
	(379,'Iguala de la Independencia',12),
	(380,'Huitzuco de los Figueroa',12),
	(381,'Tepecoacuilco de Trujano',12),
	(382,'Eduardo Neri',12),
	(383,'Taxco de Alarcon',12),
	(384,'Buenavista de Cuellar',12),
	(385,'Tetipac',12),
	(386,'Pilcaya',12),
	(387,'Teloloapan',12),
	(388,'Ixcateopan de Cuauhtemoc',12),
	(389,'Pedro Ascencio Alquisiras',12),
	(390,'General Canuto A. Neri',12),
	(391,'Arcelia',12),
	(392,'Apaxtla',12),
	(393,'Cuetzala del Progreso',12),
	(394,'Cocula',12),
	(395,'Tlapehuala',12),
	(396,'Cutzamala de Pinzon',12),
	(397,'Pungarabato',12),
	(398,'Tlalchapa',12),
	(399,'Coyuca de Catalan',12),
	(400,'Ajuchitlan del Progreso',12),
	(401,'Zirandaro',12),
	(402,'San Miguel Totolapan',12),
	(403,'La Union de Isidoro Montes de Oca',12),
	(404,'Petatlan',12),
	(405,'Coahuayutla de Jose Maria Izazaga',12),
	(406,'Zihuatanejo de Azueta',12),
	(407,'Tecpan de Galeana',12),
	(408,'Atoyac de Alvarez',12),
	(409,'Coyuca de Benitez',12),
	(410,'Olinala',12),
	(411,'Atenango del Rio',12),
	(412,'Copalillo',12),
	(413,'Cualac',12),
	(414,'Chilapa de Alvarez',12),
	(415,'Jose Joaquin de Herrera',12),
	(416,'Ahuacuotzingo',12),
	(417,'Zitlala',12),
	(418,'Martir de Cuilapan',12),
	(419,'Huamuxtitlan',12),
	(420,'Xochihuehuetlan',12),
	(421,'Alpoyeca',12),
	(422,'Tlapa de Comonfort',12),
	(423,'Tlalixtaquilla de Maldonado',12),
	(424,'Xalpatlahuac',12),
	(425,'Zapotitlan Tablas',12),
	(426,'Acatepec',12),
	(427,'Atlixtac',12),
	(428,'Copanatoyac',12),
	(429,'Malinaltepec',12),
	(430,'Iliatenco',12),
	(431,'Tlacoapa',12),
	(432,'Atlamajalcingo del Monte',12),
	(433,'San Luis Acatlan',12),
	(434,'Metlatonoc',12),
	(435,'Cochoapa el Grande',12),
	(436,'Alcozauca de Guerrero',12),
	(437,'Ometepec',12),
	(438,'Tlacoachistlahuaca',12),
	(439,'Xochistlahuaca',12),
	(440,'Florencio Villarreal',12),
	(441,'Cuautepec',12),
	(442,'Copala',12),
	(443,'Azoyu',12),
	(444,'Juchitan',12),
	(445,'Marquelia',12),
	(446,'Cuajinicuilapa',12),
	(447,'Igualapa',12),
	(448,'Francisco I. Madero',13),
	(449,'Pachuca de Soto',13),
	(450,'Mineral del Chico',13),
	(451,'Mineral del Monte',13),
	(452,'Ajacuba',13),
	(453,'San Agustin Tlaxiaca',13),
	(454,'Mineral de la Reforma',13),
	(455,'Zapotlan de Juarez',13),
	(456,'Jacala de Ledezma',13),
	(457,'Pisaflores',13),
	(458,'Pacula',13),
	(459,'La Mision',13),
	(460,'Chapulhuacan',13),
	(461,'Ixmiquilpan',13),
	(462,'Zimapan',13),
	(463,'Nicolas Flores',13),
	(464,'Cardonal',13),
	(465,'Tasquillo',13),
	(466,'Alfajayucan',13),
	(467,'Huichapan',13),
	(468,'Tecozautla',13),
	(469,'Nopala de Villagran',13),
	(470,'Actopan',13),
	(471,'Santiago de Anaya',13),
	(472,'San Salvador',13),
	(473,'El Arenal',13),
	(474,'Mixquiahuala de Juarez',13),
	(475,'Progreso de Obregon',13),
	(476,'Chilcuautla',13),
	(477,'Tezontepec de Aldama',13),
	(478,'Tlahuelilpan',13),
	(479,'Tula de Allende',13),
	(480,'Tepeji del Rio de Ocampo',13),
	(481,'Chapantongo',13),
	(482,'Tepetitlan',13),
	(483,'Tetepango',13),
	(484,'Tlaxcoapan',13),
	(485,'Atitalaquia',13),
	(486,'Atotonilco de Tula',13),
	(487,'Huejutla de Reyes',13),
	(488,'San Felipe Orizatlan',13),
	(489,'Jaltocan',13),
	(490,'Huautla',13),
	(491,'Atlapexco',13),
	(492,'Huazalingo',13),
	(493,'Yahualica',13),
	(494,'Xochiatipan',13),
	(495,'Molango de Escamilla',13),
	(496,'Tepehuacan de Guerrero',13),
	(497,'Lolotla',13),
	(498,'Tlanchinol',13),
	(499,'Tlahuiltepa',13),
	(500,'Juarez Hidalgo',13),
	(501,'Zacualtipan de Angeles',13),
	(502,'Calnali',13),
	(503,'Xochicoatlan',13),
	(504,'Tianguistengo',13),
	(505,'Atotonilco el Grande',13),
	(506,'Eloxochitlan',13),
	(507,'Metztitlan',13),
	(508,'San Agustin Metzquititlan',13),
	(509,'Metepec',13),
	(510,'Huehuetla',13),
	(511,'San Bartolo Tutotepec',13),
	(512,'Agua Blanca de Iturbide',13),
	(513,'Tenango de Doria',13),
	(514,'Huasca de Ocampo',13),
	(515,'Acatlan',13),
	(516,'Omitlan de Juarez',13),
	(517,'Epazoyucan',13),
	(518,'Tulancingo de Bravo',13),
	(519,'Acaxochitlan',13),
	(520,'Cuautepec de Hinojosa',13),
	(521,'Santiago Tulantepec de Lugo Guerrero',13),
	(522,'Singuilucan',13),
	(523,'Tizayuca',13),
	(524,'Zempoala',13),
	(525,'Tolcayuca',13),
	(526,'Villa de Tezontepec',13),
	(527,'Apan',13),
	(528,'Tlanalapa',13),
	(529,'Almoloya',13),
	(530,'Emiliano Zapata',13),
	(531,'Tepeapulco',13),
	(532,'Jesus Maria',14),
	(533,'Tonala',14),
	(534,'Gomez Farias',14),
	(535,'San Marcos',14),
	(536,'Cocula',14),
	(537,'El Arenal',14),
	(538,'Guadalajara',14),
	(539,'Zapopan',14),
	(540,'San Cristobal de la Barranca',14),
	(541,'Ixtlahuacan del Rio',14),
	(542,'Tala',14),
	(543,'Amatitan',14),
	(544,'Zapotlanejo',14),
	(545,'Acatic',14),
	(546,'Cuquio',14),
	(547,'Tlaquepaque',14),
	(548,'Tlajomulco de Zuniga',14),
	(549,'El Salto',14),
	(550,'Acatlan de Juarez',14),
	(551,'Villa Corona',14),
	(552,'Zacoalco de Torres',14),
	(553,'Atemajac de Brizuela',14),
	(554,'Jocotepec',14),
	(555,'Ixtlahuacan de los Membrillos',14),
	(556,'Juanacatlan',14),
	(557,'Chapala',14),
	(558,'Poncitlan',14),
	(559,'Zapotlan del Rey',14),
	(560,'Huejuquilla el Alto',14),
	(561,'Mezquitic',14),
	(562,'Villa Guerrero',14),
	(563,'Bolanos',14),
	(564,'Totatiche',14),
	(565,'Colotlan',14),
	(566,'Santa Maria de los Angeles',14),
	(567,'Huejucar',14),
	(568,'Chimaltitan',14),
	(569,'San Martin de Bolanos',14),
	(570,'Tequila',14),
	(571,'Hostotipaquillo',14),
	(572,'Magdalena',14),
	(573,'Etzatlan',14),
	(574,'San Juanito de Escobedo',14),
	(575,'Ameca',14),
	(576,'Ahualulco de Mercado',14),
	(577,'Teuchitlan',14),
	(578,'San Martin Hidalgo',14),
	(579,'Guachinango',14),
	(580,'Mixtlan',14),
	(581,'Mascota',14),
	(582,'San Sebastian del Oeste',14),
	(583,'San Juan de los Lagos',14),
	(584,'Jalostotitlan',14),
	(585,'San Miguel el Alto',14),
	(586,'San Julian',14),
	(587,'Arandas',14),
	(588,'San Ignacio Cerro Gordo',14),
	(589,'Teocaltiche',14),
	(590,'Villa Hidalgo',14),
	(591,'Encarnacion de Diaz',14),
	(592,'Yahualica de Gonzalez Gallo',14),
	(593,'Mexticacan',14),
	(594,'Canadas de Obregon',14),
	(595,'Valle de Guadalupe',14),
	(596,'Lagos de Moreno',14),
	(597,'Ojuelos de Jalisco',14),
	(598,'Union de San Antonio',14),
	(599,'San Diego de Alejandria',14),
	(600,'Tepatitlan de Morelos',14),
	(601,'Tototlan',14),
	(602,'Atotonilco el Alto',14),
	(603,'Ocotlan',14),
	(604,'Jamay',14),
	(605,'La Barca',14),
	(606,'Ayotlan',14),
	(607,'Degollado',14),
	(608,'Union de Tula',14),
	(609,'Ayutla',14),
	(610,'Atenguillo',14),
	(611,'Cuautla',14),
	(612,'Atengo',14),
	(613,'Talpa de Allende',14),
	(614,'Puerto Vallarta',14),
	(615,'Cabo Corrientes',14),
	(616,'Tomatlan',14),
	(617,'Tecolotlan',14),
	(618,'Tenamaxtlan',14),
	(619,'Juchitlan',14),
	(620,'Chiquilistlan',14),
	(621,'Ejutla',14),
	(622,'El Limon',14),
	(623,'El Grullo',14),
	(624,'Tonaya',14),
	(625,'Tuxcacuesco',14),
	(626,'Villa Purificacion',14),
	(627,'La Huerta',14),
	(628,'Autlan de Navarro',14),
	(629,'Casimiro Castillo',14),
	(630,'Cuautitlan de Garcia Barragan',14),
	(631,'Cihuatlan',14),
	(632,'Zapotlan el Grande',14),
	(633,'Concepcion de Buenos Aires',14),
	(634,'Atoyac',14),
	(635,'Techaluta de Montenegro',14),
	(636,'Teocuitatlan de Corona',14),
	(637,'Sayula',14),
	(638,'Tapalpa',14),
	(639,'Amacueca',14),
	(640,'Tizapan el Alto',14),
	(641,'Tuxcueca',14),
	(642,'La Manzanilla de la Paz',14),
	(643,'Mazamitla',14),
	(644,'Valle de Juarez',14),
	(645,'Quitupan',14),
	(646,'Zapotiltic',14),
	(647,'Tamazula de Gordiano',14),
	(648,'San Gabriel',14),
	(649,'Toliman',14),
	(650,'Zapotitlan de Vadillo',14),
	(651,'Tuxpan',14),
	(652,'Tonila',14),
	(653,'Pihuamo',14),
	(654,'Tecalitlan',14),
	(655,'Jilotlan de los Dolores',14),
	(656,'Santa Maria del Oro',14),
	(657,'La Paz',15),
	(658,'Morelos',15),
	(659,'Rayon',15),
	(660,'El Oro',15),
	(661,'Metepec',15),
	(662,'Villa Guerrero',15),
	(663,'Toluca',15),
	(664,'Acambay',15),
	(665,'Aculco',15),
	(666,'Temascalcingo',15),
	(667,'Atlacomulco',15),
	(668,'Timilpan',15),
	(669,'San Felipe del Progreso',15),
	(670,'San Jose del Rincon',15),
	(671,'Jocotitlan',15),
	(672,'Ixtlahuaca',15),
	(673,'Jiquipilco',15),
	(674,'Temoaya',15),
	(675,'Almoloya de Juarez',15),
	(676,'Villa Victoria',15),
	(677,'Villa de Allende',15),
	(678,'Donato Guerra',15),
	(679,'Ixtapan del Oro',15),
	(680,'Santo Tomas',15),
	(681,'Otzoloapan',15),
	(682,'Zacazonapan',15),
	(683,'Valle de Bravo',15),
	(684,'Amanalco',15),
	(685,'Temascaltepec',15),
	(686,'Zinacantepec',15),
	(687,'Tejupilco',15),
	(688,'Luvianos',15),
	(689,'San Simon de Guerrero',15),
	(690,'Amatepec',15),
	(691,'Tlatlaya',15),
	(692,'Sultepec',15),
	(693,'Texcaltitlan',15),
	(694,'Coatepec Harinas',15),
	(695,'Zacualpan',15),
	(696,'Almoloya de Alquisiras',15),
	(697,'Ixtapan de la Sal',15),
	(698,'Tonatico',15),
	(699,'Zumpahuacan',15),
	(700,'Lerma',15),
	(701,'Xonacatlan',15),
	(702,'Otzolotepec',15),
	(703,'San Mateo Atenco',15),
	(704,'Mexicaltzingo',15),
	(705,'Calimaya',15),
	(706,'Chapultepec',15),
	(707,'San Antonio la Isla',15),
	(708,'Tenango del Valle',15),
	(709,'Joquicingo',15),
	(710,'Tenancingo',15),
	(711,'Malinalco',15),
	(712,'Ocuilan',15),
	(713,'Atizapan',15),
	(714,'Almoloya del Rio',15),
	(715,'Texcalyacac',15),
	(716,'Tianguistenco',15),
	(717,'Xalatlaco',15),
	(718,'Capulhuac',15),
	(719,'Ocoyoacac',15),
	(720,'Huixquilucan',15),
	(721,'Atizapan de Zaragoza',15),
	(722,'Naucalpan de Juarez',15),
	(723,'Tlalnepantla de Baz',15),
	(724,'Polotitlan',15),
	(725,'Jilotepec',15),
	(726,'Soyaniquilpan de Juarez',15),
	(727,'Villa del Carbon',15),
	(728,'Chapa de Mota',15),
	(729,'Nicolas Romero',15),
	(730,'Isidro Fabela',15),
	(731,'Jilotzingo',15),
	(732,'Tepotzotlan',15),
	(733,'Coyotepec',15),
	(734,'Huehuetoca',15),
	(735,'Cuautitlan Izcalli',15),
	(736,'Teoloyucan',15),
	(737,'Cuautitlan',15),
	(738,'Melchor Ocampo',15),
	(739,'Tultitlan',15),
	(740,'Tultepec',15),
	(741,'Ecatepec de Morelos',15),
	(742,'Zumpango',15),
	(743,'Tequixquiac',15),
	(744,'Apaxco',15),
	(745,'Hueypoxtla',15),
	(746,'Coacalco de Berriozabal',15),
	(747,'Tecamac',15),
	(748,'Jaltenco',15),
	(749,'Tonanitla',15),
	(750,'Nextlalpan',15),
	(751,'Teotihuacan',15),
	(752,'San Martin de las Piramides',15),
	(753,'Acolman',15),
	(754,'Otumba',15),
	(755,'Axapusco',15),
	(756,'Nopaltepec',15),
	(757,'Temascalapa',15),
	(758,'Tezoyuca',15),
	(759,'Chiautla',15),
	(760,'Papalotla',15),
	(761,'Tepetlaoxtoc',15),
	(762,'Texcoco',15),
	(763,'Chiconcuac',15),
	(764,'Atenco',15),
	(765,'Chimalhuacan',15),
	(766,'Chicoloapan',15),
	(767,'Ixtapaluca',15),
	(768,'Chalco',15),
	(769,'Valle de Chalco Solidaridad',15),
	(770,'Temamatla',15),
	(771,'Cocotitlan',15),
	(772,'Tlalmanalco',15),
	(773,'Ayapango',15),
	(774,'Tenango del Aire',15),
	(775,'Ozumba',15),
	(776,'Juchitepec',15),
	(777,'Tepetlixpa',15),
	(778,'Amecameca',15),
	(779,'Atlautla',15),
	(780,'Ecatzingo',15),
	(781,'Nezahualcoyotl',15),
	(782,'Arteaga',16),
	(783,'Juarez',16),
	(784,'Jimenez',16),
	(785,'Morelos',16),
	(786,'Hidalgo',16),
	(787,'Ocampo',16),
	(788,'San Lucas',16),
	(789,'Venustiano Carranza',16),
	(790,'Alvaro Obregon',16),
	(791,'Tuxpan',16),
	(792,'Morelia',16),
	(793,'Huaniqueo',16),
	(794,'Coeneo',16),
	(795,'Quiroga',16),
	(796,'Tzintzuntzan',16),
	(797,'Lagunillas',16),
	(798,'Acuitzio',16),
	(799,'Madero',16),
	(800,'Puruandiro',16),
	(801,'Jose Sixto Verduzco',16),
	(802,'Angamacutiro',16),
	(803,'Panindicuaro',16),
	(804,'Zacapu',16),
	(805,'Tlazazalca',16),
	(806,'Purepero',16),
	(807,'Huandacareo',16),
	(808,'Cuitzeo',16),
	(809,'Chucandiro',16),
	(810,'Copandaro',16),
	(811,'Tarimbaro',16),
	(812,'Santa Ana Maya',16),
	(813,'Zinapecuaro',16),
	(814,'Indaparapeo',16),
	(815,'Querendaro',16),
	(816,'Sahuayo',16),
	(817,'Brisenas',16),
	(818,'Cojumatlan de Regules',16),
	(819,'Pajacuaran',16),
	(820,'Vista Hermosa',16),
	(821,'Tanhuato',16),
	(822,'Yurecuaro',16),
	(823,'Ixtlan',16),
	(824,'La Piedad',16),
	(825,'Numaran',16),
	(826,'Churintzio',16),
	(827,'Zinaparo',16),
	(828,'Penjamillo',16),
	(829,'Marcos Castellanos',16),
	(830,'Jiquilpan',16),
	(831,'Villamar',16),
	(832,'Chavinda',16),
	(833,'Zamora',16),
	(834,'Ecuandureo',16),
	(835,'Tangancicuaro',16),
	(836,'Chilchota',16),
	(837,'Jacona',16),
	(838,'Tangamandapio',16),
	(839,'Cotija',16),
	(840,'Tocumbo',16),
	(841,'Tinguindin',16),
	(842,'Uruapan',16),
	(843,'Charapan',16),
	(844,'Paracho',16),
	(845,'Cheran',16),
	(846,'Nahuatzen',16),
	(847,'Tingambato',16),
	(848,'Los Reyes',16),
	(849,'Periban',16),
	(850,'Tancitaro',16),
	(851,'Nuevo Parangaricutiro',16),
	(852,'Buenavista',16),
	(853,'Tepalcatepec',16),
	(854,'Aguililla',16),
	(855,'Apatzingan',16),
	(856,'Paracuaro',16),
	(857,'Coahuayana',16),
	(858,'Chinicuila',16),
	(859,'Coalcoman de Vazquez Pallares',16),
	(860,'Aquila',16),
	(861,'Tumbiscatio',16),
	(862,'Lazaro Cardenas',16),
	(863,'Epitacio Huerta',16),
	(864,'Contepec',16),
	(865,'Tlalpujahua',16),
	(866,'Maravatio',16),
	(867,'Irimbo',16),
	(868,'Senguio',16),
	(869,'Charo',16),
	(870,'Tzitzio',16),
	(871,'Tiquicheo de Nicolas Romero',16),
	(872,'Aporo',16),
	(873,'Angangueo',16),
	(874,'Jungapeo',16),
	(875,'Zitacuaro',16),
	(876,'Tuzantla',16),
	(877,'Susupuato',16),
	(878,'Patzcuaro',16),
	(879,'Erongaricuaro',16),
	(880,'Huiramba',16),
	(881,'Tacambaro',16),
	(882,'Turicato',16),
	(883,'Ziracuaretiro',16),
	(884,'Taretan',16),
	(885,'Gabriel Zamora',16),
	(886,'Nuevo Urecho',16),
	(887,'Mugica',16),
	(888,'Salvador Escalante',16),
	(889,'Ario',16),
	(890,'La Huacana',16),
	(891,'Churumuco',16),
	(892,'Nocupetaro',16),
	(893,'Caracuaro',16),
	(894,'Huetamo',16),
	(895,'Emiliano Zapata',17),
	(896,'Cuautla',17),
	(897,'Zacualpan',17),
	(898,'Cuernavaca',17),
	(899,'Huitzilac',17),
	(900,'Tepoztlan',17),
	(901,'Tlalnepantla',17),
	(902,'Tlayacapan',17),
	(903,'Jiutepec',17),
	(904,'Temixco',17),
	(905,'Miacatlan',17),
	(906,'Coatlan del Rio',17),
	(907,'Tetecala',17),
	(908,'Mazatepec',17),
	(909,'Amacuzac',17),
	(910,'Puente de Ixtla',17),
	(911,'Ayala',17),
	(912,'Yautepec',17),
	(913,'Tlaltizapan',17),
	(914,'Zacatepec',17),
	(915,'Xochitepec',17),
	(916,'Tetela del Volcan',17),
	(917,'Yecapixtla',17),
	(918,'Totolapan',17),
	(919,'Atlatlahucan',17),
	(920,'Ocuituco',17),
	(921,'Temoac',17),
	(922,'Jojutla',17),
	(923,'Tepalcingo',17),
	(924,'Jonacatepec',17),
	(925,'Axochiapan',17),
	(926,'Jantetelco',17),
	(927,'Tlaquiltenango',17),
	(928,'Tuxpan',18),
	(929,'Santa Maria del Oro',18),
	(930,'Tepic',18),
	(931,'Santiago Ixcuintla',18),
	(932,'Acaponeta',18),
	(933,'Tecuala',18),
	(934,'Huajicori',18),
	(935,'Del Nayar',18),
	(936,'La Yesca',18),
	(937,'Ruiz',18),
	(938,'Rosamorada',18),
	(939,'Compostela',18),
	(940,'Bahia de Banderas',18),
	(941,'San Blas',18),
	(942,'Xalisco',18),
	(943,'San Pedro Lagunillas',18),
	(944,'Jala',18),
	(945,'Ahuacatlan',18),
	(946,'Ixtlan del Rio',18),
	(947,'Amatlan de Canas',18),
	(948,'Carmen',19),
	(949,'Juarez',19),
	(950,'Abasolo',19),
	(951,'Allende',19),
	(952,'Hidalgo',19),
	(953,'Galeana',19),
	(954,'Guadalupe',19),
	(955,'Santa Catarina',19),
	(956,'Melchor Ocampo',19),
	(957,'Monterrey',19),
	(958,'Anahuac',19),
	(959,'Lampazos de Naranjo',19),
	(960,'Mina',19),
	(961,'Bustamante',19),
	(962,'Sabinas Hidalgo',19),
	(963,'Villaldama',19),
	(964,'Vallecillo',19),
	(965,'Paras',19),
	(966,'Salinas Victoria',19),
	(967,'Cienega de Flores',19),
	(968,'Higueras',19),
	(969,'Gral. Zuazua',19),
	(970,'Agualeguas',19),
	(971,'Gral. Trevino',19),
	(972,'Cerralvo',19),
	(973,'Garcia',19),
	(974,'Gral. Escobedo',19),
	(975,'San Pedro Garza Garcia',19),
	(976,'San Nicolas de los Garza',19),
	(977,'Apodaca',19),
	(978,'Pesqueria',19),
	(979,'Marin',19),
	(980,'Dr. Gonzalez',19),
	(981,'Los Ramones',19),
	(982,'Los Herreras',19),
	(983,'Los Aldamas',19),
	(984,'Dr. Coss',19),
	(985,'Gral. Bravo',19),
	(986,'China',19),
	(987,'Santiago',19),
	(988,'Gral. Teran',19),
	(989,'Cadereyta Jimenez',19),
	(990,'Montemorelos',19),
	(991,'Rayones',19),
	(992,'Linares',19),
	(993,'Iturbide',19),
	(994,'Hualahuises',19),
	(995,'Dr. Arroyo',19),
	(996,'Aramberri',19),
	(997,'Gral. Zaragoza',19),
	(998,'Mier y Noriega',19),
	(999,'San Juan del Rio',20),
	(1000,'Villa Hidalgo',20),
	(1001,'Oaxaca de Juarez',20),
	(1002,'Villa de Etla',20),
	(1003,'San Juan Bautista Atatlahuca',20),
	(1004,'San Jeronimo Sosola',20),
	(1005,'San Juan Bautista Jayacatlan',20),
	(1006,'San Francisco Telixtlahuaca',20),
	(1007,'Santiago Tenango',20),
	(1008,'San Pablo Huitzo',20),
	(1009,'San Juan del Estado',20),
	(1010,'Magdalena Apasco',20),
	(1011,'Santiago Suchilquitongo',20),
	(1012,'San Juan Bautista Guelache',20),
	(1013,'Reyes Etla',20),
	(1014,'Nazareno Etla',20),
	(1015,'San Andres Zautla',20),
	(1016,'San Agustin Etla',20),
	(1017,'Soledad Etla',20),
	(1018,'Santo Tomas Mazaltepec',20),
	(1019,'Guadalupe Etla',20),
	(1020,'San Pablo Etla',20),
	(1021,'San Felipe Tejalapam',20),
	(1022,'San Lorenzo Cacaotepec',20),
	(1023,'Santa Maria Penoles',20),
	(1024,'Santiago Tlazoyaltepec',20),
	(1025,'Tlalixtac de Cabrera',20),
	(1026,'San Jacinto Amilpas',20),
	(1027,'San Andres Huayapam',20),
	(1028,'San Agustin Yatareni',20),
	(1029,'Santo Domingo Tomaltepec',20),
	(1030,'Santa Maria del Tule',20),
	(1031,'San Juan Bautista Tuxtepec',20),
	(1032,'Loma Bonita',20),
	(1033,'San Jose Independencia',20),
	(1034,'Cosolapa',20),
	(1035,'Acatlan de Perez Figueroa',20),
	(1036,'San Miguel Soyaltepec',20),
	(1037,'Ayotzintepec',20),
	(1038,'San Pedro Ixcatlan',20),
	(1039,'San Jose Chiltepec',20),
	(1040,'San Felipe Jalapa de Diaz',20),
	(1041,'Santa Maria Jacatepec',20),
	(1042,'San Lucas Ojitlan',20),
	(1043,'San Juan Bautista Valle Nacional',20),
	(1044,'San Felipe Usila',20),
	(1045,'Huautla de Jimenez',20),
	(1046,'Santa Maria Chilchotla',20),
	(1047,'Santa Ana Ateixtlahuaca',20),
	(1048,'San Lorenzo Cuaunecuiltitla',20),
	(1049,'San Francisco Huehuetlan',20),
	(1050,'San Pedro Ocopetatillo',20),
	(1051,'Santa Cruz Acatepec',20),
	(1052,'Eloxochitlan de Flores Magon',20),
	(1053,'Santiago Texcalcingo',20),
	(1054,'Teotitlan de Flores Magon',20),
	(1055,'Santa Maria Teopoxco',20),
	(1056,'San Martin Toxpalan',20),
	(1057,'San Jeronimo Tecoatl',20),
	(1058,'Santa Maria la Asuncion',20),
	(1059,'Huautepec',20),
	(1060,'San Juan Coatzospam',20),
	(1061,'San Lucas Zoquiapam',20),
	(1062,'San Antonio Nanahuatipam',20),
	(1063,'San Jose Tenango',20),
	(1064,'San Mateo Yoloxochitlan',20),
	(1065,'San Bartolome Ayautla',20),
	(1066,'Mazatlan Villa de Flores',20),
	(1067,'San Juan de los Cues',20),
	(1068,'Santa Maria Tecomavaca',20),
	(1069,'Santa Maria Ixcatlan',20),
	(1070,'San Juan Bautista Cuicatlan',20),
	(1071,'Cuyamecalco Villa de Zaragoza',20),
	(1072,'Santa Ana Cuauhtemoc',20),
	(1073,'Chiquihuitlan de Benito Juarez',20),
	(1074,'San Pedro Teutila',20),
	(1075,'San Miguel Santa Flor',20),
	(1076,'Santa Maria Tlalixtac',20),
	(1077,'San Andres Teotilalpam',20),
	(1078,'San Francisco Chapulapa',20),
	(1079,'Concepcion Papalo',20),
	(1080,'Santos Reyes Papalo',20),
	(1081,'San Juan Bautista Tlacoatzintepec',20),
	(1082,'Santa Maria Papalo',20),
	(1083,'San Juan Tepeuxila',20),
	(1084,'San Pedro Sochiapam',20),
	(1085,'Valerio Trujano',20),
	(1086,'San Pedro Jocotipac',20),
	(1087,'Santa Maria Texcatitlan',20),
	(1088,'San Pedro Jaltepetongo',20),
	(1089,'Santiago Nacaltepec',20),
	(1090,'Natividad',20),
	(1091,'San Juan Quiotepec',20),
	(1092,'San Pedro Yolox',20),
	(1093,'Santiago Comaltepec',20),
	(1094,'Abejones',20),
	(1095,'San Pablo Macuiltianguis',20),
	(1096,'Ixtlan de Juarez',20),
	(1097,'San Juan Atepec',20),
	(1098,'San Pedro Yaneri',20),
	(1099,'San Miguel Aloapam',20),
	(1100,'Teococuilco de Marcos Perez',20),
	(1101,'Santa Ana Yareni',20),
	(1102,'San Juan Evangelista Analco',20),
	(1103,'Santa Maria Jaltianguis',20),
	(1104,'San Miguel del Rio',20),
	(1105,'San Juan Chicomezuchil',20),
	(1106,'Capulalpam de Mendez',20),
	(1107,'Nuevo Zoquiapam',20),
	(1108,'Santiago Xiacui',20),
	(1109,'Guelatao de Juarez',20),
	(1110,'Santa Catarina Ixtepeji',20),
	(1111,'San Miguel Yotao',20),
	(1112,'Santa Catarina Lachatao',20),
	(1113,'San Miguel Amatlan',20),
	(1114,'Santa Maria Yavesia',20),
	(1115,'Santiago Laxopa',20),
	(1116,'San Ildefonso Villa Alta',20),
	(1117,'Santiago Camotlan',20),
	(1118,'San Juan Yaee',20),
	(1119,'Santiago Lalopa',20),
	(1120,'San Juan Yatzona',20),
	(1121,'Villa Talea de Castro',20),
	(1122,'Tanetze de Zaragoza',20),
	(1123,'San Juan Juquila Vijanos',20),
	(1124,'San Cristobal Lachirioag',20),
	(1125,'Santa Maria Temaxcalapa',20),
	(1126,'Santo Domingo Roayaga',20),
	(1127,'Santa Maria Yalina',20),
	(1128,'San Andres Solaga',20),
	(1129,'San Juan Tabaa',20),
	(1130,'San Melchor Betaza',20),
	(1131,'San Andres Yaa',20),
	(1132,'San Bartolome Zoogocho',20),
	(1133,'San Baltazar Yatzachi el Bajo',20),
	(1134,'Santiago Zoochila',20),
	(1135,'San Francisco Cajonos',20),
	(1136,'San Mateo Cajonos',20),
	(1137,'San Pedro Cajonos',20),
	(1138,'Santo Domingo Xagacia',20),
	(1139,'San Pablo Yaganiza',20),
	(1140,'Santiago Choapam',20),
	(1141,'Santiago Jocotepec',20),
	(1142,'San Juan Lalana',20),
	(1143,'Santiago Yaveo',20),
	(1144,'San Juan Petlapa',20),
	(1145,'San Juan Comaltepec',20),
	(1146,'Heroica Ciudad de Huajuapan de Leon',20),
	(1147,'Santiago Chazumba',20),
	(1148,'Cosoltepec',20),
	(1149,'San Pedro y San Pablo Tequixtepec',20),
	(1150,'San Juan Bautista Suchitepec',20),
	(1151,'Santa Catarina Zapoquila',20),
	(1152,'Santiago Miltepec',20),
	(1153,'San Jeronimo Silacayoapilla',20),
	(1154,'Zapotitlan Palmas',20),
	(1155,'San Andres Dinicuiti',20),
	(1156,'Santiago Cacaloxtepec',20),
	(1157,'Asuncion Cuyotepeji',20),
	(1158,'Santa Maria Camotlan',20),
	(1159,'Santiago Huajolotitlan',20),
	(1160,'Santiago Tamazola',20),
	(1161,'San Juan Cieneguilla',20),
	(1162,'Zapotitlan Lagunas',20),
	(1163,'San Juan Ihualtepec',20),
	(1164,'San Nicolas Hidalgo',20),
	(1165,'Guadalupe de Ramirez',20),
	(1166,'San Andres Tepetlapa',20),
	(1167,'San Miguel Ahuehuetitlan',20),
	(1168,'San Mateo Nejapam',20),
	(1169,'San Juan Bautista Tlachichilco',20),
	(1170,'Tezoatlan de Segura y Luna',20),
	(1171,'Fresnillo de Trujano',20),
	(1172,'Santiago Ayuquililla',20),
	(1173,'San Jose Ayuquila',20),
	(1174,'San Martin Zacatepec',20),
	(1175,'San Miguel Amatitlan',20),
	(1176,'Mariscala de Juarez',20),
	(1177,'Santa Cruz Tacache de Mina',20),
	(1178,'San Simon Zahuatlan',20),
	(1179,'San Marcos Arteaga',20),
	(1180,'San Jorge Nuchita',20),
	(1181,'Santos Reyes Yucuna',20),
	(1182,'Santo Domingo Tonala',20),
	(1183,'Santo Domingo Yodohino',20),
	(1184,'San Juan Bautista Coixtlahuaca',20),
	(1185,'Tepelmeme Villa de Morelos',20),
	(1186,'Concepcion Buenavista',20),
	(1187,'Santiago Ihuitlan Plumas',20),
	(1188,'Tlacotepec Plumas',20),
	(1189,'San Francisco Teopan',20),
	(1190,'Santa Magdalena Jicotlan',20),
	(1191,'San Mateo Tlapiltepec',20),
	(1192,'San Miguel Tequixtepec',20),
	(1193,'San Miguel Tulancingo',20),
	(1194,'Santiago Tepetlapa',20),
	(1195,'San Cristobal Suchixtlahuaca',20),
	(1196,'Santa Maria Nativitas',20),
	(1197,'Silacayoapam',20),
	(1198,'Santiago Yucuyachi',20),
	(1199,'San Lorenzo Victoria',20),
	(1200,'San Agustin Atenango',20),
	(1201,'Calihuala',20),
	(1202,'Santa Cruz de Bravo',20),
	(1203,'Ixpantepec Nieves',20),
	(1204,'San Francisco Tlapancingo',20),
	(1205,'Santiago del Rio',20),
	(1206,'San Pedro y San Pablo Teposcolula',20),
	(1207,'La Trinidad Vista Hermosa',20),
	(1208,'Villa de Tamazulapam del Progreso',20),
	(1209,'San Pedro Nopala',20),
	(1210,'Teotongo',20),
	(1211,'San Antonio Acutla',20),
	(1212,'Villa Tejupam de la Union',20),
	(1213,'Santo Domingo Tonaltepec',20),
	(1214,'Villa de Chilapa de Diaz',20),
	(1215,'San Antonino Monte Verde',20),
	(1216,'San Andres Lagunas',20),
	(1217,'San Pedro Yucunama',20),
	(1218,'San Juan Teposcolula',20),
	(1219,'San Bartolo Soyaltepec',20),
	(1220,'Santiago Yolomecatl',20),
	(1221,'San Sebastian Nicananduta',20),
	(1222,'Santo Domingo Tlatayapam',20),
	(1223,'Santa Maria Nduayaco',20),
	(1224,'San Vicente Nunu',20),
	(1225,'San Pedro Topiltepec',20),
	(1226,'Santiago Nejapilla',20),
	(1227,'Asuncion Nochixtlan',20),
	(1228,'San Miguel Huautla',20),
	(1229,'San Miguel Chicahua',20),
	(1230,'Santa Maria Apazco',20),
	(1231,'Santiago Apoala',20),
	(1232,'Santa Maria Chachoapam',20),
	(1233,'San Pedro Coxcaltepec Cantaros',20),
	(1234,'Santiago Huauclilla',20),
	(1235,'Santo Domingo Yanhuitlan',20),
	(1236,'San Andres Sinaxtla',20),
	(1237,'San Juan Yucuita',20),
	(1238,'San Juan Sayultepec',20),
	(1239,'Santiago Tillo',20),
	(1240,'San Francisco Chindua',20),
	(1241,'San Mateo Etlatongo',20),
	(1242,'Santa Ines de Zaragoza',20),
	(1243,'Santiago Juxtlahuaca',20),
	(1244,'San Miguel Tlacotepec',20),
	(1245,'San Sebastian Tecomaxtlahuaca',20),
	(1246,'Santos Reyes Tepejillo',20),
	(1247,'San Juan Mixtepec -Dto. 08 -',20),
	(1248,'San Martin Peras',20),
	(1249,'Coicoyan de las Flores',20),
	(1250,'Heroica Ciudad de Tlaxiaco',20),
	(1251,'San Juan Numi',20),
	(1252,'San Pedro Martir Yucuxaco',20),
	(1253,'San Martin Huamelulpam',20),
	(1254,'Santa Cruz Tayata',20),
	(1255,'Santiago Nundiche',20),
	(1256,'Santa Maria del Rosario',20),
	(1257,'San Juan Achiutla',20),
	(1258,'Santa Catarina Tayata',20),
	(1259,'San Cristobal Amoltepec',20),
	(1260,'San Miguel Achiutla',20),
	(1261,'San Martin Itunyoso',20),
	(1262,'Magdalena Penasco',20),
	(1263,'San Bartolome Yucuane',20),
	(1264,'Santa Cruz Nundaco',20),
	(1265,'San Agustin Tlacotepec',20),
	(1266,'Santo Tomas Ocotepec',20),
	(1267,'San Antonio Sinicahua',20),
	(1268,'San Mateo Penasco',20),
	(1269,'Santa Maria Tataltepec',20),
	(1270,'San Pedro Molinos',20),
	(1271,'Santa Maria Yosoyua',20),
	(1272,'San Juan Teita',20),
	(1273,'Magdalena Jaltepec',20),
	(1274,'Magdalena Yodocono de Porfirio Diaz',20),
	(1275,'San Miguel Tecomatlan',20),
	(1276,'Magdalena Zahuatlan',20),
	(1277,'San Francisco Nuxano',20),
	(1278,'San Pedro Tidaa',20),
	(1279,'San Francisco Jaltepetongo',20),
	(1280,'Santiago Tilantongo',20),
	(1281,'San Juan Diuxi',20),
	(1282,'San Andres Nuxino',20),
	(1283,'San Juan Tamazola',20),
	(1284,'Santo Domingo Nuxaa',20),
	(1285,'Yutanduchi de Guerrero',20),
	(1286,'San Pedro Teozacoalco',20),
	(1287,'San Miguel Piedras',20),
	(1288,'San Mateo Sindihui',20),
	(1289,'Heroica Ciudad de Juchitan de Zaragoza',20),
	(1290,'Ciudad Ixtepec',20),
	(1291,'El Espinal',20),
	(1292,'Santo Domingo Ingenio',20),
	(1293,'Santa Maria Xadani',20),
	(1294,'Santiago Niltepec',20),
	(1295,'San Dionisio del Mar',20),
	(1296,'Asuncion Ixtaltepec',20),
	(1297,'San Francisco del Mar',20),
	(1298,'Union Hidalgo',20),
	(1299,'San Miguel Chimalapa',20),
	(1300,'Santo Domingo Zanatepec',20),
	(1301,'Reforma de Pineda',20),
	(1302,'San Francisco Ixhuatan',20),
	(1303,'San Pedro Tapanatepec',20),
	(1304,'Chahuites',20),
	(1305,'Santiago Zacatepec',20),
	(1306,'Santo Domingo Tepuxtepec',20),
	(1307,'San Juan Cotzocon',20),
	(1308,'San Juan Mazatlan',20),
	(1309,'Totontepec Villa de Morelos',20),
	(1310,'Mixistlan de la Reforma',20),
	(1311,'Santa Maria Tlahuitoltepec',20),
	(1312,'Santa Maria Alotepec',20),
	(1313,'Santiago Atitlan',20),
	(1314,'Tamazulapam del Espiritu Santo',20),
	(1315,'San Pedro y San Pablo Ayutla',20),
	(1316,'Santa Maria Tepantlali',20),
	(1317,'San Miguel Quetzaltepec',20),
	(1318,'Asuncion Cacalotepec',20),
	(1319,'San Pedro Ocotepec',20),
	(1320,'San Lucas Camotlan',20),
	(1321,'Santiago Ixcuintepec',20),
	(1322,'Matias Romero Avendano',20),
	(1323,'San Juan Guichicovi',20),
	(1324,'Santo Domingo Petapa',20),
	(1325,'Santa Maria Chimalapa',20),
	(1326,'Santa Maria Petapa',20),
	(1327,'El Barrio de la Soledad',20),
	(1328,'Tlacolula de Matamoros',20),
	(1329,'San Sebastian Abasolo',20),
	(1330,'Villa Diaz Ordaz',20),
	(1331,'Santa Maria Guelace',20),
	(1332,'Teotitlan del Valle',20),
	(1333,'San Francisco Lachigolo',20),
	(1334,'San Sebastian Teitipac',20),
	(1335,'Santa Ana del Valle',20),
	(1336,'San Pablo Villa de Mitla',20),
	(1337,'Santiago Matatlan',20),
	(1338,'Santo Domingo Albarradas',20),
	(1339,'Rojas de Cuauhtemoc',20),
	(1340,'San Juan Teitipac',20),
	(1341,'Santa Cruz Papalutla',20),
	(1342,'Magdalena Teitipac',20),
	(1343,'San Jeronimo Tlacochahuaya',20),
	(1344,'San Juan Guelavia',20),
	(1345,'San Lucas Quiavini',20),
	(1346,'San Bartolome Quialana',20),
	(1347,'San Lorenzo Albarradas',20),
	(1348,'San Pedro Totolapam',20),
	(1349,'San Pedro Quiatoni',20),
	(1350,'Santa Maria Zoquitlan',20),
	(1351,'San Dionisio Ocotepec',20),
	(1352,'San Carlos Yautepec',20),
	(1353,'San Juan Juquila Mixes',20),
	(1354,'Nejapa de Madero',20),
	(1355,'Santa Ana Tavela',20),
	(1356,'San Juan Lajarcia',20),
	(1357,'San Bartolo Yautepec',20),
	(1358,'Santa Maria Ecatepec',20),
	(1359,'Asuncion Tlacolulita',20),
	(1360,'San Pedro Martir Quiechapa',20),
	(1361,'Santa Maria Quiegolani',20),
	(1362,'Santa Catarina Quioquitani',20),
	(1363,'Santa Catalina Quieri',20),
	(1364,'Salina Cruz',20),
	(1365,'Santiago Lachiguiri',20),
	(1366,'Santa Maria Jalapa del Marques',20),
	(1367,'Santa Maria Totolapilla',20),
	(1368,'Santiago Laollaga',20),
	(1369,'Guevea de Humboldt',20),
	(1370,'Santo Domingo Chihuitan',20),
	(1371,'Santa Maria Guienagati',20),
	(1372,'Magdalena Tequisistlan',20),
	(1373,'Magdalena Tlacotepec',20),
	(1374,'San Pedro Comitancillo',20),
	(1375,'Santa Maria Mixtequilla',20),
	(1376,'Santo Domingo Tehuantepec',20),
	(1377,'San Pedro Huamelula',20),
	(1378,'San Pedro Huilotepec',20),
	(1379,'San Mateo del Mar',20),
	(1380,'San Blas Atempa',20),
	(1381,'Santiago Astata',20),
	(1382,'San Miguel Tenango',20),
	(1383,'Miahuatlan de Porfirio Diaz',20),
	(1384,'San Nicolas',20),
	(1385,'San Simon Almolongas',20),
	(1386,'San Luis Amatlan',20),
	(1387,'San Jose Lachiguiri',20),
	(1388,'Sitio de Xitlapehua',20),
	(1389,'San Francisco Logueche',20),
	(1390,'Santa Ana',20),
	(1391,'Santa Cruz Xitla',20),
	(1392,'Monjas',20),
	(1393,'San Ildefonso Amatlan',20),
	(1394,'Santa Catarina Cuixtla',20),
	(1395,'San Jose del Penasco',20),
	(1396,'San Cristobal Amatlan',20),
	(1397,'San Juan Mixtepec -Dto. 26 -',20),
	(1398,'San Pedro Mixtepec -Dto. 26 -',20),
	(1399,'Santa Lucia Miahuatlan',20),
	(1400,'San Jeronimo Coatlan',20),
	(1401,'San Sebastian Coatlan',20),
	(1402,'San Pablo Coatlan',20),
	(1403,'San Mateo Rio Hondo',20),
	(1404,'Santo Tomas Tamazulapan',20),
	(1405,'San Andres Paxtlan',20),
	(1406,'Santa Maria Ozolotepec',20),
	(1407,'San Miguel Coatlan',20),
	(1408,'San Sebastian Rio Hondo',20),
	(1409,'San Miguel Suchixtepec',20),
	(1410,'Santo Domingo Ozolotepec',20),
	(1411,'San Francisco Ozolotepec',20),
	(1412,'Santiago Xanica',20),
	(1413,'San Marcial Ozolotepec',20),
	(1414,'San Juan Ozolotepec',20),
	(1415,'San Pedro Pochutla',20),
	(1416,'Santo Domingo de Morelos',20),
	(1417,'Santa Catarina Loxicha',20),
	(1418,'San Agustin Loxicha',20),
	(1419,'San Baltazar Loxicha',20),
	(1420,'Santa Maria Colotepec',20),
	(1421,'San Bartolome Loxicha',20),
	(1422,'Santa Maria Tonameca',20),
	(1423,'Candelaria Loxicha',20),
	(1424,'Pluma Hidalgo',20),
	(1425,'San Pedro el Alto',20),
	(1426,'San Mateo Pinas',20),
	(1427,'Santa Maria Huatulco',20),
	(1428,'San Miguel del Puerto',20),
	(1429,'Putla Villa de Guerrero',20),
	(1430,'Constancia del Rosario',20),
	(1431,'Mesones Hidalgo',20),
	(1432,'Santa Maria Zacatepec',20),
	(1433,'San Pedro Amuzgos',20),
	(1434,'La Reforma',20),
	(1435,'Santa Maria Ipalapa',20),
	(1436,'Chalcatongo de Hidalgo',20),
	(1437,'Santa Maria Yucuhiti',20),
	(1438,'San Esteban Atatlahuca',20),
	(1439,'Santa Catarina Ticua',20),
	(1440,'Santiago Nuyoo',20),
	(1441,'Santa Catarina Yosonotu',20),
	(1442,'San Miguel el Grande',20),
	(1443,'Santo Domingo Ixcatlan',20),
	(1444,'San Pablo Tijaltepec',20),
	(1445,'Santa Cruz Tacahua',20),
	(1446,'Santa Lucia Monteverde',20),
	(1447,'San Andres Cabecera Nueva',20),
	(1448,'Santa Maria Yolotepec',20),
	(1449,'Santiago Yosondua',20),
	(1450,'Santa Cruz Itundujia',20),
	(1451,'Zimatlan de Alvarez',20),
	(1452,'San Bernardo Mixtepec',20),
	(1453,'Santa Cruz Mixtepec',20),
	(1454,'San Miguel Mixtepec',20),
	(1455,'Santa Maria Atzompa',20),
	(1456,'San Andres Ixtlahuaca',20),
	(1457,'Santa Cruz Amilpas',20),
	(1458,'Santa Lucia del Camino',20),
	(1459,'Santa Cruz Xoxocotlan',20),
	(1460,'San Pedro Ixtlahuaca',20),
	(1461,'San Antonio de la Cal',20),
	(1462,'San Agustin de las Juntas',20),
	(1463,'Cuilapam de Guerrero',20),
	(1464,'Animas Trujano',20),
	(1465,'San Sebastian Tutla',20),
	(1466,'San Raymundo Jalpan',20),
	(1467,'Villa de Zaachila',20),
	(1468,'Santa Maria Coyotepec',20),
	(1469,'San Bartolo Coyotepec',20),
	(1470,'Santa Ines Yatzeche',20),
	(1471,'Cienega de Zimatlan',20),
	(1472,'San Antonio Huitepec',20),
	(1473,'Trinidad Zaachila',20),
	(1474,'San Pablo Huixtepec',20),
	(1475,'San Miguel Peras',20),
	(1476,'San Pablo Cuatro Venados',20),
	(1477,'Santa Ines del Monte',20),
	(1478,'Santa Gertrudis',20),
	(1479,'San Antonino el Alto',20),
	(1480,'Magdalena Mixtepec',20),
	(1481,'Santa Catarina Quiane',20),
	(1482,'Ayoquezco de Aldama',20),
	(1483,'Santa Ana Tlapacoyan',20),
	(1484,'Santa Cruz Zenzontepec',20),
	(1485,'San Francisco Cahuacua',20),
	(1486,'Zapotitlan del Rio',20),
	(1487,'Santiago Textitlan',20),
	(1488,'Santiago Amoltepec',20),
	(1489,'Santa Maria Zaniza',20),
	(1490,'Santo Domingo Teojomulco',20),
	(1491,'San Jacinto Tlacotepec',20),
	(1492,'Villa Sola de Vega',20),
	(1493,'Santa Maria Lachixio',20),
	(1494,'San Vicente Lachixio',20),
	(1495,'San Lorenzo Texmelucan',20),
	(1496,'Santa Maria Sola',20),
	(1497,'San Francisco Sola',20),
	(1498,'San Ildefonso Sola',20),
	(1499,'Santiago Minas',20),
	(1500,'Heroica Ciudad de Ejutla de Crespo',20),
	(1501,'San Martin Tilcajete',20),
	(1502,'Santo Tomas Jalieza',20),
	(1503,'San Juan Chilateca',20),
	(1504,'Ocotlan de Morelos',20),
	(1505,'Santa Ana Zegache',20),
	(1506,'Santiago Apostol',20),
	(1507,'San Antonino Castillo Velasco',20),
	(1508,'Asuncion Ocotlan',20),
	(1509,'San Pedro Martir',20),
	(1510,'San Dionisio Ocotlan',20),
	(1511,'Magdalena Ocotlan',20),
	(1512,'San Miguel Tilquiapam',20),
	(1513,'Santa Catarina Minas',20),
	(1514,'San Baltazar Chichicapam',20),
	(1515,'San Pedro Apostol',20),
	(1516,'Santa Lucia Ocotlan',20),
	(1517,'San Jeronimo Taviche',20),
	(1518,'San Andres Zabache',20),
	(1519,'San Jose del Progreso',20),
	(1520,'Yaxe',20),
	(1521,'San Pedro Taviche',20),
	(1522,'San Martin de los Cansecos',20),
	(1523,'San Martin Lachila',20),
	(1524,'La Pe',20),
	(1525,'La Compania',20),
	(1526,'Coatecas Altas',20),
	(1527,'San Juan Lachigalla',20),
	(1528,'San Agustin Amatengo',20),
	(1529,'Taniche',20),
	(1530,'San Miguel Ejutla',20),
	(1531,'Yogana',20),
	(1532,'San Vicente Coatlan',20),
	(1533,'Santiago Pinotepa Nacional',20),
	(1534,'San Juan Cacahuatepec',20),
	(1535,'San Juan Bautista Lo de Soto',20),
	(1536,'Martires de Tacubaya',20),
	(1537,'San Sebastian Ixcapa',20),
	(1538,'San Antonio Tepetlapa',20),
	(1539,'Santa Maria Cortijo',20),
	(1540,'Santiago Llano Grande',20),
	(1541,'San Miguel Tlacamama',20),
	(1542,'Santiago Tapextla',20),
	(1543,'San Jose Estancia Grande',20),
	(1544,'Santo Domingo Armenta',20),
	(1545,'Santiago Jamiltepec',20),
	(1546,'San Pedro Atoyac',20),
	(1547,'San Juan Colorado',20),
	(1548,'Santiago Ixtayutla',20),
	(1549,'San Pedro Jicayan',20),
	(1550,'Pinotepa de Don Luis',20),
	(1551,'San Lorenzo',20),
	(1552,'San Agustin Chayuco',20),
	(1553,'San Andres Huaxpaltepec',20),
	(1554,'Santa Catarina Mechoacan',20),
	(1555,'Santiago Tetepec',20),
	(1556,'Santa Maria Huazolotitlan',20),
	(1557,'Villa de Tututepec de Melchor Ocampo',20),
	(1558,'Tataltepec de Valdes',20),
	(1559,'San Juan Quiahije',20),
	(1560,'San Miguel Panixtlahuaca',20),
	(1561,'Santa Catarina Juquila',20),
	(1562,'San Pedro Juchatengo',20),
	(1563,'Santiago Yaitepec',20),
	(1564,'San Juan Lachao',20),
	(1565,'Santa Maria Temaxcaltepec',20),
	(1566,'Santos Reyes Nopala',20),
	(1567,'San Gabriel Mixtepec',20),
	(1568,'San Pedro Mixtepec -Dto. 22 -',20),
	(1569,'Zaragoza',21),
	(1570,'Pantepec',21),
	(1571,'Ocotepec',21),
	(1572,'Venustiano Carranza',21),
	(1573,'Guadalupe',21),
	(1574,'Guadalupe Victoria',21),
	(1575,'Vicente Guerrero',21),
	(1576,'Eloxochitlan',21),
	(1577,'Huehuetla',21),
	(1578,'Acatlan',21),
	(1579,'Coyotepec',21),
	(1580,'Chiautla',21),
	(1581,'Ahuacatlan',21),
	(1582,'Puebla',21),
	(1583,'Tlaltenango',21),
	(1584,'San Miguel Xoxtla',21),
	(1585,'Juan C. Bonilla',21),
	(1586,'Coronango',21),
	(1587,'Cuautlancingo',21),
	(1588,'San Pedro Cholula',21),
	(1589,'San Andres Cholula',21),
	(1590,'Ocoyucan',21),
	(1591,'Amozoc',21),
	(1592,'Francisco Z. Mena',21),
	(1593,'Jalpan',21),
	(1594,'Tlaxco',21),
	(1595,'Tlacuilotepec',21),
	(1596,'Xicotepec',21),
	(1597,'Pahuatlan',21),
	(1598,'Honey',21),
	(1599,'Naupan',21),
	(1600,'Huauchinango',21),
	(1601,'Ahuazotepec',21),
	(1602,'Juan Galindo',21),
	(1603,'Tlaola',21),
	(1604,'Zihuateutla',21),
	(1605,'Jopala',21),
	(1606,'Tlapacoya',21),
	(1607,'Chignahuapan',21),
	(1608,'Zacatlan',21),
	(1609,'Chiconcuautla',21),
	(1610,'Tepetzintla',21),
	(1611,'San Felipe Tepatlan',21),
	(1612,'Amixtlan',21),
	(1613,'Tepango de Rodriguez',21),
	(1614,'Zongozotla',21),
	(1615,'Hermenegildo Galeana',21),
	(1616,'Olintla',21),
	(1617,'Coatepec',21),
	(1618,'Camocuautla',21),
	(1619,'Hueytlalpan',21),
	(1620,'Zapotitlan de Mendez',21),
	(1621,'Huitzilan de Serdan',21),
	(1622,'Xochitlan de Vicente Suarez',21),
	(1623,'Ixtepec',21),
	(1624,'Atlequizayan',21),
	(1625,'Tenampulco',21),
	(1626,'Tuzamapan de Galeana',21),
	(1627,'Caxhuacan',21),
	(1628,'Jonotla',21),
	(1629,'Zoquiapan',21),
	(1630,'Nauzontla',21),
	(1631,'Cuetzalan del Progreso',21),
	(1632,'Ayotoxco de Guerrero',21),
	(1633,'Hueytamalco',21),
	(1634,'Acateno',21),
	(1635,'Cuautempan',21),
	(1636,'Aquixtla',21),
	(1637,'Tetela de Ocampo',21),
	(1638,'Xochiapulco',21),
	(1639,'Zacapoaxtla',21),
	(1640,'Ixtacamaxtitlan',21),
	(1641,'Zautla',21),
	(1642,'Libres',21),
	(1643,'Teziutlan',21),
	(1644,'Tlatlauquitepec',21),
	(1645,'Yaonahuac',21),
	(1646,'Hueyapan',21),
	(1647,'Teteles de Avila Castillo',21),
	(1648,'Atempan',21),
	(1649,'Chignautla',21),
	(1650,'Xiutetelco',21),
	(1651,'Cuyoaco',21),
	(1652,'Tepeyahualco',21),
	(1653,'San Martin Texmelucan',21),
	(1654,'Tlahuapan',21),
	(1655,'San Matias Tlalancaleca',21),
	(1656,'San Salvador el Verde',21),
	(1657,'San Felipe Teotlalcingo',21),
	(1658,'Chiautzingo',21),
	(1659,'Huejotzingo',21),
	(1660,'Domingo Arenas',21),
	(1661,'Calpan',21),
	(1662,'San Nicolas de los Ranchos',21),
	(1663,'Atlixco',21),
	(1664,'Nealtican',21),
	(1665,'San Jeronimo Tecuanipan',21),
	(1666,'San Gregorio Atzompa',21),
	(1667,'Tochimilco',21),
	(1668,'Tianguismanalco',21),
	(1669,'Santa Isabel Cholula',21),
	(1670,'Huaquechula',21),
	(1671,'San Diego la Mesa Tochimiltzingo',21),
	(1672,'Tepeojuma',21),
	(1673,'Izucar de Matamoros',21),
	(1674,'Atzitzihuacan',21),
	(1675,'Acteopan',21),
	(1676,'Cohuecan',21),
	(1677,'Tepemaxalco',21),
	(1678,'Tlapanala',21),
	(1679,'Tepexco',21),
	(1680,'Tilapa',21),
	(1681,'Chietla',21),
	(1682,'Atzala',21),
	(1683,'Teopantlan',21),
	(1684,'San Martin Totoltepec',21),
	(1685,'Xochiltepec',21),
	(1686,'Epatlan',21),
	(1687,'Ahuatlan',21),
	(1688,'Coatzingo',21),
	(1689,'Santa Catarina Tlaltempan',21),
	(1690,'Chigmecatitlan',21),
	(1691,'Zacapala',21),
	(1692,'Tepexi de Rodriguez',21),
	(1693,'Teotlalco',21),
	(1694,'Jolalpan',21),
	(1695,'Huehuetlan el Chico',21),
	(1696,'Cohetzala',21),
	(1697,'Xicotlan',21),
	(1698,'Chila de la Sal',21),
	(1699,'Ixcamilpa de Guerrero',21),
	(1700,'Albino Zertuche',21),
	(1701,'Tulcingo',21),
	(1702,'Tehuitzingo',21),
	(1703,'Cuayuca de Andrade',21),
	(1704,'Santa Ines Ahuatempan',21),
	(1705,'Axutla',21),
	(1706,'Chinantla',21),
	(1707,'Ahuehuetitla',21),
	(1708,'San Pablo Anicano',21),
	(1709,'Tecomatlan',21),
	(1710,'Piaxtla',21),
	(1711,'Ixcaquixtla',21),
	(1712,'Xayacatlan de Bravo',21),
	(1713,'Totoltepec de Guerrero',21),
	(1714,'San Jeronimo Xayacatlan',21),
	(1715,'San Pedro Yeloixtlahuaca',21),
	(1716,'Petlalcingo',21),
	(1717,'San Miguel Ixitlan',21),
	(1718,'Chila',21),
	(1719,'Rafael Lara Grajales',21),
	(1720,'San Jose Chiapa',21),
	(1721,'Oriental',21),
	(1722,'San Nicolas Buenos Aires',21),
	(1723,'Tlachichuca',21),
	(1724,'Lafragua',21),
	(1725,'Chilchotla',21),
	(1726,'Quimixtlan',21),
	(1727,'Chichiquila',21),
	(1728,'Tepatlaxco de Hidalgo',21),
	(1729,'Acajete',21),
	(1730,'Nopalucan',21),
	(1731,'Mazapiltepec de Juarez',21),
	(1732,'Soltepec',21),
	(1733,'Acatzingo',21),
	(1734,'San Salvador el Seco',21),
	(1735,'General Felipe Angeles',21),
	(1736,'Aljojuca',21),
	(1737,'San Juan Atenco',21),
	(1738,'Tepeaca',21),
	(1739,'Cuautinchan',21),
	(1740,'Tecali de Herrera',21),
	(1741,'Mixtla',21),
	(1742,'Santo Tomas Hueyotlipan',21),
	(1743,'Tzicatlacoyan',21),
	(1744,'Huehuetlan el Grande',21),
	(1745,'La Magdalena Tlatlauquitepec',21),
	(1746,'San Juan Atzompa',21),
	(1747,'Huatlatlauca',21),
	(1748,'Los Reyes de Juarez',21),
	(1749,'Cuapiaxtla de Madero',21),
	(1750,'San Salvador Huixcolotla',21),
	(1751,'Quecholac',21),
	(1752,'Tecamachalco',21),
	(1753,'Palmar de Bravo',21),
	(1754,'Chalchicomula de Sesma',21),
	(1755,'Atzitzintla',21),
	(1756,'Esperanza',21),
	(1757,'Canada Morelos',21),
	(1758,'Tlanepantla',21),
	(1759,'Tochtepec',21),
	(1760,'Atoyatempan',21),
	(1761,'Tepeyahualco de Cuauhtemoc',21),
	(1762,'Huitziltepec',21),
	(1763,'Molcaxac',21),
	(1764,'Xochitlan Todos Santos',21),
	(1765,'Yehualtepec',21),
	(1766,'Tlacotepec de Benito Juarez',21),
	(1767,'Juan N. Mendez',21),
	(1768,'Tehuacan',21),
	(1769,'Tepanco de Lopez',21),
	(1770,'Chapulco',21),
	(1771,'Santiago Miahuatlan',21),
	(1772,'Nicolas Bravo',21),
	(1773,'Atexcal',21),
	(1774,'San Antonio Canada',21),
	(1775,'Zapotitlan',21),
	(1776,'San Gabriel Chilac',21),
	(1777,'Caltepec',21),
	(1778,'Ajalpan',21),
	(1779,'Zoquitlan',21),
	(1780,'San Sebastian Tlacotepec',21),
	(1781,'Altepexi',21),
	(1782,'Zinacatepec',21),
	(1783,'San Jose Miahuatlan',21),
	(1784,'Coxcatlan',21),
	(1785,'Coyomeapan',21),
	(1786,'San Juan del Rio',22),
	(1787,'Toliman',22),
	(1788,'Queretaro',22),
	(1789,'El Marques',22),
	(1790,'Colon',22),
	(1791,'Pinal de Amoles',22),
	(1792,'Jalpan de Serra',22),
	(1793,'Landa de Matamoros',22),
	(1794,'Arroyo Seco',22),
	(1795,'Penamiller',22),
	(1796,'Cadereyta de Montes',22),
	(1797,'San Joaquin',22),
	(1798,'Ezequiel Montes',22),
	(1799,'Pedro Escobedo',22),
	(1800,'Tequisquiapan',22),
	(1801,'Amealco de Bonfil',22),
	(1802,'Corregidora',22),
	(1803,'Huimilpan',22),
	(1804,'Benito Juarez',23),
	(1805,'Lazaro Cardenas',23),
	(1806,'Othon P. Blanco',23),
	(1807,'Felipe Carrillo Puerto',23),
	(1808,'Isla Mujeres',23),
	(1809,'Cozumel',23),
	(1810,'Solidaridad',23),
	(1811,'Tulum',23),
	(1812,'Jose Maria Morelos',23),
	(1813,'Zaragoza',24),
	(1814,'Rayon',24),
	(1815,'Santa Catarina',24),
	(1816,'Villa Hidalgo',24),
	(1817,'Lagunillas',24),
	(1818,'Coxcatlan',24),
	(1819,'San Luis Potosi',24),
	(1820,'Soledad de Graciano Sanchez',24),
	(1821,'Cerro de San Pedro',24),
	(1822,'Ahualulco',24),
	(1823,'Mexquitic de Carmona',24),
	(1824,'Villa de Arriaga',24),
	(1825,'Vanegas',24),
	(1826,'Cedral',24),
	(1827,'Catorce',24),
	(1828,'Charcas',24),
	(1829,'Salinas',24),
	(1830,'Santo Domingo',24),
	(1831,'Villa de Ramos',24),
	(1832,'Matehuala',24),
	(1833,'Villa de la Paz',24),
	(1834,'Villa de Guadalupe',24),
	(1835,'Guadalcazar',24),
	(1836,'Moctezuma',24),
	(1837,'Venado',24),
	(1838,'Villa de Arista',24),
	(1839,'Armadillo de los Infante',24),
	(1840,'Ciudad Valles',24),
	(1841,'Ebano',24),
	(1842,'Tamuin',24),
	(1843,'Ciudad del Maiz',24),
	(1844,'El Naranjo',24),
	(1845,'Alaquines',24),
	(1846,'Cardenas',24),
	(1847,'Cerritos',24),
	(1848,'Villa Juarez',24),
	(1849,'San Nicolas Tolentino',24),
	(1850,'Villa de Reyes',24),
	(1851,'Santa Maria del Rio',24),
	(1852,'Tierra Nueva',24),
	(1853,'Rioverde',24),
	(1854,'Ciudad Fernandez',24),
	(1855,'San Ciro de Acosta',24),
	(1856,'Tamasopo',24),
	(1857,'Aquismon',24),
	(1858,'Tancanhuitz',24),
	(1859,'Tanlajas',24),
	(1860,'San Vicente Tancuayalab',24),
	(1861,'San Antonio',24),
	(1862,'Tanquian de Escobedo',24),
	(1863,'Tampamolon Corona',24),
	(1864,'Huehuetlan',24),
	(1865,'Xilitla',24),
	(1866,'Axtla de Terrazas',24),
	(1867,'Tampacan',24),
	(1868,'San Martin Chalchicuautla',24),
	(1869,'Tamazunchale',24),
	(1870,'Matlapa',24),
	(1871,'Rosario',25),
	(1872,'Culiacan',25),
	(1873,'Navolato',25),
	(1874,'Badiraguato',25),
	(1875,'Cosala',25),
	(1876,'Mocorito',25),
	(1877,'Guasave',25),
	(1878,'Ahome',25),
	(1879,'Salvador Alvarado',25),
	(1880,'Angostura',25),
	(1881,'Choix',25),
	(1882,'El Fuerte',25),
	(1883,'Sinaloa',25),
	(1884,'Mazatlan',25),
	(1885,'Escuinapa',25),
	(1886,'Concordia',25),
	(1887,'Elota',25),
	(1888,'San Ignacio',25),
	(1889,'Rayon',26),
	(1890,'Mazatan',26),
	(1891,'Rosario',26),
	(1892,'Benito Juarez',26),
	(1893,'Magdalena',26),
	(1894,'Villa Hidalgo',26),
	(1895,'Santa Ana',26),
	(1896,'Moctezuma',26),
	(1897,'Hermosillo',26),
	(1898,'San Miguel de Horcasitas',26),
	(1899,'Carbo',26),
	(1900,'San Luis Rio Colorado',26),
	(1901,'Puerto Penasco',26),
	(1902,'General Plutarco Elias Calles',26),
	(1903,'Caborca',26),
	(1904,'Altar',26),
	(1905,'Tubutama',26),
	(1906,'Atil',26),
	(1907,'Oquitoa',26),
	(1908,'Saric',26),
	(1909,'Benjamin Hill',26),
	(1910,'Trincheras',26),
	(1911,'Pitiquito',26),
	(1912,'Nogales',26),
	(1913,'Imuris',26),
	(1914,'Santa Cruz',26),
	(1915,'Naco',26),
	(1916,'Agua Prieta',26),
	(1917,'Fronteras',26),
	(1918,'Nacozari de Garcia',26),
	(1919,'Bavispe',26),
	(1920,'Bacerac',26),
	(1921,'Huachinera',26),
	(1922,'Nacori Chico',26),
	(1923,'Granados',26),
	(1924,'Bacadehuachi',26),
	(1925,'Cumpas',26),
	(1926,'Huasabas',26),
	(1927,'Cananea',26),
	(1928,'Arizpe',26),
	(1929,'Cucurpe',26),
	(1930,'Bacoachi',26),
	(1931,'San Pedro de la Cueva',26),
	(1932,'Divisaderos',26),
	(1933,'Tepache',26),
	(1934,'Villa Pesqueira',26),
	(1935,'Opodepe',26),
	(1936,'Huepac',26),
	(1937,'Banamichi',26),
	(1938,'Ures',26),
	(1939,'Aconchi',26),
	(1940,'Baviacora',26),
	(1941,'San Felipe de Jesus',26),
	(1942,'Cajeme',26),
	(1943,'Navojoa',26),
	(1944,'Huatabampo',26),
	(1945,'Bacum',26),
	(1946,'Etchojoa',26),
	(1947,'Empalme',26),
	(1948,'Guaymas',26),
	(1949,'San Ignacio Rio Muerto',26),
	(1950,'La Colorada',26),
	(1951,'Suaqui Grande',26),
	(1952,'Sahuaripa',26),
	(1953,'San Javier',26),
	(1954,'Soyopa',26),
	(1955,'Bacanora',26),
	(1956,'Arivechi',26),
	(1957,'Quiriego',26),
	(1958,'Onavas',26),
	(1959,'Alamos',26),
	(1960,'Yecora',26),
	(1961,'Emiliano Zapata',27),
	(1962,'Cardenas',27),
	(1963,'Centro',27),
	(1964,'Jalpa de Mendez',27),
	(1965,'Nacajuca',27),
	(1966,'Comalcalco',27),
	(1967,'Huimanguillo',27),
	(1968,'Paraiso',27),
	(1969,'Cunduacan',27),
	(1970,'Macuspana',27),
	(1971,'Centla',27),
	(1972,'Jonuta',27),
	(1973,'Teapa',27),
	(1974,'Jalapa',27),
	(1975,'Tacotalpa',27),
	(1976,'Tenosique',27),
	(1977,'Balancan',27),
	(1978,'Abasolo',28),
	(1979,'Jimenez',28),
	(1980,'Guerrero',28),
	(1981,'Hidalgo',28),
	(1982,'Matamoros',28),
	(1983,'Ocampo',28),
	(1984,'San Fernando',28),
	(1985,'Aldama',28),
	(1986,'Gomez Farias',28),
	(1987,'Camargo',28),
	(1988,'Victoria',28),
	(1989,'Villagran',28),
	(1990,'Bustamante',28),
	(1991,'San Nicolas',28),
	(1992,'Llera',28),
	(1993,'Guemez',28),
	(1994,'Casas',28),
	(1995,'Valle Hermoso',28),
	(1996,'Cruillas',28),
	(1997,'Soto la Marina',28),
	(1998,'San Carlos',28),
	(1999,'Padilla',28),
	(2000,'Mainero',28),
	(2001,'Tula',28),
	(2002,'Jaumave',28),
	(2003,'Miquihuana',28),
	(2004,'Palmillas',28),
	(2005,'Nuevo Laredo',28),
	(2006,'Miguel Aleman',28),
	(2007,'Mier',28),
	(2008,'Gustavo Diaz Ordaz',28),
	(2009,'Reynosa',28),
	(2010,'Rio Bravo',28),
	(2011,'Mendez',28),
	(2012,'Burgos',28),
	(2013,'Tampico',28),
	(2014,'Ciudad Madero',28),
	(2015,'Altamira',28),
	(2016,'Gonzalez',28),
	(2017,'Xicotencatl',28),
	(2018,'El Mante',28),
	(2019,'Antiguo Morelos',28),
	(2020,'Nuevo Morelos',28),
	(2021,'Benito Juarez',29),
	(2022,'Emiliano Zapata',29),
	(2023,'Tenancingo',29),
	(2024,'Lazaro Cardenas',29),
	(2025,'Tlaxco',29),
	(2026,'Tlaxcala',29),
	(2027,'Ixtacuixtla de Mariano Matamoros',29),
	(2028,'Santa Ana Nopalucan',29),
	(2029,'Panotla',29),
	(2030,'Totolac',29),
	(2031,'Tepeyanco',29),
	(2032,'Santa Isabel Xiloxoxtla',29),
	(2033,'San Juan Huactzinco',29),
	(2034,'Calpulalpan',29),
	(2035,'Sanctorum de Lazaro Cardenas',29),
	(2036,'Hueyotlipan',29),
	(2037,'Nanacamilpa de Mariano Arista',29),
	(2038,'Espanita',29),
	(2039,'Apizaco',29),
	(2040,'Atlangatepec',29),
	(2041,'Munoz de Domingo Arenas',29),
	(2042,'Tetla de la Solidaridad',29),
	(2043,'Xaltocan',29),
	(2044,'San Lucas Tecopilco',29),
	(2045,'Yauhquemehcan',29),
	(2046,'Xaloztoc',29),
	(2047,'Tocatlan',29),
	(2048,'Tzompantepec',29),
	(2049,'San Jose Teacalco',29),
	(2050,'Huamantla',29),
	(2051,'Terrenate',29),
	(2052,'Atltzayanca',29),
	(2053,'Cuapiaxtla',29),
	(2054,'El Carmen Tequexquitla',29),
	(2055,'Ixtenco',29),
	(2056,'Ziltlaltepec de Trinidad Sanchez Santos',29),
	(2057,'Apetatitlan de Antonio Carvajal',29),
	(2058,'Amaxac de Guerrero',29),
	(2059,'Santa Cruz Tlaxcala',29),
	(2060,'Cuaxomulco',29),
	(2061,'Contla de Juan Cuamatzi',29),
	(2062,'Tepetitla de Lardizabal',29),
	(2063,'Nativitas',29),
	(2064,'Santa Apolonia Teacalco',29),
	(2065,'Tetlatlahuca',29),
	(2066,'San Damian Texoloc',29),
	(2067,'San Jeronimo Zacualpan',29),
	(2068,'Zacatelco',29),
	(2069,'San Lorenzo Axocomanitla',29),
	(2070,'Santa Catarina Ayometla',29),
	(2071,'Xicohtzinco',29),
	(2072,'Papalotla de Xicohtencatl',29),
	(2073,'Chiautempan',29),
	(2074,'La Magdalena Tlaltelulco',29),
	(2075,'San Francisco Tetlanohcan',29),
	(2076,'Teolocholco',29),
	(2077,'Acuamanala de Miguel Hidalgo',29),
	(2078,'Santa Cruz Quilehtla',29),
	(2079,'Mazatecochco de Jose Maria Morelos',29),
	(2080,'San Pablo del Monte',29),
	(2081,'Zaragoza',30),
	(2082,'Minatitlan',30),
	(2083,'Benito Juarez',30),
	(2084,'Tierra Blanca',30),
	(2085,'Actopan',30),
	(2086,'Acatlan',30),
	(2087,'Emiliano Zapata',30),
	(2088,'Tequila',30),
	(2089,'Magdalena',30),
	(2090,'Tomatlan',30),
	(2091,'Atoyac',30),
	(2092,'Tuxpan',30),
	(2093,'Zacualpan',30),
	(2094,'Jilotepec',30),
	(2095,'Los Reyes',30),
	(2096,'Aquila',30),
	(2097,'Tepetzintla',30),
	(2098,'Coatepec',30),
	(2099,'Acajete',30),
	(2100,'Nogales',30),
	(2101,'Xalapa',30),
	(2102,'Tlalnelhuayocan',30),
	(2103,'Xico',30),
	(2104,'Ixhuacan de los Reyes',30),
	(2105,'Ayahualulco',30),
	(2106,'Perote',30),
	(2107,'Banderilla',30),
	(2108,'Rafael Lucio',30),
	(2109,'Las Vigas de Ramirez',30),
	(2110,'Villa Aldama',30),
	(2111,'Tlacolulan',30),
	(2112,'Tonayan',30),
	(2113,'Coacoatzintla',30),
	(2114,'Naolinco',30),
	(2115,'Miahuatlan',30),
	(2116,'Tepetlan',30),
	(2117,'Juchique de Ferrer',30),
	(2118,'Alto Lucero de Gutierrez Barrios',30),
	(2119,'Teocelo',30),
	(2120,'Cosautlan de Carvajal',30),
	(2121,'Apazapan',30),
	(2122,'Puente Nacional',30),
	(2123,'Ursulo Galvan',30),
	(2124,'Paso de Ovejas',30),
	(2125,'La Antigua',30),
	(2126,'Veracruz',30),
	(2127,'Panuco',30),
	(2128,'Pueblo Viejo',30),
	(2129,'Tampico Alto',30),
	(2130,'Tempoal',30),
	(2131,'Ozuluama de Mascarenas',30),
	(2132,'Tantoyuca',30),
	(2133,'Platon Sanchez',30),
	(2134,'Chiconamel',30),
	(2135,'Chalma',30),
	(2136,'Chontla',30),
	(2137,'Citlaltepetl',30),
	(2138,'Ixcatepec',30),
	(2139,'Naranjos Amatlan',30),
	(2140,'El Higo',30),
	(2141,'Chinampa de Gorostiza',30),
	(2142,'Tantima',30),
	(2143,'Tamalin',30),
	(2144,'Cerro Azul',30),
	(2145,'Tancoco',30),
	(2146,'Tamiahua',30),
	(2147,'Huayacocotla',30),
	(2148,'Ilamatlan',30),
	(2149,'Zontecomatlan de Lopez y Fuentes',30),
	(2150,'Texcatepec',30),
	(2151,'Tlachichilco',30),
	(2152,'Ixhuatlan de Madero',30),
	(2153,'Chicontepec',30),
	(2154,'Alamo Temapache',30),
	(2155,'Tihuatlan',30),
	(2156,'Castillo de Teayo',30),
	(2157,'Cazones de Herrera',30),
	(2158,'Zozocolco de Hidalgo',30),
	(2159,'Chumatlan',30),
	(2160,'Coxquihui',30),
	(2161,'Mecatlan',30),
	(2162,'Filomeno Mata',30),
	(2163,'Coahuitlan',30),
	(2164,'Coyutla',30),
	(2165,'Coatzintla',30),
	(2166,'Espinal',30),
	(2167,'Poza Rica de Hidalgo',30),
	(2168,'Papantla',30),
	(2169,'Gutierrez Zamora',30),
	(2170,'Tecolutla',30),
	(2171,'Martinez de la Torre',30),
	(2172,'San Rafael',30),
	(2173,'Tlapacoyan',30),
	(2174,'Jalacingo',30),
	(2175,'Atzalan',30),
	(2176,'Altotonga',30),
	(2177,'Las Minas',30),
	(2178,'Tatatila',30),
	(2179,'Tenochtitlan',30),
	(2180,'Nautla',30),
	(2181,'Misantla',30),
	(2182,'Landero y Coss',30),
	(2183,'Chiconquiaco',30),
	(2184,'Yecuatla',30),
	(2185,'Colipa',30),
	(2186,'Vega de Alatorre',30),
	(2187,'Jalcomulco',30),
	(2188,'Tlaltetela',30),
	(2189,'Tenampa',30),
	(2190,'Totutla',30),
	(2191,'Sochiapa',30),
	(2192,'Tlacotepec de Mejia',30),
	(2193,'Huatusco',30),
	(2194,'Calcahualco',30),
	(2195,'Alpatlahuac',30),
	(2196,'Coscomatepec',30),
	(2197,'La Perla',30),
	(2198,'Chocaman',30),
	(2199,'Ixhuatlan del Cafe',30),
	(2200,'Tepatlaxco',30),
	(2201,'Comapa',30),
	(2202,'Zentla',30),
	(2203,'Camaron de Tejeda',30),
	(2204,'Soledad de Doblado',30),
	(2205,'Manlio Fabio Altamirano',30),
	(2206,'Jamapa',30),
	(2207,'Medellin',30),
	(2208,'Boca del Rio',30),
	(2209,'Orizaba',30),
	(2210,'Rafael Delgado',30),
	(2211,'Mariano Escobedo',30),
	(2212,'Ixhuatlancillo',30),
	(2213,'Atzacan',30),
	(2214,'Ixtaczoquitlan',30),
	(2215,'Fortin',30),
	(2216,'Cordoba',30),
	(2217,'Maltrata',30),
	(2218,'Rio Blanco',30),
	(2219,'Camerino Z. Mendoza',30),
	(2220,'Acultzingo',30),
	(2221,'Soledad Atzompa',30),
	(2222,'Huiloapan de Cuauhtemoc',30),
	(2223,'Tlaquilpa',30),
	(2224,'Astacinga',30),
	(2225,'Xoxocotla',30),
	(2226,'Atlahuilco',30),
	(2227,'San Andres Tenejapan',30),
	(2228,'Tlilapan',30),
	(2229,'Naranjal',30),
	(2230,'Coetzala',30),
	(2231,'Omealca',30),
	(2232,'Cuitlahuac',30),
	(2233,'Cuichapa',30),
	(2234,'Yanga',30),
	(2235,'Amatlan de los Reyes',30),
	(2236,'Paso del Macho',30),
	(2237,'Carrillo Puerto',30),
	(2238,'Cotaxtla',30),
	(2239,'Zongolica',30),
	(2240,'Tehuipango',30),
	(2241,'Mixtla de Altamirano',30),
	(2242,'Texhuacan',30),
	(2243,'Tezonapa',30),
	(2244,'Tlalixcoyan',30),
	(2245,'Ignacio de la Llave',30),
	(2246,'Alvarado',30),
	(2247,'Lerdo de Tejada',30),
	(2248,'Tres Valles',30),
	(2249,'Carlos A. Carrillo',30),
	(2250,'Cosamaloapan de Carpio',30),
	(2251,'Ixmatlahuacan',30),
	(2252,'Acula',30),
	(2253,'Amatitlan',30),
	(2254,'Tlacotalpan',30),
	(2255,'Saltabarranca',30),
	(2256,'Otatitlan',30),
	(2257,'Tlacojalpan',30),
	(2258,'Tuxtilla',30),
	(2259,'Chacaltianguis',30),
	(2260,'Jose Azueta',30),
	(2261,'Playa Vicente',30),
	(2262,'Santiago Sochiapan',30),
	(2263,'Isla',30),
	(2264,'Juan Rodriguez Clara',30),
	(2265,'San Andres Tuxtla',30),
	(2266,'Santiago Tuxtla',30),
	(2267,'Angel R. Cabada',30),
	(2268,'Hueyapan de Ocampo',30),
	(2269,'Catemaco',30),
	(2270,'Soteapan',30),
	(2271,'Mecayapan',30),
	(2272,'Tatahuicapan de Juarez',30),
	(2273,'Pajapan',30),
	(2274,'Chinameca',30),
	(2275,'Acayucan',30),
	(2276,'San Juan Evangelista',30),
	(2277,'Sayula de Aleman',30),
	(2278,'Oluta',30),
	(2279,'Soconusco',30),
	(2280,'Texistepec',30),
	(2281,'Jaltipan',30),
	(2282,'Oteapan',30),
	(2283,'Cosoleacaque',30),
	(2284,'Nanchital de Lazaro Cardenas del Rio',30),
	(2285,'Ixhuatlan del Sureste',30),
	(2286,'Moloacan',30),
	(2287,'Coatzacoalcos',30),
	(2288,'Agua Dulce',30),
	(2289,'Hidalgotitlan',30),
	(2290,'Jesus Carranza',30),
	(2291,'Las Choapas',30),
	(2292,'Uxpanapa',30),
	(2293,'Progreso',31),
	(2294,'San Felipe',31),
	(2295,'Merida',31),
	(2296,'Chicxulub Pueblo',31),
	(2297,'Ixil',31),
	(2298,'Conkal',31),
	(2299,'Yaxkukul',31),
	(2300,'Hunucma',31),
	(2301,'Ucu',31),
	(2302,'Kinchil',31),
	(2303,'Tetiz',31),
	(2304,'Celestun',31),
	(2305,'Kanasin',31),
	(2306,'Timucuy',31),
	(2307,'Acanceh',31),
	(2308,'Tixpehual',31),
	(2309,'Uman',31),
	(2310,'Telchac Pueblo',31),
	(2311,'Dzemul',31),
	(2312,'Telchac Puerto',31),
	(2313,'Cansahcab',31),
	(2314,'Sinanche',31),
	(2315,'Yobain',31),
	(2316,'Motul',31),
	(2317,'Baca',31),
	(2318,'Mococha',31),
	(2319,'Muxupip',31),
	(2320,'Cacalchen',31),
	(2321,'Bokoba',31),
	(2322,'Tixkokob',31),
	(2323,'Hoctun',31),
	(2324,'Tahmek',31),
	(2325,'Dzidzantun',31),
	(2326,'Temax',31),
	(2327,'Tekanto',31),
	(2328,'Teya',31),
	(2329,'Suma',31),
	(2330,'Tepakan',31),
	(2331,'Tekal de Venegas',31),
	(2332,'Izamal',31),
	(2333,'Hocaba',31),
	(2334,'Xocchel',31),
	(2335,'Seye',31),
	(2336,'Cuzama',31),
	(2337,'Homun',31),
	(2338,'Sanahcat',31),
	(2339,'Huhi',31),
	(2340,'Dzilam Gonzalez',31),
	(2341,'Dzilam de Bravo',31),
	(2342,'Panaba',31),
	(2343,'Buctzotz',31),
	(2344,'Sucila',31),
	(2345,'Cenotillo',31),
	(2346,'Dzoncauich',31),
	(2347,'Tunkas',31),
	(2348,'Quintana Roo',31),
	(2349,'Dzitas',31),
	(2350,'Kantunil',31),
	(2351,'Sudzal',31),
	(2352,'Tekit',31),
	(2353,'Sotuta',31),
	(2354,'Tizimin',31),
	(2355,'Rio Lagartos',31),
	(2356,'Espita',31),
	(2357,'Temozon',31),
	(2358,'Calotmul',31),
	(2359,'Tinum',31),
	(2360,'Chankom',31),
	(2361,'Chichimila',31),
	(2362,'Tixcacalcupul',31),
	(2363,'Kaua',31),
	(2364,'Cuncunul',31),
	(2365,'Tekom',31),
	(2366,'Chemax',31),
	(2367,'Valladolid',31),
	(2368,'Uayma',31),
	(2369,'Maxcanu',31),
	(2370,'Samahil',31),
	(2371,'Opichen',31),
	(2372,'Chochola',31),
	(2373,'Kopoma',31),
	(2374,'Tecoh',31),
	(2375,'Abala',31),
	(2376,'Halacho',31),
	(2377,'Muna',31),
	(2378,'Sacalum',31),
	(2379,'Mani',31),
	(2380,'Dzan',31),
	(2381,'Chapab',31),
	(2382,'Ticul',31),
	(2383,'Oxkutzcab',31),
	(2384,'Santa Elena',31),
	(2385,'Mama',31),
	(2386,'Chumayel',31),
	(2387,'Mayapan',31),
	(2388,'Teabo',31),
	(2389,'Cantamayec',31),
	(2390,'Yaxcaba',31),
	(2391,'Peto',31),
	(2392,'Chikindzonot',31),
	(2393,'Tahdziu',31),
	(2394,'Tixmehuac',31),
	(2395,'Chacsinkin',31),
	(2396,'Tzucacab',31),
	(2397,'Tekax',31),
	(2398,'Akil',31),
	(2399,'Loreto',32),
	(2400,'Morelos',32),
	(2401,'Cuauhtemoc',32),
	(2402,'Guadalupe',32),
	(2403,'Benito Juarez',32),
	(2404,'Villa Hidalgo',32),
	(2405,'Melchor Ocampo',32),
	(2406,'Panuco',32),
	(2407,'Zacatecas',32),
	(2408,'Vetagrande',32),
	(2409,'Concepcion del Oro',32),
	(2410,'Mazapil',32),
	(2411,'El Salvador',32),
	(2412,'Juan Aldama',32),
	(2413,'Miguel Auza',32),
	(2414,'General Francisco R. Murguia',32),
	(2415,'Rio Grande',32),
	(2416,'Villa de Cos',32),
	(2417,'Canitas de Felipe Pescador',32),
	(2418,'Calera',32),
	(2419,'General Enrique Estrada',32),
	(2420,'Trancoso',32),
	(2421,'Genaro Codina',32),
	(2422,'Ojocaliente',32),
	(2423,'General Panfilo Natera',32),
	(2424,'Luis Moya',32),
	(2425,'Villa Gonzalez Ortega',32),
	(2426,'Noria de Angeles',32),
	(2427,'Villa Garcia',32),
	(2428,'Pinos',32),
	(2429,'Fresnillo',32),
	(2430,'Sombrerete',32),
	(2431,'Sain Alto',32),
	(2432,'Valparaiso',32),
	(2433,'Chalchihuites',32),
	(2434,'Jimenez del Teul',32),
	(2435,'Jerez',32),
	(2436,'Monte Escobedo',32),
	(2437,'Susticacan',32),
	(2438,'Villanueva',32),
	(2439,'Tepetongo',32),
	(2440,'El Plateado de Joaquin Amaro',32),
	(2441,'Jalpa',32),
	(2442,'Tabasco',32),
	(2443,'Huanusco',32),
	(2444,'Tlaltenango de Sanchez Roman',32),
	(2445,'Momax',32),
	(2446,'Atolinga',32),
	(2447,'Tepechitlan',32),
	(2448,'Teul de Gonzalez Ortega',32),
	(2449,'Santa Maria de la Paz',32),
	(2450,'Trinidad Garcia de la Cadena',32),
	(2451,'Mezquital del Oro',32),
	(2452,'Nochistlan de Mejia',32),
	(2453,'Apulco',32),
	(2454,'Apozol',32),
	(2455,'Juchipila',32),
	(2456,'Moyahua de Estrada',32),
	(2457,'Villahermosa',27);

/*!40000 ALTER TABLE `municipios` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla netwarelog_version
# ------------------------------------------------------------

DROP TABLE IF EXISTS `netwarelog_version`;

CREATE TABLE `netwarelog_version` (
  `version` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `netwarelog_version` WRITE;
/*!40000 ALTER TABLE `netwarelog_version` DISABLE KEYS */;

INSERT INTO `netwarelog_version` (`version`)
VALUES
	(1.008);

/*!40000 ALTER TABLE `netwarelog_version` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla nomi_deducciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nomi_deducciones`;

CREATE TABLE `nomi_deducciones` (
  `idAgrupador` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `descripcion` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `account_id` int(11) NOT NULL DEFAULT '-1' COMMENT 'cuenta contable',
  PRIMARY KEY (`idAgrupador`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

LOCK TABLES `nomi_deducciones` WRITE;
/*!40000 ALTER TABLE `nomi_deducciones` DISABLE KEYS */;

INSERT INTO `nomi_deducciones` (`idAgrupador`, `clave`, `descripcion`, `account_id`)
VALUES
	(1,'001','Seguridad social ',-1),
	(2,'002','ISR',-1),
	(3,'003','Aportaciones a retiro, cesantía en edad avanzada y vejez',-1),
	(4,'004','Otros',-1),
	(5,'005','Aportaciones a Fondo de vivienda',-1),
	(6,'006','Descuento por incapacidad',-1),
	(7,'007','Pensión alimenticia',-1),
	(8,'008','Renta',-1),
	(9,'009','Préstamos provenientes del Fondo Nacional de la Vivienda para los Trabajadores',-1),
	(10,'010','Pago por crédito de vivienda',-1),
	(11,'011','Pago de abonos INFONACOT',-1),
	(12,'012','Anticipo de salarios',-1),
	(13,'013','Pagos hechos con exceso al trabajador',-1),
	(14,'014','Errores',-1),
	(15,'015','Pérdidas',-1),
	(16,'016','Averías',-1),
	(17,'017','Adquisición de artículos producidos por la empresa o establecimiento',-1),
	(18,'018','Cuotas para la constitución y fomento de sociedades cooperativas y de cajas de ahorro',-1),
	(19,'019','Cuotas sindicales',-1),
	(20,'020','Ausencia (Ausentismo)',-1),
	(21,'021','Cuotas obrero patronales',-1),
	(22,'022','Impuestos Locales',-1),
	(23,'023','Aportaciones voluntarias',-1),
	(24,'024','Ajuste en Gratificación Anual (Aguinaldo) Exento',-1),
	(25,'025','Ajuste en Gratificación Anual (Aguinaldo) Gravado',-1),
	(26,'026','Ajuste en Participación de los Trabajadores en las Utilidades PTU Exento',-1),
	(27,'027','Ajuste en Participación de los Trabajadores en las Utilidades PTU Gravado',-1),
	(28,'028','Ajuste en Reembolso de Gastos Médicos Dentales y Hospitalarios Exento',-1),
	(29,'029','Ajuste en Fondo de ahorro Exento',-1),
	(30,'030','Ajuste en Caja de ahorro Exento',-1),
	(31,'031','Ajuste en Contribuciones a Cargo del Trabajador Pagadas por el Patrón Exento',-1),
	(32,'032','Ajuste en Premios por puntualidad Gravado',-1),
	(33,'033','Ajuste en Prima de Seguro de vida Exento',-1),
	(34,'034','Ajuste en Seguro de Gastos Médicos Mayores Exento',-1),
	(35,'035','Ajuste en Cuotas Sindicales Pagadas por el Patrón Exento',-1),
	(36,'036','Ajuste en Subsidios por incapacidad Exento',-1),
	(37,'037','Ajuste en Becas para trabajadores y/o hijos Exento',-1),
	(38,'038','Ajuste en Horas extra Exento',-1),
	(39,'039','Ajuste en Horas extra Gravado',-1),
	(40,'040','Ajuste en Prima dominical Exento',-1),
	(41,'041','Ajuste en Prima dominical Gravado',-1),
	(42,'042','Ajuste en Prima vacacional Exento',-1),
	(43,'043','Ajuste en Prima vacacional Gravado',-1),
	(44,'044','Ajuste en Prima por antigüedad Exento',-1),
	(45,'045','Ajuste en Prima por antigüedad Gravado',-1),
	(46,'046','Ajuste en Pagos por separación Exento',-1),
	(47,'047','Ajuste en Pagos por separación Gravado',-1),
	(48,'048','Ajuste en Seguro de retiro Exento',-1),
	(49,'049','Ajuste en Indemnizaciones Exento',-1),
	(50,'050','Ajuste en Indemnizaciones Gravado',-1),
	(51,'051','Ajuste en Reembolso por funeral Exento',-1),
	(52,'052','Ajuste en Cuotas de seguridad social pagadas por el patrón Exento',-1),
	(53,'053','Ajuste en Comisiones Gravado',-1),
	(54,'054','Ajuste en Vales de despensa Exento',-1),
	(55,'055','Ajuste en Vales de restaurante Exento',-1),
	(56,'056','Ajuste en Vales de gasolina Exento',-1),
	(57,'057','Ajuste en Vales de ropa Exento',-1),
	(58,'058','Ajuste en Ayuda para renta Exento',-1),
	(59,'059','Ajuste en Ayuda para artículos escolares Exento',-1),
	(60,'060','Ajuste en Ayuda para anteojos Exento',-1),
	(61,'061','Ajuste en Ayuda para transporte Exento',-1),
	(62,'062','Ajuste en Ayuda para gastos de funeral Exento',-1),
	(63,'063','Ajuste en Otros ingresos por salarios Exento',-1),
	(64,'064','Ajuste en Otros ingresos por salarios Gravado',-1),
	(65,'065','Ajuste en Jubilaciones, pensiones o haberes de retiro Exento',-1),
	(66,'066','Ajuste en Jubilaciones, pensiones o haberes de retiro Gravado',-1),
	(67,'067','Ajuste en Pagos por separación Acumulable',-1),
	(68,'068','Ajuste en Pagos por separación No acumulable',-1),
	(69,'069','Ajuste en Jubilaciones, pensiones o haberes de retiro Acumulable',-1),
	(70,'070','Ajuste en Jubilaciones, pensiones o haberes de retiro No acumulable',-1),
	(71,'071','Ajuste en Subsidio para el empleo (efectivamente entregado al trabajador)',-1),
	(72,'072','Ajuste en Ingresos en acciones o títulos valor que representan bienes Exento',-1),
	(73,'073','Ajuste en Ingresos en acciones o títulos valor que representan bienes Gravado',-1),
	(74,'074','Ajuste en Alimentación Exento',-1),
	(75,'075','Ajuste en Alimentación Gravado',-1),
	(76,'076','Ajuste en Habitación Exento',-1),
	(77,'077','Ajuste en Habitación Gravado',-1),
	(78,'078','Ajuste en Premios por asistencia',-1),
	(79,'079','Ajuste en Pagos distintos a los listados y que no deben considerarse como ingreso por sueldos, salar',-1),
	(80,'080','Ajuste en Viáticos no comprobados',-1),
	(81,'081','Ajuste en Viáticos anticipados',-1),
	(82,'082','Ajuste en Fondo de ahorro Gravado',-1),
	(83,'083','Ajuste en Caja de ahorro Gravado',-1),
	(84,'084','Ajuste en Prima de Seguro de vida Gravado',-1),
	(85,'085','Ajuste en Seguro de Gastos Médicos Mayores Gravado',-1),
	(86,'086','Ajuste en Subsidios por incapacidad Gravado',-1),
	(87,'087','Ajuste en Becas para trabajadores y/o hijos Gravado',-1),
	(88,'088','Ajuste en Seguro de retiro Gravado',-1),
	(89,'089','Ajuste en Vales de despensa Gravado',-1),
	(90,'090','Ajuste en Vales de restaurante Gravado',-1),
	(91,'091','Ajuste en Vales de gasolina Gravado',-1),
	(92,'092','Ajuste en Vales de ropa Gravado',-1),
	(93,'093','Ajuste en Ayuda para renta Gravado',-1),
	(94,'094','Ajuste en Ayuda para artículos escolares Gravado',-1),
	(95,'095','Ajuste en Ayuda para anteojos Gravado',-1),
	(96,'096','Ajuste en Ayuda para transporte Gravado',-1),
	(97,'097','Ajuste en Ayuda para gastos de funeral Gravado',-1),
	(98,'098','Ajuste a ingresos asimilados a salarios gravados',-1),
	(99,'099','Ajuste a ingresos por sueldos y salarios gravados',-1),
	(100,'100','Ajuste en Viáticos exentos',-1),
	(101,'OP001','Reintegro de ISR pagado en exceso (siempre que no haya sido enterado al SAT)',-1),
	(102,'OP004','Aplicacion de saldo a favor por compensacion anual',-1),
	(103,'OP002','Subsidio para el empleo (efectivamente entregado al trabajador)',-1);

/*!40000 ALTER TABLE `nomi_deducciones` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla nomi_empleados
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nomi_empleados`;

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
  `nss` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
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
  `activo` tinyint(1) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idEmpleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla nomi_estado_civil
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nomi_estado_civil`;

CREATE TABLE `nomi_estado_civil` (
  `idEstadoCivil` int(11) NOT NULL AUTO_INCREMENT,
  `estadoCivil` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idEstadoCivil`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `nomi_estado_civil` WRITE;
/*!40000 ALTER TABLE `nomi_estado_civil` DISABLE KEYS */;

INSERT INTO `nomi_estado_civil` (`idEstadoCivil`, `estadoCivil`)
VALUES
	(1,'Casad@'),
	(2,'Solter@'),
	(3,'Divorsiad@'),
	(4,'Union Libre');

/*!40000 ALTER TABLE `nomi_estado_civil` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla nomi_percepciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nomi_percepciones`;

CREATE TABLE `nomi_percepciones` (
  `idAgrupador` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `descripcion` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `account_id` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`idAgrupador`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

LOCK TABLES `nomi_percepciones` WRITE;
/*!40000 ALTER TABLE `nomi_percepciones` DISABLE KEYS */;

INSERT INTO `nomi_percepciones` (`idAgrupador`, `clave`, `descripcion`, `account_id`)
VALUES
	(1,'001','Sueldos, Salarios Rayas y Jornales',-1),
	(2,'002','Gratificación Anual (Aguinaldo)',-1),
	(3,'003','Participación de los Trabajadores en las Utilidades PTU',-1),
	(4,'004','Reembolso de Gastos Médicos Dentales y Hospitalarios',-1),
	(5,'005','Fondo de Ahorro',-1),
	(6,'006','Caja de ahorro',-1),
	(7,'009','Contribuciones a Cargo del Trabajador Pagadas por el Patrón',-1),
	(8,'010','Premios por puntualidad',-1),
	(9,'011','Prima de Seguro de vida',-1),
	(10,'012','Seguro de Gastos Médicos Mayores',-1),
	(11,'013','Cuotas Sindicales Pagadas por el Patrón',-1),
	(12,'014','Subsidios por incapacidad',-1),
	(13,'015','Becas para trabajadores y/o hijos',-1),
	(14,'016','Otros',-1),
	(15,'017','Subsidio para el empleo',-1),
	(16,'019','Horas extra',-1),
	(17,'020','Prima dominical ',-1),
	(18,'021','Prima vacacional',-1),
	(19,'022','Prima por antigüedad',-1),
	(20,'023','Pagos por separación',-1),
	(21,'024','Seguro de retiro ',-1),
	(22,'025','Indemnizaciones ',-1),
	(23,'026','Reembolso por funeral',-1),
	(24,'027','Cuotas de seguridad social pagadas por el patrón',-1),
	(25,'028','Comisiones',-1),
	(26,'029','Vales de despensa ',-1),
	(27,'030','Vales de restaurante',-1),
	(28,'031','Vales de gasolina ',-1),
	(29,'032','Vales de ropa',-1),
	(30,'033','Ayuda para renta',-1),
	(31,'034','Ayuda para artículos escolares',-1),
	(32,'035','Ayuda para anteojos',-1),
	(33,'036','Ayuda para transporte',-1),
	(34,'037','Ayuda para gastos de funeral',-1),
	(35,'038','Otros ingresos por salarios',-1),
	(36,'039','Jubilaciones, pensiones o haberes de retiro',-1),
	(37,'044','Jubilaciones, pensiones o haberes de retiro en parcialidades',-1),
	(38,'045','Ingresos en acciones o títulos valor que representan bienes',-1),
	(39,'046','Ingresos asimilados a salarios',-1),
	(40,'047','Alimentación',-1),
	(41,'048','Habitación',-1),
	(42,'049','Premios por asistencia',-1),
	(43,'050','Viáticos',-1),
	(44,'OP001','Reintegro de ISR pagado en exeso (siempre que no haya sido enterado al SAT)',-1),
	(45,'OP002','Subsidio para el empleado (efectivamente entregado al trabajador)',-1),
	(46,'OP003','Viaticos (entregados al trabajador)',-1),
	(47,'OP004','Aplicacion de saldo a favor por compensacion anual',-1),
	(48,'OP999','Pagos distintos a los listados y que no deben considerarse como ingresos por sueldos, salarios o ing',-1);

/*!40000 ALTER TABLE `nomi_percepciones` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla nomi_sexo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nomi_sexo`;

CREATE TABLE `nomi_sexo` (
  `idsexo` int(11) NOT NULL AUTO_INCREMENT,
  `sexo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idsexo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `nomi_sexo` WRITE;
/*!40000 ALTER TABLE `nomi_sexo` DISABLE KEYS */;

INSERT INTO `nomi_sexo` (`idsexo`, `sexo`)
VALUES
	(1,'Femenino'),
	(2,'Masculino');

/*!40000 ALTER TABLE `nomi_sexo` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla nomi_zona
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nomi_zona`;

CREATE TABLE `nomi_zona` (
  `idzona` int(11) NOT NULL AUTO_INCREMENT,
  `zonasalario` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'mexico, gdl y  monterrey ZONA A',
  PRIMARY KEY (`idzona`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `nomi_zona` WRITE;
/*!40000 ALTER TABLE `nomi_zona` DISABLE KEYS */;

INSERT INTO `nomi_zona` (`idzona`, `zonasalario`)
VALUES
	(1,'A');

/*!40000 ALTER TABLE `nomi_zona` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla organizaciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `organizaciones`;

CREATE TABLE `organizaciones` (
  `idorganizacion` int(11) NOT NULL AUTO_INCREMENT,
  `nombreorganizacion` varchar(45) NOT NULL,
  `RFC` varchar(40) DEFAULT NULL,
  `regimen` varchar(50) DEFAULT NULL,
  `domicilio` varchar(200) DEFAULT NULL,
  `idpais` int(11) DEFAULT '1',
  `idestado` int(11) DEFAULT NULL,
  `idmunicipio` varchar(50) DEFAULT NULL,
  `cp` varchar(50) DEFAULT NULL,
  `colonia` varchar(120) DEFAULT NULL,
  `paginaweb` varchar(100) DEFAULT NULL,
  `logoempresa` varchar(255) DEFAULT NULL,
  `tipoinstancia` char(1) DEFAULT '1',
  PRIMARY KEY (`idorganizacion`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `organizaciones` WRITE;
/*!40000 ALTER TABLE `organizaciones` DISABLE KEYS */;

INSERT INTO `organizaciones` (`idorganizacion`, `nombreorganizacion`, `RFC`, `regimen`, `domicilio`, `idpais`, `idestado`, `idmunicipio`, `cp`, `colonia`, `paginaweb`, `logoempresa`, `tipoinstancia`)
VALUES
	(1,'JORGE ENRIQUE FELIPE HERNANDEZ RIVERA','HERE4503211GA','General','TULIPAN 21, COL. TIRO AL BLANCO, C.P. 61154, HIDALGO',1,16,'56','61154','Tiro Al Blanco','-','logo.png','1');

/*!40000 ALTER TABLE `organizaciones` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla paises
# ------------------------------------------------------------

DROP TABLE IF EXISTS `paises`;

CREATE TABLE `paises` (
  `idpais` int(11) NOT NULL AUTO_INCREMENT,
  `pais` varchar(100) DEFAULT NULL,
  `clave` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`idpais`)
) ENGINE=InnoDB AUTO_INCREMENT=248 DEFAULT CHARSET=utf8;

LOCK TABLES `paises` WRITE;
/*!40000 ALTER TABLE `paises` DISABLE KEYS */;

INSERT INTO `paises` (`idpais`, `pais`, `clave`)
VALUES
	(1,'MEXICO (ESTADOS UNIDOS MEXICANOS)','MX'),
	(2,'ESTADOS UNIDOS DE AMERICA','US'),
	(3,'ALEMANIA (REPUBLICA FEDERAL DE)','DE'),
	(4,'ANDORRA (PRINCIPADO DE)','AD'),
	(5,'ANGOLA (REPUBLICA DE)','AO'),
	(6,'ANGUILA','AI'),
	(7,'ANTARTIDA','AQ'),
	(8,'ANTIGUA Y BARBUDA (COMUNIDAD BRITANICA DE NACIONES)','AG'),
	(9,'ANTILLAS NEERLANDESAS (TERRITORIO HOLANDES DE ULTRAMAR)','AN'),
	(10,'ARABIA SAUDITA (REINO DE)','SA'),
	(11,'ARGELIA (REPUBLICA DEMOCRATICA Y POPULAR DE)','DZ'),
	(12,'ARGENTINA (REPUBLICA)','AR'),
	(13,'ARMENIA (REPUBLICA DE)','AM'),
	(14,'ARUBA (TERRITORIO HOLANDES DE ULTRAMAR)','AW'),
	(15,'AUSTRALIA (COMUNIDAD DE)','AU'),
	(16,'AUSTRIA (REPUBLICA DE)','AT'),
	(17,'AZERBAIJAN (REPUBLICA AZERBAIJANI)','AZ'),
	(18,'BAHAMAS (COMUNIDAD DE LAS)','BS'),
	(19,'BAHREIN (ESTADO DE)','BH'),
	(20,'BANGLADESH (REPUBLICA POPULAR DE)','BD'),
	(21,'BARBADOS (COMUNIDAD BRITANICA DE NACIONES)','BB'),
	(22,'BELGICA (REINO DE)','BE'),
	(23,'BELICE','BZ'),
	(24,'BENIN (REPUBLICA DE)','BJ'),
	(25,'BERMUDAS','BM'),
	(26,'BIELORRUSIA (REPUBLICA DE)','BY'),
	(27,'BOLIVIA (REPUBLICA DE)','BO'),
	(28,'BOSNIA Y HERZEGOVINA','BA'),
	(29,'BOTSWANA (REPUBLICA DE)','BW'),
	(30,'BRASIL (REPUBLICA FEDERATIVA DE)','BR'),
	(31,'BRUNEI (ESTADO DE) (RESIDENCIA DE PAZ)','BN'),
	(32,'BULGARIA (REPUBLICA DE)','BG'),
	(33,'BURKINA FASO','BF'),
	(34,'BURUNDI (REPUBLICA DE)','BI'),
	(35,'BUTAN (REINO DE)','BT'),
	(36,'CABO VERDE (REPUBLICA DE)','CV'),
	(37,'CHAD (REPUBLICA DEL)','TD'),
	(38,'CAIMAN (ISLAS)','KY'),
	(39,'CAMBOYA (REINO DE)','KH'),
	(40,'CAMERUN (REPUBLICA DEL)','CM'),
	(41,'CANADA','CA'),
	(42,'CHILE (REPUBLICA DE)','CL'),
	(43,'CHINA (REPUBLICA POPULAR)','CN'),
	(44,'CHIPRE (REPUBLICA DE)','CY'),
	(45,'CIUDAD DEL VATICANO (ESTADO DE LA)','VA'),
	(46,'COCOS (KEELING, ISLAS AUSTRALIANAS)','CC'),
	(47,'COLOMBIA (REPUBLICA DE)','CO'),
	(48,'COMORAS (ISLAS)','KM'),
	(49,'CONGO (REPUBLICA DEL)','CG'),
	(50,'COOK (ISLAS)','CK'),
	(51,'COREA (REPUBLICA POPULAR DEMOCRATICA DE) (COREA DEL NORTE)','KP'),
	(52,'COREA (REPUBLICA DE) (COREA DEL SUR)','KR'),
	(53,'COSTA DE MARFIL (REPUBLICA DE LA)','CI'),
	(54,'COSTA RICA (REPUBLICA DE)','CR'),
	(55,'CROACIA (REPUBLICA DE)','HR'),
	(56,'CUBA (REPUBLICA DE)','CU'),
	(57,'DINAMARCA (REINO DE)','DK'),
	(58,'DJIBOUTI (REPUBLICA DE)','DJ'),
	(59,'DOMINICA (COMUNIDAD DE)','DM'),
	(60,'ECUADOR (REPUBLICA DEL)','EC'),
	(61,'EGIPTO (REPUBLICA ARABE DE)','EG'),
	(62,'EL SALVADOR (REPUBLICA DE)','SV'),
	(63,'EMIRATOS ARABES UNIDOS','AE'),
	(64,'ERITREA (ESTADO DE)','ER'),
	(65,'ESLOVENIA (REPUBLICA DE)','SI'),
	(66,'ESPAÃ‘A (REINO DE)','ES'),
	(67,'ESTADO FEDERADO DE MICRONESIA','FM'),
	(68,'ALBANIA (REPUBLICA DE)','AL'),
	(69,'ESTONIA (REPUBLICA DE)','EE'),
	(70,'ETIOPIA (REPUBLICA DEMOCRATICA FEDERAL)','ET'),
	(71,'FIDJI (REPUBLICA DE)','FJ'),
	(72,'FILIPINAS (REPUBLICA DE LAS)','PH'),
	(73,'FINLANDIA (REPUBLICA DE)','FI'),
	(74,'FRANCIA (REPUBLICA FRANCESA)','FR'),
	(75,'GABONESA (REPUBLICA)','GA'),
	(76,'GAMBIA (REPUBLICA DE LA)','GM'),
	(77,'GEORGIA (REPUBLICA DE)','GE'),
	(78,'GHANA (REPUBLICA DE)','GH'),
	(79,'GIBRALTAR (R.U.)','GI'),
	(80,'GRANADA','GD'),
	(81,'GRECIA (REPUBLICA HELENICA)','GR'),
	(82,'GROENLANDIA (DINAMARCA)','GL'),
	(83,'GUADALUPE (DEPARTAMENTO DE)','GP'),
	(84,'GUAM (E.U.A.)','GU'),
	(85,'GUATEMALA (REPUBLICA DE)','GT'),
	(86,'GUERNSEY','GG'),
	(87,'GUINEA-BISSAU (REPUBLICA DE)','GW'),
	(88,'GUINEA ECUATORIAL (REPUBLICA DE)','GQ'),
	(89,'GUINEA (REPUBLICA DE)','GN'),
	(90,'GUYANA FRANCESA','GF'),
	(91,'GUYANA (REPUBLICA COOPERATIVA DE)','GY'),
	(92,'HAITI (REPUBLICA DE)','HT'),
	(93,'HONDURAS (REPUBLICA DE)','HN'),
	(94,'HONG KONG (REGION ADMINISTRATIVA ESPECIAL DE LA REPUBLICA)','HK'),
	(95,'HUNGRIA (REPUBLICA DE)','HU'),
	(96,'INDIA (REPUBLICA DE)','IN'),
	(97,'INDONESIA (REPUBLICA DE)','ID'),
	(98,'IRAK (REPUBLICA DE)','IQ'),
	(99,'IRAN (REPUBLICA ISLAMICA DEL)','IR'),
	(100,'IRLANDA (REPUBLICA DE)','IE'),
	(101,'ISLANDIA (REPUBLICA DE)','IS'),
	(102,'ISLA BOUVET','BV'),
	(103,'ISLA DE MAN','IM'),
	(104,'ISLAS ALAND','AX'),
	(105,'ISLAS FEROE','FO'),
	(106,'ISLAS GEORGIA Y SANDWICH DEL SUR','GS'),
	(107,'ISLAS HEARD Y MCDONALD','HM'),
	(108,'ISLAS MALVINAS (R.U.)','FK'),
	(109,'ISLAS MARIANAS SEPTENTRIONALES','MP'),
	(110,'ISLAS MARSHALL','MH'),
	(111,'ISLAS MENORES DE ULTRAMAR DE ESTADOS UNIDOS DE AMERICA','UM'),
	(112,'ISLAS SALOMON (COMUNIDAD BRITANICA DE NACIONES)','SB'),
	(113,'ISLAS SVALBARD Y JAN MAYEN (NORUEGA)','SJ'),
	(114,'ISLAS TOKELAU','TK'),
	(115,'ISLAS WALLIS Y FUTUNA','WF'),
	(116,'ISRAEL (ESTADO DE)','IL'),
	(117,'ITALIA (REPUBLICA ITALIANA)','IT'),
	(118,'JAMAICA','JM'),
	(119,'JAPON','JP'),
	(120,'JERSEY','JE'),
	(121,'JORDANIA (REINO HACHEMITA DE)','JO'),
	(122,'KAZAKHSTAN (REPUBLICA DE) ','KZ'),
	(123,'KENYA (REPUBLICA DE)','KE'),
	(124,'KIRIBATI (REPUBLICA DE)','KI'),
	(125,'KUWAIT (ESTADO DE)','KW'),
	(126,'KYRGYZSTAN (REPUBLICA KIRGYZIA)','KG'),
	(127,'LESOTHO (REINO DE)','LS'),
	(128,'LETONIA (REPUBLICA DE)','LV'),
	(129,'LIBANO (REPUBLICA DE)','LB'),
	(130,'LIBERIA (REPUBLICA DE)','LR'),
	(131,'LIBIA (JAMAHIRIYA LIBIA ARABE POPULAR SOCIALISTA)','LY'),
	(132,'LIECHTENSTEIN (PRINCIPADO DE)','LI'),
	(133,'LITUANIA (REPUBLICA DE)','LT'),
	(134,'LUXEMBURGO (GRAN DUCADO DE)','LU'),
	(135,'MACAO','MO'),
	(136,'MACEDONIA (ANTIGUA REPUBLICA YUGOSLAVA DE)','MK'),
	(137,'MADAGASCAR (REPUBLICA DE)','MG'),
	(138,'MALASIA','MY'),
	(139,'MALAWI (REPUBLICA DE)','MW'),
	(140,'MALDIVAS (REPUBLICA DE)','MV'),
	(141,'MALI (REPUBLICA DE)','ML'),
	(142,'MALTA (REPUBLICA DE)','MT'),
	(143,'MARRUECOS (REINO DE)','MA'),
	(144,'MARTINICA (DEPARTAMENTO DE) (FRANCIA)','MQ'),
	(145,'MAURICIO (REPUBLICA DE)','MU'),
	(146,'MAURITANIA (REPUBLICA ISLAMICA DE)','MR'),
	(147,'MAYOTTE','YT'),
	(148,'AFGANISTAN (EMIRATO ISLAMICO DE)','AF'),
	(149,'MOLDAVIA (REPUBLICA DE)','MD'),
	(150,'MONACO (PRINCIPADO DE)','MC'),
	(151,'MONGOLIA','MN'),
	(152,'MONSERRAT (ISLA)','MS'),
	(153,'MONTENEGRO','ME'),
	(154,'MOZAMBIQUE (REPUBLICA DE)','MZ'),
	(155,'MYANMAR (UNION DE)','MM'),
	(156,'NAMIBIA (REPUBLICA DE)','NA'),
	(157,'NAURU','NR'),
	(158,'NAVIDAD (CHRISTMAS) (ISLAS)','CX'),
	(159,'NEPAL (REINO DE)','NP'),
	(160,'NICARAGUA (REPUBLICA DE)','NI'),
	(161,'NIGER (REPUBLICA DE)','NE'),
	(162,'NIGERIA (REPUBLICA FEDERAL DE)','NG'),
	(163,'NIVE (ISLA)','NU'),
	(164,'NORFOLK (ISLA)','NF'),
	(165,'NORUEGA (REINO DE)','NO'),
	(166,'NUEVA CALEDONIA (TERRITORIO FRANCES DE ULTRAMAR)','NC'),
	(167,'NUEVA ZELANDIA','NZ'),
	(168,'OMAN (SULTANATO DE)','OM'),
	(169,'PACIFICO, ISLAS DEL (ADMON. E.U.A.)','PIK'),
	(170,'PAISES BAJOS (REINO DE LOS) (HOLANDA)','NL'),
	(171,'PAKISTAN (REPUBLICA ISLAMICA DE)','PK'),
	(172,'PALAU (REPUBLICA DE)','PW'),
	(173,'PALESTINA','PS'),
	(174,'PANAMA (REPUBLICA DE)','PA'),
	(175,'PAPUA NUEVA GUINEA (ESTADO INDEPENDIENTE DE)','PG'),
	(176,'PARAGUAY (REPUBLICA DEL)','PY'),
	(177,'PERU (REPUBLICA DEL)','PE'),
	(178,'PITCAIRNS (ISLAS DEPENDENCIA BRITANICA)','PN'),
	(179,'POLINESIA FRANCESA','PF'),
	(180,'POLONIA (REPUBLICA DE)','PL'),
	(181,'PORTUGAL (REPUBLICA PORTUGUESA)','PT'),
	(182,'PUERTO RICO (ESTADO LIBRE ASOCIADO DE LA COMUNIDAD DE)','PR'),
	(183,'QATAR (ESTADO DE)','QA'),
	(184,'REINO UNIDO DE LA GRAN BRETAÃ‘A E IRLANDA DEL NORTE','GB'),
	(185,'REPUBLICA CHECA','CZ'),
	(186,'REPUBLICA CENTROAFRICANA','CF'),
	(187,'REPUBLICA DEMOCRATICA POPULAR LAOS','LA'),
	(188,'REPUBLICA DE SERBIA','RS'),
	(189,'REPUBLICA DOMINICANA','DO'),
	(190,'REPUBLICA ESLOVACA','SK'),
	(191,'REPUBLICA POPULAR DEL CONGO','CD'),
	(192,'REPUBLICA RUANDESA','RW'),
	(193,'REUNION (DEPARTAMENTO DE LA) (FRANCIA)','RE'),
	(194,'RUMANIA','RO'),
	(195,'RUSIA (FEDERACION RUSA)','RU'),
	(196,'SAHARA OCCIDENTAL (REPUBLICA ARABE SAHARAVI DEMOCRATICA)','EH'),
	(197,'SAMOA (ESTADO INDEPENDIENTE DE)','WS'),
	(198,'SAMOA AMERICANA','AS'),
	(199,'SAN BARTOLOME','BL'),
	(200,'SAN CRISTOBAL Y NIEVES (FEDERACION DE) (SAN KITTS-NEVIS)','KN'),
	(201,'SAN MARINO (SERENISIMA REPUBLICA DE)','SM'),
	(202,'SAN MARTIN','MF'),
	(203,'SAN PEDRO Y MIQUELON','PM'),
	(204,'SAN VICENTE Y LAS GRANADINAS','VC'),
	(205,'SANTA ELENA','SH'),
	(206,'SANTA LUCIA','LC'),
	(207,'SANTO TOME Y PRINCIPE (REPUBLICA DEMOCRATICA DE)','ST'),
	(208,'SENEGAL (REPUBLICA DEL)','SN'),
	(209,'SEYCHELLES (REPUBLICA DE LAS)','SC'),
	(210,'SIERRA LEONA (REPUBLICA DE)','SL'),
	(211,'SINGAPUR (REPUBLICA DE)','SG'),
	(212,'SIRIA (REPUBLICA ARABE)','SY'),
	(213,'SOMALIA','SO'),
	(214,'SRI LANKA (REPUBLICA DEMOCRATICA SOCIALISTA DE)','LK'),
	(215,'SUDAFRICA (REPUBLICA DE)','ZA'),
	(216,'SUDAN (REPUBLICA DEL)','SD'),
	(217,'SUECIA (REINO DE)','SE'),
	(218,'SUIZA (CONFEDERACION)','CH'),
	(219,'SURINAME (REPUBLICA DE)','SR'),
	(220,'SWAZILANDIA (REINO DE)','SZ'),
	(221,'TADJIKISTAN (REPUBLICA DE)','TJ'),
	(222,'TAILANDIA (REINO DE)','TH'),
	(223,'TAIWAN (REPUBLICA DE CHINA)','TW'),
	(224,'TANZANIA (REPUBLICA UNIDA DE)','TZ'),
	(225,'TERRITORIOS BRITANICOS DEL OCEANO INDICO','IO'),
	(226,'TERRITORIOS FRANCESES, AUSTRALES Y ANTARTICOS','TF'),
	(227,'TIMOR ORIENTAL','TL'),
	(228,'TOGO (REPUBLICA TOGOLESA)','TG'),
	(229,'TONGA (REINO DE)','TO'),
	(230,'TRINIDAD Y TOBAGO (REPUBLICA DE)','TT'),
	(231,'TUNEZ (REPUBLICA DE)','TN'),
	(232,'TURCAS Y CAICOS (ISLAS)','TC'),
	(233,'TURKMENISTAN (REPUBLICA DE)','TM'),
	(234,'TURQUIA (REPUBLICA DE)','TR'),
	(235,'TUVALU (COMUNIDAD BRITANICA DE NACIONES)','TV'),
	(236,'UCRANIA','UA'),
	(237,'UGANDA (REPUBLICA DE)','UG'),
	(238,'URUGUAY (REPUBLICA ORIENTAL DEL)','UY'),
	(239,'UZBEJISTAN (REPUBLICA DE)','UZ'),
	(240,'VANUATU','VU'),
	(241,'VENEZUELA (REPUBLICA DE)','VE'),
	(242,'VIETNAM (REPUBLICA SOCIALISTA DE)','VN'),
	(243,'VIRGENES. ISLAS (BRITANICAS)','VG'),
	(244,'VIRGENES. ISLAS (NORTEAMERICANAS)','VI'),
	(245,'YEMEN (REPUBLICA DE)','YE'),
	(246,'ZAMBIA (REPUBLICA DE)','ZM'),
	(247,'ZIMBABWE (REPUBLICA DE)','ZW');

/*!40000 ALTER TABLE `paises` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla parciallog_caracteristicas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `parciallog_caracteristicas`;

CREATE TABLE `parciallog_caracteristicas` (
  `caracteristica` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`caracteristica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `parciallog_caracteristicas` WRITE;
/*!40000 ALTER TABLE `parciallog_caracteristicas` DISABLE KEYS */;

INSERT INTO `parciallog_caracteristicas` (`caracteristica`)
VALUES
	('Lectura'),
	('Oculto');

/*!40000 ALTER TABLE `parciallog_caracteristicas` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla parciallog_detalle
# ------------------------------------------------------------

DROP TABLE IF EXISTS `parciallog_detalle`;

CREATE TABLE `parciallog_detalle` (
  `idparciallog` int(11) DEFAULT NULL,
  `campo` varchar(50) DEFAULT NULL,
  `caracteristica` varchar(10) DEFAULT NULL,
  `idlinea` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idlinea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla parciallog_titulo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `parciallog_titulo`;

CREATE TABLE `parciallog_titulo` (
  `idparciallog` int(11) NOT NULL AUTO_INCREMENT,
  `idperfil` int(11) NOT NULL,
  `estructura` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idparciallog`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla producto_impuesto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `producto_impuesto`;

CREATE TABLE `producto_impuesto` (
  `idproducto_impuesto` int(11) NOT NULL AUTO_INCREMENT,
  `idProducto` int(11) DEFAULT NULL,
  `idImpuesto` int(11) DEFAULT NULL,
  `valor` float(10,6) DEFAULT NULL,
  PRIMARY KEY (`idproducto_impuesto`),
  KEY `idProducto` (`idProducto`),
  KEY `idImpuesto` (`idImpuesto`),
  CONSTRAINT `producto_impuesto_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `mrp_producto` (`idProducto`),
  CONSTRAINT `producto_impuesto_ibfk_2` FOREIGN KEY (`idImpuesto`) REFERENCES `impuesto` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla prontipagos_configuracion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `prontipagos_configuracion`;

CREATE TABLE `prontipagos_configuracion` (
  `id` int(11) NOT NULL,
  `strUser` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `strPassword` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla prontipagos_productos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `prontipagos_productos`;

CREATE TABLE `prontipagos_productos` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla puestos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `puestos`;

CREATE TABLE `puestos` (
  `idpuesto` int(11) NOT NULL AUTO_INCREMENT,
  `puesto` varchar(100) DEFAULT NULL,
  `cosa` text NOT NULL,
  PRIMARY KEY (`idpuesto`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `puestos` WRITE;
/*!40000 ALTER TABLE `puestos` DISABLE KEYS */;

INSERT INTO `puestos` (`idpuesto`, `puesto`, `cosa`)
VALUES
	(1,'Administrador','');

/*!40000 ALTER TABLE `puestos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla pvt_catalogo_regimen
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pvt_catalogo_regimen`;

CREATE TABLE `pvt_catalogo_regimen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regimen` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

LOCK TABLES `pvt_catalogo_regimen` WRITE;
/*!40000 ALTER TABLE `pvt_catalogo_regimen` DISABLE KEYS */;

INSERT INTO `pvt_catalogo_regimen` (`id`, `regimen`)
VALUES
	(1,'REGIMEN GENERAL'),
	(2,'REGIMEN DE ASALARIADOS'),
	(3,'REGIMEN DE ASIMILADOS A SALARIOS'),
	(4,'REGIMEN DE PEQUEÑOS CONTRIBUYENTES (REPECO)'),
	(5,'REGIMEN INTERMEDIO'),
	(6,'REGIMEN CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES'),
	(7,'REGIMEN DE ARRENDAMIENTO USO O GOCE'),
	(8,'PM CON FINES LUCRATIVOS'),
	(9,'PM CON FINES NO LUCRATIVOS'),
	(10,'PM DEL REGIMEN SIMPLIFICADO'),
	(11,'REGIMEN DE INCORPORACION FISCAL');

/*!40000 ALTER TABLE `pvt_catalogo_regimen` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla pvt_configura_facturacion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pvt_configura_facturacion`;

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
  `pac` int(1) DEFAULT '1',
  `fc_user` varchar(45) DEFAULT NULL,
  `fc_password` varchar(45) DEFAULT NULL,
  `lugar_exp` varchar(100) DEFAULT 'Mexico',
  `pass_ciec` varchar(32) DEFAULT '----',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `pvt_configura_facturacion` WRITE;
/*!40000 ALTER TABLE `pvt_configura_facturacion` DISABLE KEYS */;

INSERT INTO `pvt_configura_facturacion` (`id`, `rfc`, `regimen`, `pais`, `razon_social`, `calle`, `num_ext`, `colonia`, `ciudad`, `municipio`, `estado`, `cp`, `cer`, `llave`, `clave`, `ticket_config`, `pac`, `fc_user`, `fc_password`, `lugar_exp`, `pass_ciec`)
VALUES
	(1,'XXX000000XXX','','','','','','','','','','','','','',1,1,NULL,NULL,'Mexico','----');

/*!40000 ALTER TABLE `pvt_configura_facturacion` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla pvt_contadorFacturas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pvt_contadorFacturas`;

CREATE TABLE `pvt_contadorFacturas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `total` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

LOCK TABLES `pvt_contadorFacturas` WRITE;
/*!40000 ALTER TABLE `pvt_contadorFacturas` DISABLE KEYS */;

INSERT INTO `pvt_contadorFacturas` (`id`, `total`)
VALUES
	(1,0);

/*!40000 ALTER TABLE `pvt_contadorFacturas` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla pvt_notadeCredito
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pvt_notadeCredito`;

CREATE TABLE `pvt_notadeCredito` (
  `idNc` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `total` int(11) DEFAULT NULL,
  `idfac` int(11) DEFAULT NULL,
  PRIMARY KEY (`idNc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Volcado de tabla pvt_pendienteFactura
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pvt_pendienteFactura`;

CREATE TABLE `pvt_pendienteFactura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sale` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `facturado` tinyint(1) DEFAULT NULL,
  `factNum` int(11) DEFAULT '0',
  `cadenaOriginal` text,
  `tipoComp` varchar(1) DEFAULT 'F',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla pvt_respuestaFacturacion
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pvt_respuestaFacturacion`;

CREATE TABLE `pvt_respuestaFacturacion` (
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
  `idComprobante` varchar(45) DEFAULT '0',
  `cadenaOriginal` text,
  `trackid` int(11) DEFAULT '0',
  `proviene` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla pvt_serie_folio
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pvt_serie_folio`;

CREATE TABLE `pvt_serie_folio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serie` varchar(20) NOT NULL,
  `folio` int(11) NOT NULL,
  `serie_f` varchar(20) DEFAULT 'A',
  `folio_r` int(11) DEFAULT '1',
  `serie_nc` varchar(25) DEFAULT 'NC',
  `folio_nc` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `pvt_serie_folio` WRITE;
/*!40000 ALTER TABLE `pvt_serie_folio` DISABLE KEYS */;

INSERT INTO `pvt_serie_folio` (`id`, `serie`, `folio`, `serie_f`, `folio_r`, `serie_nc`, `folio_nc`)
VALUES
	(1,'',1,'A',1,'NC',1);

/*!40000 ALTER TABLE `pvt_serie_folio` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla pvt_targetasRegalo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pvt_targetasRegalo`;

CREATE TABLE `pvt_targetasRegalo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numTargeta` varchar(25) DEFAULT NULL,
  `value` double DEFAULT NULL,
  `deleted` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla repolog_campos_subtotales
# ------------------------------------------------------------

DROP TABLE IF EXISTS `repolog_campos_subtotales`;

CREATE TABLE `repolog_campos_subtotales` (
  `idreporte` int(11) NOT NULL,
  `idcampogrupo` int(11) NOT NULL,
  `idcamposubtotal` int(11) NOT NULL,
  PRIMARY KEY (`idreporte`,`idcampogrupo`,`idcamposubtotal`),
  KEY `repolog_reportes_campos_subtotales` (`idreporte`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla repolog_campos_totales
# ------------------------------------------------------------

DROP TABLE IF EXISTS `repolog_campos_totales`;

CREATE TABLE `repolog_campos_totales` (
  `idreporte` int(11) NOT NULL,
  `idcampo` varchar(50) NOT NULL,
  PRIMARY KEY (`idreporte`,`idcampo`),
  KEY `repolog_reportes_campos_totales` (`idreporte`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla repolog_estilos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `repolog_estilos`;

CREATE TABLE `repolog_estilos` (
  `idestilo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idestilo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `repolog_estilos` WRITE;
/*!40000 ALTER TABLE `repolog_estilos` DISABLE KEYS */;

INSERT INTO `repolog_estilos` (`idestilo`, `nombre`)
VALUES
	(1,'Ejecutivo');

/*!40000 ALTER TABLE `repolog_estilos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla repolog_filtros_solicitar
# ------------------------------------------------------------

DROP TABLE IF EXISTS `repolog_filtros_solicitar`;

CREATE TABLE `repolog_filtros_solicitar` (
  `idfiltro` int(11) NOT NULL AUTO_INCREMENT,
  `idreporte` int(11) NOT NULL,
  `operadorlogico` varchar(10) DEFAULT NULL,
  `idcampo` varchar(50) DEFAULT NULL,
  `operadorcomp` varchar(10) DEFAULT NULL,
  `etiqueta` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idfiltro`,`idreporte`),
  KEY `repolog_reportes_filtros_solicitar` (`idreporte`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla repolog_reportes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `repolog_reportes`;

CREATE TABLE `repolog_reportes` (
  `idreporte` int(11) NOT NULL AUTO_INCREMENT,
  `nombrereporte` varchar(50) DEFAULT NULL,
  `descripcion` varchar(80) DEFAULT NULL,
  `fechacreacion` datetime DEFAULT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `estatus` char(1) DEFAULT NULL,
  `idestiloomision` int(11) DEFAULT NULL,
  `sql_select` text,
  `sql_from` text,
  `sql_where` text,
  `sql_groupby` text,
  `sql_having` text,
  `sql_orderby` text,
  `url_include` text,
  `url_include_despues` text,
  `subtotales_agrupaciones` text,
  `subtotales_funciones` text,
  `subtotales_subtotal` text,
  PRIMARY KEY (`idreporte`),
  KEY `repolog_reportes_estilos` (`idestiloomision`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

LOCK TABLES `repolog_reportes` WRITE;
/*!40000 ALTER TABLE `repolog_reportes` DISABLE KEYS */;

INSERT INTO `repolog_reportes` (`idreporte`, `nombrereporte`, `descripcion`, `fechacreacion`, `fechamodificacion`, `estatus`, `idestiloomision`, `sql_select`, `sql_from`, `sql_where`, `sql_groupby`, `sql_having`, `sql_orderby`, `url_include`, `url_include_despues`, `subtotales_agrupaciones`, `subtotales_funciones`, `subtotales_subtotal`)
VALUES
	(1,'DiarioVentas','Diario Ventas','2012-11-23 23:06:29','2013-04-23 01:45:27','A',1,'s.Sucursal,vt.fecha,c.razonsocial Cliente,p.nombreproducto Producto,p.UnidadMedida,format(vd.preciounitario,2) PrecioUnitario, format(vd.cantidad,2) Cantidad, format(vd.subtotal,2) SubTotal, format((0.16*vd.subtotal),2) Impuestos, format(vd.subtotal*1.16,2) Total, format(vd.subtotal-(p.costo*vd.cantidad),2) \"Utilidad\", concat(e.nombre,\" \",e.apellido1,\" \",e.apellido2) Empleado','ventas_titulo vt \r\nLEFT JOIN ventas_detalle vd ON vt.idventa=vd.idventa\r\nLEFT JOIN productos p ON p.idproducto=vd.idproducto\r\nLEFT JOIN sucursales s ON s.idsucursal=vt.idsucursal\r\nLEFT JOIN vista_empleados ve ON ve.idempleado=vt.idempleado\r\nLEFT JOIN clientes c ON c.idcliente=vt.idcliente\r\nLeft Join empleados e on e.idempleado=vt.idempleado','vt.fecha between \"[#Del] 00:00:00\" and \"[#Al] 23:59:59\"\r\nAnd concat(e.nombre,e.apellido1,e.apellido2) Like \"%[Empleado]%\"','','','vt.fecha,s.sucursal,c.razonsocial,p.nombreproducto','','','Sucursal','suma(Cantidad), suma(Impuestos),\r\nsuma(SubTotal),suma(Total), suma(Utilidad)','Sucursal'),
	(2,'ExistenciasCorte','Existencias del Dia','2012-12-17 19:23:43','2013-04-23 01:47:07','A',1,'al.Almacen, p.nombreproducto Producto, il.descripcionlote Lote, ep.descripcion Estado, format(im.cantidad,2) Existencia','inventarios_saldos im\r\nLEFT JOIN productos p ON p.idproducto=im.idproducto\r\nLEFT JOIN inventarios_lotes il ON il.idlote=im.idlote\r\nLEFT JOIN inventarios_estadosproducto ep ON ep.idestadoproducto=im.idestadoproducto\r\nLEFT JOIN almacenes al ON al.idalmacen=im.idalmacen','al.almacen like \"%[Almacen]%\" And \r\np.nombreproducto like \"%[Producto]%\"\r\n','','','','','','Almacen','suma(Existencia)','Almacen'),
	(3,'DetalladoExistencias','Detallado Movimientos Inventario','2012-12-17 21:00:07','2012-12-17 21:00:07','G',1,'','','','','','','','','','',''),
	(4,'estatusventas','Estatus Ventas','2013-04-23 01:24:11','2013-09-03 18:44:41','A',1,'ev.nombre Estatus, s.Sucursal,vt.fecha,c.razonsocial Cliente,p.nombreproducto Producto,p.UnidadMedida,format(vd.preciounitario,2) PrecioUnitario, format(vd.cantidad,2) Cantidad, format(vd.subtotal,2) SubTotal, format((0.16*vd.subtotal),2) Impuestos, format(vd.subtotal*1.16,2) Total, format(vd.subtotal-(p.costo*vd.cantidad),2) \"Utilidad\", concat(e.nombre,\" \",e.apellido1,\" \",e.apellido2) Empleado\r\n','ventas_titulo vt \r\nLEFT JOIN ventas_detalle vd ON vt.idventa=vd.idventa\r\nLEFT JOIN productos p ON p.idproducto=vd.idproducto\r\nLEFT JOIN sucursales s ON s.idsucursal=vt.idsucursal\r\nLEFT JOIN vista_empleados ve ON ve.idempleado=vt.idempleado\r\nLEFT JOIN clientes c ON c.idcliente=vt.idcliente\r\nLeft Join empleados e on e.idempleado=vt.idempleado\r\nLeft Join crm_estadosventa ev on ev.idestadoventa=vt.idestadoventa','vt.fecha between \"[#Del] 00:00:00\" and \"[#Al] 23:59:59\"\r\nAnd concat(e.nombre,e.apellido1,e.apellido2) Like \"%[Empleado]%\"','','','','','','Estatus','suma(Cantidad), suma(SubTotal), suma(Impuestos), suma(Total), suma(Utilidad), ','Estatus'),
	(5,'ActividadesVendedores','Actividades Vendedor','2013-05-09 13:09:07','2013-05-09 13:09:29','A',1,'e.nombre Vendedor, c.nombre Actividad, a.descripcion, fecha','crm_registroactividadescomerciales a \r\ninner join vista_empleados e on a.idempleado=e.idempleado\r\ninner join crm_actividades c on c.idactividad=a.idactividad','e.nombre like \"%[Vendedor]%\"\r\nand a.fecha between \"[#Del] 00:00:00\" and \"[#Al] 23:59:59\"','','','','','','','',''),
	(6,'programacionentregas','Programación Entregas','2013-05-09 14:20:46','2013-10-26 16:06:16','G',1,'vt.fecha \"FechaVenta\", vt.FechaEntrega, cl.razonsocial \"Cliente\", cl.Domicilio, \r\nes.Estado, mu.Municipio,pr.nombre, sum(vd.cantidad) Toneladas\r\n','ventas_titulo vt \r\ninner join ventas_detalle vd on vt.idventa=vd.idventa\r\ninner join clientes cl on cl.idcliente=vt.idcliente\r\ninner join estados es on es.idestado=cl.idestado\r\ninner join municipios mu on mu.idmunicipio=cl.idmunicipio\r\ninner join productos pr on pr.idproducto=vt.idproducto\r\n','','','','','','','','',''),
	(7,'ReporteCuentasPorCobrar','Reporte Cuentas Por Cobrar','2013-05-16 01:30:27','2013-09-03 19:29:33','A',1,'d.nombredeudor Deudor, format(a.saldoinicial,2) SaldoInicial, format(IFNULL(a.abonos,0),2) Abonos, format(saldoactual,2) SaldoActual ','admin_cxc a \r\nleft join admin_deudores d on d.iddeudor=a.iddeudor\r\n','a.saldoactual>0 AND a.activo=-1','','','d.nombredeudor','','','Deudor','suma(SaldoActual)','Deudor'),
	(8,'EstadoCuentaClientes','Estado de Cuenta Clientes','2013-05-16 01:36:10','2013-05-16 01:36:10','G',1,'d.nombredeudor Cliente, \r\nfoliodocumento Factura, fechacargo Fecha, format(saldoinicial,2) Importe,\r\nif(datediff(CURRENT_DATE(),ADDDATE(fechacargo,v.diascredito))>0&&datediff(CURRENT_DATE(),ADDDATE(fechacargo,v.diascredito))<=29,format(saldoactual,2),0) \"X Vencer\",\r\nif(datediff(CURRENT_DATE(),ADDDATE(fechacargo,v.diascredito))>29&&60>datediff(CURRENT_DATE(),ADDDATE(fechacargo,v.diascredito)),format(saldoactual,2),0) \"30 dias\",\r\nif(datediff(CURRENT_DATE(),ADDDATE(fechacargo,v.diascredito))>59&&90>datediff(CURRENT_DATE(),ADDDATE(fechacargo,v.diascredito)),format(saldoactual,2),0) \"60 dias\",\r\nif(datediff(CURRENT_DATE(),ADDDATE(fechacargo,v.diascredito))>89&&91>datediff(CURRENT_DATE(),ADDDATE(fechacargo,v.diascredito)),format(saldoactual,2),0) \"90 dias\",\r\nif(datediff(CURRENT_DATE(),ADDDATE(fechacargo,v.diascredito))>90,format(saldoactual,2),0) \"+90 dias\"','admin_cxc c\r\nLeft Join (admin_deudores d Inner Join catalog_estructuras e On e.idestructura = d.idestructura\r\n  Inner Join (ventas_complementoclientes cm\r\n    Inner join ventas_condicionesventas v On v.idcondicionventa = cm.idcondicionventa)\r\n  On cm.idcomplementocliente = d.idclaveorigen)\r\nOn d.iddeudor = c.iddeudor\r\nLeft Join resultados_conceptos r On r.idconcepto = c.idconcepto\r\nLeft Join configuracion_documentosprocesos p ON p.iddocumento = c.iddocumento','( fechacargo between \"[#Fecha Cargo Del] 12:00:00\" And \"[#Al] 23:59:59\") And ( d.nombredeudor Like \"%[Deudor]%\" Or r.concepto Like \"%[Concepto]%\" Or saldoinicial Like \"%[Saldo Inicial]%\" Or saldoactual Like \"%[Saldo Actual]%\")  And (p.nombredocumento LIKE \"Facturacion\") And (saldoactual > 0) Group By foliodocumento','','','','','','','',''),
	(9,'ReporteCuentasPorPagar','Reporte Cuentas Por Pagar','2013-05-16 12:05:54','2013-11-20 15:50:55','A',1,'d.nombreacreedor Acreedor, format(sum(a.saldoinicial),2) SaldoInicial, format(sum(a.abonos),2) Abonos, format(sum(saldoactual),2) SaldoActual ','admin_cxp a \r\nleft join rest_admin_acreedores d on d.idacreedor=a.idacreedor\r\n','a.saldoactual>0\r\n','','','d.nombreacreedor','','','Acreedor','suma(SaldoActual)','Acreedor'),
	(10,'ListadoFacturas','Listado Facturas','2013-10-11 18:02:36','2013-10-21 20:31:28','A',1,'a.id, a.folio as Folio_fiscal, a.cadenaOriginal as Numero_folio, if(a.tipoComp=\"C\" and nc.total is not null and nc.total !=\"\", nc.total,c.monto) as Pago, d.nombre as Nombre, b.rfc, a.borrado as Cancelada, a.tipoComp as Tipo, a.proviene as Origen','pvt_respuestaFacturacion a left join comun_facturacion b on a.idFact=b.id left join venta c on c.idVenta=a.idSale left join comun_cliente d on d.id=c.idCliente left join pvt_notadeCredito nc on nc.idfac=a.id','(a.borrado=[@Estatus factura;pBorrado;pDesc;select \"0 OR a.borrado is not null\" as \"pBorrado\", \"Todas\" as \"pDesc\" union all select 0 as \"pBorrado\", \"Activas\" as \"pDesc\" union all select 1 as \"pBorrado\", \"Canceladas\" as \"pDesc\" union all select 2 as \"pBorrado\", \"Anuladas\" as \"pDesc\"]) AND a.tipoComp=\"[@Tipo de comprobante;tTipo;tDesc;select \"F\" as \"tTipo\", \"Factura CFDI\" as \"tDesc\" union all select \"C\" as \"tTipo\", \"Nota de credito\" as \"tDesc\"]\" and DATE_FORMAT(a.fecha,\"%Y-%m-%d\")>=\"[#Fecha inicio]\" and DATE_FORMAT(a.fecha,\"%Y-%m-%d\")<=\"[#Fecha fin]\"','','','a.id desc','../../modulos/facturacion/reporteFacturas.php','','','',''),
	(11,'ReportedePolizas','REporte de polizas','2013-10-11 23:32:16','2013-10-15 16:31:10','G',1,'*','cont_movimientos','','','','','','','','',''),
	(12,'cont_balance_sumas_saldos','Balance de Sumas y Saldos','2013-10-15 15:48:59','2013-10-23 17:18:19','G',1,' DISTINCT\r\ncont_accounts.account_nature AS Naturaleza,\r\ncont_accounts.description AS Cuenta,\r\n(SELECT SUM(Importe) FROM cont_movimientos x WHERE x.Cuenta = c.Cuenta AND TipoMovto = \"Cargo\" AND x.idPoliza = c.idPoliza) AS Cargos,\r\n(SELECT SUM(Importe) FROM cont_movimientos x WHERE x.Cuenta = c.Cuenta AND TipoMovto = \"Abono\" AND x.idPoliza = c.idPoliza) AS Abonos','cont_movimientos c\r\nINNER JOIN cont_accounts ON c.Cuenta = cont_accounts.account_id','c.IdPoliza =  [@Poliza;id;concepto;SELECT id,concepto FROM cont_polizas WHERE activo = 1;]  \r\nAND c.Activo = 0','','','','','../../modulos/cont_repolog/cont_repo_sumas_saldos.php','','suma(Abonos),suma(Cargos),suma(Saldo Acreedor)suma(Saldo Deudor)',''),
	(13,'cont_libro_mayor','Libro de Mayor','2013-10-23 16:46:47','2013-10-23 16:46:47','G',1,'n.description AS Naturaleza,\r\np.fecha AS Fecha,\r\np.concepto AS Poliza,\r\na.description AS Cuenta,\r\nif(TipoMovto = \"Cargo\",Importe, 0) AS Cargos,\r\nif(TipoMovto = \"Abono\",Importe, 0) AS Abonos','cont_movimientos m \r\nINNER JOIN cont_accounts a ON m.Cuenta = a.account_id  \r\nINNER JOIN cont_nature n ON a.account_nature = n.nature_id\r\nINNER JOIN cont_polizas p ON m.idPoliza = p.id AND p.fecha BETWEEN \"[#Fecha Inicial]\" AND \"[#Fecha Final]\"','m.Activo = 1','','','Cuenta,Fecha','','../../modulos/cont_repolog/cont_libro_mayor.php','Cuenta','suma(Abonos),suma(Cargos)','Cuenta'),
	(14,'cont_ej_ant','Libro de Mayor','2013-10-23 21:01:39','2013-10-23 22:59:51','G',1,'*','cont_movimientos ','[@Ejercicio;val;des;SELECT id as val, NombreEjercicio AS des FROM cont_ejercicios] > 0\nAND [@Periodo Inicial;val;des; select \"01\" AS val , \"Enero\" AS des UNION select \"02\" AS val , \"Febrero\" AS des UNION select \"03\" AS val , \"Marzo\" AS des UNION select \"04\" AS val , \"Abril\" AS des UNION select \"05\" AS val , \"Mayo\" AS des UNION select \"06\" AS val , \"Junio\" AS des UNION select \"07\" AS val , \"Julio\" AS des UNION select \"08\" AS val , \"Agosto\" AS des UNION select \"09\" AS val , \"Septiembre\" AS des UNION select \"10\" AS val , \"Octubre\" AS des UNION select \"11\" AS val , \"Noviembre\" AS des UNION select \"12\" AS val , \"Diciembre\" AS des] > 0 \nAND [@Periodo Final;val;des; select \"01\" AS val , \"Enero\" AS des UNION select \"02\" AS val , \"Febrero\" AS des UNION select \"03\" AS val , \"Marzo\" AS des UNION select \"04\" AS val , \"Abril\" AS des UNION select \"05\" AS val , \"Mayo\" AS des UNION select \"06\" AS val , \"Junio\" AS des UNION select \"07\" AS val , \"Julio\" AS des UNION select \"08\" AS val , \"Agosto\" AS des UNION select \"09\" AS val , \"Septiembre\" AS des UNION select \"10\" AS val , \"Octubre\" AS des UNION select \"11\" AS val , \"Noviembre\" AS des UNION select \"12\" AS val , \"Diciembre\" AS des] > 0 \nAND [@A nivel de:;val;des;SELECT \"1\" AS val , \"Cuentas Afectables\" AS des UNION SELECT \"2\" AS val, \"Cuentas de Mayor\" AS des] \nAND [@Cierre del ejercicio:;nc;dc;SELECT \"0\" AS nc , \"No\" AS dc UNION SELECT \"1\" AS nc, \"Si\" AS dc] >=0','','','','../../modulos/cont_repolog/test.php','../../modulos/cont_repolog/test_cont_libro_mayor.php','Clasificacion,Cuenta_de_Mayor,Cuenta','suma(Abonos),suma(Cargos)','Cuenta'),
	(15,'reporteagenda','Reporte de la Agenda','2013-10-25 17:55:04','2013-10-25 18:10:56','G',1,'nombre, estado','agendaLog a inner join estados e on a.idestado = e.idestado','a.idestado = [@Estado;idestado;estado;select * from estados]','','','nombre','','','','',''),
	(16,'Listado Facturas (REST)','Listado Facturas (REST)','2013-10-27 02:41:09','2013-10-27 02:45:38','A',1,'a.id, a.folio as Folio_fiscal, a.factNum as Numero_folio, c.payment_type as Pago, CONCAT(d.first_name,\" \",d.last_name) as Nombre, b.rfc, a.borrado as Cancelada, a.tipoComp as Tipo','rest_pvt_respuestaFacturacion a\r\nleft join rest_pvt_datosFacturacion b on a.idFact=b.id\r\nleft join rest_sales c on c.sale_id=a.idSale\r\nleft join rest_people d on d.person_id=c.customer_id','(a.borrado=[@Estatus factura;pBorrado;pDesc;select \"0 OR a.borrado is not null\" as \"pBorrado\", \"Todas\" as \"pDesc\" union all select 0 as \"pBorrado\", \"Activas\" as \"pDesc\" union all select 1 as \"pBorrado\", \"Canceladas\" as \"pDesc\" union all select 2 as \"pBorrado\", \"Anuladas\" as \"pDesc\"]) AND a.tipoComp=\"[@Tipo de comprobante;tTipo;tDesc;select \"F\" as \"tTipo\", \"Factura CFDI\" as \"tDesc\" union all select \"C\" as \"tTipo\", \"Nota de credito\" as \"tDesc\"]\" and a.fecha>=\"[#Fecha inicio]\" and a.fecha<=\"[#Fecha fin]\"','','','a.id desc','../../modulos/facturacionRest/reporteFacturas.php','','','',''),
	(17,'cont_EstadoResultados','Estado de Resultados','2013-10-28 16:19:11','2013-12-09 18:15:01','A',1,'*','cont_polizas','idejercicio = [@Ejercicio;val;des;SELECT Id as val, NombreEjercicio AS des FROM cont_ejercicios] \nAND\nidperiodo = [@Periodo;val;des; select \'1\' AS val , \'Enero\' AS des UNION select \'2\' AS val , \'Febrero\' AS des UNION select \'3\' AS val , \'Marzo\' AS des UNION select \'4\' AS val , \'Abril\' AS des UNION select \'5\' AS val , \'Mayo\' AS des UNION select \'6\' AS val , \'Junio\' AS des UNION select \'7\' AS val , \'Julio\' AS des UNION select \'8\' AS val , \'Agosto\' AS des UNION select \'9\' AS val , \'Septiembre\' AS des UNION select \'10\' AS val , \'Octubre\' AS des UNION select \'11\' AS val , \'Noviembre\' AS des UNION select \'12\' AS val , \'Diciembre\' AS des] AND idsucursal = [@Sucursal;val;des;SELECT \'0\' AS val, \'Todas\' AS des UNION SELECT idSuc AS val, nombre AS des FROM mrp_sucursal] AND idsegmento = [@Segmento_de_Negocio;val;des;SELECT \'0\' AS val, \'Todos\' AS des UNION SELECT idSuc AS val, nombre AS des FROM cont_segmentos]','Cuenta_de_Mayor','','Tipo ASC,code','../../modulos/cont_repolog/resultados_antes.php','../../modulos/cont_repolog/resultados_despues.php','Tipo,Grupo','','Tipo'),
	(18,'prueba_sql','Esta es una descripcin','2013-10-28 17:05:08','2013-10-28 17:36:11','G',1,'-- FUNCION NO VALIDA--hola mundo','-- FUNCION NO VALIDA--hola mundo','a = 0','','','','','','','',''),
	(19,'cont_PolizasImpresas','Polizas Impresas','2013-10-28 17:29:27','2013-11-01 19:05:03','A',1,'m.NumMovto AS NUM_MOVIMIENTO,a.manual_code AS \'Codigo manual\',p.fecha AS FECHA,a.description AS CUENTA,CONCAT(p.numpol,\'/\',p.Concepto) AS POLIZA,(select titulo From cont_tipos_poliza WHERE id=p.idtipopoliza) AS TIPO_POLIZA,m.Referencia AS REFERENCIA_MOV,m.Concepto AS CONCEPTO_MOV,  (select format(Importe,2) From cont_movimientos WHERE TipoMovto=\'Cargo\' AND Id = m.Id) AS CARGO, (select format(Importe,2) From cont_movimientos WHERE TipoMovto=\'Abono\' AND Id = m.Id) AS ABONO','cont_movimientos m INNER JOIN cont_polizas p ON p.id = m.IdPoliza INNER JOIN cont_accounts a ON a.account_id = m.Cuenta','p.fecha BETWEEN \'[#Del]\' AND \'[#Al]\' AND p.activo = 1 AND m.Activo = 1 AND p.idperiodo != 13','','','FECHA,TIPO_POLIZA,numpol,NUM_MOVIMIENTO','../../modulos/cont_repolog/polizas_antes.php','../../modulos/cont_repolog/polizas_despues.php','POLIZA,TIPO_POLIZA,FECHA','suma(ABONO),suma(CARGO)','POLIZA'),
	(20,'prueba','asdfasdfsda','2013-11-04 23:35:07','2013-11-04 23:35:07','G',1,'asdfsdf','asdf','','','','','','','','',''),
	(21,'cont_catalogo_cuentas','Catalogo de Cuentas','2013-11-05 00:34:22','2013-12-09 19:18:40','A',1,'(SELECT codigo_agrupador FROM cont_diarioficial WHERE id = cuentaoficial) AS CodigoOficial,a.manual_code AS CodigoManual,a.description AS Nombre,t.description AS Clasificacion,(CASE WHEN a.account_code LIKE \'1%\' THEN \'Activo\' WHEN a.account_code LIKE \'2%\' THEN \'Pasivo\' WHEN a.account_code LIKE \'3%\' THEN \'Capital\' WHEN a.account_code LIKE \'4%\' THEN \'Resultados\' WHEN a.account_code LIKE \'5%\' THEN \'De Orden\' END) AS TipoCuenta, n.description AS Naturaleza, (CASE WHEN affectable = 1 THEN \'Si\' WHEN affectable = 0 THEN \'No\' END) AS Afectable','cont_accounts a INNER JOIN cont_nature n ON n.nature_id = a.account_nature INNER JOIN cont_main_type t ON t.type_id = a.main_account','a.account_nature=[@Naturaleza;nature_id;description;SELECT \"0\" AS nature_id, \"Todas\" AS description UNION SELECT nature_id,description FROM cont_nature;] AND t.type_id=[@Tipo;type_id;description; SELECT \"0\" AS type_id, \"Todas\" AS description UNION SELECT type_id,description FROM cont_main_type;] AND a.removed = 0   ','','','CodigoManual','../../modulos/cont_repolog/catalogocuentas.php','','','',''),
	(22,'cont_balance_general','Balance General','2013-11-08 00:00:54','2013-12-09 18:22:13','A',1,'*','cont_polizas','idejercicio = [@Ejercicio;val;des;SELECT Id as val, NombreEjercicio AS des FROM cont_ejercicios] \r\nAND\r\nidperiodo = [@Periodo;val;des; select \"1\" AS val , \"Enero\" AS des UNION select \"2\" AS val , \"Febrero\" AS des UNION select \"3\" AS val , \"Marzo\" AS des UNION select \"4\" AS val , \"Abril\" AS des UNION select \"5\" AS val , \"Mayo\" AS des UNION select \"6\" AS val , \"Junio\" AS des UNION select \"7\" AS val , \"Julio\" AS des UNION select \"8\" AS val , \"Agosto\" AS des UNION select \"9\" AS val , \"Septiembre\" AS des UNION select \"10\" AS val , \"Octubre\" AS des UNION select \"11\" AS val , \"Noviembre\" AS des UNION select \"12\" AS val , \"Diciembre\" AS des]\r\n','Cuenta_de_Mayor','','Tipo ASC,code','../../modulos/cont_repolog/balance_antes.php','../../modulos/cont_repolog/balance_despues.php','Tipo,Grupo','','Tipo'),
	(23,'scrumlog_reporte','Reporte de Proyectos','2013-11-09 00:13:58','2013-11-11 17:35:16','A',1,'','','','','','','','','','',''),
	(24,'TRT Reporte OS','TRT Reporte OS','2013-11-14 11:49:25','2013-11-21 23:10:07','A',1,'a.id, b.nombre, IF(a.id_facturacion=0,\"XXXX0000XXXX\", c.rfc) rfc, g.nombre unidad, i.estatus, e.origen, f.destino, a.observaciones, a.fecha_alta, a.fecha_salida, a.contacto, a.tipo_carga, a.telefono, a.total_km, a.total_lt, a.total','trt_os_detalle a\r\nleft join comun_cliente b on a.id_cliente=b.id\r\nleft join pvt_facturacion c on c.idFactura=a.id_facturacion\r\nleft join trt_rutas d on d.id=a.id_ruta\r\nleft join vista_trt_origen e on e.id=d.origen\r\nleft join vista_trt_destinos f on f.id=d.destino\r\nleft join trt_unidades g on g.id=a.id_unidad\r\nleft join trt_os h on h.id_detalle=a.id\r\nleft join trt_estatus i on i.id=h.estatus','','','','a.id desc','','','','',''),
	(25,'TRT Reporte Rutas','TRT Reporte Rutas','2013-11-14 16:46:53','2013-11-14 18:35:54','A',1,'e.origen, f.destino, count(a.id_ruta) viajes, SUM(a.total_km) kms, SUM(a.total_lt) litros, SUM(a.total) total','trt_os_detalle a\r\nleft join trt_rutas d on d.id=a.id_ruta\r\nleft join vista_trt_origen e on e.id=d.origen\r\nleft join vista_trt_destinos f on f.id=d.destino','','a.id_ruta','','a.id_cliente desc','','','','',''),
	(26,'TRT Reporte Pendiente','TRT Reporte Pendiente','2013-11-14 17:57:18','2013-11-14 18:23:17','A',1,'a.id, b.nombre, IF(a.id_facturacion=0,\"XXXX0000XXXX\", c.rfc) rfc, g.nombre unidad, i.estatus, e.origen, f.destino, a.observaciones, a.fecha_alta, a.fecha_salida, a.contacto, a.tipo_carga, a.telefono, a.total_km, a.total_lt, a.total','trt_os_detalle a\r\nleft join comun_cliente b on a.id_cliente=b.id\r\nleft join pvt_facturacion c on c.idFactura=a.id_facturacion\r\nleft join trt_rutas d on d.id=a.id_ruta\r\nleft join vista_trt_origen e on e.id=d.origen\r\nleft join vista_trt_destinos f on f.id=d.destino\r\nleft join trt_unidades g on g.id=a.id_unidad\r\nleft join trt_os h on h.id_detalle=a.id\r\nleft join trt_estatus i on i.id=h.estatus','i.id=1','','','','','','','',''),
	(27,'TRT Reporte Cancelado','TRT Reporte Cancelado','2013-11-14 17:57:51','2013-11-14 18:29:22','A',1,'a.id, b.nombre, IF(a.id_facturacion=0,\"XXXX0000XXXX\", c.rfc) rfc, g.nombre unidad, i.estatus, e.origen, f.destino, a.observaciones, a.fecha_alta, a.fecha_salida, a.contacto, a.tipo_carga, a.telefono, a.total_km, a.total_lt, a.total','trt_os_detalle a\r\nleft join comun_cliente b on a.id_cliente=b.id\r\nleft join pvt_facturacion c on c.idFactura=a.id_facturacion\r\nleft join trt_rutas d on d.id=a.id_ruta\r\nleft join vista_trt_origen e on e.id=d.origen\r\nleft join vista_trt_destinos f on f.id=d.destino\r\nleft join trt_unidades g on g.id=a.id_unidad\r\nleft join trt_os h on h.id_detalle=a.id\r\nleft join trt_estatus i on i.id=h.estatus','i.id=4','','','','','','','',''),
	(28,'TRT Reporte Entregado','TRT Reporte Entregado','2013-11-14 17:58:18','2013-11-14 18:30:43','A',1,'a.id, b.nombre, IF(a.id_facturacion=0,\"XXXX0000XXXX\", c.rfc) rfc, g.nombre unidad, i.estatus, e.origen, f.destino, a.observaciones, a.fecha_alta, a.fecha_salida, a.contacto, a.tipo_carga, a.telefono, a.total_km, a.total_lt, a.total','trt_os_detalle a\r\nleft join comun_cliente b on a.id_cliente=b.id\r\nleft join pvt_facturacion c on c.idFactura=a.id_facturacion\r\nleft join trt_rutas d on d.id=a.id_ruta\r\nleft join vista_trt_origen e on e.id=d.origen\r\nleft join vista_trt_destinos f on f.id=d.destino\r\nleft join trt_unidades g on g.id=a.id_unidad\r\nleft join trt_os h on h.id_detalle=a.id\r\nleft join trt_estatus i on i.id=h.estatus','i.id=3','','','','','','','',''),
	(29,'TRT Reportes Fact','TRT Reportes Fact','2013-11-15 14:55:13','2013-11-23 00:34:16','A',1,'a.id, a.folio as Folio_fiscal, a.factNum as Numero_folio, c.nombre, IF(b.rfc=\"\", \"XXXX0000XXXX\", b.rfc) RFC, a.borrado as Cancelada, a.tipoComp as Tipo','pvt_respuestaFacturacion a\r\ninner join pvt_facturacion b on a.idFact=b.idFactura\r\ninner join comun_cliente c on c.id=b.id','a.idOs<>0','','','a.id desc','../../modulos/transportes/archivos reportes/reporteFacturas.php','','','',''),
	(30,'TRT Reportes Dia','TRT Reportes Dia','2013-11-15 16:48:36','2013-11-15 17:37:52','A',1,'a.id, b.nombre, IF(a.id_facturacion=0,\"XXXX0000XXXX\", c.rfc) rfc, g.nombre unidad, i.estatus, e.origen, f.destino, a.observaciones, a.fecha_alta, a.fecha_salida, a.contacto, a.tipo_carga, a.telefono, a.total_km, a.total_lt, a.total','trt_os_detalle a\r\nleft join comun_cliente b on a.id_cliente=b.id\r\nleft join pvt_facturacion c on c.idFactura=a.id_facturacion\r\nleft join trt_rutas d on d.id=a.id_ruta\r\nleft join vista_trt_origen e on e.id=d.origen\r\nleft join vista_trt_destinos f on f.id=d.destino\r\nleft join trt_unidades g on g.id=a.id_unidad\r\nleft join trt_os h on h.id_detalle=a.id\r\nleft join trt_estatus i on i.id=h.estatus','a.fecha_alta>=CURRENT_DATE','','','a.id desc','','','','',''),
	(31,'TRT Reportes Total','TRT Reportes Total','2013-11-15 17:29:51','2013-11-21 23:28:52','A',1,'\"Total de ventas: \" Mensage, SUM(a.total) total','trt_os_detalle a','','','','','','','','',''),
	(32,'cont_balanza_comp','Contabilidad - Balaza de comprobacion','2013-11-20 12:26:46','2013-11-22 19:42:12','G',1,'*','cont_movimientos ','[@Ejercicio;val;des;SELECT id as val, NombreEjercicio AS des FROM cont_ejercicios] > 0\r\nAND [@Periodo Inicial;val;des; select \"01\" AS val , \"Enero\" AS des UNION select \"02\" AS val , \"Febrero\" AS des UNION select \"03\" AS val , \"Marzo\" AS des UNION select \"04\" AS val , \"Abril\" AS des UNION select \"05\" AS val , \"Mayo\" AS des UNION select \"06\" AS val , \"Junio\" AS des UNION select \"07\" AS val , \"Julio\" AS des UNION select \"08\" AS val , \"Agosto\" AS des UNION select \"09\" AS val , \"Septiembre\" AS des UNION select \"10\" AS val , \"Octubre\" AS des UNION select \"11\" AS val , \"Noviembre\" AS des UNION select \"12\" AS val , \"Diciembre\" AS des] > 0 \r\nAND [@Periodo Final;val;des; select \"01\" AS val , \"Enero\" AS des UNION select \"02\" AS val , \"Febrero\" AS des UNION select \"03\" AS val , \"Marzo\" AS des UNION select \"04\" AS val , \"Abril\" AS des UNION select \"05\" AS val , \"Mayo\" AS des UNION select \"06\" AS val , \"Junio\" AS des UNION select \"07\" AS val , \"Julio\" AS des UNION select \"08\" AS val , \"Agosto\" AS des UNION select \"09\" AS val , \"Septiembre\" AS des UNION select \"10\" AS val , \"Octubre\" AS des UNION select \"11\" AS val , \"Noviembre\" AS des UNION select \"12\" AS val , \"Diciembre\" AS des] > 0 \r\nAND [@A nivel de:;val;des;SELECT \"1\" AS val , \"Cuentas Afectables\" AS des UNION SELECT \"1\" AS val, \"Cuentas de Mayor\" AS des]','','','','../../modulos/cont_repolog/balanza_antes.php','../../modulos/cont_repolog/balanza_despues.php','Clasificacion,Cuenta_de_Mayor,Cuenta','suma(Abonos),suma(Cargos)','Cuenta'),
	(33,'restReporteCuentasPorCobrar','Reporte Cuentas Por Cobrar','2013-11-20 15:53:59','2013-11-20 15:53:59','G',1,'d.nombredeudor Deudor, format(a.saldoinicial,2) SaldoInicial, format(IFNULL(a.abonos,0),2) Abonos, format(saldoactual,2) SaldoActual ','rest_admin_cxc a \r\nleft join rest_admin_deudores d on d.iddeudor=a.iddeudor','a.saldoactual>0 AND a.activo=-1','','','d.nombredeudor','','','Deudor','suma(SaldoActual)','Deudor'),
	(34,'ReporteCuentasPorPagar','Reporte Cuentas Por Pagar','2013-11-20 15:55:47','2013-11-21 22:26:13','D',1,'d.nombreacreedor Acreedor, format(sum(a.saldoinicial),2) SaldoInicial, format(sum(a.abonos),2) Abonos, format(sum(saldoactual),2) SaldoActual ','admin_cxp a \r\nleft join admin_acreedores d on d.idacreedor=a.idacreedor\r\n','a.saldoactual>0\r\n','','','d.nombreacreedor','','','Acreedor','suma(SaldoActual)','Acreedor'),
	(35,'RestReporteCuentasPorPagar','Rest Reporte Cuentas Por Pagar','2013-11-21 22:20:39','2013-11-21 22:27:04','A',1,'d.nombreacreedor Acreedor, format(a.saldoinicial,2) SaldoInicial, format(IFNULL(a.abonos,0),2) Abonos, format(saldoactual,2) SaldoActual ','rest_admin_cxp a \r\nleft join rest_admin_acreedores d on d.idacreedor=a.idacreedor\r\n','a.saldoactual>0 AND a.activo=-1','','','d.nombreacreedor','','','Acreedor','suma(SaldoActual)','Acreedor'),
	(36,'TRT Reporte pagos transportistas','TRT Reporte pagos transportistas','2013-11-21 23:41:07','2013-11-29 00:21:09','A',1,'a.id, a.id_os Folio, b.transport Nombre, count(*) as Total_viajes,  FORMAT(SUM(a.monto),2) as Total_pagado','trt_pagos_transportistas a\r\ninner join trt_transportistas b on b.id=a.transport','a.efectuado=-1','a.transport','','','','','','',''),
	(37,'TRT Egresos por comisiones','TRT Egresos por comisiones','2013-11-21 23:44:04','2013-11-28 23:15:35','A',1,'COUNT(*) as Ventas, FORMAT(SUM(a.monto),2) as Total','trt_pagos_transportistas a','a.efectuado=-1','','','','','','','',''),
	(38,'reporte_ventas_rutas_ms','Por ruta','2013-11-22 17:55:56','2013-11-22 19:26:51','A',1,'\r\nCONCAT(o.cantidad,\" \",u.compuesto,\" \",p.nombre) Promocion,\r\nro.nombre  Ruta, \r\nCONCAT(ut.tipo,\" \",tum.marca,\" \",cu.capacidad,\" placas:\",tu.placas) Transporte_ruta,\r\nCONCAT(\"$\",o.precio) as Precio_promocion,\r\nSUM(lrc.cantidadentregada) Cantidad_vendida,\r\nCONCAT(\"$\",SUM(lrc.cantidadentregada*o.precio)) as Subtotal\r\n\r\n','sms_liquidacion_ruta_cliente lrc,\r\nsms_ruta_oferta_cliente roc,\r\nsms_oferta_cliente oc ,\r\nsms_oferta o,\r\nsms_ruta_oferta ro,\r\ntrt_unidades tu,\r\ntrt_unidad_marca tum,\r\ntrt_unidad_tipo ut,\r\ntrt_capacidad_unidad cu,\r\ncomun_cliente cc,\r\nmrp_producto p,\r\nmrp_unidades u','u.idUni=o.idUnidad AND\r\np.idProducto=o.idProducto AND\r\ncc.id=oc.idCliente AND\r\nroc.id=lrc.idRutaOfertaCliente  AND\r\nroc.idOfertacliente=oc.id AND\r\no.idOferta=oc.idOferta AND \r\nro.id=roc.idRutaOferta AND\r\ntu.id=ro.idTransporte AND\r\ntum.id=tu.marca AND\r\nut.id=tu.tipo AND cu.id=tu.capacidad ','roc.idRutaOferta ','',' o.fechaCreacion desc\r\n','','','','','Subtotal'),
	(39,'reporte_ventas_oferta_sms','Por oferta','2013-11-22 19:29:34','2013-11-22 19:32:24','A',1,'o.fechaCreacion  Fecha_Creacion,\r\nCONCAT(o.cantidad,\" \",u.compuesto,\" \",p.nombre) Promocion,\r\nCONCAT(\"$\",o.precio) as Precio_promocion,\r\nSUM(lrc.cantidadentregada) Cantidad_vendida,\r\nCONCAT(\"$\",SUM(lrc.cantidadentregada*o.precio)) as Subtotal','sms_liquidacion_ruta_cliente lrc,\r\nsms_ruta_oferta_cliente roc,\r\nsms_oferta_cliente oc ,\r\nsms_oferta o,\r\nsms_ruta_oferta ro,\r\ntrt_unidades tu,\r\ntrt_unidad_marca tum,\r\ntrt_unidad_tipo ut,\r\ntrt_capacidad_unidad cu,\r\ncomun_cliente cc,\r\nmrp_producto p,\r\nmrp_unidades u','u.idUni=o.idUnidad AND\r\np.idProducto=o.idProducto AND\r\ncc.id=oc.idCliente AND\r\nroc.id=lrc.idRutaOfertaCliente  AND\r\nroc.idOfertacliente=oc.id AND\r\no.idOferta=oc.idOferta AND \r\nro.id=roc.idRutaOferta AND\r\ntu.id=ro.idTransporte AND\r\ntum.id=tu.marca AND\r\nut.id=tu.tipo AND cu.id=tu.capacidad ','oc.idOferta ','','o.fechaCreacion desc','','','','','Subtotal'),
	(40,'reporte_ventas_clientes_sms','Por cliente','2013-11-22 19:50:07','2013-11-22 19:58:13','A',1,'CONCAT(cc.nombretienda,\"-\",cc.nombre) Cliente,\r\ng.nombre Giro,\r\nr.nombre Rubro,\r\nSUM(lrc.cantidadentregada) Cantidad_comprada_de_promociones,\r\nCONCAT(\"$\",SUM(lrc.cantidadentregada*o.precio)) as Subtotal','sms_liquidacion_ruta_cliente lrc,\r\nsms_ruta_oferta_cliente roc,\r\nsms_oferta_cliente oc ,\r\nsms_oferta o,\r\nsms_ruta_oferta ro,\r\ntrt_unidades tu,\r\ntrt_unidad_marca tum,\r\ntrt_unidad_tipo ut,\r\ntrt_capacidad_unidad cu,\r\ncomun_cliente cc,\r\nmrp_producto p,\r\nmrp_unidades u,\r\nsms_giro g,\r\nsms_rubro r','r.idRubro=cc.idRubro AND\r\ng.idGiro=cc.idGiro AND\r\nu.idUni=o.idUnidad AND\r\np.idProducto=o.idProducto AND\r\ncc.id=oc.idCliente AND\r\nroc.id=lrc.idRutaOfertaCliente  AND\r\nroc.idOfertacliente=oc.id AND\r\no.idOferta=oc.idOferta AND \r\nro.id=roc.idRutaOferta AND\r\ntu.id=ro.idTransporte AND\r\ntum.id=tu.marca AND\r\nut.id=tu.tipo AND cu.id=tu.capacidad ','cc.id ','','o.fechaCreacion desc','','','','','Subtotal'),
	(41,'reporte_ventas_transportista_sms','Por transporte','2013-11-22 20:10:10','2013-11-22 22:06:44','A',1,'CONCAT(ut.tipo,\" \",tum.marca,\" \",cu.capacidad,\" placas:\",tu.placas) Transporte_ruta,\r\ntt.transport Transportista,\r\nSUM(lrc.cantidadentregada) Cantidad_vendida,\r\nCONCAT(\"$\",SUM(lrc.cantidadentregada*o.precio)) as Subtotal','sms_liquidacion_ruta_cliente lrc,\r\nsms_ruta_oferta_cliente roc,\r\nsms_oferta_cliente oc ,\r\nsms_oferta o,\r\nsms_ruta_oferta ro,\r\ntrt_unidades tu,\r\ntrt_unidad_marca tum,\r\ntrt_unidad_tipo ut,\r\ntrt_capacidad_unidad cu,\r\ncomun_cliente cc,\r\nmrp_producto p,\r\nmrp_unidades u,\r\ntrt_transportistas tt',' tt.id=tu.transport AND\r\nu.idUni=o.idUnidad AND\r\np.idProducto=o.idProducto AND\r\ncc.id=oc.idCliente AND\r\nroc.id=lrc.idRutaOfertaCliente  AND\r\nroc.idOfertacliente=oc.id AND\r\no.idOferta=oc.idOferta AND \r\nro.id=roc.idRutaOferta AND\r\ntu.id=ro.idTransporte AND\r\ntum.id=tu.marca AND\r\nut.id=tu.tipo AND cu.id=tu.capacidad ','ro.idTransporte\r\n','','o.fechaCreacion desc','','','','','Subtotal'),
	(42,'cont_diario','Contabilidad - Hoja de Diario','2013-11-27 19:17:26','2013-11-28 00:38:56','G',1,'m.NumMovto AS \"# Movimiento\",\r\np.fecha_creacion AS Fecha,\r\np.concepto AS \"Poliza\",\r\ntp.titulo AS \"Tipo\",\r\nm.Concepto AS \"Concepto\",\r\nc.description AS \"Cuenta\",\r\nif(m.TipoMovto = \"Cargo\",m.Importe,\"&nbsp;\") AS Cargo,\r\nif(m.TipoMovto = \"Abono\",m.Importe,\"&nbsp;\") AS Abono','cont_movimientos m\r\nINNER JOIN cont_polizas p ON m.IdPoliza = p.id AND p.Activo = 1 AND p.eliminado = 0 \r\nINNER JOIN cont_accounts c ON m.Cuenta = c.account_id\r\nINNER JOIN cont_tipos_poliza tp ON tp.id = p.idtipopoliza','m.Activo = 1\r\nAND p.id = [@Poliza;val;des;SELECT 0 AS val, \"Todos\" AS des UNION SELECT id AS val,UPPER(concepto) AS des FROM cont_polizas WHERE activo = 1 AND eliminado = 0]\r\nAND p.fecha_creacion BETWEEN \"[#Periodo Inicial]\" AND \"[#Periodo Final]\"','','','','../../modulos/cont_repolog/diario_antes.php','../../modulos/cont_repolog/diario_despues.php','Poliza','suma(Cargo),suma(Abono)','Poliza'),
	(43,'Reporte Ventas No Facturadas','Reporte Ventas No Facturadas','2013-11-28 22:01:08','2013-11-28 23:02:36','A',1,'b.idVenta Id, b.fecha Fecha, b.monto Monto, c.nombre Nombre, a.factNum TrackId','pvt_pendienteFactura a inner join venta b on  b.idVenta=a.id_sale left join comun_cliente c on c.id=a.id_cliente','a.facturado=0','','','a.fecha asc','../../modulos/facturacion/antesVentas.php','','','',''),
	(44,'TRT Reportes Control de convenios','TRT Reportes Control de convenios','2013-11-29 15:56:13','2013-11-29 15:58:19','A',1,'','','','','','','','','','',''),
	(45,'TRT Reporte Distribucion de Gastos','TRT Reporte Distribucion de Gastos','2013-11-29 16:15:54','2013-11-29 17:51:26','A',1,'a.id','trt_transportistas a','','','','','','','','',''),
	(47,'cont_anexos','Contabilidad - Anexos de Catalogo','2013-12-04 01:42:18','2013-12-04 01:42:18','G',1,'*','cont_movimientos ','[@Ejercicio;val;des;SELECT id as val, NombreEjercicio AS des FROM cont_ejercicios] > 0\r\nAND [@Periodo Inicial;val;des; select \"01\" AS val , \"Enero\" AS des UNION select \"02\" AS val , \"Febrero\" AS des UNION select \"03\" AS val , \"Marzo\" AS des UNION select \"04\" AS val , \"Abril\" AS des UNION select \"05\" AS val , \"Mayo\" AS des UNION select \"06\" AS val , \"Junio\" AS des UNION select \"07\" AS val , \"Julio\" AS des UNION select \"08\" AS val , \"Agosto\" AS des UNION select \"09\" AS val , \"Septiembre\" AS des UNION select \"10\" AS val , \"Octubre\" AS des UNION select \"11\" AS val , \"Noviembre\" AS des UNION select \"12\" AS val , \"Diciembre\" AS des] > 0 \r\nAND [@Periodo Final;val;des; select \"01\" AS val , \"Enero\" AS des UNION select \"02\" AS val , \"Febrero\" AS des UNION select \"03\" AS val , \"Marzo\" AS des UNION select \"04\" AS val , \"Abril\" AS des UNION select \"05\" AS val , \"Mayo\" AS des UNION select \"06\" AS val , \"Junio\" AS des UNION select \"07\" AS val , \"Julio\" AS des UNION select \"08\" AS val , \"Agosto\" AS des UNION select \"09\" AS val , \"Septiembre\" AS des UNION select \"10\" AS val , \"Octubre\" AS des UNION select \"11\" AS val , \"Noviembre\" AS des UNION select \"12\" AS val , \"Diciembre\" AS des] > 0 \r\nAND [@A nivel de:;val;des;SELECT \"1\" AS val , \"Cuentas Afectables\" AS des UNION SELECT \"1\" AS val, \"Cuentas de Mayor\" AS des]','','','','../../modulos/cont_repolog/anexos_antes.php','../../modulos/cont_repolog/anexos_despues.php','Cuenta_de_Mayor','suma(Abonos),suma(Cargos)','Cuenta_de_Mayor'),
	(48,'prueba-14-41','prueba-14-41','2013-12-10 20:41:42','2013-12-10 20:41:42','G',1,'','','','','','','','','','',''),
	(49,'Reporte Ventas No Facturadas (REST)','Reporte Ventas No Facturadas (REST)','2013-12-17 21:16:51','2013-12-17 21:18:26','A',1,'b.sale_id Id, b.sale_time Fecha, b.payment_type Monto','rest_pvt_pendienteFactura a\r\ninner join rest_sales b on  b.sale_id=a.id_sale','a.facturado=0','','','b.sale_time desc','../../modulos/facturacionRest/antesVentasREST.php','','','',''),
	(50,'tra_reportes_utilidad','TRA Reportes Utilidad','2013-12-18 23:26:41','2013-12-19 00:06:09','A',1,'','','','','','','','','','',''),
	(51,'TRA Reportes Utilidad','TRA Reportes Utilidad','2013-12-19 00:04:36','2013-12-28 00:41:06','A',1,'a.os, b.nombre cliente, c.operador operador, d.unidad unidad, e.transportista transportista, a.destino, a.subtotal, a.total, a.utilidad, a.fecha','tra_reporte_utilidad a\r\nleft join comun_cliente b on a.cliente=b.id\r\nleft join tra_operadores c on c.id=a.operador\r\nleft join tra_unidad d on d.id=a.unidad\r\nleft join tra_transportistas e on e.id=a.transportista','a.cliente=[@Cliente;id;nombre;select id,nombre from comun_cliente] and a.transportista=[@Transportista;id;transportista;select id,transportista from tra_transportistas] and a.operador=[@Operador;id;operador;select id,operador from tra_operadores] and a.unidad=[@Unidad;id;nEconomico;select id,nEconomico from tra_unidades]','','','','','','','',''),
	(52,'rcxc','Reporte Cuentas Por Cobrar','2014-01-07 17:05:52','2014-01-08 17:03:47','A',1,'c.idCxc ID,cc.nombre Nombre,c.concepto,c.idVenta FolioV,c.monto,c.saldoabonado,format(c.saldoactual,2) SaldoActual,c.estatus,c.fechacargo,c.fechavencimiento','cxc c,comun_cliente cc','cc.id=c.idCliente and   c.estatus= 0  ','','','','','','','suma(SaldoActual)','Clientes'),
	(53,'rcxp','Reporte cuentas por Pagar','2014-01-07 22:03:44','2014-01-07 22:13:30','A',1,'c.idCxp ID,c.concepto,c.monto,c.saldoabonado,format(c.saldoactual,2) SaldoActual,c.estatus,c.fechacargo,c.fechavencimiento\r\n','cxp c','c.estatus= 0','','','','','','','suma(SaldoActual)','Saldos actuales'),
	(55,'Devoluciones a Proveedores Listado','Devoluciones a Proveedores Listado','2014-02-11 22:03:54','2014-02-21 23:52:40','A',1,'a.id Id, a.nDevoluciones Numero_Devoluciones, d.nombre Producto, b.razon_social Proveedor, c.nombre Almacen, a.fechaDevolucion Fecha_Devolucion, CASE a.estatus WHEN 0 THEN CONCAT(\'<a href=\\\"javascript:void(0)\\\" onclick=\\\"confirmar(\',a.id,\')\\\">Confirmar?</a> / <a href=\\\"javascript:void(0)\\\" onclick=\\\"cancelar(\',a.id,\')\\\">Cancelar</a>\') WHEN 1 THEN \'Confirmada\' WHEN 2 THEN \'Cancelada\' END as Estatus','mrp_devoluciones_reporte a\r\ninner join mrp_proveedor b on b.idPrv=a.idProveedor\r\ninner join almacen c on c.idAlmacen=a.idAlmacen\r\ninner join mrp_producto d on d.idProducto=a.idProducto','a.idProveedor=[@Proveedor;idPrv;razon_social;select \"0 OR a.idProveedor is not null\" as \"idPrv\",\"Todos\" as \"razon_social\" union all select idPrv,razon_social from mrp_proveedor] and \na.idAlmacen=[@Almacen;idAlmacen;nombre;select \"0 OR a.idAlmacen is not null\" as \"idAlmacen\",\"Todos\" as \"nombre\" union all select idAlmacen,nombre from almacen] and \na.estatus=[@Estatus;pestatus;nestatus;select 0 as \"pestatus\",\"Pendientes\" as \"nestatus\" union all select 1 as \"pestatus\",\"Confirmados\" as \"nestatus\" union all select 2 as \"pestatus\",\"Cancelados\" as \"nestatus\"] and\nDATE_FORMAT(a.fechaDevolucion,\"%Y-%m-%d\")>=\"[#Fecha inicio]\" and \nDATE_FORMAT(a.fechaDevolucion,\"%Y-%m-%d\")<=\"[#Fecha fin]\"','a.id desc','','','../../modulos/devoluciones/antes_devoluciones.php','','','',''),
	(58,'Reporte Costo y Precio','Reporte Costo y Precio','2014-06-26 16:50:27','2014-06-27 14:12:13','A',1,'a.idProducto id, a.nombre producto, a.precioventa PrecioVenta, if((select ultCosto from mrp_producto_orden_compra where idProducto = a.idProducto order by idPrOr desc limit 1) is null, a.costo,(select ultCosto from mrp_producto_orden_compra where idProducto = a.idProducto order by idPrOr desc limit 1)) UltCosto','mrp_producto a \r\ninner join mrp_linea b on b.idLin=a.idLinea\r\ninner join mrp_familia c on c.idFam=b.idFam\r\ninner join mrp_departamento d on d.idDep=c.idDep','a.idProveedor=[@Proveedor;idPrv;razon_social;select \"0 OR a.idProveedor is not null\" as \"idPrv\",\"Todos\" as \"razon_social\" union all select idPrv,razon_social from mrp_proveedor] and \r\n\r\na.idLinea=[@Linea;idLin;nombre;select \"0 OR a.idLinea is not null\" as \"idLin\",\"Todos\" as \"nombre\" union all select idLin,nombre from mrp_linea] and\r\n\r\nb.idFam=[@Familia;idFam;nombre;select \"0 OR b.idFam is not null\" as \"idFam\",\"Todos\" as \"nombre\" union all select idFam,nombre from mrp_familia] and\r\n\r\nc.idDep=[@Departamento;idDep;nombre;select \"0 OR c.idDep is not null\" as \"idDep\",\"Todos\" as \"nombre\" union all select idDep,nombre from mrp_departamento]','','','','','','','',''),
	(59,'cont_auxiliar_catalogo','Anexos de Catálogo','2013-11-20 12:26:46','2014-08-28 16:44:17','A',1,'*','cont_movimientos ','[@Ejercicio;val;des;SELECT id as val, NombreEjercicio AS des FROM cont_ejercicios] > 0\nAND [@Periodo Inicial;val;des; select \"01\" AS val , \"Enero\" AS des UNION select \"02\" AS val , \"Febrero\" AS des UNION select \"03\" AS val , \"Marzo\" AS des UNION select \"04\" AS val , \"Abril\" AS des UNION select \"05\" AS val , \"Mayo\" AS des UNION select \"06\" AS val , \"Junio\" AS des UNION select \"07\" AS val , \"Julio\" AS des UNION select \"08\" AS val , \"Agosto\" AS des UNION select \"09\" AS val , \"Septiembre\" AS des UNION select \"10\" AS val , \"Octubre\" AS des UNION select \"11\" AS val , \"Noviembre\" AS des UNION select \"12\" AS val , \"Diciembre\" AS des] > 0 \nAND [@Periodo Final;val;des; select \"01\" AS val , \"Enero\" AS des UNION select \"02\" AS val , \"Febrero\" AS des UNION select \"03\" AS val , \"Marzo\" AS des UNION select \"04\" AS val , \"Abril\" AS des UNION select \"05\" AS val , \"Mayo\" AS des UNION select \"06\" AS val , \"Junio\" AS des UNION select \"07\" AS val , \"Julio\" AS des UNION select \"08\" AS val , \"Agosto\" AS des UNION select \"09\" AS val , \"Septiembre\" AS des UNION select \"10\" AS val , \"Octubre\" AS des UNION select \"11\" AS val , \"Noviembre\" AS des UNION select \"12\" AS val , \"Diciembre\" AS des] > 0 \nAND [@Cierre del ejercicio:;nc;dc;SELECT \"0\" AS nc , \"No\" AS dc UNION SELECT \"1\" AS nc, \"Si\" AS dc] >=0','','','','../../modulos/cont_repolog/auxiliares_antes.php','../../modulos/cont_repolog/auxiliares_despues.php','Clasificacion,Cuenta_de_Mayor,Cuenta','suma(Abonos),suma(Cargos)','Cuenta'),
	(61,'cont_libro_diario','Libro de Diario','2015-01-14 22:30:55','2015-02-03 17:46:49','A',1,'m.NumMovto AS NUM_MOVIMIENTO,a.manual_code AS \"Codigo manual\",p.fecha AS FECHA,a.description AS CUENTA,CONCAT(p.numpol,\'/\',p.Concepto) AS POLIZA,(select titulo From cont_tipos_poliza WHERE id=p.idtipopoliza) AS TIPO_POLIZA,m.Referencia AS REFERENCIA_MOV,m.Concepto AS CONCEPTO_MOV,  (select format(Importe,2) From cont_movimientos WHERE TipoMovto=\"Cargo\" AND Id = m.Id) AS CARGO, (select format(Importe,2) From cont_movimientos WHERE TipoMovto=\"Abono\" AND Id = m.Id) AS ABONO','cont_movimientos m INNER JOIN cont_polizas p ON p.id = m.IdPoliza INNER JOIN cont_accounts a ON a.account_id = m.Cuenta','p.fecha BETWEEN \'[#Del]\' AND \'[#Al]\' \nAND SSaaldo=[@Con Saldos;val;des;SELECT \'0\' AS val, \'No\' AS des UNION SELECT \'1\' AS val, \'Si\' AS des ]\nAND p.activo = 1 AND m.Activo = 1 AND p.idperiodo != 13','','','FECHA,TIPO_POLIZA,numpol,p.idperiodo,NUM_MOVIMIENTO','../../modulos/cont_repolog/polizas_antes.php','../../modulos/cont_repolog/librodiario_despues.php','POLIZA,TIPO_POLIZA,FECHA','suma(ABONO),suma(CARGO)','POLIZA'),
	(66,'Comandas Reporte','Comadas Reporte','2015-07-20 16:42:22','2015-07-20 20:19:12','A',1,'c.id,c.idmesa, c.personas,if(c.tipo=0, \'Mesa\', \'Para llevar\') as tipo,\n(CASE c.status \n	WHEN 0 THEN\n		\'Abierta\'\n	WHEN 2 THEN\n		\'Cerrada\'\n	WHEN 3 THEN\n		\'Eliminada\'\n	ELSE \'---\' END) as status,\nu.usuario,c.codigo,c.timestamp','com_comandas c , accelog_usuarios u','c.idempleado=u.idempleado and timestamp BETWEEN \'[#Del] [Desde (00:01)]\' AND \'[#Al] [Hasta (23:59)]\'','','','','../../modulos/restaurantes/views/comandas/imprimecomandas.php','','','','');

/*!40000 ALTER TABLE `repolog_reportes` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla seguimiento_inovekia
# ------------------------------------------------------------

DROP TABLE IF EXISTS `seguimiento_inovekia`;

CREATE TABLE `seguimiento_inovekia` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_empleado` bigint(20) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `ultimo_slide` int(11) NOT NULL,
  `seguimiento` longtext NOT NULL,
  `latitud` double NOT NULL,
  `longitud` double NOT NULL,
  `creado` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Volcado de tabla sucursales
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sucursales`;

CREATE TABLE `sucursales` (
  `idSuc` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) DEFAULT NULL,
  `idOrg` int(11) NOT NULL,
  PRIMARY KEY (`idSuc`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `sucursales` WRITE;
/*!40000 ALTER TABLE `sucursales` DISABLE KEYS */;

INSERT INTO `sucursales` (`idSuc`, `nombre`, `idOrg`)
VALUES
	(1,'Guadalajara',1);

/*!40000 ALTER TABLE `sucursales` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla tarjeta_regalo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tarjeta_regalo`;

CREATE TABLE `tarjeta_regalo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) DEFAULT NULL,
  `valor` float(10,2) DEFAULT NULL,
  `usada` int(11) DEFAULT NULL,
  `montousado` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreunico` (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla tipo_proveedor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tipo_proveedor`;

CREATE TABLE `tipo_proveedor` (
  `idtipo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idtipo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `tipo_proveedor` WRITE;
/*!40000 ALTER TABLE `tipo_proveedor` DISABLE KEYS */;

INSERT INTO `tipo_proveedor` (`idtipo`, `tipo`)
VALUES
	(1,'Proveedor'),
	(2,'Deudor'),
	(3,'Acreedor');

/*!40000 ALTER TABLE `tipo_proveedor` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla unid_generica
# ------------------------------------------------------------

DROP TABLE IF EXISTS `unid_generica`;

CREATE TABLE `unid_generica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `identificadores` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `permiso` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `unid_generica` WRITE;
/*!40000 ALTER TABLE `unid_generica` DISABLE KEYS */;

INSERT INTO `unid_generica` (`id`, `tipo`, `identificadores`, `permiso`)
VALUES
	(1,'Unidad','1',0),
	(2,'Peso','2,3,4,5',0),
	(3,'Area','6,7,8,9',0),
	(4,'Longitud','10,11,12,13,14',0),
	(5,'Tiempo','15,16,17,18,19',0),
	(6,'Capacidad','20,21',0);

/*!40000 ALTER TABLE `unid_generica` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla venta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `venta`;

CREATE TABLE `venta` (
  `idVenta` int(11) NOT NULL AUTO_INCREMENT,
  `idCliente` int(11) DEFAULT NULL,
  `monto` float(10,2) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  `rfc` varchar(45) DEFAULT NULL,
  `documento` varchar(100) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `cambio` float(10,2) DEFAULT NULL,
  `montoimpuestos` float(10,2) DEFAULT NULL,
  `idSucursal` int(11) DEFAULT NULL,
  `observacion` longtext,
  `envio` int(11) DEFAULT '0',
  PRIMARY KEY (`idVenta`),
  KEY `idSucursal` (`idSucursal`),
  KEY `idCliente` (`idCliente`),
  KEY `idEmpleado` (`idEmpleado`),
  CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`idSucursal`) REFERENCES `mrp_sucursal` (`idSuc`),
  CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`idCliente`) REFERENCES `comun_cliente` (`id`),
  CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`idEmpleado`) REFERENCES `empleados` (`idempleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla venta_pagos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `venta_pagos`;

CREATE TABLE `venta_pagos` (
  `idventa_pagos` int(11) NOT NULL AUTO_INCREMENT,
  `idVenta` int(11) DEFAULT NULL,
  `idFormapago` int(11) DEFAULT NULL,
  `monto` float(10,2) DEFAULT NULL,
  `referencia` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idventa_pagos`),
  KEY `idFormapago` (`idFormapago`),
  KEY `idVenta` (`idVenta`),
  CONSTRAINT `venta_pagos_ibfk_1` FOREIGN KEY (`idFormapago`) REFERENCES `forma_pago` (`idFormapago`),
  CONSTRAINT `venta_pagos_ibfk_2` FOREIGN KEY (`idVenta`) REFERENCES `venta` (`idVenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla venta_producto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `idventa_producto` int(11) NOT NULL AUTO_INCREMENT,
  `idProducto` int(11) DEFAULT NULL,
  `cantidad` float(10,2) DEFAULT NULL,
  `preciounitario` float(10,2) DEFAULT NULL,
  `tipodescuento` varchar(4) DEFAULT '$',
  `descuento` float(10,2) DEFAULT NULL,
  `subtotal` float(10,2) DEFAULT NULL,
  `idVenta` int(11) DEFAULT NULL,
  `impuestosproductoventa` float(10,2) DEFAULT NULL,
  `montodescuento` float(10,2) DEFAULT NULL,
  `total` float(10,2) DEFAULT NULL,
  `arr_kit` longtext,
  `comentario` longtext,
  PRIMARY KEY (`idventa_producto`),
  KEY `idProducto` (`idProducto`),
  KEY `idVenta` (`idVenta`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `mrp_producto` (`idProducto`),
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`idVenta`) REFERENCES `venta` (`idVenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla venta_producto_impuesto
# ------------------------------------------------------------

DROP TABLE IF EXISTS `venta_producto_impuesto`;

CREATE TABLE `venta_producto_impuesto` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idVentaproducto` int(11) DEFAULT NULL,
  `idImpuesto` int(11) DEFAULT NULL,
  `porcentaje` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idImpuesto` (`idImpuesto`),
  KEY `idVentaproducto` (`idVentaproducto`),
  CONSTRAINT `venta_producto_impuesto_ibfk_1` FOREIGN KEY (`idImpuesto`) REFERENCES `impuesto` (`id`),
  CONSTRAINT `venta_producto_impuesto_ibfk_2` FOREIGN KEY (`idVentaproducto`) REFERENCES `venta_producto` (`idventa_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla venta_retiro_caja
# ------------------------------------------------------------

DROP TABLE IF EXISTS `venta_retiro_caja`;

CREATE TABLE `venta_retiro_caja` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cantidad` decimal(20,6) DEFAULT NULL,
  `concepto` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `idempleado` int(11) DEFAULT '0',
  `fecha` datetime DEFAULT NULL,
  `idcorte` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla venta_suspendida
# ------------------------------------------------------------

DROP TABLE IF EXISTS `venta_suspendida`;

CREATE TABLE `venta_suspendida` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_almacen` varchar(45) DEFAULT NULL,
  `s_cambio` varchar(45) DEFAULT NULL,
  `s_cliente` varchar(45) DEFAULT NULL,
  `s_documento` varchar(45) DEFAULT NULL,
  `s_empleado` varchar(45) DEFAULT NULL,
  `s_funcion` varchar(45) DEFAULT NULL,
  `s_idFact` varchar(45) DEFAULT NULL,
  `s_impuestos` varchar(45) DEFAULT NULL,
  `s_monto` varchar(45) DEFAULT NULL,
  `s_pagoautomatico` varchar(45) DEFAULT NULL,
  `s_sucursal` varchar(45) DEFAULT NULL,
  `s_impuestost` varchar(45) DEFAULT '0',
  `arreglo1` varchar(10000) DEFAULT NULL,
  `arreglo2` varchar(10000) DEFAULT NULL,
  `identi` varchar(150) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `borrado` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla vista_comun_facturacion
# ------------------------------------------------------------

DROP VIEW IF EXISTS `vista_comun_facturacion`;

CREATE TABLE `vista_comun_facturacion` (
   `idfacturacion` INT(11) NOT NULL DEFAULT '0',
   `id` INT(11) NULL DEFAULT NULL,
   `rfc` VARCHAR(15) NULL DEFAULT NULL,
   `razon_social` VARCHAR(100) NULL DEFAULT NULL,
   `correo` VARCHAR(100) NULL DEFAULT NULL,
   `pais` VARCHAR(100) NULL DEFAULT NULL,
   `regimen_fiscal` VARCHAR(100) NULL DEFAULT NULL,
   `domicilio` VARCHAR(200) NULL DEFAULT NULL,
   `num_ext` VARCHAR(20) NULL DEFAULT NULL,
   `cp` VARCHAR(10) NULL DEFAULT NULL,
   `colonia` VARCHAR(100) NULL DEFAULT NULL,
   `idestado` INT(11) NULL DEFAULT NULL,
   `ciudad` VARCHAR(100) NULL DEFAULT NULL,
   `municipio` VARCHAR(100) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Volcado de tabla vista_empleados
# ------------------------------------------------------------

DROP VIEW IF EXISTS `vista_empleados`;

CREATE TABLE `vista_empleados` (
   `idempleado` INT(11) NOT NULL DEFAULT '0',
   `nombre` VARCHAR(137) NOT NULL DEFAULT ''
) ENGINE=MyISAM;



# Volcado de tabla vista_estados
# ------------------------------------------------------------

DROP VIEW IF EXISTS `vista_estados`;

CREATE TABLE `vista_estados` (
   `idestadosat` INT(11) NOT NULL DEFAULT '0',
   `estado` VARCHAR(50) NULL DEFAULT NULL,
   `idpais` INT(11) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Volcado de tabla vista_perfiles
# ------------------------------------------------------------

DROP VIEW IF EXISTS `vista_perfiles`;

CREATE TABLE `vista_perfiles` (
   `idperfil` INT(11) NOT NULL DEFAULT '0',
   `nombre` VARCHAR(50) NULL DEFAULT NULL
) ENGINE=MyISAM;





# Replace placeholder table for cont_view_libro_mayor with correct view syntax
# ------------------------------------------------------------

DROP TABLE `cont_view_libro_mayor`;

CREATE ALGORITHM=UNDEFINED DEFINER=`nmdevel`@`%` SQL SECURITY DEFINER VIEW `cont_view_libro_mayor`
AS SELECT
   `m`.`Id` AS `Mov_Id`,(case when (`a`.`account_code` like '1%') then 'Activo' when (`a`.`account_code` like '2%') then 'Pasivo' when (`a`.`account_code` like '3%') then 'Capital' when (`a`.`account_code` like '4%') then 'Resultados' end) AS `Clasificacion`,(case when (`a`.`account_code` like '1%') then 1 when (`a`.`account_code` like '2%') then 2 when (`a`.`account_code` like '3%') then 3 when (`a`.`account_code` like '4%') then 4 end) AS `Num_Clasif`,(select concat(`cont_accounts`.`description`,' (',`cont_accounts`.`manual_code`,')')
FROM `cont_accounts` where (`cont_accounts`.`account_id` = `a`.`main_father`)) AS `Cuenta_de_Mayor`,ucase(`a`.`account_code`) AS `Code`,ucase(`a`.`manual_code`) AS `Manual_Code`,ucase(`n`.`description`) AS `Naturaleza`,`p`.`fecha` AS `Fecha`,ucase(`p`.`concepto`) AS `Poliza`,concat(`a`.`description`,' (',`a`.`manual_code`,')') AS `Cuenta`,if((`m`.`TipoMovto` = 'CARGO'),`m`.`Importe`,0) AS `Cargos`,if((`m`.`TipoMovto` = 'ABONO'),`m`.`Importe`,0) AS `Abonos`,'SALDOS INICIALES' AS `Flag`,`p`.`idperiodo` AS `idperiodo`,(select `b`.`manual_code` from `cont_accounts` `b` where (`b`.`account_id` = `a`.`main_father`)) AS `Codigo_Cuenta_de_Mayor` from (((`cont_movimientos` `m` join `cont_accounts` `a` on((`m`.`Cuenta` = `a`.`account_id`))) join `cont_nature` `n` on((`a`.`account_nature` = `n`.`nature_id`))) join `cont_polizas` `p` on((`m`.`IdPoliza` = `p`.`id`))) where ((`m`.`Activo` = 1) and (`p`.`activo` = 1) and ((`p`.`idperiodo` < 13) or ((`p`.`idperiodo` = 13) and `a`.`account_id` in (select `cont_accounts`.`account_id` from `cont_accounts` where (`cont_accounts`.`main_father` = (select `cont_config`.`CuentaSaldos` from `cont_config`))))));


# Replace placeholder table for vista_estados with correct view syntax
# ------------------------------------------------------------

DROP TABLE `vista_estados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`nmdevel`@`%` SQL SECURITY DEFINER VIEW `vista_estados`
AS SELECT
   `estados`.`idestado` AS `idestadosat`,
   `estados`.`estado` AS `estado`,
   `estados`.`idpais` AS `idpais`
FROM `estados`;


# Replace placeholder table for cont_view_init_balance with correct view syntax
# ------------------------------------------------------------

DROP TABLE `cont_view_init_balance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`nmdevel`@`%` SQL SECURITY DEFINER VIEW `cont_view_init_balance`
AS SELECT
   (case when (`a`.`account_code` like '1%') then 'Activo' when (`a`.`account_code` like '2%') then 'Pasivo' when (`a`.`account_code` like '3%') then 'Capital' when (`a`.`account_code` like '4%') then 'Resultados' end) AS `Clasificacion`,(select `cont_accounts`.`description`
FROM `cont_accounts` where (`cont_accounts`.`account_id` = `a`.`main_father`)) AS `Cuenta_de_Mayor`,ucase(`a`.`account_code`) AS `Code`,ucase(`n`.`description`) AS `Naturaleza`,`p`.`fecha` AS `Fecha`,ucase(`p`.`concepto`) AS `Poliza`,`a`.`description` AS `Cuenta`,if((`m`.`TipoMovto` = 'CARGO'),`m`.`Importe`,0) AS `Cargos`,if((`m`.`TipoMovto` = 'ABONO'),`m`.`Importe`,0) AS `Abonos`,'SALDOS INICIALES' AS `Flag` from (((`cont_movimientos` `m` join `cont_accounts` `a` on((`m`.`Cuenta` = `a`.`account_id`))) join `cont_nature` `n` on((`a`.`account_nature` = `n`.`nature_id`))) join `cont_polizas` `p` on((`m`.`IdPoliza` = `p`.`id`))) where ((`m`.`Activo` = 1) and (`p`.`activo` = 1)) group by `m`.`Cuenta`,`m`.`TipoMovto`;


# Replace placeholder table for cont_view_init_balance2 with correct view syntax
# ------------------------------------------------------------

DROP TABLE `cont_view_init_balance2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`nmdevel`@`%` SQL SECURITY DEFINER VIEW `cont_view_init_balance2`
AS SELECT
   (case when (`a`.`account_code` like '1%') then 'Activo' when (`a`.`account_code` like '2%') then 'Pasivo' when (`a`.`account_code` like '3%') then 'Capital' when (`a`.`account_code` like '4%') then 'Resultados' end) AS `Clasificacion`,(select `cont_accounts`.`sec_desc`
FROM `cont_accounts` where ((`cont_accounts`.`account_code` = `a`.`account_type`) and (`cont_accounts`.`removed` = 0))) AS `Clasificacion_Alt`,(select concat(`cont_accounts`.`manual_code`,' / ',`cont_accounts`.`description`) from `cont_accounts` where (`cont_accounts`.`account_id` = `a`.`main_father`)) AS `Cuenta_de_Mayor`,(select concat(`cont_accounts`.`manual_code`,' / ',`cont_accounts`.`sec_desc`) from `cont_accounts` where (`cont_accounts`.`account_id` = `a`.`main_father`)) AS `Cuenta_de_Mayor_Alt`,ucase(`a`.`account_code`) AS `Code`,ucase(`n`.`description`) AS `Naturaleza`,`p`.`fecha` AS `Fecha`,ucase(`p`.`concepto`) AS `Poliza`,`a`.`description` AS `Cuenta`,`a`.`sec_desc` AS `Cuenta_Alt`,if((`m`.`TipoMovto` = 'CARGO'),`m`.`Importe`,0) AS `Cargos`,if((`m`.`TipoMovto` = 'ABONO'),`m`.`Importe`,0) AS `Abonos`,'SALDOS INICIALES' AS `Flag`,`p`.`idperiodo` AS `idperiodo`,`m`.`IdSegmento` AS `idsegmento`,`m`.`IdSucursal` AS `idsucursal` from (((`cont_movimientos` `m` join `cont_accounts` `a` on((`m`.`Cuenta` = `a`.`account_id`))) join `cont_nature` `n` on((`a`.`account_nature` = `n`.`nature_id`))) join `cont_polizas` `p` on((`m`.`IdPoliza` = `p`.`id`))) where ((`m`.`Activo` = 1) and (`p`.`activo` = 1));


# Replace placeholder table for vista_empleados with correct view syntax
# ------------------------------------------------------------

DROP TABLE `vista_empleados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`nmdevel`@`%` SQL SECURITY DEFINER VIEW `vista_empleados`
AS SELECT
   `e`.`idempleado` AS `idempleado`,concat(`e`.`nombre`,_utf8' ',`e`.`apellido1`,_utf8' ',`e`.`apellido2`) AS `nombre`
FROM `empleados` `e` where (`e`.`visible` = -(1));


# Replace placeholder table for mrp_vista_unidades with correct view syntax
# ------------------------------------------------------------

DROP TABLE `mrp_vista_unidades`;

CREATE ALGORITHM=UNDEFINED DEFINER=`nmdevel`@`%` SQL SECURITY DEFINER VIEW `mrp_vista_unidades`
AS SELECT
   `mrp_unidades`.`idUni` AS `idUnidad`,
   `mrp_unidades`.`compuesto` AS `compuesto_descripcion`,
   `mrp_unidades`.`conversion` AS `conversion`,
   `mrp_unidades`.`unidad` AS `unidad`
FROM `mrp_unidades`;


# Replace placeholder table for vista_comun_facturacion with correct view syntax
# ------------------------------------------------------------

DROP TABLE `vista_comun_facturacion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`nmdevel`@`%` SQL SECURITY DEFINER VIEW `vista_comun_facturacion` AS (select `comun_facturacion`.`id` AS `idfacturacion`,`comun_facturacion`.`nombre` AS `id`,`comun_facturacion`.`rfc` AS `rfc`,`comun_facturacion`.`razon_social` AS `razon_social`,`comun_facturacion`.`correo` AS `correo`,`comun_facturacion`.`pais` AS `pais`,`comun_facturacion`.`regimen_fiscal` AS `regimen_fiscal`,`comun_facturacion`.`domicilio` AS `domicilio`,`comun_facturacion`.`num_ext` AS `num_ext`,`comun_facturacion`.`cp` AS `cp`,`comun_facturacion`.`colonia` AS `colonia`,`comun_facturacion`.`estado` AS `idestado`,`comun_facturacion`.`ciudad` AS `ciudad`,`comun_facturacion`.`municipio` AS `municipio` from `comun_facturacion`);


# Replace placeholder table for vista_perfiles with correct view syntax
# ------------------------------------------------------------

DROP TABLE `vista_perfiles`;

CREATE ALGORITHM=UNDEFINED DEFINER=`nmdevel`@`%` SQL SECURITY DEFINER VIEW `vista_perfiles`
AS SELECT
   `p`.`idperfil` AS `idperfil`,
   `p`.`nombre` AS `nombre`
FROM `accelog_perfiles` `p` where (`p`.`visible` = -(1));

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
